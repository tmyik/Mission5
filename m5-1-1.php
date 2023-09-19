<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5-1</title>
</head>
<body>
    【簡易掲示板】<br><br><br>
    <form action="" method="post">
        <input type="text" placeholder="山田太郎" name="name"><br>
        <textarea  placeholder="こちらに内容を記入してください" name="comment"></textarea><br>
        <!--<input id="pass" type = "text" name = "pass"  placeholder = "パスワード">-->
        <input type="submit" name="submit"><br><br>

        <input type="number" step="1" min="1" placeholder="削除番号" name="del_num">
        <!--<input id="pass" type = "text" name = "del_pass"  placeholder = "パスワード">-->
        <input type = "submit"  name = "delete" value = "削除"><br>

        <input type="number" step="1" min="1" placeholder="編集番号" name="edit_num">
        <!--<input id="pass" type = "text" name = "edit_pass"  placeholder = "パスワード">-->
        <input type =  "submit" name = "edit" value = "編集"><br> 
    </form>

    <?php

        // DB接続設定
        $dsn = 'mysql:dbname=XXXDB;host=localhost';
        $user = 'XXXUSER';
        $password = 'XXXPASSWORD'
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        //テーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS m5"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date TEXT"
        .");";
        $stmt = $pdo->query($sql);

        //編集機能
        if( !empty($_POST["edit_num"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) ){

            $edit_num = $_POST["edit_num"];     
            $name = htmlspecialchars($_POST["name"], ENT_QUOTES);       //htmlspecialchars…ENT_QUOTES : 掲示板のコメント入力時に「不正なhtmlタグの埋め込み」を防止
            $comment = htmlspecialchars($_POST["comment"], ENT_QUOTES);
            $date = date("Y-m-d H:i:s");

            $sql = 'update m5 set name=:name,comment=:comment,date=:date where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':id', $edit_num, PDO::PARAM_INT);
            $stmt->execute();

        }

        //削除機能
        if(!empty($_POST['del_num']) ){

            $del_num = $_POST['del_num']; 
            //$del_pass = $_POST['del_pass']; 
            $sql = 'delete from m5 where id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':id', $del_num, PDO::PARAM_INT);
            //$stmt->bindParam(':pass', $del_pass, PDO::PARAM_INT);
	        $stmt->execute();

        }

        //投稿機能
        if( !empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["edit_num"])  ){

            $name = htmlspecialchars($_POST["name"], ENT_QUOTES); //htmlspecialchars…ENT_QUOTES : 掲示板のコメント入力時に「不正なhtmlタグの埋め込み」を防止
            $comment = htmlspecialchars($_POST["comment"], ENT_QUOTES);
            $date = date("Y-m-d H:i:s");     //現在日時取得
            //$pass = htmlspecialchars($_POST["pass"], ENT_QUOTES);

            $sql = "INSERT INTO m5 (name, comment, date) VALUES (:name, :comment, :date)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            //$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();

        }

        //未記入防止ポップアップ機能
        if( empty($_POST["name"]) && !empty($_POST["comment"]) ){

            function func_alert($message){
                echo "<script>alert('$message');</script>";
            }
            func_alert("名前を書いてね！");

        }else if( !empty($_POST["name"]) && empty($_POST["comment"])){
             
            function func_alert($message){
                echo "<script>alert('$message');</script>";
            }
            func_alert("コメントを書いてね！");

        }else if( empty($_POST["name"]) && empty($_POST["comment"]) ){
            
            function func_alert($message){
                echo "<script>alert('$message');</script>";
            }
            func_alert("名前・コメント両方書いてね！");

        }

        //データ表示
        $sql = 'SELECT * FROM m5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){             //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
        echo "<hr>";
        }

    ?>
</body>
</html>