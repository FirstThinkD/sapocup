<?php
if ($_POST['login2'] == "送信") {
	print_r($_POST);
	echo "<br>";
	$kaisu = $_POST['kaisu'];
	echo $_POST['goukei']. " / ". $kaisu. " = ". ($_POST['goukei'] / $kaisu). "<br>";
	$skei = ($_POST['goukei'] / $kaisu);
	echo "skei = ". $skei. "<br>";

	// echo "round1=". round($kei, 1). "<br>";
	// echo "round2=". round($kei, 2). "<br>";
	// echo "round3=". round($kei, 3). "<br>";
	// echo "ceil=". ceil($kei). "<br>";

	$floo = floor($skei);
	echo "floo = ". $floo. "<br>";
	$hasu = ($skei - $floo);
	echo "floo - skei = ". $hasu. "<br>";

	// echo "合計：". $floo. " * ". $kaisu. " = ". ($floo * $kaisu). "<br>";
	echo "合計：". (($floo * $kaisu) + ($hasu * $kaisu)). "<br>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>テキストエリアの行数を数える</title>
	<script type="text/javascript"></script>
</head>
<body>
	<form action="" method="post">
		<p>回数：<input type="test" name="kaisu" value="48"></p>
		<p>合計：<input type="test" name="goukei" value="3000"></p>
		<input type="submit" name="login2" value="送信" style="cursor:pointer;">
	</form>
</body>
</html>