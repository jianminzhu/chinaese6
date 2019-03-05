<?php
require_once 'rb.php';
R::setup("mysql:host=localhost;dbname=test", "root", "root");
//创建一个表（也可以指为实例化一个表）
$tableName = "member";

$newArr = [];
$countrys = R::getAll("SELECT DISTINCT(countryid) FROM `cupidaddress`");
foreach ($countrys as $country) {
    $countryid = $country["countryid"];
    $arr = [];
    $provices = R::getAll("SELECT  `attributeid` AS v, `translation` AS n   FROM cupidaddress WHERE countryid=? and stateid is null", [$countryid]);
    foreach ($provices as $provice) {
        $cities = R::getAll("SELECT  `attributeid` AS v, `translation` AS n   FROM cupidaddress WHERE countryid=? and stateid =?", [$countryid, $provice["v"]]);
        $provice["cities"] = $cities;
        $arr[] = $provice;
    }
    $countyProvicesFile = "country/$countryid.js";
    $resource = fopen($countyProvicesFile, 'w');
    fwrite($resource, "if(ADDRESS==undefined){ADDRESS=[]};ADDRESS[$countryid]=" . json_encode($arr));
    echo "write content to file $countyProvicesFile";
}
echo "end";




