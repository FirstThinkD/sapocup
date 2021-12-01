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
					<h1>パスワード再設定用メール送信</h1>
				</div>
			</div>
		</div>
		<div class="otherContent">
			<div class="allWrapper">
				<div class="otherContentInner loginWrap">
					<div class="loginBox">
						<div class="inputBox">
							<p>再設定用のURLリンクをメールアドレスに送信しました。<br>10分以内に送信したURLをクリックし、パスワードの再設定を行ってください。</p>
						</div>
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
    </script>
</body>
</html>