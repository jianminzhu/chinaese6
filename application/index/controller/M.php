<?php

namespace app\index\controller;
require('ext_util/fileUtil.php');
require('ext_util/BytripUtil.php');

use app\index\model\Member;
use think\Controller;
use think\Db;

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

    public function profile()
    {
        $index = new Index();
        $index->headData();
        $id = request()->param("id");
        list($member, $pics) = $this->getMember($id);
        return view('/index/profile', ['m' => $member, "pics" => $pics]);
    }


    public function profiledit()
    {
        $index = new Index();
        $index->headData();
        $loginUser = session("loginUser");
        if($loginUser){
            return view('/index/profile_edit', ['u' => $loginUser]);
        }else{
            session("lastUrl", "/index.php/index/index/m/profiledit");
            return redirect('/index.php/index/a/login');
        }
    }

    /**
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMember($id)
    {
        $member = Member::get($id);
        $pics = Db::table("pics")->where("m_id", $id)->select();
        return array($member, $pics);
    }


}


