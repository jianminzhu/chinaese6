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
        $this->search();
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
        $loginUser = $this->loginUser();
        $this->assign([
                'loginUser' => $loginUser,
                "msgs" => json_encode(Message::all()),
                "lang" => cookie("think_var") ? cookie("think_var") : "en-us"
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

    /**
     * @param $mid
     * @param $cookieLang
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function search()
    {

        $cookieLang = cookie("think_var");
        $loginUser = $this->loginUser();
        $mid = 0;
        if ($loginUser) {
            $user = clone $loginUser;
            $this->nickNameToPinYing($cookieLang, $user);
            $mid = $loginUser->id;
        }
        $pno = intval(request()->param("pno", 1));
        $page = $pno . ",15";
        $dbMembers = Db::table('member')->where('id', "<>", $mid)->page($page)->select();
        $members = [];
        foreach ($dbMembers as $member) {
            $members[] = $this->nickNameToPinYing($cookieLang, $member);
        }
        $this->assign("members", $members);
        $this->assign("nextPno", ++$pno);
    }

}
