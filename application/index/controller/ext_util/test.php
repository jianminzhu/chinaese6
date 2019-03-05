<?php
include_once "ext_util/fileUtil.php";
//$url = "http://www.bytrip.com/Index/Fere/index/status/2/area/310100/low_age/18/high_age/60";
//if ($url != "" && strpos($url, "://www.bytrip.com")) {
//    echo $url;
//}
//
//
//echo "aaaaa";


$szUrl = "https://www.chinalovecupid.com/zc/widget/loadstates?countryid=5";
/**
 * @param $szUrl
 * @return mixed
 */

$data = ExtGetHtml($szUrl);
echo $data;