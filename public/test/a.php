<?php


/**
 * 数据库pdo连接
 */
class myPDO{
    private static $pdo;

    private function __construct(){
        //code
    }
    private function __clone(){
        //code
    }
    /**
     * 获取实例化的PDO，单例模式
     * @return PDO
     */
    public static function getInstance($dbConf){
        if(!(self::$pdo instanceof PDO)){
            $dsn ="mysql:host=".$dbConf['host'].";port=".$dbConf['port'].";dbname=".$dbConf['dbName'].";charset=".$dbConf['charSet'];
            try {
                self::$pdo = new PDO($dsn,$dbConf['user'], $dbConf['password'], array(PDO::ATTR_PERSISTENT => true,PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //保持长连接
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            } catch (PDOException $e) {
                print "Error:".$e->getMessage()."<br/>";
                die();
            }
        }
        return self::$pdo;
    }
}



$dbConf=  array(
    'host'=>'az1-ls7.a2hosting.com',
    'user'=>'chinese6_zjm',
    'password'=>'jzm2019ok',
    'dbName'=>'chinese6_companion',
    'charSet'=>'utf8',
    'port'=>'3306'
);

$msg = "ddd";
try {//打开
    $pdo = myPDO::getInstance($dbConf);
    /*************数据查询***************************/
    $sql = 'SELECT * from `message` ';
    $rs = $pdo->query($sql);
    $data = $rs->fetchAll();//取出所有结果
    $msg=$msg. json_data($data);
} catch (Exception $e) {
    $msg=$msg.$e->getMessage();
}


echo $msg."eeee";


