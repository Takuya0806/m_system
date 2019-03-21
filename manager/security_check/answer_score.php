<?php

// 設定ファイルの読み込み
require_once('/home/t_katsumata/public_html/akarie/database_config.php');

// GET PARAMETER
$target_answer_date=$_GET['date'];

// セッション
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

// 回答日
$date = new DateTime($target_answer_date);

// 社員ID・社員名
$query='select * from question_answer where answer_date ="'.$target_answer_date.'" group by employer_id';

$employer_line="";
   //  実行
   if ($result = mysqli_query($link, $query)) {

        //  レコード数が１でなければエラー
        if(mysqli_num_rows($result) != 1) {

           echo "Error: Can't specify person";

           exit;
        }

        foreach ($result as $row) {

           $e_id = $row['employer_id'];
           $e_name = $row['employer_name'];

        }

        /* 結果セットを開放します */
        mysqli_free_result($result);

   }

// Query
// 回答結果抽出
$query='select q_id,title,answer_type,answer,correct,a_correct,score from question_answer where answer_date  ="'.$target_answer_date.'" order by a_id';

// 後ほどhtmlファイルで置き換えするための変数の初期化
$answer_line="";

//実行
if ($result = mysqli_query($link, $query)) {

    $i=0;

while ($row = mysqli_fetch_row($result)) {

    $i++;

    $qid[$i] = $row[0];
    $title[$i] = $row[1];
    $a_type[$i] = $row[2];
    $answer[$i] = $row[3];
    $correct[$i] = $row[4];
    $a_correct[$i] = $row[5];
    $score[$i] = $row[6];

    if ($a_type[$i] ==1) {

       if ($a_correct[$i] =="") {

           $answer_line.="<tr><td><font color =\"#0404B4\">問 ".$qid[$i]."</font></td><td><font color =\"#0404B4\">".$title[$i]."</font></td><td></td></tr>
<tr><td><font color =\"#ff0000\">答</font></td><td>".$answer[$i]."</td><td><select name =\"a_".$i."\"><option value=\"0\"><font color =\"#ff0000\">○ </font></option> <option value=\"1\">× </option></select></td></tr><input type =\"hidden\" name =\"q_".$qid[$i]."\" value =\"".$qid[$i]."\">\n";

        } elseif ($a_correct[$i] ==0) {

           $answer_line.="<tr><td><font color =\"#0404B4\">問 ".$qid[$i]."</font></td><td><font color =\"#0404B4\">".$title[$i]."</font></td><td></td></tr>
<tr><td><font color =\"#ff0000\">答</font></td><td>".$answer[$i]."</td><td><select name =\"a_".$i."\"><option value=\"0\" selected><font color =\"#ff0000\">○ </font></option> <option value=\"1\">× </option></select></td></tr><input type =\"hidden\" name =\"q_".$qid[$i]."\" value =\"".$qid[$i]."\">\n";

        } elseif ($a_correct[$i] ==1) { 

           $answer_line.="<tr><td><font color =\"#0404B4\">問 ".$qid[$i]."</font></td><td><font color =\"#0404B4\">".$title[$i]."</font></td><td></td></tr>
<tr><td><font color =\"#ff0000\">答</font></td><td>".$answer[$i]."</td><td><select name =\"a_".$i."\"><option value=\"1\" selected>× </option> <option value=\"0\"><font color =\"#ff0000\">○ </font></option></td></tr><input type =\"hidden\" name =\"q_".$qid[$i]."\" value =\"".$qid[$i]."\">\n";

        }

    } elseif ($a_type[$i] ==0) {

       if ($a_correct[$i] =="") {

          if ($correct[$i] == $answer[$i]) {

           $answer_line.="<tr><td><font color =\"#0404B4\">問 ".$qid[$i]."</font></td><td><font color =\"#0404B4\">".$title[$i]."</font></td><td></td></tr>
<tr><td><font color =\"#ff0000\">答</font></td><td>".$answer[$i]."</td><td><input type =\"hidden\" name =\"a_".$i."\" value =\"0\"><font color =\"#ff0000\">○ </font></td></tr><input type =\"hidden\" name =\"q_".$qid[$i]."\" value =\"".$qid[$i]."\">\n";

          } else {

           $answer_line.="<tr><td><font color =\"#0404B4\">問 ".$qid[$i]."</font></td><td><font color =\"#0404B4\">".$title[$i]."</font></td><td></td></tr>
<tr><td><font color =\"#ff0000\">答</font></td><td>".$answer[$i]."</td><td><input type =\"hidden\" name =\"a_".$i."\" value =\"1\">× </td></tr><input type =\"hidden\" name =\"q_".$qid[$i]."\" value =\"".$qid[$i]."\">\n";

          }

       } elseif ($a_correct[$i] ==0) {

           $answer_line.="<tr><td><font color =\"#0404B4\">問 ".$qid[$i]."</font></td><td><font color =\"#0404B4\">".$title[$i]."</font></td><td></td></tr>
<tr><td><font color =\"#ff0000\">答</font></td><td>".$answer[$i]."</td><td><input type =\"hidden\" name =\"a_".$i."\" value =\"".$a_correct[$i]."\"><font color =\"#ff0000\">○ </font></td></tr><input type =\"hidden\" name =\"q_".$qid[$i]."\" value =\"".$qid[$i]."\">\n";

       } elseif ($a_correct[$i] ==1) {

           $answer_line.="<tr><td><font color =\"#0404B4\">問 ".$qid[$i]."</font></td><td><font color =\"#0404B4\">".$title[$i]."</font></td><td></td></tr>
<tr><td><font color =\"#ff0000\">答</font></td><td>".$answer[$i]."</td><td><input type =\"hidden\" name =\"a_".$i."\" value =\"".$a_correct[$i]."\">× </td></tr><input type =\"hidden\" name =\"q_".$qid[$i]."\" value =\"".$qid[$i]."\">\n";
     }

   }
   
}

// 結果セットを開放します
mysqli_free_result($result);

}

   // 質問総数代入
   $q_total = $i;

// 総合得点
$query='select sum(score) from question_answer where answer_date ="'.$target_answer_date.'"';

// 後ほどhtmlファイルで置き換えするための変数の初期化
$sum_line="";

   //Execute
   if ($result = mysqli_query($link, $query)) {

        $i=0;

        while ($row = mysqli_fetch_row($result)) {

           $a_sum[$i] = $row[0];
           $sum = $a_sum[$i];
           $i++;

        }

        /* 結果セットを開放します */
        mysqli_free_result($result);

   }

// 回答の得点抽出
$query='select sum(score) from question_answer where a_correct = 0 and answer_date ="'.$target_answer_date.'"';

// 後ほどhtmlファイルで置き換えするための変数の初期化
$score="";

//実行
if ($result = mysqli_query($link, $query)) {

    $i=0;

while ($row = mysqli_fetch_row($result)) {

    $a_score[$i] = $row[0];

    // 代入
    $score = $a_score[$i];

    $i++;

     }

// 結果セットを開放します
mysqli_free_result($result);

}

 // 正解率計算 & 代入
 $a_rate = $score / $sum * 100;


mysqli_close($link);

// ファイル内容を変数に取り込む
$fp=fopen('./answer_score.html','r');

// ファイルの最後まで処理を行う
while(!feof($fp)) {

// 1行ずつファイルを読み込み変数にセット
$line=fgets($fp);

// データベースからセットする項目について置き換え（動的部分）

// ログイン名
$line1=str_replace("<###LOGINNAME###>",$login_name,$line);

$line2=str_replace("<###ANSWERDATE###>",$date->format('Y/m/d H:i'),$line1);

$line3=str_replace("<###EMPLOYERNAME###>",$e_name,$line2);

$line4=str_replace("<###TOTALSUM###>",$sum,$line3);

$line5=str_replace("<###ANSWERRATE###>",round($a_rate),$line4);

$line6=str_replace("<###QUESTIONTOTAL###>",$q_total,$line5);

$line7=str_replace("<###DATE###>",$target_answer_date,$line6);

// 回答一覧
$line8=str_replace("<###QUESTIONANSWER###>",$answer_line,$line7);

//代入
$lines=$line8;

//  1行ずつ出力
echo $lines;

}

fclose($fp);

exit();

?>
