<?php

function memo_get($qu_id) {
	require(__DIR__ . '/../../../common/dbconnect.php');
	$str = "";
	$sql = sprintf('SELECT * FROM `q_memo` WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row0 = mysqli_fetch_assoc($record);

	// $str = str_replace(PHP_EOL, '', $row0['q_memo']);
	// $str = nl2br($str);
	$str = str_replace(PHP_EOL, '', $row0['q_memo']);
	$str = str_replace(array("\r\n", "\r", "\n"), '', $row0['q_memo']);
	// $str = "あいうえお";
	// echo $q_memo;

	return ($str);
}

function ymtoymd($ym, $day) {
	$out = array();
	$w_day = $day;
	$outdate = "";
	$error = "";

	preg_match("@([0-9]{4,})/([0-9]{1,2})@", $ym, $out);
	if (count($out) != 3) {
		$error = "end_date";
		return [$outdate, $error];
	} else {
		$outdate = sprintf("%04d%02d%02d", $out[1], $out[2], $w_day);

		$tmpdate = sprintf("%04d%02d01", $out[1], $out[2]);
		$tmpdate = date('Ymt', strtotime(date($tmpdate)));

		if ($outdate > $tmpdate) {
			$outdate = $tmpdate;
		}
	}
	return [$outdate, $error];
}

function cust_data($qu_id) {
	require(__DIR__ . '/../../../common/dbconnect.php');
	require(__DIR__ . '/../simulation/sim_func.php');

	$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row3 = mysqli_fetch_assoc($record);

	$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $row3['u_id'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row7 = mysqli_fetch_assoc($record);

	$sql = sprintf('SELECT * FROM `customer` WHERE c_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $row3['qu_custom_name'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row0 = mysqli_fetch_assoc($record);
	$c_name = $row0['c_name'];

	$ptn_out = partition($row3['qu_paymentDate'], $row3['qu_startDate'], $row3['qu_installments'], $row3['q_alltotal'], $row3['qu_deposit'], $row3['qu_initPayAmount']);

	for($ix=0; $ix<$ptn_out[0][5]; $ix++) {
		$yyyymm = date('Ym', strtotime($ptn_out[$ix][6]));
		$sql = sprintf('INSERT INTO `data_quo` SET qu_id="%d", u_id="%d",
			c_id="%d", yyyymm="%s", qu_custom_name="%s", qu_name="%s",
			q_alltotal="%s", qu_installments="%s", monthly_pay="%s",
			qu_startDate="%s", qu_endDate="%s", depo_date="%s"',
			mysqli_real_escape_string($db, $qu_id),
			mysqli_real_escape_string($db, $row3['u_id']),
			mysqli_real_escape_string($db, $row3['c_id']),
			mysqli_real_escape_string($db, $yyyymm),
			mysqli_real_escape_string($db, $c_name),
			mysqli_real_escape_string($db, $row3['qu_name']),
			mysqli_real_escape_string($db, $row3['q_alltotal']),
			mysqli_real_escape_string($db, $row3['qu_installments']),
			mysqli_real_escape_string($db, $ptn_out[$ix][8]),
			mysqli_real_escape_string($db, $row3['qu_startDate']),
			mysqli_real_escape_string($db, $row3['qu_endDate']),
			mysqli_real_escape_string($db, $ptn_out[$ix][6])
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));

		$sql = sprintf('INSERT INTO `data_opt` SET qu_id="%d",
			u_id="%d", c_id="%d", yyyymm="%s"',
			mysqli_real_escape_string($db, $qu_id),
			mysqli_real_escape_string($db, $row3['u_id']),
			mysqli_real_escape_string($db, $row3['c_id']),
			mysqli_real_escape_string($db, $yyyymm)
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));
	}
	$str = "";
	return ($str);
}

function w1_cust_data($qu_id) {
	require(__DIR__ . '/../../../common/dbconnect.php');
	require(__DIR__ . '/../simulation/sim_func.php');

	$sql = sprintf('SELECT * FROM `w1_quotation` WHERE qu_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row3 = mysqli_fetch_assoc($record);

	$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $row3['u_id'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row7 = mysqli_fetch_assoc($record);

	$sql = sprintf('SELECT * FROM `customer` WHERE c_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $row3['qu_custom_name'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row0 = mysqli_fetch_assoc($record);
	$c_name = $row0['c_name'];

	$ptn_out = partition($row3['qu_paymentDate'], $row3['qu_startDate'], $row3['qu_installments'], $row3['q_alltotal'], $row3['qu_deposit'], $row3['qu_initPayAmount']);

	for($ix=0; $ix<$ptn_out[0][5]; $ix++) {
		$yyyymm = date('Ym', strtotime($ptn_out[$ix][6]));
		$sql = sprintf('INSERT INTO `w1_data_quo` SET qu_id="%d", u_id="%d",
			c_id="%d", yyyymm="%s", qu_custom_name="%s", qu_name="%s",
			q_alltotal="%s", qu_installments="%s", monthly_pay="%s",
			qu_startDate="%s", qu_endDate="%s", depo_date="%s"',
			mysqli_real_escape_string($db, $qu_id),
			mysqli_real_escape_string($db, $row3['u_id']),
			mysqli_real_escape_string($db, $row3['c_id']),
			mysqli_real_escape_string($db, $yyyymm),
			mysqli_real_escape_string($db, $c_name),
			mysqli_real_escape_string($db, $row3['qu_name']),
			mysqli_real_escape_string($db, $row3['q_alltotal']),
			mysqli_real_escape_string($db, $row3['qu_installments']),
			mysqli_real_escape_string($db, $ptn_out[$ix][8]),
			mysqli_real_escape_string($db, $row3['qu_startDate']),
			mysqli_real_escape_string($db, $row3['qu_endDate']),
			mysqli_real_escape_string($db, $ptn_out[$ix][6])
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));

		$sql = sprintf('INSERT INTO `w1_data_opt` SET qu_id="%d",
			u_id="%d", c_id="%d", yyyymm="%s"',
			mysqli_real_escape_string($db, $qu_id),
			mysqli_real_escape_string($db, $row3['u_id']),
			mysqli_real_escape_string($db, $row3['c_id']),
			mysqli_real_escape_string($db, $yyyymm)
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));
	}
	$str = "";
	return ($str);
}

function cust_dataUP($qu_id) {
	require(__DIR__ . '/../../../common/dbconnect.php');
	require(__DIR__ . '/../simulation/sim_func.php');

	$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row3 = mysqli_fetch_assoc($record);

	$sql = sprintf('SELECT * FROM `customer` WHERE c_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $row3['qu_custom_name'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row0 = mysqli_fetch_assoc($record);
	$c_name = $row0['c_name'];

	$sql = sprintf('DELETE FROM `data_quo` WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	mysqli_query($db,$sql) or die(mysqli_error($db));

	$ptn_out = partition($row3['qu_paymentDate'], $row3['qu_startDate'], $row3['qu_installments'], $row3['q_alltotal'], $row3['qu_deposit'], $row3['qu_initPayAmount']);

	$yyyymm = date('Ym', strtotime($ptn_out[0][6]));
	$sql = sprintf('DELETE FROM `data_opt` WHERE qu_id="%d" AND yyyymm<"%s"',
		mysqli_real_escape_string($db, $qu_id),
		mysqli_real_escape_string($db, $yyyymm)
	);
	mysqli_query($db,$sql) or die(mysqli_error($db));

	$yyyymm = date('Ym', strtotime($ptn_out[($ptn_out[0][5] - 1)][6]));
	$sql = sprintf('DELETE FROM `data_opt` WHERE qu_id="%d" AND yyyymm>"%s"',
		mysqli_real_escape_string($db, $qu_id),
		mysqli_real_escape_string($db, $yyyymm)
	);
	mysqli_query($db,$sql) or die(mysqli_error($db));

	for($ix=0; $ix<$ptn_out[0][5]; $ix++) {
		$yyyymm = date('Ym', strtotime($ptn_out[$ix][6]));
		$sql = sprintf('INSERT INTO `data_quo` SET qu_id="%d", u_id="%d",
			c_id="%d", yyyymm="%s", qu_custom_name="%s", qu_name="%s",
			q_alltotal="%s", qu_installments="%s", monthly_pay="%s",
			qu_startDate="%s", qu_endDate="%s", depo_date="%s"',
			mysqli_real_escape_string($db, $qu_id),
			mysqli_real_escape_string($db, $row3['u_id']),
			mysqli_real_escape_string($db, $row3['c_id']),
			mysqli_real_escape_string($db, $yyyymm),
			mysqli_real_escape_string($db, $c_name),
			mysqli_real_escape_string($db, $row3['qu_name']),
			mysqli_real_escape_string($db, $row3['q_alltotal']),
			mysqli_real_escape_string($db, $row3['qu_installments']),
			mysqli_real_escape_string($db, $ptn_out[$ix][8]),
			mysqli_real_escape_string($db, $row3['qu_startDate']),
			mysqli_real_escape_string($db, $row3['qu_endDate']),
			mysqli_real_escape_string($db, $ptn_out[$ix][6])
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));

		$sql = sprintf('SELECT * FROM `data_opt` WHERE qu_id="%d" AND yyyymm="%s" AND delFlag=0',
			mysqli_real_escape_string($db, $qu_id),
			mysqli_real_escape_string($db, $yyyymm)
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		if ($row0 = mysqli_fetch_assoc($record)) {
			// あり
		} else {
			$sql = sprintf('INSERT INTO `data_opt` SET qu_id="%d",
				u_id="%d", c_id="%d", yyyymm="%s"',
				mysqli_real_escape_string($db, $qu_id),
				mysqli_real_escape_string($db, $row3['u_id']),
				mysqli_real_escape_string($db, $row3['c_id']),
				mysqli_real_escape_string($db, $yyyymm)
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
		}
	}
	$str = "";
	return ($str);
}
?>