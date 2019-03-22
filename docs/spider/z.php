<?php
include_once "vendor/autoload.php";
require_once 'ext_util/rb.php';
require_once "ext_util/fileUtil.php";

use QL\QueryList;
use think\Db;

function SpiderCityMembersByPno($cityid, $pno = 1,$ageStart ,$ageEnd )
{
    $url = "http://www.bytrip.com/i/status/2/area/$cityid/low_age/$ageStart/high_age/$ageEnd/sex/2/lt/1/rt/0/hot/0/p_$pno/";
    $html = ExtGetHtml($url);
    echo "\n<br><br>url :" . $url;
    $rules = [
        //采集img标签的src属性，也就是采集页面中的图片链接
        'uid' => ['.information p .name', 'href', '', function ($content) {
            return preg_replace('/[^\d]*/', '', $content);
        }],
        'nickname' => ['.information p .name', 'text'],
        'age' => ["ul>li" => "eq(0)", "text"]
    ];
// 过程"=>"设置HTML=>设置采集规则=>执行采集=>获取采集结果数据
    $q = QueryList::html($html);
    $data = $q->rules($rules)->range("div.travel-bd .list")->queryData();
    $page = intval($q->find("a.end")->text());
    return array($data, $page);
}
function save($tableName, $data)
{
    try {
        $colValue = get_object_vars($data);
        $id = Db::table($tableName)->insert($colValue);
        if($tableName=="memberby" ){
            echo "\n<br/><br/>add succ member " .$colValue["uid"]."  ".$colValue["realname"]  ;
        }else if($tableName == "membercontactsnologin"){
            echo "\n<br>add succ concat " .$colValue["uid"]."  ".$colValue["type"]." :".$colValue["number"]  ;
        };
        return $id;
    } catch (\Exception $e) {
        if($tableName=="memberby" ){
            echo "\n<br><br>member  has in db" .$colValue["uid"]."  ".$colValue["realname"]  ;
        }else if($tableName == "membercontactsnologin"){
            echo "\n<br>concat has in db  " .$colValue["uid"]."  ".$colValue["type"]." :".$colValue["number"]  ;
        };
        return null;
    }
}


function saveR($tableName, $data)
{
    try {
        $to = R::dispense($tableName);
        foreach ($data as $key => $value) {
            $to[$key] = $value;
        }
        $id=R::store($to);
        $colValue = get_object_vars($data);
        if($tableName=="memberby" ){
            echo "\n<br/><br/>add succ member " .$colValue["uid"]."  ".$colValue["realname"]  ;
        }else if($tableName == "membercontactsnologin"){
            echo "\n<br>add succ concat " .$colValue["uid"]."  ".$colValue["type"]." :".$colValue["number"]  ;
        };
        return $id;

    } catch (Exception $e) {
        if($tableName=="memberby" ){
            echo "\n<br><br>member  has in db" .$colValue["uid"]."  ".$colValue["realname"]  ;
        }else if($tableName == "membercontactsnologin"){
            echo "\n<br>concat has in db  " .$colValue["uid"]."  ".$colValue["type"]." :".$colValue["number"]  ;
        };
        return null;
    }
}


function SpiderMemberDetail($id,$cookie="Cookie: userAgent=windows;")
{
    if ($id) {
        $header = [
            "Host: www.bytrip.com"
            , "Connection: keep-alive"
            , "Accept: */*"
            , "X-Requested-With: XMLHttpRequest"
            , "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3710.0 Safari/537.36"
            , "Referer: http://www.bytrip.com/i/u_$id.html"
            , "Accept-Encoding: gzip, deflate"
            , "Accept-Language: zh-CN,zh;q=0.9"
            , $cookie
        ];
        //$sjson = ExtGetJson("http://www.bytrip.com/User/Ajax/swapContact.html?id=$id", $header);
        $jsonStr = ExtGetJson("http://www.bytrip.com/User/Ajax/contact.html?fuid=$id", $header);
        try {
            $data = json_decode($jsonStr);
            $member = $data[4];
            $concats = $data[0];
            foreach ($concats as $concat) {
                $concat->uid = $member->uid;
            }
            $level = $data[1];
            $level->uid = $member->uid;
            $member->mark = "";
            $member->main_pic = $data[3];
            return array($member, $concats, $level);
        } catch (Exception $e) {
        }
    }
    return null;
}

function dbmsg($id, $msg, $nullMsg){
    if ($id) {
        echo "\n add $msg";
    }else{
        echo "\n exists in $nullMsg";
    }

}

function saveMembersDataToDb($arr)
{
    list($member, $concats, $level) = $arr;
    save('memberby', $member);
    save('memberlevel', $level);
    foreach ($concats as $concat) {
        if (strpos($concat->number, "*") == false) {
            save('membercontacts', $concat);
        }
        save('membercontactsnologin', $concat);
    }
}

function SpiderMembersDetailToDb($members,$cookie="Cookie: userAgent=windows;")
{
    foreach ($members as $member) {
        saveMembersDataToDb(SpiderMemberDetail($member["uid"],$cookie));
    }
}

function spiderAllCity($allCityids,$ageStart,$ageEnd,$lastSpiderRecord="spider_last.txt")
{

    list($lastCityid, $pno) = include($lastSpiderRecord);
    $totalCity = count($allCityids);
    $startCity = array_search($lastCityid, $allCityids);
    for ($i = $startCity; $i < $totalCity; $i++) {
        $cityid = $allCityids[$i];
        file_put_contents($lastSpiderRecord, "<?php\n return  array($cityid,$pno) ;");
        list($members, $totalPage) = SpiderCityMembersByPno($cityid, $pno,$ageStart,$ageEnd);
        SpiderMembersDetailToDb($members);
        if ($totalPage >= $pno + 1) {
            for ($pno = $pno + 1; $pno <= $totalPage; $pno++) {
                list($nextPageMembers) = SpiderCityMembersByPno($cityid, $pno,$ageStart,$ageEnd) ;
                SpiderMembersDetailToDb($nextPageMembers);
                file_put_contents($lastSpiderRecord, "<?php\n return  array($cityid,$pno) ;");
                echo "\n city $cityid the page $pno finished";
            }
        }
        $pno = 1;
        echo "\n city $cityid finished";
    }
}

$dbconf = include("../../application/database.php");
// 数据库配置信息设置（全局有效）
Db::setConfig($dbconf);

echo json_encode($dbconf);






