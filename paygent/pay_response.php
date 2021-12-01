<?php
session_start();
require_once(__DIR__. '/../common/config.php');
require_once(__DIR__. '/../common/dbconnect.php');

// ① 接続モジュールの利用準備 {接続モジュールのインストールディレクトリ}/vendor/autoload.phpを指定します。
require(__DIR__. "/vendor/autoload.php");
use PaygentModule\System\PaygentB2BModule;

// print_r($_POST);
// echo "<br>";
// print_r($_SESSION);
// exit();

if ($_POST['token'] != "") {
	$pay_customer_id = 10000000 + $_SESSION['customer_id'];
	$pay_token = $_POST['token'];
	$pay_masked_card_number1 = $_POST['masked_card_number'];
	$pay_valid_until = $_POST['valid_until'];
	$pay_fingerprint = $_POST['fingerprint'];
	$pay_hc = $_POST['hc'];

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
	$p->reqPut("telegram_kind",     "025");			//  4 電文種別ID
	$p->reqPut("telegram_version",  TELEGRAM_VERSION);	//  5 電文バージョン番号
	// $p->reqPut("trading_id",        "40110100");		//  6 マーチャント取引ID
	// $p->reqPut("payment_id",        "11111");		//  7 決済ID
	$p->reqPut("customer_id",       $pay_customer_id);	//  8 顧客ID
	// $p->reqPut("card_number",       $_POST['masked_card_number']);	//  9 カード番号
	// $p->reqPut("card_number",       "4900000000000000");	//  9 カード番号
	// $p->reqPut("card_valid_term",   "3010");		// 10 カード有効期限
	// $p->reqPut("card_conf_number",  "1111");		// 11 カード確認番号
	// $p->reqPut("cardholder_name",   "TARO YAMADA");	// 13 カード名義人
	$p->reqPut("valid_check_flg",   "1");			// 19 有効性チェックフラグ
	$p->reqPut("card_token",        $_POST['token']);	// 20 カード情報トークン
	// $p->reqPut("security_code_use", "0");		// 21 セキュリティコード利用
	// 電文種別ID：200（ファイル決済要求）の場合は、送信ファイルパスをセット
	// $p->setSendFilePath("");
	$result = 0;

	// ④ ペイジェントへ要求を送信
	$result = $p->post();
	$resultStatus = $p->getResultStatus(); // 処理結果 0=正常終了, 1=異常終了
	// echo "kind=025 resultStatus=". $resultStatus. "<br>";

	$file = "./log/post_response.log";
	$data = date('Y/m/d H:i:s');
	$data .= " customer_id=". $pay_customer_id;
	$data .= " kind=025 result=". $result. " resultStatus=". $resultStatus;
	$data .= "\n";
	file_put_contents($file, $data, FILE_APPEND);

	// ⑤ 要求送信結果を確認
	// if ($resultStatus == 1 || $resultStatus == "" || $result == 1) {
	if ($resultStatus == 1 || $resultStatus == "") {
		// ⑥ 要求結果を取得
		// エラーコード取得
		$responseCode = $p->getResponseCode();		// 異常終了時、レスポンスコードが取得できる
		$responseDetail = $p->getResponseDetail();	// 異常終了時、レスポンス詳細が取得できる
		$errorcode = $result;
		$str = mb_convert_encoding($responseDetail, "UTF-8", "SJIS");

		$file = "./log/result_err.log";
		$data = date('Y/m/d H:i:s');
		$data .= " responseCode=". $responseCode;
		$data .= " responseDetail=". $str;
		$data .= "\n";
		file_put_contents($file, $data, FILE_APPEND);

		$_SESSION['pay_Error']  = "fail";
		$_SESSION['pay_Detail'] = $str;
		// echo "エラー内容：". $str;
		// echo "<script>alert('カード情報登録でエラーが発生しました。左上のエラー内容を管理者にお伝えください。');location.href='/paygent/',3000;</script>";
		// echo "<script>alert('カード情報登録でエラーが発生しました。左上のエラー内容を確認し、再登録をお願いします。');location.href='/paygent/';</script>";
		header("Location:/paygent/");
		exit();
	} else {
		// echo "OK result=". $result. "<br>";

		// $num_of_cards = $p->num_of_cards();		// 顧客カード数
		// echo "num_of_cards=". $num_of_cards. "<br>";
		// $customer_card_id = $p->customer_card_id();	// 顧客カードID
		// echo "customer_card_id=". $customer_card_id. "<br>";

		// 1件取得の場合
		// if($p->hasResNext()) {
		//	// # データが存在
		//	$res_array = $p->resNext();		// # 要求結果取得
		//	// # 他、応答情報を取得
		//	print_r($res_array);
		//	$pay_running_id = $res_array['running_id'];
		//	$pay_trading_id = $res_array['trading_id'];
		//	$_SESSION['pay_ended'] = $res_array['ended'];
		// }
		// 複数件取得の場合
		while($p->hasResNext()) {
			// # データが存在する限り、取得
			$res_array = $p->resNext(); // # 要求結果取得
			$payment_id = $res_array["payment_id"]; // # 決済ID取得
			// # 他、応答情報を取得
			// print_r($res_array);
			// echo "<br>";

			if ($res_array['num_of_cards'] != "") {
				$pay_num_of_cards = $res_array['num_of_cards'];
			}
			if ($res_array['customer_card_id'] != "") {
				$pay_customer_card_id = $res_array['customer_card_id'];
			}
			if ($res_array['fingerprint'] != "") {
				$pay_fingerprint2 = $res_array['fingerprint'];
			}
			if ($res_array['masked_card_number'] != "") {
				$pay_masked_card_number2 = $res_array['masked_card_number'];
			}
			if ($res_array['card_valid_term'] != "") {
				$pay_card_valid_term = $res_array['card_valid_term'];
			}
			if ($res_array['cardholder_name'] != "") {
				$pay_cardholder_name = $res_array['cardholder_name'];
			}
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
		$first_day = date('Ymt');

		$p->init();
		$p->reqPut("merchant_id",       MERCHANT_ID);		//  1 マーチャントID
		$p->reqPut("connect_id",        CONNECT_ID);		//  2 接続ID
		$p->reqPut("connect_password",  CONNECT_PASSWORD);	//  3 接続パスワード
		$p->reqPut("telegram_kind",     "280");			//  4 電文種別ID
		$p->reqPut("telegram_version",  TELEGRAM_VERSION);	//  5 電文バージョン番号
		$p->reqPut("amount",            $_SESSION['PAY_SERVICE']);	//  7 決済金額
		$p->reqPut("customer_id",       $pay_customer_id);	//  9 顧客ID
		$p->reqPut("customer_card_id",  $res_array['customer_card_id']);	// 10 顧客カードID
		$p->reqPut("cycle",             "1");			// 11 課金サイクル
		$p->reqPut("timing",            "31");			// 12 課金タイミング
		$p->reqPut("first_executed",    $first_day);		// 13 初回課金
		$result = $p->post();
		$resultStatus = $p->getResultStatus(); // 処理結果 0=正常終了, 1=異常終了

		// echo "kind=280 resultStatus=". $resultStatus. "<br>";

		$file = "./log/post_response.log";
		$data = date('Y/m/d H:i:s');
		$data .= " customer_id=". $pay_customer_id;
		$data .= " kind=280 resultStatus=". $resultStatus;
		$data .= "\n";
		file_put_contents($file, $data, FILE_APPEND);

		if ($resultStatus == 1) {
			$responseCode = $p->getResponseCode();		// 異常終了時、レスポンスコードが取得できる
			$responseDetail = $p->getResponseDetail();	//異常終了時、レスポンス詳細が取得できる
			$str = mb_convert_encoding($responseDetail, "UTF-8", "SJIS");
			// echo "kind=280 responseCode=". $responseCode. " responseDetail=". $str. "<br>";

			$file = "./log/result_err.log";
			$data = date('Y/m/d H:i:s');
			$data .= " responseCode=". $responseCode;
			$data .= " responseDetail=". $str;
			$data .= "\n";
			file_put_contents($file, $data, FILE_APPEND);
		} else {
			// 複数件取得の場合
			while($p->hasResNext()) {
				// # データが存在する限り、取得
				$res_array = $p->resNext(); // # 要求結果取得
				$payment_id = $res_array["payment_id"]; // # 決済ID取得
				// # 他、応答情報を取得
				// print_r($res_array);
				// echo "<br>";

				if ($res_array['running_id'] != "") {
					$pay_running_id = $res_array['running_id'];
				}
				if ($res_array['trading_id'] != "") {
					$pay_trading_id = $res_array['trading_id'];
				}
				if ($res_array['created'] != "") {
					$pay_created = $res_array['created'];
				}
				if ($res_array['next_scheduled'] != "") {
					$pay_next_scheduled = $res_array['next_scheduled'];
				}
			}

			$sql = sprintf('SELECT * FROM paygent WHERE u_id="%d" AND ptn=1 AND delFlag=2',
				mysqli_real_escape_string($db, $_SESSION['customer_id'])
			);
			$record = mysqli_query($db, $sql) or die(mysqli_error($db));
			if ($row0 = mysqli_fetch_assoc($record)) {
				// データあり
			} else {
				// データなし
				$sql = sprintf('INSERT INTO paygent SET u_id="%d",
					ptn=1, customer_id="%s", customer_card_id="%s",
					fingerprint="%s", running_id="%s", token="%s",
					masked_card_number1="%s", valid_until="%s",
					hc="%s", num_of_cards="%s", fingerprint2="%s",
					masked_card_number2="%s", card_valid_term="%s",
					cardholder_name="%s", trading_id="%s", created="%s",
					next_scheduled="%s", str_date=NOW()',
					mysqli_real_escape_string($db, $_SESSION['customer_id']),
					mysqli_real_escape_string($db, $pay_customer_id),
					mysqli_real_escape_string($db, $pay_customer_card_id),
					mysqli_real_escape_string($db, $pay_fingerprint),
					mysqli_real_escape_string($db, $pay_running_id),
					mysqli_real_escape_string($db, $pay_token),
					mysqli_real_escape_string($db, $pay_masked_card_number1),
					mysqli_real_escape_string($db, $pay_valid_until),
					mysqli_real_escape_string($db, $pay_hc),
					mysqli_real_escape_string($db, $pay_num_of_cards),
					mysqli_real_escape_string($db, $pay_fingerprint2),
					mysqli_real_escape_string($db, $pay_masked_card_number2),
					mysqli_real_escape_string($db, $pay_card_valid_term),
					mysqli_real_escape_string($db, $pay_cardholder_name),
					mysqli_real_escape_string($db, $pay_trading_id),
					mysqli_real_escape_string($db, $pay_created),
					mysqli_real_escape_string($db, $pay_next_scheduled)
				);
				mysqli_query($db,$sql) or die(mysqli_error($db));


				//会員契約サービスの設定 >>>
                $sql = sprintf('SELECT * FROM user WHERE customer_id="%s"', mysqli_real_escape_string($db, $_SESSION['customer_id']));
                $record1 = mysqli_query($db, $sql) or die(mysqli_error($db));
                if ($row1 = mysqli_fetch_assoc($record)) {
                    if ($row1['service_exe'] == "service1") {
                        $sql = sprintf('UPDATE `user` SET service1_fl=1,
				service1_id="%s", service1_dt=NOW() WHERE customer_id="%s"',
                            mysqli_real_escape_string($db, $pay_customer_card_id),
                            mysqli_real_escape_string($db, $_SESSION['customer_id'])
                        );
                        mysqli_query($db,$sql) or die(mysqli_error($db));
                    } else if ($row1['service_exe'] == "service2") {
                        $sql = sprintf('UPDATE `user` SET service2_fl=1,
				service2_id="%s", service2_dt=NOW() WHERE customer_id="%s"',
                            mysqli_real_escape_string($db, $pay_customer_card_id),
                            mysqli_real_escape_string($db, $_SESSION['customer_id'])
                        );
                        mysqli_query($db,$sql) or die(mysqli_error($db));
                    } elseif ($row1['service_exe'] == "service3") {
                        $sql = sprintf('UPDATE `user` SET service3_fl=1,
				service3_id="%s", service3_dt=NOW() WHERE customer_id="%s"',
                            mysqli_real_escape_string($db, $pay_customer_card_id),
                            mysqli_real_escape_string($db, $_SESSION['customer_id'])
                        );
                        mysqli_query($db,$sql) or die(mysqli_error($db));
                    } else {
                        $sql = sprintf('UPDATE `user` SET service4_fl=1,
				service4_id="%s", service4_dt=NOW() WHERE customer_id="%s"',
                            mysqli_real_escape_string($db, $pay_customer_card_id),
                            mysqli_real_escape_string($db, $_SESSION['customer_id'])
                        );
                        mysqli_query($db,$sql) or die(mysqli_error($db));
                    }
                }
                //<<<

				// 法人
				unset($_SESSION['u_company']);
				unset($_SESSION['u_company_kana']);
				unset($_SESSION['u_ceo']);
				unset($_SESSION['u_ceo_kana']);
				unset($_SESSION['u_person']);
				unset($_SESSION['u_person_kana']);
				unset($_SESSION['u_department']);
				unset($_SESSION['u_postal']);
				unset($_SESSION['u_address1']);
				unset($_SESSION['u_address2']);
				unset($_SESSION['u_address3']);
				unset($_SESSION['u_tel']);
				unset($_SESSION['u_email1']);
				unset($_SESSION['u_email2']);
				unset($_SESSION['u_pass1']);
				unset($_SESSION['u_pass2']);

				// 個人
				unset($_SESSION['p_name']);
				unset($_SESSION['p_kana']);
				unset($_SESSION['p_year']);
				unset($_SESSION['p_mouth']);
				unset($_SESSION['p_day']);
				unset($_SESSION['p_tel']);
				unset($_SESSION['p_postal']);
				unset($_SESSION['p_address1']);
				unset($_SESSION['p_address2']);
				unset($_SESSION['p_address3']);
				unset($_SESSION['p_email1']);
				unset($_SESSION['p_email2']);
				unset($_SESSION['p_pass1']);
				unset($_SESSION['p_pass2']);

				header("Location:/complete-signup.php");
				exit();
			}

		}
		/*------------------------------------------ 

		新規追加項目

		--------------------------------------------*/
	}
}
header("Location:/");
exit();
?>