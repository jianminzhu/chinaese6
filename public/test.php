<?php



'hostname'        =>,
//

    'database'        => ,
    'username'        => 'chinese6_zjm',
    'password'        => 'jzm2019ok',

 $dbhostip= 'az1-ls7.a2hosting.com';
$username ='chinese6_companion';

$userpassword =;



$con=mysql_connect($dbhostip,$username,$userpassword) or die("Unable to connect to the MySQL!");
$db = mysql_select_db($dbdatabasename,$con);
//执行语句
$qres=mysql_query("SELECT id,GoodsName FROM user");
//提取一条数据
11 $row=mysql_fetch_row($result);//mysql_fetch_row只能提取出查询结果的第一条记录
//提取多条记录
$reslist = array();
$i=0;
while($row = mysql_fetch_row($res)){
    $reslist[$i] = $row;
    $i++;
}
mysql_close($con);