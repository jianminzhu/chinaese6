<?php

namespace app\index\controller;
require('ext_util/fileUtil.php');
require('ext_util/BytripUtil.php');

use app\index\model\Member;
use app\index\model\Pics;
use think\Db;

class M extends Base
{

    public function reg($data = ["param" => [], "emsgs" => []])
    {
        return view('/index/reg', $data);
    }


    public function savereg()
    {
        $param = request()->param();
        $emsgs = [];
        $email = request()->param("email");
        $pwd = request()->param("pwd");
        if (!$email && trim($email) === "") {
            $emsgs["email"] = lang("邮箱必须填写");
        }
        if (!$pwd) {
            $emsgs["pwd"] = lang("密码必须填写");
        } else {
            if (strlen($pwd) < 6) {
                $emsgs["pwd"] = lang("密码必须大于等于6位");
            }
        }
        if (count($emsgs) == 0) {
            $count = Db::table("member")->where("email", $email)->count();
            if ($count > 0) {
                $emsgs["email"] = lang("邮箱已经被注册");
            } else {
                try {
                    $nickname = request()->param("nickname");
                    $sex = request()->param("sex");
                    $age = request()->param("age");
                    $data = [
                        "nickname" => $nickname,
                        "sex" => $sex,
                        "age" => $age,
                        "email" => trim($email),
                        "pwd" => md5($pwd)
                    ];
                    Db::table("member")->insert($data);
                } catch (\Exception $e) {
                    $emsgs["db"] = lang("系统异常，请稍后重试");
                }
            }
        }
        if (count($emsgs) == 0) {
            return view('/index/login', ["email" => $email, "pwd" => $pwd]);
        } else {
            return $this->reg(["param" => $param, "emsgs" => $emsgs]);
        }
    }


    public function isPay()
    {
        try {
            $loginUser = $this->loginUser();
            $query = Db::table("pay")->where("m_id", $loginUser->id);
            $count = $query-> count("id");
            return $this->ajax($count > 0);
        } catch (\Exception $e) {
            return $this->ajax(false,["mid"=>$loginUser->id,"emsg"=>$e->getMessage()]);
        }
    }

    public function profile()
    {
        $index = new Index();
        $index->headData();
        $id = request()->param("id");
        return view('/index/profile', $this->getMember($id));
    }


    public function profiledit()
    {
        $index = new Index();
        $index->headData();
        $loginUser = session("loginUser");
        if ($loginUser) {
            return view('/index/profiledit', $this->getMember($loginUser->id));
        } else {
            session("lastUrl", "/index.php/index/index/m/profiledit");
            return redirect('/index.php/index/a/login');
        }
    }

    /**
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMember($id)
    {
        $member = Member::get($id);
        $pics = Db::table("pics")->where("m_id", $id)->select();
        return ['m' => $member, "pics" => $pics];
    }


    //上传照片
    public function uploadPhoto()
    {
        if (session('?loginUser')) {
            $loginUser = session("loginUser");
            session("loginUser");
            $pic_path = $this->saveUploadPhoto();
            $type = request()->param("type");
            if ($pic_path) {
                $pic_path = "/Uploads/Picture/" . $pic_path;
                $mid = $loginUser->id;
                if ($type == "main") {
                    $loginUser->main_pic = $pic_path;
                    Db::table("member")->where("id", $mid)->update(['main_pic' => $pic_path]);
                } else {
                    $pic = new Pics(["m_id" => $mid, "file_path" => $pic_path]);
                    $pic->save();
                }
            }
        }
        return redirect("/index.php/index/m/profiledit");
    }  //上传照片

    public function uploadInfo()
    {
        if (session('?loginUser')) {
            $loginUser = session("loginUser");
            $mid = $loginUser->id;
            $member = new Member();
            $this->unsetItem("cityid", -1);
            $this->unsetItem("stateid", -1);
            $this->unsetItem("countryid", -1);
            $member->allowField(true)->save($_REQUEST, ['id' => $mid]);
        }
        return redirect("/index.php/index/m/profiledit");
    }

    public function unsetItem($key, $compare)
    {
        try {
            if ($_REQUEST[$key] == $compare) {
                unset($_REQUEST[$key]);
            }
        } catch (\Exception $e) {
        }
    }


    public function deletePhoto()
    {
        $id = request()->param("id");
        if ($id && session('?loginUser')) {
            $loginUser = session("loginUser");
            $table_pics = Db::table('pics');
            $table_pics->where('id', $id);
            $table_pics->where('m_id', $loginUser->id);
            $table_pics->delete();
        }
        return redirect("/index.php/index/m/profiledit");
    }


    /**
     * @return mixed
     */
    public function saveUploadPhoto()
    {
        $file = request()->file('image');
        if ($file) {
            $info = $file->move($this->getRoot() . '/Uploads/Picture/');
            if ($info) {
                return str_replace('\\', '/', $info->getSaveName());
            } else {
                return "";
            }
        }
    }

    function getRoot($file = ".")
    {
        $fileUrl = str_replace('\\', '/', realpath(dirname($file) . '/')) . "/";
        return $fileUrl;
    }
}


