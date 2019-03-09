<?php

namespace app\index\controller;
require('ext_util/fileUtil.php');
require('ext_util/BytripUtil.php');

use think\Controller;
use think\Db;

/**
 * 数组 转 对象
 *
 * @param array $arr 数组
 * @return object
 */
function array_to_object($arr)
{
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)array_to_object($v);
        }
    }

    return (object)$arr;
}

/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */
function object_to_array($obj)
{
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)object_to_array($v);
        }
    }

    return $obj;
}

class Addr extends Controller
{
    public function loadstates()
    {
        $data = [];
        $stateid = request()->param("stateid");
        if ($stateid && trim($stateid) != "") {
            $data= Db::query("select translation as n, attributeid as v,chinese as cn from cupidaddress where stateid=? order by sort asc", [$stateid]) ;
        }

        $countryid = request()->param("countryid");
        if ($countryid && trim($countryid) != "") {
            $data = Db::query("select translation as n, attributeid as v,chinese as cn from cupidaddress where stateid is null  and countryid=? order by sort asc", [$countryid]);

        }
        return ($data);
    }


    public function saveAddrs()    //    /index.php/index/addr/saveAddrs
    {
        $addrs = json_decode(request()->param("addressJsonStr"));
        foreach ($addrs as $addr) {
            try {

                Db::table("bytripaddres")->insert(object_to_array($addr));
            } catch (\Exception $e) {
                echo "" . $e->getMessage();
            }
        }
        return "finished";
    }

    public function allCounty()
    {
        // $countyids = [42, 7, 5, 4, 12, 3, 1, 147, 6, 61, 67, 62, 64, 9, 10, 15, 2, 14, 13, 134, 18, 173, 22, 169, 166, 93, 17, 171, 30, 28, 20, 32, 116, 49, 27, 21, 100, 181, 170, 19, 26, 25, 224, 24, 174, 31, 35, 33, 63, 56, 86, 213, 210, 58, 59, 182, 60, 65, 74, 76, 75, 92, 69, 77, 232, 180, 71, 70, 53, 72, 78, 40, 41, 48, 50, 84, 85, 83, 52, 87, 88, 121, 94, 118, 96, 158, 163, 246, 97, 57, 115, 91, 175, 36, 81, 79, 37, 68, 46, 176, 44, 54, 47, 108, 120, 45, 117, 98, 114, 244, 124, 126, 129, 123, 125, 131, 122, 128, 177, 130, 183, 179, 132, 135, 148, 146, 139, 150, 141, 140, 178, 133, 145, 136, 223, 221, 231, 11, 137, 138, 23, 168, 73, 242, 144, 143, 142, 151, 149, 233, 188, 16, 201, 161, 160, 164, 155, 157, 153, 159, 154, 167, 172, 80, 110, 200, 203, 66, 236, 238, 192, 189, 55, 187, 184, 119, 211, 190, 186, 197, 193, 245, 185, 227, 199, 39, 127, 191, 237, 198, 243, 162, 29, 195, 206, 218, 216, 205, 209, 204, 212, 214, 217, 207, 215, 208, 156, 90, 228, 34, 234, 219, 222, 225, 226, 196, 235, 89, 95, 241, 194, 152, 165, 99, 202, 112, 8, 239, 109, 105, 106, 107, 103, 101, 220, 104, 102, 229, 113, 230, 240, 111, 38, 82, 43, 51];
        $countyids = [42];
        foreach ($countyids as $countyid) {
            try {
                $countyCiytes = $this->getAllAddress($countyid);
                Db::table("cupidaddress")->insertAll($countyCiytes);
            } catch (\Exception $e) {
            }
        }
        return "finished";

    }

    public function test()
    {
        return json_encode($this->getAllAddress(42));
    }


    public function getAllAddress($countryid)
    {
        $contryStateUrl = "https://www.chinalovecupid.com/zc/widget/loadstates?countryid=$countryid";
        $html = ExtGetHtml($contryStateUrl);
        ExtWriteFile("addresses/$countryid states.json", $html);
        $states = json_decode($html);
        $all = [];
        foreach ($states as $state) {
            $stateId = $state->ATTRIBUTEID;
            $all[] = [
                "attributeid" => $stateId,
                "translation" => $state->TRANSLATION,
                "reorder" => $state->REORDER,
                "countryid" => $countryid,
                "stateid" => null,
            ];
            $citys = json_decode(ExtGetHtml("https://www.chinalovecupid.com/zc/widget/loadcities?stateid=$stateId"));
            foreach ($citys as $city) {
                $all[] = [
                    "attributeid" => $city->ATTRIBUTEID,
                    "translation" => $city->TRANSLATION,
                    "reorder" => $city->REORDER,
                    "countryid" => $countryid,
                    "stateid" => $stateId
                ];
            }
        }
        return $all;
    }

    public function toSql($all)
    {
        $sql = "INSERT INTO `cupidaddress` (  `reorder`,`attributeid`,`translation`,`countryid`,`stateid`)VALUES";
        $values = [];
        foreach ($all as $row) {
            $reorder = $row["reorder"];
            $attributeid = $row["attributeid"];
            $translation = $row["translation"];
            $countryid = $row["countryid"];
            $stateid = $row["stateid"];
            $value = str_replace("[", "(", json_encode([$reorder, $attributeid, $translation, $countryid, $stateid]));
            $value = str_replace("]", ")", $value);
            $values[] = $value;
        }
        $sql = $sql . implode("\n,", $values);
        return $sql;
    }
}



