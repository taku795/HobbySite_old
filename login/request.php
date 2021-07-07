<?php 
$pdo=new PDO('mysql:host=localhost;dbname=board;charset=utf8',
'staff','password');
session_start();

foreach($sql=$pdo->query('select * from users') as $row ) {
    if ($row['User_Mail']==$_GET['mail']) {
        $_SESSION['user_mail']=$_GET['mail'];
        echo 1;
        return;
    }
}
echo 2;



?>