<?php require "../header.php"; ?>

<?php
$pdo=new PDO('mysql:host=localhost;dbname=board;charset=utf8',
'staff','password');

//key_wordで検索をかける、それを返す、検索結果を別ページで表示する
//SELECT * FROM content where Title or Content like '%ス%'
//SQLはこんな感じ

?>

<?php require "../footer.php"; ?>