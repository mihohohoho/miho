<!DOCTYPE html>

    <html lang="ja">
    <head>
     <meta chartset="UTF-8">
    　<tytle>Mission_5-1 </tytle>
        </head>
        　<body>
  <?php
  //データベースに接続する
    $dsn = 'mysql:dbname=tb******db;host=localhost';
    $user = 'tb-******';
    $password = '**********';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
  //テーブルを作成する
     $sql = "CREATE TABLE IF NOT EXISTS tbtest2"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql);

    $name=$_POST["name"];
    $comment=$_POST["comment"];
    $date=date("Y年m月d日 H時i分s秒");
    $pass=$_POST["pass"];
    $deleteid=$_POST["deleteid"];
    $editid=$_POST["editid"];
    $deletepassword=$_POST["deletepassword"];
    $editpassword=$_POST["editpassword"];

//編集選択    
     if(!empty($editid&&$editpassword))
     //データベースから表の情報を抜き出す
       {$sql = 'SELECT * FROM tbtest2';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();//fetchAllとは？？
       //idと編集対象番号を照らし合わせて
        foreach ($results as $row)
                {if($editid==$row['id'])
      //パスワードを確認する
                   {$editedpass=$row['pass'];
                    if($editedpass!=$editpassword)
                      {echo "【編集番号とパスワードが一致しません】<br>";
                      }
    //パスワードがあっていたらフォームで表示するため名前・コメントを定義する
                    else{$editname=$row['name'];
                         $editcom=$row['comment'];
                        }
                   }  
                }
       }
     //フォームを作る
    ?>        

      　 <form action="" method="post">
        　  投稿フォーム<br>
            <input type="text" name="name" placeholder="名前" value="<?php if(!empty($editname)){echo $editname ;} ?>">
            <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($editcom)){echo $editcom ;} ?>">
            <input type="hidden" name="editedid" value="<?php echo $editid ;?>">
            <input type="password" name="pass" placeholder="パスワード">
            <input type="submit" name="submit" value="送信"><br>
            ---------------------------------------------------------<br>
          　削除フォーム<br>
            <input type="number" name="deleteid" placeholder="削除番号">
            <input type="password" name="deletepassword" placeholder="パスワード">
            <input type="submit" name="submit" value="削除"><br>
            ---------------------------------------------------------<br>
            編集フォーム<br>
            <input type="number" name="editid" placeholder="編集番号">
            <input type="password" name="editpassword" placeholder="パスワード">
            <input type="submit" name="edit" value="編集番号"><br>
            ---------------------------------------------------------<br>
 　　　　　　</form> 
  
    
<?php  
//投稿した内容をデータベースに保存する
//情報をデータベースから抽出し、画面にうつす
//編集実行
    //編集内容が送信されたら
    $editedid=$_POST["editedid"];
     if(!empty($comment&&$name&&$editedid))
                     {$id=$editedid;
                      $name=$_POST["name"];
                      $comment=$_POST["comment"];
                      $date=date("Y年m月d日 H時i分s秒");
                      $pass=$_POST["pass"];
                     //ここで編集対象番号の値の受け取りを行う
                      $sql = 'UPDATE tbtest2 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
                      $stmt = $pdo->prepare($sql);
                      $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
                      $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
                      $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
                      $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
                      $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                      $stmt -> execute();
                     }
                

//投稿    
     elseif(!empty($comment&&$name))
           { $date = date("Y年m月d日 H時i分s秒");
             $sql = $pdo -> prepare("INSERT INTO tbtest2 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
             $sql -> bindParam(':name', $name, PDO::PARAM_STR);
             $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
             $sql -> bindParam(':date', $date, PDO::PARAM_STR);
             $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
             $sql -> execute();
           }
     
//削除
     elseif(!empty($deleteid&&$deletepassword))
       {$sql = 'SELECT * FROM tbtest2';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();//fetchAllとは？？
       //idと編集対象番号を照らし合わせて
        foreach ($results as $row)
                {if($deleteid==$row['id'])
      //パスワードを確認する
                   {$deletedpass=$row['pass'];
                    if($deletedpass!=$deletepassword)
                      {echo "【番号とパスワードが一致しません】<br><br>";
                      }
                       else{$id = $deleteid;
                            $sql = 'delete from tbtest2 where id=:id';
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt->execute();

                           }
                    }
                }
       }
    if(empty($comment or $name or $deleteid or $deletepassword or $editid or $editpassword)){echo"【何か打ってください】<br><br>";}
 //表示機能
     $sql = 'SELECT * FROM tbtest2';
     $stmt = $pdo->query($sql);
     $results = $stmt->fetchAll();
     foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
    echo "<hr>";
    }
     

     /*https://teratail.com/questions/287091参照*/
    ?>
    
           
          </body>
      </html>
           
