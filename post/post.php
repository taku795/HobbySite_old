<?php
try{
    $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
  'b0e1b2175788a4','46b12765');
  }catch(PDOException $e){
    print('DB接続エラー:'.$e->getMessage());
  }
session_start();

//投稿内容を確認、空白でないか
if (empty($_REQUEST['title'])) {
    $no_title=1;
}
if (empty($_REQUEST['content'])) {
    $no_content=1;
}
if ($no_content || $no_title) {
    header("Location:../home.php?no_title=$no_title&no_content=$no_content");
    return;
}

if (isset($_REQUEST['edit_content_id'])) {
    //UPDATE content SET Content="テストです" WHERE id=8;
    $sql=$pdo->prepare("UPDATE content SET Content=?,Title=? WHERE id=?");
    $sql->execute([$_REQUEST['content'],$_REQUEST['title'],$_REQUEST['edit_content_id']]);
    if ($_REQUEST['tag']!=NULL) {
        $sql=$pdo->query("SELECT * FROM tag_to_content WHERE Content_ID=$_REQUEST[edit_content_id]");
        $result = $sql->fetchAll(PDO::FETCH_BOTH);
        if (isset($result[0]['id'])) {
            $sql=$pdo->prepare("UPDATE tag_to_content SET Tag_ID=? WHERE Content_ID=?");
            $sql->execute([$_REQUEST['tag'],$_REQUEST['edit_content_id']]);
        } else {
            $pdo->query("insert into tag_to_content values(NULL,$_REQUEST[tag],$_REQUEST[edit_content_id])");
        }
    } else {
        $pdo->query("DELETE FROM tag_to_content WHERE Content_ID=$_REQUEST[edit_content_id]");
    }
    header('Location:post_end_page.php');
    return;
}

//タイムゾーンリセット
date_default_timezone_set('Asia/Tokyo');
$time=date("Y,m,d");

//データベースに登録
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