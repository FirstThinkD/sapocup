<?php
session_start();

// ① 接続モジュールの利用準備 {接続モジュールのインストールディレクトリ}/vendor/autoload.phpを指定します。
require(__DIR__. "/vendor/autoload.php");
use PaygentModule\System\PaygentB2BModule;

$pay_customer_id      = "10000002";	//  8 顧客ID
$pay_customer_card_id = "9099583";	//  9 顧客カードID
$pay_running_id       = "1032787";	//  6 継続課金ID

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
$p->reqPut("merchant_id",       "47661");		//  1 マーチャントID
$p->reqPut("connect_id",        "pgynt47661");		//  2 接続ID
$p->reqPut("connect_password",  "ogs9zUsUxXs");		//  3 接続パスワード
$p->reqPut("telegram_kind",     "026");			//  4 電文種別ID
$p->reqPut("telegram_version",  "1.0");			//  5 電文バージョン番号
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
$p->reqPut("fingerprint",        "fWxVXnIXgOt6m9hUSvlC6oeOnuT4t1U9EPcshgFZc3whILSzezOTLPzDvzcaAJEw");	// 20 カード情報トークン
// $p->reqPut("security_code_use", "0");		// 21 セキュリティコード利用

// 電文種別ID：200（ファイル決済要求）の場合は、送信ファイルパスをセット
// $p->setSendFilePath("");
$result = 0;

// ④ ペイジェントへ要求を送信
$result = $p->post();
$resultStatus = $p->getResultStatus(); // 処理結果 0=正常終了, 1=異常終了
echo "resultStatus=". $resultStatus. "<br>";

// ⑤ 要求送信結果を確認
if ($resultStatus == 1) {
	// エラー処理
	// ⑥ 要求結果を取得
	$responseCode = $p->getResponseCode();		// 異常終了時、レスポンスコードが取得できる
	$responseDetail = $p->getResponseDetail();	//異常終了時、レスポンス詳細が取得できる

	echo "ERROR result=". $result. "<br>";
	// エラーコード取得
	$errorcode = $result;
	// echo "resultStatus=". $resultStatus. "<br>";
	echo "responseCode=". $responseCode;
	$str = mb_convert_encoding($responseDetail, "UTF-8", "SJIS");
	echo " responseDetail=". $str. "<br>";

	$file = "./log/result.log";
	$data = date('Y/m/d H:i:s');
	$data .= " responseCode=". $responseCode;
	$data .= " responseDetail=". $responseDetail;
	$data .= "\n";
	file_put_contents($file, $data, FILE_APPEND);
} else {
	echo "OK result=". $result. "<br>";

	// $num_of_cards = $p->num_of_cards();		// 顧客カード数
	// echo "num_of_cards=". $num_of_cards. "<br>";
	// $customer_card_id = $p->customer_card_id();	// 顧客カードID
	// echo "customer_card_id=". $customer_card_id. "<br>";

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
		print_r($res_array);
		echo "<br>";
	}

	// [num_of_cards] => 5
	// [customer_card_id] => 9098558
	// [fingerprint] => fWxVXnIXgOt6m9hUSvlC6oeOnuT4t1U9EPcshgFZc3whILSzezOTLPzDvzcaAJEw
	// [masked_card_number] => ************0000
	// [card_valid_term] => 1030
	// [cardholder_name] => TARO YAMADA ) 

	/*------------------------------------------ 

	新規追加項目

	--------------------------------------------*/

	$p->init();
	$p->reqPut("merchant_id",       "47661");		//  1 マーチャントID
	$p->reqPut("connect_id",        "pgynt47661");		//  2 接続ID
	$p->reqPut("connect_password",  "ogs9zUsUxXs");		//  3 接続パスワード
	$p->reqPut("telegram_kind",     "283");			//  4 電文種別ID
	$p->reqPut("telegram_version",  "1.0");			//  5 電文バージョン番号
	$p->reqPut("running_id",        $pay_running_id);			//  6 継続課金ID

	// $p->reqPut("amount",            "480");			//  7 決済金額
	// $p->reqPut("customer_id",       "10006");		//  9 顧客ID
	// $p->reqPut("customer_card_id",  $res_array['customer_card_id']);	// 10 顧客カードID
	// $p->reqPut("cycle",             "5");			// 11 課金サイクル
	// $p->reqPut("timing",            "10");			// 12 課金タイミング
	// $p->reqPut("first_executed",    "20200510");		// 13 課金タイミング
	$result = $p->post();
	$resultStatus = $p->getResultStatus(); // 処理結果 0=正常終了, 1=異常終了

	echo "resultStatus=". $resultStatus. "<br>";

	if ($resultStatus == 1) {
		$responseCode = $p->getResponseCode();		// 異常終了時、レスポンスコードが取得できる
		$responseDetail = $p->getResponseDetail();	//異常終了時、レスポンス詳細が取得できる
		echo "1 resultStatus=". $resultStatus. "<br>";
		echo "responseCode=". $responseCode;
		$str = mb_convert_encoding($responseDetail, "UTF-8", "SJIS");
		echo " responseDetail=". $str. "<br>";
	} else {
		// 複数件取得の場合
		while($p->hasResNext()) {
			// # データが存在する限り、取得
			$res_array = $p->resNext(); // # 要求結果取得
			$payment_id = $res_array["payment_id"]; // # 決済ID取得
			// # 他、応答情報を取得
			print_r($res_array);
			echo "<br>";
		}
	}

	/*------------------------------------------ 

	新規追加項目

	--------------------------------------------*/

}
?>
