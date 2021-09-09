<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <title>掲示板</title>
    <link rel="stylesheet" href="../css/board.css">
</head>
<body>

<?php 
require "input.php";
session_start();
?>

<!-- 入力フォーム -->
<form class="postform" action="#" method='post' class="postform">
表示名：<input type='text' name="name" placeholder="<?php echo $_SESSION['user_name']; ?>">
登校内容：<input type="text" name='message' placeholder="テキストを入力" size="40">
<input type='hidden' value=<?php echo $_GET['thread_id']; ?> name='thread_id'>
<input type='submit' value='投稿する'>
</form>

<section class="content">
  <?php 
  require "out.php"; 
  ?>
</section>

</body>
</html>