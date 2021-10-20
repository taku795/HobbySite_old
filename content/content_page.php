<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/content_page.css">
    <title>記事ページ</title>
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

    //コンテンツIDから著者の情報を抽出
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

    //コンテンツIDからついているタグを抽出
    $sql=$pdo->prepare("SELECT * FROM tag_to_content WHERE Content_ID=?");
    $sql -> execute([$_GET['content_id']]);
    $result = $sql->fetchAll(PDO::FETCH_BOTH);
    for($tag_number=0;$result[$tag_number]['id']!=NULL;$tag_number++) {
        //tagidからtag_masterを検索
        $sql=$pdo->prepare("SELECT * FROM tag_master WHERE Tag_ID=?");
        $sql -> execute([$result[$tag_number]['Tag_ID']]);
        $result_buf = $sql->fetchAll(PDO::FETCH_BOTH);

        //検索結果をタグ配列に代入
        $tag[$tag_number]=$result_buf[0]['Tag_Name'];
    }

    echo "
    <section class='article'>
    <div class='article-header'>
        <div class='profile'>
            <p>プロフィール：<a href='../account/account_page.php?content_id=$id'>$content_name</a></p>
            <a href='../account/account_page.php?content_id=$id'>この作者について</a>
        </div>
        <div class='day'>
            <p>$day</p>
        </div>
            <div class='title'>
            <h1>$title</h1>
        </div>
    </div>
    ";
    
    ?>

    <div class="social-buttons">
        <!-- いいねぼたん -->
        <div class="good-area">
            <button id=good onclick="onClickGood()"></button>
            <div id="good_count"></div>
        </div>

        <!-- フォロー -->
        <div class="follow-area">
            <?php
            if ($buf[0]['Login_ID']!=$_SESSION['login_id']) {
                echo "<button id=follow onclick='onClick()'></button>";
            }
            ?>
        </div>
        

        <!-- Twitterに共有 -->
        <?php
        //url作成
        $twitter_url = "https://twitter.com/share?text=$title";
        ?>
        <div class="twitter-area">
            <a href="<?php echo $twitter_url; ?>" class="twitter-share-button" data-show-count="false">Tweet</a>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            <p>記事を共有する</p>
        </div>
        
    </div>

    <!-- 記事内容 -->
    <?php
    $patern = '/＼{3}マップ([1-9]):.*? 住所:(.*?)＼{3}/u';
    if (preg_match($patern,$content)) {
        $content=preg_replace($patern,"<div id='map$1_area' class='map'></div><p id='map$1_address' class='map_place'>$2</p><p>",$content);
    }
    echo "
    <div class='content'>
    <p>$content
    </div>
    ";
    ?>

    <!-- タグ -->
    <?php
    echo "タグ：";
    foreach($tag as $row) {
        echo "$row";
    }
    ?>

    <div class="social-buttons">
        <!-- いいねぼたん -->
        <div class="good-area">
            <button id=good2 onclick="onClickGood()"></button>
            <div id="good_count2"></div>
        </div>

        <!-- フォロー -->
        <div class="follow-area">
            <?php
            if ($buf[0]['Login_ID']!=$_SESSION['login_id']) {
                echo "<button id=follow2 onclick='onClick()'></button>";
            }
            ?>
        </div>
        

        <!-- Twitterに共有 -->
        <?php
        //url作成
        $twitter_url = "https://twitter.com/share?text=$title";
        ?>
        <div class="twitter-area">
            <a href="<?php echo $twitter_url; ?>" class="twitter-share-button" data-show-count="false">Tweet</a>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            <p>記事を共有する</p>
        </div>
        
    </div>
    </section>
  
    <section class="comment-area">
        <div class="comment">
            <p>------コメント------</p>
            <!-- コメントを表示 -->
            <?php
            //nullを消去
            $pdo->query("DELETE FROM comment_to_content WHERE Content_ID IS null OR Login_ID IS null");
            
            $sql = $pdo -> prepare("SELECT * FROM comment_to_content WHERE Content_ID=?");
            $sql -> execute([$_GET['content_id']]);
            $result_buf = $sql->fetchAll(PDO::FETCH_BOTH);
            for ($number=0;$result_buf[$number]['id']!=NULL;$number++) {
                $comment=$result_buf[$number]['Comment'];

                $sql = $pdo -> prepare("SELECT * FROM users WHERE Login_ID=?");
                $sql -> execute([$result_buf[$number]['Login_ID']]);
                $result = $sql->fetchAll(PDO::FETCH_BOTH);
                $name=$result[0]['User_Name'];

                echo "<p>$name ： $comment</p>";
            }
            ?>
        </div>

        <div class="comment-form">
            <!-- コメントを登録 -->
            <form action="comment.php" method="post">
                <textarea name="comment" cols="60" rows="10"></textarea>
                <input type="hidden" name="content_id" value="<?php echo $_GET['content_id']; ?>">
                <input type="submit" value="コメントする">
            </form>
        </div>
    </section>
    
    <a class="home_link" href="../home.php">ホーム画面へ</a>

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
                good2.innerHTML = "良いね";
            }　else {
                good.innerHTML = "良いね解除";
                good2.innerHTML = "良いね解除";
            }
            good_count.innerHTML = "いいねの数："+arr['good_count'];
            good_count2.innerHTML = "いいねの数："+arr['good_count'];
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
                    good2.innerHTML = "良いね";
                }　else {
                    good.innerHTML = "良いね解除";
                    good2.innerHTML = "良いね解除";
                }
                good_count.innerHTML = "いいねの数："+arr['good_count'];
                good_count2.innerHTML = "いいねの数："+arr['good_count'];
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
                    follow2.innerHTML = 'フォロー';
                } else {
                    follow.innerHTML = 'フォロー中';
                    follow2.innerHTML = 'フォロー中';
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
                    follow2.innerHTML = 'フォロー';
                } else {
                    follow.innerHTML = 'フォロー中';
                    follow2.innerHTML = 'フォロー中';
                }
            }
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyBpyyrVRBNkYFhModUxYGrgeJLAsmwW6Uo" ></script>
<script>
    initMap();
    function initMap() {
            <?php
            //変換後の記事内容を読み込んでマップクラスを検索しscriptを作成
            $patern = "class='map'";
            $map=substr_count($content,$patern);
            for ($n=1;$n<=$map;$n++) {
            echo 
            "
            if (document.getElementById('map${n}_area')) {
                var target = document.getElementById('map${n}_area');
                var address = document.getElementById('map${n}_address').innerHTML; 

                var service = new google.maps.places.PlacesService(document.createElement('places'));
                service.textSearch({
                    //入力されたキーワードで検索
                    query: address
                }, function(callback, status) {   
                    if (status === 'OK' && callback[0]){ 
                    //緯度軽度とズームを設定
                    var map_setting={
                        center:callback[0].geometry.location,
                        zoom:14
                    }
                    //マップを導入
                    var map=new google.maps.Map(
                        document.getElementById('map${n}_area'),
                        map_setting
                    ); 
                    //マーカーを設置                                     
                    new google.maps.Marker({
                        position: callback[0].geometry.location,map:map 
                        });                   
                    }else{ 
                    alert('失敗しました。理由: ' + status);
                    return;
                    }                
                });
            }
            ";
            }
            ?>              
    }
</script>

</body>
</html>
