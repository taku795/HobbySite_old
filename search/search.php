<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/search.css?v=2">
  <title>検索結果</title>
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

      $sql=$pdo->prepare("select * from content where Title or content like ?");
      $sql->execute(["%$_REQUEST[key_word]%"]);
  
      foreach ($sql->fetchAll() as $row) {
        echo
        "
        <article>
        <form name='form$row[id]' target='_brank' action='../content/content_page.php?content_id=$row[id]' method='post'>
        <a href='javascript:form$row[id].submit()'>
        <p>記事タイトル：$row[Title]</p>
        <p>$row[Content]</p>
        </a>
        </form>
        </article>
        ";
      }
    ?>
  </article>
  <a class="home_link" href="../home.php">ホーム画面へ</a>
</body>
</html>