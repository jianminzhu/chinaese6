<?php
header("Content-Type: text/html;charset=utf-8");
include  'BytripUtil.php' ;

$url = "http://www.bytrip.com/Index/Fere/index/status/2/area/310100/low_age/18/high_age/60";
echo $url;
$members = BytripSearchMembers($url);
echo json_encode($members);





