<?php

namespace app\index\controller;


use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use think\Db;


class Companion extends Base
{
    public function initPayPal()
    {

        try {
            require "../extend/PayPal-PHP-SDK/autoload.php"; //载入sdk的自动加载文件
//            $clientId   = "Ad78wVm4vY24Mimz6Bw1leIIVhMeEK6ujhWDGSBWLAkUZ6LAsJGNVxm3m5TEhTohScS8LPGGcGfQda9Y";
//            $clientSecret= 'EPSIwrLoICniOXIQpZh450rUgdxOvcZdp0fWJnuK_F1hLTm3n4cuoH7UToB80Jwm2QhF2jSpUsUEXsTa';

//            //r
//            $clientId = "AZUj0NJWfvYjpL0WhBETlUK9N2UgSsKuZRAm_yU32AeTWfa9MJN-1ZApbe5bABzTIq7jSZHgXAoQFQDG";
//            $clientSecret = 'EFvYculgDdQylsstVJQLryDwcqMgngl9MgiP6VuTcBdMHDc63H4qXbOmg43p6m-wN2S1gySO-10p-Wow';


            $clientId = "AVh0IsTx_d7J-tWDtv0yap9sEyEsoVJytKV2VRCcHxFKYYqZYkDEP9VBbiB-JriCKIURwzRt4-NIZtU6";
            $clientSecret = 'EPBbFWIhT7SL7WzVcpJ3jhvDlspnjVEu2b6lgmGv4xapay0xet-9gzKt7z21lBAZ3bFpXkwbyEXgnqVk';


            $apiContext = new ApiContext(
                new OAuthTokenCredential(
                    $clientId,
                    $clientSecret
                )
            );
            $apiContext->setConfig(
                array(
                    'mode' => 'live',
                    'log.LogEnabled' => true,
                    'log.FileName' => '../PayPal.log',
                    'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
                    'cache.enabled' => true,
                )
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $apiContext;
    }

    function aa()
    {
        $baseUrl = 'http://' . $_SERVER["HTTP_HOST"];
        return $baseUrl;
    }


    function pay()
    {
        $type = request()->param("product");
        $price = 0;
        if ($type == "life") {
            $price = 0.8;
        } else if ($type == "year") {
            $price = 0.1;
        }
        if ($price > 0) {
            try {
                $approvalUrl = $this->payItem($price, $type);

            } catch (\Exception $e) {
                return $e->getMessage();
            }
            return $this->redirect($approvalUrl);

        } else {
            return json_encode(request()->param()) . $price;
        }
    }

    public function test()
    {
        $isSucc = request()->param("isSucc");
        if ($isSucc == 1) {
            $data = ["paymentId" => request()->param("paymentId"),
                "token" => request()->param("token"),
                "PayerID" => request()->param("PayerID")
            ];
            try {
                Db::table("palpay_callback")->insert($data);
            } catch (\Exception $e) {
            }
            return json_encode(request()->param());
        } else {
            return "cancle ";
        }
    }

    /**
     * @param $price
     * @param $type
     * @return null|string
     */
    public function payItem($price, $type)
    {
        $apiContext = $this->initPayPal();  // 获取配置好的ApiContext
        $itemList = new ItemList();
        $items = array();
        $total = 0;
        $product = lang("VIP会员费");
        $total = $total + $price;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($product)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($price);
        $items[] = $item;
        $itemList->setItems($items);


        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($total);

        $transaction = new Transaction();
        $uniqid = uniqid();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($product)
            ->setInvoiceNumber($uniqid);

        $baseUrl = "http://travelling.chinesecompanion.com/index.php/index/companion/test";
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($baseUrl . '?isSucc=1')
            ->setCancelUrl($baseUrl . '?isSucc=2');


        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        $payment->create($apiContext);
        $approvalUrl = $payment->getApprovalLink();
        $pid = $payment->getId();
        Db::table("pay_details")->insert([
            "pid" => $pid,
            "mid" => $this->loginUser()->id,
            "type" => $type,
            "price" => $price,
            "uniqid" => $uniqid
        ]);
        return $approvalUrl;
    }
}



