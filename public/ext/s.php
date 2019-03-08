<?php

include_once("../application/index/controller/ext_util/fileUtil.php");

$urls = [
    "https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css",
    "https://cdn.bootcss.com/jquery/3.2.1/jquery.slim.min.js",
    "https://cdn.bootcss.com/popper.js/1.12.9/umd/popper.min.js",
    "https://cdn.bootcss.com/bootstrap/4.0.0/js/bootstrap.min.js"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $urls[0]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$html = curl_exec($ch);
curl_close($ch);

echo "<textarea style='width:800px;height:600px;'>".$html."</textarea>";




