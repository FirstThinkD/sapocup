<?php

// $url = 'https://mdev.paygent.co.jp/v/request';
// $url = 'https://mdev.paygent.co.jp/v/u/request';
// $url = 'https://sandbox.paygent.co.jp/v/u/cardreg';
$url = 'https://sandbox.paygent.co.jp/v/cardreg';	// 試験用
$data = array(
	'merchant_id'	   => "40011",		// <!-- マーチャントID -->
	'connect_id'       => "test40011",	// <!-- 接続ID -->
	'connect_password' => "4QBSjhRyNLAQv",	// <!-- 接続パスワード -->
	'telegram_kind'    => "280",		// <!-- 電文種別ID -->
	'telegram_version' => "1.0",		// <!-- 電文バージョン番号 -->
	'amount'           => "480",		// <!-- 決済金額 -->
	'customer_id'      => "9060372",	// <!-- 顧客ID -->
	'customer_card_id' => "1000",		// <!-- 顧客カードID -->
	'cycle'            => "1",		// <!-- 課金サイクル -->
	'timing'           => "31",		// <!-- 課金タイミング -->
	'first_executed'   => "20200401",	// <!-- 初回課金日 -->
	'seq_merchant_id'  => "40011",		// <!-- マーチャントID -->
	'return_url'       => "https://sapocup.jp/test/recv.php",	// <!-- 戻りURL -->
	'hc'               => "16673b8c25c7db01f4b0c57a51d784cbfbc1fe74a3567bd7bb24bcb5a6cd5d38",
	// btob mode ON
	'isbtob' => '1'

);

$data = http_build_query($data, "", "&");

$options = array(
	'http' => array(
		'method' => 'POST',
		'header' => 'Content-Type: application/x-www-form-urlencoded',
		'Content-Length: '.strlen($data),
		'content' => $data
	)
);

$context = stream_context_create($options);
$body = file_get_contents($url, false, $context);

// $file = "./debug.log";
// $data = $body;
// file_put_contents($file, $data, FILE_APPEND);

// echo "### body ###<br>";
// echo $body. "<br>";

$result = '';
$response_code = '';
$response_detail = '';
$url = '';
$trading_id = '';
$payment_type = '';
$limit_date = '';
$trade_generation_date = '';

// parse the response into each variables
// $body = preg_split("\r\n", $body);
// $body = explode ("\r\n", $body);
// echo "### body ###<br>";
// print_r($body);

// $url = substr($body[2], 4);
// process the variables here
// echo "### body ###<br>";
// echo $url;

$result = '';
$response_code = '';
$response_detail = '';
$url = '';
$trading_id = '';
$payment_type = '';
$limit_date = '';
$trade_generation_date = '';

// parse the response into each variables
$body = explode("\r\n", $body);
foreach ($body as $i => $line) {
           $item = explode("=", $line, 2);
           if (strlen($item[0]) > 0 && $item[0] == 'result') {
                      $result = $item[1];
           }
           if (strlen($item[0]) > 0 && $item[0] == 'response_code') {
                      $response_code = $item[1];
           }
           if (strlen($item[0]) > 0 && $item[0] == 'response_detail') {
                      $response_detail = $item[1];
           }
           if (strlen($item[0]) > 0 && $item[0] == 'url') {
                      $url = $item[1];
           }
           if (strlen($item[0]) > 0 && $item[0] == 'trading_id') {
                      $trading_id = $item[1];
           }
           if (strlen($item[0]) > 0 && $item[0] == 'payment_type') {
                      $payment_type = $item[1];
           }
           if (strlen($item[0]) > 0 && $item[0] == 'limit_date') {
                      $limit_date = $item[1];
           }
           if (strlen($item[0]) > 0 && $item[0] == 'trade_generation_date') {
                      $trade_generation_date = $item[1];
           }
}
echo $url;
// header("Location:". $url);
exit();
?>