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
	if ($row0 = mysqli_fetch_assoc($record)) {
		if ($_GET['id'] == "service1") {
			$sql = sprintf('UPDATE `user` SET `service_exe`="%s", `service1_dt`=NOW()
				WHERE u_id="%d"',
				mysqli_real_escape_string($db, $_GET['id']),
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		} else if ($_GET['id'] == "service2") {
			$sql = sprintf('UPDATE `user` SET `service_exe`="%s", `service2_dt`=NOW()
				WHERE u_id="%d"',
				mysqli_real_escape_string($db, $_GET['id']),
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		} else if ($_GET['id'] == "service3") {
			$sql = sprintf('UPDATE `user` SET `service_exe`="%s", `service3_dt`=NOW()
				WHERE u_id="%d"',
				mysqli_real_escape_string($db, $_GET['id']),
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		} else {
			$sql = sprintf('UPDATE `user` SET `service_exe`="%s", `service4_dt`=NOW()
				WHERE u_id="%d"',
				mysqli_real_escape_string($db, $_GET['id']),
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		}
	} else {
		header("Location:/");
		exit();
	}

	if ($row0['u_type'] == "法人") {
		$connect_id = $row0['u_company'];
	} else {
		$connect_id = $row0['p_name'];
	}

	$hash = hash_out(MERCHANT_ID, $row0['u_id'], HASH_KEY);
	$first_date = date('Ymd');

	// $url = 'https://sandbox.paygent.co.jp/v/u/cardreg';
	$url = 'https://mdev.paygent.co.jp/v/u/request';
	$data = array(
		'merchant_id'	   => MERCHANT_ID,	// マーチャントID
		'connect_id'       => $connect_id,	//  接続ID
		'connect_password' => CONNECT_PASSWORD,	// 接続パスワード
		'telegram_kind'    => TELEGRAM_KIND,	// 電文種別ID
		'telegram_version' => TELEGRAM_VERSION,	// 電文バージョン番号
		'amount'           => "500",		// 決済金額
		'customer_id'      => $row0['u_id'],	// 顧客ID
		// 'customer_card_id' => "1000",	// 顧客カードID
		'cycle'            => CYCLE,		// 課金サイクル
		'timing'           => TIMING,		// 課金タイミング
		'first_executed'   => $first_date,	// 初回課金日
		'seq_merchant_id'  => SEG_MERCHANT_ID,	// マーチャントID
		'return_url'       => "https://sapocup.jp/manage/user/service-comp.php",
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
}
?>
