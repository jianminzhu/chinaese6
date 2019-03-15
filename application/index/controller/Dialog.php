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
            if ($otherId != $mid) {
                $msgs= Db::query("select * from message where type=2 and ( (to_m_id=? and from_m_id =?) or (to_m_id=? and from_m_id =?))",[$otherId,$mid,$mid,$otherId]);
                $arr = [
                    "other" => $other,
                    "msg" => $msgs
                ];
                $this->assign($arr);
                $other["isPay"]= $this->memberIsPay($other->id);
                $loginUser["isPay"]= $this->memberIsPay($loginUser->id);
                return view("/index/sentMsgDialog",["other"=>$other,"u"=>$loginUser, "msgs" => $msgs, "isSelf" => false,
                ]);
            }
            return view("/index/sentMsgDialog",["other"=>$other,"u"=>$loginUser, "isSelf" => true,
            ]);
        }else{
            return $this->showDialogLogin();
        }
    }

}
