<?php
session_start();
require_once(__DIR__ . '/common/dbconnect.php');
$expire_timer = 60;		// 60秒

$signError = "";
if (!empty($_POST['login2']) && $_POST['login2'] == "ログイン") {
	$passwd = md5($_POST['passwd']);

	$sql = sprintf('SELECT COUNT(*) AS user_cont FROM user WHERE (u_email="%s" OR p_email="%s") AND (u_pass="%s" OR p_pass="%s") AND delFlag=0',
		mysqli_real_escape_string($db, $_POST['loginID']),
		mysqli_real_escape_string($db, $_POST['loginID']),
		mysqli_real_escape_string($db, $passwd),
		mysqli_real_escape_string($db, $passwd)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row0 = mysqli_fetch_assoc($record);
	if ($row0['user_cont'] == 1) {
		$sql = sprintf('SELECT * FROM user WHERE (u_email="%s" OR p_email="%s") AND (u_pass="%s" OR p_pass="%s") AND delFlag=0',
			mysqli_real_escape_string($db, $_POST['loginID']),
			mysqli_real_escape_string($db, $_POST['loginID']),
			mysqli_real_escape_string($db, $passwd),
			mysqli_real_escape_string($db, $passwd)
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		while($row0 = mysqli_fetch_assoc($record)) {
			// OK
			$sql = sprintf('UPDATE `user` SET pass_count="0" WHERE u_id="%d"',
				mysqli_real_escape_string($db, $row0['u_id'])
			);
			mysqli_query($db, $sql) or die(mysqli_error($db));
			$_SESSION['loginID'] = $row0['u_id'];
			if ($row0['u_type'] == "法人") {
				$_SESSION['loginName'] = $row0['u_company'];
				$_SESSION['loginMail'] = $row0['u_email'];
			} else {
				$_SESSION['loginName'] = $row0['p_name'];
				$_SESSION['loginMail'] = $row0['p_email'];
			}
			$_SESSION['data_ymd'] = date('Ymd');
			$_SESSION['service5_chat'] = $row0['service5_fl'];
			$_SESSION['user_type']     = $row0['u_type'];
			$_SESSION['user_exeFlag']  = $row0['exeFlag'];
			$_SESSION['user_runFlag']  = 1;
			header("Location:/manage/data/");
			exit();
		}
	} else if ($row0['user_cont'] == 0) {
		// ログイン失敗
		$sql = sprintf('SELECT * FROM `user` WHERE (u_email="%s" OR p_email="%s") AND delFlag=0',
			mysqli_real_escape_string($db, $_POST['loginID']),
			mysqli_real_escape_string($db, $_POST['loginID'])
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		if ($row0 = mysqli_fetch_assoc($record)) {
			// EmailチェックOK
			$pass_count = $row0['pass_count'] + 1;
			if ($pass_count <= 5) {
				$sql = sprintf('UPDATE `user` SET pass_count="%d" WHERE u_id="%d"',
					mysqli_real_escape_string($db, $pass_count),
					mysqli_real_escape_string($db, $row0['u_id'])
				);
				mysqli_query($db, $sql) or die(mysqli_error($db));
			} else {
				$now_time = strtotime(date("Y-m-d H:i:s"));
				$chk_time = strtotime($row0['updated']) + $expire_timer;
				if ($now_time > $chk_time) {
					$sql = sprintf('UPDATE `user` SET pass_count=1 WHERE u_id="%d"',
						mysqli_real_escape_string($db, $row0['u_id'])
					);
					mysqli_query($db, $sql) or die(mysqli_error($db));
				} else {
					$signError = "failPass";
				}
			}
		}
	} else {
		$_SESSION['loginCount'] = $row0['user_cont'];
		$_SESSION['loginEmail'] = $_POST['loginID'];
		$_SESSION['loginPassw'] = $_POST['passwd'];
		header("Location:/login-select.php");
		exit();
	}
	if ($signError == "") {
		$signError = "failEmail";
	}
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
			$('#passcheck').change(function(){
				if ( $(this).prop('checked') ) {
					$('#password').attr('type','text');
				} else {
					$('#password').attr('type','password');
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
					<h1>ログイン</h1>
				</div>
			</div>
		</div>
		<div class="otherContent">
			<div class="allWrapper">
				<form action="" method="post" accept-charset="utf-8">
					<div class="otherContentInner loginWrap">
						<div class="loginBox">
							<?php if ($signError == "failEmail") { ?>
								<font color="#f05b72">メールアドレスまたはパスワードが一致しません。</font>
								<br><br>
							<?php } ?>
							<?php if ($signError == "failPass") { ?>
								<font color="#f05b72" style="white-space: nowrap">5回連続でログインに失敗したため、1分間のログイン制限がかかります。</font>
								<br><br>
							<?php } ?>
							<div class="inputBox">
								<input type="text" class="form-control" name="loginID" placeholder="ID（メールアドレス）入力" required>
							</div>
							<div class="inputBox">
								<input type="password" id="password" class="form-control" name="passwd" placeholder="Password入力" required>
								<input type="checkbox" id="passcheck" /> パスワードを表示
							</div>
							<div class="inputBox inputPass">
								<input type="checkbox">パスワードを保存する
							</div>
							<div class="inputBox submitBox">
								<input type="submit" name="login2" value="ログイン" style="cursor:pointer;">
							</div>
							<a href="/reset-password.php">パスワードをお忘れの方はこちら</a>
						</div>
						<div class="loginOther">
							<a href="/signup.php">新規登録</a>
						</div>
					</div>
				</form>
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

		$(function(){
			$("input").on("keydown", function(e) {
				if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
					return false;
				} else {
					return true;
				}
			});
		});
	</script>
</body>
</html>