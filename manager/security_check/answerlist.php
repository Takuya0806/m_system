<?php

// 設定ファイルの読み込み
require_once('/home/t_katsumata/public_html/akarie/database_config.php');

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

// Query
// 回答内容一覧抽出

  $query='select employer_master.employer_name,question_answer.answer,question_answer.answer_date,question_answer.a_correct from employer_master left outer join question_answer on employer_master.employer_id = question_answer.employer_id group by question_answer.answer_date,employer_master.employer_id order by question_answer.answer_date desc';

// 後ほどhtmlファイルで置き換えするための変数の初期化
$answer_list="";

//実行
if ($result = mysqli_query($link, $query)) {

  foreach ($result as $row) {

    $e_name = $row['employer_name'];
    $answer = $row['answer'];
    $a_date = $row['answer_date'];
    $a_correct = $row['a_correct'];

    $date = new DateTime($a_date);

 if ($answer =="" && $a_correct =="") {

    $answer_list.="<tr><td>".$e_name."</td><td><font color =\"#ff0000\">未解答</font></td><td>未</td><td>未</td><td><font color =\"#848484\">採点画面へ</font></td></tr>\n";

      }elseif ($answer !=="" && $a_correct ==""){

    $answer_list.="<tr><td>".$e_name."</td><td>".$date->format('Y/m/d H:i')."</td><td><font color =\"#ff0000\">済</font></td><td>未</td><td><a href='./answer_score.php?date=".$a_date."'>採点画面へ</a></td></tr>\n";

      }else{

    $answer_list.="<tr><td>".$e_name."</td><td>".$date->format('Y/m/d H:i')."</td><td><font color =\"#ff0000\">済</font></td><td><font color =\"#ff0000\">済</font></td><td><a href='./answer_score.php?date=".$a_date."'>採点画面へ</a></td></tr>\n";

      }

  }

// 結果セットを開放します
mysqli_free_result($result);

}

mysqli_close($link);

// ファイル内容を変数に取り込む
$fp=fopen('./answerlist.html','r');

// ファイルの最後まで処理を行う
while(!feof($fp)) {
// 1行ずつファイルを読み込み変数にセット
$line=fgets($fp);

// データベースからセットする項目について置き換え（動的部分）

// ログイン名
$line1=str_replace("<###LOGINNAME###>",$login_name,$line);

// 回答一覧
$line2=str_replace("<###ANSWERLIST###>",$answer_list,$line1);

//代入
$lines=$line2;

//  1行ずつ出力
echo $lines;

}

fclose($fp);

exit();

?>
