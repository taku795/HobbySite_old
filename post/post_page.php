<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/post_page.css">
    <title>記事投稿画面</title>
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
    ?>
    <h1>趣味に関することを自由に書いてみよう</h1>
    <form class="post_form" action='post.php' method='post'>
        <div class="title_form">
            <p>タイトル</p><input type='text' name='title' placeholder="タイトルを入力">
            <?php
            if ($_GET['no_title']) {
                echo "<p>入力されていません</p>";
            }
            ?>
        </div>
        <div class="content_form">
            <p>投稿内容</p>
            <textarea name="content" rows="10" placeholder="テキストを入力"></textarea>
            <?php
            if ($_GET['no_content']) {
                echo "<p>入力されていません</p>";
            }
            ?>
            タグをつける：
            <select name="tag">
            <option value="">-</option>
            <?php
            //tag_masterからタグIDとnameを順番に
            foreach($sql=$pdo->query('select * from tag_master') as $row ) {
                echo "<option value=$row[Tag_ID]>$row[Tag_Name]</option>";
            }
            ?>
            </select>
            <br>
            <input type='submit' value='投稿する'>
        </div>
        
    </form>

    <a class="home_link" href="../home.php">ホーム画面へ</a>

</body>
</html>