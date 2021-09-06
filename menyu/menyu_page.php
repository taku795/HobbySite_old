<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/menyu.css">
    <title>メニュー画面</title>
</head>
<body>
    <?php
    session_start();
    $alert_s="<script>alert('";
    $alert_e="');</script>";
    switch($_GET['change_N']) {
        case 1:
            echo $alert_s;
            echo '変更しました';
            echo $alert_e;
            break;
        case 2:
            echo $alert_s;
            echo '入力欄が空欄です';
            echo $alert_e;
            break;
    } 
    switch($_GET['change_M']) {
        case 1:
            echo $alert_s;
            echo '変更しました';
            echo $alert_e;
            break;
        case 2:
            echo $alert_s;
            echo '入力欄が空欄です';
            echo $alert_e;
            break;
        case 3:
            echo $alert_s;
            echo '正規のメールアドレスではありません';
            echo $alert_e;
    } 
    ?>
    <section class="menyupage">
        <h1>設定</h1>
        <p>あなただけがみれる画面です</p>
        <div class="loginID">
            <?php
            echo "<p>ログインID　　：$_SESSION[login_id]</p>";
            ?>
        </div>

        <form class="name" method='post' action='change_user_name.php'>
        <p>名前　　　　　：<input type='text' placeholder='<?php echo $_SESSION['user_name']; ?>' name='user_name'>
        <input type='submit' value='変更'></p>
        </form>

        <form class="mail" action='change_user_mail.php' method='post'>
        <p>メールアドレス：<input type='text' placeholder='<?php 
        if ($_SESSION['user_mail']==null) {
            echo "設定されていません";
        } else {
            echo "$_SESSION[user_mail]";
        } 
        ?>
        ' name='user_mail'>
        <input type='submit' value='変更'></p>
        </form>
        <p>Googleのメールアドレスを設定するとログインがスムーズになります</p>
        <p><a href="content_delete.php">記事を消去する</a></p>
        <p><a href="finish.php">ログアウトする</a></p>
        <?php
        //もしテストユーザーじゃなければアカウント消去できる
        if ($_SESSION['login_id']!='test') {
            echo "<p><a href='account_delete.php'>アカウントを消去する</a></p>";
        }
        ?>
    </section>

    <a class="home_link" href="../home.php">ホーム画面へ</a>

    <section class="account-page">
        <h1>アカウントページ画面</h1>
        <p class='sub'><?php echo $_SESSION['user_name']; ?>　さんの記事一覧</p>
        <div class="articles">
            <?php
                try{
                    $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
                    'b0e1b2175788a4','46b12765');
                }catch(PDOException $e){
                    print('DB接続エラー:'.$e->getMessage());
                }

                $sql=$pdo->prepare('select * from content where Login_ID=?');
                $sql->execute([$_SESSION['login_id']]);
                foreach ($sql as $row) {
                    echo 
                    "
                    <article>
                    <form name='form$row[id]' target='_brank' action='../content/content_page.php?content_id=$row[id]' method='post'>
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
            ?>
        </div>
    </section>
</body>
</html>