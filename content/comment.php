<?php
//データベースに接続
try{
	$pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
	'b0e1b2175788a4','46b12765');
}catch(PDOException $e){
	print('DB接続エラー:'.$e->getMessage());
}
session_start();

$pdo->query("DELETE FROM comment_to_content WHERE Content_ID IS null OR Login_ID IS null");

//comment_to_conntentに登録
$sql = $pdo -> prepare("INSERT INTO comment_to_content VALUES (NULL,?,?,?)");
if($sql -> execute([$_REQUEST['content_id'],$_SESSION['login_id'],$_REQUEST['comment']])) {
	header("Location: content_page.php?content_id=$_REQUEST[content_id]");
}


?>