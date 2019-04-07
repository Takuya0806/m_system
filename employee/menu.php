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

$query='select * from employer_master where employer_name ="'.$login_name.'"';

   if ($result = mysqli_query($link, $query)) {

        //  レコード数が１でなければエラー
        if(mysqli_num_rows($result) != 1) {
           echo "Error: Can't specify person";
           exit;
        }

        foreach ($result as $row) {

           $e_id = $row['employer_id'];

        }

   /* 結果セットを開放します */
   mysqli_free_result($result);

   }


mysqli_close($link);


// ファイル内容を変数に取り込む
$fp=fopen('./menu.html','r');

// ファイルの最後まで処理を行う
while(!feof($fp)) {

// 1行ずつファイルを読み込み変数にセット
$line=fgets($fp);

// データベースからセットする項目について置き換え（動的部分）

// ログインID
$line1=str_replace("<###LOGINNAME###>",$login_name,$line);

// ログイン名
$line2=str_replace("<###EMPLOYERID###>",$e_id,$line1);

// 代入
$lines=$line2;

//  1行ずつ出力
echo $lines;

}

fclose($fp);

exit();

?>
