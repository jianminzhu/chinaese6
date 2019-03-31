<?php

require_once 'rb.php';

R::setup("mysql:host=localhost;dbname=test", "root", "root");

$p = R::dispense('pay');

$p->paymentMethod = "CC";
$p->priceAmount = "2.64";
$p->priceCurrency = "EUR";
$p->saleID = "18426319";
$p->shopID = "115404";
$p->type = "purchase";
$p->signature = "ec0e2601184c1ca55376bcd95bcb0135a81c2c25";

R::store($p);



echo " finished11111111111111111111";


