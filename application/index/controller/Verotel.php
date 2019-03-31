<?php

namespace app\index\controller;

require_once __DIR__ . './ext_util/flexpay/FlexpayUtil.php';
require_once __DIR__ . './ext_util/fileUtil.php';

class Verotel extends Base
{

    public function pay()
    {
        if ($this->isLogin()) {
            $url = payurl(125, "1 Year VIP Membership");
            $mid = $this->loginUser()->id;
            db("verotel_user")->insert([
                "mid" => $mid,
                "md5" => md5($url),
                "url" => $url
            ]);
            $this->redirect($url);
        } else {
            $this->redirect("/index.php/index/a/login");
        }
    }

 /*   public function test()
    {
        echo ExtGetHtml("http://travelling.chinesecompanion.com/index.php/index/verotel/succ?paymentMethod=CC&priceAmount=100&priceCurrency=EUR&saleID=18426632&shopID=115404&type=purchase&signature=0d0494359023b21a38e1f6844765f799e257523e");
    }

    public function testS()
    {
        $jsonstr = '{"paymentMethod":"CC","priceAmount":"2.64","priceCurrency":"EUR","saleID":"18426319","shopID":"115404","type":"purchase","signature":"ec0e2601184c1ca55376bcd95bcb0135a81c2c25"}';
        $realParams = parseJsonParas($jsonstr);
        echo "[[[[[" . validate_signature($realParams) . "]]]]";
    }*/

    public function succ()
    {

        try {
            $param = request()->param();
            if (validate_signature($param)) {
                db("verotel_pay")->insert($param);
            }
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



