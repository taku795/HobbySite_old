<?php
try{
    $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
    'b0e1b2175788a4','46b12765');
}catch(PDOException $e){
    print('DB接続エラー:'.$e->getMessage());
}
session_start();

$checked=$_REQUEST['checked_content_id'];

foreach($checked as $row) {
    $sql = $pdo->prepare("DELETE FROM content WHERE id=?");
    $sql->execute([$row]);
}
echo "<p>消去しました</p>";

?>
<a href="menyu_page.php">メニュートップへ</a>