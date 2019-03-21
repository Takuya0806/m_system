<?php

// 設定ファイルの読み込み
require_once('./database_config.php');

// セッション
session_save_path('/home/aoyama/session/');

session_start();
$login_name=$_SESSION['manager_name'];
$login_id=$_SESSION['manager_no'];

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

$query='select manager_id,manager_no,manager_name from manager_info order by manager_id';

// 後ほどhtmlファイルで置き換えするための変数の初期化
$manager_list="";

   //実行
   if ($result = mysqli_query($link, $query)) {

    $i=0;

while ($row = mysqli_fetch_row($result)) {

    $manager_id[$i] = $row[0];
    $manager_no[$i] = $row[1];
    $manager_name[$i] = $row[2];

    $manager_list.="<tr><td>".$manager_no[$i]."</td><td>".$manager_name[$i]."</td><td><font color =\"#ffff00\"><a href='./manageredit.php?mi=".$manager_id[$i]."'>編集画面へ</font></a></td></tr>\n";

   $i++;

  }

// 結果セットを開放します
mysqli_free_result($result);

}


mysqli_close($link);

// ファイル内容を変数に取り込む
$fp=fopen('./managerlist.html','r');

// ファイルの最後まで処理を行う
while(!feof($fp)) {
// 1行ずつファイルを読み込み変数にセット
$line=fgets($fp);

// データベースからセットする項目について置き換え（動的部分）

// ログイン名
$line1=str_replace("<###LOGINNAME###>",$login_name,$line);

// 回答一覧
$line2=str_replace("<###MANAGERLIST###>",$manager_list,$line1);

//代入
$lines=$line2;

//  1行ずつ出力
echo $lines;

}

fclose($fp);

exit();

?>

