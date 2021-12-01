<?php
session_start();
require_once('./common/dbconnect.php');
// $five_min = 300;	//  5分
$five_min = 600;	// 10分

$signError = "";
if (!empty($_POST['passset3']) && $_POST['passset3'] == "パスワードを登録") {
	if ($_POST['passwd1'] != $_POST['passwd2']) {
		$signError = "failPass";
	}

	if ($signError == "") {
		$now_date = date("Y-m-d H:i:s");
		$now_time = strtotime($now_date);

		// echo "chk_time=". $chk_time. " now_time=". $now_time;

		if ($now_time > $_SESSION['chk_time']) {
			$signError = "failOver";
		}

		if ($signError == "") {
			$passwd = md5($_POST['passwd1']);

			$sql = sprintf('SELECT * FROM `user` WHERE (u_email="%s" OR p_email="%s") AND (u_pass="%s" OR p_pass="%s") AND delFlag=3',
				mysqli_real_escape_string($db, $_POST['s_email']),
				mysqli_real_escape_string($db, $_POST['s_email']),
				mysqli_real_escape_string($db, $passwd),
				mysqli_real_escape_string($db, $passwd)
			);
			$record = mysqli_query($db, $sql) or die(mysqli_error($db));
			if ($row0 = mysqli_fetch_assoc($record)) {
				// パスワード重複
				$signError = "passNG";
			} else {
				$sql = sprintf('SELECT * FROM user WHERE u_id="%d" AND delFlag=3',
					mysqli_real_escape_string($db, $_SESSION['Loing_user_id'])
				);
				$record = mysqli_query($db, $sql) or die(mysqli_error($db));
				if ($row0 = mysqli_fetch_assoc($record)) {
					// OK
					if ($row0['u_type'] == "法人") {
						$sql = sprintf('UPDATE user SET u_pass="%s", delFlag=0 WHERE u_id="%s"',
							mysqli_real_escape_string($db, $passwd),
							mysqli_real_escape_string($db, $row0['u_id'])
						);
						mysqli_query($db, $sql) or die(mysqli_error($db));
					} else {
						$sql = sprintf('UPDATE user SET p_pass="%s", delFlag=0 WHERE u_id="%s"',
							mysqli_real_escape_string($db, $passwd),
							mysqli_real_escape_string($db, $row0['u_id'])
						);
						mysqli_query($db, $sql) or die(mysqli_error($db));
					}

					if ($row0['u_type'] == "法人") {
						$to = $row0['u_email'];
						$user_name = $row0['u_company'];
					} else {
						$to = $row0['p_email'];
						$user_name = $row0['p_name'];
					}
					$_SESSION['Loing_user_id'] = "";

					$title = "【さぽかっぷ】パスワード変更完了のお知らせ";
					$body  = "※このメールはシステムからの自動返信です". "\r\n\r\n";
					$body .= $user_name. " 様". "\r\n\r\n";
					$body .= "「さぽかっぷ」運営企業の株式会社ANBIENCEでございます。". "\r\n";
					$body .= "いつも、「さぽかっぷ」をご利用いただき誠にありがとうございます。". "\r\n";
					$body .= "パスワードの変更手続きは、完了いたしました。". "\r\n\r\n";
					$body .= "このメールは、ご登録時に確認のため送信させていただいております。". "\r\n";
					$body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━". "\r\n";
					$body .= "■ご登録いただいた会員ID". "\r\n";
					$body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━". "\r\n";
					$body .= "ログインID：". $to. "\r\n";
					$body .= "会員番号　：". $row0['u_id']. "\r\n\r\n";
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

					mb_language('ja');
					mb_internal_encoding('UTF-8');
					mb_send_mail($to, $title, $body, $header);

					header("Location:/complete-password.php");
					exit();
				}
			}
		}
	}
}

if ($_SESSION['Loing_user_id'] != "") {
	$sql = sprintf('SELECT * FROM user WHERE u_id="%d" AND delFlag=3',
		mysqli_real_escape_string($db, $_SESSION['Loing_user_id'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row0 = mysqli_fetch_assoc($record)) {
		// OK
		if ($row0['u_type'] == "法人") {
			$s_email = $row0['u_email'];
		} else {
			$s_email = $row0['p_email'];
		}

		$now_date = date("Y-m-d H:i:s");
		$now_time = strtotime($now_date);

		$chk_time = strtotime($row0['mailDate']) + $five_min;
		$_SESSION['chk_time'] = $chk_time;
		// echo "chk_time=". $chk_time. " now_time=". $now_time;

		if ($now_time > $chk_time) {
			$signError = "failOver";
		}
	} else {
		$signError = "failEmail";
	}
} else {
	header("Location:/");
	exit();
}
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
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript">
	<!--
		$(function() {
			$('#passcheck1').change(function(){
				if ( $(this).prop('checked') ) {
					$('#password1').attr('type','text');
				} else {
					$('#password1').attr('type','password');
				}
			});
			$('#passcheck2').change(function(){
				if ( $(this).prop('checked') ) {
					$('#password2').attr('type','text');
				} else {
					$('#password2').attr('type','password');
				}
			});
		});
	// -->
	</script>
</head>
<body>
	<?php include_once('./common/header.php'); ?>
	<main id="searchFunction">
		<div class="otherScreen help">
			<div class="allWrapper">
				<div class="otherScreenInner">
					<h1>パスワード再設定</h1>
				</div>
			</div>
		</div>
		<div class="otherContent">
			<div class="allWrapper">
				<div class="otherContentInner loginWrap">
					<div class="loginBox">
						<form action="" method="post" accept-charset="utf-8">
							<?php if ($signError == "failEmail") { ?>
								<font color="#f05b72">　　idが一致しません。</font>
								<br><br>
							<?php } ?>
							<?php if ($signError == "failPass") { ?>
								<font color="#f05b72">　　パスワードが一致しません。</font>
								<br><br>
							<?php } ?>
							<?php if ($signError == "passNG") { ?>
								<font color="#f05b72">パスワードが重複しています。<br>パスワードを変更してください。</font>
								<br><br>
							<?php } ?>
							<?php if ($signError == "failOver") { ?>
								<font color="#f05b72">10分以上が経過しました。<br>再度、「パスワードリセット」を行ってください。</font>
								<br><br>
							<?php } ?>
							<input type="hidden" name="s_email" value="<?php echo $s_email; ?>">
							<?php if ($signError != "failOver") { ?>
								<div class="inputBox">
									<input type="password" id="password1" class="form-control" name="passwd1" minlength="8" placeholder="パスワードを入力(半角英数字８桁)" required>
									<input type="checkbox" id="passcheck1" /> パスワードを表示
								</div>
								<div class="inputBox">
									<input type="password" id="password2" class="form-control" name="passwd2" minlength="8" placeholder="確認用パスワードを入力(半角英数字８桁)" required>
									<input type="checkbox" id="passcheck2" /> パスワードを表示
								</div>
								<div class="inputBox submitBox">
									<input type="submit" name="passset3" value="パスワードを登録" style="cursor:pointer;">
								</div>
							<?php } ?>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php include_once('./common/footer.php'); ?>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<script src="/common/js/footerFixed.js"></script>
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