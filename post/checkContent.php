<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/content_page.css">
    <title>プレビュー</title>
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

    //タイムゾーンリセット
    date_default_timezone_set('Asia/Tokyo');
    $day=date("Y-m-d");
    $title=$_REQUEST['title'];
    $content=$_REQUEST['content'];
    $sql=$pdo->prepare('SELECT * from tag_master WHERE Tag_ID=?');
    $sql->execute([$_REQUEST['tag']]);
    $result = $sql->fetchAll(PDO::FETCH_BOTH);
    $tag=$result[0]['Tag_Name'];

    
    echo "
    <section class='article'>
    <div class='article-header'>
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
            <button>いいね</button>
            <div>いいねの数：</div>
        </div>

        <!-- フォロー -->
        <div class="follow-area">
            <button>フォロー</button>        
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
    echo "タグ：$tag";
    ?>

    <div class="social-buttons">
        <!-- いいねぼたん -->
        <div class="good-area">
            <button>いいね</button>
            <div>いいねの数：</div>
        </div>

        <!-- フォロー -->
        <div class="follow-area">        
            <button>フォロー</button>                       
        </div>
        
    </div>
    </section>
    <div style="
        position: fixed;
        right: 20px;
        top: 30px;
        transform: translateX(-50%)
                translateY(-50%);
        ">
        <button onclick="closeWindow()">プレビューを閉じる</button>
    </div>
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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

<script>
    function closeWindow() {
        window.close();
    }
</script>

</body>
</html>
