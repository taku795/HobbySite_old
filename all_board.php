<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/all_board.css">
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
?>

    <section class="board">
      <h2>趣味の語り場</h2>
      <p>興味のある趣味について語り合おう！</p>
      <div class="sum">
        <?php 
        foreach($sql=$pdo->query('select * from thread') as $row ) {
          echo 
          "
          <article>
          <a href='board/board.php?thread_id=$row[id]' target='brank'>$row[title]</a>
          </article>
          ";
        }
        ?>
      </div>
    </section>

</body>
</html>