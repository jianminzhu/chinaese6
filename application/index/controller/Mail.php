<?php

namespace app\index\controller;

use app\index\model\Message;
use think\Controller;
use think\Db;

class Mail extends Controller
{
        public function send()
    {
        $params = request()->param();
        $msg = new Message($params);
        $msg["send_status"] = 1;
        try {
            $msg->allowField(['from_m_id', 'to_m_id', "msg", "send_status", "type"])->save();
            return json_encode($msg->id);
        } catch (\Exception $e) {
            return json_encode($e->getMessage());
        }
    }


    public function favorite()
    {
        $params = request()->param();
        $msg = new Message($params);
        $msg["send_status"] = 1;
        try {
            $msg->allowField(['from_m_id', 'to_m_id', "msg", "send_status", "type"])->save();
            return json_encode($msg->id);
        } catch (\Exception $e) {
            return json_encode($e->getMessage());
        }
    }


    public function msgDetail()
    {
        $index = new Index();
        $index->headData();
        if ($index->isLogin()) {
            session("lastUrl", "/index/mail/fromfavorite");
            return $this->fetch('/index/msgDetail');
        } else {


        }
        return $this->fetch();
    }

    public function msglist()
    {

        $index = new Index();

        $u = $index->loginUser();
        $index = new Index();
        $index->headData();
        if ($index->isLogin()) {
            return $this->fetch('/index/msglist');
        } else {
            session("lastUrl", "/index/mail/msglist");
            return redirect('/index.php/index/a/login');
        }
    }

    public function profileSimpleData($mid)
    {
        //1 秋波 2 邮件
        return [
            "sendMailsCount" => Message::where(['from_m_id' => $mid, "type" => 2])->count(),
            "sendMails" => Message::where(['from_m_id' => $mid, "type" => 2])->select(),
            "inMails" => Message::where(['to_m_id' => $mid, "type" => 2])->select(),
            "inMailsCount" => Message::where(['to_m_id' => $mid, "type" => 2])->select(),
            "interestsCount" => Message::where(['from_m_id' => $mid, "type" => 1])->count(),
            "newInterestsCount" => Message::where(['from_m_id' => $mid, "type" => 1, "read_status" => 0])->count()
        ];
    }

    public function allMails()
    {
        $index = new Index();
        $user = $index->loginUser();
        return json_encode($this->msg(null, $user['id'], 2));
    }

    public function msg($id, $mid, $type)
    {
        $msg = Db::table('message');
        if ($id != null) {
            $msg = $msg->where('id', $id);
        }
        if ($mid != null) {
            $msg = $msg->where('from_m_id|to_m_id', $mid);
        }
        if ($type != null) {
            $msg = $msg->where('type', $type);
        }
        return $msg->select();
    }

    public function readStatus($mailId, $mid)
    {
        try {
            $msg = new Message;// save方法第二个参数为更新条件
            $msg->save([
                "read_status" => 1
            ], ['id' => $mailId, 'to_m_id' => $mid, "read_status" => 0]);
        } catch (\Exception $e) {
        }
    }
}
