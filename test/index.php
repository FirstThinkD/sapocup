<?php
if ($_POST['send2'] == "申し込み") {
	header("Location:/test/payment.php");
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>フォームテスト</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache, no-store">
	</head>
	<body>
		<form method="post" action="">
			<input type="hidden" name="trading_id" value="21" />
			<input type="hidden" name="id" value="1000">
			<input type="hidden" name="seq_merchant_id" value="40011">
			<input type="hidden" name="customer_id" value="323232">
			<input type="hidden" name="hc" value="3af577f759bad7853b06069383522c166886d3c1a95075a60bbdab3553489f02c292c" /> <!-- 改ざん防止用のハッシュ値 -->
			<input type="submit" name="send2" value="申し込み" />
		</form>
	</body>
</html>


