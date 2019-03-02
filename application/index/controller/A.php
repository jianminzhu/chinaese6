<?php

namespace app\index\controller;

use app\index\model\Member;
use think\Controller;
use think\Session;
use think\Lang;

/**
 * Class Auth
 * @package app\controller
 * 权限相关
 */
class A extends Controller
{

    public function login()
    {
        return view('/index/login');
    }


    /**
     * 安全退出
     */
    public function logout(){
        session('loginUser', null);

        session("isLogin", null);
        $this->redirect('/index/index');
    }

    public function doLogin()
    {
        $p = request()->param();
        $email = $p["email"];
        $pwd = $p["pwd"];
        $phpMd5pwd = md5(trim($pwd));
        $dbMember = Member::get(['email' => $email]);
//        $dbMember = Db::table("member")->where("email",$email)->find( );
        $isSucc = 0;
        $emsg = "";
        $mysqlMd5pwd = $dbMember['pwd'];
        if ($phpMd5pwd == $mysqlMd5pwd) {
            session("loginUser", $dbMember);
            session("isLogin", true);
            $isSucc = 1;
        }


        $type = request()->param("type", "");
         if ($type == "json") {
             return json_encode(["isSucc" => $isSucc, 'emsg' => $emsg]);//,"phpMd5pwd"=>$phpMd5pwd,"mmd5"=>$mysqlMd5pwd
         }else{
            if ($isSucc === 1) {
                $lastUrl = session("lastUrl");

                return redirect("/");
            } else {
                return $this->error( "邮箱密码不匹配" ,url("login")  ) ;
            }
        }
    }





    public function tolang() {
        $lang=input('lang');
        switch ($lang) {
            case 'en':
                cookie('think_var', 'en-us');
                break;
            case 'zn':
                cookie('think_var', 'zh-cn');
                break;
            default:
                cookie('think_var','zh-cn');
                break;
        }
    }

    public function showforgotpassword()
    {
        return view('/index/forgotpassword');
    }

}
