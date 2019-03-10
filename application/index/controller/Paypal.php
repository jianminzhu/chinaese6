<?php
/**
 * Created by PhpStorm.
 * User: faker1
 * Date: 2018/5/19
 * Time: 下午4:34
 */
namespace app\index\controller;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

//use PayPal\Api\Amount;
//use PayPal\Api\Details;
//use PayPal\Api\Item;
//use PayPal\Api\ItemList;
//use PayPal\Api\Payer;
//use PayPal\Api\Payment;
//use PayPal\Api\RedirectUrls;
//use PayPal\Api\Transaction;
//use Psr\Log\AbstractLogger;


class Paypal
{

    public function pay_goods()
    {
        require "../extend/PayPal-PHP-SDK/autoload.php"; //载入sdk的自动加载文件
        $clientId = 'access_token$sandbox$24g4v2x94xmhrsxp$9806ec852f76879a05c2eee16933719a';
        $clientSecret = "Av-jutUGYHCmia3yMoGy9.bMdWOyAOJjofOGRnahKe.9GmhMaYNJ5oIB";

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

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $item1 = new Item();
        $item1->setName('test pro 1')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setSku("testpro1_01")// Similar to `item_number` in Classic API
            ->setPrice(20);
        $item2 = new Item();
        $item2->setName('test pro 2')
            ->setCurrency('USD')
            ->setQuantity(5)
            ->setSku("testpro2_01")// Similar to `item_number` in Classic API
            ->setPrice(10);

        $itemList = new ItemList();
        $itemList->setItems(array($item1, $item2));

        $address = new ShippingAddress();
        $address->setRecipientName('什么名字')
            ->setLine1('什么街什么路什么小区')
            ->setLine2('什么单元什么号')
            ->setCity('城市名')
            ->setState('浙江省')
            ->setPhone('12345678911')
            ->setPostalCode('12345')
            ->setCountryCode('CN');

        $itemList->setShippingAddress($address);


        $details = new Details();
        $details->setShipping(5)
            ->setTax(10)
            ->setSubtotal(70);

        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(85)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());

        $baseUrl = 'http://'.$_SERVER["HTTP_HOST"];
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("$baseUrl/api/Paypal/success")
            ->setCancelUrl("$baseUrl/api/Paypal/cancel");
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        $payment->create($apiContext);

        $approvalUrl = $payment->getApprovalLink();

        dump($approvalUrl);
    }

    public function success()
    {
        echo 'success';
    }

    public function cancel()
    {
        echo 'cancel';
    }
}