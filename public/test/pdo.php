<?php
$dsn='mysql:host=localhost;dbname=mssc';
$user='root';
$password='';
$status=1;
try {
    $sql='select * from onethink_order where status=:status';
    $dbh=new PDO($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $stmt=$dbh->prepare($sql);
    $stmt->bindParam(':status',$status);
    $stmt->execute();
    //返回插入、更新、删除的受影响行数
    // echo $stmt->rowCount();
    //返回最后插入的id
    // echo 'ID of last insert:'.$dbh->lastInsertId();
    while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['id']."\t".$row['status'].'</br>';
    }
} catch (PDOException $e) {
    echo 'SQL Query:'.$sql.'</br>';
    echo 'Connection failed:'.$e->getMessage();
}
?>