<?php

namespace app\index\controller;
require('ext_util/fileUtil.php');
require('ext_util/BytripUtil.php');

use app\index\model\Address;
use think\Controller;
use think\Db;

class Sp extends Controller
{

    public function spiderMembers()

    {
        $idsStr = request()->param("ids");
        $patterns = "/\d+/";
        preg_match_all($patterns, $idsStr, $ids);
        $a = [];
        foreach ($ids[0] as $id) {
            $id = trim($id);
            if ($id != "") {
                $member = $this->updateMember($id)["member"];
                $id = $member["id"];
                $a[] = "<a target='_blank' href='/index.php/index/m/profile?id=$id'>" . $member["nickname"] . "</a>";
            }
        }
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset=\"UTF-8\"></head><body>
        " . implode("<br>", $a) . "
        </body></html>";
        return $html;
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

    /**
     * @param $url
     * @return array
     */
    public function parseMemberTodb($url)
    {
        $members = BytripSearchMembers($url);
        foreach ($members as $member) {
            try {
                $member["pwd"] = "e3e0e0b164ed59c430312854451d1d22 <br>";
                Db::table("member")->insert($member);
                echo " | IN(" . $member["nickname"] . ")";
            } catch (\Exception $e) {
                echo " | EX(" . $member["nickname"] . ")";
            }
        }
    }

    public function pidAddress()
    {
        $provices = [
            ["id" => 0, "area" => "全国", "pid" => 0],
            ["id" => 110000, "area" => "北京", "pid" => 0],
            ["id" => 120000, "area" => "天津", "pid" => 0],
            ["id" => 130000, "area" => "河北省", "pid" => 0],
            ["id" => 140000, "area" => "山西省", "pid" => 0],
            ["id" => 150000, "area" => "内蒙古自治区", "pid" => 0],
            ["id" => 210000, "area" => "辽宁省", "pid" => 0],
            ["id" => 220000, "area" => "吉林省", "pid" => 0],
            ["id" => 230000, "area" => "黑龙江省", "pid" => 0],
            ["id" => 310000, "area" => "上海", "pid" => 0],
            ["id" => 320000, "area" => "江苏省", "pid" => 0],
            ["id" => 330000, "area" => "浙江省", "pid" => 0],
            ["id" => 340000, "area" => "安徽省", "pid" => 0],
            ["id" => 350000, "area" => "福建省", "pid" => 0],
            ["id" => 360000, "area" => "江西省", "pid" => 0],
            ["id" => 370000, "area" => "山东省", "pid" => 0],
            ["id" => 410000, "area" => "河南省", "pid" => 0],
            ["id" => 420000, "area" => "湖北省", "pid" => 0],
            ["id" => 430000, "area" => "湖南省", "pid" => 0],
            ["id" => 440000, "area" => "广东省", "pid" => 0],
            ["id" => 450000, "area" => "广西壮族自治区", "pid" => 0],
            ["id" => 460000, "area" => "海南省", "pid" => 0],
            ["id" => 500000, "area" => "重庆", "pid" => 0],
            ["id" => 510000, "area" => "四川省", "pid" => 0],
            ["id" => 520000, "area" => "贵州省", "pid" => 0],
            ["id" => 530000, "area" => "云南省", "pid" => 0],
            ["id" => 540000, "area" => "西藏自治区", "pid" => 0],
            ["id" => 610000, "area" => "陕西省", "pid" => 0],
            ["id" => 620000, "area" => "甘肃省", "pid" => 0],
            ["id" => 630000, "area" => "青海省", "pid" => 0],
            ["id" => 640000, "area" => "宁夏回族自治区", "pid" => 0],
            ["id" => 650000, "area" => "新疆维吾尔自治区", "pid" => 0],
            ["id" => 710000, "area" => "台湾省", "pid" => 0],
            ["id" => 810000, "area" => "香港特别行政区", "pid" => 0],
            ["id" => 820000, "area" => "澳门特别行政区", "pid" => 0],
            ["id" => 990000, "area" => "海外", "pid" => 0]
        ];


        foreach ($provices as $provice) {
            $address = new Address($provice);
            try {
                $address->save();
            } catch (\Exception $e) {
            }
        }
        return json_encode($provice);
    }

    public function getAllAddress()

    {
        $provices = ["110000"
            , "120000"
            , "130000"
            , "140000"
            , "150000"
            , "210000"
            , "220000"
            , "230000"
            , "310000"
            , "320000"
            , "330000"
            , "340000"
            , "350000"
            , "360000"
            , "370000"
            , "410000"
            , "420000"
            , "430000"
            , "440000"
            , "450000"
            , "460000"
            , "500000"
            , "510000"
            , "520000"
            , "530000"
            , "540000"
            , "610000"
            , "620000"
            , "630000"
            , "640000"
            , "650000"
            , "710000"
            , "810000"
            , "820000"
            , "990000"];

        foreach ($provices as $provice) {
            $szUrl = "http://www.bytrip.com/By/Program/ajaxGetCity.html?id=$provice";
            $arr = json_decode(ExtGetHtml($szUrl));
            foreach ($arr as $a) {
                $address = new Address($a);
                $address["pid"] = $provice;
                try {
                    $address->save();
                } catch (\Exception $e) {
                }
                echo "save succ " . json_encode($address);
            }
        }
        $dataDb = Db::table("address")->select();
        return json_encode($dataDb);
    }


}



