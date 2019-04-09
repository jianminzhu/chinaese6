<?php

namespace app\index\controller;

include_once "stripe/init.php";


use think\Log;

function uuid($prefix = '')
{
    $chars = md5(uniqid(mt_rand(), true));
    $uuid = substr($chars, 0, 8) . '-';
    $uuid .= substr($chars, 8, 4) . '-';
    $uuid .= substr($chars, 12, 4) . '-';
    $uuid .= substr($chars, 16, 4) . '-';
    $uuid .= substr($chars, 20, 12);
    return $prefix . $uuid;
}

class Pay extends Base
{

    function test()
    {
        $this->headData();
        echo date("Y-m-d H:i:s", time()) . uuid();
        return "";
    }

    function realStripe($token)
    {
        $isSucc = false;
//        $Secret = "sk_test_yD1UwUeF99VI5S5hKFHQAvGL00dWlCK5LX";
        $Secret = "sk_live_yYj5Td70UqufoFzOydps5h9u00jjVFsKNy";//LINE
        $payType= request()->param("payType");
        $cost = $payType == "lifetime" ? 599:149;
        $type=$payType == "lifetime" ? 10:1;
        $stripePayDetail = "";
        try {
            \Stripe\Stripe::setApiKey($Secret);
            $charge = \Stripe\Charge::create(array(
                "amount" => $cost,
                "currency" => "usd",
                "source" => $token,
            ));
            $mid = "";
            try {
                $mid = $this->loginUser()->id;
            } catch (\Exception $e) {
            }
            $stripePayDetail = json_encode($charge);
            $date = date("Y-m-d H:i:s", time());
            $uuid = uuid();
            Log::record("strippay,mid: $mid,date:$date,uuid:$uuid ,stripePayDetail:  $stripePayDetail,");
            if ($charge["status"] == "succeeded") {
                $isSucc = true;
                try {
                    db("stripe_pay")->insert(["mid" => $mid, "cost" => $charge["amount"], "details" => $stripePayDetail, "uuid" => $uuid]);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
                try {
                    $startdate = gmdate("Y-m-d H:i:s", time() + 8 * 3600);
                    $enddate = gmdate("Y-m-d H:i:s", strtotime("+$type  year") + 8 * 3600);
                    $table = Db::table("pay");
                    $table->insert(["m_id" => $mid, "cost" => $cost, "type" => $type, "startdate" => $startdate, "enddate" => $enddate]);
                } catch (\Exception $e) {
                    echo $e->getMessage();

                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return array($isSucc, $stripePayDetail);
    }


    function payStripe()
    {
        //有数据返回
        $token = $_POST['stripeToken'];
        $isSucc = false;
        $msg = lang("支付失败");
        if ($token) {
            list($isSucc, $stipeDetail) = $this->realStripe($token);
            if ($isSucc) {
                $this->headData();
                return view("/index/paysucc");
            }
        }
        $this->headData();
        return view("/index/paysucc",["msg"=>$msg,"stipeDetail"=>$stipeDetail,"param"=>request()->param()]);

    }
}



