<?php

    // 設定ファイルの読み込み
    require_once($_SERVER['DOCUMENT_ROOT'] .'/m_system/database_config.php');

    // セッション処理
    // session_save_path('/home/t_katsumata/session/');

    session_start();
    $login_name=$_SESSION['shain_mei'];
    $login_id=$_SESSION['login_id'];

    // POST PARAMETER
    $employer_id = $_POST['employer_id'];

    $employer_name = $_POST['employer_name'];
    $employer_name_kana = $_POST['employer_name_kana'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $join_date = $_POST['join'];
    $leave_date = $_POST['leave'];
    $address = $_POST['address'];
    $m_flg = $_POST['m_flg'];
    $note = $_POST['note'];

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

   //  社員情報 更新
   if($submit_option==='1') {

       $query='update employer_master set employer_name ="'.$employer_name.'",employer_name_kana ="'.$employer_name_kana.'",department_id ='.$department.',position_id ='.$position.',join_date ="'.$join_date.'",leave_date ="'.$leave_date.'",address ="'.$address.'",manager_flg ='.$m_flg.',note ="'.$note.'" where employer_id='.$employer_id;

    header ('location: ./employerlist.php');

   //  社員情報 削除(理論削除)
   } else {

       $query='update employer_master set del_flg =1 where employer_id ='.$employer_id;

    header ('location: ./employerlist.php');

   }

   //Execute
   if ($result = mysqli_query($link, $query)) {

   } else {

     echo "Error: Update or Insert";

     exit;

   }

   mysqli_close($link);
