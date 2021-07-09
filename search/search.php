<?php require "../header.php"; ?>

<h1>検索結果</h1>

<?php
$pdo=new PDO('mysql:host=localhost;dbname=board;charset=utf8',
'staff','password');

$sql=$pdo->prepare("select * from content where Title or content like ?");
$sql->execute(["%$_REQUEST[key_word]%"]);
foreach ($sql->fetchAll() as $row) {
    foreach($sql=$pdo->query('select * from users') as $row2 ) {
        if ($row['Login_ID']==$row2['Login_ID']) {
            $content_name=$row2['User_Name'];
        }
    }
    echo 
    "<form name='form$row[id]' action='content/content_page.php?content_name=$content_name&title=$row[Title]&day=$row[Day]' method='post'>
    <input type='hidden' name='content' value='$row[Content]'>
    <a href='javascript:form$row[id].submit()'>$content_name-$row[Title]-$row[Content]-$row[Day]</a>
    </form>";
    echo '<br>';
}


?>

<?php require "../footer.php"; ?>