<?php

    // 設定ファイルの読み込み
    require_once('./database_config.php');

    //  セッション処理
    session_save_path('/home/aoyama/session/');

    session_start();
    $login_name=$_SESSION['manager_name'];
    $login_id=$_SESSION['manager_no'];

    // POST PARAMETER
    $manager_no = $_POST['manager_no'];
    $password = $_POST['password'];
    $manager_name = $_POST['manager_name'];
    
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

   //  管理者情報 新規登録
   if($_POST['manager_no'] !="" && $_POST['password'] !="" && $_POST['manager_name'] !="") {

       $query='insert into manager_info(manager_no,password,manager_name) values("'.$manager_no.'","'.$password.'","'.$manager_name.'")';

   // クエリ実行
   $result = mysqli_query($link, $query);

    header ('location: ./managerlist.php');

   }else{

     echo "管理者番号・パスワード・管理者名が入力されていません。";

     exit;

   }

   mysqli_close($link);

?>
