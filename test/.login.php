<?php
session_start();
require_once('../common/dbconnect.php');

$signError = "";
if (!empty($_POST['login2']) && $_POST['login2'] == "ログイン") {
	if ($_POST['passwd'] != "12346789") {
		exit();
	}

	$passwd = md5($_POST['passwd']);

	$sql = sprintf('SELECT * FROM user WHERE u_id="%d"',
		mysqli_real_escape_string($db, $_POST['loginID'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row0 = mysqli_fetch_assoc($record)) {

		// OK
		$_SESSION['loginID']   = $row0['u_id'];
		if ($row0['u_type'] == "法人") {
			$_SESSION['loginName'] = $row0['u_company'];
		} else {
			$_SESSION['loginName'] = $row0['p_name'];
		}
		$_SESSION['data_ymd'] = date('Ymd');
		$_SESSION['service5_chat'] = $row0['service5_fl'];
		header("Location:/manage/data/");
		exit();
	}
	$signError = "failEmail";
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
</head>
<body>
	<?php include_once('../common/header.php'); ?>
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
							<?php } ?>
							<div class="inputBox">
								<input type="text" class="form-control" name="loginID" placeholder="ID入力" required>
							</div>
							<div class="inputBox">
								<input type="password" class="form-control" name="passwd" placeholder="Password入力" required>
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
	<?php include_once('../common/footer.php'); ?>
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