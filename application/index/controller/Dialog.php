<?php

namespace app\index\controller;

use app\index\model\Member;
use think\Db;

include_once "ext_util/pinyin.php";

class Dialog extends Base
{
    public function show()
    {

        $loginUser = $this->loginUser();


    }

    public function sentMsgDialog()
    {
        if ($this->isLogin()) {
            $otherId= request()->param("otherId");
            $loginUser  = $this->loginUser();
            $other=Member::get($otherId);
            $mid = $loginUser->id;
            $msgs= Db::query("select * from message where type=2 and ( (to_m_id=? and from_m_id =?) or (to_m_id=? and from_m_id =?))",[$otherId,$mid,$mid,$other]);
            $this->assign([
                "other"=>$other,
                "msg"=>$msgs
            ]);
            return view("/index/sentMsgDialog",["other"=>$other,"u"=>$loginUser, "msgs" => $msgs,
            ]);
        }else{
            return $this->showDialogLogin();
        }


    }

}
