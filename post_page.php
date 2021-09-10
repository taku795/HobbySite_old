<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/post_page.css">
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
    <h2>趣味に関することを自由に書いてみよう</h2>
    <form class="post_form" action='post/post.php' method='post'>
        <div class="title_form">
            <p>タイトル</p><input type='text' name='title' placeholder="タイトルを入力">
        </div>
        <div class="content_form">
            <p>投稿内容</p>
            <textarea name="content" rows="10" placeholder="テキストを入力"></textarea>
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
</body>
</html>