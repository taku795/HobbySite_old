<!DOCTYPE html>
<html lang="jp">
  <head>
      <meta charset="UTF-8">
      <title>趣味旅行サイト</title>
      <link rel="stylesheet" href="css/home.css">
  </head>

  <body>
    <section class="header">
      <h1 class="main_title">趣味旅行</h1>
      <p class="sub_title">人生を楽しくするための趣味探しをお手伝いするサイト</p>
      <nav class="menubar">
        <div class="menu"><a href="post/post_page.php"><h2>記事を書く</h2></a></div>
        <div class="menu"><a href="menyu/menyu_page.php"><h2>メニュー</h2></a></div>
      </nav>
        <form action="search/search.php">
        <p>キーワード検索：<input type="text" name='key_word' placeholder="テキストを入力">
        <input type="submit" value='検索'></p>
        </form>
    </section>

    <section class="account">
      <div class="good">
        <p>いいねした記事を表示する</p>
        <a href="content/good_content_page.php">こちら</a>
      </div>
      <div class="follow">
        <p>フォローしている人の記事一覧を表示</p>
        <a href="account/follow_user.php">こちら</a>
      </div>
    </section>

    <section class="board">
      <h2>趣味の語り場</h2>
      <p>興味のある趣味について語り合おう！</p>
      <div class="sum">
        <?php 
        try{
          $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
        'b0e1b2175788a4','46b12765');
        }catch(PDOException $e){
          print('DB接続エラー:'.$e->getMessage());
        }
        foreach($sql=$pdo->query('select * from thread') as $row ) {
          echo 
          "
          <article>
          <a href='board/board.php?thread_id=$row[id]'>$row[title]</a>
          </article>
          ";
        }
      ?>
    </div>
  </section>

  <section class="articles">
    <h2>記事一覧</h2>
    <?php
    //記事内容を新しい順に取得して表示
    session_start();

    foreach($sql=$pdo->query('select * from content') as $row ) {
        echo 
        "
        <article>
        <form name='form$row[id]' target='_brank' action='content/content_page.php?content_id=$row[id]' method='post'>
        <a href='javascript:form$row[id].submit()'>
        <p>記事タイトル：$row[Title]</p>
        <p>$row[Content]</p>
        </a>
        </form>
        </article>
        ";
    }
    ?>
  </section>

  <footer>
    <h1>あなたの人生が<br>より楽しいものになりますように</h1>
  </footer>
  
</body>
</html>