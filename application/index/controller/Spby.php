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
        $isShowPic = request()->param("isShowPic","")!="no";
        $uid= request()->param("uid","") ;
        $limit= intval(request()->param("limit","10")) ;
        $uids = [];
        $picArr = [];
        if ($uid) {
            $uids[] = $uid;
            $isShowPic = True;
        }else{
            $m = new Bmember();
            $m->where("isDownPics", 0);
            $uids = $m->limit($limit)->column("uid");
            echo  join("<br>", $picArr);
        }
        foreach ($uids as $uid) {
            try {
                $item = $this->updateMember($uid);
                $main_pic = $item["member"]["main_pic"];
                $arr=[$uid,$main_pic];
                echo $isShowPic==True? "<br> <img title='$uid' height='60px' src='" . $main_pic . "'/>":"";
                Db::table('bmember')->where(["uid" => $uid])->update(["isDownPics" => "1"]);
                foreach ($item["pics"] as $pic) {
                    $arr[] = $pic["file_path"];
                    echo $isShowPic==True?  " <img title='$uid' height='60px' src='" . $pic . "'/>":"";
                }
                $picArr[] = json_encode($arr);
            } catch (\Exception $e) {
            }
        }
        return "<br>".join("<br>", $picArr);
    }

}



