<?php require "header.php"; ?>
<?php $pdo=new PDO('mysql:host=localhost;dbname=board;charset=utf8',
'staff','password');
foreach($sql=$pdo->query('select * from thread') as $row ) {
    echo "<a href='board.php?thread_id=$row[id]'>$row[title]</a>";
    echo '<br>';
}
?>

<?php require "footer.php"; ?>