<?php
session_start();
require_once(__DIR__ . '/../../../common/dbconnect.php');
require_once(__DIR__ . '/../../common/functions.php');

if (empty($_SESSION['loginID'])) {
	header("Location:/login.php");
	exit();
}

if (empty($_GET['id'])) {
	header("Location:/");
	exit();
} else {
	//　送られたIDを取得
	$qu_id = $_GET['id'];

	$sql = sprintf('UPDATE `quotation` SET delFlag=1 WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	mysqli_query($db, $sql) or die(mysqli_error($db));

	$sql = sprintf('UPDATE `q_items` SET delFlag=1 WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	mysqli_query($db, $sql) or die(mysqli_error($db));

	$sql = sprintf('UPDATE `q_memo` SET delFlag=1 WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	mysqli_query($db, $sql) or die(mysqli_error($db));

	$sql = sprintf('UPDATE `data_quo` SET delFlag=1 WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	mysqli_query($db, $sql) or die(mysqli_error($db));

	$sql = sprintf('UPDATE `data_opt` SET delFlag=1 WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	mysqli_query($db, $sql) or die(mysqli_error($db));

	$sql = sprintf('UPDATE `simulation` SET delFlag=1 WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	mysqli_query($db, $sql) or die(mysqli_error($db));

	header("Location:/manage/invoices/");
	exit();
}
?>
