<!DOCTYPE html>
<html lang="jp">
<head>
  <meta charset="UTF-8">
</head>
<body>
  <h2>検索結果</h2>
  <article class='articles'>
    <?php
      try{
        $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
        'b0e1b2175788a4','46b12765');
      }catch(PDOException $e){
        print('DB接続エラー:'.$e->getMessage());
      }
      session_start();

      //記事をキーワードで検索
      if ($_REQUEST['key_word']!=NULL && $_REQUEST['tag']==NULL) {
        $sql=$pdo->prepare("select * from content where Title or content like ?");
        $sql->execute(["%$_REQUEST[key_word]%"]);
        require("content_display.php");
      } else if ($_REQUEST['key_word']==NULL && $_REQUEST['tag']!=NULL) {
        $sql_buf=$pdo->prepare("select * from tag_to_content where Tag_ID=?");
        $sql_buf->execute([$_REQUEST['tag']]);
        
        foreach($sql_buf->fetchAll() as $row) {
          $sql=$pdo->prepare("select * from content where id=?");
          $sql->execute([$row['Content_ID']]);
          require("content_display.php");
        }
      } else if ($_REQUEST['key_word']!=NULL && $_REQUEST['tag']!=NULL) {
        $sql=$pdo->prepare("select * from content where Title or content like ?");
        $sql->execute(["%$_REQUEST[key_word]%"]);
        require("content_display.php");

        $sql_buf=$pdo->prepare("select * from tag_to_content where Tag_ID=?");
        $sql_buf->execute([$_REQUEST['tag']]);

        foreach($sql_buf->fetchAll() as $row) {
          $sql=$pdo->prepare("select * from content where id=?");
          $sql->execute([$row['Content_ID']]);
          require("content_display.php");
        }
      }
    ?>
  </article>
</body>
</html>