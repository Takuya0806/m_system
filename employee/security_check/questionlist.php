<?php

    // 設定ファイルの読み込み
    require_once($_SERVER['DOCUMENT_ROOT'] .'/m_system/database_config.php');

    // セッション
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

// Query
// 質問内容抽出
$query='select q_id,title,answer_type,body_1,body_2,body_3,body_4,body_5,body_6,body_7,body_8,body_9,body_10,correct,score from question_master where del_flg = 0 order by q_id';

// 後ほどhtmlファイルで置き換えするための変数の初期化
$question_line="";

//実行
if ($result = mysqli_query($link, $query)) {

    $i=0;

while ($row = mysqli_fetch_row($result)) {

   $i++;

   $qid[$i] = $row[0];
   $title[$i] = $row[1];
   $a_type[$i] = $row[2];
   $body_1[$i] = $row[3];
   $body_2[$i] = $row[4];
   $body_3[$i] = $row[5];
   $body_4[$i] = $row[6];
   $body_5[$i] = $row[7];
   $body_6[$i] = $row[8];
   $body_7[$i] = $row[9];
   $body_8[$i] = $row[10];
   $body_9[$i] = $row[11];
   $body_10[$i] = $row[12];
   $correct[$i] = $row[13];
   $score[$i] = $row[14];

   //質問内容セット(hiddenに質問ID・質問内容・正解番号・得点セット)
   $question_line.="<tr><td>・質問".$i."</td><td><font color =\"#0404B4\">".$title[$i]."</font></td></tr>
<tr><td><input type =\"hidden\" name =\"q_".$i."\" value =\"".$i."\">
<input type =\"hidden\" name =\"title_".$i."\" value =\"".$title[$i]."\">
<input type =\"hidden\" name =\"a_type".$i."\" value =\"".$a_type[$i]."\">
<input type =\"hidden\" name =\"correct_".$i."\" value =\"".$correct[$i]."\">
<input type =\"hidden\" name =\"score_".$i."\" value =\"".$score[$i]."\"></td></tr>\n";

if ($a_type[$i] == 0) {

  // ラジオボタンセット

  if ($body_1[$i] !="") {
   $question_line.="<tr><td></td><td><input type=\"radio\" name=\"answer_".$i."\" value=\"".$body_1[$i]."\" checked />".$body_1[$i]."</td></tr>\n";
  }

  if ($body_2[$i] !="") {
   $question_line.="<tr><td></td><td><input type=\"radio\" name=\"answer_".$i."\" value=\"".$body_2[$i]."\">".$body_2[$i]."</td></tr>\n";
  }

  if ($body_3[$i] !="") {
   $question_line.="<tr><td></td><td><input type=\"radio\" name=\"answer_".$i."\" value=\"".$body_3[$i]."\">".$body_3[$i]."</td></tr>\n";
  }

  if ($body_4[$i] !="") {
   $question_line.="<tr><td></td><td><input type=\"radio\" name=\"answer_".$i."\" value=\"".$body_4[$i]."\">".$body_4[$i]."</td></tr>\n";
  }

  if ($body_5[$i] !="") {
   $question_line.="<tr><td></td><td><input type=\"radio\" name=\"answer_".$i."\" value=\"".$body_5[$i]."\">".$body_5[$i]."</td></tr>\n";
  }

  if ($body_6[$i] !="") {
   $question_line.="<tr><td></td><td><input type=\"radio\" name=\"answer_".$i."\" value=\"".$body_6[$i]."\">".$body_6[$i]."</td></tr>\n";
  }

  if ($body_7[$i] !="") {
   $question_line.="<tr><td></td><td><input type=\"radio\" name=\"answer_".$i."\" value=\"".$body_7[$i]."\">".$body_7[$i]."</td></tr>\n";
  }

  if ($body_8[$i] !="") {
   $question_line.="<tr><td></td><td><input type=\"radio\" name=\"answer_".$i."\" value=\"".$body_8[$i]."\">".$body_8[$i]."</td></tr>\n";
  }

  if ($body_9[$i] !="") {
   $question_line.="<tr><td></td><td><input type=\"radio\" name=\"answer_".$i."\" value=\"".$body_9[$i]."\">".$body_9[$i]."</td></tr>\n";
  }

  if ($body_10[$i] !="") {
   $question_line.="<tr><td></td><td><input type=\"radio\" name=\"answer_".$i."\" value=\"".$body_10[$i]."\">".$body_10[$i]."</td></tr>\n";
  }

  } elseif ($a_type[$i] == 1) {

   // テキストエリアセット（hiddenに回答タイプセット）
   $question_line.="<tr><td></td><td><textarea name=\"answer_".$i."\" rows=\"5\" cols=\"40\" placeholder=\"500文字以内\"></textarea></td></tr>\n";

     }

  }

   // 質問総数代入
   $question_total = $i;

// 結果セットを開放します
mysqli_free_result($result);

}

// 社員ID内容抽出
$query='select employer_id from employer_master where employer_name ="'.$login_name.'"';

   if ($result = mysqli_query($link, $query)) {

   foreach ($result as $row) {

	$e_id = $row['employer_id'];

   }

   mysqli_free_result($result);

 }


mysqli_close($link);

// ファイル内容を変数に取り込む
$fp=fopen('./questionlist.html','r');

// ファイルの最後まで処理を行う
while(!feof($fp)) {
// 1行ずつファイルを読み込み変数にセット
$line=fgets($fp);

// データベースからセットする項目について置き換え（動的部分）

// ログイン名
$line1=str_replace("<###LOGINNAME###>",$login_name,$line);

// 社員ID
$line2=str_replace("<###EMPLOYERID###>",$e_id,$line1);

// 質問リスト
$line3=str_replace("<###QUESTIONLIST###>",$question_line,$line2);

// 質問総数
$line4=str_replace("<###QUESTIONTOTAL###>",$question_total,$line3);

//代入
$lines=$line4;

// 1行ずつ出力
echo $lines;

}

fclose($fp);

exit();
