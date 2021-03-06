<?php

namespace app\index\controller;
require('ext_util/fileUtil.php');
require('ext_util/BytripUtil.php');

use app\index\model\Member;
use app\index\model\Pics;
use PHPMailer;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\facade\Config;
use think\Validate;

class M extends Base
{

    public function reg($data = ["param" => [], "emsgs" => []])
    {
        $this->headData();
        return view('/index/reg', $data);
    }

    public function updateNickname()
    {
        $pno = intval(request()->param("pno", 1));
        if ($pno < 0) {
            $pno = 1;
        }
        $pageSize = intval(request()->param("__psize", 500));
        $members = Db::table("member")->page("$pno,$pageSize")->select();
        $count = 0;
        foreach ($members as $member) {
            $nickname = $member["nickname"];
            $nickname_en = (pinyinName($nickname));
            $id = $member["id"];
            $sql = "update member set nickname_en='$nickname_en' where id=$id;";
            $count = $count + Db::execute($sql);
            echo "<br>$nickname => $nickname_en";
        }
        return "$count,$pno,$pageSize";
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
        $this->headData();
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
            $isPay = $this->loginUser()->isPay;
            $this->headData();
            $mid = request()->param("mid");
            list($cc, $concats) = $this->concatData($mid, $isPay);
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
        $member = $this->getMemberWithPay($id);
        $pics = Db::table("pics")->where("m_id", $id)->select();
        $emsg = "";
        list($vip, $isPay) = $this->vipInfo($id);
        $logigUserIsPay = false;
        $isLogin = $this->isLogin();
        if ($isLogin) {
            $logigUserIsPay = $this->loginUser()->isPay;
        }
        list($cc) = $this->concatData($id, $logigUserIsPay);
        return ['m' => $member, "pics" => $pics, "cc" => $cc, "emsg" => $emsg, "isPay" => $isPay, "vip" => $vip, "isLogin" => $isLogin];
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
    }


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

            $concats = Db::table("membercontact")->where("uid", $uid)->select();
            foreach ($concats as $concat) {
                $type = $types[$concat["type"]];
                $types[$concat["type"]]["isInDb"] = true;
                $updateItems = ['number' => $this->param($type["fname"], "")];
                try {
                    Db::table("membercontact")
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
                        Db::table("membercontact")->insert($data);
                    } catch (\Exception $e) {
                    }
                }
            }
            $member->allowField(true)->save($_REQUEST, ['id' => $uid]);
            $this->changeSessionLoginUser($this->getMemberWithPay($uid));

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
        $picid = request()->param("id");
        if ($picid && $picid != "undefined") {
            try {
                $pic = Db::table("pics")->where("id", $picid)->find();
                if ($pic) {
                    Db::table("del_pics")->insert(["file_path" => $pic["file_path"], "picid" => $picid, "type" => "pics", "mid" => $pic["m_id"]]);
                    Db::table("pics")->where("id", $picid)->delete();
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
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
        $table = "membercontact";
        $concats = Db::table($table)->where("uid", $id)->select();
        foreach ($concats as $concat) {
            try {
                $cc[$concat["type"]] = $isPay ? $concat["real_number"] : substr_replace($concat["number"], '****', 3, 4);
            } catch (\Exception $e) {
            }
        }
        return array($cc, $concats);
    }


    /**
     * @param $id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public function getMemberWithPay($id)
    {
        $member = Member::get($id);
        if ($member) {
            $member->isPay = $this->memberIsPay($id);
        }
        return $member;
    }

    public function sendMail($toEmail, $subject, $body)
    {
        $config = config("ext_config.mail");
        $host = $config['host'];
        $username = $config['username'];
        $password = $config['password'];
        $port = $config['port'];
        $SMTPSecure = $config['SMTPSecure'];

        $fromEmail = $username;
        $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $host;
        $mail->SMTPSecure = $SMTPSecure;
        $mail->Port = $port;
        $mail->CharSet = 'UTF-8';
        $mail->FromName = '';
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->From = $fromEmail;
        $mail->isHTML(true);
        $mail->addAddress($toEmail);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $status = $mail->send();
        return $status;

    }

    public function sendResetPasswordMail()
    {
        $isSucc = false;
        $email = request()->param("email");
        $rule = [
            'email' => 'email'
        ];

        $data = [
            'email' => $email
        ];
        $msg = ["email.email" => "请输入正确的邮箱"];
        $v = new Validate($rule, $msg);
        $isPassCheck = $v->check($data);
        $emsg = $v->getError();
        if ($isPassCheck) {
            $member = Db::table("member")->where("email", $email)->find();
            if (!$member) {
                $emsg = lang("此邮箱不存在");
            } else {
                $member = Db::table("member")->where("email", $email)->find();
                list($isSucc, $title, $content, $token) = $this->genResetPassWordContext($member);
                if ($isSucc) {
                    Db::table("member")->where("email", $email)->update(["token" => $token, "getpasstime" => date("Y-m-d H:i:s", time())]);
                    $isSucc = $this->sendMail($email, $title, $content);
                    if (!$isSucc) {
                        $emsg = lang("邮件发送失败，请联系管理员");
                    }
                } else {
                    $emsg = lang("系统出现故障，请联系管理员");
                }
            }
        }

        $this->headData();
        if ($isSucc) {
            return view("/index/passwordMailSentSucc");
        } else {
            return view("/index/passwordForget", ["emsg" => $emsg]);
        }
    }

    private function genResetPassWordContext($member)
    {
        $isSucc = true;
        $content = "";
        try {
            $email = $member["email"];
            $token = md5($member["pwd"] . $email);
            $lang = $this->getLang();
            $domain = config("ext_config.domain");
            $url = $domain . "/index.php/index/m/resetPassword?token=$token&lang=$lang";
            $content = $this->render('/index/_resetpassword', ["name" => $member["nickname"], "email" => $email, "url" => $url]);
        } catch (\Exception $e) {
            $isSucc = false;
        }
        return array($isSucc, "chinesecompanion.com " . lang("重置密码"), $content, $token);
    }


    public function passwordForget()
    {
        return view('/index/passwordForget');
    }

    function updatePassword()
    {
        $rule = [
            'pwd' => 'require|min:6|max:20',
            'pwd_confirm' => 'require|confirm:pwd',
            'token' => 'require'
        ];

        $msg = [
            'pwd.require' => '密码不能为空',
            'pwd_confirm.require' => '确认密码不能为空',
            'pwd.max' => '密码最长20位',
            'pwd.min' => '密码最少6位',
            'pwd_confirm.confirm' => '两次密码不一致'
        ];
        $token = request()->param("token");
        $pwd = request()->param("pwd");
        $data = [
            'pwd' => $pwd,
            'pwd_confirm' => request()->param("pwd_confirm"),
            'token' => $token,
            'ischeck' => 1
        ];
        $v = new Validate($rule, $msg);

        $isPassCheck = $v->batch()->check($data);
        $error = $v->getError();
        function def($arr, $key, $def = "")
        {
            try {
                return $arr[$key] ? $arr[$key] : $def;
            } catch (\Exception $e) {
                return $def;
            }
        }

        $data["error"] = [
            "pwd" => def($error, "pwd"),
            "pwd_confirm" => def($error, "pwd_confirm"),
            "token" => def($error, "token")
        ];
        $email = "";
        $isUpdateSucc = false;
        if ($isPassCheck) {
            $m = Db::table("member")->where("token", $token)->find();
            if ($m) {
                Db::table("member")->where("token", $token)->update(["pwd" => md5($pwd)]);
                $isUpdateSucc = true;
                $email = $m["email"];

            } else {
                $data["error"]["token"] = "请从邮箱打开些链接";
            }
        }

        $this->headData();
        if ($isUpdateSucc) {
            return view("/index/login", ["email" => $email]);
        } else {
            return view("/index/passwordReset", $data);
        }
    }

    function resetPassword()
    {
        $this->headData();
        return view("/index/passwordReset", ["token" => request()->param("token")]);
    }


}


