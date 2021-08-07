<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/content_page.css">
    <title>記事ページ</title>
</head>
<body>
    <h2>記事</h2>
    <?php
    try{
        $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
      'b0e1b2175788a4','46b12765');
      }catch(PDOException $e){
        print('DB接続エラー:'.$e->getMessage());
      }
      session_start();

    $sql=$pdo->prepare("SELECT * FROM content WHERE id=?");
    $sql -> execute([$_GET['content_id']]);
    $result = $sql->fetchAll(PDO::FETCH_BOTH);
    $id=$result[0]['id'];
    $day=$result[0]['Day'];
    $title=$result[0]['Title'];
    $content=$result[0]['Content'];
    
    $sql=$pdo->prepare("SELECT * FROM users WHERE Login_ID=?");
    $sql -> execute([$result[0]['Login_ID']]);
    $buf = $sql->fetchAll(PDO::FETCH_BOTH);
    $content_name=$buf[0]['User_Name'];

    echo "
    <section class='article'>
    <p>作者：$content_name</p>
    <a href='../account/account_page.php?content_id=$id'>$content_name</a>
    <p>投稿日時：$day</p>
    <p>タイトル：$title</p>
    <p>記事内容</p>
    <div class='content'>
    <p>$content</p>
    </div>
    </section>";
    ?>

    <!-- いいねぼたん -->
    <button id=good onclick="onClickGood()"></button>
    <div id="good_count"></div>

    <!-- フォロー -->
    <?php
    if ($buf[0]['Login_ID']!=$_SESSION['login_id']) {
        echo "<button id=follow onclick='onClick()'></button>";
    }
    ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    var Content_ID =<?php echo $_GET['content_id']; ?> ;
    //ページ読み込みの時
    $.ajax({
        type: 'post',
        url: "https://taku777.herokuapp.com/content/good.php",
        data: {"Content_ID": Content_ID},
        success: function(result){
            var arr = JSON.parse(result);
            if (arr['good']==1) {
                good.innerHTML = "良いね";
            }　else {
                good.innerHTML = "良いね解除";
            }
            good_count.innerHTML = "いいねの数："+arr['good_count'];
        }
    });

    //ボタンをクリックした時
    function onClickGood() {
        $.ajax({
            type: 'post',
            url: "https://taku777.herokuapp.com/content/good.php?click=1",
            data: {"Content_ID": Content_ID},
            success: function(result){
                var arr = JSON.parse(result);
                if (arr['good']==1) {
                    good.innerHTML = "良いね";
                }　else {
                    good.innerHTML = "良いね解除";
                }
                good_count.innerHTML = "いいねの数："+arr['good_count'];
            }
            });
    }
</script>

<script>
    var Follower_ID ="<?php echo $buf[0]['Login_ID'];?>";
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
