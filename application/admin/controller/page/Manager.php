<?php

namespace app\admin\controller\page;

use app\common\controller\Backend;
use app\index\controller\Base;
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

    public function msglist()
    {
        $where ="";
        $fid = request()->param("fid");
        $param = [];
        if ($fid) {
            $where = $where . " and  from_m_id=?";
            $param[] = $fid;
        }

        $tid = request()->param("tid");
        if ($tid) {
            $where = $where . " and  to_m_id=?";
            $param[] = $tid;
        }

        $data= Db::query("SELECT
mf.`nickname` AS fnickname ,
mf.`id` AS fid ,
mf.`main_pic` AS fmain_pic ,
mf.`age` AS fage ,
mf.`city` AS fcity,
mt.`nickname` AS tnickname ,
mt.`id` AS tid ,
mt.`main_pic` AS tmain_pic ,
mt.`age` AS tage ,
mt.`city` AS tcity,
ms.msg ,
ms.`send_date`  
FROM message ms LEFT JOIN member mf ON mf.id=ms .`from_m_id`  
 LEFT JOIN member mt ON mt.id=ms .`to_m_id` 
 WHERE 1=1 $where 
 ORDER BY  from_m_id ,to_m_id,send_date desc  ",$param);
        return view("page/msglist",["msgs"=>    $data]);
    }

    public function delpics()
    {
        $mid = request()->param("mid");
        $picid = request()->param("picid");
        if ($mid && $mid != "undefined") {
            $m = Db::table("member")->where("id", $mid)->find();
            if ($m) {
                try {
                    Db::table("del_pics")->insert(["mid" => $mid, "file_path" => $m["main_pic"], "type" => "main", "picid" => 0]);
                    Db::table("member")->where("id", $mid)->update(["main_pic" => "/nv_toux_del.jpg"]);
                } catch (\Exception $e) {
                }
            }
        }
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
        return json(["isSuccess" => 0]);

    }

    public function updatesort()
    {
        $mid = request()->param("mid");
        $sort = intval(request()->param("sort"));
        db("member")->where("id", $mid)->update(["sort" => $sort]);
        return Base::ajax(true);
    }

    public function delmember()
    {
        $mid = request()->param("mid");
        if ($mid && $mid != "undefined") {
            $m = Db::table("member")->where("id", $mid)->find();
            if ($m) {
                try {
                    Db::table("member_del")->insert($m);
                    Db::table("member")->where("id", $mid)->delete();
                } catch (\Exception $e) {
                }
            }
        }
        return json(["isSuccess" => 0]);
    }


}
