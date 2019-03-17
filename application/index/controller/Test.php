<?php

namespace app\index\controller;

use QL\QueryList;

require_once "ext_util/fileUtil.php";

class Test extends Base
{
    public function md5()
    {
        return md5(request()->param("pwd"));
    }

    public function index()
    {
        $url = "http://www.bytrip.com/Index/Fere/index/status/2/area/0/low_age/18/high_age/60";
        $html = require_once("Data.php");
        //采集规则
        $rules = [
            //采集img标签的src属性，也就是采集页面中的图片链接
            'nickname' => ['.information p .name', 'text'],
            'age' => ["ul>li" => "eq(0)", "text"]
        ];
        // 过程"=>"设置HTML=>设置采集规则=>执行采集=>获取采集结果数据
        $data = QueryList::html($html)->rules($rules)->range("div.travel-bd .list")->queryData();
        //打印结果
        echo json_encode($data);

    }



}