<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/post_page.css?v=2">
    <title>記事投稿画面</title>
</head>
<body>
    <div class="header">
        <h2>記事を書こう</h2>
        <p>好きな趣味の魅力や気になっている趣味についてなど自由に書いてみよう</p>
    </div>
    <form class="post_form" action='post.php' method='post'>
        <div class="title_form">
            <p>タイトル</p><input type='text' name='title' placeholder="タイトルを入力">
            <?php
            if ($_GET['no_title']) {
                echo "<p>入力されていません</p>";
            }
            ?>
        </div>
        <div class="content_form">
            <p>投稿内容</p>
            <textarea name="content" rows="10" placeholder="テキストを入力"></textarea>
            <?php
            if ($_GET['no_content']) {
                echo "<p>入力されていません</p>";
            }
            ?>
            <input type='submit' value='投稿する'>
        </div>
    </form>

</body>
</html>