<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/finish.css">
    <title>消去完了</title>
</head>
<body>
    <section class="finish">
        <?php
        try{
            $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
            'b0e1b2175788a4','46b12765');
        }catch(PDOException $e){
            print('DB接続エラー:'.$e->getMessage());
        }
        session_start();

        //記事の消去
        if ($_REQUEST['checked_content_id']!=NULL) {

            $checked=$_REQUEST['checked_content_id'];

            foreach($checked as $row) {
                $sql = $pdo->prepare("DELETE FROM content WHERE id=?");
                $sql->execute([$row]);
            }
            echo "<p>消去しました</p>";
            echo "<a href='menyu_page.php'>メニュートップへ</a>";
        }

        //アカウントの消去
        if ($_REQUEST['login_id']!=NULL) {
            $sql = $pdo->prepare("DELETE FROM users WHERE Login_ID=?");
            $sql->execute([$_REQUEST['login_id']]);
            echo "<p>消去しました</p>";
            echo "<a href='../login_page.php'>ログインページへ</a>";
        }

        //ログアウト
        if ($_REQUEST['login_id']==NULL && $_REQUEST['checked_content_id']==NULL) {
            header('Location: ../login_page.php');
        }
    ?>
    </section>

</body>
</html>