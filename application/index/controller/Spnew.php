<?php

namespace app\index\controller;
require('ext_util/fileUtil.php');
require('ext_util/BytripSpider.php');

use app\index\model\Address;
use app\index\model\Member;
use think\Controller;
use think\Db;

class Spnew extends Controller
{     function spider()
    {
        spicerAll();
    }
}



