<?php

    // 設定ファイルの読み込み
    require_once('/home/t_katsumata/public_html/akarie/database_config.php');

    //  セッション処理
    session_save_path('/home/t_katsumata/session/');

    session_start();
    $login_name=$_SESSION['shain_mei'];
    $login_id=$_SESSION['login_id'];

    // POST PARAMETER
    $shozokubu_mei = $_POST['shozokubu_mei'];
    
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

   //  所属部署 新規登録
   if($_POST['shozokubu_mei'] !="") {

       $query='insert into shozokubu_info(shain_shozokubu_mei) values("'.$shozokubu_mei.'")';

   // クエリ実行
   $result = mysqli_query($link, $query);

    header ('location: ./departmentlist.php');

   }else{

     echo "部署名が入力されていません。";

     exit;

   }

   mysqli_close($link);

?>
