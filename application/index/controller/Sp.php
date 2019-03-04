<?php

namespace app\index\controller;
require('ext_util/fileUtil.php');
require('ext_util/BytripUtil.php');

use think\Controller;
use think\Db;

class Sp extends Controller
{
    public function pics()
    {
        $ids = Db::table('member')->where("isUpdateHW", 0)->column("id");
        $all = [];
        foreach ($ids as $id) {
            $all[] = $this->updateMember($id);
        }
        return json_encode($all);
    }

    public function spider()
    {
        return view("/index/spider");
    }

    public function doSpider()
    {
        $urlsParamStr = request()->param("urls");
        $urls = explode("\r\n", $urlsParamStr);
        foreach ($urls as $url) {
            $url = trim($url);
            if ($url != "" && strpos($url, "://www.bytrip.com")) {
                $this->parseMemberTodb($url);
            }
        }
        return "<br> spider complate";
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
                ExtDownloadPic($imgUrl, ".");
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

    /**
     * @param $url
     * @return array
     */
    public function parseMemberTodb($url)
    {
        echo "<br>" . $url . " starting spider ";
        $members = BytripSearchMembers($url);
        echo "<br>" . $url . " parse ok ";
        foreach ($members as $member) {
            try {
                $member["pwd"] = "e3e0e0b164ed59c430312854451d1d22 <br>";
                Db::table("member")->insert($member);
                echo "<br>" . "add " . $member["nickname"]." succ";
            } catch (\Exception $e) {
                echo "<br>" . "add " . $member["nickname"]." is exists";
            }
        }
    }
}


