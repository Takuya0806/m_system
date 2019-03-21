<?php

   // 設定ファイルの読み込み
    require_once('/home/t_katsumata/public_html/akarie/database_config.php');

    // GET PARAMETER
    $target_user_id=$_GET['ei'];

    session_save_path('/home/t_katsumata/session/');

    session_start();
    $login_name=$_SESSION['shain_mei'];
    $login_id=$_SESSION['login_id'];

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
   // 従業員情報抽出（shain_info , shozokubu_info）
$query='select login_id,password from employer_master where employer_id ="'.$target_user_id.'"';

   // 後ほどhtmlファイルで置き換えするための変数の初期化
   $employer_line="";

   //  実行
   if ($result = mysqli_query($link, $query)) {

        //  レコード数が１でなければエラー
        if(mysqli_num_rows($result) != 1) {
           echo "Error: Can't specify person";
           exit;
        }

        foreach ($result as $row) {

           $id = $row['login_id'];
           $password = $row['password'];

        }

        /* 結果セットを開放します */
        mysqli_free_result($result);
   }


    mysqli_close($link);


    // ファイル内容を変数に取り込む
    $fp=fopen('./employeredit.html','r');

    // ファイルの最後まで処理を行う
    while(!feof($fp)) {

       // 1行ずつファイルを読み込み変数にセット
       $line=fgets($fp);

       // データベースからセットする項目について置き換え（動的部分）
       // ログイン名
       $line1=str_replace("<###LOGINNAME###>",$login_name,$line);

       // 従業員詳細情報
       $line2=str_replace("<###EMPLOYERID###>",$target_user_id,$line1);
       $line3=str_replace("<###LOGINID###>",$id,$line2);
       $line4=str_replace("<###PASSWORD###>",$password,$line3);

       // 代入
       $lines=$line4;

       //  1行ずつ出力
       echo $lines;
    }

    fclose($fp);

    exit();

?>
