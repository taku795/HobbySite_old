<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/account_delete.css">
    <title>アカウント消去</title>
</head>
<body>
    <section class="delete_form">
        <p>本当にこのアカウントを消去しますか？</p>
        <?php
        session_start();
        echo "<p>ログインID：$_SESSION[login_id]</p>";
        echo "<p>名前：$_SESSION[user_name]</p>";
        ?>
        <form action="finish.php" method="post">
            <input type="submit" value="消去する">
            <input type="hidden" name="login_id" value="<?php echo $_SESSION['login_id']; ?>">
        </form>
    </section>
    
</body>
</html>