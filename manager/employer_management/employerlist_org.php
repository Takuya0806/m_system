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
// 社員情報一覧抽出

$query='select shain_info.shain_id,shain_info.shain_mei,shozokubu_info.shain_shozokubu_mei from shain_info,shozokubu_info where shain_info.shain_shozokubu_id = shozokubu_info.shain_shozokubu_id and shain_info.del_flag =0 order by shain_info.shain_id';

// 後ほどhtmlファイルで置き換えするための変数の初期化
$employer_list="";

   //実行
   if ($result = mysqli_query($link, $query)) {

    $i=0;

while ($row = mysqli_fetch_row($result)) {

    $shain_id[$i] = $row[0];
    $shain_mei[$i] = $row[1];
    $shozokubu[$i] = $row[2];

    $employer_list.="<tr><td>".$shain_id[$i]."</td><td>".$shain_mei[$i]."</td><td>".$shozokubu[$i]."</td><td><font color =\"#ffff00\"><a href='./employerdetail.php?ei=".$shain_id[$i]."'>詳細画面へ</font></a></td></tr>\n";

   $i++;

  }

// 結果セットを開放します
mysqli_free_result($result);

}


mysqli_close($link);

// ファイル内容を変数に取り込む
$fp=fopen('./employerlist.html','r');

// ファイルの最後まで処理を行う
while(!feof($fp)) {
// 1行ずつファイルを読み込み変数にセット
$line=fgets($fp);

// データベースからセットする項目について置き換え（動的部分）

// ログイン名
$line1=str_replace("<###LOGINNAME###>",$login_name,$line);

// 回答一覧
$line2=str_replace("<###EMPLOYERLIST###>",$employer_list,$line1);

//代入
$lines=$line2;

//  1行ずつ出力
echo $lines;

}

fclose($fp);

exit();

?>
