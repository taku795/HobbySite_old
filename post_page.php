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
    <section class="form_group">
        <form class="post_form" action='post/post.php' method='post'>
            <div class="title_form">
                <p>タイトル</p><input type='text' name='title' id='title' placeholder="タイトルを入力">
            </div>
            <div class="content_form">
                <p>投稿内容</p>
                <button onclick="mapAdd()" id="map_add" type='button'>マップ</button>
                <div id="map">
                    <div class="input_field">
                        <div class="map_number">
                            マップ番号　<select id="map_num"></select>
                        </div>
                        <button id="map_delete" onclick="mapDelete()" type='button'>消去</button>
                        <div class="form">
                            <div>
                                <div class="address">
                                    住所　：<input type="text" id="map_address" placeholder="住所(必須) を入力してください">
                                </div>
                                <div class="place_name">
                                    場所名：<input type="text" id="map_place_name" placeholder="場所名(任意)を入力してください">
                                </div>
                            </div>
                            
                            <button id="addORedit" onclick="addressInText()" type='button'>追加</button>
                            
                        </div>
                    </div>
                    
                    <div class="search_field">
                        <p>住所がわからない際はこちらから検索できます</p>
                        <div class="search_form">
                            <input type="text" id="text" placeholder="キーワードを入力">
                            <button onclick="search()" type='button'>検索</button>
                        </div>
                        <div class="search_result">
                            検索結果：
                            <select name="" id="map_result"></select>
                            <button onclick="useResult()" type='button'>この結果を使用</button>
                        </div>
                        <div id="map_area"></div>
                    </div>
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
        
                <input type='submit' value='投稿する' formaction="post/post.php">
                <input type="submit" value='プレビューを表示' formaction="post/checkContent.php" formtarget="blank">
            </div>     
        </form>
    </section>
    
    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyBpyyrVRBNkYFhModUxYGrgeJLAsmwW6Uo" ></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        //編集処理
        <?php
        if (isset($edit_content_id)) {
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

            $patern = '/＼＼＼マップ[0-9]:(.+?)＼＼＼/';
            if (preg_match($patern,$content)) {
                $map_num=preg_match_all($patern,$content);
            }
            $content = json_encode($content);
            echo"
            var content = $content;
            var content_title = '$title';
            ";
            if (isset($tag)) {
                echo "var content_tag = $tag;";
            } else {
                echo "var content_tag='';";
            }
            
        }
        ?>

        var geocoder;
        var map;
        //次に追加するマップの番号、初期はマップ起動時に代入するから1
        var next_map_number=1;
        //ないと起動しない
        function initMap() {}
        document.getElementById("map").style.display="none";

        //編集処理があった時はcontentが宣言されている
        if (typeof content != 'undefined') {
            contentInTextarea(content,content_title,content_tag);
        }

        //今（編集前）の記事情報をフォームに代入
        function contentInTextarea(content,content_title,content_tag) {
            //記事をテキストエリアに
            document.getElementById("textarea").value=content;
            //タイトル
            document.getElementById("title").value=content_title;
            //タグ情報
            if (content_tag=="") {
                document.getElementById("tag").options[0].selected=true;
            } else {
                document.getElementById("tag").options[content_tag].selected=true;
            }
            //マップの個数情報
            next_map_number = <?php echo $map_num; ?>+1;
        }

        //mapの表示もしくは非表示
        function mapAdd() {
            var target = document.getElementById("map");

            if(target.style.display=="grid"){
                // noneで非表示
                target.style.display ="none";
                document.getElementById("map_add").innerHTML="マップ";
            }else{
                // gridで表示
                target.style.display ="grid";
                document.getElementById("map_add").innerHTML="閉じる";
            }

            //map_areaにgooglemapを表示
            map = new google.maps.Map(document.getElementById("map_area"),{center:{lat:35.6812369,lng:139.767125},zoom:8});

            makeMapNumber();
        }

        //マップ番号を作成しselectを作る
        function makeMapNumber() {
            var select = document.getElementById('map_num');
            //初期化
            while(select.firstChild){
                select.removeChild(select.firstChild);
            }

            //新規のマップが作れるようにnext_map_numberまでselectを作る
            for (var n=1;n<=next_map_number;n++) {
                var option = document.createElement('option');
                
                option.value = n;
                option.textContent = n;
                select.appendChild(option);
            }
            //最後を選択状態にする
            select.options[next_map_number-1].selected=true;

            //番号が変わらないとjqueryが発動しないため念の為追加に変えておく
            var num = select.options[next_map_number-1].value;
            var area = document.getElementById('textarea');
            var patern = ('＼＼＼マップ'+num+':');
            
            if (area.value.match(patern)==null) {
                //マッチしなかったら新しく追加できるようにする
                document.getElementById('addORedit').innerHTML = "追加";
                document.getElementById('map_address').value="";
                document.getElementById('map_place_name').value="";
            } else {
                document.getElementById('addORedit').innerHTML = "編集";
            }
        }

        function search() {
            //検索結果の次のページがあるかどうか
            var NoNextPage=1;
            //次ページがあったら何枚目かを数える
            var backnumber=0;
            //テキストを取得
            var keyword=document.getElementById("text").value;
            var results=[];

            //テキストの中身を確認
            if (keyword == "") {
                return;
            }

            var service = new google.maps.places.PlacesService(document.createElement('places'));
            service.textSearch({
                //入力されたキーワードで検索
                query: keyword
            }, function(callback, status,pagetoken) {
                if (pagetoken.hasNextPage) {
                    //次のページがあったら
                    NoNextPage=0;
                    backnumber++;
                } else {
                    NoNextPage=1;
                }
                
                //帰ってきたやつをresultsに入れる
                for (var n=0;callback[n]!=null;n++) {
                    var subN=0;
                    switch(backnumber) {
                        case 1:                    
                        break;
                        case 2:
                            subN=20;
                        break;
                        case 3:
                            subN=40;
                        break;
                    }
                    results[subN+n]=callback[n];
                }
                //textSearchの先頭に戻る
                pagetoken.nextPage();

                if (NoNextPage==1) {
                    //次のページがなかったら
                    map.setCenter(results[0].geometry.location);
                    map.setZoom(14);
                    
                    //住所、マーカー挿入
                    for (var n=0;results[n]!=null;n++) {
                        new google.maps.Marker({
                        position: results[n].geometry.location,map: map
                        });
                    }

                    //マップ結果のoptionを追加
                    var select = document.getElementById('map_result');  
                    //初期化
                    while(select.firstChild){
                        select.removeChild(select.firstChild);
                    }
                    for (var n=0;results[n]!=null;n++) {
                        var option = document.createElement('option');
                        option.value = results[n].formatted_address;
                        option.textContent = results[n].name;
                        select.appendChild(option);
                    } 
                } else {
                    //次のページがあったら
                    var select = document.getElementById('map_result');
                    var option = document.createElement('option');
                    //初期化
                    while(select.firstChild){
                        select.removeChild(select.firstChild);
                    }
                    option.value = "検索中";
                    option.textContent = "検索中";
                    select.appendChild(option);

                }
            });    
        }

        function addressInText() {
            //新しい情報を入手
            var add_or_edit = document.getElementById('addORedit');
            var num = document.getElementById('map_num').value;
            var address=document.getElementById('map_address').value;
            var name=document.getElementById('map_place_name').value;
            var area = document.getElementById('textarea');
            var text;

            //住所が入っているか確認
            if (address=="") {
                alert("住所を入力してください");;
                return;
            }

            //挿入するテキストを作成
            //もし最終文字列にスペースがなかったらスペースを作る
            var last;
            for (var n=0;isNaN(name.charCodeAt(n))==false;n++) {
                last=n;
            }
            if (name.charCodeAt(last)!=32) {
                name=`${name} `;
            }

            //マップ形式にする
            text = `＼＼＼マップ${num}:${name}住所:${address}＼＼＼`;

            if (add_or_edit.innerHTML=="追加") {
                //追加処理

                //カーソルの位置を基準に前後を分割して、その間に文字列を挿入
                area.value = area.value.substr(0, area.selectionStart)
                    + text
                    + area.value.substr(area.selectionStart);
                //マップナンバーを一つ進めてマップ番号を振り直す
                next_map_number++;
                makeMapNumber();
            } else {
                //編集処理

                //マップナンバーから検索をする
                var patern = new RegExp(`＼＼＼マップ${num}:(.*?) 住所(.*?)＼＼＼`);
                //置き換え
                area.value=area.value.replace(patern,text);
            }
        }

        function useResult() {
            //選択されているものを読み取り
            var select=document.getElementById('map_result');
            
            for (var n=0;select.options[n]!=null;n++){
                if (select.options[n].selected) {
                    var address = select.options[n].value;
                    var name = select.options[n].textContent;
                }
            }

            //住所を加工
            address = address.replace(/日本、/,"");
            
            //フォームに入力
            document.getElementById('map_address').value= address;
            document.getElementById('map_place_name').value= name;
        }

        function mapDelete() {
            //マップの文字列を検索
            //選択されている数字を取り出す
            var index = document.getElementById('map_num').selectedIndex;
            var num = document.getElementById('map_num').options[index].value;
            //今の情報を取り出す
            var area = document.getElementById('textarea');
            var address = document.getElementById('map_address').value;
            var name = document.getElementById('map_place_name').value;
            
            //番号から検索
            var patern = new RegExp(`＼＼＼マップ${num}:(.*?)＼＼＼`);

            //空白に置き換える
            if (area.value.match(patern)!=null) {
                area.value = area.value.replace(patern,"");
                
                //番号を変える
                for (var n=num;n<next_map_number;n++) {
                    text = area.value;
                    after=n-1;
                    
                    area.value=text.replace(`＼＼＼マップ${n}:`,`＼＼＼マップ${after}:`);
                }
                //一つ消去されたから番号を下げてselectを作り直す
                next_map_number--;
                makeMapNumber();
            }
        }
    </script>
    <script>
        $(function() {
            //セレクトボックスが切り替わったら発動
            $('#map_num').change(function selectChange() {
            //テキストエリアの文字を検索する
            var num = $(this).val();
            var area = $('#textarea').val();
            var patern = new RegExp(`＼＼＼マップ${num}:(.*?) 住所:(.*?)＼＼＼`);
            var address = area.match(patern);
            
            if (area.match(patern)) {
                //検索した文字をtextに出力
                $('#map_address').val(address[2]);
                $('#map_place_name').val(address[1]);
                //編集にする
                $('#addORedit').text("編集");
            } else {
                $('#map_address').val("");
                $('#map_place_name').val("");
                //追加にする
                $('#addORedit').text("追加");
            }
            });
            
        });
    </script>

  </body>
</html>
