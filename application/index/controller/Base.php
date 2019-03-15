<?php

namespace app\index\controller;

use think\Controller;
use think\Db;

include_once "ext_util/pinyin.php";

class Base extends Controller
{

    public function ajax($isSuccess, $data = [])
    {
        return json(["isSuccess" => $isSuccess, "data" => $data]);
    }
    public function memberIsPay($mid){
        return Db::table("pay")->where("m_id", $mid)->count() > 0;
    }
    public function ajaxIsPay()
{
    return $this->ajax(session("isPay")==true);
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
        $loginUser =[];
        if ($this->isLogin()) {
            $loginUser = $this->loginUser();
            session("isPay", db("pay")->where("m_id", $loginUser->id)->count()>0);
        }else{
            session("isPay", false);
        }
        $arr = [
            'u' => $loginUser,
            'ucounts' => $this->loginUserCounts(),
            "uFavoriteMids" => $loginUser ? Db::table("favorite")->where("mid", $loginUser->id)->column("to_mid") : [],
            "uIntrestMids" => $loginUser ? Db::table("interest")->where("mid", $loginUser->id)->column("to_mid") : [],
            "lang" => $toLang,
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
            $this->nickNameToPinYing($cookieLang, $user);
            $mid = $loginUser->id;
        }

        $table = Db::table('member');
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
        $dbMembers = [];
        $pno = intval(request()->param("pno", 1));
        if ($pno < 0) {
            $pno = 1;
        }
        $lastPno = $pno;
        $nextPno = $pno;

        try {
            $pageSize = 15;
            $count = $table->count();
            $lastPno = ceil($count / $pageSize);
            if ($pno >= $lastPno) {
                $pno = $lastPno;
                $nextPno = $pno;
            } else {
                $nextPno = $pno + 1;
            }
            $dbMembers = $table->page("$pno,$pageSize")->select();
//            echo $table->getLastSql();
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        $members = [];
        foreach ($dbMembers as $member) {
            $members[] = $this->nickNameToPinYing($cookieLang, $member);
        }
        $this->assign("members", $members);
        $this->assign("pno", $pno);
        $this->assign("prevPno", $pno > 1 ? $pno - 1 : $pno);
        $this->assign("nextPno", $nextPno);
        $this->assign("lastPno", $lastPno);
        $this->assign([

            "uFavoriteMids" => $loginUser ? Db::table("favorite")->where("mid", $loginUser->id)->column("to_mid") : [],
            "uIntrestMids" => $loginUser ? Db::table("interest")->where("mid", $loginUser->id)->column("to_mid") : [],

        ]);

    }

    /**
     * @param $cookieLang
     * @param $user
     * @return mixed
     */
    public function nickNameToPinYing($cookieLang, $user)
    {
        if ($cookieLang == "en-us" && $user) {
            $user["nickname"] = pinyinName($user["nickname"]);
            try {
                $user["address"] = pinyinAddress($user["address"]);
            } catch (\Exception $e) {
            }
        }
        return $user;
    }

    public function search()
    {
        $this->searchData();
        return view("/index/search");
    }

    public function msgs()
    {
        $this->headData();
        return $this->fetch('msglist');
    }

    /**
     * @param $loginUser
     */
    public function loginUserCounts()
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

}





