<?php
require "PayPal-PHP-SDK/autoload.php";
define('SITE_URL', 'http://travelling.chinesecompanion.com');
//创建支付对象实例
$tocke = "Av-jutUGYHCmia3yMoGy9.bMdWOyAOJjofOGRnahKe.9GmhMaYNJ5oIB";

$paypal = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
            'access_token$sandbox$24g4v2x94xmhrsxp$9806ec852f76879a05c2eee16933719a',
            $tocke
    )
);