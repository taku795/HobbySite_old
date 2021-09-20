<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/post_page.css">
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
    <h2>趣味に関することを自由に書いてみよう</h2>
    <form class="post_form" action='post/post.php' method='post'>
        <div class="title_form">
        <p>タイトル</p><input type='text' name='title' id='title' placeholder="タイトルを入力">
        </div>
        <div class="content_form">
            <p>投稿内容</p>
            <div class="mapform">
                <button type="button" onclick="makeMap()">マップ</button>
                マップを消去
                <select id="mapnum">
                </select>
                <button type="button" onclick="deleteMap()">消去</button>
            </div>
            <div class="tag">
                タグをつける：
                <select name="tag" id="tag">
                <option value="">-</option>
                <?php
                //tag_masterからタグIDとnameを順番に
                foreach($sql=$pdo->query('select * from tag_master') as $row ) {
                    echo "<option value=$row[Tag_ID]>$row[Tag_Name]</option>";
                }
                ?>
                </select>
                <br>
            </div>
            <textarea id="textarea" name="content" rows="10" placeholder="テキストを入力"></textarea>
            <!-- 編集処理 -->
            <?php
            if (isset($_REQUEST['edit_content_id'])) {
                $edit_content_id = $_REQUEST['edit_content_id'];
                //編集の時はrequestに入れる
                echo "<input type='hidden' name='edit_content_id' value='$edit_content_id'>";
            }
            ?>
    
            <input type='submit' value='投稿する'>
        </div>     
    </form>
    
    <script>
        //編集処理
        <?php
        $sql=$pdo->prepare("SELECT * FROM content WHERE id=?");
        $sql -> execute([$edit_content_id]);
        $result = $sql->fetchAll(PDO::FETCH_BOTH);
        $title=$result[0]['Title'];
        $content=$result[0]['Content'];
        //コンテンツIDからついているタグを抽出
        $sql=$pdo->prepare("SELECT * FROM tag_to_content WHERE Content_ID=?");
        $sql -> execute([$edit_content_id]);
        $result = $sql->fetchAll(PDO::FETCH_BOTH);
        $tag=$result[0]['Tag_ID'];
        ?>
        var content = "<?php echo $content; ?>";
        var title = "<?php echo $title; ?>";
        var tag = <?php
        if(isset($tag)) {
            echo $tag;
        } else {
            echo 101;
        } ?>;
        if (typeof content != 'undefined') {
            document.getElementById('textarea').value = content;
            document.getElementById('title').value = title;
            if (tag!=101) {
                document.getElementById('tag').options[tag].selected = true;
            }
        }
    </script>
    <script>
        //マップ処理
        //テキストエリアの中身を一度見てマップがないか確認
        var num=1;
        makeOption();
        function makeOption() {
            var select = document.getElementById('mapnum');  
            //初期化
            while( select.firstChild ){
                select.removeChild(select.firstChild );
            }
            //numに応じてselectの子要素にoptionを追加
            var n =0;
            now_num=num-1;
            for (n;n<=now_num;n++) {
                var option = document.createElement('option');
                if (n==0) {
                option.value = "";
                option.textContent = "-";
                } else {
                option.value = n;
                option.textContent = n;
                }
                select.appendChild(option);
            }     
        }
            
        function deleteMap() {
            var text=document.getElementById('textarea').value;
            var mapnum = document.getElementById('mapnum').value;
            var patern = new RegExp("\\\\\\\\\\\\マップ"+mapnum+":(.+)\\\\\\\\\\\\ ",'g');

            if (text.match(patern)) {
            text=text.replace(patern,"");
            document.getElementById('textarea').value=text;
            if (mapnum==num) {
                num--;
            } else {        
                var buf;
                for (buf=mapnum;buf<=num;buf++) {
                var patern2 = new RegExp("\\\\\\\\\\\\マップ"+buf);
                var buf_buf=buf-1;
                var after = "\\\\\\マップ" +buf_buf;
                text=text.replace(patern2,after);
                document.getElementById('textarea').value=text;
                }
                num--;
            }
            }
            makeOption();
        }

        function makeMap() {
            var area = document.getElementById('textarea');
            var text = '\\\\\\\マップ'+ num +':場所を入力\\\\\\\ '+ '\n';
            //カーソルの位置を基準に前後を分割して、その間に文字列を挿入
            area.value = area.value.substr(0, area.selectionStart)
                + text
                + area.value.substr(area.selectionStart);
            num++;
            makeOption();
        }
    </script>

  </body>
</html>
