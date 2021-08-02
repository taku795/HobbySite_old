<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/menyu.css?v=2">
    <title>メニュー画面</title>
</head>
<body>
    <h2>メニュー</h2>
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
        <a href="../home.php">ホーム画面へ</a>
    </section>
</body>
</html>