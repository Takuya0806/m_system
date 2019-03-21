<?php

   // 設定ファイルの読み込み
    require_once('/home/t_katsumata/public_html/akarie/database_config.php');

    // GET PARAMETER
    $target_q_id=$_GET['qi'];

    session_save_path('/home/t_katsumata/session/');

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

   // Query
   // 質問内容詳細抽出
$query='select title,answer_type,body_1,body_2,body_3,body_4,body_5,body_6,body_7,body_8,body_9,body_10,correct,score from question_master where del_flag = 0 and q_id = "'.$target_q_id.'"';

   // 後ほどhtmlファイルで置き換えするための変数の初期化
   $qiestion_line="";

   //  実行
   if ($result = mysqli_query($link, $query)) {

        //  レコード数が１でなければエラー
        if(mysqli_num_rows($result) != 1) {
           echo "Error: Can't specify person";
           exit;
        }

        $i=0;

        while ($row = mysqli_fetch_assoc($result)) {

           $title = $row['title'];
           $a_type= $row['answer_type'];
           $body_1 = $row['body_1'];
           $body_2 = $row['body_2'];
           $body_3 = $row['body_3'];
           $body_4 = $row['body_4'];
           $body_5 = $row['body_5'];
           $body_6 = $row['body_6'];
           $body_7 = $row['body_7'];
           $body_8 = $row['body_8'];
           $body_9 = $row['body_9'];
           $body_10 = $row['body_10'];
           $correct = $row['correct'];
           $score = $row['score'];

           $i++;

        }

 $line_type ="";

       if ($a_type == 0) {

    $line_type .= "<input type =\"radio\" name =\"a_type\" value =\"0\" checked />選択肢タイプ
                   <input type =\"radio\" name =\"a_type\" value =\"1\" />記述式";

           } else {

    $line_type .= "<input type =\"radio\" name =\"a_type\" value =\"0\" />選択肢タイプ
                   <input type =\"radio\" name =\"a_type\" value =\"1\" checked/>記述式";

           }

 $body_line1 ="";

       if ($correct == $body_1) {

    $body_line1 .="<input type =\"radio\" name =\"a_radio\" value =\"1\" checked /></td><td><input type =\"text\" name =\"body_1\" value =\"".$body_1."\">";

           } else {

    $body_line1 .="<input type =\"radio\" name =\"a_radio\" value =\"1\" /></td><td><input type =\"text\" name =\"body_1\" value =\"".$body_1."\">";

           }

 $body_line2 ="";

       if ($correct == $body_2) {

    $body_line2 .="<input type =\"radio\" name =\"a_radio\" value =\"2\" checked /></td><td><input type =\"text\" name =\"body_2\" value =\"".$body_2."\">";

           } else {

    $body_line2 .="<input type =\"radio\" name =\"a_radio\" value =\"2\" /></td><td><input type =\"text\" name =\"body_2\" value =\"".$body_2."\">";

           }

 $body_line3 ="";

       if ($correct == $body_3) {

    $body_line3 .="<input type =\"radio\" name =\"a_radio\" value =\"3\" checked /></td><td><input type =\"text\" name =\"body_3\" value =\"".$body_3."\">";

           } else {

    $body_line3 .="<input type =\"radio\" name =\"a_radio\" value =\"3\" /></td><td><input type =\"text\" name =\"body_3\" value =\"".$body_3."\">";

           }

 $body_line4 ="";

       if ($correct == $body_4) {

    $body_line4 .="<input type =\"radio\" name =\"a_radio\" value =\"4\" checked /></td><td><input type =\"text\" name =\"body_4\" value =\"".$body_4."\">";

           } else {

    $body_line4 .="<input type =\"radio\" name =\"a_radio\" value =\"4\" /></td><td><input type =\"text\" name =\"body_4\" value =\"".$body_4."\">";

           }

 $body_line5 ="";

       if ($correct == $body_5) {

    $body_line5 .="<input type =\"radio\" name =\"a_radio\" value =\"5\" checked /></td><td><input type =\"text\" name =\"body_5\" value =\"".$body_5."\">";

           } else {

    $body_line5 .="<input type =\"radio\" name =\"a_radio\" value =\"5\" /></td><td><input type =\"text\" name =\"body_5\" value =\"".$body_5."\">";

           }

 $body_line6 ="";

       if ($correct == $body_6) {

    $body_line6 .="<input type =\"radio\" name =\"a_radio\" value =\"6\" checked /></td><td><input type =\"text\" name =\"body_6\" value =\"".$body_6."\">";

           } else {

    $body_line6 .="<input type =\"radio\" name =\"a_radio\" value =\"6\" /></td><td><input type =\"text\" name =\"body_6\" value =\"".$body_6."\">";

           }

 $body_line7 ="";

       if ($correct == $body_7) {

    $body_line7 .="<input type =\"radio\" name =\"a_radio\" value =\"7\" checked /></td><td><input type =\"text\" name =\"body_7\" value =\"".$body_7."\">";

           } else {

    $body_line7 .="<input type =\"radio\" name =\"a_radio\" value =\"7\" /></td><td><input type =\"text\" name =\"body_7\" value =\"".$body_7."\">";

           }

 $body_line8 ="";

       if ($correct == $body_8) {

    $body_line8 .="<input type =\"radio\" name =\"a_radio\" value =\"8\" checked /></td><td><input type =\"text\" name =\"body_8\" value =\"".$body_8."\">";

           } else {

    $body_line8 .="<input type =\"radio\" name =\"a_radio\" value =\"8\" /></td><td><input type =\"text\" name =\"body_8\" value =\"".$body_8."\">";

           }

 $body_line9 ="";

       if ($correct == $body_9) {

    $body_line9 .="<input type =\"radio\" name =\"a_radio\" value =\"9\" checked /></td><td><input type =\"text\" name =\"body_9\" value =\"".$body_9."\">";

           } else {

    $body_line9 .="<input type =\"radio\" name =\"a_radio\" value =\"9\" /></td><td><input type =\"text\" name =\"body_9\" value =\"".$body_9."\">";

           }

 $body_line10 ="";

       if ($correct == $body_10) {

    $body_line10 .="<input type =\"radio\" name =\"a_radio\" value =\"10\" checked /></td><td><input type =\"text\" name =\"body_10\" value =\"".$body_10."\">";

           } else {

    $body_line10 .="<input type =\"radio\" name =\"a_radio\" value =\"10\" /></td><td><input type =\"text\" name =\"body_10\" value =\"".$body_10."\">";

           }


        /* 結果セットを開放します */
        mysqli_free_result($result);
   }


    mysqli_close($link);


    // ファイル内容を変数に取り込む
    $fp=fopen('./questionedit.html','r');

    // ファイルの最後まで処理を行う
    while(!feof($fp)) {

       // 1行ずつファイルを読み込み変数にセット
       $line=fgets($fp);

       // データベースからセットする項目について置き換え（動的部分）
       // ログイン名
       $line1=str_replace("<###LOGINNAME###>",$login_name,$line);

       // 質問内容詳細情報
       $line2=str_replace("<###QUESTIONTITLE###>",$title,$line1);
       $line3=str_replace("<###ANSWERTYPE###>",$line_type,$line2);
       $line4=str_replace("<###QUESTIONBODY1###>",$body_line1,$line3);
       $line5=str_replace("<###QUESTIONBODY2###>",$body_line2,$line4);
       $line6=str_replace("<###QUESTIONBODY3###>",$body_line3,$line5);
       $line7=str_replace("<###QUESTIONBODY4###>",$body_line4,$line6);
       $line8=str_replace("<###QUESTIONBODY5###>",$body_line5,$line7);
       $line9=str_replace("<###QUESTIONBODY6###>",$body_line6,$line8);
       $line10=str_replace("<###QUESTIONBODY7###>",$body_line7,$line9);
       $line11=str_replace("<###QUESTIONBODY8###>",$body_line8,$line10);
       $line12=str_replace("<###QUESTIONBODY9###>",$body_line9,$line11);
       $line13=str_replace("<###QUESTIONBODY10###>",$body_line10,$line12);
       $line14=str_replace("<###CORRECT###>",$correct,$line13);
       $line15=str_replace("<###SCORE###>",$score,$line14);
       $line16=str_replace("<###QUESTIONID###>",$target_q_id,$line15);

       // 代入
       $lines=$line16;

       //  1行ずつ出力
       echo $lines;
    }

    fclose($fp);

    exit();

?>
