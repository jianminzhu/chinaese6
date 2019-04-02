<?php

namespace app\index\controller;

use app\index\model\Member;
use think\Controller;
use think\Db;

include_once "ext_util/pinyin.php";

class Base extends Controller
{
    public function refreshVipInfo($dbMember)
    {
        if ($dbMember) {
            list($vip, $isPay) =  $this->vipInfo($dbMember["id"]);
            $dbMember->vip = $vip;
            $dbMember->isPay = $isPay;
        }
        return $dbMember;
    }
    /**
     * @param $id
     * @return array
     */
    public function vipInfo($id)
    {
        $isPay = false;
        $vip = [
            "type" => "no",
            "renge" => ""
        ];
        $vips = Db::query(" SELECT *, TIMESTAMPDIFF(DAY,startdate,enddate)AS remaining FROM pay where m_id=$id order by enddate desc ");
        if ($vips) {
            $vip = $vips[0];
            $vip["renge"] = $vip["cost"] > 199 ? lang("终身VIP会员") : lang("1年 VIP 会员");
            $vip["type"] = $vip["cost"] > 199 ? "lifetime" : "1year";
            $isPay = true;
        }

        return array($vip, $isPay);
    }
    public function render($tpl, $data)
    {
        return view($tpl, $data)->getContent();
    }

    public static function ajax($isSuccess, $data = [], $msg = "")
    {
        return json(["isSuccess" => $isSuccess, "msg" => $msg, "data" => $data]);
    }

    public function memberIsPay($mid)
    {
        $isPay = false;
        if ($mid != null) {
            try {
                $query = Db::table("pay");
                $count = $query->where("m_id", $mid)->count("m_id");
                $isPay = $count > 0;
            } catch (\Exception $e) {
            }
        }
        return $isPay;
    }

    public function changeSessionLoginUser($member)
    {
        session("loginUser", $member);
    }

    public function ajaxIsPay()
    {
        return $this->ajax(session("isPay") == true);
    }

    public function ajaxIsLogin()
    {
        return $this->ajax($this->isLogin());
    }

    public function isLogin()
    {
        return $this->hasSessionKey('loginUser');
    }

    public function hasSessionKey($key)
    {
        return session('?' . $key);
    }

    public function loginUser()
    {
        $loginUser = [];
        if ($this->isLogin()) {
            $loginUser = session("loginUser");
        }
        return $loginUser;
    }

    public function headData()
    {
        $toLang = $this->getLang();
        $loginUser = [];
        if ($this->isLogin()) {
            $loginUser = $this->loginUser();
            $this->refreshVipInfo($loginUser);
            session("isPay", db("pay")->where("m_id", $loginUser->id)->count() > 0);
        } else {
            session("isPay", false);
        }
        $arr = [
            'u' => $loginUser,
            'ucounts' => $this->loginUserCounts(),
            "uFavoriteMids" => $loginUser ? Db::table("favorite")->where("mid", $loginUser->id)->column("to_mid") : [],
            "uIntrestMids" => $loginUser ? Db::table("interest")->where("mid", $loginUser->id)->column("to_mid") : [],
            "lang" => $toLang
        ];
        $this->assign($arr);
        return json_encode(['u' => $loginUser,]);
    }

    /**
     * @param $mid
     * @param $cookieLang
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function searchData()
    {
        $this->assign($this->searchWithRequestParam());
    }

    /**
     * @param $cookieLang
     * @param $user
     * @return mixed
     */
    public function predeal($cookieLang, $user, $getPics = false)
    {
        if ($user) {
            if ($cookieLang == "en-us") {
                try {
                    $user["nickname"] = pinyinName($user["nickname"]);
                    $user["address"] = pinyinAddress($user["address"]);
                } catch (\Exception $e) {
                }
            }
            if ($getPics) {
                try {
                    $user["pics"] = Db::table("pics")->where("m_id", $user["id"])->select();
                } catch (\Exception $e) {
                }
            }
        }
        return $user;
    }


    public
    function search()
    {
        $this->searchData();
        return view("/index/search");
    }


    public
    function searchAdmin()
    {
        $this->searchData();
        return view("../../admin/view/page/memberSearchTable");
    }

    public
    function msgs()
    {
        $this->headData();
        return $this->fetch('msglist');
    }

    /**
     * @param $loginUser
     */
    public
    function loginUserCounts()
    {
        $ucounts = [];
        if ($this->isLogin()) {
            $loginUser = $this->loginUser();
            $ucounts = [
                "newMessageCount" => Db::table("message")
                    ->where("to_m_id", $loginUser->id)
                    ->where("type", 2)
                    ->where("read_status", 0)->count(),
                "newInterestsCount" => Db::table("interest")
                    ->where("to_mid", $loginUser->id)
                    ->where("is_view", 0)->count(),
                "newVvistorCount" => Db::table("vistor")->group('mid')
                    ->where("to_mid", $loginUser->id)
                    ->where("is_view", 0)->count(),
                "newFavoritesCount" => Db::table("favorite")
                    ->where("to_mid", $loginUser->id)
                    ->where("is_view", 0)->count(),
                "newFavoritesCountSql" => Db::table("favorite")->fetchSql(true)
                    ->where("to_mid", $loginUser->id)
                    ->where("is_view", 0)->count(),
            ];

        }
        return $ucounts;
    }

    /**
     * @param $mid
     * @return Member
     */
    public
    function getMemberWithWhere($mid)
    {
        $table = Db::table("member");
        $table->where('id', "<>", $mid);

        try {
            $sex = request()->param("sex");
            $age_min = request()->param("age_min");
            $age_max = request()->param("age_max");
            $countryLive = request()->param("countryid");
            $stateLive = request()->param("stateid");
            $cityLive = request()->param("cityid");
            if (trim($age_min) != "" && $age_min != "-1") {
                $table->where("age", ">=", $age_min);
            }
            if (trim($age_max) != "" && $age_max != "-1") {
                $table->where("age", "<=", $age_max);
            }
            if (trim($sex) != "" && $sex != "-1") {
                $table->where("sex", $sex);
            }
            if (trim($countryLive) != "" && $countryLive != "-1") {
                $table->where("countryid", $countryLive);
            }
            if (trim($stateLive) != "" && $stateLive != "-1") {
                $table->where("stateid", $stateLive);
            }
            if (trim($cityLive) != "" && $cityLive != "-1") {
                $table->where("cityid", $cityLive);
            }
        } catch (\Exception $e) {
        }
        return $table;
    }

    /**
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public
    function searchWithRequestParam()
    {
        $cookieLang = cookie("think_var");
        if ($cookieLang) {
            $this->assign("lang", $cookieLang);
        } else {
            $this->assign("lang", "en-us");
        }
        $loginUser = $this->loginUser();
        $mid = 0;
        if ($loginUser) {
            $user = clone $loginUser;
            $this->predeal($cookieLang, $user);
            $mid = $loginUser->id;
        }


        $dbMembers = [];
        $pno = intval(request()->param("pno", 1));
        if ($pno < 0) {
            $pno = 1;
        }
        $lastPno = $pno;
        $nextPno = $pno;

        try {
            $pageSize = intval(request()->param("__psize", 15));
            $count = $this->getMemberWithWhere($mid)->count();
            $lastPno = ceil($count / $pageSize);
            if ($pno >= $lastPno) {
                $pno = $lastPno;
                $nextPno = $pno;
            } else {
                $nextPno = $pno + 1;
            }
            $dbMembers = $this->getMemberWithWhere($mid)->page("$pno,$pageSize")->order("sort asc")->select();
        } catch (Exception $e) {

        }
        $members = [];

        foreach ($dbMembers as $member) {
            $members[] = $this->predeal($cookieLang, $member, request()->param("pics") == "y");
        }
        $data = [
            "members" => $members,
            "pno" => $pno,
            "prevPno" => $pno > 1 ? $pno - 1 : $pno,
            "nextPno" => $nextPno,
            "lastPno" => $lastPno,
            "uFavoriteMids" => $loginUser ? Db::table("favorite")->where("mid", $loginUser->id)->column("to_mid") : [],
            "uIntrestMids" => $loginUser ? Db::table("interest")->where("mid", $loginUser->id)->column("to_mid") : [],
            "piconly" => request()->param("piconly") == "y",

        ];
        return $data;
    }


    /**
     * @return mixed|string
     */
    public
    function getLang()
    {
        $lang = request()->param("lang");
        $toLang = "";
        if ($lang) {
            $toLang = $lang;
        } else {
            $cookieLang = cookie("think_var");
            if ($cookieLang) {
                $toLang = $cookieLang;
            }
        }
        if ($toLang == "zh-cn" || $toLang = "en-us") {
        } else {
            $toLang = "en-us";
        }
        cookie("think_var", $toLang);
        return $toLang;
    }
}





