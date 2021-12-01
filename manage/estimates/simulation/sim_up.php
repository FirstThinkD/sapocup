<?php
session_start();
require(__DIR__ . '/../../../common/dbconnect.php');
require(__DIR__ . '/sim_func.php');
require(__DIR__ . '/sim_func2.php');
// require(__DIR__ . '/../new/functions.php');

if ($_SESSION['loginID'] == "") {
	header("Location:/");
	exit();
}

if ($_GET['id'] != "" && $_GET['qu_id'] != "") {
	// print_r($_GET);

	$qu_id = $_GET['qu_id'];

	$sql = sprintf('UPDATE `simulation` SET pt_number="%d" WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $_GET['id']),
		mysqli_real_escape_string($db, $qu_id)
	);
	mysqli_query($db,$sql) or die(mysqli_error($db));

	$sql = sprintf('SELECT * FROM `simulation` WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row7 = mysqli_fetch_assoc($record);

	if ($_GET['id'] == 1) {
		$qu_deposit       = $row7['pt1_deposit'];
		$qu_price         = $row7['qu_price'];
		$qu_commission    = $row7['pt1_commission'];
		$qu_commit        = $row7['pt1_commit'];
		$qu_initPayAmount = $row7['pt1_initPayAmount'];
		$qu_amount_pay    = $row7['pt1_amount_pay'];
		$qu_installments  = $row7['pt1_installments'];
		$qu_startDate     = $row7['qu_startDate'];
		$q_alltotal       = $row7['pt1_alltotal'];
	} else if ($_GET['id'] == 2) {
		$qu_deposit       = $row7['pt2_deposit'];
		$qu_price         = $row7['qu_price'];
		$qu_commission    = $row7['pt2_commission'];
		$qu_commit        = $row7['pt2_commit'];
		$qu_initPayAmount = $row7['pt2_initPayAmount'];
		$qu_amount_pay    = $row7['pt2_amount_pay'];
		$qu_installments  = $row7['pt2_installments'];
		$qu_startDate     = $row7['qu_startDate'];
		$q_alltotal       = $row7['pt2_alltotal'];
	} else {
		$qu_deposit       = $row7['pt3_deposit'];
		$qu_price         = $row7['qu_price'];
		$qu_commission    = $row7['pt3_commission'];
		$qu_commit        = $row7['pt3_commit'];
		$qu_initPayAmount = $row7['pt3_initPayAmount'];
		$qu_amount_pay    = $row7['pt3_amount_pay'];
		$qu_installments  = $row7['pt3_installments'];
		$qu_startDate     = $row7['qu_startDate'];
		$q_alltotal       = $row7['pt3_alltotal'];
	}

	$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row3 = mysqli_fetch_assoc($record);

	$ptn_out = partition($row3['qu_paymentDate'], $row7['qu_startDate'], $qu_installments, $q_alltotal, $qu_deposit, $qu_initPayAmount);
	$qu_endDate = date('Y-m-d', strtotime($ptn_out[($ptn_out[0][5] - 1)][6]));

	$sql = sprintf('UPDATE `quotation` SET qu_deposit="%s",
		qu_price="%s", qu_commission="%s", qu_commit="%s",
		qu_initPayAmount="%s", qu_amount_pay="%s", qu_installments="%s",
		qu_startDate="%s", qu_endDate="%s", q_alltotal="%s" WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_deposit),
		mysqli_real_escape_string($db, $qu_price),
		mysqli_real_escape_string($db, $qu_commission),
		mysqli_real_escape_string($db, $qu_commit),
		mysqli_real_escape_string($db, $qu_initPayAmount),
		mysqli_real_escape_string($db, $qu_amount_pay),
		mysqli_real_escape_string($db, $qu_installments),
		mysqli_real_escape_string($db, $qu_startDate),
		mysqli_real_escape_string($db, $qu_endDate),
		mysqli_real_escape_string($db, $q_alltotal),
		mysqli_real_escape_string($db, $qu_id)
	);
	mysqli_query($db, $sql) or die(mysqli_error($db));

	$sql = sprintf('DELETE FROM `data_quo` WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	mysqli_query($db, $sql) or die(mysqli_error($db));

	$sql = sprintf('DELETE FROM `data_opt` WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	mysqli_query($db, $sql) or die(mysqli_error($db));

	cust_dataUP2($qu_id);

	// $_SESSION['sim_err'] = "UPOK";
	header("Location:/manage/estimates/detail/?id=". $qu_id);
	exit();
} else {
	header("Location:/");
	exit();
}
?>