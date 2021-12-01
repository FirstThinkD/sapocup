<?php
session_start();
require('./common/functions.php');
require_once('./common/config.php');
// print_r($_SESSION);

if ($_SESSION['customer_id'] != "" && $_SESSION['connect_id'] != "" && $_SESSION['CONNECT_PS'] == CONNECT_PASSWORD) {
	$hash = hash_out(MERCHANT_ID, $_SESSION['customer_id'], HASH_KEY);
	$first_date = date('Ymd');

	// $url = 'https://sandbox.paygent.co.jp/v/u/cardreg';	// 試験用
	$url = 'https://mdev.paygent.co.jp/v/u/request';
	$data = array(
		'merchant_id'	   => MERCHANT_ID,	// マーチャントID
		'connect_id'       => $_SESSION['connect_id'],	//  接続ID
		'connect_password' => CONNECT_PASSWORD,	// 接続パスワード
		'telegram_kind'    => TELEGRAM_KIND,	// 電文種別ID
		'telegram_version' => TELEGRAM_VERSION,	// 電文バージョン番号
		'amount'           => AMOUNT,		// 決済金額
		'customer_id'      => $_SESSION['customer_id'],		// 顧客ID
		// 'customer_card_id' => "1000",	// 顧客カードID
		'cycle'            => CYCLE,		// 課金サイクル
		'timing'           => TIMING,		// 課金タイミング
		'first_executed'   => $first_date,	// 初回課金日
		'seq_merchant_id'  => SEG_MERCHANT_ID,	// マーチャントID
		'return_url'       => "https://sapocup.jp/complete-signup.php",
		'hc'               => $hash,
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
	// print_r($body);

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

	// $body = explode("\r\n", $body);
	// $url = substr($body[2], 4);
	header("Location:". $url);

	// header("Location:/complete-signup.php");
	exit();
} else {
	header("Location:/");
	exit();
}
?>