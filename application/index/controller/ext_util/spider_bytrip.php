<?php
include_once "vendor/autoload.php";
require_once 'rb.php';
require_once "fileUtil.php";

require('BytripSpider.php');

use think\Db;

function updateMember($id)
{


    $item = BytripMember($id);
    $imgRootDir = "../../../../public";
    if ($item["member"]) {
        $member = $item["member"];
        try {
            $member["pwd"] = md5("111222333");
            Db::table("member")->insert($member);
        } catch (Exception $e) {
            echo "\n-------member update------------" . $e->getMessage();
            try {
                $member["isUpdateHW"] = 1;
                $uid = $member["id"];
                 Db::table('member')->where("isUpdateHW=0 and id=$uid")->update($member);
                echo "\n-------update succ------------" . $uid;
            } catch (Exception $e2) {
                echo "\n-------member update error------------" . $e2->getMessage();
            }
        }
        $main_pic = "http://www.bytrip.com/" . $member["main_pic"];
        ExtDownloadPic($main_pic, $imgRootDir);
    }
    if ($item["pics"]) {
        $imgs = [];
        foreach ($item["pics"] as $pic) {
            $file_path = $pic["file_path"];
            $imgs[] = $file_path;
            $imgUrl = "http://www.bytrip.com/" . $file_path;
            ExtDownloadPic($imgUrl, $imgRootDir);
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


function spiderPics($limit = 10, $isShowPic, $uid)
{

    $uids = [];
    $picArr = [];
    if ($uid) {
        $uids[] = $uid;
        $isShowPic = True;
    } else {
        try {
            $query = Db::table("bmember")->where("isDownPics", 0);
            if ($limit > 0) {
                $query->limit($limit);
            }
            $uids =$query->column("uid");
        } catch (Exception $e) {
            echo "ddddddddddddddddddddddddddddddd" . $e->getMessage();
        }
    }
    foreach ($uids as $uid) {
        try {
            $item = updateMember($uid);
            $main_pic = $item["member"]["main_pic"];
            $arr = [$uid, $main_pic];
            echo $isShowPic == True ? "<br>\n <img title='$uid' height='60px' src='" . $main_pic . "'/>" : "";
            Db::table('bmember')->where(["uid" => $uid])->update(["isDownPics" => "1"]);
            foreach ($item["pics"] as $pic) {
                $file_path = $pic["file_path"];
                $arr[] = $file_path;
                echo $isShowPic == True ? "\n<img title='$uid' height='60px' src='" . $file_path . "'/>" : "";
            }
            $picArr[] = json_encode($arr);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    $str = "<br>\n------picArr " . join("<br>\n", $picArr);
    echo $str;
    return $str;
}

$dbconf = include("../../../../application/database.php");
// 数据库配置信息设置（全局有效）
Db::setConfig($dbconf);
spiderPics(0, False, False);






