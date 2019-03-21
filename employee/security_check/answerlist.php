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
// 回答内容抽出
$query='select answer_date,a_correct from question_answer where employer_name ="'.$login_name.'" group by answer_date desc';

// 後ほどhtmlファイルで置き換えするための変数の初期化
$answer_list="";

//実行
if ($result = mysqli_query($link, $query)) {

  // 回答データがなければ別ページへ遷移
  if (mysqli_num_rows($result) < 1) {

     header('location: ./non_answer.php');

     exit;

     }

    $i=0;

while ($row = mysqli_fetch_row($result)) {

    $a_date[$i] = $row[0];
    $a_correct[$i] = $row[1];

    $date = new DateTime($a_date[$i]);

 if($a_correct[$i] !="") {

    $answer_list.="<tr><td>".$date->format('Y/m/d H:i')."</td><td><a href=\"./answerdetail.php?date=".$a_date[$i]."\"><font color =\"#ff0000\">詳細画面へ<font></a></td></tr>\n";

    }else{

    $answer_list.="<tr><td>".$date->format('Y/m/d H:i')."</td><td>採点中</td></tr>\n";

    }

   $i++;

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
