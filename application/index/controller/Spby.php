<?php

namespace app\index\controller;
require('ext_util/fileUtil.php');
require('ext_util/BytripSpider.php');

use app\index\model\Bmember;
use think\Controller;
use think\Db;

class Spby extends Controller
{
    public function updateMember($id)
    {
        $item = BytripMember($id);
        if ($item["member"]) {
            $member = $item["member"];
            try {
                $member["pwd"] = md5("111222333");
                Db::table("member")->insert($member);
            } catch (\Exception $e) {
                $member["isUpdateHW"] = 1;
                Db::table('member')->where(["isUpdateHW" => "0", "id" => $member["id"]])->update($member);
            }
            $main_pic = "http://www.bytrip.com/" . $member["main_pic"];
            ExtDownloadPic($main_pic, ".");
        }
        if ($item["pics"]) {
            $imgs = [];
            foreach ($item["pics"] as $pic) {
                $file_path = $pic["file_path"];
                $imgs[] = $file_path;
                $imgUrl = "http://www.bytrip.com/" . $file_path;
                ExtDownloadPic($imgUrl, ".");
            }
            $inDbImgs = Db::table('pics')->whereIn("file_path", $imgs)->column("file_path");
            $needInsertImgs = array_diff($imgs, $inDbImgs);
            $needInsertPics = [];
            foreach ($needInsertImgs as $img) {
                $needInsertPics [] = ["m_id" => $member["id"], "file_path" => $img];
            }
            if ($needInsertPics) {
                try {
                    Db::table("pics")->insertAll($needInsertPics);
                } catch (\Exception $e) {
                }
            }
            $item["needInsertImgs"] = $needInsertImgs;
        }
        return $item;
    }

    function pics()
    {
        $m = new Bmember();
        $m->where("isDownPics", 0);
        $mcs = $m->limit(50)->select();
        $picArr = [];

        foreach ($mcs as $mc)
            try {
                $uid = $mc->uid;
                $item = $this->updateMember($uid);
                $picArr[] = [];
                echo "<br>" . $uid . " <img alt='$uid' height='60px' src='" . $item["member"]["main_pic"] . "'/>";
                Db::table('bmember')->where(["uid" => $uid])->update(["isDownPics" => "1"]);
                foreach ($item["pics"] as $pic) {
                    echo " <img alt='$uid' height='60px' src='" . $pic . "'/>";
                }
            } catch (\Exception $e) {
            }
        return "finished";
    }

}



