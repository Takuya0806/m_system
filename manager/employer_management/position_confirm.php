<?php

    // 設定ファイルの読み込み
    require_once($_SERVER['DOCUMENT_ROOT'] .'/m_system/database_config.php');

    session_start();
    $login_name=$_SESSION['shain_mei'];
    $login_id=$_SESSION['login_id'];

    // POST PARAMETER
    $p_name=$_POST['position_name'];

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

   if (!empty($_POST['position_name'])) {

		$query='insert into position_master(position_name) values("'.$p_name.'")';

		// クエリ実行
		$result = mysqli_query($link, $query);

			header ('location: ./positionlist.php');

	} else {

			header('location: ./position_signup.php?em=3');

	}

        /* 結果セットを開放します */

        //mysqli_free_result($result);


   mysqli_close($link);

   exit;
