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
        $pics = Db::table("pics")->where("m_id", $id)->select();
        return view('/index/profile', ['m' => $member, "pics" => $pics]);
    }


    public function profiledit()
    {
        $loginUser = session("loginUser");
        $dbMember = Member::get(['email' => $loginUser->email]);
        return view('/index/profile_edit', ['u' => $dbMember]);
    }

    public function pics()
    {
        $ids = Db::table('member')->where("isUpdateHW", 0)->column("id");
        $all = [];
        foreach ($ids as $id) {
            $all[] = $this->updateMember($id);
        }

        return json_encode($all);
    }

    /**
     * @param $id
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateMember($id)
    {
        $item = BytripMemberPics($id);
        if ($item["pics"]) {
            //height weight
            $member = $item["member"];
            $member["isUpdateHW"] = 1;
            Db::table('member')->where(["isUpdateHW" => "0", "id" => $member["id"]])->update($member);

            $imgs = [];
            foreach ($item["pics"] as $pic) {
                $file_path = $pic["file_path"];
                $imgs[] = $file_path;
                $imgUrl = "http://www.bytrip.com/" . $file_path;
                ExtDownload($imgUrl, ".");
            }
            $inDbImgs = Db::table('pics')->whereIn("file_path", $imgs)->column("file_path");

            $needInsertImgs = array_diff($imgs, $inDbImgs);
            $needInsertPics = [];
            foreach ($needInsertImgs as $img) {
                $needInsertPics [] = ["m_id" => $member["id"], "file_path" => $img];
            }
            if ($needInsertPics) {
                Db::table("pics")->insertAll($needInsertPics);
            }
            $item["needInsertImgs"] = $needInsertImgs;
        }
        return $item;
    }
}


