<?php

namespace app\index\controller;

use app\index\model\Member;
use app\index\model\Message;
use think\Db;

class Mail extends Base
{


    public function send()
    {
        if ($this->isLogin()) {
            $params = request()->param();
            $msg = new Message($params);
            $loginUser = $this->loginUser();
            $msg["from_m_id"] = $loginUser->id;
            $msg["send_status"] = 1;
            try {
                $msg->allowField(['from_m_id', 'to_m_id', "msg", "send_status", "type"])->save();
            } catch (\Exception $e) {
                return $this->ajax(true, lang("发送失败"));
            }
        }
        return $this->ajax(true, lang("发送成功"));
    }
    public function canSend()
    {
        $isSucc = false;
        if ($this->isLogin()) {
            $fromid = request()->param("fromId");
            $toid = request()->param("toId");
            $toidIsPay= $this->memberIsPay($toid);
            $fromidIsPay= $this->memberIsPay($fromid);
            if ($toidIsPay || $fromidIsPay) {
                $isSucc = true;
            }
        }
        return $this->ajax($isSucc, lang("cansend"));
    }


    public function msgDetail()
    {
        $mid = request()->param("mid");
        if ($this->isLogin()) {
            if ($mid) {
                $other = Member::get($mid);
                if ($other) {
                    $this->headData();
                    $u = $this->loginUser();
                    $myMid = $u->id;
                    session("lastUrl", "/index/mail/fromfavorite");
                    $sql = "
                        SELECT *
                        FROM
                          message AS msg 
                         where  (from_m_id=$mid and  to_m_id=$myMid) or (from_m_id=$myMid and to_m_id=$mid) 
                        ORDER BY send_date asc  
                        ";
                    $msgs = Db::query($sql);
                    $arr = ["m" => $other, "my" => $u, "msgs" => count($msgs) > 0 ? $msgs : []];
                    return view('/index/msgDetail', $arr);
                }
            }
        }
    }
    public function msgDetailAjax()
    {
        $mid = request()->param("mid");
        if ($this->isLogin()) {
            if ($mid) {
                $other = Member::get($mid);
                if ($other) {
                    $this->headData();
                    $u = $this->loginUser();
                    $myMid = $u->id;
                    session("lastUrl", "/index/mail/fromfavorite");
                    $sql = "
                        SELECT *
                        FROM
                          message AS msg 
                         where  (from_m_id=$mid and  to_m_id=$myMid) or (from_m_id=$myMid and to_m_id=$mid) 
                        ORDER BY send_date asc  
                        ";
                    $msgs = Db::query($sql);
                    $arr = ["m" => $other, "my" => $u, "msgs" => count($msgs) > 0 ? $msgs : []];
                    return view('/index/msgDetail_Ajax', $arr);
                }
            }
        }
    }


    public function msglist()
    {
        $index = new Index();
        $index->headData();
        if ($index->isLogin()) {
            $u = $index->loginUser();
            $myMid = $u->id;
            $sql = "
SELECT
  fm.id AS f_mid,
  fm.nickname AS f_nickname,
  fm.main_pic AS f_main_pic,
  fm.age AS f_age,
  fm.address AS f_address, 
  
  tm.id AS t_mid,
  tm.nickname AS t_nickname,
  tm.main_pic AS t_main_pic,
  tm.age AS t_age,
  tm.address AS t_address, 
  msg.*
FROM
  message AS msg
  LEFT JOIN member AS tm
    ON tm.id = msg.`to_m_id`
  LEFT JOIN member AS fm
    ON fm.id = msg.`from_m_id` 
";
            $receiveMsgs = Db::query($sql . "WHERE tm.id = $myMid GROUP BY f_mid ORDER BY  send_date DESC");
            $sentMsgs = Db::query($sql . "WHERE fm.id = $myMid GROUP BY t_mid ORDER BY  send_date DESC");
            return view('/index/msglist', ["receiveMsgs" => $receiveMsgs, "sentMsgs" => $sentMsgs]);
        } else {
            session("lastUrl", "/index/mail/msglist");
            return redirect('/index.php/index/a/login');
        }
    }
}
