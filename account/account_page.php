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
    //記事画面からアクセスした場合
    if ($_REQUEST['content_id']!=null) {
      $sql = $pdo->prepare("SELECT * FROM content WHERE id=?");
      $sql->execute([$_REQUEST['content_id']]);
      $result = $sql->fetchAll(PDO::FETCH_BOTH);
      $Login_ID=$result[0]['Login_ID'];

      $sql = $pdo->prepare("SELECT * FROM users WHERE Login_ID=?");
      $sql->execute([$result[0]['Login_ID']]);
      $result = $sql->fetchAll(PDO::FETCH_BOTH);
      $name=$result[0]['User_Name'];
    } else {
      //フォロー一覧からアクセスした場合
      $Login_ID = $_POST['Login_ID'];
      $name = $_POST['name'];
    }
  ?>
  <section class="account_page">
    <h3>アカウントページ</h3>
    <p><?php echo $name; ?>　さんの記事一覧</p>
    <article class="articles">
      <?php
      //セッションのログインidじゃなくて前で抽出したログインidを使う
        foreach ($sql=$pdo->query('select * from content') as $row) {
          if ($row['Login_ID']==$Login_ID) {
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