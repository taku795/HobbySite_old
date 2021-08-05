<?php
try{
	$pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
  'b0e1b2175788a4','46b12765');
  }catch(PDOException $e){
	print('DB接続エラー:'.$e->getMessage());
  }
session_start();
$Content_ID=$_POST["Content_ID"];

// nullの値が入っているところを消去
$pdo->query('DELETE FROM good WHERE Login_ID IS null OR Content_ID IS null');

// ログインIDとContentIDで表を検索し、結果を変数に代入
$sql=$pdo->prepare("SELECT * FROM good WHERE Login_ID=? AND Content_ID=?");
$sql->execute([$_SESSION['login_id'],$Content_ID]);
$result = $sql->fetchAll(PDO::FETCH_BOTH);

//クリックされた時の処理
if ($_REQUEST['click']==1) {
	// もし登録してあったら登録を消去
	// 登録していなかったら登録
	if ($result[0]['Login_ID']==$_SESSION['login_id']) {
		$sql=$pdo->prepare("DELETE FROM good WHERE Login_ID=? AND Content_ID=?");
		$sql->execute([$_SESSION['login_id'],$Content_ID]);
		$good=1;
	} else {
		$sql=$pdo->prepare("INSERT INTO good VALUES(?,?)");
		$sql->execute([$_SESSION['login_id'],$Content_ID]);
		$good=2;
	}
//クリックされていない＝ページ読み込みの時
} else {
	if ($result[0]['Login_ID']==$_SESSION['login_id']) {
		$good=2;
	} else {
		$good=1;
	}
}

// Content＿IDをカウントして総数を表示
$sql=$pdo->prepare("SELECT COUNT(Content_ID) FROM good WHERE Content_ID=?");
$sql->execute([$Content_ID]);
$good_count = $sql->fetchAll(PDO::FETCH_BOTH);

$result=array(
	'good' => $good,
	'good_count' => $good_count[0]['COUNT(Content_ID)']
);
echo json_encode( $result );


?>