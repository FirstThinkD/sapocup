<?php
echo "### SESSION ###<br>";
print_r($_SESSION);
echo "### GET ###<br>";
print_r($_GET);
echo "### POST ###<br>";
print_r($_POST);
$logfile = "recv.log";
$logdata = date("Y-m-d H:i:s");
$logdata .= "  ". "trading_id=". $_POST['trading_id']. " id=". $_POST['id']. " seq_merchant_id=". $_POST['seq_merchant_id']. "\n";
file_put_contents($logfile, $logdata, FILE_APPEND);
exit();
?>
