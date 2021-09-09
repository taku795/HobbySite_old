<!DOCTYPE html>
<html lang="jp">
  <head>
      <meta charset="UTF-8">
      <title>趣味旅行サイト</title>
      <link rel="stylesheet" href="css/home.css">
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
      
      //名前変更時
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

    <section class="header">
      <div class="title">
        <h1 class="main_title">趣味旅行</h1>
        <p class="sub_title">人生を楽しくするための趣味探しをお手伝いするサイト</p>
      </div>
      <nav class="menubar">
        <button id="home"><a href="home.php">ホーム</a></button>
        <button id="room">語り部屋</button>
        <button id="search">検索</button>
        <button id="menyu">メニュー</button>
        <button id="good">いいね</button>
        <button id="follow">フォロー</button>
        <form action="post/post_page.php">
        <button>記事を書く</button>
        </form>
      </nav>
    </section>

    
    <div id="main" class="main"></div>

    <footer>
      <h1>あなたの人生が<br>より楽しいものになりますように</h1>
    </footer>
    
    <script src="http://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
      $(function() {
        //通常読み込み時
        $('#main').load('all_content_page.php');

        //検索時
        <?php
        if ($_REQUEST['key_word'] || $_REQUEST['tag']) {
          echo "$('#main').load('search/search.php?key_word=$_REQUEST[key_word]&tag=$_REQUEST[tag]');";
        }
        ?>

        //クリック時
        $('#home').click(function() {
          $('#main').load('all_content_page.php');
        })
        $('#room').click(function() {
          $('#main').load('all_board.php');
        })
        $('#search').click(function() {
          $('#main').load('search_form.php');
        })
        $('#write').click(function() {
          $('#main').load('post_page.php');
        })
        $('#menyu').click(function() {
          $('#main').load('menyu_page.php');
        })
        $('#good').click(function() {
          $('#main').load('good_content_page.php');
        })
        $('#follow').click(function() {
          $('#main').load('follow_user.php');
        })
        
      })
    </script>
  
  </body>
</html>