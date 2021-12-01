<?php
session_start();
require_once(__DIR__ . '/common/dbconnect.php');
$expire_timer = 60;		// 60秒

if (empty($_SESSION['loginEmail']) || empty($_SESSION['loginPassw'])) {
	header("Location:/");
	exit();
}

$signError = "";
if ($_POST['login2'] == "決定") {
	$sql = sprintf('SELECT * FROM user WHERE u_id="%d"',
		mysqli_real_escape_string($db, $_POST['user_id'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row0 = mysqli_fetch_assoc($record)) {
		// OK
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
	} else {
		header("Location:/");
		exit();
	}
}
$passwd = md5($_SESSION['loginPassw']);

$sql = sprintf('SELECT * FROM user WHERE (u_email="%s" OR p_email="%s") AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginEmail']),
	mysqli_real_escape_string($db, $_SESSION['loginEmail'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$ix = 0;
while ($row0 = mysqli_fetch_assoc($record)) {
	$u_id[$ix] = $row0['u_id'];
	$ix++;
}
$user_cont = $ix;
//echo "loginCount=". $_SESSION['loginCount']. " user_cont=". $user_cont;
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
							<div class="inputBox2">
								<p class="title">会員番号</p>
								<div class="birthBox2 year">
								<span class="birth_input select_box">
									<select name="user_id">
										<?php for($ix=0; $ix<$user_cont; $ix++) { ?>
											<option value="<?php echo $u_id[$ix]; ?>"><?php echo $u_id[$ix]; ?></option>
										<?php } ?>
									</select>
								</span>
								</div>
							</div>
							<div class="inputBox submitBox">
								<input type="submit" name="login2" value="決定" style="cursor:pointer;">
							</div>
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