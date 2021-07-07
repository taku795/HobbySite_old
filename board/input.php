<?php
//投稿内容があった時データベースに登録
if (!empty($_REQUEST['message'])) {
    $pdo=new PDO('mysql:host=localhost;dbname=board;charset=utf8',
    'staff','password');

    //送られてきたメッセージを登録
    $sql = $pdo->prepare("insert into message values(null,?,?,?,?)");
    //id,thread_id,name,message,time

    date_default_timezone_set('Asia/Tokyo');
    //タイムゾーンリセット
    $time=date("Y,m,d,H,i,s");
    $sql->execute([$_REQUEST['thread_id'],$_REQUEST['name'],$_REQUEST['message'],$time]);
}
?>
