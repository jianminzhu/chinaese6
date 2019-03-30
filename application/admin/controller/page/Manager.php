<?php

namespace app\admin\controller\page;

use app\common\controller\Backend;
use app\index\controller\Index;
use think\Db;

class Manager extends Backend
{

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $this->assign([
            "pays" => $msgs = Db::query("SELECT  p.cost,p.startdate,p.`enddate` ,m.* FROM pay AS p  LEFT JOIN member AS m ON p.m_id=m.id "),
        ]);
        return view("page/paylist");
    }
    public function members()
    {
        $index = new Index();
        $index->headData();
        return view("page/memberList");
    }

    public function delpics(){
        $mid = request()->param("mid");
        $picid = request()->param("picid");
        if ($mid &&$mid != "undefined") {
            $m = Db::table("member")->where("id", $mid)->find() ;
            if ($m) {
                try {
                    Db::table("del_pics")->insert(["mid" => $mid, "file_path" => $m["main_pic"], "type" => "main", "picid" => 0]);
                    Db::table("member")->where("id", $mid)->update(["main_pic"=> "/nv_toux_del.jpg"]);
                } catch (\Exception $e) {
                }
            }
        }
        if($picid && $picid != "undefined") {
            try {
                $pic = Db::table("pics")->where("id", $picid)->find();
                if ($pic) {
                    Db::table("del_pics")->insert(["file_path" => $pic["file_path"],"picid"=>$picid, "type" => "pics", "mid" => $pic["m_id"]]);
                    Db::table("pics")->where("id", $picid)->delete();
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        return json(["isSuccess" => 0]);

    }


}
