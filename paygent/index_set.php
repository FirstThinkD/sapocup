<?php
session_start();
require_once(__DIR__. '/../common/config.php');

// ① 接続モジュールの利用準備 {接続モジュールのインストールディレクトリ}/vendor/autoload.phpを指定します。
// require(__DIR__. "/vendor/autoload.php");
// use PaygentModule\System\PaygentB2BModule;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<script type="text/javascript" src="<?php echo PAYGENT_JS; ?>" charset="UTF-8"></script>
	<script type="text/javascript">
		// <!-- send関数の定義。カード情報入⼒フォームの送信ボタン押下時の処理。-->
		function send() {
			var form = document.card_form;
			var paygentToken = new PaygentToken();			//PaygentTokenオブジェクトの生成
			paygentToken.createToken(
				'47661',					//第１引数︓マーチャントID
				'live_nICttBQRycmy252kabXQM09K',		//第２引数︓トークン生成鍵
				{						//第３引数︓クレジットカード情報
					card_number:form.card_number.value,	//クレジットカード番号
					expire_year:form.expire_year.value,	//有効期限-YY
					expire_month: form.expire_month.value,	//有効期限-MM
					cvc:form.cvc.value,			//セキュリティーコード
					name:form.name.value			//カード名義
				},execPurchase					//第４引数︓コールバック関数(トークン取得後に実⾏)
			);
		}
		function execPurchase(response) {
			var form = document.card_form;
			// alert("execPurchase");
			if (response.result == '0000') {	//トークン処理結果が正常の場合
				// <!-- カード情報入⼒フォームから、入⼒情報を削除。-->
				form.card_number.removeAttribute('name');
				form.expire_year.removeAttribute('name');
				form.expire_month.removeAttribute('name');
				form.cvc.removeAttribute('name');
				form.name.removeAttribute('name');
				// <!-- 予め⽤意したhidden項目にcreateToken()から応答されたトークン等を設定。-->
				form.token.value = response.tokenizedCardObject.token;
				form.masked_card_number.value = response.tokenizedCardObject.masked_card_number;
				form.valid_until.value = response.tokenizedCardObject.valid_until;
				form.fingerprint.value = response.tokenizedCardObject.fingerprint;
				form.hc.value = response.hc;
				// <!-- カード情報入⼒フォームをsubmitしてtokenを送信する -->
				form.submit();
			} else {	//トークン処理結果が異常の場合
				//<!-- エラー時の処理をここに記述する -->
				alert(response.result);
			}
		}
	</script>
</head>
<body>
	<!-- カード情報入⼒フォーム -->
	<form action="https://sapocup.jp/paygent/pay_response.php" method="POST" name="card_form">
		<div>カード番号︓<input type="text" name="card_number" value="4900000000000000"></div>
		<div>有効期限（年）︓<input type="text" name="expire_year" value="20"></div>
		<div>有効期限（月）︓<input type="text" name="expire_month" value="10"></div>
		<div>セキュリティコード︓<input type="text" name="cvc" value="111"></div>
		<div>カード名義︓<input type="text" name="name" value="TARO YAMADA"></div>
		<input type="button" name="btn" value="登録" onClick="send();">
		<!-- 取得したトークン等をセットするhidden項目 -->
		<input type="hidden" name="token" value="">
		<input type="hidden" name="masked_card_number" value="">
		<input type="hidden" name="valid_until" value="">
		<input type="hidden" name="fingerprint" value="">
		<input type="hidden" name="hc" value="">
	</form>
</body>
</html>
