<?php require "../header.php";?>

<?php
//require "input.php";
?>

<?php
//投稿内容があった時データベースに登録
if (!empty($_REQUEST['message'])) {
    try{
        $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
      'b0e1b2175788a4','46b12765');
      }catch(PDOException $e){
        print('DB接続エラー:'.$e->getMessage());
      }

    //投稿内容をデータベースに登録するSQL文の準備
    $sql = $pdo->prepare("insert into message values(null,?,?,?,?)");
    //id(aout),thread_id(int),name(char),message(char),time(date)

    //タイムゾーンリセット
    date_default_timezone_set('Asia/Tokyo');

    //現在時刻を取得
    $time=date("Y,m,d,H,i,s");

    //SQL実行
    $sql->execute([$_REQUEST['thread_id'],$_REQUEST['name'],$_REQUEST['message'],$time]);
}
?>


<!-- 入力フォーム -->
<form action="#" method='post'>
表示名
<p><input type='text' name='name'></p>
一言メッセージ
<p><input type='text' name='message'></p>
<input type='hidden' value=<?php echo $_GET['thread_id']; ?> name='thread_id'>
<input type='submit' value='投稿する'>
</form>

<!-- 境界線 -->
<br><hr>

<!-- 
過去に投稿されたデータをデータベースより取得、
表示
 -->
<?php require "out.php"; ?>

<?php require "../footer.php"; ?>