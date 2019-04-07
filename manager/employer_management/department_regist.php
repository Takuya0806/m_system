<?php

    // 設定ファイルの読み込み
    require_once($_SERVER['DOCUMENT_ROOT'] .'/m_system/database_config.php');

    // セッション処理
    // session_save_path('/home/t_katsumata/session/');

    session_start();
    $login_name=$_SESSION['shain_mei'];
    $login_id=$_SESSION['login_id'];

    // POST PARAMETER
    $d_id = $_POST['department_id'];

    $d_name = $_POST['department_name'];
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

   //  所属部情報 更新
   if($sub_option=='1') {

       $query='update department_master set department_name ="'.$d_name.'" where department_id='.$d_id;

    header ('location: ./departmentlist.php');

   //  所属部情報 削除
   } else {

       $query='delete from department_master where department_id ='.$d_id;

    header ('location: ./departmentlist.php');

   }

   //Execute
   if ($result = mysqli_query($link, $query)) {

   } else {

     echo "Error: Update or delete";

     exit;

   }

   mysqli_close($link);
