<?php
session_start();
require_once('../../common/dbconnect.php');

if ($_SESSION['loginID'] == "") {
	header("Location:/");
	exit();
}

if ($_GET['id'] != "") {
	$sql = sprintf('SELECT * FROM `customer` WHERE c_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $_GET['id'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row0 = mysqli_fetch_assoc($record)) {
		$sql = sprintf('UPDATE `customer` SET delFlag=1 WHERE c_id="%d"',
			mysqli_real_escape_string($db, $_GET['id'])
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));
	}

	$_SESSION['s_item'] = "顧客一覧";
	header("Location:/manage/search/");
	exit();
}
header("Location:/");
exit();
?>
