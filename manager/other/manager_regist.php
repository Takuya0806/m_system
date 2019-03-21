<?php

    // 設定ファイルの読み込み
    require_once('./database_config.php');

    //  セッション処理
    session_save_path('/home/aoyama/session/');

    session_start();
    $login_name=$_SESSION['manager_name'];
    $login_id=$_SESSION['manager_no'];

    // POST PARAMETER
    $manager_id = $_POST['manager_id'];

    $manager_no = $_POST['manager_no'];
    $password = $_POST['password'];
    $manager_name = $_POST['manager_name'];

    $submit_option = $_POST['submit_option'];
    
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

   //  管理者情報 更新
   if($submit_option=='1') {

       $query='update manager_info set manager_no ="'.$manager_no.'",password ="'.$password.'",manager_name ="'.$manager_name.'" where manager_id='.$manager_id;

    header ('location: ./managerlist.php');

   //  管理者情報 削除
   } else {

       $query='delete from manager_info where manager_id ='.$manager_id;

    header ('location: ./managerlist.php');

   }

   //Execute
   if ($result = mysqli_query($link, $query)) {

   } else {

     echo "Error: Update or Insert";

     exit;

   }

   mysqli_close($link);

?>
