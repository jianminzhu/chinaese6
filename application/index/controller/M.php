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

    public function test()
    {
        return json_encode(Member::all());
    }

    public function profile()
    {
        $index = new Index();
        $index->headData();
        $id = request()->param("id");
        $member = Member::get($id);
        return view('/index/profile', ['m' => $member]);
    }


    public function profiledit()
    {
        $loginUser = session("loginUser");
        $dbMember = Member::get(['email' => $loginUser->email]);
        return view('/index/profile_edit', ['u' => $dbMember]);
    }

    public function pics()
    {
        $id = request()->param("id");
        $item = BytripMemberPics($id);
        if ($item["pics"]) {
            foreach ($item["pics"] as $pic) {
                $imgUrl = "http://www.bytrip.com/" . $pic["file_path"];
                ExtDownload($imgUrl, ".");

                try {
                    Db::table('pics')->insert($pic);
                } catch (\Exception $e) {
                }

            }
        }
        return json_encode($item);;

    }
}


