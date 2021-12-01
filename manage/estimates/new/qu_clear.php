<?php
session_start();
require_once(__DIR__ . '/../../../common/dbconnect.php');

$sql = sprintf('DELETE FROM `w1_quotation` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id'])
);
mysqli_query($db, $sql) or die(mysqli_error($db));

$sql = sprintf('DELETE FROM `w1_q_memo` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id'])
);
mysqli_query($db, $sql) or die(mysqli_error($db));

$sql = sprintf('DELETE FROM `w1_q_items` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id'])
);
mysqli_query($db, $sql) or die(mysqli_error($db));

$sql = sprintf('DELETE FROM `w1_q_item_dep` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id'])
);
mysqli_query($db, $sql) or die(mysqli_error($db));

$sql = sprintf('DELETE FROM `w1_data_opt` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id'])
);
mysqli_query($db, $sql) or die(mysqli_error($db));

$sql = sprintf('DELETE FROM `w1_data_quo` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id'])
);
mysqli_query($db, $sql) or die(mysqli_error($db));

$sql = sprintf('DELETE FROM `w1_simulation` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id'])
);
mysqli_query($db, $sql) or die(mysqli_error($db));

$_SESSION['tmp1_qu_id'] = "";

header("Location:/manage/estimates/new/");
exit();
?>
