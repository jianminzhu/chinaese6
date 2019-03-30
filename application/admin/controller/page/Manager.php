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


}
