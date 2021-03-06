<?php

namespace app\index\controller;

use app\index\model\Member;
use QL\QueryList;

/**
 * Class Auth
 * @package app\controller
 * 权限相关
 */


class A extends Base
{
    public function index()
    {
        //采集某页面所有的图片
        $data = QueryList::get('http://cms.querylist.cc/bizhi/453.html')->find('img')->attrs('src');
        //打印结果
        print_r($data->all());
    }


    public function login($data = [])
    {
        $this->headData();
        return view('/index/login', $data);
    }

    /**
     * 安全退出
     */
    public function logout()
    {
        session('loginUser', null);
        session("isLogin", null);
        session("isPay", null);
        $this->redirect('/');
    }


    public function doLogin()
    {
        $p = request()->param();
        $email = $p["email"];
        $pwd = $p["pwd"];
        $phpMd5pwd = md5(trim($pwd));
        $dbMember = Member::get ( ['email' => $email]) ;
        $isSucc = 0;
        $emsg = "";
        $mysqlMd5pwd = $dbMember['pwd'];
        if ($phpMd5pwd == $mysqlMd5pwd) {
            $dbMember->isPay = $this->memberIsPay($dbMember->id);
            $this->refreshVipInfo($dbMember);
            session("loginUser", $dbMember);
            session("isLogin", true);
            $isSucc = 1;
        }
        $type = request()->param("type", "");
        if ($type == "json") {
            return json_encode(["isSucc" => $isSucc, 'emsg' => $emsg]);//,"phpMd5pwd"=>$phpMd5pwd,"mmd5"=>$mysqlMd5pwd
        } else {
            if ($isSucc === 1) {
                return $this->redirect("/");
            } else {
                return $this->error(lang("请检查邮箱和密码"), url("login"));
            }
        }
    }


    public function tolang()
    {
        $lang = input('lang');
        $toLang = "en-us";
        if ($lang == "zh-cn") {
            $toLang = "zh-cn";
        }
        cookie('think_var', $toLang);
        return $this->ajax(true);
    }

}
