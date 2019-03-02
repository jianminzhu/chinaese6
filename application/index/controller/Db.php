<?php

namespace app\index\controller;

use app\index\model\Member;
use think\Controller;

/**
 * Class Auth
 * @package app\controller
 * 权限相关
 */
class Db extends Controller
{
    public function test()
    {
        $data = Member::where("nickname", "tophold")->where("age", ">=", 32)->select();
        return json_encode($data);
    }
}
