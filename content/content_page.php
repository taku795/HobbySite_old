<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/content_page.css?v=1">
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
    foreach($sql=$pdo->query("select * from content WHERE id=$_GET[content_id]") as $result ) {
        foreach($sql=$pdo->query('select * from users') as $row ) {
            if ($result['Login_ID']==$row['Login_ID']) {
                $content_name=$row['User_Name'];
            }
        }
        echo "
        <section class='article'>
        <p>作者：$content_name</p>
        <p>投稿日時：$result[Day]</p>
        <p>タイトル：$result[Title]</p>
        <p>記事内容</p>
        <div class='content'>
        <p>$result[Content]</p>
        </div>
        </section>";
    }
    
    ?>
    <!-- いいねぼたん -->
    <button id=good onclick="onClick()"></button>
    <div id="good_count"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    var Content_ID =<?php echo $_GET['content_id']; ?> ;
    //ページ読み込みの時
    $.ajax({
        type: 'post',
        url: "http://localhost/test_folder/content/good.php",
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
    function onClick() {
        $.ajax({
            type: 'post',
            url: "http://localhost/test_folder/content/good.php?click=1",
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

</body>
</html>
