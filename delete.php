<?php
session_start();
require_once('./common/dbconnect.php');

if ($_SESSION['delete_flag'] == "ON") {
	$_SESSION['delete_flag'] = "";

	$sql = sprintf('SELECT * FROM user WHERE delFlag=2');
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row0 = mysqli_fetch_assoc($record)) {
		$sql = sprintf('DELETE FROM user WHERE u_id="%d" AND delFlag=2',
			mysqli_real_escape_string($db, $row0['u_id'])
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));

		header("Location:/signup.php");
		exit();
	}
}
$_SESSION['delete_flag'] = "";
header("Location:/");
exit();
?>