<?php
$dbConf=include 'conf.php';
function openDb($dbConf){
    $conn=mysqli_connect($dbConf['host'],$dbConf['user'],$dbConf['password'],$dbConf['dbName'],$dbConf['port']) or die('打开失败');
    //当然如上面不填写数据库也可通过mysqli_select($conn,$dbConf['dbName'])来选择数据库
    mysqli_set_charset($conn,$dbConf['charSet']);//设置编码
    return $conn;
}
function closeDb($conn){
    mysqli_close($conn);
}

//1.打开连接
$conn=openDb($dbConf);
//2query方法执行增、查、删、改
$sql='SELECT *  from message';
/*************数据查询***************************/
$rs=$conn->query($sql);
//从结果集中读取数据
//fetch_assoc:返回键值对形式，键位字段名、fetch_row：返回键值对形式，键值为数值、fetch_array：返回1和2两种形式的组合
$data=array();//保存数据
while($tmp=mysqli_fetch_assoc($rs)){//每次从结果集中取出一行数据
    $data[]=$tmp;
}
//对数据进行相应的操作
print_r($data);//输出数据

//3.关闭连接
closeDb($conn);