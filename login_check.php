<?php

   // 設定ファイルの読み込み

   require_once('./database_config.php');

   //Post Parameter

   $id=$_POST['login_id'];

   $password=$_POST['password'];

   //Database connect

   $link=mysqli_connect(DB_SERVER,DB_ACCOUNT_ID,DB_ACCOUNT_PW,DB_NAME);

   if (!$link) {

      echo "Error: Unable to connect to MySQL." . PHP_EOL;

      echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;

      echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;

      exit;

   }

   mysqli_set_charset($link,"utf8");

   //Query

   $query='select login_id,employer_name,manager_flg from employer_master where login_id="'.$id.'" and password="'.$password.'"';

   //Execute

   if ($result = mysqli_query($link, $query)) {

        // if no one matched, move login

        if(mysqli_num_rows($result)!=1) {

           move_login();

        }

        foreach ($result as $row) {

          $login_id = $row['login_id'];

          $login_name = $row['employer_name'];

          $m_flg = $row['manager_flg'];

        }

        /* 結果セットを開放します */

        mysqli_free_result($result);

   } else {

       // if error, move login
       move_login();

   }

   // keep login information between screens    

   session_save_path('/home/t_katsumata/session/');

   session_start();

   // inititalize session

   $_SESSION = array();

   $_SESSION['login_id'] = $login_id;

   $_SESSION['shain_mei'] = $login_name;

   mysqli_close($link);


   if($m_flg == 0) {

   header('location: ./employee/menu.php');

   } else {

   header('location: ./manager/menu.php');

   }

   exit;

   function move_login() {

       header('location: ./index.php?em=1');

       exit;

   }

?>
