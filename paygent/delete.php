<?php
session_start();
require_once(__DIR__. '/../common/config.php');

$file = "./log/post_response.log";
$data = date('Y/m/d H:i:s');
$data .= " DELETE u_id=". $_SESSION['customer_id'] ;
$data .= "\n";
file_put_contents($file, $data, FILE_APPEND);

// print_r($_SESSION);
// exit();
// ① 接続モジュールの利用準備 {接続モジュールのインストールディレクトリ}/vendor/autoload.phpを指定します。
require(__DIR__. "/vendor/autoload.php");
use PaygentModule\System\PaygentB2BModule;

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<link rel="stylesheet" type="text/css" href="/common/css/other.css">
	<link rel="stylesheet" type="text/css" href="/common/css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP&display=swap" rel="stylesheet">
</head>
<body>
	<?php include_once(__DIR__ . '/../common/header.php'); ?>
	<script type="text/javascript" src="<?php echo PAYGENT_JS; ?>" charset="UTF-8"></script>
	<script type="text/javascript" src="/common/js/paygent.js" charset="UTF-8"></script>
	<main id="searchFunction">
		<div class="otherScreen help">
			<div class="allWrapper">
				<div class="otherScreenInner">
					<h1>クレジットカード決済</h1>
				</div>
			</div>
		</div>
		<style>
			#habbitButton a,
			#habbitButton input[type=button] {
				float: left;
				display: block;
				width: 49%;
				height: 60px;
				line-height: 60px;
				border-radius: 2px;
				text-align: center;
				font-weight: 700;
				color: #fff;
				font-size: 14px;
			}
			#habbitButton a {
				margin-right: 2%;
				background: #333;
			}
			#habbitButton input[type=button] {
				background:#1D449B;
			}
			#habbitButton a:hover,
			#habbitButton input[type=button]:hover {
				opacity: 0.8;
			}
		</style>
		<div class="otherContent" id="memberRegistation">
			<div class="allWrapper">
				<div class="otherContentInner loginWrap">
					<div class="loginBox">
						<?php if ($_SESSION['pay_Error'] == "fail") { ?>
							<div class="otherScreenInner">
							<?php if ($_SESSION['pay_Detail'] != "") { ?>
								<font color="#f05b72">「<?php echo $_SESSION['pay_Detail']; ?>」<br><br></font>
							<?php } ?>
							<font color="#f05b72">エラーが発生しました。<br></font>
							<font color="#f05b72" style="white-space: nowrap">再度カード番号を登録をするか、管理者にご連絡ください。</font>
							<font color="#f05b72"><br>　<br></font>
							<?php $_SESSION['pay_Error']  = ""; ?>
							<?php $_SESSION['pay_Detail'] = ""; ?>
							</div>
						<?php } ?>
						<div class="loginImg">
							<img src="/common/img/logo_creditcard.png" alt="クレジットカード情報">
						</div>
						<form action="https://sapocup.jp/paygent/pay_response.php" method="POST" name="card_form">
							<div class="inputBox">
								<p>カード番号<span class="must">必須</span></p>
								<input id="inputCard" type="text" name="card_number" value="" pattern="^[0-9]+$" maxlength="16" placeholder="1234567890123456" title="半角数字を16桁以内で入力してください" required>
							</div>
							<div class="inputBox">
								<p>有効期限（年）<span class="must">必須（下2桁）</span></p>
								<div id="selectDate" class="inputBoxBirth cf">
									<div class="birthBox month cf">
										<span class="birth_input">
											<input id="inputYear" type="text" name="expire_year" value="" pattern="^[0-9]+$" maxlength="2" placeholder="30" title="半角数字を2桁以内で入力してください" required="required">
										</span>
									</div>
								</div>
								<p>有効期限（月）<span class="must">必須</span></p>
								<div id="selectDate" class="inputBoxBirth cf">
									<div class="birthBox day cf">
										<span class="birth_input">
											<input id="inputMonth" type="text" name="expire_month" value="" pattern="^[0-9]+$" maxlength="2" placeholder="01" title="半角数字を2桁以内で入力してください" required="required">
										</span>
									</div>
								</div>
							</div>
							<div class="inputBox">
								<p>セキュリティコード<span class="must">必須</span></p>
								<input id="inputSecurity" type="text" name="cvc" value="" pattern="^[0-9]+$" maxlength="4" placeholder="1234" title="半角数字を4桁以内で入力してください" required="required">
							</div>
							<input type="hidden" name="token" value="">
							<input type="hidden" name="masked_card_number" value="">
							<input type="hidden" name="valid_until" value="">
							<input type="hidden" name="fingerprint" value="">
							<input type="hidden" name="hc" value="">
							<div class="inputBox">
								<p>カード名義<span class="must">必須</span></p>
								<input id="your_name" type="text" name="name" value="" pattern="^[0-9A-Za-z, ,]+$" placeholder="TARO YAMADA" title="半角英字を入力してください" required="required">
							</div>
							<div id="habbitButton" class="inputBox submitBox cf no_button">
								<a href="<?php echo $_SESSION['verifi_url']; ?>">戻る</a>
								<input id="submit_btn" type="button" value="送信" onClick="send();" style="cursor:pointer;">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php include_once(__DIR__ . '../../common/footer.php'); ?>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<script src="/common/js/footerFixed.js"></script>
	<script src="/common/js/input.js"></script>
	<script src="/common/js/prefectures.js"></script>
	<script>
		// ハンバーガーメニュー
		$('#nav-input').on('change',function(){
			if ($(this).prop('checked')) {
				$('#nav-content').addClass('navOpen');
			} else {
				$('#nav-content').removeClass('navOpen');
			}
		});

		// テキストが入っていた場合の測定
		$(function(){
			$("#inputCard").on('input change', function() {
				if($("#inputCard").val() !== "" && $("#inputMonth").val() !== "" && $("#inputYear").val() !== "" && $("#inputSecurity").val() !== "" && $("#your_name").val() !== "") {
					$('#habbitButton').removeClass('no_button');
				}
			})
		});
		$(function(){
			$("#inputMonth").on('input change', function() {
				if($("#inputCard").val() !== "" && $("#inputMonth").val() !== "" && $("#inputYear").val() !== "" && $("#inputSecurity").val() !== "" && $("#your_name").val() !== "") {
					$('#habbitButton').removeClass('no_button');
				}
			})
		});
		$(function(){
			$("#inputYear").on('input change', function() {
				if($("#inputCard").val() !== "" && $("#inputMonth").val() !== "" && $("#inputYear").val() !== "" && $("#inputSecurity").val() !== "" && $("#your_name").val() !== "") {
					$('#habbitButton').removeClass('no_button');
				}
			})
		});
		$(function(){
			$("#inputSecurity").on('input change', function() {
				if($("#inputCard").val() !== "" && $("#inputMonth").val() !== "" && $("#inputYear").val() !== "" && $("#inputSecurity").val() !== "" && $("#your_name").val() !== "") {
					$('#habbitButton').removeClass('no_button');
				}
			})
		});
		$(function(){
			$("#your_name").on('input change', function() {
				if($("#inputCard").val() !== "" && $("#inputMonth").val() !== "" && $("#inputYear").val() !== "" && $("#inputSecurity").val() !== "" && $("#your_name").val() !== "") {
					$('#habbitButton').removeClass('no_button');
				}
			})
		});



		$(function(){
			$("#inputCard").on('input change', function() {
				if($("#inputCard").val() === "" || $("#inputMonth").val() === "" || $("#inputYear").val() === "" || $("#inputSecurity").val() === "" || $("#your_name").val() === "") {
					$('#habbitButton').addClass('no_button');
				}
			})
		});
		$(function(){
			$("#inputMonth").on('input change', function() {
				if($("#inputCard").val() === "" || $("#inputMonth").val() === "" || $("#inputYear").val() === "" || $("#inputSecurity").val() === "" || $("#your_name").val() === "") {
					$('#habbitButton').addClass('no_button');
				}
			})
		});
		$(function(){
			$("#inputYear").on('input change', function() {
				if($("#inputCard").val() === "" || $("#inputMonth").val() === "" || $("#inputYear").val() === "" || $("#inputSecurity").val() === "" || $("#your_name").val() === "") {
					$('#habbitButton').addClass('no_button');
				}
			})
		});
		$(function(){
			$("#inputSecurity").on('input change', function() {
				if($("#inputCard").val() === "" || $("#inputMonth").val() === "" || $("#inputYear").val() === "" || $("#inputSecurity").val() === "" || $("#your_name").val() === "") {
					$('#habbitButton').addClass('no_button');
				}
			})
		});
		$(function(){
			$("#your_name").on('input change', function() {
				if($("#inputCard").val() === "" || $("#inputMonth").val() === "" || $("#inputYear").val() === "" || $("#inputSecurity").val() === "" || $("#your_name").val() === "") {
					$('#habbitButton').addClass('no_button');
				}
			})
		});

	</script>
</body>
</html>