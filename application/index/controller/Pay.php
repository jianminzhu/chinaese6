<?php

namespace app\index\controller;


use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use think\Controller;


class Pay extends Controller
{
    public function initPayPal()
    {

        try {
            require "../extend/PayPal-PHP-SDK/autoload.php"; //载入sdk的自动加载文件
            $clientId   = "Ad78wVm4vY24Mimz6Bw1leIIVhMeEK6ujhWDGSBWLAkUZ6LAsJGNVxm3m5TEhTohScS8LPGGcGfQda9Y";
            $clientSecret= 'EPSIwrLoICniOXIQpZh450rUgdxOvcZdp0fWJnuK_F1hLTm3n4cuoH7UToB80Jwm2QhF2jSpUsUEXsTa';

            $apiContext = new ApiContext(
                new OAuthTokenCredential(
                    $clientId,
                    $clientSecret
                )
            );
            $apiContext->setConfig(
                array(
                    'mode' => 'sandbox',
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



    function pay()
    {
        $type = request("product");
        $price = 0;
        if ($type == "life") {
            $price = 699;
        }else if ($type == "year") {
            $price = 199;
        }
        if ($price > 0) {

        }
        $apiContext = $this->initPayPal();  // 获取配置好的ApiContext
        $itemList = new ItemList();
        $items = array();
        $total = 0;
        $product = lang("会员");
        $price = 0.01;  // 金额

        $total = $total + $price;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($product)
            ->setCurrency('HKD')
            ->setQuantity(1)
            ->setPrice($price);
        $items[] = $item;

        $itemList->setItems($items);


        $shipping = 0.00; //运费
        $total = $total + $shipping;


        $details = new Details();
        $details->setShipping($shipping)
            ->setSubtotal($total);

        $amount = new Amount();
        $amount->setCurrency('HKD')
            ->setTotal($total);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($product)
            ->setInvoiceNumber(uniqid());


        $baseUrl = "http://travelling.chinesecompanion.com/index.php/index/pay/test";
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($baseUrl . '?lz_type=1')
            ->setCancelUrl($baseUrl . '?lz_type=2');


        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($apiContext);


        } catch (PayPalConnectionException $e) {
            echo $e->getMessage();
            echo $e->getData();
            die();
        }
        $approvalUrl = $payment->getApprovalLink();
        $id = $payment->getId();
        $this->redirect($approvalUrl);

    }


}



