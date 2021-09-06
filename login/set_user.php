<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/set_user.css">
    <title>登録完了</title>
</head>
<body>
<section class="set">
    <?php 
    //ユーザーID、パスワードに重複、謝りがないか確認
    $password_wrong=0;
    $ID_miss=0;

    try{
        $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
    'b0e1b2175788a4','46b12765');
    }catch(PDOException $e){
        print('DB接続エラー:'.$e->getMessage());
    }
    //IDが空だったら
    if(empty($_REQUEST['new_user_id'])) {
        $ID_miss=2;
    }
    //空じゃなければ重複してないかチェック
    foreach($sql=$pdo->query('select * from users') as $row) {
        if ($row['Login_ID']==$_REQUEST['new_user_id']) {
            $ID_miss=1;
            break;
        }
    }

    //入力された２つのパスワードが一致しなかったら、空だったら
    if ($_REQUEST['new_user_password']!=$_REQUEST['new_user_password_re']) {
        $password_wrong=1;
    }  else if (empty($_REQUEST['new_user_password']||$_REQUEST['new_user_password_re'])) {
        $password_wrong=2;
    }

    //登録ページに飛ばす
    if ($password_wrong>=1 || $ID_miss>=1) {
        header("Location:new_page.php?password_wrong=$password_wrong&ID_miss=$ID_miss");
        return;
    }


    //IDを受け取りsqlを用意して登録
    $sql=$pdo->prepare("insert into users values(?,?,DEFAULT,DEFAULT)");
    if($sql->execute([$_REQUEST['new_user_id'],$_REQUEST['new_user_password']])) {
        echo '<p>登録完了です</p>';
    } else {
        echo '<p>登録できませんでした</p>';
    }

    ?>

    <!-- 登録完了後ログイン画面に飛ばす -->
    <a href="../login_page.php">ログインページへ</a>
</section>

</body>
</html>