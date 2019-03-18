<?php
require_once 'rb.php';
require_once "fileUtil.php";
require_once "../../../../vendor/autoload.php";

use QL\QueryList;

$fileName = "bj_1.html";


//$html= ExtGetHtml("http://www.bytrip.com/i/status/2/area/110100/low_age/18/high_age/60/sex/2/lt/1/rt/0/hot/0/p_1/");
//file_put_contents($fileName, $html);
$file = fopen($fileName,"r");
$html = fread($file,filesize($fileName));

$rules = [
    //采集img标签的src属性，也就是采集页面中的图片链接
    'nickname' => ['.information p .name', 'text'],
    'age' => ["ul>li" => "eq(0)", "text"]
];
// 过程"=>"设置HTML=>设置采集规则=>执行采集=>获取采集结果数据
$data = QueryList::html($html)->rules($rules)->range("div.travel-bd .list")->queryData();
//打印结果
echo json_encode($data);
