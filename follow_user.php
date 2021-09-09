<!DOCTYPE html>
<html lang="jp">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/follow_user.css">
</head>
<body>
<section class="follow-user">
  <h2>フォローユーザー</h2>
  <div class="user">
    <?php
    try{
      $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
      'b0e1b2175788a4','46b12765');
    }catch(PDOException $e){
      print('DB接続エラー:'.$e->getMessage());
    }
    session_start();
    
    //followテーブルからログインしているユーザーが誰をフォローしているか検索
    $sql=$pdo->prepare("SELECT * FROM follow WHERE Follow_ID=?");
    $sql->execute([$_SESSION['login_id']]);
    $result = $sql->fetchAll(PDO::FETCH_BOTH);
    
    
    //その結果をアカウントページのaタグで表示
    for ($number=0;$result[$number]['Follower_ID']!=NULL;$number++) {
      $sql=$pdo->prepare("SELECT * FROM users WHERE Login_ID=?");
      $sql->execute([$result[$number]['Follower_ID']]);
      $buf = $sql->fetchAll(PDO::FETCH_BOTH);
      $name = $buf[0]['User_Name'];
      $Login_ID=$buf[0]['Login_ID'];
      echo 
      " 
      <form name='form$number' target='_brank' action='account/account_page.php' method='post'>
      <input type='hidden' name='Login_ID' value=$Login_ID>
      <input type='hidden' name='name' value=$name>
      <a href='javascript:form$number.submit()'>
      <p>$name</p>
      </a>
      </form>
      ";
    }
    ?>
    </div>
  </section>
  
  

</body>
</html>