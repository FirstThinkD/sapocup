<?php
session_start();
require_once('../../common/dbconnect.php');

if ($_GET['customer_id'] != "" && $_GET['customer_card_id'] != "") {
	if ($_SESSION['loginID'] != $_GET['customer_id']) {
		header("Location:/");
		exit();
	}

	$sql = sprintf('SELECT * FROM user WHERE customer_id="%s"',
		mysqli_real_escape_string($db, $_GET['customer_id'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row0 = mysqli_fetch_assoc($record)) {
		if ($row0['service_exe'] == "service1") {
			$sql = sprintf('UPDATE `user` SET service1_fl=1,
				service1_id="%s", service1_dt=NOW() WHERE customer_id="%s"',
				mysqli_real_escape_string($db, $_GET['customer_card_id']),
				mysqli_real_escape_string($db, $_GET['customer_id'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		} else if ($row0['service_exe'] == "service2") {
			$sql = sprintf('UPDATE `user` SET service2_fl=1,
				service2_id="%s", service2_dt=NOW() WHERE customer_id="%s"',
				mysqli_real_escape_string($db, $_GET['customer_card_id']),
				mysqli_real_escape_string($db, $_GET['customer_id'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		} elseif ($row0['service_exe'] == "service3") {
			$sql = sprintf('UPDATE `user` SET service3_fl=1,
				service3_id="%s", service3_dt=NOW() WHERE customer_id="%s"',
				mysqli_real_escape_string($db, $_GET['customer_card_id']),
				mysqli_real_escape_string($db, $_GET['customer_id'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		} else {
			$sql = sprintf('UPDATE `user` SET service4_fl=1,
				service4_id="%s", service4_dt=NOW() WHERE customer_id="%s"',
				mysqli_real_escape_string($db, $_GET['customer_card_id']),
				mysqli_real_escape_string($db, $_GET['customer_id'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		}
		$_SESSION['servic_eexec'] = "service_comp";
		header("Location:/manage/user/");
		exit();
	} else {
		// customer_idが不一致
		header("Location:/");
		exit();
	}
} else {
	header("Location:/");
	exit();
}
?>
