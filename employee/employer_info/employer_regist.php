<?php

    // 設定ファイルの読み込み
    require_once($_SERVER['DOCUMENT_ROOT'] .'/m_system/database_config.php');

    //  セッション処理
    // session_save_path('/home/t_katsumata/session/');

    session_start();
    $login_name=$_SESSION['shain_mei'];
    $login_id=$_SESSION['login_id'];

    // POST PARAMETER
    $e_id = $_POST['employer_id'];

    $id = $_POST['id'];
    $password = $_POST['password'];
    
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

   //  社員情報 更新
   if($id && $password !="") {

       $query='update shain_info set login_id ="'.$id.'",password ="'.$password.'" where employer_id='.$e_id;

    header ('location: ../menu.php');

    exit;

   } else {

	echo "ID・パスワードを入力して下さい。";

   }


   mysqli_close($link);
