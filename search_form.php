<!DOCTYPE html>
<html lang="jp">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/search_form.css">
</head>
<body>
  <?php
  try{
    $pdo=new PDO('mysql:host=us-cdbr-east-04.cleardb.com;dbname=heroku_57d4f20f139d026;charset=utf8',
    'b0e1b2175788a4','46b12765');
  }catch(PDOException $e){
    print('DB接続エラー:'.$e->getMessage());
  }
  session_start();

  ?>
      <form class="search">
        <div>
          <p>キーワード：<input type="text" name='key_word' placeholder="テキストを入力"></p>
        </div>
        <div>
          <p>タグ：
          <select name="tag">
            <option value="">-</option>
            <?php
            //tag_masterからタグIDとnameを順番に
            foreach($sql=$pdo->query('select * from tag_master') as $row ) {
              echo "<option value=$row[Tag_ID]>$row[Tag_Name]</option>";
            }
            ?>
          </select></p>
        </div>
        <input type="submit" value='検索'>
      </form>

    
</body>
</html>