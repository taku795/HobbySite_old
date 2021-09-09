<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <section class="articles">
      <h2>記事一覧</h2>
      <?php
      try{
        $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
        'b0e1b2175788a4','46b12765');
      }catch(PDOException $e){
        print('DB接続エラー:'.$e->getMessage());
      }
      session_start();
      //記事内容を新しい順に取得して表示
      foreach($sql=$pdo->query('select * from content') as $row ) {
        echo 
        "
        <article>
        <form name='form$row[id]' target='_brank' action='content/content_page.php?content_id=$row[id]' method='post'>
        <a href='javascript:form$row[id].submit()'>
        <div class='content'>
        <p>タイトル：$row[Title]</p>
        <div class='content-body'>
        <p>$row[Content]</p>
        </div>
        </div>
        </a>
        </form>
        </article>
        ";
      }
      ?>
    </section>
</body>
</html>
