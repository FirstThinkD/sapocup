<?php
session_start();
require_once(__DIR__. '/../common/config.php');
require_once(__DIR__. '/../common/dbconnect.php');

if ($_GET['ptn'] == "" || $_GET['id'] != "1" || $_SESSION['loginID'] == "") {
	header("Location:/");
	exit();
}

$sql = sprintf('SELECT * FROM `paygent` WHERE u_id="%d" AND ptn="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID']),
	mysqli_real_escape_string($db, $_GET['ptn'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row6 = mysqli_fetch_assoc($record);
// $pay_customer_id      = $row6['customer_id'];	//  8 顧客ID
// $pay_customer_card_id = $row6['customer_card_id'];	//  9 顧客カードID
// $pay_running_id       = $row6['running_id'];		//  6 継続課金ID
$fingerprint          = $row6['fingerprint'];		//    フィンガープリント

$sql = sprintf('SELECT * FROM `paygent` WHERE u_id="%d" AND ptn="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID']),
	mysqli_real_escape_string($db, $_GET['ptn'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
if ($row7 = mysqli_fetch_assoc($record)) {
	// データあり
	$pay_customer_id      = $row7['customer_id'];		//  8 顧客ID
	$pay_customer_card_id = $row7['customer_card_id'];	//  9 顧客カードID
	$pay_running_id       = $row7['running_id'];		//  6 継続課金ID
	// $fingerprint          = $row7['fingerprint'];		//    フィンガープリント
} else {
	header("Location:/manage/user/");
	exit();
}

// ① 接続モジュールの利用準備 {接続モジュールのインストールディレクトリ}/vendor/autoload.phpを指定します。
require(__DIR__. "/vendor/autoload.php");
use PaygentModule\System\PaygentB2BModule;

// ② 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
$p = new PaygentB2BModule();
$p->init();

// ③ ペイジェントへの要求をセット※電文に必要な要求情報を設定
//（要求に必要となるパラメータをハッシュにして設定 ）
$p->reqPut("merchant_id",       MERCHANT_ID);		//  1 マーチャントID
$p->reqPut("connect_id",        CONNECT_ID);		//  2 接続ID
$p->reqPut("connect_password",  CONNECT_PASSWORD);	//  3 接続パスワード
$p->reqPut("telegram_kind",     "116");			//  4 電文種別ID
$p->reqPut("telegram_version",  TELEGRAM_VERSION);		//  5 電文バージョン番号
// $p->reqPut("trading_id",        "40110100");		//  6 マーチャント取引ID
// $p->reqPut("payment_id",        "11111");		//  7 決済ID

$p->reqPut("customer_id",       $pay_customer_id);	//  8 顧客ID
$p->reqPut("customer_card_id",  $pay_customer_card_id);	//  9 顧客カードID
// $p->reqPut("card_number",       $_POST['masked_card_number']);	//  9 カード番号
// $p->reqPut("card_valid_term",   "3010");		// 10 カード有効期限
// $p->reqPut("card_conf_number",  "1111");		// 11 カード確認番号
// $p->reqPut("cardholder_name",   "TARO YAMADA");	// 13 カード名義人
// $p->reqPut("valid_check_flg",   "0");		// 19 有効性チェックフラグ
// $p->reqPut("card_token",        $_POST['token']);	// 20 カード情報トークン
$p->reqPut("fingerprint",        $fingerprint);		// 20 
// $p->reqPut("security_code_use", "0");		// 21 セキュリティコード利用

// 電文種別ID：200（ファイル決済要求）の場合は、送信ファイルパスをセット
// $p->setSendFilePath("");
$result = 0;

// ④ ペイジェントへ要求を送信
$result = $p->post();
$resultStatus = $p->getResultStatus(); // 処理結果 0=正常終了, 1=異常終了
// echo "resultStatus=". $resultStatus. "<br>";

$file = "./log/stop_response.log";
$data = date('Y/m/d H:i:s');
$data .= " customer_id=". $pay_customer_id. " running_id=". $pay_running_id;
$data .= " kind=116 resultStatus=". $resultStatus;
$data .= "\n";
file_put_contents($file, $data, FILE_APPEND);

// ⑤ 要求送信結果を確認
if ($resultStatus == 1) {
	// エラー処理
	// ⑥ 要求結果を取得
	$responseCode   = $p->getResponseCode();	// 異常終了時、レスポンスコードが取得できる
	$responseDetail = $p->getResponseDetail();	//異常終了時、レスポンス詳細が取得できる

	// エラーコード取得
	$errorcode = $result;
	$str = mb_convert_encoding($responseDetail, "UTF-8", "SJIS");

	$file = "./log/stop_result_err.log";
	$data = date('Y/m/d H:i:s');
	$data .= " kind=026 responseCode=". $responseCode;
	$data .= " responseDetail=". $str;
	$data .= "\n";
	file_put_contents($file, $data, FILE_APPEND);
} else {
	// 1件取得の場合
	// if($p->hasResNext()) {
	//	// # データが存在
	//	$res_array = $p->resNext();		// # 要求結果取得
	//	$payment_id = $res_array["payment_id"]; // # 決済ID取得
	//	// # 他、応答情報を取得
	//	echo "payment_id=". $payment_id. "<br>";
	//	print_r($res_array);
	// }
	// 複数件取得の場合
	while($p->hasResNext()) {
		// # データが存在する限り、取得
		$res_array = $p->resNext(); // # 要求結果取得
		// # 他、応答情報を取得
		// print_r($res_array);
		// echo "<br>";
		$num_of_cards = $res_array['num_of_cards'];	// 顧客カード数
	}

	// [num_of_cards] => 5
	// [customer_card_id] => 9098558
	// [fingerprint] => fWxVXnIXgOt6m9hUSvlC6oeOnuT4t1U9EPcshgFZc3whILSzezOTLPzDvzcaAJEw
	// [masked_card_number] => ************0000
	// [card_valid_term] => 1030
	// [cardholder_name] => TARO YAMADA ) 

	$p->init();
	$p->reqPut("merchant_id",       MERCHANT_ID);		//  1 マーチャントID
	$p->reqPut("connect_id",        CONNECT_ID);		//  2 接続ID
	$p->reqPut("connect_password",  CONNECT_PASSWORD);	//  3 接続パスワード
	$p->reqPut("telegram_kind",     "283");			//  4 電文種別ID
	$p->reqPut("telegram_version",  TELEGRAM_VERSION);			//  5 電文バージョン番号
	$p->reqPut("running_id",        $pay_running_id);			//  6 継続課金ID

	// $p->reqPut("amount",            "480");			//  7 決済金額
	// $p->reqPut("customer_id",       "10006");		//  9 顧客ID
	// $p->reqPut("customer_card_id",  $res_array['customer_card_id']);	// 10 顧客カードID
	// $p->reqPut("cycle",             "5");			// 11 課金サイクル
	// $p->reqPut("timing",            "10");			// 12 課金タイミング
	// $p->reqPut("first_executed",    "20200510");		// 13 課金タイミング
	$result = $p->post();
	$resultStatus = $p->getResultStatus(); // 処理結果 0=正常終了, 1=異常終了

	$file = "./log/stop_response.log";
	$data = date('Y/m/d H:i:s');
	$data .= " customer_id=". $pay_customer_id. " running_id=". $pay_running_id;
	$data .= " kind=283 resultStatus=". $resultStatus;
	$data .= "\n";
	file_put_contents($file, $data, FILE_APPEND);

	if ($resultStatus == 1) {
		$responseCode = $p->getResponseCode();		// 異常終了時、レスポンスコードが取得できる
		$responseDetail = $p->getResponseDetail();	//異常終了時、レスポンス詳細が取得できる
		$str = mb_convert_encoding($responseDetail, "UTF-8", "SJIS");

		$file = "./log/stop_result_err.log";
		$data = date('Y/m/d H:i:s');
		$data .= " kind=283 responseCode=". $responseCode;
		$data .= " responseDetail=". $str;
		$data .= "\n";
		file_put_contents($file, $data, FILE_APPEND);
	} else {
		$running_id = "";
		$ended      = "";
		// 複数件取得の場合
		while($p->hasResNext()) {
			// # データが存在する限り、取得
			$res_array = $p->resNext(); // # 要求結果取得
			// $payment_id = $res_array["payment_id"]; // # 決済ID取得
			// # 他、応答情報を取得
			// print_r($res_array);
			// echo "<br>";
			$running_id = $res_array['running_id'];
			$ended      = $res_array['ended'];
		}
	}
	// print_r($res_array);
	// exit();

	if ($num_of_cards == "") {
		$num_of_cards = "0";
	}
	if ($running_id == "") {
		$running_id = "12345678";
	}
	if ($ended == "") {
		$ended = "12345678";
	}
	if ($_GET['ptn'] == 1) {
		$sql = sprintf('UPDATE `user` SET service1_fl=0 WHERE u_id="%d"',
			mysqli_real_escape_string($db, $_SESSION['loginID'])
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));
	} else if ($_GET['ptn'] == 2) {
		$sql = sprintf('UPDATE `user` SET service2_fl=0 WHERE u_id="%d"',
			mysqli_real_escape_string($db, $_SESSION['loginID'])
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));
	} else if ($_GET['ptn'] == 3) {
		$sql = sprintf('UPDATE `user` SET service3_fl=0 WHERE u_id="%d"',
			mysqli_real_escape_string($db, $_SESSION['loginID'])
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));
	} else if ($_GET['ptn'] == 4) {
		$sql = sprintf('UPDATE `user` SET service4_fl=0 WHERE u_id="%d"',
			mysqli_real_escape_string($db, $_SESSION['loginID'])
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));
	} else {
		$sql = sprintf('UPDATE `user` SET service5_fl=0 WHERE u_id="%d"',
			mysqli_real_escape_string($db, $_SESSION['loginID'])
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));
		$_SESSION['service5_chat'] = 0;
	}
	$sql = sprintf('UPDATE `paygent` SET end_num_of_cards="%s",
		end_running_id="%s", end_ended="%s", end_date=NOW(),
		delFlag=1 WHERE u_id="%d" AND ptn="%d"',
		mysqli_real_escape_string($db, $num_of_cards),
		mysqli_real_escape_string($db, $running_id),
		mysqli_real_escape_string($db, $ended),
		mysqli_real_escape_string($db, $_SESSION['loginID']),
		mysqli_real_escape_string($db, $_GET['ptn'])
	);
	mysqli_query($db,$sql) or die(mysqli_error($db));
}
header("Location:/manage/user/");
exit();
?>
