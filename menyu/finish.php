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
            if (isset($_REQUEST['delete'])) {
                $checked=$_REQUEST['checked_content_id'];

                foreach($checked as $row) {
                    $sql = $pdo->prepare("DELETE FROM content WHERE id=?");
                    $sql->execute([$row]);
                }
                echo "<p>消去しました</p>";
                echo "<a href='../home.php'>ホームへ</a>";
            } else if (isset($_REQUEST['edit'])) {
                $checked=$_REQUEST['checked_content_id'];
                if (isset($checked[1])) {
                    header("Location: ../home.php?edit_error=1");
                    return;
                }
                header("Location: ../home.php?edit_content_id=$checked[0]");
            }
            return;
        }

        //アカウントの消去
        if ($_REQUEST['login_id']!=NULL) {
            $sql = $pdo->prepare("DELETE FROM users WHERE Login_ID=?");
            $sql->execute([$_REQUEST['login_id']]);
            echo "<p>消去しました</p>";
            echo "<a href='../login_page.php'>ログインページへ</a>";
            return;
        }

        //ログアウト
        if ($_REQUEST['logout']==1) {
            header('Location: ../login_page.php');
            return;
        }
        
        header("Location: ../home.php");
    ?>
    </section>

</body>
</html>