<?php  
try{
    $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
  'b0e1b2175788a4','46b12765');
  }catch(PDOException $e){
    print('DB接続エラー:'.$e->getMessage());
  }
session_start();

if(empty($_REQUEST['user_mail'])) {
    $change_M=2;
    header("Location:../home.php?change_M=$change_M");
    return;
}
$reg_str = "/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/";
if (!preg_match($reg_str,$_REQUEST['user_mail'])) {
    $change_M=3;
    header("Location:../home.php?change_M=$change_M");
    return;
} 
$sql=$pdo->prepare("update users set User_Mail=? where Login_ID=?");
if($sql->execute([$_REQUEST['user_mail'],$_SESSION['login_id']])) {
    $_SESSION['user_mail']=$_REQUEST['user_mail'];
    $change_M=1;
    header("Location:../home.php?change_M=$change_M");
}
?>