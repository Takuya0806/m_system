<?php

    // 設定ファイルの読み込み

    require_once('/home/t_katsumata/public_html/akarie/database_config.php');

    session_save_path('/home/t_katsumata/session/');

    //  セッション処理
    session_start();
    $login_name=$_SESSION['shain_mei'];
    $login_id=$_SESSION['login_id'];

    // POST PARAMETER
    $id = $_POST['id'];
    $password = $_POST['password'];
    $employer_name = $_POST['employer_name'];
    $employer_name_kana = $_POST['employer_name_kana'];
    $m_flg = $_POST['m_flg'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $join_date = $_POST['join_date'];
    $leave_date = $_POST['leave_date'];
    $address = $_POST['address'];
    $note = $_POST['note'];

    // $_POST['btn_back'];
    // $_POST['btn_add'];

   //Database connect
   $link=mysqli_connect(DB_SERVER,DB_ACCOUNT_ID,DB_ACCOUNT_PW,DB_NAME);

   if (!$link) {

      echo "Error: Unable to connect to MySQL." . PHP_EOL;

      echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;

      echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;

      exit;

   }

   mysqli_set_charset($link,"utf8");

   //Execute

   if ($_POST['id'] && $_POST['password'] && $_POST['employer_name'] !="") {

	$query='insert into employer_master(login_id,password,employer_name,employer_name_kana,department_id,position_id,join_date,leave_date,address,note,manager_flg) values("'.$id.'","'.$password.'","'.$employer_name.'","'.$employer_name_kana.'",'.$department.','.$position.',"'.$join_date.'","'.$leave_date.'","'.$address.'","'.$note.'",'.$m_flg.')';

   // クエリ実行
   $result = mysqli_query($link, $query);

	header ('location: ./employerlist.php');

	} else {

	header('location: ./employer_signup.php?em=2');

	}

        /* 結果セットを開放します */

       //  mysqli_free_result($result);


   mysqli_close($link);

   exit;

?>
