<?php

// 出力情報の設定
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=download.csv");
header("Content-Transfer-Encoding: binary");
 

// 設定ファイルの読み込み
require_once('./database_config.php');


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

// 回答内容抽出
$query='select shain_mei,q_id,answer_date,a_correct from question_answer group by answer_date order by a_id;';

// 後ほどhtmlファイルで置き換えするための変数の初期化
$answer_line="";

//実行
if ($result = mysqli_query($link, $query)) {

    $i=0;

while ($row = mysqli_fetch_row($result)) {

    $shain_mei[$i] = $row[0];
    $qid[$i] = $row[1];
    $date[$i] = $row[2];
    $a_correct[$i] = $row[3];

     if ($a_correct[$i] ==0) {

         $answer_line.="".$date[$i].",".$shain_mei[$i].",".$qid[$i].",○ \n";

       }else{

         $answer_line.="".$date[$i].",".$shain_mei[$i].",".$qid[$i].",× \n";

     }

   $i++;

  }

// 結果セットを開放します
mysqli_free_result($result);

}

mysqli_close($link);

// ファイル内容を変数に取り込む
$fp=fopen('./answer.csv','w');

// ファイルの最後まで処理を行う
while(!feof($fp)) {

// 1行ずつファイルを読み込み変数にセット
$line=fgets($fp);

// データベースからセットする項目について置き換え（動的部分）

// ログイン名
$line1=$answer_line;

//代入
$lines=$line1;

//  1行ずつ出力
echo $lines;

}

fclose($fp);


exit();

?>
