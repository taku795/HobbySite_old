<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/menyu.css">
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
        <h2>設定</h2>
        <p>あなただけがみれる画面です</p>
        <div class="loginID">
            <?php
            echo "<p>ログインID　　：$_SESSION[login_id]</p>";
            ?>
        </div>

        <form class="name" method='post' action='menyu/change_user_name.php'>
        <p>名前　　　　　：<input type='text' placeholder='<?php echo $_SESSION['user_name']; ?>' name='user_name'>
        <input type='submit' value='変更'></p>
        </form>

        <form class="mail" action='menyu/change_user_mail.php' method='post'>
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
        <button id="content_delete">記事を編集する</button>
        <p><a href="menyu/finish.php?logout=1">ログアウトする</a></p>
        <?php
        //もしテストユーザーじゃなければアカウント消去できる
        if ($_SESSION['login_id']!='testuser') {
            echo "<p><a href='menyu/account_delete.php'>アカウントを消去する</a></p>";
        }
        ?>
    </section>

    <section class="account-page">
        <h2>アカウントページ画面</h2>
        <p class='sub'><?php echo $_SESSION['user_name']; ?>　さんの記事一覧</p>
        <div class="articles">
            <?php
                $sql=$pdo->prepare('select * from content where Login_ID=?');
                $sql->execute([$_SESSION['login_id']]);
                foreach ($sql as $row) {
                    echo 
                    "
                    <article>
                    <form name='form$row[id]' target='_brank' action='content/content_page.php?content_id=$row[id]' method='post'>
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


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
      $(function() {
        //クリック時
        $('#content_delete').click(function() {
          $('#main').load('menyu/content_delete.php');
        })
      })
    </script>
    
</body>
</html>