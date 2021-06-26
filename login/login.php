<?php require "../header.php"; ?>

<?php 
$pdo=new PDO('mysql:host=localhost;dbname=board;charset=utf8',
'staff','password');

foreach($sql=$pdo->query('select * from users') as $row ) {
    if ($row['Login_ID']==$_REQUEST['Login_ID'] && $row['Login_Password']==$_REQUEST['Login_Password']) {
      header("Location:../home.php");
    } else {
      echo 'IDかパスワードが間違っています';
    }
}
?>

<?php require "../footer.php"; ?>