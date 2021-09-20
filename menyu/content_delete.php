<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/content_delete.css">
</head>
<body>
    <h2>消去する記事を選択</h2>
    <form action="menyu/finish.php">
    <section class="articles">
    <?php
    try{
        $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
        'b0e1b2175788a4','46b12765');
    }catch(PDOException $e){
        print('DB接続エラー:'.$e->getMessage());
    }
    session_start();

    $sql=$pdo->prepare('select * from content where Login_ID=?');
    $sql->execute([$_SESSION['login_id']]);
    foreach ($sql as $row) {
        echo 
        "
        <input id='check$row[id]'  class='checkbox-hidden' type='checkbox' name='checked_content_id[]' value='$row[id]'>
        <label for='check$row[id]'>
        <article id='target$row[id]'>
        <div class='content'>
        <p>タイトル：$row[Title]</p>
        <div class='content-body'>
        <p>$row[Content]</p>
        </div>
        </div>
        </article>
        </label>
        ";
        echo 
        "
        <script>
            document.getElementById('target$row[id]').addEventListener('click',function(){
            if(document.getElementById('target$row[id]').style.background == 'rgb(176, 218, 238)') {
                document.getElementById('target$row[id]').style.background = 'white';
            } else {
                document.getElementById('target$row[id]').style.background = 'rgb(176, 218, 238)';
            }
            })
        </script>
        ";
    }
    ?>
    <div class="button">
        <input class='submit-button' name="delete" type="submit" value="消去">
        <input type="submit" name="edit" value="編集">
    </div>
    </form>
    </section>
</body>
</html>