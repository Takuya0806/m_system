<?php

    // 設定ファイルの読み込み
    require_once($_SERVER['DOCUMENT_ROOT'] .'/m_system/database_config.php');

    // セッション
    // session_save_path('/home/t_katsumata/session/');

    session_start();

    $login_name = $_SESSION['shain_mei'];
    $login_id = $_SESSION['login_id'];

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

  /* 所属部フィルタ */
  if (isset($_POST['filter_department'])) {

      $_SESSION['filter_department'] = $_POST['filter_department'];

    }

  if (isset($_SESSION['filter_department'])) {

      $d_id = $_SESSION['filter_department'];

    } else {

      $d_id ="";

    }

  // 所属部データ抽出
  $line_department ="";

  $query='select * from department_master order by department_id';

  //実行
  if ($result = mysqli_query($link, $query)) {

        //  部署マスタにレコードが存在しない場合はエラー
        if(mysqli_num_rows($result) < 1) {

           echo "Error: 所属部マスタにレコードが存在しません";
           exit;
        }

      foreach ($result as $row) {

          $department_id = $row['department_id'];
          $department_name = $row['department_name'];

        if($department_id == $d_id) {

          $line_department .="<option value='".$department_id."' selected>".$department_name."</option>\n";

        } else {

          $line_department .="<option value='".$department_id."'>".$department_name."</option>\n";

        }

      }

        // 結果セットを開放します
        mysqli_free_result($result);
  }

  // 所属部クエリ
  $query1 ="";

  if (empty($d_id)) {

      $query1 ="and employer_master.department_id = department_master.department_id";

  } else {

      $query1 ="and employer_master.department_id =$d_id and department_master.department_id =$d_id";

  }


  /* 役職フィルタ */
  if (isset($_POST['filter_position'])) {

      $_SESSION['filter_position'] = $_POST['filter_position'];

  }

  if (isset($_SESSION['filter_position'])) {

      $p_id = $_SESSION['filter_position'];

    } else {

      $p_id ="";

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

        if($position_id == $p_id) {

          $line_position .="<option value='".$position_id."' selected>".$position_name."</option>\n";

        } else {

          $line_position .="<option value='".$position_id."'>".$position_name."</option>\n";

        }

      }

        // 結果セットを開放します
        mysqli_free_result($result);
  }

  $query2 ="";

  if (empty($p_id)) {

      $query2 ="and employer_master.position_id = position_master.position_id";

      } else {

      $query2 ="and employer_master.position_id =$p_id and position_master.position_id =$p_id";

      }

  /* ソート機能 */
  if (isset($_POST['sort_option'])) {

        $_SESSION['sort_option'] = $_POST['sort_option'];

  }

  if (isset($_SESSION['sort_option'])) {

        $sort = $_SESSION['sort_option'];

  } else {

        $sort ="";

  }

$sort_line ="";
$query3 ="";

    if ($sort ==='1') {

        $sort_line .="<option value=\"1\" selected />ID 昇順</option>
                      <option value=\"2\">ID 降順</option>
                      <option value=\"3\">かな 昇順</option>
                      <option value=\"4\">かな 降順</option>";

        $query3 ="order by employer_master.employer_id asc";

      } elseif ($sort ==='2') {

        $sort_line .="<option value=\"1\">ID 昇順</option>
                      <option value=\"2\" selected />ID 降順</option>
                      <option value=\"3\">かな 昇順</option>
                      <option value=\"4\">かな 降順</option>";

        $query3 ="order by employer_master.employer_id desc";

      } elseif ($sort ==='3') {

        $sort_line .="<option value=\"1\">ID 昇順</option>
                      <option value=\"2\">ID 降順</option>
                      <option value=\"3\" selected />かな 昇順</option>
                      <option value=\"4\">かな 降順</option>";

        $query3 ="order by employer_master.employer_name_kana asc";

      } elseif ($sort ==='4') {

        $sort_line .="<option value=\"1\">ID 昇順</option>
                      <option value=\"2\">ID 降順</option>
                      <option value=\"3\">かな 昇順</option>
                      <option value=\"4\" selected />かな 降順</option>";

        $query3 ="order by employer_master.employer_name_kana desc";

      } else {

        $sort_line .="<option value=\"1\">ID 昇順</option>
                      <option value=\"2\">ID 降順</option>
                      <option value=\"3\">かな 昇順</option>
                      <option value=\"4\">かな 降順</option>";

        $query3 ="order by employer_master.employer_id asc";


      }

  // GETの設定
  if (empty($_GET['page'])) {

        $_GET['page'] ="";

  }

  if (preg_match('/^[1-9][0-9]*$/',$_GET['page'])) {

        $page = (int)$_GET['page'];

    } else {

        $page = 1;

  }

// 何件表示させるか
$list_per_page =10;

  if (isset($_POST['list_per_page'])) {

      $_SESSION['list_per_page'] = $_POST['list_per_page'];

    }

  if (isset($_SESSION['list_per_page'])) {

      $list_per_page = $_SESSION['list_per_page'];

    }

// offset
$offset = $list_per_page * ($page -1);

// 社員情報一覧抽出
$query='select * from employer_master,department_master,position_master where employer_master.del_flg =0 '.$query1.' '.$query2.' '.$query3.' limit '.$offset.','.$list_per_page.'';

// クエリ確認
// echo $query;


// 後ほどhtmlファイルで置き換えするための変数の初期化
$employer_list="";

   //実行
   if ($result = mysqli_query($link, $query)) {

foreach ($result as $row) {

  if ($row['manager_flg'] ==='0') {

        $row['manager_flg'] ="一般社員";

  } else {

        $row['manager_flg'] ="<font color =\"red\">管理者</font>";

  }

    $employer_list.="<tr><td>".$row['employer_id']."</td><td>".$row['employer_name']."</td><td>".$row['department_name']."</td><td>".$row['position_name']."</td><td>".$row['manager_flg']."</td><td><font color =\"#ffff00\"><a href='./employerdetail.php?ei=".$row['employer_id']."'>詳細画面へ</font></a></td></tr>\n";

  }

// 結果セットを開放します
mysqli_free_result($result);

}

// トータルレコードの抽出
$query ='select count(*) from employer_master,department_master,position_master where employer_master.del_flg =0 '.$query1.' '.$query2.'';

  if ($result = mysqli_query($link,$query)) {

      /* 結果セットの行数 */
      $row = mysqli_fetch_array($result);
      $total = $row['count(*)'];

      // 結果セット開放
      mysqli_free_result($result);
  }

// トータルを件数で割ってceilで繰り上げ
$total_pages = ceil($total / $list_per_page);

// pervリンク作成
$page_prev ="";

  if ($page > 1) {

    $j = $page -1;

    $page_prev.="<a href ='./employerlist.php?page=".$j."'><< 前</a>&emsp;";

    } else {

    $page_prev.="<font color =\"#666666\"><< 前</font>&emsp;";

    }

// nextリンク作成
$page_next ="";

  if ($page < $total_pages) {

    $k = $page +1;

    $page_next.="<a href ='./employerlist.php?page=".$k."'>次 >></a>&emsp;";

    } else {

    $page_next.="<font color =\"#666666\">次 >></font>&emsp;";

    }

// ページリンク作成
$page_list ="";

  for ($i =1; $i <= $total_pages; $i++) {

    if ($page === $i) {

        $page_list.="<strong><a href='./employerlist.php?page=".$i."'>$i</a></strong>&emsp;";

    } else {

        $page_list.="<a href='./employerlist.php?page=".$i."'>$i</a>&emsp;";

    }

  }

// 件数表示
$from = $offset +1;
$to = ($offset + $list_per_page) < $total ?($offset + $list_per_page) : $total;

$number_line ="全 ".$total." 件中&emsp;".$from." 件 〜 ".$to." 件";


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

// ソート
$line2=str_replace("<###SORT###>",$sort_line,$line1);

// 所属部一覧
$line3=str_replace("<###DEPARTMENT###>",$line_department,$line2);

// 役職一覧
$line4=str_replace("<###POSITION###>",$line_position,$line3);

// 社員一覧
$line5=str_replace("<###EMPLOYERLIST###>",$employer_list,$line4);

// prevリンク
$line6=str_replace("<###PREVIOUS###>",$page_prev,$line5);

// nextリンク
$line7=str_replace("<###NEXT###>",$page_next,$line6);

// ページリンク
$line8=str_replace("<###PAGELIST###>",$page_list,$line7);

// 件数
$line9=str_replace("<###NUMBER###>",$number_line,$line8);

//代入
$lines=$line9;

//  1行ずつ出力
echo $lines;

}

fclose($fp);

exit();
