<?php

namespace app\index\controller;
include_once "stripe/init.php";


class Pay extends Base
{

    function test()
    {
        $this->headData();
        return view("/index/payStripe");
    }

    function payStripe()
    {
        $Publishable = "pk_test_kjTZ7tqLZ5dYkdzDzdabZ2K500YtN3kHhk";
        $Secret = "sk_test_yD1UwUeF99VI5S5hKFHQAvGL00dWlCK5LX";
        //有数据返回
        if ($_POST['stripeToken']) {
            \Stripe\Stripe::setApiKey($Secret);
            $token = $_POST['stripeToken'];
            try {// Charge the user's card:
                $charge = \Stripe\Charge::create(array(
                    "amount" => 129,
                    "currency" => "usd",
                    "description" => lang('1年 VIP 会员'),
                    "source" => $token,
                ));
                if ($charge["status"]=="succeeded") {
                    echo json_encode($charge);
                }else{
                    echo "pay failed";
                }
            } catch (\Exception $e) {
                echo "pay failed";
            }
        }

        if ($this->isLogin()) {
            $loginUser = $this->loginUser();
            $loginUser->id;
        }

    }
}



