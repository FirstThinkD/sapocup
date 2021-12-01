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

	$sql = sprintf('DELETE FROM `simulation` WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	mysqli_query($db, $sql) or die(mysqli_error($db));

	header("Location:/manage/estimates/");
	exit();
}
?>
