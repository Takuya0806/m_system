<?php

   // 設定ファイルの読み込み
    require_once('./database_config.php');

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

   // 所属部データ抽出
   $line_department="";

   $query='select * from shozokubu_info order by shain_shozokubu_id';

   //実行
   if ($result = mysqli_query($link, $query)) {

        //  部署マスタにレコードが存在しない場合はエラー
        if(mysqli_num_rows($result) < 1) {

           echo "Error: 役職マスタにレコードが存在しません";

           exit;
        }

        $i=0;

        while ($row = mysqli_fetch_row($result)) {

           $shozokubu_id = $row[0];
           $shozokubu_mei = $row[1];

               $line_department .= "<option value='".$shozokubu_id."'>".$shozokubu_mei."</option>\n";

           $i++;

        }

        /* 結果セットを開放します */
        mysqli_free_result($result);
   }


    mysqli_close($link);


    // ファイル内容を変数に取り込む
    $fp=fopen('./employer_signup.html','r');

    // ファイルの最後まで処理を行う
    while(!feof($fp)) {

       // 1行ずつファイルを読み込み変数にセット
       $line=fgets($fp);

       // データベースからセットする項目について置き換え（動的部分）
       // ログイン名
       $line1=str_replace("<###LOGINNAME###>",$login_name,$line);

       $line2=str_replace("<###DEPARTMENT###>",$line_department,$line1);

       $lines=$line2;

       //  1行ずつ出力
       echo $lines;
    }

    fclose($fp);

    exit();

?>
