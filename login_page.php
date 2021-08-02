<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <meta name="google-signin-client_id" content="485636287764-buq3prhqbqldmgigcsjmcg7p4ct4jl30.apps.googleusercontent.com">
    <link rel="stylesheet" href="css/login_page.css">
    <title>ログインページ</title>
</head>
<body>
<?php
session_start();
$_SESSION['user_mail']=null;
$_SESSION['user_name']=null;
$_SESSION['login_id']=null;

try{
  $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
'b0e1b2175788a4','46b12765');
}catch(PDOException $e){
  print('DB接続エラー:'.$e->getMessage());
}

//テストユーザーのアカウント、投稿内容を消去
$pdo->query("DELETE FROM content WHERE Login_ID='test'");
$pdo->query("DELETE FROM users WHERE Login_ID='test'");
//再度テストユーザーを追加
$pdo->query("INSERT INTO users VALUES('test','test',default,default)");
?>

<section class="loginform">
  <!-- 通常ログイン -->
  <form class="nomal_login" action='login/login.php' method='post'>
    <p>ログインID：<input class="text" type='text' name='Login_ID'></p>
    <?php
    if ($_GET['no_id']) {
      echo '<p>入力されていません</p>';
    }
    ?>
    <p>パスワード：<input class="text" type='text' name='Login_Password'></p>
    <?php
    if ($_GET['no_password']) {
      echo '<p>入力されていません</p>';
    }

    if ($_GET['wrong']) {  
      echo '<p>パスワードかIDが間違っています</p>';
    }
    ?>
    <input class="nomal_button" type='submit' value='ログイン'>
  </form>

  <div class="new_test_login">
    <form action="login/login.php" method="psot">
      <input type="hidden" name="Login_ID" value="test">
      <input type="hidden" name="Login_Password" value="test">
      <input class="test_button" type="submit" value="テストユーザーとしてログイン*">
    </form>
    <div class="new_set">
      <a href="login/new_page.php">新規登録</a>
    </div>
  </div>

  <!-- Googleログインボタン -->
  <div class="google_login">
    <p>↓ログインIDとGoogleアカウントを連携されている方のみ利用できます</p>
    <div class="g-signin2" data-onsuccess="onSignIn" onclick="onClick()"></div>
  </div>
</section>
<div class="memo">
  <p>*テストユーザーとして新規登録をせずにログインすることができます</p>
</div>


<!-- ログインボタンが押されて、ログインに成功したら画面遷移 -->
<script>
  var clicked=false;
  function onClick()
  {
    clicked=true;
  }
  function onSignIn(googleUser) {
      if (clicked) {
        var profile = googleUser.getBasicProfile();
        var gmail=profile.getEmail();
        var url='https://taku777.herokuapp.com/login/request.php?mail='+gmail;

        var xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.send();
        xhr.onreadystatechange = function() {
          if(xhr.readyState === 4 && xhr.status === 200) {
              if(xhr.responseText==1) {
                window.location.href = 'login/login.php'; 
              } else {
                alert('登録されていません');
              }
          }
        }
      }
    }
</script>
</body>
</html>
