<?php

namespace app\index\controller;

use app\index\model\Message;
use think\Controller;
use think\Db;

include_once "ext_util/pinyin.php";

class Index extends Controller
{
    public function index()
    {
        $this->headData();
        return $this->fetch('index');
    }

    public function msgs()
    {
        $this->headData();
        return $this->fetch('msglist');
    }

    public function search()
    {
        $this->headData();
        return $this->fetch('search');
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
        $loginUser = $this->loginUser();
        $mid = 0;
        $user = [];
        if ($loginUser) {
            $user = clone $loginUser;
            $this->nickNameToPinYing($cookieLang, $user);
            $mid = $loginUser->id;
        }
        $pno = intval(request()->param("pno", 1));
        $page = $pno . ",15";
        $dbMembers  = Db::table('member')->where('id', "<>", $mid)->page($page)->select();
        $members = [];
        foreach ($dbMembers as $member) {
            $members[]=$this->nickNameToPinYing($cookieLang, $member);
        }
        // 或者批量赋值
        $this->assign(
            [
                "members" => $members,
                'loginUser' => $loginUser
                , "nextPno" => ++$pno,
                'u' => $user,

                "msgs" => json_encode(Message::all()),
                'lang' => $cookieLang
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

}
