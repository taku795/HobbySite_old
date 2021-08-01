<?php
session_start();

if (empty($_REQUEST['name'])) {
  $_REQUEST['name']=$_SESSION['user_name'];
}

//投稿内容があった時データベースに登録
if (!empty($_REQUEST['message'])) {
    try{
        $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
      'b0e1b2175788a4','46b12765');
      }catch(PDOException $e){
        print('DB接続エラー:'.$e->getMessage());
      }

    //送られてきたメッセージを登録
    $sql = $pdo->prepare("insert into message values(null,?,?,?,?)");
    //id,thread_id,name,message,time

    date_default_timezone_set('Asia/Tokyo');
    //タイムゾーンリセット
    $time=date("Y,m,d,H,i,s");
    $sql->execute([$_REQUEST['thread_id'],$_REQUEST['name'],$_REQUEST['message'],$time]);
}
?>
