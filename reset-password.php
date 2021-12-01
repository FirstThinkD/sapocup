<?php
session_start();
require_once(__DIR__ . '/common/dbconnect.php');

$signError = "";
if (!empty($_POST['passset2']) && $_POST['passset2'] == "パスワード再設定") {
	$sql = sprintf('SELECT * FROM user WHERE (u_email="%s" OR p_email="%s")',
		mysqli_real_escape_string($db, $_POST['email']),
		mysqli_real_escape_string($db, $_POST['email'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$ix = 0;
	while ($row0 = mysqli_fetch_assoc($record)) {
		$u_id[$ix]    = $row0['u_id'];
		$exeFlag[$ix] = $row0['exeFlag'];
		$delFlag[$ix] = $row0['delFlag'];
		$ix++;
	}
	$user_count = $ix;

	if ($user_count != 0) {
		// OK
		$rand = rand(10000000, 99999999);
		$mail_id = "sapo". $rand;

		if ($user_count == 1) {
			$sql = sprintf('UPDATE `user` SET mail_id="%s", mailDate=NOW(), delFlag=3 WHERE u_id="%d"',
				mysqli_real_escape_string($db, $mail_id),
				mysqli_real_escape_string($db, $u_id[0])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		} else {
			$sql = sprintf('UPDATE `user` SET mail_id="%s", mailDate=NOW() WHERE (u_email="%s" OR p_email="%s") AND (delFlag=0 OR delFlag=3)',
				mysqli_real_escape_string($db, $mail_id),
				mysqli_real_escape_string($db, $_POST['email']),
				mysqli_real_escape_string($db, $_POST['email'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		}

		$to = $_POST['email'];
		$title = "【さぽかっぷ】パスワード再設定";
		$body  = $_POST['email']. " 様". "\r\n\r\n\r\n";
		$body .= "この度は、パスワード再設定、誠にありがとうございました。". "\r\n\r\n";
		$body .= "下記URLからパスワード再設定を行ってください。". "\r\n\r\n";

		for($ix=0; $ix<$user_count; $ix++) {
			if ($exeFlag[$ix] == 0) {
				$body .= "ご利用中の会員番号：". $u_id[$ix]. "\r\n";
			} else {
				$body .= "ご退会済の会員番号：". $u_id[$ix]. "\r\n";
			}
		}
		$body .= "\r\n";

		$body .= "なお、10分以内に登録がなければ自動的に破棄されますので、". "\r\n";
		$body .= "お早めに手続きをお願いいたします。". "\r\n\r\n";
		$body .= "https://sapocup.jp/change-password.php?id=". $mail_id. "\r\n\r\n";

		$header  = "From:sapocup01@sapocup.jp". "\r\n";
		$header .= "Bcc:sapocup01@sapocup.jp". "\r\n";
		// $header .= "Bcc:chankan77@gmail.com". "\r\n";
		// $header .= "Bcc:nakazawa6097@gmail.com". "\r\n";

		mb_language('ja');
		mb_internal_encoding('UTF-8');
        mb_send_mail($to, $title, $body, $header);

		header("Location:/new-password.php");
		exit();
	}
	$signError = "failEmail";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<link rel="stylesheet" type="text/css" href="/common/css/other.css">
	<link rel="stylesheet" type="text/css" href="/common/css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP&display=swap" rel="stylesheet">
</head>
<body class="blue_bg">
	<?php include_once('./common/header.php'); ?>

	<main id="searchFunction">
		<div class="otherScreen help">
			<div class="allWrapper">
				<div class="otherScreenInner">
					<h1>パスワードリセット</h1>
				</div>
			</div>
		</div>
		<div class="otherContent">
			<div class="allWrapper">
				<div class="otherContentInner loginWrap">
					<div class="loginBox">
						<form action="" method="post">
							<?php if ($signError == "failEmail") { ?>
							<font color="#f05b72">メールアドレスが一致しません。</font>
							<br><br>
							<?php } ?>
							<div class="inputBox">
								<input type="email" class="form-control" name="email" placeholder="E-mail入力" required>
							</div>
							<div class="inputBox submitBox">
								<input type="submit" name="passset2" value="パスワード再設定" style="cursor:pointer;">
							</div>
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
		'use strict';
		// ハンバーガーメニュー
		$('#nav-input').on('change',function(){
			if ($(this).prop('checked')) {
				$('#nav-content').addClass('navOpen');
			} else {
				$('#nav-content').removeClass('navOpen');
			}
		});
		$(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});
    </script>
</body>
</html>