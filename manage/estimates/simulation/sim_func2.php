<?php

function cust_dataUP2($qu_id) {
	require(__DIR__ . '/../../../common/dbconnect.php');
	// require(__DIR__ . '/simulation/sim_func.php');

	$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row3 = mysqli_fetch_assoc($record);

	$sql = sprintf('SELECT * FROM `customer` WHERE c_id="%d"',
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

		$sql = sprintf('SELECT * FROM `data_opt` WHERE qu_id="%d" AND yyyymm="%s"',
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