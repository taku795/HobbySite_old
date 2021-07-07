<?php require '../header.php' ?>

<?php
echo 
"作者：$_GET[content_name]<br>
投稿日時：$_GET[day]<br>
タイトル：$_GET[title]<br>
記事：$_POST[content]";
?>

<?php require '../footer.php' ?>