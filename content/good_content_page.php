<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <title>いいねした記事</title>
</head>
<body>
<?php
      session_start();
      try{
        $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
      'b0e1b2175788a4','46b12765');
      }catch(PDOException $e){
        print('DB接続エラー:'.$e->getMessage());
      }

      // お気に入り登録している記事のidを検索
      $sql=$pdo->prepare("SELECT * FROM good WHERE Login_ID=?");
      $sql->execute([$_SESSION['login_id']]);
      $result = $sql->fetchAll(PDO::FETCH_BOTH);

      // 検索結果から記事の情報を獲得し表示
      for ($number=0;$result[$number]['Content_ID']!=NULL;$number++) {
        $sql=$pdo->prepare('select * from content where id=?');
        $sql->execute([$result[$number]['Content_ID']]);
        $content = $sql->fetchAll(PDO::FETCH_BOTH);
        $id=$content[0][id];
        $title=$content[0][Title];
        $buf=$content[0][Content];
        echo 
          "
          <article>
          <form name='form_good_$id' target='_brank' action='content_page.php?content_id=$id' method='post'>
          <a href='javascript:form_good_$id.submit()'>
          <p>記事タイトル：$title</p>
          <p>$buf</p>
          </a>
          </form>
          </article>
          ";
      }
      
      ?>

</body>
</html>
