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
try{
    $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
  'b0e1b2175788a4','46b12765');
  }catch(PDOException $e){
    print('DB接続エラー:'.$e->getMessage());
  }
session_start();

date_default_timezone_set('Asia/Tokyo');
    //タイムゾーンリセット
$time=date("Y,m,d");
$sql=$pdo->prepare('insert into content values(null,?,?,?,?)');
if($sql->execute([$_SESSION['login_id'],$_REQUEST['title'],$_REQUEST['content'],$time])) {
    header('Location:post_end_page.php');
}

//タグを登録
if ($_REQUEST['tag']!=NULL) {
    $sql=$pdo->prepare('SELECT MAX(id) FROM content WHERE Login_ID=?');
    $sql->execute([$_SESSION['login_id']]);
    $result = $sql->fetchAll(PDO::FETCH_BOTH);
    $content_id = $result[0]['MAX(id)'];
    $pdo->query("insert into tag_to_content values(NULL,$_REQUEST[tag],$content_id)");
}

?>