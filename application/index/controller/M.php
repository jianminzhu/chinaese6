<?php

namespace app\index\controller;

use app\index\model\Member;
use think\Controller;

class M extends Controller
{

    public function reg()
    {
        return view('/index/reg');
    }


    public function savereg()
    {
        $member = new Member(request()->param());
        try {
            $member->pwd = md5($member->password);
            $member->allowField(['nickname', 'email', "pwd"])->save();
            session("loginUser", $member);
            return redirect("/", "", 302);
        } catch (\Exception $e) {
            return redirect('/')->params(['emsg' => $e->getMessage()], 302);
        }
    }

    public function test()
    {
        return json_encode(Member::get(10));
    }

    public function profile()
    {
        $index = new Index();
        $index->headData();
        $id = request()->param("id");
        $member = Member::get($id);
        return view('/index/profile', ['u' => $member]);
    }

    public function profiledit()
    {
        $loginUser = session("loginUser");
        $dbMember = Member::get(['email' => $loginUser->email]);
        return view('/index/profile_edit', ['u' => $dbMember]);
    }
}
