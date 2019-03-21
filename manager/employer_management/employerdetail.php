<?php

   // 設定ファイルの読み込み
    require_once('/home/t_katsumata/public_html/akarie/database_config.php');

    // GET PARAMETER
    $target_user_id=$_GET['ei'];

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
   // 従業員情報抽出（shain_info , shozokubu_info）
	$query='select * from employer_master where employer_id ="'.$target_user_id.'"';

   // 後ほどhtmlファイルで置き換えするための変数の初期化
   $employer_line="";

   //  実行
   if ($result = mysqli_query($link, $query)) {

        //  レコード数が１でなければエラー
        if(mysqli_num_rows($result) != 1) {
           echo "Error: Can't specify person";
           exit;
        }

        foreach ($result as $row) {

           $employer_id = $row['employer_id'];
           $employer_name = $row['employer_name'];
           $employer_name_kana = $row['employer_name_kana'];
           $em_position_id = $row['position_id'];
           $em_department_id = $row['department_id'];
           $join_date = $row['join_date'];
           $leave_date = $row['leave_date'];
           $address = $row['address'];
           $note = $row['note'];
           $m_flg = $row['manager_flg'];

        }

        /* 結果セットを開放します */
        mysqli_free_result($result);
   }

   // 管理者フラグ
   $manager_line="";

	if($m_flg ==='0') {

               $manager_line .="一般社員\n";

           } else {

               $manager_line .="<font color =\"#ff0000\">管理者</font>\n";

           }

   // 役職データ抽出
   $line_position="";

   $query='select * from position_master order by position_id';

   //実行
   if ($result = mysqli_query($link, $query)) {

        //  部署マスタにレコードが存在しない場合はエラー
        if(mysqli_num_rows($result) < 1) {

           echo "Error: 役職マスタにレコードが存在しません";

           exit;
        }

        foreach ($result as $row) {

           $position_id = $row['position_id'];
           $position_name = $row['position_name'];

           if($position_id === $em_position_id) {

               $line_position .="$position_name\n";

           } else {

               $line_position .="\n";

           }

        }

        /* 結果セットを開放します */
        mysqli_free_result($result);

     }

   // 所属部データ抽出
   $line_department="";

   $query='select * from department_master order by department_id';

   //実行
   if ($result = mysqli_query($link, $query)) {

        //  部署マスタにレコードが存在しない場合はエラー
        if(mysqli_num_rows($result) < 1) {

           echo "Error: 役職マスタにレコードが存在しません";

           exit;
        }

        foreach ($result as $row) {

           $department_id = $row['department_id'];
           $department_name = $row['department_name'];

           if($department_id === $em_department_id) {

               $line_department .="$department_name\n";

           } else {

               $line_department .="\n";

           }

        }

        /* 結果セットを開放します */
        mysqli_free_result($result);

     }

   // 入社日日付設定
   $join_line ="";

   if ($join_date === "0000-00-00") {

	$join_line .="\n";

   } else {

	$join_line = date('Y/m/d',strtotime($join_date));

   }

   // 退社日日付設定
   $leave_line ="";

   if ($leave_date === "0000-00-00") {
        
	$leave_line .= "\n";   

   } else {

	$leave_line .= date('Y/m/d',strtotime($leave_date));

   }


    mysqli_close($link);


    // ファイル内容を変数に取り込む
    $fp=fopen('./employerdetail.html','r');

    // ファイルの最後まで処理を行う
    while(!feof($fp)) {

       // 1行ずつファイルを読み込み変数にセット
       $line=fgets($fp);

       // データベースからセットする項目について置き換え（動的部分）
       // ログイン名
       $line1=str_replace("<###LOGINNAME###>",$login_name,$line);

       // 従業員詳細情報
       $line2=str_replace("<###EMPLOYERID###>",$employer_id,$line1);
       $line3=str_replace("<###EMPLOYERNAME###>",$employer_name,$line2);
       $line4=str_replace("<###KANA###>",$employer_name_kana,$line3);
       $line5=str_replace("<###MANAGERFLAG###>",$manager_line,$line4);
       $line6=str_replace("<###POSITION###>",$line_position,$line5);
       $line7=str_replace("<###DEPARTMENT###>",$line_department,$line6);
       $line8=str_replace("<###JOINDATE###>",$join_line,$line7);
       $line9=str_replace("<###LEAVEDATE###>",$leave_line,$line8);
       $line10=str_replace("<###ADDRESS###>",$address,$line9);
       $line11=str_replace("<###NOTE###>",$note,$line10);

       // 代入
       $lines=$line11;

       //  1行ずつ出力
       echo $lines;
    }

    fclose($fp);

    exit();

?>
