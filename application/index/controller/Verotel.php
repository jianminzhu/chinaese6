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
                }
            }
            $this->headData();
            return view("/index/paysucc");
        } catch (\Exception $e) {
        }
        return redirect("/index.php/index/m/upgrade");
    }

    public function cancel()
    {
        echo json_encode(request()->param());

    }
}



