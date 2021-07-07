<?php require "../header.php"; ?>

<h1>投稿画面</h1>
<form action='post.php' method='post'>
<P>タイトル：<input type='text' name='title'></p>
<?php
if ($_GET['no_title']) {
    echo "<p>入力されていません</p>";
}
?>
<p>投稿内容：<input type='text' name='content'></p>
<?php
if ($_GET['no_content']) {
    echo "<p>入力されていません</p>";
}
?>
<input type='submit' value='投稿'>
</form>

<?php require "../footer.php"; ?>