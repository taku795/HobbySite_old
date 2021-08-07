<?php
try{
	$pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
	'b0e1b2175788a4','46b12765');
}catch(PDOException $e){
	print('DB接続エラー:'.$e->getMessage());
}
session_start();
$Follow_ID=$_POST["Follow_ID"];
$Follower_ID=$_POST["Follower_ID"];

// nullの値が入っているところを消去
$pdo->query('DELETE FROM follow WHERE Follow_ID IS null OR Follower_ID IS null');

$sql=$pdo->prepare("SELECT * FROM follow WHERE Follow_ID=? AND Follower_ID=?");
$sql->execute([$Follow_ID,$Follower_ID]);
$result = $sql->fetchAll(PDO::FETCH_BOTH);

if ($_REQUEST['click']==1) {
    if ($result[0]['Follow_ID']==$Follow_ID) {
		$sql=$pdo->prepare("DELETE FROM follow WHERE Follow_ID=? AND Follower_ID=?");
		$sql->execute([$Follow_ID,$Follower_ID]);
	} else {
		$sql=$pdo->prepare("INSERT INTO follow VALUES(?,?)");
		$sql->execute([$Follow_ID,$Follower_ID]);
	}
}

$sql=$pdo->prepare("SELECT * FROM follow WHERE Follow_ID=? AND Follower_ID=?");
$sql->execute([$Follow_ID,$Follower_ID]);
$result = $sql->fetchAll(PDO::FETCH_BOTH);
if ($result[0]['Follow_ID']==NULL) {
    echo 1;
} else {
    echo 2;
}

?>