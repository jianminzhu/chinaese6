<?php
/**
 * @author xxxxxxxx
 * @brief 简介：
 * @date 15/9/2
 * @time 下午5:00
 */

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Exception\PayPalConnectionException;
try {


    require "../application/app/start.php";
//    if (!isset($_POST['product'], $_POST['price'])) {
//        die("lose some params");
//    }
//    $product = $_POST['product'];
//    $price = $_POST['price'];
//    $shipping = 2.00; //运费
//
//    $total = $price + $shipping;
//
//    $payer = new Payer();
//    $payer->setPaymentMethod('paypal');
//
//    $item = new Item();
//    $item->setName($product)
//        ->setCurrency('USD')
//        ->setQuantity(1)
//        ->setPrice($price);
//
//    $itemList = new ItemList();
//    $itemList->setItems([$item]);
//
//    $details = new Details();
//    $details->setShipping($shipping)
//        ->setSubtotal($price);
//
//    $amount = new Amount();
//    $amount->setCurrency('USD')
//        ->setTotal($total)
//        ->setDetails($details);
//
//    $transaction = new Transaction();
//    $transaction->setAmount($amount)
//        ->setItemList($itemList)
//        ->setDescription("支付描述内容")
//        ->setInvoiceNumber(uniqid());
//
//    $redirectUrls = new RedirectUrls();
//    $redirectUrls->setReturnUrl(SITE_URL . '/pay.php?success=true')
//        ->setCancelUrl(SITE_URL . '/pay.php?success=false');
//
//    $payment = new Payment();
//    $payment->setIntent('sale')
//        ->setPayer($payer)
//        ->setRedirectUrls($redirectUrls)
//        ->setTransactions([$transaction]);
//
//    try {
//        $payment->create($paypal);
//    } catch (PayPalConnectionException $e) {
//        echo $e->getData();
//        die();
//    }
//
//    $approvalUrl = $payment->getApprovalLink();
//    header("Location: {$approvalUrl}");
} catch (Exception $e) {
    echo $e->getMessage();
}
