<?php

  require_once($_SERVER['DOCUMENT_ROOT'] .'/m_system/errorlist.php');

  if(isset($_GET['em'])) {

      $error_no=$_GET['em'];

  } else {

      $error_no =0;

  }

  // ファイル内容を変数に取り込む
  $fp=fopen('./index.html','r');

  // ファイルの最後まで処理を行う
  while(!feof($fp)) {

     // 1行ずつファイルを読み込み変数にセット
     $line=fgets($fp);

     // 置き換え
     $lines=str_replace("<###ERROR###>",$error_msg[$error_no],$line);

     echo $lines;

  }

  fclose($fp);

?>
