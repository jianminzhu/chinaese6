<?php

require_once  "FlexPay.php";
function validate_signature($realParams)
{
    $isSucc = false;
    try {
        $FLEXPAYCONFIG = array(
            'merchantId' => '9804000000040884',
            'shopId' => '115404',
            'signatureKey' => '6gZu2e5KhpPrTuqUAuJ2QYD67SgwKA'
        );
        $brand = Verotel\FlexPay\Brand::create_from_merchant_id($FLEXPAYCONFIG['merchantId']);
        $flexpayClient = new Verotel\FlexPay\Client(
            $FLEXPAYCONFIG['shopId'],
            $FLEXPAYCONFIG['signatureKey'],
            $brand
        );
        if ($flexpayClient->validate_signature($realParams)) {
            $isSucc = true;
        }
    } catch (\Verotel\FlexPay\Exception $e) {

    }
    return $isSucc;
}

/**
 * @param $jsonHtml
 * @return array
 */
function parseJsonParas($jsonHtml)
{
    $params = json_decode($jsonHtml);
    $realParams = [
        "paymentMethod" => $params->paymentMethod,
        "priceAmount" => $params->priceAmount,
        "priceCurrency" => $params->priceCurrency,
        "saleID" => $params->saleID,
        "shopID" => $params->shopID,
        "type" => $params->type,
        "signature" => $params->signature
    ];
    return $realParams;
}

function payurl($price, $custom1,$description = "", $type = "EUR")
{

    $FLEXPAYCONFIG = array(
        'merchantId' => '9804000000040884',
        'shopId' => '115404',
        'signatureKey' => '6gZu2e5KhpPrTuqUAuJ2QYD67SgwKA'
    );
    $brand = Verotel\FlexPay\Brand::create_from_merchant_id($FLEXPAYCONFIG['merchantId']);
    $flexpayClient = new Verotel\FlexPay\Client(
        $FLEXPAYCONFIG['shopId'],
        $FLEXPAYCONFIG['signatureKey'],
        $brand
    );
    $purchaseUrl = $flexpayClient->get_purchase_URL([
        "priceAmount" => $price,
        "priceCurrency" => $type,
        "custom1" => $custom1,
        "description" => $description,
    ]);
    return $purchaseUrl;
}

function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}
