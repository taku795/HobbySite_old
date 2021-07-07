<?php
//投稿内容を確認、空白でないか
if (empty($_REQUEST['title'])) {
    $no_title=1;
}
if (empty($_REQUEST['content'])) {
    $no_content=1;
}
if ($no_content || $no_title) {
    header("Location:post_page.php?no_title=$no_title&no_content=$no_content");
    return;
}

//データベースに登録
$pdo=new PDO('mysql:host=localhost;dbname=board;charset=utf8',
'staff','password');
session_start();

date_default_timezone_set('Asia/Tokyo');
    //タイムゾーンリセット
$time=date("Y,m,d,H,i,s");
$sql=$pdo->prepare('insert into content values(null,?,?,?,?)');
if ($sql->execute([$_SESSION['login_id'],$_REQUEST['title'],$_REQUEST['content'],$time])) {
    header('Location:post_end_page.php');
}


?>