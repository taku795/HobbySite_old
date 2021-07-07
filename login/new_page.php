<?php require "../header.php"; ?>

<form action="set_user.php" method="post">
<p>ユーザーID<input type="text" name="new_user_id"></p>
<?php
switch($_GET['ID_miss']) {
    case 1:
        echo '<p>そのIDは既に使われています</p>';
        break;
    case 2:
        echo '<p>入力されていません</p>';
        break;
}
?>
<p>パスワード<input type="text" name="new_user_password"></p>
<p>パスワード(再確認)<input type="text" name="new_user_password_re"></p>
<?php
switch($_GET['password_wrong']) {
    case 1:
        echo '<p>入力した２つのパスワードが一致しません</p>';
        break;
    case 2:
        echo '<p>入力されていません</p>';
        break;
}
?>
<input type="submit" value="登録">
</form>

<?php require "../footer.php"; ?>