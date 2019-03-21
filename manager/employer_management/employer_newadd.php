<?php

// 設定ファイルの読み込み
require_once('/home/t_katsumata/public_html/akarie/database_config.php');

// セッション
session_save_path('/home/t_katsumata/session/');

session_start();
$login_name=$_SESSION['shain_mei'];
$login_id=$_SESSION['login_id'];

    // POST PARAMETER
    $id = $_POST['id'];
    $password = $_POST['password'];
    $shain_mei = $_POST['shain_mei'];
    $m_flag = $_POST['m_flag'];
    $shozokubu = $_POST['shain_shozokubu'];
    $nyuushabi = $_POST['shain_nyuushabi'];
    $taishabi = $_POST['shain_taishabi'];
    $jusho = $_POST['shain_jusho'];
    $note = $_POST['note'];
    
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

   //  社員情報 新規登録
   if($_POST['id'] && $_POST['password'] !="" && $_POST['shain_mei'] !="") {

       $query='insert into shain_info(login_id,password,shain_mei,shain_shozokubu_id,shain_nyuushabi,shain_taishabi,shain_jusho,note,manager_flag) values("'.$id.'","'.$password.'","'.$shain_mei.'",'.$shozokubu.',"'.$nyuushabi.'","'.$taishabi.'","'.$jusho.'","'.$note.'",'.$m_flag.')';

   // クエリ実行
   $result = mysqli_query($link, $query);

    header ('location: ./employerlist.php');

   }else{

     echo "ログインID・パスワード・社員名が入力されていません。";

     echo "<br />";

     echo "<a href=\"./employer_signup.php\">戻る</a>";

     exit;

   }

 echo $query;

   mysqli_close($link);

?>
