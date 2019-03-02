<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Message;

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
        $loginUser = $this->loginUser();
        if ($loginUser) {

        }
        $pno = intval(request()->param("pno", 1));
        $page = $pno . ",10";
        $members = Db::table('member')->page($page)->select();
        // 或者批量赋值
        $this->assign(
            [
                "members" => $members,
                'loginUser' => $loginUser
                ,"nextPno" => ++$pno,
                'u' => $loginUser,
                "msgs"=>json_encode(Message::all())
            ]
        );
    }

}
