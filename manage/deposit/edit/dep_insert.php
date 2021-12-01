<?php
session_start();
require_once(__DIR__ . '/../../../common/dbconnect.php');
require_once(__DIR__ . '/../../common/functions.php');
require_once(__DIR__ . '/../func_dep.php');

if (empty($_SESSION['loginID'])) {
	header("Location:/");
	exit();
}

if (empty($_GET['id'])) {
	header("Location:/");
	exit();
} else {
	$qu_id = $_GET['id'];
}

$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
if ($row1 = mysqli_fetch_assoc($record)) {
	// OK
	$_SESSION['dep_err'] = "UPOK";

	if ($_GET['dl_id'] == 0) {
		$erase_money    = 0;
		$erase_money_mi = 0;
		list($erase_money, $erase_money_mi) = erase_get($qu_id, $row1['q_alltotal'], $row1['erase_money'], $row1['erase_money_mi']);

		$sql = sprintf('INSERT INTO `dep_list` SET qu_id="%d",
				dl_yymmdd="%s", dl_money="%s", dl_comment="%s"',
			mysqli_real_escape_string($db, $qu_id),
			mysqli_real_escape_string($db, $_GET['yymmdd']),
			mysqli_real_escape_string($db, $_GET['money']),
			mysqli_real_escape_string($db, $_GET['comment'])
		);
		mysqli_query($db, $sql) or die(mysqli_error($db));

		$erase_money    = $erase_money    + $_GET['money'];
		$erase_money_mi = $erase_money_mi - $_GET['money'];

		$sql = sprintf('UPDATE `quotation` SET erase_money="%s", erase_money_mi="%s", notice_flag=0  
				WHERE qu_id="%d"',
			mysqli_real_escape_string($db, $erase_money),
			mysqli_real_escape_string($db, $erase_money_mi),
			mysqli_real_escape_string($db, $qu_id)
		);
		mysqli_query($db, $sql) or die(mysqli_error($db));

		// $sql = sprintf('COMMIT');
		// mysqli_query($db, $sql) or die(mysqli_error($db));
	} else {
		$sql = sprintf('UPDATE `dep_list` SET dl_yymmdd="%s",
				dl_money="%s", dl_comment="%s" WHERE dl_id="%d"',
			mysqli_real_escape_string($db, $_GET['yymmdd']),
			mysqli_real_escape_string($db, $_GET['money']),
			mysqli_real_escape_string($db, $_GET['comment']),
			mysqli_real_escape_string($db, $_GET['dl_id'])
		);
		mysqli_query($db, $sql) or die(mysqli_error($db));

		$erase_money    = 0;
		$erase_money_mi = 0;
		list($erase_money, $erase_money_mi) = erase_get($qu_id, $row1['q_alltotal'], $row1['erase_money'], $row1['erase_money_mi']);

		$sql = sprintf('UPDATE `quotation` SET erase_money="%s", erase_money_mi="%s", notice_flag=0  
				WHERE qu_id="%d"',
			mysqli_real_escape_string($db, $erase_money),
			mysqli_real_escape_string($db, $erase_money_mi),
			mysqli_real_escape_string($db, $qu_id)
		);
		mysqli_query($db, $sql) or die(mysqli_error($db));

		// $sql = sprintf('COMMIT');
		// mysqli_query($db, $sql) or die(mysqli_error($db));
	}
} else {
	$_SESSION['dep_err'] = "UPNG";
}

// echo "erase_money=". $erase_money. " erase_money_mi=". $erase_money_mi. "<br>";
// print_r($_GET);
// exit();

header("Location:/manage/deposit/edit/?id=". $_GET['id']);
exit();
?>