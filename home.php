<?php require "header.php"; ?>

<h1>趣味旅行</h1>
<a href="menyu/menyu_page.php"><h2>メニュー</h2></a>
<a href="post/post_page.php"><h2>記事投稿</h2></a>
<h2>掲示板</h2>
<?php $pdo=new PDO('mysql:host=localhost;dbname=board;charset=utf8',
'staff','password');
foreach($sql=$pdo->query('select * from thread') as $row ) {
    echo "<a href='board/board.php?thread_id=$row[id]'>$row[title]</a>";
    echo '<br>';
}
?>
<h2>記事</h2>
<?php
//データベースに接続、記事内容を新しい順に取得して表示
$pdo=new PDO('mysql:host=localhost;dbname=board;charset=utf8',
'staff','password');
session_start();

foreach($sql=$pdo->query('select * from content') as $row ) {
    foreach($sql=$pdo->query('select * from users') as $row2 ) {
        if ($row['Login_ID']==$row2['Login_ID']) {
            $content_name=$row2['User_Name'];
        }
    }
    echo 
    "<form name='form$row[id]' action='content/content_page.php?content_name=$content_name&title=$row[Title]&day=$row[Day]' method='post'>
    <input type='hidden' name='content' value='$row[Content]'>
    <a href='javascript:form$row[id].submit()'>$content_name-$row[Title]-$row[Content]-$row[Day]</a>
    </form>";
    echo '<br>';
}


?>

<?php require "footer.php"; ?>