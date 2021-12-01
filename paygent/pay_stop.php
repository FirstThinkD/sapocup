<?php
session_start();
require_once(__DIR__. '/../common/config.php');
require_once(__DIR__. '/../common/dbconnect.php');

if ($_GET['ptn'] == "" || $_GET['id'] != "1" || $_SESSION['loginID'] == "") {
	header("Location:/");
	exit();
}

$sql = sprintf('SELECT * FROM `paygent` WHERE u_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$ix = 0;
while($row7 = mysqli_fetch_assoc($record)) {
	$pay_id[$ix]         = $row7['pay_id'];
	$pay_ptn[$ix]        = $row7['ptn'];
	$pay_running_id[$ix] = $row7['running_id'];	//  6 継続課金ID
	if ($pay_ptn[$ix] == 1) {
		$pay_customer_id      = $row7['customer_id'];	//  8 顧客ID
		$pay_customer_card_id = $row7['customer_card_id'];	//  9 顧客カードID
		$fingerprint          = $row7['fingerprint'];	// フィンガープリント
	}
	$ix++;
}
$pay_count = $ix;

if ($pay_count == 0) {
	header("Location:/manage/user/");
	exit();
}

// ① 接続モジュールの利用準備 {接続モジュールのインストールディレクトリ}/vendor/autoload.phpを指定します。
require(__DIR__. "/vendor/autoload.php");
use PaygentModule\System\PaygentB2BModule;

// $pay_customer_id      = "10000002";	//  8 顧客ID
// $pay_customer_card_id = "9099583";	//  9 顧客カードID
// $pay_running_id       = "1032787";	//  6 継続課金ID

// token
// masked_card_number: ************0000
// valid_until(トークン有効期限): 20200413155921
// fingerprint(フィンガープリント): 
// hc

// ② 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
$p = new PaygentB2BModule();
$p->init();

// ③ ペイジェントへの要求をセット※電文に必要な要求情報を設定
//（要求に必要となるパラメータをハッシュにして設定 ）
$p->reqPut("merchant_id",       MERCHANT_ID);		//  1 マーチャントID
$p->reqPut("connect_id",        CONNECT_ID);		//  2 接続ID
$p->reqPut("connect_password",  CONNECT_PASSWORD);	//  3 接続パスワード
$p->reqPut("telegram_kind",     "026");			//  4 電文種別ID
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
$data .= " customer_id=". $pay_customer_id. " running_id=". $pay_running_id[0];
$data .= " kind=026 resultStatus=". $resultStatus;
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

	for($ix=0; $ix<$pay_count; $ix++) {
		$p->init();
		$p->reqPut("merchant_id",       MERCHANT_ID);		//  1 マーチャントID
		$p->reqPut("connect_id",        CONNECT_ID);		//  2 接続ID
		$p->reqPut("connect_password",  CONNECT_PASSWORD);	//  3 接続パスワード
		$p->reqPut("telegram_kind",     "283");			//  4 電文種別ID
		$p->reqPut("telegram_version",  TELEGRAM_VERSION);	//  5 電文バージョン番号
		$p->reqPut("running_id",        $pay_running_id[$ix]);	//  6 継続課金ID

		// $p->reqPut("amount",            "480");		//  7 決済金額
		// $p->reqPut("customer_id",       "10006");		//  9 顧客ID
		// $p->reqPut("customer_card_id",  $res_array['customer_card_id']);	// 10 顧客カードID
		// $p->reqPut("cycle",             "5");		// 11 課金サイクル
		// $p->reqPut("timing",            "10");		// 12 課金タイミング
		// $p->reqPut("first_executed",    "20200510");		// 13 課金タイミング
		$result = $p->post();
		$resultStatus = $p->getResultStatus(); // 処理結果 0=正常終了, 1=異常終了

		$file = "./log/stop_response.log";
		$data = date('Y/m/d H:i:s');
		$data .= " customer_id=". $pay_customer_id. " running_id=". $pay_running_id[$ix];
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
			$trading_id = "";
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
				$trading_id = $res_array['trading_id'];
				$ended      = $res_array['ended'];
			}
		}
		if ($num_of_cards == "") {
			$num_of_cards = "0";
		}
		if ($running_id == "") {
			$running_id = "12345678";
		}
		if ($ended == "") {
			$ended = "12345678";
		}

		if ($pay_ptn[$ix] == 1) {
			$sql = sprintf('UPDATE `user` SET service1_fl=0, exeFlag=1 WHERE u_id="%d"',
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
			$_SESSION['user_exeFlag'] = 1;
			$_SESSION['user_exemess'] = "userEnd";

			$to = $_SESSION['loginMail'];
			$title = "【さぽかっぷ】会員退会完了のお知らせ";
			$body  = "※このメールはシステムからの自動返信です". "\r\n\r\n";
			$body .= $_SESSION['loginName']. " 様". "\r\n\r\n";
			$body .= "「さぽかっぷ」運営企業の株式会社ANBIENCEでございます。". "\r\n";
			$body .= "「さぽかっぷ」の会員退会の手続きが完了いたしました。これまで、ご利用いただきまして". "\r\n";
			$body .= "誠にありがとうございました。". "\r\n\r\n";
			$body .= "このメールは、ご登録時に確認のため送信させていただいております。". "\r\n";
			$body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━". "\r\n";
			$body .= "■ご登録いただいた会員ID". "\r\n";
			$body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━". "\r\n";
			$body .= "ログインID：". $to. "\r\n\r\n";
			$body .= "ログインIDおよびパスワードは、". "\r\n";
			$body .= "「さぽかっぷ」にログインする際に必要となります。". "\r\n";
			$body .= "忘れず保管をお願いいたします。". "\r\n\r\n";
			$body .= "また、ご不明な点がございましたら". "\r\n";
			$body .= "下記までお気軽にお問い合せくださいませ。". "\r\n";
			$body .= "――――――――――――". "\r\n\r\n";
			$body .= "「さぽかっぷ」". "\r\n";
			$body .= "URL：https://sapocup.jp/contact.php". "\r\n";
			$body .= "メールアドレス：sapocup01@sapocup.jp". "\r\n\r\n";
			$body .= "営業時間：　平日 10:00～17:00". "\r\n\r\n";
			$body .= "【運営元：株式会社AMBIENCE】". "\r\n";
			$body .= "住所：〒103-0025　東京都中央区日本橋茅場町3-7-6". "\r\n\r\n";

			$header  = "From:sapocup01@sapocup.jp". "\r\n";
			$header .= "Bcc:sapocup01@sapocup.jp". "\r\n";
			// $header .= "Bcc:chankan77@gmail.com". "\r\n";
			// $header .= "Bcc:nakazawa6097@gmail.com". "\r\n";

			mb_language('ja');
			mb_internal_encoding('UTF-8');
			mb_send_mail($to, $title, $body, $header);
		}
		if ($pay_ptn[$ix] == 2) {
			$sql = sprintf('UPDATE `user` SET service2_fl=0 WHERE u_id="%d"',
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		}
		if ($pay_ptn[$ix] == 3) {
			$sql = sprintf('UPDATE `user` SET service3_fl=0 WHERE u_id="%d"',
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		}
		if ($pay_ptn[$ix] == 4) {
			$sql = sprintf('UPDATE `user` SET service4_fl=0 WHERE u_id="%d"',
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		}
		if ($pay_ptn[$ix] == 5) {
			$sql = sprintf('UPDATE `user` SET service5_fl=0 WHERE u_id="%d"',
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		}

		$sql = sprintf('UPDATE `paygent` SET end_num_of_cards="%s",
			end_running_id="%s", end_ended="%s", end_date=NOW(),
			delFlag=1 WHERE pay_id="%d"',
			mysqli_real_escape_string($db, $num_of_cards),
			mysqli_real_escape_string($db, $running_id),
			mysqli_real_escape_string($db, $ended),
			mysqli_real_escape_string($db, $pay_id[$ix])
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));
	}
}
header("Location:/manage/user/");
exit();
?>
