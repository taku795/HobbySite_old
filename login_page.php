<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <meta name="google-signin-client_id" content="485636287764-buq3prhqbqldmgigcsjmcg7p4ct4jl30.apps.googleusercontent.com">
    <title>Document</title>
</head>
<body>
<?php
session_start();
$_SESSION['user_mail']=null;
$_SESSION['user_name']=null;
$_SESSION['login_id']=null;
?>

<!-- 通常ログイン -->
<form action='login/login.php' method='post'>
<p>ログインID<input type='text' name='Login_ID'></p>
<?php
if ($_GET['no_id']) {
  echo '<p>入力されていません</p>';
}
?>

<p>パスワード<input type='text' name='Login_Password'></p>
<?php
if ($_GET['no_password']) {
  echo '<p>入力されていません</p>';
}

if ($_GET['wrong']) {  
  echo '<p>パスワードかIDが間違っています</p>';
}
?>
<p><input type='submit' value='ログイン'></p>
</form>

<!-- Googleログインボタン -->
<p>ログインIDとGoogleアカウントを連携されている方のみ利用できます</p>
<div class="g-signin2" data-onsuccess="onSignIn" onclick="onClick()"></div>

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
        var url='http://localhost/login/request.php?mail='+gmail;

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

<a href="login/new_page.php">新規登録</a>
</body>
</html>
