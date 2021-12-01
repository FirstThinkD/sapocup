<?php
  /*Create!Form：実行ファイルの設定*/
  $cfInstDir = "C:/Program Files (x86)/Infotec/CreateForm/11";
  /*作業ディレクトリ*/
  $cfworkdir  = "C:/ProgramData/Infotec/CreateForm/11/work/reference/barcode";
  /*スタイルファイル*/
  $stylefile  = "code39.sty";
  /*入力データファイル名*/
  $datafile   = $cfworkdir."/code39.csv";
  /*プリンター名*/
  $printer = "PrinterName";
 
  /*Create!Form PrintStage実行*/
  $execmd = "\"".$cfInstDir."/CPrintST.exe\" -D".$cfworkdir." -s".$stylefile." -#".$printer." ".$datafile;
  system($execmd, $ret);
 
  echo("Return Code:".$ret.PHP_EOL);
?>