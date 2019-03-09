<?php

namespace app\index\controller;
require('ext_util/fileUtil.php');
require('ext_util/BytripUtil.php');

use app\index\model\Member;
use app\index\model\Pics;
use think\Controller;
use think\Db;

class M extends Controller
{

    public function reg()
    {
        return view('/index/reg');
    }


    public function savereg()
    {
        $member = new Member(request()->param());
        try {
            $member->pwd = md5($member->password);
            $member->allowField(['nickname', 'email', "pwd"])->save();
            session("loginUser", $member);
            return redirect("/", "", 302);
        } catch (\Exception $e) {
            return redirect('/')->params(['emsg' => $e->getMessage()], 302);
        }
    }

    public function profile()
    {
        $index = new Index();
        $index->headData();
        $id = request()->param("id");
        return view('/index/profile',$this->getMember($id) );
    }


    public function profiledit()
    {
        $index = new Index();
        $index->headData();
        $loginUser = session("loginUser");
        if($loginUser){
            return view('/index/profiledit', $this->getMember($loginUser->id));
        }else{
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
                    Db::table("member") -> where("id", $mid)->update(['main_pic' => $pic_path]);
                }else{
                    $pic = new Pics(["m_id" => $mid, "file_path" =>  $pic_path]);
                    $pic->save();
                }
            }
        }
        return redirect("/index.php/index/m/profiledit");
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


