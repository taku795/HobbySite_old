<!DOCTYPE html>
<html lang="jp">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/acount_page.css">
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
    <h2>アカウントページ</h2>
    <p class="sub"><?php echo $name; ?>　さんの記事一覧</p>

    <!-- フォロー -->
    <?php
      if ($result[0]['Login_ID']!=$_SESSION['login_id']) {
          echo "<button id=follow onclick='onClick()'></button>";
      }
    ?>

    <article class="articles">
      <?php
      //セッションのログインidじゃなくて前で抽出したログインidを使う
        foreach ($sql=$pdo->query('select * from content') as $row) {
          if ($row['Login_ID']==$Login_ID) {
            echo 
            "
            <article>
            <form name='form$row[id]' action='../content/content_page.php?content_id=$row[id]' method='post'>
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
        }
      ?>
    </article>
  </section>

  <a class="home_link" href="../home.php">ホーム画面へ</a>

  <!-- フォローボタンの処理 -->
  <?php
   if($_GET['content_id']!=null) {
     $sql=$pdo->prepare("SELECT * FROM content WHERE id=?");
     $sql -> execute([$_GET['content_id']]);
     $result = $sql->fetchAll(PDO::FETCH_BOTH);

     $follower_id = $result[0]['Login_ID'];
   } else {
    $follower_id = $_POST['Login_ID'];
   }
  ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
    var Follower_ID ="<?php echo $follower_id;?>";
    var Follow_ID ="<?php echo $_SESSION['login_id'];?>";
    //ページ読み込みの時
    if (Follower_ID!=Follow_ID) {
        $.ajax({
            type: 'post',
            url: "https://taku777.herokuapp.com/content/follow.php",
            data: {"Follow_ID": Follow_ID,"Follower_ID": Follower_ID},
            success: function(result){
                if (result==1) {
                    follow.innerHTML = 'フォロー';
                } else {
                    follow.innerHTML = 'フォロー中';
                }

                
            }
        });
    }
    

    //ボタンをクリックした時
    function onClick() {
        $.ajax({
            type: 'post',
            url: "https://taku777.herokuapp.com/content/follow.php?click=1",
            data: {"Follow_ID": Follow_ID,"Follower_ID": Follower_ID},
            success: function(result){
                if (result==1) {
                    follow.innerHTML = 'フォロー';
                } else {
                    follow.innerHTML = 'フォロー中';
                }
            }
        });
    }
</script>
</body>
</html>