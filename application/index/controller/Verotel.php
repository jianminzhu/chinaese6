<?php

namespace app\index\controller;

require_once 'ext_util/flexpay/FlexpayUtil.php';
require_once 'ext_util/fileUtil.php';

class Verotel extends Base
{

    public function pay()
    {
        if ($this->isLogin()) {
            $url = payurl(99, "1 Year VIP Membership");
            $mid = $this->loginUser()->id;
            try {
                db("verotel_user")->insert([
                    "mid" => $mid,
                    "md5" => md5($url),
                    "url" => $url
                ]);
            } catch (\Exception $e) {
            }
            $this->redirect($url);
        } else {
            $this->redirect("/index.php/index/a/login");
        }
    }


    public function succ()
    {

        try {
            $param = request()->param();
            if (validate_signature($param)) {
                try {
                    db("verotel_pay")->insert($param);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
            $this->headData();
            return "支付成功";//view("/index/paysucc",["data"=>json_encode($param)]);
        } catch (\Exception $e) {
        }
        return "从新支付";
    }

    public function cancel()
    {
        echo json_encode(request()->param());

    }
}



