<?php

   // 設定ファイルの読み込み
    require_once('/home/t_katsumata/public_html/akarie/database_config.php');

    require_once('/home/t_katsumata/public_html/akarie/errorlist.php');

    if(isset($_GET['em'])) {

       $error_no=$_GET['em'];

    } else {

       $error_no=0;

    }

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

    mysqli_close($link);


    // ファイル内容を変数に取り込む
    $fp=fopen('./department_signup.html','r');

    // ファイルの最後まで処理を行う
    while(!feof($fp)) {

       // 1行ずつファイルを読み込み変数にセット
       $line=fgets($fp);

       // データベースからセットする項目について置き換え（動的部分）
       // ログイン名
       $line1=str_replace("<###LOGINNAME###>",$login_name,$line);
       $line2=str_replace("<###ERROR###>",$error_msg[$error_no],$line1);

       $lines=$line2;

       //  1行ずつ出力
       echo $lines;
    }

    fclose($fp);

    exit();

?>
