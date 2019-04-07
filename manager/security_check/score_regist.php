<?php

    // 設定ファイルの読み込み
    require_once($_SERVER['DOCUMENT_ROOT'] .'/m_system/database_config.php');

    // セッション開始
    // session_save_path('/home/t_katsumata/session/');

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

  // POST
    $num = $_POST['total'];
    $date = $_POST['a_date'];

     // 回答内容をループ処理してinsertする
     for ($i=1; $i<=$num; $i++) {

       $qid = $_POST["q_".$i];
       $answer=$_POST["a_".$i];

       $query ='update question_answer set a_correct ='.$answer.' where q_id ='.$qid.' and answer_date ="'.$date.'"';

     $result = mysqli_query($link, $query);

     }


   mysqli_close($link);

   header('location: ./answerlist.php');

   exit();
