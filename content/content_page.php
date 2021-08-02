<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/content_page.css?v=2">
    <title>記事ページ</title>
</head>
<body>
    <h2>記事</h2>
    <?php
    echo 
    "
    <section class='article'>
        <p>作者：$_GET[content_name]</p>
        <p>投稿日時：$_GET[day]</p>
        <p>タイトル：$_GET[title]</p>
        <p>記事</p>
        <div class='content'><p>$_POST[content]</p></div>
    </section>
    ";
    ?>
</body>
</html>
