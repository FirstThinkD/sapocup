<?php
require_once(__DIR__ . '/../../common/dbconnect.php');
require_once(__DIR__ . '/../common/functions.php');
require_once(__DIR__ . '/../data/functions.php');
require_once(__DIR__ . '/../deposit/func_dep.php');

if ($_SESSION['loginID'] == "") {
	header("Location:/");
	exit();
}

$sql = sprintf('SELECT * FROM `customer` WHERE u_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$ix = 0;
while($row0 = mysqli_fetch_assoc($record)) {
	$c_id[$ix]   = $row0['c_id'];
	$c_name[$ix] = $row0['c_name'];

	// $custom_id[$ix] = 10000000 + $c_id[$ix];
	$custom_id[$ix] = sprintf("%06s", $c_id[$ix]);
	$ix++;
}
$custom_count = $ix;

$s_cust_id   = "";
$s_cust_no   = "";
$s_cust_name = "";
$s_cust_nano = "";
$s_item      = "";

if (($_POST['search2'] == "検索" && $_POST['item'] != "") || $_SESSION['s_item'] != "") {
	// print_r($_POST);
	// echo "<br>";

	for($ix=0; $ix<$custom_count; $ix++) {
		if ($_POST['qu_custom_no'] == $c_id[$ix]) {
			// $s_cust_id = 10000000 + $c_id[$ix];
			$s_cust_id = sprintf("%06s", $c_id[$ix]);
			$s_cust_no = $c_id[$ix];
		}
		if ($_POST['qu_custom_name'] == $c_id[$ix]) {
			$s_cust_name = $c_name[$ix];
			$s_cust_nano = $c_id[$ix];
		}
	}

	$s_item = $_POST['item'];
	if ($_SESSION['s_item'] == "顧客一覧") {
		$s_item = $_SESSION['s_item'];
		$_SESSION['s_item'] = "";
	}
	// echo "s_item=". $s_item. "<br>";

	if ($s_item == "見積書") {
		if ($s_cust_no != "" && $s_cust_nano != "") {
			$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND delFlag=0 AND
				(c_id="%s" OR c_id="%s")',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $s_cust_no),
				mysqli_real_escape_string($db, $s_cust_nano)
			);
		} else if ($s_cust_no != "" && $s_cust_nano == "") {
			$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND delFlag=0 AND
				c_id="%s"',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $s_cust_no)
			);
		} else if ($s_cust_no == "" && $s_cust_nano != "") {
			$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND delFlag=0 AND
				c_id="%s"',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $s_cust_nano)
			);
		} else {
			$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND delFlag=0',
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
		}

		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$ix = 0;
		while ($row0 = mysqli_fetch_assoc($record)) {
			$s1_qu_id[$ix]           = $row0['qu_id'];
			// $in_id[$ix]           = $row0['in_id'];
			$s1_c_id[$ix]            = $row0['c_id'];
			// $qu_bunrui[$ix]       = $row0['qu_bunrui'];
			$s1_qu_custom_name[$ix]  = $row0['qu_custom_name'];
			// $qu_custom_no[$ix]    = $row0['qu_custom_no'];
			$s1_qu_name[$ix]         = $row0['qu_name'];
			// $qu_location[$ix]     = $row0['qu_location'];
			$s1_qu_paymentDate[$ix]  = sprintf("毎月%02d日", $row0['qu_paymentDate']);
			$s1_qu_deliveryDate[$ix] = date('Y年m月d日', strtotime($row0['qu_deliveryDate']));
			$s1_q_alltotal[$ix]      = number_format($row0['q_alltotal']);
			$s1_invoice_flag[$ix]    = $row0['invoice_flag'];

			$s1_customer_id[$ix]     = sprintf("%06s", $s1_qu_id[$ix]);
			$ix++;
		}
		$s1_count = $ix;

		for($ix=0; $ix<$s1_count; $ix++) {
			for($iy=0; $iy<$custom_count; $iy++) {
				if ($s1_qu_custom_name[$ix] == $c_id[$iy]) {
					$s1_qu_custom_name[$ix] = $c_name[$iy];
					break;
				}
			}
		}
		// echo "s1_count=". $s1_count. "<br>";
	} else if ($s_item == "請求書") {
		if ($s_cust_no != "" && $s_cust_nano != "") {
			$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND invoice_flag=1 AND
				delFlag=0 AND (c_id="%s" OR c_id="%d")',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $s_cust_no),
				mysqli_real_escape_string($db, $s_cust_nano)
			);
		} else if ($s_cust_no != "" && $s_cust_nano == "") {
			$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND invoice_flag=1 AND
				delFlag=0 AND c_id="%d"',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $s_cust_no)
			);
		} else if ($s_cust_no == "" && $s_cust_nano != "") {
			$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND invoice_flag=1 AND
				delFlag=0 AND c_id="%d"',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $s_cust_nano)
			);
		} else {
			$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND invoice_flag=1 AND
				delFlag=0',
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
		}

		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$ix = 0;
		while ($row0 = mysqli_fetch_assoc($record)) {
			$s2_qu_id[$ix]           = $row0['qu_id'];
			// $in_id[$ix]           = $row0['in_id'];
			$s2_c_id[$ix]            = $row0['c_id'];
			$s2_u_id[$ix]            = $row0['u_id'];
			// $qu_bunrui[$ix]       = $row0['qu_bunrui'];
			$s2_qu_custom_name[$ix]  = $row0['qu_custom_name'];
			// $qu_custom_no[$ix]    = $row0['qu_custom_no'];
			$s2_qu_name[$ix]         = $row0['qu_name'];
			// $qu_location[$ix]     = $row0['qu_location'];
			$s2_qu_paymentDate[$ix]  = sprintf("毎月%02d日", $row0['qu_paymentDate']);
			$s2_qu_deliveryDate[$ix] = date('Y年m月d日', strtotime($row0['qu_deliveryDate']));
			$s2_q_alltotal[$ix]      = number_format($row0['q_alltotal']);

			$s2_customer_id[$ix]     = sprintf("%06s", $s2_qu_id[$ix]);
			$ix++;
		}
		$s2_count = $ix;

		for($ix=0; $ix<$s2_count; $ix++) {
			for($iy=0; $iy<$custom_count; $iy++) {
				if ($s2_qu_custom_name[$ix] == $c_id[$iy]) {
					$s2_qu_custom_name[$ix] = $c_name[$iy];
					break;
				}
			}
		}
	} else if ($s_item == "顧客一覧") {
		if ($s_cust_no != "" && $s_cust_nano != "") {
			$sql = sprintf('SELECT * FROM `customer` WHERE u_id="%d" AND delFlag=0 AND
				(c_id="%s" OR c_id="%s")',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $s_cust_no),
				mysqli_real_escape_string($db, $s_cust_nano)
			);
		} else if ($s_cust_no != "" && $s_cust_nano == "") {
			$sql = sprintf('SELECT * FROM `customer` WHERE u_id="%d" AND delFlag=0 AND
				c_id="%s"',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $s_cust_no)
			);
		// } else if ($s_cust_no == "" && $s_cust_nano != "") {
		} else if ($s_cust_no == "" && !empty($s_cust_nano)) {
			$sql = sprintf('SELECT * FROM `customer` WHERE u_id="%d" AND delFlag=0 AND
				c_id="%s"',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $s_cust_nano)
			);
		} else {
			$sql = sprintf('SELECT * FROM `customer` WHERE u_id="%d" AND delFlag=0',
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
		}

		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$ix = 0;
		while ($row0 = mysqli_fetch_assoc($record)) {
			$s3_c_id[$ix]         = $row0['c_id'];
			$s3_u_id[$ix]         = $row0['u_id'];
			$s3_c_name[$ix]       = $row0['c_name'];
			$s3_c_kana[$ix]       = $row0['c_kana'];
			$s3_c_birth[$ix]      = $row0['c_birth'];
			$s3_c_tel[$ix]        = $row0['c_tel'];
			$s3_c_postal[$ix]     = $row0['c_postal'];
			$s3_c_address1[$ix]   = $row0['c_address1'];
			$s3_c_address2[$ix]   = $row0['c_address2'];
			$s3_c_address3[$ix]   = $row0['c_address3'];
			$s3_c_email[$ix]      = $row0['c_email'];
			$s3_c_type[$ix]       = $row0['c_type'];
			$s3_c_loan_total[$ix] = $row0['c_loan_total'];
			$s3_c_loan_count[$ix] = $row0['c_loan_count'];
			$s3_c_loan_start[$ix] = $row0['c_loan_start'];
			$s3_c_loan_fin[$ix]   = $row0['c_loan_fin'];

			// $s3_c_no[$ix] = 10000000 + $s3_c_id[$ix];
			$s3_c_no[$ix] = sprintf("%06s", $s3_c_id[$ix]);

			// if ($s3_c_loan_total[$ix] != "") {
			if (!empty($s3_c_loan_total[$ix])) {
				if (ctype_digit($s3_c_loan_total[$ix])) {
					$s3_c_loan_total[$ix] = number_format($s3_c_loan_total[$ix]). "円";
				} else {
					$s3_c_loan_total[$ix] = $s3_c_loan_total[$ix]. "円";
				}
			}

			// if ($s3_c_loan_count[$ix] != "") {
			if (!empty($s3_c_loan_count[$ix])) {
				$s3_c_loan_count[$ix] = $s3_c_loan_count[$ix]. "回";
			}

			// if ($s3_c_loan_start[$ix] != "") {
			if (!empty($s3_c_loan_start[$ix])) {
				$s3_c_loan_start_yy[$ix] = date('Y年', strtotime($s3_c_loan_start[$ix]));
				$s3_c_loan_start_mm[$ix] = date('m月d日', strtotime($s3_c_loan_start[$ix]));
			} else {
				$s3_c_loan_start_yy[$ix] = "";
				$s3_c_loan_start_mm[$ix] = "";
			}

			// if ($s3_c_loan_fin[$ix] != "") {
			if (!empty($s3_c_loan_fin[$ix])) {
				$s3_c_loan_fin_yy[$ix] = date('Y年', strtotime($s3_c_loan_fin[$ix]));
				$s3_c_loan_fin_mm[$ix] = date('m月d日', strtotime($s3_c_loan_fin[$ix]));
			} else {
				$s3_c_loan_fin_yy[$ix] = "";
				$s3_c_loan_fin_mm[$ix] = "";
			}
			$ix++;
		}
		$s3_count = $ix;
	} else if ($s_item == "顧客データ管理") {
		$org_ym = date('Ym');
		if ($s_cust_no != "" && $s_cust_nano != "") {
			$sql = sprintf('SELECT * FROM `data_quo` WHERE u_id="%d" AND
				delFlag=0 AND yyyymm="%s" AND (c_id="%s" OR c_id="%s")
				ORDER BY qu_id',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $org_ym),
				mysqli_real_escape_string($db, $s_cust_no),
				mysqli_real_escape_string($db, $s_cust_nano)
			);

			$sql2 = sprintf('SELECT * FROM `data_opt` WHERE u_id="%d" AND
				delFlag=0 AND yyyymm="%s" AND (c_id="%s" OR c_id="%s")
				ORDER BY qu_id',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $org_ym),
				mysqli_real_escape_string($db, $s_cust_no),
				mysqli_real_escape_string($db, $s_cust_nano)
			);
		} else if ($s_cust_no != "" && $s_cust_nano == "") {
			$sql = sprintf('SELECT * FROM `data_quo` WHERE u_id="%d" AND
				delFlag=0 AND yyyymm="%s" AND c_id="%s"
				ORDER BY qu_id',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $org_ym),
				mysqli_real_escape_string($db, $s_cust_no)
			);

			$sql2 = sprintf('SELECT * FROM `data_opt` WHERE u_id="%d" AND
				delFlag=0 AND yyyymm="%s" AND c_id="%s"
				ORDER BY qu_id',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $org_ym),
				mysqli_real_escape_string($db, $s_cust_no)
			);
		} else if ($s_cust_no == "" && $s_cust_nano != "") {
			$sql = sprintf('SELECT * FROM `data_quo` WHERE u_id="%d" AND
				delFlag=0 AND yyyymm="%s" AND c_id="%s"
				ORDER BY qu_id',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $org_ym),
				mysqli_real_escape_string($db, $s_cust_nano)
			);

			$sql2 = sprintf('SELECT * FROM `data_opt` WHERE u_id="%d" AND
				delFlag=0 AND yyyymm="%s" AND c_id="%s"
				ORDER BY qu_id',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $org_ym),
				mysqli_real_escape_string($db, $s_cust_nano)
			);
		} else {
			$sql = sprintf('SELECT * FROM `data_quo` WHERE u_id="%d" AND
				yyyymm="%s" AND delFlag=0 ORDER BY qu_id',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $org_ym)
			);

			$sql2 = sprintf('SELECT * FROM `data_opt` WHERE u_id="%d" AND
				yyyymm="%s" AND delFlag=0 ORDER BY qu_id',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $org_ym)
			);
		}

		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$ix = 0;
		while ($row0 = mysqli_fetch_assoc($record)) {
			$s4_da_id[$ix]           = $row0['da_id'];
			$s4_qu_id[$ix]           = $row0['qu_id'];
			$s4_c_id[$ix]            = $row0['c_id'];
			$s4_u_id[$ix]            = $row0['u_id'];
			$s4_qu_custom_name[$ix]  = $row0['qu_custom_name'];
			$s4_qu_name[$ix]         = $row0['qu_name'];
			$s4_q_alltotal[$ix]      = number_format($row0['q_alltotal']);
			$s4_qu_installments[$ix] = $row0['qu_installments'];
			$s4_monthly_pay[$ix]     = number_format($row0['monthly_pay']);
			$s4_qu_startDate[$ix]    = $row0['qu_startDate'];
			$s4_qu_endDate[$ix]      = $row0['qu_endDate'];
			$s4_depo_date[$ix]       = $row0['depo_date'];
			$s4_depo_memo[$ix]       = $row0['depo_memo'];

			$s4_customer_id[$ix] = sprintf("%06s", $s4_c_id[$ix]);

			if ($s4_qu_startDate[$ix] != "") {
				$s4_qu_startDate_yy[$ix] = date('Y年', strtotime($s4_qu_startDate[$ix]));
				$s4_qu_startDate_mm[$ix] = date('m月d日', strtotime($s4_qu_startDate[$ix]));
			} else {
				$s4_qu_startDate_yy[$ix] = "";
				$s4_qu_startDate_mm[$ix] = "";
			}
			if ($s4_qu_endDate[$ix] != "") {
				$s4_qu_endDate_yy[$ix] = date('Y年', strtotime($s4_qu_endDate[$ix]));
				$s4_qu_endDate_mm[$ix] = date('m月d日', strtotime($s4_qu_endDate[$ix]));
			} else {
				$s4_qu_endDate_yy[$ix] = "";
				$s4_qu_endDate_mm[$ix] = "";
			}
			if ($s4_depo_date[$ix] != "") {
				$s4_depo_date_yy[$ix] = date('Y年', strtotime($s4_depo_date[$ix]));
				$s4_depo_date_mm[$ix] = date('m月d日', strtotime($s4_depo_date[$ix]));
				$s4_depo_sendDate[$ix] = date('Y-m-d', strtotime('-3 day '. $s4_depo_date[$ix]));
				$s4_depo_sendDate[$ix] = date('Y年m月d日', strtotime($s4_depo_sendDate[$ix]));
			} else {
				$s4_depo_date_yy[$ix] = "";
				$s4_depo_date_mm[$ix] = "";
				$s4_depo_sendDate[$ix] = "";
			}
			$ix++;
		}
		$s4_qu_count = $ix;

		$record = mysqli_query($db, $sql2) or die(mysqli_error($db));
		$ix = 0;
		while($row0 = mysqli_fetch_assoc($record)) {
			$s4o_op_id[$ix]    = $row0['op_id'];
			$s4o_qu_id[$ix]    = $row0['qu_id'];
			// $s4o_c_id[$ix]     = $row0['c_id'];
			// $s4o_u_id[$ix]     = $row0['u_id'];
			$s4o_yyyymm[$ix]   = $row0['yyyymm'];
			$s4o_sms_serv[$ix] = $row0['sms_serv'];
			$s4o_service1[$ix] = $row0['service1'];
			$s4o_service2[$ix] = $row0['service2'];
			$s4o_service3[$ix] = $row0['service3'];
			$s4o_operator[$ix] = $row0['operator'];
			$ix++;
		}
		$s4_op_count = $ix;

		for($ix=0; $ix<$s4_qu_count; $ix++) {
			for($iy=0; $iy<$custom_count; $iy++) {
				if ($s4_qu_custom_name[$ix] == $c_id[$iy]) {
					$s4_qu_custom_name[$ix] = $c_name[$iy];
					break;
				}
			}
		}

		//  now_ym = date('Y年m月', strtotime($_SESSION['data_ymd']));

		$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
			mysqli_real_escape_string($db, $_SESSION['loginID'])
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$row7 = mysqli_fetch_assoc($record);

		// echo "s4_qu_count=". $s4_qu_count. " s4_op_count=". $s4_op_count;
	} else if ($s_item == "減価償却一覧") {
		if ($s_cust_no != "" && $s_cust_nano != "") {
			$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND invoice_flag=1 AND delFlag=0 AND
				(c_id="%s" OR c_id="%s")',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $s_cust_no),
				mysqli_real_escape_string($db, $s_cust_nano)
			);
		} else if ($s_cust_no != "" && $s_cust_nano == "") {
			$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND invoice_flag=1 AND delFlag=0 AND
				c_id="%s"',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $s_cust_no)
			);
		} else if ($s_cust_no == "" && $s_cust_nano != "") {
			$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND invoice_flag=1 AND delFlag=0 AND
				c_id="%s"',
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $s_cust_nano)
			);
		} else {
			$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND invoice_flag=1 AND delFlag=0',
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
		}

		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$_SESSION['dep_chkbox2'] = array();
		$iy = 0;
		$ix = 0;
		while ($row0 = mysqli_fetch_assoc($record)) {
			$s5_qu_id[$ix]            = $row0['qu_id'];
			// $s5_in_id[$ix]            = $row0['in_id'];
			// $s5_c_id[$ix]             = $row0['c_id'];
			$s5_qu_custom_name[$ix]   = $row0['qu_custom_name'];
			$s5_qu_name[$ix]          = $row0['qu_name'];
			$s5_qu_paymentDate[$ix]   = $row0['qu_paymentDate'];
			// $s5_qu_deliveryDate[$ix]  = $row0['qu_deliveryDate'];
			$s5_q_alltotal[$ix]       = $row0['q_alltotal'];
			$s5_erase_money[$ix]      = $row0['erase_money'];
			$s5_erase_money_mi[$ix]   = $row0['erase_money_mi'];
			$s5_invoice_flag[$ix]     = $row0['invoice_flag'];

			$s5_customer_id[$ix] = sprintf("%06s", $s5_qu_id[$ix]);

			list($s5_erase_money[$ix], $s5_erase_money_mi[$ix]) = erase_get($s5_qu_id[$ix], $s5_q_alltotal[$ix], $s5_erase_money[$ix], $s5_erase_money_mi[$ix]);

			if ($s5_erase_money[$ix] == "0") {
				$s5_erase_status[$ix] = "未消込";
			} else if ($s5_erase_money[$ix] == $s5_q_alltotal[$ix]) {
				$s5_erase_status[$ix] = "消込済";
			} else {
				$s5_erase_status[$ix] = "消込中";
			}

			$s5_erase_money[$ix]    = number_format($s5_erase_money[$ix]);
			$s5_erase_money_mi[$ix] = number_format($s5_erase_money_mi[$ix]);
			$s5_q_alltotal[$ix]     = number_format($s5_q_alltotal[$ix]);

			if ($s5_qu_paymentDate[$ix] == "末日") {
				$s5_qu_paymentDate[$ix] = "毎月". $s5_qu_paymentDate[$ix];
			} else {
				$s5_qu_paymentDate[$ix] = sprintf("毎月%02d日", $s5_qu_paymentDate[$ix]);
			}

			// if ($qu_deliveryDate[$ix] != "") {
			//	$qu_deliveryDate[$ix] = date('Y年m月d日', strtotime($qu_deliveryDate[$ix]));
			// }

			$_SESSION['dep_chkbox2'][($ix + 1)] = $s5_qu_id[$ix];
			$ix++;
		}
		$s5_qu_count = $ix;
		$_SESSION['dep_chkbox2'][0] = $ix;

		$sql = sprintf('SELECT * FROM `customer` WHERE u_id="%d" AND delFlag=0',
			mysqli_real_escape_string($db, $_SESSION['loginID'])
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$ix = 0;
		while($row0 = mysqli_fetch_assoc($record)) {
			$s5_c_id[$ix]   = $row0['c_id'];

			for($iy=0; $iy<$s5_qu_count; $iy++) {
				if ($s5_c_id[$ix] == $s5_qu_custom_name[$iy]) {
					$s5_qu_custom_name[$iy] = $row0['c_name'];
				}
			}
			$ix++;
		}

		// if ($ix == 0) {
		//	$_SESSION['custom_no'] = "ZERO";
		// } else {
		//	$_SESSION['custom_no'] = "";
		// }

		$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
			mysqli_real_escape_string($db, $_SESSION['loginID'])
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$row7 = mysqli_fetch_assoc($record);
	}
}

// echo "custom_count=". $custom_count;
?>
<?php require_once(__DIR__ . '/../common/header.php'); ?>
	<main>
		<div class="main_inner">
			<?php require_once(__DIR__ .'/../common/grobal-menu.php'); ?>
			<div class="main_wrap">
				<div class="main_title">
					<div class="all_wrapper">
						<div class="main_title_inner">
							<div class="main_title_top">
								<p class="title">検索・帳票</p>
							</div>
						</div>

						<?php if (isset($error) == "fail") { ?>
							<p class="error_message">氏名が重複しています。別名で登録してください。</p>
						<?php } ?>
					</div>
				</div>
				<div class="main_content search_table">
					<div class="all_wrapper sp_all">
						<div class="main_content_inner estimates_new_inner">
							<form action="" method="post" accept-charset="utf-8">
								<div class="main_content_wrap estimates_new_wrap">
									<div class="estimates_new_content">
										<div class="estimates_new_content_inner cf">
											<div class="estimates_company_info sp-margin-bottom-none sp-padding-bottom-none sp-border-bottom-none">
												<p class="company_title">対象データ</p>
												<div class="estimates_company_info_inner">
													<table class="estimates_new_company">
														<tr>
															<th>① 見積書</th>
															<td><input type="radio" name="item" value="見積書" <?php if ($s_item == "見積書") { echo 'checked'; } ?> required></td>
														</tr>
														<tr>
															<th>② 請求書</th>
															<td><input type="radio" name="item" value="請求書" <?php if ($s_item == "請求書") { echo 'checked'; } ?>></td>
														</tr>
														<tr>
															<th>③ 顧客一覧</th>
															<td><input type="radio" name="item" value="顧客一覧" <?php if ($s_item == "顧客一覧") { echo 'checked'; } ?>></td>
														</tr>
														<tr>
															<th>④ 顧客データ管理</th>
															<td><input type="radio" name="item" value="顧客データ管理" <?php if ($s_item == "顧客データ管理") { echo 'checked'; } ?>></td>
														</tr>
														<tr>
															<th>⑤ 減価償却一覧</th>
															<td><input type="radio" name="item" value="減価償却一覧" <?php if ($s_item == "減価償却一覧") { echo 'checked'; } ?>></td>
														</tr>
													</table>
												</div>
											</div>
											<div class="estimates_company_info">
												<p class="company_title">顧客データ</p>
												<div class="estimates_company_info_inner">
													<table class="estimates_new_company">
														<tr>
															<th>顧客番号</th>
															<td id="client_number">
																<span class="select_box">
																	<select name="qu_custom_no" class="ajax_number">
																		<option value="">-</option>
																		<?php for($ix=0; $ix<$custom_count; $ix++) { ?>
																			<?php if ($s_cust_no == $c_id[$ix]) { ?>
																				<option value="<?= h($s_cust_no); ?>" selected><?= h($s_cust_id); ?></option>
																			<?php } else { ?>
																				<option value="<?= h($c_id[$ix]); ?>"><?= h($custom_id[$ix]); ?></option>
																			<?php } ?>
																		<?php } ?>
																  </select>
																</span>
															</td>
														</tr>
														<tr>
															<th>顧客氏名</th>
															<td id="client_name">
																<span class="select_box">
																	<select name="qu_custom_name" class="ajax_name">
																		<option value="">-</option>
				    													<?php for($ix=0; $ix<$custom_count; $ix++) { ?>
																			<?php if ($s_cust_nano == $c_id[$ix]) { ?>
																				<option value="<?= h($s_cust_nano); ?>" selected><?= h($s_cust_name); ?></option>
																			<?php } else { ?>
																				<option value="<?= h($c_id[$ix]); ?>"><?= h($c_name[$ix]); ?></option>
																			<?php } ?>
																		<?php } ?>
																  </select>
																</span>
															</td>
														</tr>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="common_submit_button">
									<input type="submit" name="search2" value="検索">
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="main_content">
					<div class="all_wrapper sp_all">
						<!-- 減価償却一覧 -->
						<?php if ($s_item == "減価償却一覧") { ?>
						<div class="main_content_inner">
							<div class="main_title">
								<div class="main_title_inner">
									<div class="main_title_top">
										<p class="title">減価償却一覧</p>
									</div>
								</div>
							</div>
							<table class="common_table customerdata_table">
								<thead class="common_table_thead">
									<tr class="common_table_tr">
										<th>消込状態</th>
										<!-- <th class="allCheck check_submit_button"><input class="check_submit_button" type="checkbox" name="chkbox[]"> 出力</th> -->
										<th>請求書番号<br>名称</th>
										<th>請求先</th>
										<th>支払日</th>
										<th>消込金額</th>
										<th>未消込金額</th>
										<th>総金額</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="common_table_tbody">
									<input type="hidden" name="count" value="<?php echo $s5_qu_count; ?>">
									<?php for($ix=0; $ix<$s5_qu_count; $ix++) { ?>
										<tr class="common_table_tr">
											<td class="common_table_cell">
												<span class="client_name"><a style="color:#f05b72;"><?php echo $s5_erase_status[$ix]; ?></a></span>
											</td>
											<!-- <td class="common_table_cell singleCheck">
												<input class="check_submit_button" type="checkbox" name="chkbox[]" value="<?php echo $s5_qu_id[$ix]; ?>">　　
											</td> -->
											<td class="common_table_cell">
												<span class="client_num"><?php echo $s5_customer_id[$ix]; ?></span>
												<span class="client_name"><a href="/manage/invoices/detail/?id=<?php echo $s5_qu_id[$ix]; ?>" class="text_link"><?php echo $qu_name[$ix]; ?></a></span>
												<span class="detail_button">詳細</span>
											</td>
											<td class="common_table_cell"><?php echo $s5_qu_custom_name[$ix]; ?></a></td>
											<td class="common_table_cell">
												<span class="sp_customerdata_name">支払日</span>
												<span class="customerdata_item"><?php echo $s5_qu_paymentDate[$ix]; ?></span>
											</td>
											<td class="common_table_cell">
												<span class="sp_customerdata_name">消込金額</span>
												<span class="customerdata_item">¥<?php echo $s5_erase_money[$ix]; ?></span>
											</td>
											<td class="common_table_cell">
												<span class="sp_customerdata_name">未消込金額</span>
												<span class="customerdata_item">¥<?php echo $s5_erase_money_mi[$ix]; ?></span>
											</td>
											<td class="common_table_cell">
												<span class="sp_customerdata_name">総金額</span>
												<span class="customerdata_item">¥<?php echo $s5_q_alltotal[$ix]; ?></span>
											</td>
											<td class="common_table_cell edit_menu">
												<ul class="cf">
													<li><a href="/manage/search/out-csv.php" style="background: #5B9BD5;">リスト出力</a></li>
												</ul>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php } ?>
						<!-- 顧客データ管理 -->
						<?php if ($s_item == "顧客データ管理") { ?>
						<div class="main_content_inner">
							<div class="main_title">
								<div class="main_title_inner">
									<div class="main_title_top">
										<p class="title">顧客データ管理</p>
									</div>
								</div>
							</div>
							<table class="common_table customerdata_table">
								<thead class="common_table_thead">
									<tr class="common_table_tr">
										<th></th>
										<th>顧客番号<br>顧客氏名</th>
										<th>名称</th>
										<th>総支払額<br>月返済額</th>
										<th>分割回数</th>
										<th>分割開始日<br>分割終了日</th>
										<th>入金予定日<br>入金案内送信日</th>
									</tr>
								</thead>
								<tbody class="common_table_tbody">
									<!-- <input type="hidden" name="count" value="<?php echo $s4_qu_count; ?>"> -->
									<?php for($ix=0; $ix<$s4_qu_count; $ix++) { ?>
									<tr class="common_table_tr">
										<td class="common_table_cell"></td>
										<td class="common_table_cell">
											<span class="client_num"><?php echo $s4_customer_id[$ix]; ?></span>
											<span class="client_name"><?php echo $s4_qu_custom_name[$ix]; ?></span>
											<span class="detail_button">詳細</span>
										</td>
										<td class="common_table_cell"><a href="/manage/estimates/detail/?id=<?php echo $s4_qu_id[$ix]; ?>" class="text_link"><?= $s4_qu_name[$ix]; ?></a></td>
										<td class="common_table_cell text_right">
											<span class="sp_customerdata_name">総支払額</span>
											<span class="customerdata_item"><?= h($s4_q_alltotal[$ix]); ?>円</span>
											<br>
											<span class="sp_customerdata_name">月返済額</span>
											<span class="customerdata_item"><?= h($s4_monthly_pay[$ix]); ?>円</span>
										</td>
										<td class="common_table_cell text-center">
													<span class="sp_customerdata_name">分割回数</span>
											<span class="customerdata_item"><?= h($s4_qu_installments[$ix]); ?>回</span>
										</td>
										<td class="common_table_cell">
											<span class="sp_customerdata_name">分割開始日</span>
											<span class="customerdata_item"><?= h($s4_qu_startDate_yy[$ix]); ?><?= h($s4_qu_startDate_mm[$ix]); ?></span>
											<br>
											<span class="sp_customerdata_name">分割終了日</span>
											<span class="customerdata_item"><?= h($s4_qu_endDate_yy[$ix]); ?><?= h($s4_qu_endDate_mm[$ix]); ?></span>
										</td>
										<td class="common_table_cell">
											<span class="sp_customerdata_name">入金予定日</span>
											<span class="customerdata_item"><?= h($s4_depo_date_yy[$ix]); ?><?= h($s4_depo_date_mm[$ix]); ?></span>
											<br>
											<span class="sp_customerdata_name">入金事前通知日</span>
											<span class="customerdata_item"><?= h($s4_depo_sendDate[$ix]); ?></span>
										</td>
										<!--
										<?php if ($row7['service2_fl'] == 1) { ?>
										<td class="smsInput"><input type="checkbox" name=""></td>
										<td class="telInput"><input type="checkbox" name=""></td>
										<?php } ?>
										-->
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php } ?>
						<!-- 見積書 -->
						<?php if ($s_item == "見積書") { ?>
						<div class="main_content_inner">
							<div class="main_title">
								<div class="main_title_inner">
									<div class="main_title_top">
										<p class="title">見積書</p>
									</div>
								</div>
							</div>
							<table class="common_table customerdata_table">
								<thead class="common_table_thead">
									<tr class="common_table_tr">
										<th></th>
										<th>見積書番号<br>名称</th>
										<th>請求先</th>
										<th>支払日</th>
										<th>受渡期日</th>
										<th>総金額</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="common_table_tbody">
									<?php for($ix=0; $ix<$s1_count; $ix++) { ?>
									<tr class="common_table_tr">
										<td class="common_table_cell"></td>
										<td class="common_table_cell">
											<span class="client_num"><?php echo $s1_customer_id[$ix]; ?></span>
											<span class="client_name"><a href="/manage/estimates/detail/?id=<?php echo $s1_qu_id[$ix]; ?>" class="text_link"><?php echo $s1_qu_name[$ix]; ?></a></span>
											<span class="detail_button">詳細</span>
										</td>
										<td class="common_table_cell"><?php echo $s1_qu_custom_name[$ix]; ?></a></td>
										<td class="common_table_cell text_right">
											<span class="sp_customerdata_name">支払日</span>
											<span class="customerdata_item"><?php echo $s1_qu_paymentDate[$ix]; ?></span>
										</td>
										<td class="common_table_cell text-center">
											<span class="sp_customerdata_name">受渡期日</span>
											<span class="customerdata_item"><?php echo $s1_qu_deliveryDate[$ix]; ?></span>
										</td>
										<td class="common_table_cell">
											<span class="sp_customerdata_name">総金額</span>
											<span class="customerdata_item"><?php echo $s1_q_alltotal[$ix]; ?>円</span>
										</td>
										<td class="common_table_cell edit_menu">
											<ul class="cf">
												<li><a href="/manage/estimates/edit/?id=<?php echo $s1_qu_id[$ix]; ?>">編集</a></li>
												<li><a href="/manage/estimates/simulation/?id=<?php echo $s1_qu_id[$ix]; ?>">シミュレーション</a></li>
												<?php if ($s1_invoice_flag[$ix] == 0) { ?>
													<li><a class="disable">請求書</a></li>
												<?php } else { ?>
													<li><a href="/manage/invoices/edit/?id=<?php echo $s1_qu_id[$ix]; ?>">請求書</a></li>
												<?php } ?>
											</ul>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php } ?>
						<?php if ($s_item == "請求書") { ?>
						<!-- 請求書 -->
						<div class="main_content_inner">
							<div class="main_title">
								<div class="main_title_inner">
									<div class="main_title_top">
										<p class="title">請求書</p>
									</div>
								</div>
							</div>
							<table class="common_table customerdata_table">
								<thead class="common_table_thead">
									<tr class="common_table_tr">
										<th></th>
										<th>請求書番号<br>名称</th>
										<th>請求先</th>
										<th>支払日</th>
										<th>受渡期日</th>
										<th>総金額</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="common_table_tbody">
									<!-- <input type="hidden" name="count" value=""> -->
									<?php for($ix=0; $ix<$s2_count; $ix++) { ?>
									<tr class="common_table_tr">
										<td class="common_table_cell"></td>
										<td class="common_table_cell">
											<span class="client_num"><?= $s2_customer_id[$ix]; ?></span>
											<span class="client_name"><a href="/manage/invoices/detail/?id=<?= h($s2_qu_id[$ix]); ?>" class="text_link"><?= h($s2_qu_name[$ix]); ?></a></span>
											<span class="detail_button">詳細</span>
										</td>
										<td class="common_table_cell"><?= h($s2_qu_custom_name[$ix]); ?></a></td>
										<td class="common_table_cell text_right">
											<span class="sp_customerdata_name">支払日</span>
											<span class="customerdata_item"><?= h($s2_qu_paymentDate[$ix]); ?></span>
										</td>
										<td class="common_table_cell text-center">
											<span class="sp_customerdata_name">受渡期日</span>
											<span class="customerdata_item"><?= h($s2_qu_deliveryDate[$ix]); ?></span>
										</td>
										<td class="common_table_cell">
											<span class="sp_customerdata_name">総金額</span>
											<span class="customerdata_item">¥<?= h($s2_q_alltotal[$ix]); ?></span>
										</td>
										<td class="common_table_cell edit_menu">
											<ul class="cf">
												<li><a href="/manage/invoices/edit/?id=<?= h($s2_qu_id[$ix]); ?>">編集</a></li>
											</ul>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php } ?>
						<!-- 顧客 -->
						<?php if ($s_item == "顧客一覧") { ?>
						<div class="main_content_inner">
							<div class="main_title">
								<div class="main_title_inner">
									<div class="main_title_top">
										<p class="title">顧客一覧</p>
									</div>
								</div>
							</div>
							<table class="common_table customerdata_table">
								<thead class="common_table_thead">
									<tr class="common_table_tr">
										<th></th>
										<th>顧客番号<br>顧客氏名</th>
										<th>カタカナ</th>
										<th>連絡先</th>
										<th>住所</th>
										<th>メールアドレス</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="common_table_tbody">
									<?php for($ix=0; $ix<$s3_count; $ix++) { ?>

										<tr class="common_table_tr">
											<td class="common_table_cell"></td>
											<td class="common_table_cell">
												<span class="client_num"><?= h($s3_c_no[$ix]); ?></span>
												<span class="client_name"><?= h($s3_c_name[$ix]); ?></span>
												<span class="detail_button">詳細</span>
											</td>
											<td class="common_table_cell"><?= h($s3_c_kana[$ix]); ?></td>
											<td class="common_table_cell text_right">
												<span class="sp_customerdata_name">連絡先</span>
												<span class="customerdata_item"><?= h($s3_c_tel[$ix]); ?></span>
											</td>
											<td class="common_table_cell">
												<span class="sp_customerdata_name">住所</span>
												<span class="customerdata_item">〒<?= h($s3_c_postal[$ix]); ?><br><?= h($s3_c_address1[$ix]); ?><br><?= h($s3_c_address2[$ix]); ?><br><?= h($s3_c_address3[$ix]); ?></span>
											</td>
											<td class="common_table_cell">
												<span class="sp_customerdata_name">メールアドレス</span>
												<span class="customerdata_item"><?= h($s3_c_email[$ix]); ?></span>
											</td>
											<td class="common_table_cell edit_menu">
												<ul class="cf">
													<?php if ($row7['exeFlag'] == 0) { ?>
													<li><a href="" onclick="edit(<?= h($s3_c_tel[$ix]); ?>); return false;">編集</a></li>
													<?php } else { ?>
													<li><a class="disable">編集</a></li>
													<?php } ?>
												</ul>
											</td>
										</tr>
									<!-- <tr class="common_table_tr">
										<td class="common_table_cell"></td>
										<td class="common_table_cell">
											<span class="client_num"><?= h($s3_c_no[$ix]); ?></span>
											<span class="client_name"><?= h($s3_c_name[$ix]); ?></span>
											<span class="detail_button">詳細</span>
										</td>
										<td class="common_table_cell">
											<span class="customerdata_item">〒<?= h($s3_c_postal[$ix]); ?></span><br><?= h($s3_c_address1[$ix]); ?><br><?= h($s3_c_address2[$ix]); ?><br><?= h($s3_c_address3[$ix]); ?></td>
										</td>
										<td class="common_table_cell">
											<span class="sp_customerdata_name">メールアドレス</span>
											<span class="customerdata_item"><?= h($s3_c_email[$ix]); ?></span>
										</td>
										<td class="common_table_cell"><?= h($s3_c_tel[$ix]); ?></td>
										<td class="common_table_cell edit_menu">
											<ul class="cf">
												<li><a href="" onclick="edit(<?= h($s3_c_id[$ix]); ?>); return false;">編集</a></li>
											</ul>
										</td>
									</tr> -->
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</main>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="/manage/estimates/new/ajax/ajax_test.js"></script> <!-- ajax（非同期）通信 -->
	<script src="/manage/common/js/customer-data.js"></script>
	<script src="/manage/clients/clients_func.js"></script>
	<script>
		$(function() {
			$('#chatButton').on('click', function(){
				$('#sincloBox').toggleClass('chatOpen');
				$('#sincloBox').data('true');
			})
		})
	</script>
	<script src='https://ws1.sinclo.jp/client/5e7812fdb5a66.js'></script>
</body>
</html>