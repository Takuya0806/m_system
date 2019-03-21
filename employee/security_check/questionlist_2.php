<?php

   // 設定ファイルの読み込み

    require_once('./database_config.php');

    session_save_path('/home/aoyama/session/');

    session_start();

    $login_name=$_SESSION['shain_mei'];

    $login_id=$_SESSION['shain_bango'];

   // データベースに接続

   $link=mysqli_connect(DB_SERVER,DB_ACCOUNT_ID,DB_ACCOUNT_PW,DB_NAME);

   if (!$link) {

      echo "Error: Unable to connect to MySQL." . PHP_EOL;

      echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;

      echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;

      exit;

   }

  function getsql($result){
    $fld_value = array();
    $i=0;
    while ( $values = @mysqli_fetch_array($result) ) {
      $fld_value[$i] = $values;
      $i++;
    }
    return $fld_value;
  }

   // 抽出する際のエンコードを設定

   mysqli_set_charset($link,"utf8");

   //質問の表題タイトル群の取得
   $sql = "select * from question_master order by q_id";

   if ($result = mysqli_query($link,$sql) ) {
    $question_titles = getsql($result);

    $output1 = array();//表題用配列
    $output2 = array();//表題に対応した質問内容配列
    foreach($question_titles as $line) {
      $qid=$line['q_id'];
      $output1[]=array('title'=>$line['title'],'q_id'=>$qid);
      // 質問リストを抽出(質問内容)
      $sql2="select * from question_child where q_id='{$qid}' order by c_id";
      if($result2 = mysqli_query($link, $sql2)){
        $question_subs = getsql($result2);
        foreach($question_subs as $line2){
          $output2[$qid][]=array('body'=>$line2['body'],'answer_type'=>$line2['answer_type'],'c_id'=>$line2['c_id']);
        }
      }
    }

   }
   

    // ファイル内容を変数に取り込む
    //include 'questionlist.php';


mysqli_close($link);


?>


<!DOCTYPE html>
<head>
<meta charset ="UTF-8">
<title>質問ページ</title>
<link rel="stylesheet" href="employerlist.css" type="text/css">
</head>

<body>

<h3>質問ページ</h3>

<div class ="login-name">

<?php echo $login_name; ?>がログイン中

</div>

<input type = "button" value ="ログアウト" onclick ="location.href ='./login.php'">

<form method="post" action="sent.php">

<?php foreach($output1 as $val){ 
  $qid = $val['q_id'];
  $title = $val['title'];
  ?>
  <ul>
     <li><?php echo"設問".$qid." ー ".$title; ?></li>
  </ul>

    <ul>
      <?php foreach($output2[$qid] as $child){ 
        $body = $child['body'];
        $at = $child['answer_type'];
        $cid = $child['c_id'];
        ?>
     <li><?php echo $body; ?>
          <?php if($at  == 0 ){ ?>
            <input type="radio" name="answer[<?php echo $qid; ?>][]" value="<?php echo $cid; ?>">
          <?php  }elseif($at == 1 ){ ?>
            <input type="checkbox" name="answer[<?php echo $qid; ?>][]" value="<?php echo $cid; ?>">
          <?php }elseif($at == 2 ){ ?>
           <textarea name="answer[<?php echo $qid; ?>][]" rows="4" cols="40"></textarea>
          <?php  } ?>
      </li>
      <?php } ?>
    </ul>
   </li>
<?php } ?>
  </ul>

  <input type="hidden" name="shain_bango" value="<?php echo $login_id ?>">
  <input type="hidden" name="shain_mei" value="<?php echo $login_name ?>">
  <input type="hidden" name="q_id[]" value="<?php echo $qid ?>">

  <p align="center"><input type="submit" name="submit" value="回答する"></p>

</form>
</body>
</html>

