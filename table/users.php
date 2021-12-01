<?php
session_start();
require_once('./common/dbconnect.php');

$signError = "";
if ($_POST['passset2'] == "パスワード再設定") {

	$sql = sprintf('SELECT * FROM user WHERE (u_email="%s" OR p_email="%s") AND (delFlag=0 OR delFlag=2)',
		mysqli_real_escape_string($db, $_POST['email']),
		mysqli_real_escape_string($db, $_POST['email'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row0 = mysqli_fetch_assoc($record)) {
		// OK
		$rand = rand(10000000, 99999999);
		$mail_id = "sapo". $rand;

		$sql = sprintf('UPDATE `user` SET mail_id="%s", mailDate=NOW(),	delFlag=2 WHERE u_id="%d"',
			mysqli_real_escape_string($db, $mail_id),
			mysqli_real_escape_string($db, $row0['u_id'])
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));

		$to = $_POST['email'];
		$title = "【さぽかっぷ】（仮）パスワード再設定";
		$body  = $_POST['email']. " 様". "\r\n\r\n\r\n";
		$body .= "この度は、（仮）パスワード再設定、誠にありがとうございました。". "\r\n\r\n";
		$body .= "下記URLからパスワード再設定を行ってください。". "\r\n\r\n";
		$body .= "なお、10分以内に登録がなければ自動的に破棄されますので、". "\r\n";
		$body .= "お早めに手続きをお願いいたします。". "\r\n\r\n";
		$body .= "https://sapocup.jp/change-password.php?id=". $mail_id. "\r\n\r\n";

		$header  = "From:sapocup01@sapocup.jp". "\r\n";
		$header .= "Bcc:chankan77@gmail.com". "\r\n";
		$header .= "Bcc:nakazawa6097@gmail.com". "\r\n";

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
	<meta name="format-detection" content="telephone=no">
	<title></title>
	<link rel="stylesheet" type="text/css" href="common/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="common/css/style.css">
</head>
<body class="blue_bg">
	<!-- グローバルナビ -->
	<header id="header">
		<div class="container">
			<nav class="navbar navbar-expand-md navbar-light nav_custom fixed-top">
				<a href="/" class="navbar-brand"><img src="/common/img/logo.jpg" alt=""></a>
				<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#menu" aria-controls="Navber" aria-expanded="false" aria-label="ナビゲーションの切替">
				    <span class="navbar-toggler-icon"></span>
				</button>
			    <div id="menu" class="collapse navbar-collapse justify-content-end">
			        <ul class="navbar-nav">
			            <li class="nav-item"><a class="nav-link" href="">機能</a></li>
			            <li class="nav-item"><a class="nav-link" href="/signup.php">新規申込</a></li>
			            <li class="nav-item"><a class="nav-link" href="/contact.php">お問い合わせ</a></li>
			            <li id="nav_login" class="nav-item nav_attention"><a class="nav-link" href="/login.php">ログイン</a></li>
			            <li id="nav_specific" class="nav-item nav_attention"><a class="nav-link" href="">特定取引業社様</a></li>
			        </ul>
			    </div>
			</nav>
		</div>
	</header>
	<main>
		<section class="top_login input_area">
			<div class="content_title">
				<h1>パスワードリセット</h1>
			</div>
			<div class="contact_wrap input_wrap">
				<form action="" method="post" class="form-horizontal">
					<?php if ($signError == "failEmail") { ?>
					<div class="form-group">
						<font color="#f05b72">　　メールアドレスが一致しません。</font>
					</div>
					<?php } ?>
					<div class="form-group">
						<div class="col-12">
							<input type="text" class="form-control" name="email" placeholder="E-mail入力">
						</div>
					</div>
					<div class="form-group text-center">
						<div class="submit_button">
							<input type="submit" name="passset2" value="パスワード再設定">
						</div>
					</div>
				</form>
			</div>
		</section>
	</main>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script>
      $(function() {
        'use strict';
        $('[data-toggle="tooltip"]').tooltip();
      });

    </script>
</body>
</html>