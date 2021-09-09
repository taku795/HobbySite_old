<?php  
try{
    $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
  'b0e1b2175788a4','46b12765');
  }catch(PDOException $e){
    print('DB接続エラー:'.$e->getMessage());
  }
session_start();

if(empty($_REQUEST['user_name'])) {
    $change_N=2;
    header("Location:../home.php?change_N=$change_N");
    return;
}

$sql=$pdo->prepare("update users set User_Name=? where Login_ID=?");
if($sql->execute([$_REQUEST['user_name'],$_SESSION['login_id']])) {
    $_SESSION['user_name']=$_REQUEST['user_name'];
    $change_N=1;
    header("Location:../home.php?change_N=$change_N");
}
?>