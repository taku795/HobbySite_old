
<?php
$pdo=new PDO('mysql:host=localhost;dbname=board;charset=utf8',
'staff','password');

//データベースのスレッドid列と部屋のidが同じ文章を表示
foreach ($pdo->query('select * from message') as $row) {
    if($row['thread_id']==$_GET['thread_id']) {
        echo "<p>$row[id]$row[name]$row[message]$row[time]</p>";
    }
}
?>