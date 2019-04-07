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
    $e_id = $_POST['employer_id'];
    $e_name = $_POST['employer_name'];
    $date = date('YmdHi');

    $num = $_POST['total'];

  // 先ず回答内容が空かどうかをチェック

  // 未回答ありフラグ
  $emptyflg ="0";

  // 未回答の質問があるかどうかを判断するループ処理
  for ($i=1; $i<=$num; $i++) {

      $answer = $_POST["answer_".$i];

  // 回答内容をループで全て確認し、１つでもemptyなら$emptyflgを1に変える
    if (empty($answer)) {

      $emptyflg ="1";

    }

  }

  // 1に変われば未回答の内容があるという事
  if($emptyflg =="1") {

     $msg ="<p><font color =\"#ff0000\">未回答の質問があります。</font></p>
     <p><input class =\"btn\" type =\"button\" onclick=\"javascript:history.back()\" value =\"戻る\"></p>";

     } else {

     // 質問ID・回答内容をループ処理してinsertする
     for ($i=1; $i<=$num; $i++) {

       $qid = $_POST["q_".$i];
       $title = $_POST["title_".$i];
       $a_type = $_POST["a_type".$i];
       $answer = $_POST["answer_".$i];
       $correct = $_POST["correct_".$i];
       $score = $_POST["score_".$i];

       $query ='insert into question_answer(employer_id,employer_name,q_id,title,answer_type,answer,answer_date,correct,score) values('.$e_id.',"'.$e_name.'",'.$qid.',"'.$title.'",'.$a_type.',"'.$answer.'","'.$date.'","'.$correct.'",'.$score.')';

       // クエリ実行
       $result = mysqli_query($link, $query);

       $msg ="<p>ご回答いただきありがとうございました。</p>
       <p>採点が終わりましたら<br />結果を確認することができます。</p>
       <p>また、結果が出た後は再度チャレンジできますので、<br />満点を目指して頑張りましょう！</p>

       <p><input class =\"btn\"type = \"button\" value =\"メニュー画面へ\" onclick =\"location.href ='./menu.php'\"></p>";

       // この場合、クエリ解放は必要ない。
       //mysqli_free_result($result);

     }

  }

  // DB切断
  mysqli_close($link);


    // ファイル内容を変数に取り込む
    $fp=fopen('./thanks.html','r');

    // ファイルの最後まで処理を行う
    while(!feof($fp)) {

       // 1行ずつファイルを読み込み変数にセット
       $line=fgets($fp);

       // データベースからセットする項目について置き換え（動的部分）

       // ログイン名
       $line1=str_replace("<###LOGINNAME###>",$login_name,$line);

       // メッセージ
       $line2=str_replace("<###MSG###>",$msg,$line1);

       $lines=$line2;

       echo $lines;
    }

    fclose($fp);

    exit();
