
<?php require "header.php"; ?>

<h1>趣味旅行</h1>
<a href="menyu/menyu_page.php"><h2>メニュー</h2></a>
<a href="post/post_page.php"><h2>記事投稿</h2></a>
<form action="search/search.php">
<p>キーワード検索：<input type="text" name='key_word'>
<input type="submit" value='検索'></p>
</form>
<h2>掲示板</h2>
<?php 
try{
    $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
  'b0e1b2175788a4','46b12765');
  }catch(PDOException $e){
    print('DB接続エラー:'.$e->getMessage());
  }
foreach($sql=$pdo->query('select * from thread') as $row ) {
    echo "<a href='board/board.php?thread_id=$row[id]'>$row[title]</a>";
    echo '<br>';
}
?>
<h2>記事</h2>
<?php
//データベースに接続、記事内容を新しい順に取得して表示
try{
    $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
  'b0e1b2175788a4','46b12765');
  }catch(PDOException $e){
    print('DB接続エラー:'.$e->getMessage());
  }
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