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

    function payType()
    {
        $payType = request()->param("type");
        if ($payType == "year") {
            $cost = 5;
            session("cost", $cost);
        } else {
            $cost = 499;
            session("cost", $cost);
        }
        return $cost;
    }

    function realStripe($token)
    {
        $isSucc = false;
//        $Secret = "sk_test_yD1UwUeF99VI5S5hKFHQAvGL00dWlCK5LX";
        $Secret = "sk_live_yYj5Td70UqufoFzOydps5h9u00jjVFsKNy";//LINE
        $cost = session("cost");
        $charge = [];
        if (!$cost) {
            $cost = 169;
        }
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
                $typePayByStripe = 10;
                try {
                    db("pay")->insert(["m_id" => $mid, "cost" => $cost, "type" => $typePayByStripe]);
                } catch (\Exception $e) {
                    echo $e->getMessage();

                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return array($isSucc, $charge);
    }


    function payStripe()
    {
        //有数据返回
        $token = $_POST['stripeToken'];
        $isSucc = false;
        $msg = lang("支付失败");
        if ($token) {
            list($isSucc, $charge1) = $this->realStripe($token);
            if ($isSucc) {
                $this->headData();
                return view("/index/paysucc");
            }
        }
        return view("/index/paysucc",["msg"=>$msg]);

    }
}



