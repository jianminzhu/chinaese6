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

//$hostname='az1-ls7.a2hosting.com';
$hostname='localhost';
$database='bytrip';
$username='root';
$password='root';
//R::setup("mysql:host=$hostname;dbname=$database", $username, $password);

$dbconf = include("database.php");
// 数据库配置信息设置（全局有效）
Db::setConfig($dbconf);
$allCityids = [110100, 310100, 440100, 440300, 510100, 500100, 120100, 420100, 320100, 130100, 130200, 130300, 130400, 130500, 130600, 130700, 130800, 130900, 131000, 131100, 140100, 140200, 140300, 140400, 140500, 140600, 140700, 140800, 140900, 141000, 141100, 150100, 150200, 150300, 150400, 150500, 150600, 150700, 150800, 150900, 152200, 152500, 152900, 210100, 210200, 210300, 210400, 210500, 210600, 210700, 210800, 210900, 211000, 211100, 211200, 211300, 211400, 220100, 220200, 220300, 220400, 220500, 220600, 220700, 220800, 222400, 230100, 230200, 230300, 230400, 230500, 230600, 230700, 230800, 230900, 231000, 231100, 231200, 232700, 320200, 320300, 320400, 320500, 320600, 320700, 320800, 320900, 321000, 321100, 321200, 321300, 330100, 330200, 330300, 330400, 330500, 330600, 330700, 330800, 330900, 331000, 331100, 340100, 340200, 340300, 340400, 340500, 340600, 340700, 340800, 341000, 341100, 341200, 341300, 341500, 341600, 341700, 341800, 350100, 350200, 350300, 350400, 350500, 350600, 350700, 350800, 350900, 360100, 360200, 360300, 360400, 360500, 360600, 360700, 360800, 360900, 361000, 361100, 370100, 370200, 370300, 370400, 370500, 370600, 370700, 370800, 370900, 371000, 371100, 371200, 371300, 371400, 371500, 371600, 371700, 410100, 410200, 410300, 410400, 410500, 410600, 410700, 410800, 410881, 410900, 411000, 411100, 411200, 411300, 411400, 411500, 411600, 411700, 420200, 420300, 420500, 420600, 420700, 420800, 420900, 421000, 421100, 421200, 421300, 422800, 429004, 429005, 429006, 429021, 430100, 430200, 430300, 430400, 430500, 430600, 430700, 430800, 430900, 431000, 431100, 431200, 431300, 433100, 440200, 440400, 440500, 440600, 440700, 440800, 440900, 441200, 441300, 441400, 441500, 441600, 441700, 441800, 441900, 442000, 445100, 445200, 445300, 450100, 450200, 450300, 450400, 450500, 450600, 450700, 450800, 450900, 451000, 451100, 451200, 451300, 451400, 460100, 460200, 469001, 469002, 469003, 469005, 469006, 469007, 469025, 469026, 469027, 469028, 469030, 469031, 469033, 469034, 469035, 469036, 469037, 469038, 469039, 510300, 510400, 510500, 510600, 510700, 510800, 510900, 511000, 511100, 511300, 511400, 511500, 511600, 511700, 511800, 511900, 512000, 513200, 513300, 513400, 520100, 520200, 520300, 520400, 522200, 522300, 522400, 522600, 522700, 530100, 530300, 530400, 530500, 530600, 530700, 530800, 530900, 532300, 532500, 532600, 532800, 532900, 533100, 533300, 533400, 540100, 542100, 542200, 542300, 542400, 542500, 542600, 610100, 610200, 610300, 610400, 610500, 610600, 610700, 610800, 610900, 611000, 620100, 620200, 620300, 620400, 620500, 620600, 620700, 620800, 620900, 621000, 621100, 621200, 622900, 623000, 630100, 632100, 632200, 632300, 632500, 632600, 632700, 632800, 640100, 640200, 640300, 640400, 640500, 650100, 650200, 652100, 652200, 652300, 652700, 652800, 652900, 653000, 653100, 653200, 654000, 654200, 654300, 659001, 659002, 659003, 659004, 710100, 710200, 710300, 710400, 710500, 710600, 710700, 710800, 710900, 711100, 711200, 711300, 711400, 711500, 711700, 711900, 712100, 712400, 712500, 712600, 712700, 810100, 810200, 810300, 820100, 820200];
spiderAllCity([310100], 18, 33,"spider_log/shenghui.txt");







