<?php

   // 設定ファイルの読み込み
    require_once('./database_config.php');

    // GET PARAMETER
    $target_manager_id=$_GET['mi'];

    session_save_path('/home/aoyama/session/');

    session_start();
    $login_name=$_SESSION['manager_name'];
    $login_id=$_SESSION['manager_no'];

   // データベースに接続
   $link=mysqli_connect(DB_SERVER,DB_ACCOUNT_ID,DB_ACCOUNT_PW,DB_NAME);
   if (!$link) {
      echo "Error: Unable to connect to MySQL." . PHP_EOL;
      echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
      echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
      exit;
   }

   // 抽出する際のエンコードを設定
   mysqli_set_charset($link,"utf8");

   // Query
   // 管理者情報抽出
$query='select manager_no,password,manager_name from manager_info where manager_id = "'.$target_manager_id.'"';

   // 後ほどhtmlファイルで置き換えするための変数の初期化
   $manager_line="";

   //  実行
   if ($result = mysqli_query($link, $query)) {

        //  レコード数が１でなければエラー
        if(mysqli_num_rows($result) != 1) {
           echo "Error: Can't specify person";
           exit;
        }

        $i=0;

        while ($row = mysqli_fetch_assoc($result)) {

           $manager_no = $row['manager_no'];
           $password = $row['password'];
           $manager_name = $row['manager_name'];

           $i++;

        }

        /* 結果セットを開放します */
        mysqli_free_result($result);
   }


    mysqli_close($link);


    // ファイル内容を変数に取り込む
    $fp=fopen('./manageredit.html','r');

    // ファイルの最後まで処理を行う
    while(!feof($fp)) {

       // 1行ずつファイルを読み込み変数にセット
       $line=fgets($fp);

       // データベースからセットする項目について置き換え（動的部分）
       // ログイン名
       $line1=str_replace("<###LOGINNAME###>",$login_name,$line);

       // 管理者詳細情報
       $line2=str_replace("<###MANAGERNO###>",$manager_no,$line1);
       $line3=str_replace("<###PASSWORD###>",$password,$line2);
       $line4=str_replace("<###MANAGERNAME###>",$manager_name,$line3);

       // hiddenで管理者ID
       $line5=str_replace("<###MANAGERID###>",$target_manager_id,$line4);

       // 代入
       $lines=$line5;

       //  1行ずつ出力
       echo $lines;
    }

    fclose($fp);

    exit();

?>
