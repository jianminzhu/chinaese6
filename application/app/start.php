<?php
require "PayPal-PHP-SDK/autoload.php";
define('SITE_URL', 'http://travelling.chinesecompanion.com');
//创建支付对象实例
$tocke = "An5ns1Kso7MWUdW4ErQKJJJ4qi4-AkCYw9L-9wCJGRakRli0E8DrQpWb";

$paypal = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'realmarketplace-facilitator@yahoo.com',
        $tocke
    )
);