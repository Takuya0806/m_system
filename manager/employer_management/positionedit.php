<?php

    // 設定ファイルの読み込み
    require_once($_SERVER['DOCUMENT_ROOT'] .'/m_system/database_config.php');

    // GET PARAMETER
    $target_position_id=$_GET['pi'];

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
   // 所属部情報抽出
   $query ='select * from position_master where position_id ="'.$target_position_id.'"';

   // 後ほどhtmlファイルで置き換えするための変数の初期化
   $position_line="";

   //  実行
   if ($result = mysqli_query($link, $query)) {

        //  レコード数が１でなければエラー
        if(mysqli_num_rows($result) != 1) {
           echo "Error: Can't specify person";
           exit;
        }

        foreach ($result as $row) {

           $p_id = $row['position_id'];
           $p_name = $row['position_name'];

        }

        /* 結果セットを開放します */
        mysqli_free_result($result);
   }

    mysqli_close($link);


    // ファイル内容を変数に取り込む
    $fp=fopen('./positionedit.html','r');

    // ファイルの最後まで処理を行う
    while(!feof($fp)) {

       // 1行ずつファイルを読み込み変数にセット
       $line=fgets($fp);

       // データベースからセットする項目について置き換え（動的部分）
       // ログイン名
       $line1=str_replace("<###LOGINNAME###>",$login_name,$line);

       // 従業員詳細情報
       $line2=str_replace("<###POSITIONNAME###>",$p_name,$line1);

       // hiddenで所属部ID
       $line3=str_replace("<###POSITIONID###>",$target_position_id,$line2);

       // 代入
       $lines=$line3;

       //  1行ずつ出力
       echo $lines;
    }

    fclose($fp);

    exit();
