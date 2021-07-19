<?php 
try{
    $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
  'b0e1b2175788a4','46b12765');
  }catch(PDOException $e){
    print('DB接続エラー:'.$e->getMessage());
  }
session_start();

foreach($sql=$pdo->query('select * from users') as $row ) {
    if ($row['User_Mail']==$_GET['mail']) {
        $_SESSION['user_mail']=$_GET['mail'];
        echo 1;
        return;
    }
}
echo 2;



?>