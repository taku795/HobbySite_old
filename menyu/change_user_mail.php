<?php  
$pdo=new PDO('mysql:host=localhost;dbname=board;charset=utf8',
'staff','password');
session_start();

if(empty($_REQUEST['user_mail'])) {
    $change_M=2;
    header("Location:menyu_page.php?change_M=$change_M");
    return;
}
$reg_str = "/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/";
if (!preg_match($reg_str,$_REQUEST['user_mail'])) {
    $change_M=3;
    header("Location:menyu_page.php?change_M=$change_M");
    return;
} 
$sql=$pdo->prepare("update users set User_Mail=? where Login_ID=?");
if($sql->execute([$_REQUEST['user_mail'],$_SESSION['login_id']])) {
    $_SESSION['user_mail']=$_REQUEST['user_mail'];
    $change_M=1;
    header("Location:menyu_page.php?change_M=$change_M");
}
?>