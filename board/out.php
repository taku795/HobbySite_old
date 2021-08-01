
<?php
try{
    $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
  'b0e1b2175788a4','46b12765');
  }catch(PDOException $e){
    print('DB接続エラー:'.$e->getMessage());
  }

//データベースのスレッドid列と部屋のidが同じ文章を表示
foreach ($pdo->query('select * from message') as $row) {
    if($row['thread_id']==$_GET['thread_id']) {
        echo "
        <p class='name'>$row[name] </p>
        <p class='sann'>さん</p>
        <p class='time'>$row[time]</p>
        <p class='message'>$row[message]</p>
        <br>
        ";
    }
}
?>