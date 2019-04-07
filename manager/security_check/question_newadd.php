<?php

    // 設定ファイルの読み込み
    require_once($_SERVER['DOCUMENT_ROOT'] .'/m_system/database_config.php');

    //  セッション処理
    // session_save_path('/home/t_katsumata/session/');

    session_start();
    $login_name=$_SESSION['shain_mei'];
    $login_id=$_SESSION['login_id'];

    // POST PARAMETER
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

   if($_POST['title'] !="") {

   // 回答内容と正解値を同じにする（a_radioをcorrectに変換）
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

       $query='insert into question_master(title,answer_type,body_1,body_2,body_3,body_4,body_5,body_6,body_7,body_8,body_9,body_10,correct,score) values("'.$title.'",'.$a_type.',"'.$body_1.'","'.$body_2.'","'.$body_3.'","'.$body_4.'","'.$body_5.'","'.$body_6.'","'.$body_7.'","'.$body_8.'","'.$body_9.'","'.$body_10.'","'.$correct.'",'.$score.')';

   // クエリ実行
   $result = mysqli_query($link, $query);

    header ('location: ./questionlist.php');

   } else {

     echo "質問内容が入力されていません。";

     exit;

   }

   mysqli_close($link);

   exit();
