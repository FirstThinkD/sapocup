<?php
session_start();
require_once('../../common/dbconnect.php');
require('../../common/functions.php');
require_once('../../common/config.php');

if ($_SESSION['loginID'] == "") {
	header("Location:/");
	exit();
}
if ($_GET['id'] == "") {
	header("Location:/");
	exit();
} else {
	$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $_SESSION['loginID'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row7 = mysqli_fetch_assoc($record)) {
		if ($_GET['id'] == "service1") {
			$running_id = $row7['service1_id'];
			$sql = sprintf('UPDATE `user` SET `service_exe`="%s" WHERE u_id="%d"',
				mysqli_real_escape_string($db, $_GET['id']),
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		} else if ($_GET['id'] == "service2") {
			$running_id = $row7['service2_id'];
			$sql = sprintf('UPDATE `user` SET `service_exe`="%s" WHERE u_id="%d"',
				mysqli_real_escape_string($db, $_GET['id']),
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		} else if ($_GET['id'] == "service3") {
			$running_id = $row7['service3_id'];
			$sql = sprintf('UPDATE `user` SET `service_exe`="%s" WHERE u_id="%d"',
				mysqli_real_escape_string($db, $_GET['id']),
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		} else {
			$running_id = $row7['service4_id'];
			$sql = sprintf('UPDATE `user` SET `service_exe`="%s" WHERE u_id="%d"',
				mysqli_real_escape_string($db, $_GET['id']),
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		}
	} else {
		header("Location:/");
		exit();
	}

	if ($row7['u_type'] == "法人") {
		$connect_id = $row7['u_company'];
	} else {
		$connect_id = $row7['p_name'];
	}
	$hash = hash_out(MERCHANT_ID, $row7['u_id'], HASH_KEY);
	// $url = 'https://sandbox.paygent.co.jp/v/u/cardreg';
	$url = 'https://mdev.paygent.co.jp/v/u/request';
	$data = array(
		'merchant_id'	   => MERCHANT_ID,	// 1 マーチャントID
		'connect_id'       => $connect_id,	// 2 接続ID
		'connect_password' => CONNECT_PASSWORD,	// 3 接続パスワード
		'telegram_kind'    => "283",		// 4 電文種別ID
		'telegram_version' => TELEGRAM_VERSION,	// 5 電文バージョン番号
		// 'customer_id'      => $row7['u_id'],	// 6 顧客ID
		// 'customer_card_id' => $running_id,	// 顧客カードID
		'running_id'       => $running_id,	// 6 継続課金ID
		// 'trading_id'       => MERCHANT_ID,	// 7 マーチャント取引ID
		'return_url'       => "https://sapocup.jp/manage/user/service-stop.php",
		'stop_return_url'  => "https://sapocup.jp/manage/user/service-stop.php",
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
	exit();
}
?>
