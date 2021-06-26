<?php require "header.php"; ?>

<h1>趣味旅行</h1>
<a href="menyu_page.php"><h2>メニュー</h2></a>
<h2>掲示板</h2>
<?php $pdo=new PDO('mysql:host=localhost;dbname=board;charset=utf8',
'staff','password');
foreach($sql=$pdo->query('select * from thread') as $row ) {
    echo "<a href='board.php?thread_id=$row[id]'>$row[title]</a>";
    echo '<br>';
}
?>
<h2>記事</h2>

<?php require "footer.php"; ?>