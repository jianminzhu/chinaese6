<?php
require_once 'rb.php';


R::setup("mysql:host=localhost;dbname=test","root","root");
//创建一个表（也可以指为实例化一个表）
$tableName ="member";

$result = R::getAll("select * from member");


echo json_encode($result);
echo "ddd";


