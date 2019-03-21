<?php

   // 設定ファイルの読み込み
    require_once('/home/t_katsumata/public_html/akarie/database_config.php');

    require_once('/home/t_katsumata/public_html/akarie/errorlist.php');

    if(isset($_GET['em'])) {

       $error_no=$_GET['em'];

    } else {

       $error_no=0;

    }

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

   if (isset($_POST['id'])) {

	$id = $_POST['id'];

	} else {

	$id ="";

	}

   if (isset($_POST['password'])) {

	$password = $_POST['password'];

	} else {

	$password ="";

	}

   if (isset($_POST['employer_name'])) {

	$employer_name = $_POST['employer_name'];

	} else {

	$employer_name ="";

	}

   if (isset($_POST['employer_name_kana'])) {

	$employer_name_kana = $_POST['employer_name_kana'];

	} else {

	$employer_name_kana ="";

	}

   if (isset($_POST['m_flg'])) {

	$m_flg = $_POST['m_flg'];

	} else {

	$employer_name_kana ="";

	}

   if (isset($_POST['department'])) {

	$department = $_POST['department'];

	} else {

	$department ="";

	}

   if (isset($_POST['position'])) {

	$position = $_POST['position'];

	} else {

	$position ="";

	}

   if (isset($_POST['join_date'])) {

	$join_date = $_POST['join_date'];

	} else {

	$join_date ="";

	}

   if (isset($_POST['leave_date'])) {

	$leave_date = $_POST['leave_date'];

	} else {

	$leave_date ="";

	}

   if (isset($_POST['address'])) {

	$address = $_POST['address'];

	} else {

	$address ="";

	}

   if (isset($_POST['note'])) {

	$note = $_POST['note'];

	} else {

	$note ="";

	}

   // 管理者フラグ

   $line_manager ="";

   if ($m_flg =0) {

	$line_manager .="<input type =\"radio\" name =\"m_flg\" value =\"0\" checked />一般社員
      <input type =\"radio\" name =\"m_flg\" value =\"1\" />管理者";

	} else {

	$line_manager .="<input type =\"radio\" name =\"m_flg\" value =\"0\" />一般社員
      <input type =\"radio\" name =\"m_flg\" value =\"1\" checked />管理者";

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

	$line_department .= "<option value='".$row['department_id']."'>".$row['department_name']."</option>\n";

	}

        // 結果セットを開放します
        mysqli_free_result($result);
   }

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

	$line_position .= "<option value='".$row['position_id']."'>".$row['position_name']."</option>\n";

	}

        // 結果セットを開放します
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
       $line2=str_replace("<###LOGINID###>",$id,$line1);
       $line3=str_replace("<###PASSWORD###>",$password,$line2);
       $line4=str_replace("<###EMPLOYERNAME###>",$employer_name,$line3);
       $line5=str_replace("<###KANA###>",$employer_name_kana,$line4);
       $line6=str_replace("<###MANAGERFLAG###>",$line_manager,$line5);
       $line7=str_replace("<###DEPARTMENT###>",$line_department,$line6);
       $line8=str_replace("<###POSITION###>",$line_position,$line7);
       $line9=str_replace("<###JOINDATE###>",$join_date,$line8);
       $line10=str_replace("<###LEAVEDATE###>",$leave_date,$line9);
       $line11=str_replace("<###ADDRESS###>",$address,$line10);
       $line12=str_replace("<###NOTE###>",$note,$line11);
       $line13=str_replace("<###ERROR###>",$error_msg[$error_no],$line12);

       $lines=$line13;

       //  1行ずつ出力
       echo $lines;
    }

    fclose($fp);

    exit();

?>
