<?php

namespace app\index\controller;
require('ext_util/fileUtil.php');
require('ext_util/BytripUtil.php');

use app\index\model\Member;
use app\index\model\Pics;
use think\Db;
use think\Exception;
use think\exception\PDOException;

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

    public function favorite()
    {
        $emsg = lang("操作成功");
        $to_mid = request()->param("to_mid");
        $isSucc = false;
        $addClass = "";
        $removeCss = "";
        if ($this->isLogin() && $to_mid) {
            $lu = $this->loginUser();
            $mid = $lu->id;
            if ($mid != $to_mid) {
                try {
                    Db::table("favorite")->insert(["mid" => $mid, "to_mid" => $to_mid]);
                    $isSucc = true;
                    $addClass = "fill-action-highlight";
                    $removeCss = "fill-action-unhighlight";
                } catch (\Exception $e) {
                    try {
                        Db::execute('DELETE FROM `favorite` WHERE  `mid` = :mid  AND `to_mid` = :to_mid ', [$mid, $to_mid]);
                        $isSucc = true;
                        $addClass = "fill-action-unhighlight";
                        $removeCss = "fill-action-highlight";
                    } catch (Exception $e2) {
                    }
                }
            }
        }
        return $this->ajax($isSucc, ["emsg" => $emsg, "addClass" => $addClass, "removeClass" => $removeCss]);
    }

    public function upgradeDialog()
    {
        if ($this->isLogin()) {
            return view("/index/upgradeDialog");
        }
        return redirect("/index.php/index/a/login");
    }

    public function concat()
    {
        if ($this->isLogin()) {
            $this->headData();
            $mid = request()->param("mid");
            list($cc, $concats) = $this->concatData($mid, $this->isPay());
            $this->assign(["m" => Member::get(["id" => $mid]),
                "cc" => $cc,
                "concats" => $concats
            ]);
            return view("/index/concatsDialog");
        }
        return redirect("/index.php/index/a/login");
    }

    public function upgrade()
    {
        if ($this->isLogin()) {
            $this->headData();
            return view("/index/upgrade");
        }
        return redirect("/index.php/index/a/login");
    }

    public function active()
    {
        if ($this->isLogin()) {
            $this->headData();
            $lu = $this->loginUser();
            $myMid = $lu->id;

            $activeData = $this->activeData($myMid);
            $this->assign($activeData);

            return view("/index/active");
        }
        return redirect("/index.php/index/a/login");

    }

    public function interest()
    {
        $lu = $this->loginUser();
        $to_mid = request()->param("to_mid");
        try {
            Db::table("interest")->insert(["mid" => $lu->id, "to_mid" => $to_mid]);
        } catch (\Exception $e) {
        }
        return $this->ajax(true, lang("发送成功"));
    }

    public function isPay()
    {
        $mid = "";
        try {
            $loginUser = $this->loginUser();
            $mid = $loginUser->id;
            $isPay = $this->memberIsPay($mid);
            return $this->ajax($isPay);
        } catch (\Exception $e) {
            return $this->ajax(false, ["mid" => $mid, "emsg" => $e->getMessage()]);
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
        if ($member) {
            $member->isPay = $this->memberIsPay($id);
        }
        $pics = Db::table("pics")->where("m_id", $id)->select();

        $emsg = "";
        try {
            $isPay = false;
            if ($this->isLogin()) {
                $isPay = $this->loginUser()->isPay;
            }
            list($cc) = $this->concatData($id, $isPay);
        } catch (\Exception $e) {
            $emsg = $e->getMessage();
        }
        echo "---------------------------".$isPay."===============";
        return ['m' => $member, "pics" => $pics, "cc" => $cc, "emsg" => $emsg,"isPay"=>$isPay];
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

    function param($name, $default = "")
    {
        $value = request()->param($name);
        if (!$value) {
            $value = $default;
        }
        return $value;
    }

    public function uploadInfo()
    {
        if ($this->isLogin()) {
            $loginUser = session("loginUser");
            $uid = $loginUser->id;
            $member = new Member();
            $this->unsetItem("cityid", -1);
            $this->unsetItem("stateid", -1);
            $this->unsetItem("countryid", -1);

            $types = [
                "手机" => [
                    "fname" => "concat_mobile",
                    "isInDb" => false
                ], "邮箱" => [
                    "fname" => "concat_email",
                    "isInDb" => false
                ], "QQ" => [
                    "fname" => "concat_qq",
                    "isInDb" => false
                ], "微信" => [
                    "fname" => "concat_wechat",
                    "isInDb" => false
                ]
            ];

            $concats = Db::table("membercontacts")->where("uid", $uid)->select();
            foreach ($concats as $concat) {
                $type = $types[$concat["type"]];
                $types[$concat["type"]]["isInDb"] = true;
                $updateItems = ['number' => $this->param($type["fname"], "")];
                try {
                    Db::table("membercontacts")
                        ->where('uid', $uid)
                        ->where("type", $concat["type"])
                        ->update($updateItems);
                } catch (PDOException $e) {
                } catch (Exception $e) {
                }
            }
            foreach ($types as $key => $type) {
                if ($type["isInDb"] == false) {
                    $data = [
                        "type" => $key,
                        "number" => $this->param($type["fname"], ""),
                        "uid" => $uid
                    ];
                    try {
                        Db::table("membercontacts")->insert($data);
                    } catch (\Exception $e) {
                    }
                }
            }
            $member->allowField(true)->save($_REQUEST, ['id' => $uid]);
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

    function activeDataAjax()
    {
        return $this->ajax(true, $this->activeData("72543"));
    }

    /**
     * @param $myMid
     * @return array
     */
    public function activeData($myMid)
    {
        $vMeIds = db("vistor")->where("to_mid", $myMid)->group("mid")->order("create_time desc")->column("mid");
        $fMeIds = db("favorite")->where("to_mid", $myMid)->group("mid")->order("create_time desc")->column("mid");
        $iMeIds = db("interest")->where("to_mid", $myMid)->group("mid")->order("create_time desc")->column("mid");
        $myVIds = db("vistor")->where("mid", $myMid)->group("to_mid")->order("create_time desc")->column("to_mid");
        $myFIds = db("favorite")->where("mid", $myMid)->group("to_mid")->order("create_time desc")->column("to_mid");
        $myIIds = db("interest")->where("mid", $myMid)->group("to_mid")->order("create_time desc")->column("to_mid");
        $activeData = [
            "vMeLst" => count($vMeIds) > 0 ? db("member")->where("id", "in", $vMeIds)->select() : [],
            "fMeLst" => count($fMeIds) > 0 ? db("member")->where("id", "in", $fMeIds)->select() : [],
            "iMeLst" => count($iMeIds) > 0 ? db("member")->where("id", "in", $iMeIds)->select() : [],
            "myVLst" => count($myVIds) > 0 ? db("member")->where("id", "in", $myVIds)->select() : [],
            "myFLst" => count($myFIds) > 0 ? db("member")->where("id", "in", $myFIds)->select() : [],
            "myILst" => count($myIIds) > 0 ? db("member")->where("id", "in", $myIIds)->select() : [],
        ];
        return $activeData;
    }

    /**
     * @param $id
     * @param $table
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function concatData($id, $isPay = false)
    {
        $cc = ["手机" => "",
            "邮箱" => "",
            "QQ" => "",
            "微信" => ""
        ];
        $table =  "membercontacts"  ;
        $concats = Db::table($table)->where("uid", $id)->select();
        foreach ($concats as $concat) {
            try {
                $cc[$concat["type"]] = $isPay ? $concat["number"] : substr_replace($concat["number"], '****', 3, 4);
            } catch (\Exception $e) {
            }
        }
        return array($cc, $concats);
    }
}


