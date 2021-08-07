<!DOCTYPE html>
<html lang="jp">
<head>
  <meta charset="UTF-8">
  <title>アカウントページ</title>
</head>
<body>
  <?php
    try{
      $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
      'b0e1b2175788a4','46b12765');
    }catch(PDOException $e){
      print('DB接続エラー:'.$e->getMessage());
    }
    session_start();
    //コンテンツidからlogin_idを抽出
    //そのユーザーの記事を獲得
  ?>
  <section class="account_page">
    <h3>アカウントページ</h3>
    <p><?php  ?>　さんの記事一覧</p>
    <article class="articles">
      <?php
      //セッションのログインidじゃなくて前で抽出したログインidを使う
        foreach ($sql=$pdo->query('select * from content') as $row) {
          if ($row['Login_ID']==$_SESSION['login_id']) {
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
        }
      ?>
    </article>
  </section>
</body>
</html>