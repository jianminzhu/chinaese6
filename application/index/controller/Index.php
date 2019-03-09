<?php

namespace app\index\controller;

use app\index\model\Message;
use think\Controller;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

include_once "ext_util/pinyin.php";

class Index extends Controller
{
    public function index()
    {
        $this->headData();
        try {
            $this->searchData();
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return $this->fetch('index');
    }

    public function msgs()
    {
        $this->headData();
        return $this->fetch('msglist');
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
        $cookieLang = cookie("think_var");
        $lang = "en-us";
        if ($cookieLang) {
            $lang = $cookieLang;
        } else {
            cookie("think_var", $lang);
        }
        $loginUser = $this->loginUser();
        $this->assign([
                'loginUser' => $loginUser,
                "msgs" => json_encode(Message::all()),
                "lang" => $lang,

            ]
        );
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
            $user["address"] = pinyinName($user["address"]);
        }
        return $user;
    }

    public function search()
    {
        $this->searchData();
        return view("/index/search");
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
            $countryLive = request()->param("countryLive");
            $stateLive = request()->param("stateLive");
            $cityLive = request()->param("cityLive");

            if (trim($age_min) != "" && $age_min != "-1") {
                $table->where("age", ">", $age_min);
            }
            if (trim($age_max) != "" && $age_max != "-1") {
                $table->where("age", "<", $age_max);
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
    }

}
