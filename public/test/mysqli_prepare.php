<?php


$dbConf=  array(
    'host'=>'az1-ls7.a2hosting.com',
    'user'=>'chinese6_zjm',
    'password'=>'jzm2019ok',
    'dbName'=>'chinese6_companion',
    'charSet'=>'utf8',
    'port'=>'3306'
);


//打开
$conn=new mysqli($dbConf['host'],$dbConf['user'],$dbConf['password'],$dbConf['dbName'],$dbConf['port']);
if(!$conn){
    die('数据库打开失败');
}
//执行增删改查
/*************数据查询***************************/
$sql='SELECT * from `t1` as t WHERE id2>?';
$stmt=$conn->prepare($sql);
if(!$stmt){
    die('sql语句有问题');
}
//绑定参数
$id2=2;
$stmt->bind_param('i',$id2);//不能写成bind_param('i',2)
//执行
$stmt->execute();
//将结果绑定发到指定的参数上
$stmt->bind_result($id1, $id2);
//获取结果
while ($tmp=$stmt->fetch()) {
    print_r('id1='.$id1.',id2='.$id2);
    echo '</br>';
}
//关闭
$stmt->free_result();//释放结果
$stmt->close();//关闭预编译的指令.
$conn->close();//关闭连接


