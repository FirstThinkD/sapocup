<?php
session_start();
require_once(__DIR__ . '/common/dbconnect.php');
require_once(__DIR__ . '/common/config.php');
require_once(__DIR__ . '/manage/common/functions.php');

//決済画面へ
if (!empty($_POST['commit2']) && $_POST['commit2'] == "決済情報登録へ進む") {
	$passwd = md5($_SESSION['p_pass1']);

	$sql = sprintf('SELECT * FROM user WHERE p_email="%s" AND delFlag=2',
		mysqli_real_escape_string($db, $_SESSION['p_email1'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row0 = mysqli_fetch_assoc($record)) {
		// データあり
		$_SESSION['verifi_url']  = "/verification-signup.php";
		$_SESSION['customer_id'] = $row0['u_id'];
		// $_SESSION['connect_id']  = $connect_id;
		$_SESSION['PAY_SERVICE'] = SERVICE1;
		$_SESSION['CONNECT_PS']  = CONNECT_PASSWORD;

        header("Location: /paygent/");
		exit();
	} else {
		// データなし
		$p_postal = $_SESSION['p_postal1']. "-". $_SESSION['p_postal2'];

		$sql = sprintf('INSERT INTO user SET u_type="個人",service_exe="service1",
			p_name="%s", p_kana="%s", p_birth_year="%s",
			p_birth_month="%s", p_birth_day="%s",
			p_tel="%s", p_tel2="%s", p_postal="%s", p_address1="%s",
			p_address2="%s", p_address3="%s", p_email="%s",
			p_pass="%s", strdate=NOW(), delFlag=2',
			mysqli_real_escape_string($db, $_SESSION['p_name']),
			mysqli_real_escape_string($db, $_SESSION['p_kana']),
			mysqli_real_escape_string($db, $_SESSION['p_year']),
			mysqli_real_escape_string($db, $_SESSION['p_mouth']),
			mysqli_real_escape_string($db, $_SESSION['p_day']),
			mysqli_real_escape_string($db, $_SESSION['p_tel']),
			mysqli_real_escape_string($db, $_SESSION['p_tel2']),
			mysqli_real_escape_string($db, $p_postal),
			mysqli_real_escape_string($db, $_SESSION['p_address1']),
			mysqli_real_escape_string($db, $_SESSION['p_address2']),
			mysqli_real_escape_string($db, $_SESSION['p_address3']),
			mysqli_real_escape_string($db, $_SESSION['p_email1']),
			mysqli_real_escape_string($db, $passwd)
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));

		$sql = sprintf('SELECT * FROM user WHERE p_email="%s" AND delFlag=2',
			mysqli_real_escape_string($db, $_SESSION['p_email1'])
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$row0 = mysqli_fetch_assoc($record);
		$customer_id = $row0['u_id'];

		$sql = sprintf('UPDATE user SET customer_id="%s" WHERE u_id="%d" AND delFlag=2',
			mysqli_real_escape_string($db, $customer_id),
			mysqli_real_escape_string($db, $customer_id)
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));

		$connect_id = $_SESSION['p_name'];

		// unset($_SESSION['p_name']);
		// unset($_SESSION['p_kana']);
		// unset($_SESSION['p_year']);
		// unset($_SESSION['p_mouth']);
		// unset($_SESSION['p_day']);
		// unset($_SESSION['p_tel']);
		// unset($_SESSION['p_postal']);
		// unset($_SESSION['p_address1']);
		// unset($_SESSION['p_address2']);
		// unset($_SESSION['p_address3']);
		// unset($_SESSION['p_email1']);
		// unset($_SESSION['p_email2']);
		// unset($_SESSION['p_pass1']);
		// unset($_SESSION['p_pass2']);

		$_SESSION['verifi_url']  = "/verification-signup.php";
		$_SESSION['customer_id'] = $customer_id;
		// $_SESSION['connect_id']  = $connect_id;
		$_SESSION['PAY_SERVICE'] = SERVICE1;
		$_SESSION['CONNECT_PS']  = CONNECT_PASSWORD;

        header("Location: /paygent/");
        // header("Location:/complete-signup.php");
		exit();
	}
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<link rel="stylesheet" type="text/css" href="/common/css/style.css">
	<link rel="stylesheet" type="text/css" href="/common/css/other.css">
	<link rel="stylesheet" type="text/css" href="/common/css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP&display=swap" rel="stylesheet">
</head>
<body>
	<?php include_once('./common/header.php'); ?>
	<main id="searchFunction">
		<div class="otherScreen help">
			<div class="allWrapper">
				<div class="otherScreenInner">
					<h1>登録情報確認</h1>
				</div>
			</div>
		</div>
		<div class="otherContent" id="memberRegistation">
			<div class="allWrapper">
				<div class="otherContentInner loginWrap">
					<div class="loginBox">
						<div class="inputBox">
							<p>名前<span class="must">必須</span></p>
							<p class="verificationText"><?php if(!empty($_SESSION['p_name'])) {echo h($_SESSION['p_name']);} ?></p>
						</div>
						<div class="inputBox">
							<p>カタカナ</p>
							<p class="verificationText"><?php if(!empty($_SESSION['p_kana'])) {echo h($_SESSION['p_kana']);} ?></p>
						</div>
						<div class="inputBox">
							<p>生年月日<span class="must">必須</span></p>
							<p class="verificationText">
								<?php if(!empty($_SESSION['p_year'])) {echo h($_SESSION['p_year']);} ?>年
								<?php if(!empty($_SESSION['p_mouth'])) {echo h($_SESSION['p_mouth']);} ?>月
								<?php if(!empty($_SESSION['p_day'])) {echo h($_SESSION['p_day']);} ?>日
							</p>
						</div>
						<div class="inputBox">
                            <p>お電話番号※携帯番号か固定電話番号どちらか<span class="must">必須</span></p>
                            <p>携帯電話</p>
							<p class="verificationText"><?php if(!empty($_SESSION['p_tel'])) {echo h($_SESSION['p_tel']);} ?></p>
                            <p>固定電話番号</p>
							<p class="verificationText"><?php if(!empty($_SESSION['p_tel2'])) {echo h($_SESSION['p_tel2']);} ?></p>
						</div>
						<div class="inputBox">
							<p>住所<span class="must">必須</span></p>
							<div class="inputBoxAddress cf">
								<div class="addressBox post">
									<p class="verificationText"><?php echo $_SESSION['p_postal1']; ?>-<?php echo $_SESSION['p_postal2']; ?></p>
								</div>
								<?php if (!empty($_SESSION['p_address1'])) { ?>
								<div class="addressBox">
									<p class="verificationText"><?= h($_SESSION['p_address1']); ?></p>
								</div>
								<?php } ?>
								<?php if (!empty($_SESSION['p_address2'])) { ?>
								<div class="addressBox">
									<p class="verificationText"><?= h($_SESSION['p_address2']); ?></p>
								</div>
								<?php } ?>
								<?php if (!empty($_SESSION['p_address3'])) { ?>
								<div class="addressBox">
									<p class="verificationText"><?= h($_SESSION['p_address3']); ?></p>
								</div>
								<?php } ?>
							</div>
						</div>
						<div class="inputBox">
							<p>ログインID(メールアドレス)<span class="must">必須</span></p>
							<p class="verificationText"><?php if(!empty($_SESSION['p_email1'])) {echo h($_SESSION['p_email1']);} ?></p>
						</div>
						<!-- <div class="inputBox">
							<p>メールアドレス確認用<span class="must">必須</span></p>
							<p class="verificationText"><?php if(!empty($_SESSION['p_email2'])) {echo h($_SESSION['p_email2']);} ?></p>
						</div> -->
						<div class="inputBox">
							<p>パスワード<span class="must">必須</span></p>
							<p class="verificationText">**************</p>
						</div>
					</div>
					<div class="allWrapper" style="margin-bottom: 2em;display: none">
						<div id="tablePersonal" class="priceBox openTable">
							<div class="priceBoxInner">
								<table>
									<thead>
										<tr>
											<th colspan="2"><span>個人事業主様、法人様向け</span></th>
											<th>月額料金（税込）</th>
											<th>料金形態</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th>導入</th>
											<th>会員登録</th>
											<td class="text-center" rowspan="7">480円/月</td>
											<td class="text-center" rowspan="7">基本料金</td>
										</tr>
										<tr>
											<th rowspan="3">ツール操作</th>
											<th>顧客データの編集・一覧・出力</th>
										</tr>
										<tr>
											<th>お支払シミュレーション・出力</th>
										</tr>
										<tr>
											<th>見積書の作成・一覧・出力</th>
										</tr>
										<tr>
											<th rowspan="3">分割案内</th>
											<th>入金月の確認・一覧・出力</th>
										</tr>
										<tr>
											<th>請求書の作成・一覧・出力</th>
										</tr>
										<tr>
											<th>入金予定事前通知(メール)</th>
										</tr>
										<tr>
											<th rowspan="3">入金案内</th>
											<th>SMS</th>
											<td class="text-center">400円/月</td>
											<td class="text-center" rowspan="4">オプション</td>
										</tr>
										<tr>
											<th>SMS+自動音声案内</th>
											<td class="text-center">800円/月</td>
										</tr>
										<tr>
											<th>SMS+自動音声案内+オペレーター案内</th>
											<td class="text-center">1,000円/月</td>
										</tr>
										<tr>
											<th>チャット</th>
											<th>使用者問い合わせ対応</th>
											<td class="text-center">400円/月</td>
										</tr>
									</tbody>
								</table>
								<p style="font-size: 0.9em;">※ 「入金案内」および「チャット」機能については、ログイン後の画面で登録可能です。</p>
							</div>
						</div>
					</div>
					<div class="loginBox">
						<!-- <div class="inputBox">
							<p>パスワード確認用<span class="must">必須</span></p>
							<p class="verificationText">**************</p>
						</div> -->
						<div class="inputBox submitBox">
							<form action="" method="POST">
								<input type="submit" name="commit2" value="決済情報登録へ進む" style="cursor:pointer;">
							</form>
							<p class="text-center" style="font-weight: 700; padding-top: 2em;">基本料金 480円/月</p>
						</div>
					</div>
					<div class="loginOther">
						<?php if ($_SESSION['user_runFlag'] == 0) { ?>
							<a href="/personal-signup.php">編集画面に戻る</a>
						<?php } else { ?>
							<a href="/reregistration/personal-signup.php">編集画面に戻る</a>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</main>

	<?php include_once('./common/footer.php'); ?>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<script src="/common/js/footerFixed.js"></script>
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
	</script>
</body>
</html>