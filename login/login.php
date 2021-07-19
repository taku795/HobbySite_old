<?php 
try{
  $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
'b0e1b2175788a4','46b12765');
}catch(PDOException $e){
  print('DB接続エラー:'.$e->getMessage());
}
session_start();

// Googleログイン時
if ($_SESSION['user_mail']!=null) {
  foreach($sql=$pdo->query('select * from users') as $row ) {
    if ($row['User_Mail']==$_SESSION['user_mail']) {
      $_SESSION['user_name']=$row['User_Name'];
      $_SESSION['login_id']=$row['Login_ID'];
      header("Location:../home.php");
      return;
    }
  }
}

// 通常ログイン時
$wrong=0;
$no_id=0;
$no_password=0;
if (empty($_REQUEST['Login_ID'])) {
  $no_id=1;
}
if (empty($_REQUEST['Login_Password'])) {
  $no_password=1;
}
if ($no_id || $no_password) {
  header("Location:../login_page.php?no_id=$no_id&no_password=$no_password");
  return;
}
foreach($sql=$pdo->query('select * from users') as $row ) {
    if ($row['Login_ID']==$_REQUEST['Login_ID'] && $row['Login_Password']==$_REQUEST['Login_Password']) {
      $_SESSION['login_id']=$_REQUEST['Login_ID'];
      $_SESSION['user_name']=$row['User_Name'];
      $_SESSION['user_mail']=$row['User_Mail'];
      header("Location:../home.php");
      return;
    } else {
      $wrong=1;
    }
}
header("Location:../login_page.php?wrong=$wrong");
?>