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

function payurl($price, $description = "", $type = "EUR")
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
        "description" => $description,
    ]);
    return $purchaseUrl;
}
