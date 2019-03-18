<?php

namespace app\index\controller;

require_once "ext_util/fileUtil.php";
require_once "ext_util/BytripHtmlJsonSpider.php";


class Test extends Base
{
    public function md5()
    {
        return md5(request()->param("pwd"));
    }

    public function index()
    {

        spiderAllCity();
        return "------end";

    }



}