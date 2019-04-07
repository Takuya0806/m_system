<?php

    // 設定ファイルの読み込み
    require_once($_SERVER['DOCUMENT_ROOT'] .'/m_system/database_config.php');

    //  セッション処理
    // session_save_path('/home/t_katsumata/session/');

    session_start();
    $login_name=$_SESSION['shain_mei'];
    $login_id=$_SESSION['login_id'];

    // POST PARAMETER
    $p_id = $_POST['position_id'];

    $p_name = $_POST['position_name'];
    $sub_option = $_POST['submit_option'];
    
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

   //  役職情報 更新
   if($sub_option=='1') {

       $query='update position_master set position_name ="'.$p_name.'" where position_id='.$p_id;

    header ('location: ./positionlist.php');

   //  役職情報 削除
   } else {

       $query='delete from position_master where position_id ='.$p_id;

    header ('location: ./positionlist.php');

   }

   //Execute
   if ($result = mysqli_query($link, $query)) {

   } else {

     echo "Error: Update or delete";

     exit;

   }

   mysqli_close($link);
