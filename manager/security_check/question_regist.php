<?php

    // 設定ファイルの読み込み
    require_once('/home/t_katsumata/public_html/akarie/database_config.php');

    //  セッション処理
    session_save_path('/home/t_katsumata/session/');

    session_start();
    $login_name=$_SESSION['shain_mei'];
    $login_id=$_SESSION['login_id'];

    // POST PARAMETER
    $qid = $_POST['q_id'];
    $title = $_POST['title'];
    $a_type = $_POST['a_type'];
    $a_radio = $_POST['a_radio'];
    $body_1 = $_POST['body_1'];
    $body_2 = $_POST['body_2'];
    $body_3 = $_POST['body_3'];
    $body_4 = $_POST['body_4'];
    $body_5 = $_POST['body_5'];
    $body_6 = $_POST['body_6'];
    $body_7 = $_POST['body_7'];
    $body_8 = $_POST['body_8'];
    $body_9 = $_POST['body_9'];
    $body_10 = $_POST['body_10'];
    $score = $_POST['score'];

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

   // 質問内容 更新
   if ($submit_option =='1') {

   // 回答内容と正解値を同じにする（radioをテキストに変換）
   $correct ="";

       if ($a_radio =='1') {

      $correct = $body_1;

 } elseif ($a_radio =='2') {

      $correct = $body_2;

 } elseif ($a_radio =='3') {

      $correct = $body_3;

 } elseif ($a_radio =='4') {

      $correct = $body_4;

 } elseif ($a_radio =='5') {

      $correct = $body_5;

 } elseif ($a_radio =='6') {

      $correct = $body_6;

 } elseif ($a_radio =='7') {

      $correct = $body_7;

 } elseif ($a_radio =='8') {

      $correct = $body_8;

 } elseif ($a_radio =='9') {

      $correct = $body_9;

 } elseif ($a_radio =='10') {

      $correct = $body_10;

 }

      $query='update question_master set title ="'.$title.'",answer_type ='.$a_type.',body_1 ="'.$body_1.'",body_2 ="'.$body_2.'",body_3 ="'.$body_3.'",body_4 ="'.$body_4.'",body_5 ="'.$body_5.'",body_6 ="'.$body_6.'",body_7 ="'.$body_7.'",body_8 ="'.$body_8.'",body_9 ="'.$body_9.'",body_10 ="'.$body_10.'",correct ="'.$correct.'",score ='.$score.' where q_id ='.$qid;

    header ('location: ./questionlist.php');

   // 質問内容 削除（理論削除）
   } else {

      $query='update question_master set del_flag =1 where q_id ='.$qid;

    header ('location: ./questionlist.php');

   }

   //Execute
   if ($result = mysqli_query($link, $query)) {

   } else {

     echo "Error: Update or Insert";

     exit;

   }

   mysqli_close($link);

?>
