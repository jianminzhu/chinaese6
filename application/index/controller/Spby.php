<?php

namespace app\index\controller;
require('ext_util/fileUtil.php');
require('ext_util/BytripSpider.php');

use app\index\model\Bmember;
use think\Controller;
use think\Db;

function setItems($src, $keys)
{
    $item = [];
    $keyArr = [];
    if ($keys) {
        $arr = explode(",", $keys);
        foreach ($arr as $a) {
            if (trim($a) != "") {
                $keyArr[] = trim($a);
            }
        }
    }
    foreach ($keyArr as $key) {
        if ($src[$key] != None) {

            try {
                $item[$key] = $src[$key];
            } catch (\Exception $e) {
            }
        };
    }
    return $item;
}

function getPicSize($picPath, $root = ".")
{
    try {
        return filesize($root . $picPath);
    } catch (\Exception $e) {
        return 0;
    }

}

function spiderPicByUid($uid)
{
    $m = Db::table("bmember")->where("uid", $uid)->find();
    $pics = Db::table("pics")->where("m_id", $uid)->select();
    $main_pic = $m["main_pic"];
    echo json_encode($m);
    list($size, $isCover) = downPic($main_pic);
    if ($size <= 0) {
        echo "<img height=60px src='$main_pic' /><img height=60px  src='http://www.bytrip.com$main_pic'>";
    } else {
        if ($size != $m["pic_size"]) {
            Db::table("bmember")->where("uid", $uid)->update(["pic_size" => $size]);
        }
    }
    foreach ($pics as $pic) {
        list($size, $isCover) = downPic($pic["file_path"]);
        if ($size != $pic["pic_size"]) {
            Db::table("pics")->where("id", $pic["id"])->update(["pic_size" => $size]);
        }
    }
    return ["m" => $m, "pics" => $pics];
}


function flushLocalPicRealSize($limit=100)
{
    $m = Db::table("bmember")->limit($limit)->column("main_pic,uid");

    foreach ($m as $item) {
        echo "<br>" . $item;
    }
}

/**
 * @param $main_pic
 * @param $m
 * @return mixed
 */
function downPic($main_pic, $isRedownload = false, $rootPath = ".")
{
    $size = getPicSize($main_pic);
    $isCover = $isRedownload || $size == 0;
    if ($isCover) {
        ExtDownloadPic("http://www.bytrip.com" . $main_pic, $rootPath, false, $isCover);
        $size = getPicSize($main_pic);
    }
    return array($size, $isCover);
}

class Spby extends Controller
{
    function flushLocalPicRealSize()
    {
        $pno = intval(request()->param("pno", "1"));
        $psize = intval(request()->param("psize", "20"));
        $m = Db::table("bmember")->limit(($pno - 1) * $psize, $psize)->column("main_pic,uid");
        $tr=[];
        foreach ($m as $pic=>$uid) {
            $size = getPicSize($pic);
            $tr[]=" <tr><td>$size</td><td>$pic</td><td>$uid</td></tr>";
            if ($size==0) {
                spiderPicByUid($uid);
            }
            Db::table("bmember")->where("uid", $uid)->update(["pic_size" => $size]);
        }
        $trStr = implode("\n", $tr);
        $html="<table>
               <tr><td>size</td><td>file</td><td>uid</td></tr> 
               $trStr
        </table>";
        return $html;
    }

    function pics()
    {
        $isShowPic = request()->param("isShowPic", "") != "no";
        $uid = request()->param("uid", "");
        $limit = intval(request()->param("limit", "100"));
        $this->spiderPics($isShowPic, $uid, $limit);
    }

    public function updateMember($id)
    {
        $item = BytripMember($id);
        if ($item["member"]) {
            $member = $item["member"];
            try {
                $member["pwd"] = md5("111222333");
                Db::table("member")->insert($member);
            } catch (\Exception $e) {
                $item = setItems($member, "age,height,weight,nickname,main_pic,pwd");
                $item["isUpdateHW"] = 1;
                Db::table('member')->where(["isUpdateHW" => "0", "id" => $member["id"]])->update($item);
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


    function spiderPics($isShowPic, $uid, $limit)
    {

        $uids = [];
        if ($uid) {
            $uids[] = $uid;
            $isShowPic = True;
        } else {
            $m = new Bmember();
            $m->where("isDownPics", 0);
            $uids = $m->limit($limit)->column("uid");
        }
        $noShowArr = [];
        $picsHtmlAll = [];
        foreach ($uids as $uid) {
            try {
                $pics = "";
                $picsHtml = [];
                $item = $this->updateMember($uid);
                $main_pic = $item["member"]["main_pic"];
                $picsHtml[] = $this->imgHtml($main_pic, $uid);
                $pics = $uid . " " . $main_pic;
                echo $isShowPic == True ? "<br> " : "";
                Db::table('bmember')->where(["uid" => $uid])->update(["isDownPics" => "1"]);
                foreach ($item["pics"] as $pic) {
                    $pics = $pics . " " . $pic["file_path"];
                    $picsHtml[] = $this->imgHtml($pic["file_path"], $uid);
                }
                $noShowArr[] = $pics;
                $picsHtmlAll[] = join("&nbsp;", $picsHtml);
            } catch (\Exception $e) {
            }
        }
        echo $isShowPic == True ? "<br>" . join("<br><br>", $picsHtmlAll) : join("<br><br>", $noShowArr);
        return "finished";
    }

    function imgHtml($pic, $uid, $height = "60px")
    {
        return "<a href='/index.php/index/m/profile?id=$uid'><img title='$uid' height='$height' src='" . $pic . "'/></a>";
    }

    function picSize()
    {
        $pno = intval(request()->param("pno", "1"));
        $psize = intval(request()->param("psize", "20"));
        return json_encode($this->filesize($pno, $psize));
    }

    function filesize($pno, $psize)
    {
        $data = Db::table("pics")->limit(($pno - 1) * $psize, $psize)->select();
        $pics = [];
        foreach ($data as $p) {
            $file_path = $p ["file_path"];
            $realSize = filesize("./" . $file_path);
            $p["pic_size2"] = $realSize;
            $pics[] = ["id" => $p["id"], "file" => $file_path, "realsize" => $realSize];
        }
        return $pics;
    }

//    http://localhost/index.php/index/spby/userPics?uid=476
    function userPics()
    {
        $uid = request()->param("uid");
        return json_encode(spiderPicByUid($uid));
    }

}



