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
            $this->updateUserPayStatus();
            $this->headData();

            return "支付成功";//view("/index/paysucc",["data"=>json_encode($param)]);
        } catch (\Exception $e) {
        }
        return "从新支付";
    }
    public function updateUserPayStatus(){
        try {
            echo json_encode(request()->param());
            if ($this->isLogin()) {
                $mid = $this->loginUser()->id;
                $items= db("verotel_user")->where("mid",$mid)->where("is_pay_succ",0)->select();
                $html = "";
                foreach ($items as $item) {
                    try {
                        $html = ExtGetHtml($items["url"]);
                        echo "succ".json_encode($html);
                        $params=json_decode($html);
                        if (validate_signature($params)) {
                            $saleID = $params["saleID"];
                            db("verotel_user")->where("mid", $mid)->where("is_pay_succ", 0)->update(["saleID" => $saleID, "is_pay_succ" => "1"]);
                            db("pay")->insert(["m_id"=>$mid,"type"=>1,"cost"=>$params["priceAmount"]]);
                            echo "succ ";
                        }
                    } catch (\Exception $e) {
                        echo "-----".$e->getMessage().$html;
                    }
                }
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function cancel()
    {
        echo json_encode(request()->param());

    }
}



