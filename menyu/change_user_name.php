<?php  
$pdo=new PDO('mysql:host=localhost;dbname=board;charset=utf8',
'staff','password');
session_start();

if(empty($_REQUEST['user_name'])) {
    $change_N=2;
    header("Location:menyu_page.php?change_N=$change_N");
    return;
}

$sql=$pdo->prepare("update users set User_Name=? where Login_ID=?");
if($sql->execute([$_REQUEST['user_name'],$_SESSION['login_id']])) {
    $_SESSION['user_name']=$_REQUEST['user_name'];
    $change_N=1;
    header("Location:menyu_page.php?change_N=$change_N");
}
?>