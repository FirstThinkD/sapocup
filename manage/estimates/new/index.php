<?php
session_start();
require_once(__DIR__ . '/../../../common/dbconnect.php');
require_once(__DIR__ . '/../../common/functions.php');
// require_once(__DIR__ . '/../simulation/sim_func.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/functions2.php');

// セッションがない場合、ログインページに戻る
if (empty($_SESSION['loginID'])) {
	header("Location:/login.php");
	exit();
}

if (empty($_SESSION['estim_new'])) {
	$_SESSION['estim_new'] = "";
}

$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row9 = mysqli_fetch_assoc($record);

if ($row9['exeFlag'] == 1) {
	header("Location:/login.php");
}

$qu_bunrui2        = "";
$qu_custom_name2   = "";
$qu_custom_no2     = "";
$qu_name2          = "";
$qu_location2      = "";
$qu_paymentDate2   = "";
$qu_deliveryDate2  = date('Y/m/d');
$qu_deposit2       = "¥0";
$qu_price2         = "";
$qu_commission2    = "5.00";
$qu_commit2        = "";
$qu_initPayAmount2 = "";
$qu_amount_pay2    = "";
$qu_installments2  = 4;
$qu_startDate2     = date('Y/m/d');
$qu_endDate2       = "";
$q_cost2           = "";
$q_alltotal2       = "";
$in_companyName2   = "";
$in_postal21       = "";
$in_postal22       = "";
$in_address12      = "";
$in_address22      = "";
$in_address32      = "";
$in_tel2           = "";
$in_email2         = "";
$in_contactName2   = "";
$syohkei2          = "";
$memo              = "";

$q_count = 6;
for($ix=0; $ix<$q_count; $ix++) {
	$q_name[$ix]   = "";
	$q_number[$ix] = 0;
	$q_unit[$ix]   = "";
	$q_price[$ix]  = 0;
	$q_total[$ix]  = 0;

	$q_asset[$ix]  = "";
	$q_use[$ix]    = "";
	$q_detail[$ix] = "";
	$q_life[$ix]   = "";
}

/*	--------------------------------------------------

	見積書作成

--------------------------------------------------	*/

$errMsg = "";
if (!empty($_POST['send2']) && $_POST['send2'] == "シミュレーション画面へ") {
	// print_r($_POST);
	// exit();

	$qu_bunrui2        = $_POST['qu_bunrui'];
	$qu_custom_name2   = $_POST['qu_custom_name'];
	$qu_custom_no2     = $_POST['qu_custom_no'];
	$qu_name2          = $_POST['qu_name'];
	$qu_location2      = $_POST['qu_location'];
	$qu_paymentDate2   = $_POST['qu_paymentDate'];
	$qu_deliveryDate2  = $_POST['qu_deliveryDate'];
	$qu_deposit2       = $_POST['qu_deposit'];
	$qu_price2         = $_POST['qu_price'];
	$qu_commission2    = $_POST['qu_commission'];
	$qu_commit2        = $_POST['qu_commit'];
	$qu_initPayAmount2 = $_POST['qu_initPayAmount'];
	$qu_amount_pay2    = $_POST['qu_amount_pay'];
	$qu_installments2  = $_POST['qu_installments'];
	$qu_startDate2     = $_POST['qu_startDate']. "/01";
	$qu_endDate2       = $_POST['qu_endDate'];
	$q_cost2           = $_POST['q_cost'];
	$q_alltotal2       = $_POST['q_alltotal'];
	$in_companyName2   = $_POST['in_companyName'];
	$in_postal21       = $_POST['in_postal1'];
	$in_postal22       = $_POST['in_postal2'];
	$in_address12      = $_POST['in_address1'];
	$in_address22      = $_POST['in_address2'];
	$in_address32      = $_POST['in_address3'];
	$in_tel2           = $_POST['in_tel'];
	$in_email2         = $_POST['in_email'];
	$in_contactName2   = $_POST['in_contactName'];
	$syohkei2          = $_POST['syohkei'];
	$memo2             = $_POST['memo'];

	$iz = 0;
	for($ix=0; $ix<60; $ix++) {
		if ($_POST["item-num". $ix] != 0) {
			$item_q_number = preg_replace('/[^0-9-]/', '', $_POST['item-num'. $ix]);	// 数量
			$item_q_unit   = preg_replace('/[^0-9-]/', '', $_POST['item-unit'. $ix]);	// 単価
			$item_q_total  = preg_replace('/[^0-9-]/', '', $_POST['item-total'. $ix]);	// 金額

			$q_name[$iz]   = $_POST["item-name". $ix];
			$q_number[$iz] = $item_q_number;
			$q_unit[$iz]   = $_POST["item-cat". $ix];
			$q_price[$iz]  = $item_q_unit;
			$q_total[$iz]  = $item_q_total;

			// $q_asset[$iz]   = $_POST["asset". $ix];
			// $q_use[$iz]     = $_POST["use". $ix];
			// $q_detail[$iz]  = $_POST["detail". $ix];
			// $q_life[$iz]    = $_POST["life". $ix];
			$iz++;
		}
	}
	$q_count = $iz;

	if (!empty($_POST['qu_custom_name']) || !empty($_POST['qu_custom_no'])) {
		$qu_custom_id = 0;
		if (!empty($_POST['qu_custom_name'])) {
			$qu_custom_id = $_POST['qu_custom_name'];
		}
		if (!empty($_POST['qu_custom_no'])) {
			if ($qu_custom_id == 0) {
				$qu_custom_id = $_POST['qu_custom_no'];
			} else if ($qu_custom_id != $_POST['qu_custom_no']) {
				$errMsg = "customErr";
			}
		}
	}
	if ($qu_custom_id == 0) {
		$errMsg = "customNoErr";
	}

	if ($errMsg == "") {
		if ($_POST['qu_paymentDate'] == "") {
			$errMsg = "paymentDateerror";
		} else {
			if ($_POST['qu_paymentDate'] == "末日") {
				$str = $_POST['qu_startDate']. "/01";
				$str = date('Ymt', strtotime($str));
				$qu_paymentDate = date('d', strtotime($str));
			} else {
				$qu_paymentDate = $_POST['qu_paymentDate'];
			}

			$outdate = array();
			preg_match("@([0-9]{4,})/([0-9]{1,2})@", $_POST['qu_startDate'], $outdate);
			if (count($outdate) != 3) {
				$errMsg = "str_date";
			} else {
				$qu_startDate = sprintf("%04d%02d%02d", $outdate[1], $outdate[2], $qu_paymentDate);
				$start_date = sprintf("%04d%02d01", $outdate[1], $outdate[2]);
				$start_date = date('Ymt', strtotime(date($start_date)));
				if ($start_date < $qu_startDate) {
					$qu_startDate2 = sprintf("%04d年%02d月%02d日", $outdate[1], $outdate[2], $qu_paymentDate);
					$errMsg = "str_date2";
				}
			}
		}
	}

	if ($_POST['qu_deliveryDate'] == "") {
		$errMsg = "delivery_ng";
	}

	// print_r($outdate);
	// echo "<br>count=". count($outdate), " qu_startDate=". $_POST['qu_startDate']. "<br>";
	// echo "qu_startDate=". $qu_startDate. " qu_paymentDate=". $_POST['qu_paymentDate']. " error=". $errMsg. "<br>";
	// exit();
	if ($errMsg == "") {
		if ($_POST['qu_paymentDate'] == "末日") {
			$str = $_POST['qu_endDate']. "/01";
			$str = date('Ymt', strtotime($str));
			$qu_paymentDate = date('d', strtotime(date($str)));
		} else {
			$qu_paymentDate = $_POST['qu_paymentDate'];
		}

		preg_match("@([0-9]{4,})/([0-9]{1,2})@", $_POST['qu_endDate'], $outdate);
		if (count($outdate) != 3) {
			$errMsg = "end_date";
		} else {
			$qu_endDate = sprintf("%04d%02d%02d", $outdate[1], $outdate[2], $qu_paymentDate);
		}
		list($qu_endDate, $errMsg) = ymtoymd($_POST['qu_endDate'], $qu_paymentDate);
	}
	// print_r($outdate);
	// echo "<br>count=". count($outdate), " qu_endDate=". $_POST['qu_endDate']. "<br>";
	// echo "errMsg=". $errMsg. "<br>";
	// exit();

	if ($errMsg == "") {
		$iy = 0;
		for($ix=0; $ix<60; $ix++) {
			$w_total[$ix] = preg_replace("/[^0-9-]/","", $_POST['item-total'. $ix]);	// 金額
			if ($w_total[$ix] >= 0) {
				if (!empty($_POST["item-name". $ix]) && !empty($_POST["item-num". $ix]) && !empty($_POST["item-unit". $ix]) && !empty($_POST["item-cat". $ix])) {
					// echo "xxx ix=". $ix. "<br>";
					$q_name[$iy]   = $_POST['item-name'. $ix];
					$q_number[$iy] = preg_replace("/[^0-9-]/","", $_POST['item-num'. $ix]);
					$q_unit[$iy]   = $_POST['item-cat'. $ix];
					$q_price[$iy]  = preg_replace("/[^0-9-]/","", $_POST['item-unit'. $ix]);
					$q_total[$iy]  = preg_replace("/[^0-9-]/","", $_POST['item-total'. $ix]);

					if ($_POST["asset". $ix] != "" && $_POST["asset". $ix] != "---") {
						$q_asset[$iy]  = $_POST["asset". $ix];
						$q_use[$iy]    = $_POST["use". $ix];
						$q_detail[$iy] = $_POST["detail". $ix];
						$q_life[$iy]   = $_POST["life". $ix];
					}
					$iy++;
				} else if (!empty($_POST["item-name". $ix]) || !empty($_POST["item-num". $ix]) || !empty($_POST["item-unit". $ix]) || !empty($_POST["item-cat". $ix])) {
					$q_name[$iy]   = $_POST['item-name'. $ix];
					$q_number[$iy] = preg_replace("/[^0-9-]/","", $_POST['item-num'. $ix]);
					$q_unit[$iy]   = $_POST['item-cat'. $ix];
					$q_price[$iy]  = preg_replace("/[^0-9-]/","", $_POST['item-unit'. $ix]);
					$q_total[$iy]  = preg_replace("/[^0-9-]/","", $_POST['item-total'. $ix]);

					if ($_POST["asset". $ix] != "" && $_POST["asset". $ix] != "---") {
						$q_asset[$iy]  = $_POST["asset". $ix];
						$q_use[$iy]    = $_POST["use". $ix];
						$q_detail[$iy] = $_POST["detail". $ix];
						$q_life[$iy]   = $_POST["life". $ix];
					}
					$iy++;
					// echo "ix=". $ix. "<br>";
					if (empty($_POST["item-name". $ix])) {
						if ($errMsg == "") {
							$errMsg = "item_ng1";
							$err_ix = $iy;
							// echo "ix=". $ix. " errMsg=". $errMsg. " item-name=". $_POST["item-name". $ix], " item-num=". $_POST["item-num". $ix]. " item-unit=". $_POST["item-unit". $ix]. " item-cat=". $_POST["item-cat". $ix]. "<br>";
						}
					}
					if (empty($_POST["item-num". $ix])) {
						if ($errMsg == "") {
							$errMsg = "item_ng2";
							$err_ix = $iy;
							// echo "ix=". $ix. " errMsg=". $errMsg. " item-name=". $_POST["item-name". $ix], " item-num=". $_POST["item-num". $ix]. " item-unit=". $_POST["item-unit". $ix]. " item-cat=". $_POST["item-cat". $ix]. "<br>";
						}
					}
					if (empty($_POST["item-cat". $ix])) {
						if ($errMsg == "") {
							$errMsg = "item_ng3";
							$err_ix = $iy;
							// echo "ix=". $ix. " errMsg=". $errMsg. " item-name=". $_POST["item-name". $ix], " item-num=". $_POST["item-num". $ix]. " item-unit=". $_POST["item-unit". $ix]. " item-cat=". $_POST["item-cat". $ix]. "<br>";
						}
					}
					if (empty($_POST["item-unit". $ix])) {
						if ($errMsg == "") {
							$errMsg = "item_ng4";
							$err_ix = $iy;
							// echo "ix=". $ix. " errMsg=". $errMsg. " item-name=". $_POST["item-name". $ix], " item-num=". $_POST["item-num". $ix]. " item-unit=". $_POST["item-unit". $ix]. " item-cat=". $_POST["item-cat". $ix]. "<br>";
						}
					}
				}
			} else {
				if (!empty($_POST["item-name". $ix]) && !empty($_POST["item-num". $ix]) && !empty($_POST["item-unit". $ix])) {
					// echo "xxx ix=". $ix. "<br>";
					$q_name[$iy]   = $_POST['item-name'. $ix];
					$q_number[$iy] = preg_replace("/[^0-9-]/","", $_POST['item-num'. $ix]);
					$q_unit[$iy]   = $_POST['item-cat'. $ix];
					$q_price[$iy]  = preg_replace("/[^0-9-]/","", $_POST['item-unit'. $ix]);
					$q_total[$iy]  = preg_replace("/[^0-9-]/","", $_POST['item-total'. $ix]);

					if ($_POST["asset". $ix] != "" && $_POST["asset". $ix] != "---") {
						$q_asset[$iy]  = $_POST["asset". $ix];
						$q_use[$iy]    = $_POST["use". $ix];
						$q_detail[$iy] = $_POST["detail". $ix];
						$q_life[$iy]   = $_POST["life". $ix];
					}
					$iy++;
				} else if (!empty($_POST["item-name". $ix]) || !empty($_POST["item-num". $ix]) || !empty($_POST["item-unit". $ix])) {
					$q_name[$iy]   = $_POST['item-name'. $ix];
					$q_number[$iy] = preg_replace("/[^0-9-]/","", $_POST['item-num'. $ix]);
					$q_unit[$iy]   = $_POST['item-cat'. $ix];
					$q_price[$iy]  = preg_replace("/[^0-9-]/","", $_POST['item-unit'. $ix]);
					$q_total[$iy]  = preg_replace("/[^0-9-]/","", $_POST['item-total'. $ix]);

					if ($_POST["asset". $ix] != "" && $_POST["asset". $ix] != "---") {
						$q_asset[$iy]  = $_POST["asset". $ix];
						$q_use[$iy]    = $_POST["use". $ix];
						$q_detail[$iy] = $_POST["detail". $ix];
						$q_life[$iy]   = $_POST["life". $ix];
					}
					$iy++;
					// echo "ix=". $ix. "<br>";
					if (empty($_POST["item-name". $ix])) {
						if ($errMsg == "") {
							$errMsg = "item_ng1";
							$err_ix = $iy;
							// echo "ix=". $ix. " errMsg=". $errMsg. " item-name=". $_POST["item-name". $ix], " item-num=". $_POST["item-num". $ix]. " item-unit=". $_POST["item-unit". $ix]. " item-cat=". $_POST["item-cat". $ix]. "<br>";
						}
					}
					if (empty($_POST["item-num". $ix])) {
						if ($errMsg == "") {
							$errMsg = "item_ng2";
							$err_ix = $iy;
							// echo "ix=". $ix. " errMsg=". $errMsg. " item-name=". $_POST["item-name". $ix], " item-num=". $_POST["item-num". $ix]. " item-unit=". $_POST["item-unit". $ix]. " item-cat=". $_POST["item-cat". $ix]. "<br>";
						}
					}
					if (empty($_POST["item-unit". $ix])) {
						if ($errMsg == "") {
							$errMsg = "item_ng4";
							$err_ix = $iy;
							// echo "ix=". $ix. " errMsg=". $errMsg. " item-name=". $_POST["item-name". $ix], " item-num=". $_POST["item-num". $ix]. " item-unit=". $_POST["item-unit". $ix]. " item-cat=". $_POST["item-cat". $ix]. "<br>";
						}
					}
				}
			}
		}
		$q_count2 = $iy;
		// echo "q_count2=". $q_count2. "<br>";
	}

	if ($errMsg == "") {
		$q_alltotal = preg_replace('/[^0-9]/','', $_POST['q_alltotal']);
		if (!empty($_POST['q_cost'])) {
			$q_cost = preg_replace('/[^0-9]/','', $_POST['q_cost']);
		} else {
			$q_cost = 0;
		}
		$qu_price  = preg_replace('/[^0-9]/','', $_POST['qu_price']);
		$qu_commit = preg_replace('/[^0-9]/','', $_POST['qu_commit']);
		if (!empty($_POST['qu_initPayAmount'])) {
			$qu_initPayAmount = preg_replace('/[^0-9]/','', $_POST['qu_initPayAmount']);
		} else {
			$qu_initPayAmount = 0;
		}
		$qu_deposit    = preg_replace('/[^0-9]/','', $_POST['qu_deposit']);
		$qu_amount_pay = preg_replace('/[^0-9]/','', $_POST['qu_amount_pay']);
		$syohkei       = preg_replace('/[^0-9]/','', $_POST['syohkei']);
		$in_postal     = $_POST['in_postal1']. "-". $_POST['in_postal2'];

		if ($_SESSION['tmp1_qu_id'] == "") {
			$sql = sprintf('INSERT INTO `w1_quotation` SET c_id="%d", u_id="%d",
				qu_bunrui="%s", qu_custom_name="%s", qu_custom_no="%d",
				qu_name="%s", qu_location="%s", qu_paymentDate="%s",
				qu_deliveryDate="%s", qu_deposit="%s", qu_price="%s",
				qu_commission="%s", qu_commit="%s", qu_initPayAmount="%s",
				qu_amount_pay="%s", qu_installments="%s", qu_startDate="%s",
				qu_endDate="%s", q_cost="%s", q_alltotal="%s", in_companyName="%s",
				in_postal="%s", in_address1="%s", in_address2="%s",
				in_address3="%s", in_tel="%s", in_email="%s",
				in_contactName="%s", syohkei="%s", updated=NOW()',
				mysqli_real_escape_string($db, $qu_custom_id),
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $_POST['qu_bunrui']),
				mysqli_real_escape_string($db, $_POST['qu_custom_name']),
				mysqli_real_escape_string($db, $_POST['qu_custom_no']),
				mysqli_real_escape_string($db, $_POST['qu_name']),
				mysqli_real_escape_string($db, $_POST['qu_location']),
				mysqli_real_escape_string($db, $_POST['qu_paymentDate']),
				mysqli_real_escape_string($db, $_POST['qu_deliveryDate']),
				mysqli_real_escape_string($db, $qu_deposit),
				mysqli_real_escape_string($db, $qu_price),
				mysqli_real_escape_string($db, $_POST['qu_commission']),
				mysqli_real_escape_string($db, $qu_commit),
				mysqli_real_escape_string($db, $qu_initPayAmount),
				mysqli_real_escape_string($db, $qu_amount_pay),
				mysqli_real_escape_string($db, $_POST['qu_installments']),
				mysqli_real_escape_string($db, $qu_startDate),
				mysqli_real_escape_string($db, $qu_endDate),
				mysqli_real_escape_string($db, $q_cost),
				mysqli_real_escape_string($db, $q_alltotal),
				mysqli_real_escape_string($db, $_POST['in_companyName']),
				mysqli_real_escape_string($db, $in_postal),
				mysqli_real_escape_string($db, $_POST['in_address1']),
				mysqli_real_escape_string($db, $_POST['in_address2']),
				mysqli_real_escape_string($db, $_POST['in_address3']),
				mysqli_real_escape_string($db, $_POST['in_tel']),
				mysqli_real_escape_string($db, $_POST['in_email']),
				mysqli_real_escape_string($db, $_POST['in_contactName']),
				mysqli_real_escape_string($db, $syohkei)
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));

			$sql = sprintf('SELECT * FROM `w1_quotation` WHERE u_id="%d" AND delFlag=0
				ORDER BY qu_id DESC LIMIT 1',
				mysqli_real_escape_string($db, $_SESSION['loginID'])
			);
			$record = mysqli_query($db, $sql) or die(mysqli_error($db));
			$row0 = mysqli_fetch_assoc($record);

			$sql = sprintf('INSERT INTO `w1_q_memo` SET qu_id="%d", q_memo="%s"',
				mysqli_real_escape_string($db, $row0['qu_id']),
				mysqli_real_escape_string($db, $_POST['memo'])
			);
			mysqli_query($db, $sql) or die(mysqli_error($db));

			for($ix=0; $ix<60; $ix++) {
				if (!empty($_POST["item-name". $ix]) && $_POST["item-num". $ix] != 0) {
					$q_number[$ix] = preg_replace('/[^0-9-]/','', $_POST['item-num'. $ix]);		// 数量
					$q_unit[$ix]   = preg_replace('/[^0-9-]/','', $_POST['item-unit'. $ix]);	// 単価
					$q_total[$ix]  = preg_replace('/[^0-9-]/','', $_POST['item-total'. $ix]);	// 金額

					$sql = sprintf('INSERT INTO `w1_q_items` SET qu_id="%d", q_name="%s",
						q_number="%s", q_unit="%s", q_price="%s", q_total="%s"',
						mysqli_real_escape_string($db, $row0['qu_id']),
						mysqli_real_escape_string($db, $_POST["item-name". $ix]),
						mysqli_real_escape_string($db, $q_number[$ix]),
						mysqli_real_escape_string($db, $_POST["item-cat". $ix]),
						mysqli_real_escape_string($db, $q_unit[$ix]),
						mysqli_real_escape_string($db, $q_total[$ix])
					);
					mysqli_query($db,$sql) or die(mysqli_error($db));

					if ($_POST["asset". $ix] == "" || $_POST["asset". $ix] == "---") {
						$sql = sprintf('INSERT INTO `w1_q_item_dep` SET qu_id="%d",
							q_asset="", q_use="", q_detail="", q_life=""',
							mysqli_real_escape_string($db, $row0['qu_id'])
						);
						mysqli_query($db,$sql) or die(mysqli_error($db));
					} else {
						$sql = sprintf('INSERT INTO `w1_q_item_dep` SET qu_id="%d",
							q_asset="%s", q_use="%s", q_detail="%s", q_life="%s"',
							mysqli_real_escape_string($db, $row0['qu_id']),
							mysqli_real_escape_string($db, $_POST["asset". $ix]),
							mysqli_real_escape_string($db, $_POST["use". $ix]),
							mysqli_real_escape_string($db, $_POST["detail". $ix]),
							mysqli_real_escape_string($db, $_POST["life". $ix])
						);
						mysqli_query($db,$sql) or die(mysqli_error($db));
					}
				}
			}

			$_SESSION['tmp1_qu_id'] = $row0['qu_id'];
			w1_cust_data($row0['qu_id']);
		} else {
			$sql = sprintf('UPDATE `w1_quotation` SET c_id="%d", u_id="%d",
				qu_bunrui="%s", qu_custom_name="%s", qu_custom_no="%d",
				qu_name="%s", qu_location="%s", qu_paymentDate="%s",
				qu_deliveryDate="%s", qu_deposit="%s", qu_price="%s",
				qu_commission="%s", qu_commit="%s", qu_initPayAmount="%s",
				qu_amount_pay="%s", qu_installments="%s", qu_startDate="%s",
				qu_endDate="%s", q_cost="%s", q_alltotal="%s", in_companyName="%s",
				in_postal="%s", in_address1="%s", in_address2="%s",
				in_address3="%s", in_tel="%s", in_email="%s",
				in_contactName="%s", syohkei="%s", updated=NOW()
				WHERE qu_id="%d"',
				mysqli_real_escape_string($db, $qu_custom_id),
				mysqli_real_escape_string($db, $_SESSION['loginID']),
				mysqli_real_escape_string($db, $_POST['qu_bunrui']),
				mysqli_real_escape_string($db, $_POST['qu_custom_name']),
				mysqli_real_escape_string($db, $_POST['qu_custom_no']),
				mysqli_real_escape_string($db, $_POST['qu_name']),
				mysqli_real_escape_string($db, $_POST['qu_location']),
				mysqli_real_escape_string($db, $_POST['qu_paymentDate']),
				mysqli_real_escape_string($db, $_POST['qu_deliveryDate']),
				mysqli_real_escape_string($db, $qu_deposit),
				mysqli_real_escape_string($db, $qu_price),
				mysqli_real_escape_string($db, $_POST['qu_commission']),
				mysqli_real_escape_string($db, $qu_commit),
				mysqli_real_escape_string($db, $qu_initPayAmount),
				mysqli_real_escape_string($db, $qu_amount_pay),
				mysqli_real_escape_string($db, $_POST['qu_installments']),
				mysqli_real_escape_string($db, $qu_startDate),
				mysqli_real_escape_string($db, $qu_endDate),
				mysqli_real_escape_string($db, $q_cost),
				mysqli_real_escape_string($db, $q_alltotal),
				mysqli_real_escape_string($db, $_POST['in_companyName']),
				mysqli_real_escape_string($db, $in_postal),
				mysqli_real_escape_string($db, $_POST['in_address1']),
				mysqli_real_escape_string($db, $_POST['in_address2']),
				mysqli_real_escape_string($db, $_POST['in_address3']),
				mysqli_real_escape_string($db, $_POST['in_tel']),
				mysqli_real_escape_string($db, $_POST['in_email']),
				mysqli_real_escape_string($db, $_POST['in_contactName']),
				mysqli_real_escape_string($db, $syohkei),
				mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id'])
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));

//			$sql = sprintf('SELECT * FROM `w1_quotation` WHERE u_id="%d" AND delFlag=0
//				ORDER BY qu_id DESC LIMIT 1',
//				mysqli_real_escape_string($db, $_SESSION['loginID'])
//			);
//			$record = mysqli_query($db, $sql) or die(mysqli_error($db));
//			$row0 = mysqli_fetch_assoc($record);

			$sql = sprintf('UPDATE `w1_q_memo` SET q_memo="%s" WHERE qu_id="%d"',
				mysqli_real_escape_string($db, $_POST['memo']),
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

			for($ix=0; $ix<60; $ix++) {
				if (!empty($_POST["item-name". $ix]) && $_POST["item-num". $ix] != 0) {
					$q_number[$ix] = preg_replace('/[^0-9-]/','', $_POST['item-num'. $ix]);		// 数量
					$q_unit[$ix]   = preg_replace('/[^0-9-]/','', $_POST['item-unit'. $ix]);	// 単価
					$q_total[$ix]  = preg_replace('/[^0-9-]/','', $_POST['item-total'. $ix]);	// 金額

					$sql = sprintf('INSERT INTO `w1_q_items` SET qu_id="%d", q_name="%s",
						q_number="%s", q_unit="%s", q_price="%s", q_total="%s"',
						mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id']),
						mysqli_real_escape_string($db, $_POST["item-name". $ix]),
						mysqli_real_escape_string($db, $q_number[$ix]),
						mysqli_real_escape_string($db, $_POST["item-cat". $ix]),
						mysqli_real_escape_string($db, $q_unit[$ix]),
						mysqli_real_escape_string($db, $q_total[$ix])
					);
					mysqli_query($db,$sql) or die(mysqli_error($db));

					if ($_POST["asset". $ix] == "" || $_POST["asset". $ix] == "---") {
						$sql = sprintf('INSERT INTO `w1_q_item_dep` SET qu_id="%d",
							q_asset="", q_use="", q_detail="", q_life=""',
							mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id'])
						);
						mysqli_query($db,$sql) or die(mysqli_error($db));
					} else {
						$sql = sprintf('INSERT INTO `w1_q_item_dep` SET qu_id="%d",
							q_asset="%s", q_use="%s", q_detail="%s", q_life="%s"',
							mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id']),
							mysqli_real_escape_string($db, $_POST["asset". $ix]),
							mysqli_real_escape_string($db, $_POST["use". $ix]),
							mysqli_real_escape_string($db, $_POST["detail". $ix]),
							mysqli_real_escape_string($db, $_POST["life". $ix])
						);
						mysqli_query($db,$sql) or die(mysqli_error($db));
					}
				}
			}
			$row0['qu_id'] = $_SESSION['tmp1_qu_id'];
			w1_cust_update($row0['qu_id']);
		}
		header("Location:/manage/estimates/new/simulation.php?new=1&id=". $row0['qu_id']);
		exit();

		// $url = "<script type='text/javascript'>window.open('/manage/estimates/new/pdfmake.php?id=". $row0['qu_id']. "', '_blank');</script>";
		// echo $url;

		// $errMsg = "OK";
	}
}

if ($errMsg == "") {
	if ($row9['u_type'] == "個人") {
		$in_companyName2 = $row9['p_name'];
		$in_tel2         = $row9['p_tel'];
		$in_postal2      = $row9['p_postal'];
		$in_address12    = $row9['p_address1'];
		$in_address22    = $row9['p_address2'];
		$in_address32    = $row9['p_address3'];
		$in_email2       = $row9['p_email'];
	} else {
		$in_companyName2 = $row9['u_company'];
		$in_tel2         = $row9['u_tel'];
		$in_postal2      = $row9['u_postal'];
		$in_address12    = $row9['u_address1'];
		$in_address22    = $row9['u_address2'];
		$in_address32    = $row9['u_address3'];
		$in_email2       = $row9['u_email'];
		$in_contactName2 = $row9['u_person'];
	}
	if ($in_postal2 != "") {
		$str = strstr($in_postal2, '-', TRUE);
		if ($str != "") {
			$in_postal21 = $str;
			$in_postal22 = substr($in_postal2, (strpos($in_postal2, '-') + 1));
		} else {
			$leng = strlen($in_postal2);
			if ($leng > 2) {
				$in_postal21 = substr($in_postal2, 0, 3);
				$in_postal22 = substr($in_postal2, 3);
			} else {
				$in_postal21 = substr($in_postal2, 0, $leng);
				$in_postal22 = "";
			}
		}
	} else {
		$in_postal21 = "";
		$in_postal22 = "";
	}
}
/*	--------------------------------------------------

	DBに接続して値を取得

--------------------------------------------------	*/

$sql = sprintf('SELECT * FROM `customer` WHERE u_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$ix = 0;
while($row0 = mysqli_fetch_assoc($record)) {
	$c_id[$ix]   = $row0['c_id'];
	$c_name[$ix] = $row0['c_name'];
	$custom_id[$ix] = sprintf("%06s", $c_id[$ix]);
	$ix++;
}
$custom_count = $ix;

if ($custom_count == 0) {
	$_SESSION['custom_no'] = "ZERO";
	header("Location:/manage/estimates");
	exit();
}

if ($errMsg == "") {
	if ($_SESSION['tmp1_qu_id'] != "") {
		// echo "tmp1_qu_id=". $_SESSION['tmp1_qu_id']. "<br>";

		$sql = sprintf('SELECT * FROM `w1_quotation` WHERE qu_id="%d"',
			mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id'])
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$row7 = mysqli_fetch_assoc($record);

		$qu_bunrui2        = $row7['qu_bunrui'];
		$qu_custom_name2   = $row7['qu_custom_name'];
		$qu_custom_no2     = $row7['qu_custom_no'];
		$qu_name2          = $row7['qu_name'];
		$qu_location2      = $row7['qu_location'];
		$qu_paymentDate2   = $row7['qu_paymentDate'];
		$qu_deliveryDate2  = $row7['qu_deliveryDate'];
		$qu_deposit2 	   = number_format((int) $row7['qu_deposit']);		// 頭金
		$qu_price2         = $row7['qu_price'];
		$qu_commission2    = $row7['qu_commission'];				// 割賦手数料率
		$qu_commit2 	   = number_format($row7['qu_commit']);			// 割賦手数料
		$qu_initPayAmount2 = number_format((int) $row7['qu_initPayAmount']);	// 初回お支払額
		$qu_amount_pay2    = $row7['qu_amount_pay'];
		$qu_installments2  = $row7['qu_installments'];
		$qu_startDate2     = $row7['qu_startDate'];
		$qu_endDate2       = $row7['qu_endDate'];
		$q_cost2           = $row7['q_cost'];
		$q_alltotal2       = $row7['q_alltotal'];
		$in_companyName2   = $row7['in_companyName'];
		$in_postal2        = $row7['in_postal'];
		$in_address12      = $row7['in_address1'];
		$in_address22      = $row7['in_address2'];
		$in_address32      = $row7['in_address3'];
		$in_tel2           = $row7['in_tel'];
		$in_email2         = $row7['in_email'];
		$in_contactName2   = $row7['in_contactName'];
		$syohkei2          = $row7['syohkei'];

		if ($in_postal2 != "") {
			$str = strstr($in_postal2, '-', TRUE);
			if ($str != "") {
				$in_postal21 = $str;
				$in_postal22 = substr($in_postal2, (strpos($in_postal2, '-') + 1));
			} else {
				$leng = strlen($in_postal2);
				if ($leng > 2) {
					$in_postal21 = substr($in_postal2, 0, 3);
					$in_postal22 = substr($in_postal2, 3);
				} else {
					$in_postal21 = substr($in_postal2, 0, $leng);
					$in_postal22 = "";
				}
			}
		} else {
			$in_postal21 = "";
			$in_postal22 = "";
		}

		// 見積もり項目
		$sql = sprintf('SELECT * FROM `w1_q_items` WHERE qu_id="%d"',
			mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id'])
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$ix = 0;
		while($row0 = mysqli_fetch_assoc($record)) {
			$q_id[$ix]     = $row0['q_id'];
			$q_name[$ix]   = $row0['q_name'];
			$q_number[$ix] = $row0['q_number'];
			$q_unit[$ix]   = $row0['q_unit'];
			$q_price[$ix]  = $row0['q_price'];
			$q_total[$ix]  = $row0['q_total'];
			$ix++;
		}
		$q_count = $ix;

		// 減価償却項目
		$sql = sprintf('SELECT * FROM `w1_q_item_dep` WHERE qu_id="%d"',
			mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id'])
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$ix = 0;
		while($row0 = mysqli_fetch_assoc($record)) {
			$dep_id[$ix]   = $row0['dep_id'];
			$q_asset[$ix]  = $row0['q_asset'];
			$q_use[$ix]    = $row0['q_use'];
			$q_detail[$ix] = $row0['q_detail'];
			$q_life[$ix]   = $row0['q_life'];
			$ix++;
		}

		// 見積もり項目のメモ
		$sql = sprintf('SELECT * FROM `w1_q_memo` WHERE qu_id="%d"',
			mysqli_real_escape_string($db, $_SESSION['tmp1_qu_id'])
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$row8 = mysqli_fetch_assoc($record);
		$memo2 = $row8['q_memo'];
	}
}

if ($errMsg == "item_ng1" || $errMsg == "item_ng2" || $errMsg == "item_ng3" || $errMsg == "item_ng4") {
	$q_count = $q_count2;
}

if ($qu_installments2 == "") {
	$qu_installments2 = 0;
}
// echo "qu_installments2=". $qu_installments2;


// echo "errMsg=". $errMsg. " qu_bunrui2=". $qu_bunrui2. " qu_startDate2=". $qu_startDate2. " P_qu_startDate=". $_POST['qu_startDate'];
// echo "errMsg=". $errMsg. " q_count=". $q_count;
// for($ix=0; $ix<$q_count; $ix++) {
//	echo "dep_id". $dep_id[$ix]. "q_asset". $q_asset[$ix]. "q_use". $q_use[$ix]. "q_detail". $q_detail[$ix]."q_life". $q_life[$ix]. "<br>";
// }
?>
<?php require_once(__DIR__ . '/../../common/header.php'); ?>
	<link rel="stylesheet" type="text/css" href="/manage/common/css/jquery.datetimepicker.css">
	<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.10.4/themes/black-tie/jquery-ui.css">
	<style>
		@media screen and (min-width: 896px) {
			.flex-box {
			  display: flex;		/* フレックスボックスにする */
			}
		}
		.flex-item {
		  border-radius: 3px;		/* 角丸指定 */
		  text-align: center;		/* 文字中央揃え */
		}
		@media screen and (min-width: 896px) {
			.flex-item {
				padding: 1px;
				margin: 1px;
			}
		}
		@media screen and (max-width: 895px) {
			.flex-item {
				margin-top: 5px;
			}
		}
		.flex-item:nth-child(1) {
		  flex:2 1 80px;		/* 幅指定 */
		}
		.flex-item:nth-child(2) {
		  flex-basis: 150px;		/* 幅指定 */
		}
		.flex-item:nth-child(3) {
		  flex-basis: 140px;		/* 幅指定 */
		}
		.btn-info[disabled] {
		  cursor: not-allowed;
		}
		.common_submit_button2 {
		  width: 300px;
		  margin: 45px auto auto;
		}
		.common_submit_button2 input[type=submit] {
		  display: block;
		  width: 100%;
		  text-align: center;
		  padding: 15px 0px;
		  color: #fff;
		  border-radius: 3px;
		  font-weight: 700;
		  background: #9AD1B8;
		}
		@media screen and (min-width: 896px) {
		  .common_submit_button2 input[type=submit] {
		    padding: 20px 0px;
		  }
		}
		.common_submit_button2 input[type=submit]:hover {
		  opacity: 0.8;
		}
		.error {
		  color: #8a0421 !important;
		  border-color: #dd0f3b !important;
		  background-color: #ffd9d9 !important;
		}
	</style>
	<main>
		<div class="main_inner">
			<?php require_once(__DIR__ .'/../../common/grobal-menu.php'); ?>
			<div class="main_wrap">
				<div class="main_title">
					<div class="all_wrapper">
						<div class="main_pankuzu">
							<ul>
								<li><span><a href="/manage/estimates/" class="text_link">見積書一覧</a></span></li>
								<li><a href="">新規見積書作成</a></li>
							</ul>
						</div>
						<div class="main_title_inner">
							<div class="main_title_top">
								<p class="title">新規見積書作成</p>
								<?php if ($errMsg == "customErr") { ?>
									<font color="#f05b72">※請求先と顧客番号が不一致です。どちらか一方を指定してください</font>
								<?php } ?>
								<?php if ($errMsg == "customNoErr") { ?>
									<font color="#f05b72">※請求先または顧客番号のどちらかを指定してください。</font>
								<?php } ?>
								<?php if ($errMsg == "str_date") { ?>
									<font color="#f05b72">※返済開始予定年月を正しく設定してください。</font>
								<?php } ?>
								<?php if ($errMsg == "end_date") { ?>
									<font color="#f05b72">※返済終了予定年月を正しく設定してください。</font>
								<?php } ?>
								<?php if ($errMsg == "delivery_ng") { ?>
									<font color="#f05b72">※受渡期日を設定してください。</font>
								<?php } ?>
								<?php if ($errMsg == "str_date2") { ?>
									<font color="#f05b72">※<?php echo $qu_startDate2; ?>は存在しません。</font>
								<?php } ?>
								<?php if ($errMsg == "paymentDateerror") { ?>
									<font color="#f05b72">※支払日を設定してください。</font>
								<?php } ?>
								<?php if ($errMsg == "tel_no") { ?>
									<p class="error_message">携帯電話番号のみ11桁を指定してください。</p>
								<?php } ?>
								<?php if ($errMsg == "item_ng1") { ?>
									<font color="#f05b72">※<?php echo $err_ix; ?>番目の「適用」(見積項目を記入)欄が設定されていません。</font>
								<?php } ?>
								<?php if ($errMsg == "item_ng2") { ?>
									<font color="#f05b72">※<?php echo $err_ix; ?>番目の「数量」欄が設定されていません。</font>
								<?php } ?>
								<?php if ($errMsg == "item_ng3") { ?>
									<font color="#f05b72">※<?php echo $err_ix; ?>番目の「単位」欄が設定されていません。</font>
								<?php } ?>
								<?php if ($errMsg == "item_ng4") { ?>
									<font color="#f05b72">※<?php echo $err_ix; ?>番目の「単価(税抜き)」欄が設定されていません。</font>
								<?php } ?>
								<?php if ($_SESSION['estim_new'] == "OK") { ?>
									<font color="#f05b72">※見積データを登録しました。</font>
									<?php $_SESSION['estim_new'] = ""; ?>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div id="estimateCalculate" class="main_content estimates_new">
					<div class="all_wrapper sp_all">
						<div class="main_content_inner estimates_new_inner">
							<form action="" method="post" accept-charset="utf-8">
								<div class="main_content_wrap estimates_new_wrap">
									<div class="estimates_new_content">
										<div class="estimates_new_content_inner cf">
											<div class="estimates_company_info">
												<?php if (empty($_SESSION['tmp1_qu_id'])) { ?>
													<p class="company_title">見積先情報</p>
												<?php } else { ?>
													<p class="company_title">見積先情報
														<a class="must_form" href="/manage/estimates/new/qu_clear.php?id=<?php echo $_SESSION['tmp1_qu_id']; ?>">データクリア</a>
													</p>
												<?php } ?>
												<div class="estimates_company_info_inner">
													<table class="estimates_new_company">
														<tr>
															<th class="must_form">対象分類</th>
															<td>
																<ul class="item_type cf">
																	<?php if ($qu_bunrui2 == "工事" || $qu_bunrui2 == "") { ?>
																		<li class="select_type koji selected">
																			<span class="tab_panel"><input type="radio" name="qu_bunrui" value="工事" checked>工事</span>
																		</li>
																	<?php } else { ?>
																		<li class="select_type koji">
																			<span class="tab_panel"><input type="radio" name="qu_bunrui" value="工事">工事</span>
																		</li>
																	<?php } ?>
																	<?php if ($qu_bunrui2 == "商品") { ?>
																		<li class="select_type syohin selected">
																			<span class="tab_panel"><input type="radio" name="qu_bunrui" value="商品" checked>商品</span>
																		</li>
																	<?php } else { ?>
																		<li class="select_type syohin">
																			<span class="tab_panel"><input type="radio" name="qu_bunrui" value="商品">商品</span>
																		</li>
																	<?php } ?>
																</ul>
															</td>
														</tr>
														<tr>
															<th class="must_form">請求先</th>
															<td id="client_name">
																<span class="select_box">
																	<select name="qu_custom_name" class="ajax_name">
																		<option value="" hidden>-</option>
																		<?php for($ix=0; $ix<$custom_count; $ix++) { ?>
																			<?php if ($qu_custom_name2 == $c_id[$ix]) { ?>
																				<option value="<?php echo $c_id[$ix]; ?>" selected><?php echo $c_name[$ix]; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $c_id[$ix]; ?>"><?php echo $c_name[$ix]; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select>
																</span>
															</td>
														</tr>
														<tr>
															<th>顧客番号</th>
															<td id="client_number">
																<span class="select_box">
																	<select name="qu_custom_no" class="ajax_number">
																		<option value="" hidden>-</option>
																		<?php for($ix=0; $ix<$custom_count; $ix++) { ?>
																			<?php if ($qu_custom_name2 == $c_id[$ix]) { ?>
																				<option value="<?php echo $c_id[$ix]; ?>" selected><?php echo $custom_id[$ix]; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $c_id[$ix]; ?>"><?php echo $custom_id[$ix]; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select>
																</span>
															</td>
														</tr>
														<tr>
															<?php if ($qu_bunrui2 == "工事" || $qu_bunrui2 == "") { ?>
																<th id="item_name" class="must_form"><span>工事</span>名称</th>
															<?php } else { ?>
																<th id="item_name" class="must_form"><span>商品</span>名称</th>
															<?php } ?>
															<td><input type="text" name="qu_name" value="<?php echo $qu_name2; ?>" required></td>
														</tr>
														<tr>
															<?php if ($qu_bunrui2 == "工事" || $qu_bunrui2 == "") { ?>
															<th id="item_place" class="must_form"><span>工事場所</span></th>
															<?php } else { ?>
															<th id="item_place" class="must_form"><span>納品場所</span></th>
															<?php } ?>
															<td><input type="text" name="qu_location" value="<?php echo $qu_location2; ?>" required></td>
														</tr>
														<tr>
															<th class="must_form">支払日</th>
															<td class="spilt_ara">
																<span class="select_box">
																	<select name="qu_paymentDate" required>
																		<!-- <option v-for="day of 31" :key="day" v-bind:value="day">{{ day }}</option> -->
																		<?php if ($row7['qu_paymentDate'] != "") { ?>
																			<option value="<?php echo $row7['qu_paymentDate']; ?>" selected><?php echo $row7['qu_paymentDate']; ?></option>
																		<?php } ?>
																		<option value="">-</option>
																		<option value="1">1</option>
																		<option value="2">2</option>
																		<option value="3">3</option>
																		<option value="4">4</option>
																		<option value="5">5</option>
																		<option value="6">6</option>
																		<option value="7">7</option>
																		<option value="8">8</option>
																		<option value="9">9</option>
																		<option value="10">10</option>
																		<option value="11">11</option>
																		<option value="12">12</option>
																		<option value="13">13</option>
																		<option value="14">14</option>
																		<option value="15">15</option>
																		<option value="16">16</option>
																		<option value="17">17</option>
																		<option value="18">18</option>
																		<option value="19">19</option>
																		<option value="20">20</option>
																		<option value="21">21</option>
																		<option value="22">22</option>
																		<option value="23">23</option>
																		<option value="24">24</option>
																		<option value="25">25</option>
																		<option value="26">26</option>
																		<option value="27">27</option>
																		<option value="28">28</option>
																		<option value="29">29</option>
																		<option value="30">30</option>
																		<!-- <option value="31">31</option> -->
																		<option value="末日">末日</option>
																		<?php if ($qu_paymentDate2 != "") { ?>
																			<option value="<?php echo $qu_paymentDate2; ?>" selected><?php echo $qu_paymentDate2; ?></option>
																		<?php } ?>
																	</select>
																</span>
																日
															</td>
														</tr>
														<tr>
															<th class="must_form">割賦手数料率</th>
															<td><input class="text-center sp-text-left" type="text" name="qu_commission" v-model:value="adminPer" title="半角数字をご入力ください"></td>
														</tr>
														<tr>
															<th class="must_form">分割回数</th>
															<td class="spilt_ara">
																<span class="select_box">
																	<select name="qu_installments" v-model="numberPayments" required>
																		<option v-for="split of 45" :key="split" v-bind:value="split+3">{{ split + 3 }}</option>
																	</select>
																</span>
																回払い
															</td>
														</tr>
														<tr>
															<th class="must_form">頭金</th>
															<td><input class="text-center sp-text-left" type="text" name="qu_deposit" @focus="depositlInInput" @blur="depositlOutInput" v-model="downPayment" title="半角数字をご入力ください"></td>
														</tr>
														<tr>
															<th class="must_form " style="white-space: nowrap">返済開始予定年月</th>
															<td>
																<vuejs-datepicker
																	:format="customFormatter"
																	:language="ja"
																	:maximum-view="'month'"
																	:minimum-view="'month'"
																	:initial-view="'month'"
																	class="text-center sp-text-left"
																	required="required"
																	name="qu_startDate"
																	v-model="paymentStart"
																>
																</vuejs-datepicker>
																<!-- <input id="select_ym" class="text-center" type="text" name="qu_startDate" v-model="paymentStart" value="<?php echo $qu_startDate2; ?>" required></td> -->
															</td>
														</tr>
														<!-- fin 変更 200511_kan -->
														<tr>
															<th style="white-space: nowrap">返済終了予定年月</th>
															<!-- <td><input class="calculation_area text-center" type="text" name="qu_endDate" value="<?php echo $qu_endDate2; ?>" readonly="readonly" v-model="paymentFinish" title="自動計算エリア"></td> -->
															<td><input class="calculation_area text-center sp-text-left" type="text" name="qu_endDate" readonly="readonly" v-model="paymentFinish" title="自動計算エリア"></td>
														</tr>
														<tr>
															<th class="must_form">受渡期日</th>
															<!-- <td><input type="date" name="qu_deliveryDate" value="<?php echo $row7['qu_deliveryDate']; ?>" required></td> -->
															<td>
																<vuejs-datepicker
																	:format="customFormat"
																	:language="ja"
																	class="text-center sp-text-left"
																	name="qu_deliveryDate"
                                                                    v-model="deliveryDate"
                                                                    required="required"
																>
																</vuejs-datepicker>
															</td>
														</tr>
													</table>
												</div>
											</div>
											<div class="estimates_company_info">
												<p class="company_title">見積元情報</p>
												<div class="estimates_company_info_inner">
													<table class="estimates_new_company">
														<tr>
															<?php if ($row9['u_type'] == "個人") { ?>
															<th class="must_form">名前</th>
															<?php } else { ?>
															<th class="must_form">自社名</th>
															<?php } ?>
															<td><input type="text" name="in_companyName" value="<?php echo $in_companyName2; ?>" required></td>
														</tr>
														<tr>
															<th class="must_form">〒</th>
															<td class="post">
															  <input type="text" name="in_postal1" value="<?php echo $in_postal21; ?>" maxlength="3" placeholder="123" required>-<input type="text" name="in_postal2" value="<?php echo $in_postal22; ?>" maxlength="4" placeholder="4567" onKeyUp="AjaxZip3.zip2addr('in_postal1','in_postal2','in_address1','in_address2','in_address2');" required>
															</td>
														</tr>
														<tr>
															<th class="must_form">都道府県</th>
															<td><input type="text" name="in_address1" value="<?php echo $in_address12; ?>" required></td>
														</tr>
														<tr>
															<th class="must_form">市区町村</th>
															<td><input type="text" name="in_address2" value="<?php echo $in_address22; ?>" required></td>
														</tr>
														<tr>
															<th class="must_form">番地</th>
															<td><input type="text" name="in_address3" value="<?php echo $in_address32; ?>" required></td>
														</tr>
														<tr>
															<th class="must_form">TEL</th>
															<td><input type="tel" name="in_tel" value="<?php echo $in_tel2; ?>" required></td>
														</tr>
														<tr>
															<th class="must_form">メールアドレス</th>
															<td><input type="email" name="in_email" value="<?php echo $in_email2; ?>" required></td>
														</tr>
														<?php if ($row9['u_type'] != "個人") { ?>
														<tr>
															<th>担当者名</th>
															<td><input type="text" name="in_contactName" value="<?php echo $in_contactName2; ?>"></td>
														</tr>
														<?php } ?>
													</table>
												</div>
											</div>
										</div>
									</div>
									<!-- <input class="calculation_area bold text-right" type="hidden" name="q_cost" v-model="shokeiTax" readonly="readonly"> -->
									<div class="estimates_new_content">
										<div class="estimates_new_content_inner">
											<div class="estimates_table_inner">
												<div class="estimates_table">
													<div class="estimates_table_inner">
														<p class="company_title sp">見積項目</p>
														<div class="estimates_table_field">
															<div class="estimates_table_box estimates_table_itemlist">
																<table class="estimates_table_item">
																	<thead>
																		<tr>
																			<th></th>
																			<th>適用</th>
																			<th>数量</th>
																			<th>単位</th>
																			<th>単価(税抜き)</th>
																			<th>金額(税抜き)</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr v-for="(list, i) in lists" :key="i" v-cloak>
																			<td class="num">
																				<span v-on:click="remove(i)">
																					<span class="item_delete"><img src="/manage/img/icon_batu_white.png"></span>
																					<span class="sp delete_text">項目を削除する</span>
																				</span>
																			</td>
																			<td>
																				<span class="item_detail_title">適用</span>
																				<span class="item_detail_name">
																					<input type="text"
																						:name="['item-name' + i]"
																						v-model="list.name"
																						placeholder="見積項目を記入"
																						maxlength="50"
																						title="50文字以内">
																				</span>
																				<div class="item_detail_name">
																					<div class="flex-box">
																					  <div class="flex-item">
																					    <span class="select_box">
																					      	<!-- <select style="border-radius:2px; background:#f5f5f5;"
																								:name="['asset' + i]"
																								v-model="list.asset"
																						      	@change="fetchAsset(i)"> -->
																					      	<select
																								:name="['asset' + i]"
																								v-model="list.asset"
																						      	@change="fetchAsset(i)">
																						        <option
																									v-for="(item, index) of items"
																									:key="item.index"
																									:value="item.asset">{{ item.asset }}</option>
																					      </select>
																					    </span>
																					  </div>
																					  <div class="flex-item">
																					    <span class="select_box">
																					      <select
																							:name="['use' + i]"
																							v-model="list.use"
																							@change="fetchUse(i)" :class="status(lists[i].use_required)">
																					      	<!-- <select style="border-radius:2px; background:#f5f5f5;"
																					      		:name="['use' + i]"
																					      		v-model="list.use"
																					      		@change="fetchUse(i)"> -->
																					        <option
																								v-for="(type, index) of lists[i].itypes"
																								:key="type.index"
																								:value="type.use">{{ type.use }}</option>
																					      </select>
																					    </span>
																					  </div>
																					  <div class="flex-item">
																					    <span class="select_box">
																					      <select
																							:name="['detail' + i]"
																							v-model="list.label"
																							v-on:change="fetchLabel(i)" :class="status(lists[i].label_required)">
																					      	<!-- <select style="border-radius:2px; background:#f5f5f5;"
																					      		:name="['detail' + i]"
																					      		v-model="list.label"
																					      		v-on:change="fetchLabel(i)"> -->
																					        <option
																								v-for="(detail, index) of lists[i].idetails"
																								:key="detail.index"
																								:value="detail.label">{{ detail.label }}</option>
																					      </select>
																					    </span>
																					  </div>
																					</div>
																					<div>
																						<input type="hidden" style="border-radius:2px; background: #f5f5f5;" :name="['life' + i]" :value="list.life">
																					</div>
																				</div>
																			</td>
																			<td>
																				<span class="item_detail_title">数量</span>
																				<span class="item_detail_name">
																					<input class="text-center dot_area sp-text-left"
																						type="text"
																						v-bind:name="['item-num' + i]"
																						v-bind:value="list.num|number_format"
																						@change="list.num = $event.target.value"
																						oninput="this.value=this.value.replace(/[^0-9-]+/i,'')"
																						placeholder="数量を記入">
																				</span>
																			</td>
																			<td class="spilt_ara">
																				<span class="item_detail_title">単位</span>
																				<span class="item_detail_name">
																					<select class="text-center" :name="['item-cat' + i]" v-model="list.cat">
																						<option value="">未選択</option>
																						<option value="m">m</option>
																						<option value="m2">m2</option>
																						<option value="立方メートル">立方メートル</option>
																						<option value="ケース">ケース</option>
																						<option value="セット">セット</option>
																						<option value="台">台</option>
																						<option value="枚">枚</option>
																						<option value="本">本</option>
																						<option value="缶">缶</option>
																						<option value="袋">袋</option>
																						<option value="束">束</option>
																						<option value="個">個</option>
																						<option value="式">式</option>
																					</select>
																				</span>
																			</td>
																			<td>
																				<span class="item_detail_title">単価(税抜き)</span>
																				<span class="item_detail_name">
																					<input class="text-right dot_area sp-text-left"
																						type="text"
																						:name="['item-unit' + i]"
																						:value="list.unit|number_format"
																						oninput="this.value=this.value.replace(/[^0-9-]+/i,'')"
																						@change="list.unit = $event.target.value"
																						placeholder="単価(税抜き)記入">
																				</span>
																			</td>
																			<td>
																				<span class="item_detail_title">金額</span>
																				<span class="item_detail_name">
																					<input class="text-right sp-text-left" type="text" v-bind:name="['item-total' + i]" v-model="sum(list)" readonly="readonly" style="pointer-events:none;">
																				</span>
																			</td>
																		</tr>
																		<tr class="shokei">
																			<td></td>
																			<td>
																				<a v-on:click="addItem" style="cursor:pointer;">
																					<span class="shokei_img"><img src="/manage/img/icon_add_white.png" alt=""></span>新規項目追加
																				</a>
																			</td>
																			<td colspan="3"><br>小計<br>(内消費税)</td>
																			<td>
																				<input class="text-right" type="hidden" name="syohkei" v-model="shokei" readonly="readonly" title="自動計算エリア"><br>
																				<input class="calculation_area bold text-right" type="hidden" name="q_cost" v-model="shokeiTax" readonly="readonly">
																				<!-- <p class="text-right" v-model="shokei"><font size="3">{{ shokei }}</font></p> -->
																				<!-- <p class="text-right" v-model="shokeiTax">({{ shokeiTax }})</p> -->
																				<p class="text-right"><font size="3">{{ shokeiTotal }}</font></p>
																				<p class="text-right">({{ shokeiTax }})</p>
																			</td>
																		</tr>
																		<input type="hidden" name="txt_cont" value="<?php echo $row7['txt_cont']; ?>">
																		<input type="hidden" id="tline" name="txt_line" value="<?php echo $row7['txt_line']; ?>">
																		<tr class="remarks">
																			<td></td>
																			<td colspan="5" class="remarks">
																				<p>【備考】</p>
																				<textarea name="memo" id="rtxt" onblur="chk()"><?php echo $memo2; ?></textarea>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="common_submit_button2">
									<input type="submit" name="send2" class="btn btn-info" value="シミュレーション画面へ">
								</div>
								<div class="estimates_new_total">
									<div class="estimates_new_total_inner">
										
										<div class="estimates_new_total_top">
											<table>
												<tbody>
													<tr>
														<th>価格（税込）</th>
														<td><input type="text" name="qu_price" v-model="shokei" readonly="readonly" title="自動計算エリア">></td>
													</tr>
													<tr>
														<th>事務管理手数料</th>
														<td><input type="text" name="qu_commit" v-model="adminFee" readonly="readonly" title="自動計算エリア"></td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="estimates_new_total_bottom cf">
											<div class="left">
												<p>見積もり金額</p>
											</div>
											<div class="right">
												<table>
													<tbody>
														<tr>
															<th colspan="2"><input type="text" name="q_alltotal" v-model="estimatesPrice" readonly="readonly" title="自動計算エリア"></th>
														</tr>
														<tr>
															<td>月々お支払額</td>
															<td><input type="text" name="qu_amount_pay" v-model="monthlyPayment" readonly="readonly" value="0" title="自動計算エリア"></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="/manage/estimates/new/ajax/ajax_test.js"></script> <!-- ajax（非同期）通信 -->
	<script src="/manage/common/js/customer-data.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>　<!-- vue.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> <!-- input dateの修正 -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment.min.js"></script> <!-- 日付の計算 -->
	<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script> <!-- 住所自動登録 -->
	<!-- 追加 200512_kan -->
	<script src="https://unpkg.com/vuejs-datepicker"></script>
	<script src="https://cdn.jsdelivr.net/npm/vuejs-datepicker@1.6.2/dist/locale/translations/ja.js"></script>
	<!-- fin 追加 200512_kan -->
	<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
	<!-- fin 追加 200928 -->
	<script src="https://cdn.jsdelivr.net/npm/vuelidate@0.7.4/dist/validators.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vuelidate@0.7.4/dist/vuelidate.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<script>
		if (!Array.prototype.find) {
		  Object.defineProperty(Array.prototype, 'find', {
		    value: function(predicate) {
		     // 1. Let O be ? ToObject(this value).
		      if (this == null) {
		        throw new TypeError('"this" is null or not defined');
		      }

		      var o = Object(this);

		      // 2. Let len be ? ToLength(? Get(O, "length")).
		      var len = o.length >>> 0;

		      // 3. If IsCallable(predicate) is false, throw a TypeError exception.
		      if (typeof predicate !== 'function') {
		        throw new TypeError('predicate must be a function');
		      }

		      // 4. If thisArg was supplied, let T be thisArg; else let T be undefined.
		      var thisArg = arguments[1];

		      // 5. Let k be 0.
		      var k = 0;

		      // 6. Repeat, while k < len
		      while (k < len) {
		        // a. Let Pk be ! ToString(k).
		        // b. Let kValue be ? Get(O, Pk).
		        // c. Let testResult be ToBoolean(? Call(predicate, T, « kValue, k, O »)).
		        // d. If testResult is true, return kValue.
		        var kValue = o[k];
		        if (predicate.call(thisArg, kValue, k, o)) {
		          return kValue;
		        }
		        // e. Increase k by 1.
		        k++;
		      }

		      // 7. Return undefined.
		      return undefined;
		    }
		  });
		}
	</script>
	<script>
		$(function(){
			// 対象分類　タブ機能
			$('.select_type').click(function(){
				$('.selected').removeClass('selected');
				$(this).addClass('selected');
				selectName = $(this).find('input').val();
				$('#item_name span').text(selectName);
				if($(".koji").hasClass('selected')) {
					$('#item_place span').text("工事場所");
				} else {
					$('#item_place span').text("納品場所");
				}
				$('#item_price span').text(selectName);
			});

			$("input").on("keydown", function(e) {
				if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
					return false;
				} else {
					return true;
				}
			});
			// selectと同じようにonchangeでラベルを変えたいときはJSを用いる
			$('.select_field select').on('change', function(){
				var $this = $(this)
				var $option = $this.find('option:selected');
				$(this).prev('.select_label').text($option.text());
				$this.blur();
			});
		});
		// ￥＋3桁毎に点を入れる
		$(function() {
			// 初回お支払額
			// フォーカスを離した時
			$('input.number_input').on('blur', function() {
				var num = $(this).val();
				num = num.replace(/[^0-9]/g, '');
				num = '¥' + num.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
				$(this).val(num);
			});
			// フォーカスした時
			$('input.number_input').on('focus', function() {
				var num = $(this).val();
				num = num.replace(/,/g, '').replace(/¥/g, '');
			    $(this).val(num);
			})
		});

		// 対象分類　タブ機能
		$(function(){
			$('.select_type').click(function(){
				$('.selected').removeClass('selected');
				$(this).addClass('selected');
			});
		});

		// 3桁毎に点を入れる
		$(function() {
			// フォーカスを離した時
			$('input.dot_area').on('blur', function() {
				var num = $(this).val();
				num = num.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
				$(this).val(num);
			});
			// フォーカスした時
			$('input.dot_area').on('focus', function() {
				var num = $(this).val();
				num = num.replace(/,/g, '');
			    $(this).val(num);
			})
		});

		(function() {
			// 項目を追加する機能
			var estimateCalculate = new Vue ({
				el: '#estimateCalculate',
				data:function () {
					return {
						ja: vdp_translation_ja.js,
						adminPer:       "<?php echo $qu_commission2; ?>",		// 事務管理手数料率
						downPayment:    "<?php echo $qu_deposit2; ?>",			// 頭金
						numberPayments: "<?php echo $qu_installments2; ?>",		// 分割回数
						// paymentStart:   new Date(),					// 分割予定開始年月
						paymentStart:   "<?php echo $qu_startDate2; ?>",		// 分割予定開始年月
						deliveryDate:   "<?php echo $qu_deliveryDate2; ?>",		// 受渡期日
						lists: [
							<?php for($ix=0; $ix<$q_count; $ix++) { ?>
								{ asset:"<?php echo $q_asset[$ix]; ?>", use:"<?php echo $q_use[$ix]; ?>", label:"<?php echo $q_detail[$ix]; ?>", life:"<?php echo $q_life[$ix]; ?>", itypes:"", idetails:"", name: "<?php echo $q_name[$ix]; ?>", num: "<?php echo $q_number[$ix]; ?>", cat: "<?php echo $q_unit[$ix]; ?>", unit: "<?php echo $q_price[$ix]; ?>", use_required:false, label_required:false },
							<?php } ?>
							<?php for($ix=$q_count; $ix<6; $ix++) { ?>
								{ asset:"", use:"", label:"", life:"", itypes:"", idetails:"", name: "", num: 0, cat: "", unit: 0, use_required:false, label_required:false },
							<?php } ?>
						],
						itemSelect: '',
						typeSelect: '選択してください',
						detailSelect: '選択してください',
						itemIndex: 0,
						items: [
						    { id: 0, asset: '---', types: [
						        { tId: 0, use: '', details: [
						            {dId: 0, label: '', life: '' },
						        ]},
						    ]},
						    { id: 1, asset: '建物', types: [
						        { tId: 0, use: '木造・合成樹脂造のもの', details: [
						            {dId: 0, label: '事務所用のもの', life: 24 },
						            {dId: 1, label: '店舗用・住宅用のもの', life: 22 },
						            {dId: 2, label: '飲食店用のもの', life: 20 },
						            {dId: 3, label: '旅館用・ホテル用・病院用・車庫用のもの', life: 17 },
						            {dId: 4, label: '公衆浴場用のもの', life: 12 },
						            {dId: 5, label: '工場用・倉庫用のもの（一般用）', life: 15 },
                                    {dId: 6, label: 'その他', life: 0 },
						        ]},
						        { tId: 1, use: '木骨モルタル造のもの', details: [
						            {dId: 0, label: '事務所用のもの', life: 22 },
						            {dId: 1, label: '店舗用・住宅用のもの', life: 20 },
						            {dId: 2, label: '飲食店用のもの', life: 19 },
						            {dId: 3, label: '旅館用・ホテル用・病院用・車庫用のもの', life: 15 },
						            {dId: 4, label: '公衆浴場用のもの', life: 11 },
						            {dId: 5, label: '工場用・倉庫用のもの（一般用）', life: 15 },
                                    {dId: 6, label: 'その他', life: 0 },
						        ]},
						        { tId: 2, use: '鉄骨鉄筋コンクリート造・鉄筋コンクリート造のもの', details: [
						            {dId: 0, label: '事務所用のもの', life: 50 },
						            {dId: 1, label: '住宅用のもの', life: 47 },
						            {dId: 2, label: '飲食店用のもの - 延べ面積のうちに占める木造内装部分の面積が30％を超えるもの', life: 37 },
						            {dId: 3, label: '飲食店用のもの - その他のもの', life: 41 },
						            {dId: 4, label: '旅館用・ホテル用のもの - 延べ面積のうちに占める木造内装部分の面積が30％を超えるもの', life: 31 },
						            {dId: 5, label: '旅館用・ホテル用のもの - その他のもの', life: 39 },
						            {dId: 6, label: '店舗用・病院用のもの', life: 39 },
						            {dId: 7, label: '車庫用のもの', life: 38 },
						            {dId: 8, label: '公衆浴場用のもの', life: 31 },
						            {dId: 9, label: '工場用・倉庫用のもの（一般用）', life: 38 },
                                    {dId: 10, label: 'その他', life: 0 },
						        ]},
						        { tId: 3, use: 'れんが造・石造・ブロック造のもの', details: [
						            {dId: 0, label: '事務所用のもの', life: 41 },
						            {dId: 1, label: '店舗用・住宅用・飲食店用のもの', life: 38 },
						            {dId: 2, label: '旅館用・ホテル用・病院用のもの', life: 36 },
						            {dId: 3, label: '車庫用のもの', life: 34 },
						            {dId: 4, label: '公衆浴場用のもの', life: 30 },
						            {dId: 5, label: '工場用・倉庫用のもの（一般用）', life: 34 },
                                    {dId: 6, label: 'その他', life: 0 },
						        ]},
						        { tId: 4, use: '金属造のもの', details: [
						            {dId: 0, label: '事務所用のもの骨格材の肉厚が、４㎜を超えるもの', life: 38 },
						            {dId: 1, label: '事務所用のもの骨格材の肉厚が、３㎜を超え、４㎜以下のもの', life: 30 },
						            {dId: 2, label: '事務所用のもの骨格材の肉厚が、３㎜以下のもの', life: 22 },
						            {dId: 3, label: '店舗用・住宅用のもの - ４㎜を超えるもの', life: 34 },
						            {dId: 4, label: '店舗用・住宅用のもの - ３㎜を超え、４㎜以下のもの',	life: 27 },
						            {dId: 5, label: '店舗用・住宅用のもの - ３㎜以下のもの', life: 19 },
						            {dId: 6, label: '飲食店用・車庫用のもの - ４㎜を超えるもの', life: 31 },
						            {dId: 7, label: '飲食店用・車庫用のもの - ３㎜を超え、４㎜以下のもの', life: 25 },
						            {dId: 8, label: '飲食店用・車庫用のもの - ３㎜以下のもの', life: 19 },
						            {dId: 9, label: '旅館用・ホテル用・病院用のもの - ４㎜を超えるもの',	life: 29 },
						            {dId: 10, label: '旅館用・ホテル用・病院用のもの - ３㎜を超え、４㎜以下のもの', life: 24 },
						            {dId: 11, label: '旅館用・ホテル用・病院用のもの - ３㎜以下のもの', life: 17 },
						            {dId: 12, label: '公衆浴場用のもの - ４㎜を超えるもの', life: 27 },
						            {dId: 13, label: '公衆浴場用のもの - ３㎜を超え、４㎜以下のもの', life: 19 },
						            {dId: 14, label: '公衆浴場用のもの - ３㎜以下のもの', life: 15 },
						            {dId: 15, label: '工場用・倉庫用のもの（一般用） - ４㎜を超えるもの', life: 31 },
						            {dId: 16, label: '工場用・倉庫用のもの（一般用） - ３㎜を超え、４㎜以下のもの', life: 24 },
                                    {dId: 17, label: '工場用・倉庫用のもの（一般用） - ３㎜以下のもの', life: 17 },
                                    {dId: 18, label: 'その他', life: 0 },
						        ]},
                                { tId: 5, use: 'その他', details: [
                                    {dId: 0, label: 'その他', life: 0 },
                                  ]},
						    ]},
						    { id: 2, asset: '建物附属設備', types: [
						        { tId: 0, use: 'アーケード・日よけ設備', details: [
						            {dId: 0, label: '主として金属製のもの',	life: 15 },
						            {dId: 1, label: 'その他のもの',		life: 8 },
						        ]},
						        { tId: 1, use: '店舗簡易装備', details: [
						            {dId: 0, label: '-',			life: 3 },
						        ]},
						        { tId: 2, use: '電気設備（照明設備を含む。）', details: [
						            {dId: 0, label: '蓄電池電源設備',	 life: 6 },
						            {dId: 1, label: 'その他のもの', life: 15 },
						        ]},
						        { tId: 3, use: '給排水・衛生設備、ガス設備', details: [
						            {dId: 0, label: '-', life: 15 },
						        ]},
                                { tId: 4, use: 'その他', details: [
                                    {dId: 0, label: 'その他', life: 0 },
                                  ]},
						    ]},
						    { id: 3, asset: '構築物', types: [
						        { tId: 0, use: '農林業用のもの', details: [
						            {dId: 0, label: '主としてコンクリート造、れんが造、石造又はブロック造のもの - 果樹棚又はポップ棚 - その他のもの', life: 14 },
						            {dId: 1, label: '主としてコンクリート造、れんが造、石造又はブロック造のもの - 果樹棚又はポップ棚 - 【例示】頭首工、えん堤、ひ門、用水路、かんがい用配管、農用井戸、貯水そう、肥料だめ、たい肥盤、温床わく、サイロ、あぜなど', life: 17 },
						            {dId: 2, label: '主として金属造のもの - 【例示】斜降索道設備、農用井戸、かん水用又は散水用配管など', life: 14 },
						            {dId: 3, label: '主として木造のもの - 【例示】果樹棚又はホップ棚、斜降索道設備、稲架、牧さく（電気牧さくを含む。）など', life: 5 },
						            {dId: 4, label: '土管を主としたもの - 例示】暗きょ、農用井戸、かんがい用配管など', life: 10 },
						            {dId: 5, label: 'その他のもの - 【例示】薬剤散布用又はかんがい用塩化ビニール配管など', life: 8 },
						        ]},
                                { tId: 1, use: 'その他', details: [
                                    {dId: 0, label: 'その他', life: 0 },
                                  ]},
						    ]},
						    { id: 4, asset: '生物', types: [
						        { tId: 0, use: '牛', details: [
						            {dId: 0, label: '繁殖用（家畜改良増殖法に基づく種付証明書、授精証明書、体内受精卵移植証明書又は対外受精卵移植証明書のあるものに限る。） - 役肉用牛', life: 6 },
						            {dId: 1, label: '繁殖用（家畜改良増殖法に基づく種付証明書、授精証明書、体内受精卵移植証明書又は対外受精卵移植証明書のあるものに限る。） - 乳用牛', life: 4 },
						            {dId: 2, label: '種付用（家畜改良増殖法に基づく種畜証明書の交付を受けた種おす牛に限る。）', life: 4 },
						            {dId: 3, label: 'その他用', life: 6 },
						        ]},
						        { tId: 1, use: '馬', details: [
						            {dId: 0, label: '繁殖用（家畜改良増殖法に基づく種付証明書又は授精証明書のあるものに限る。）', life: 6 },
						            {dId: 1, label: '種付用（家畜改良増殖法に基づく種畜証明書の交付を受けた種おす馬に限る。）', life: 6 },
						            {dId: 2, label: '競走用',		life: 4 },
						            {dId: 3, label: 'その他用',		life: 8 },
						        ]},
						        { tId: 2, use: '豚', details: [
						            {dId: 0, label: '-',		life: 14 },
						        ]},
						        { tId: 3, use: '綿羊、やぎ', details: [
						            {dId: 0, label: '種付用',		life: 4 },
						            {dId: 1, label: 'その他用',		life: 6 },
						        ]},
						        { tId: 4, use: 'かんきつ樹', details: [
						            {dId: 0, label: '温州みかん',	life: 28 },
						            {dId: 1, label: 'その他',		life: 30 },
						        ]},
						        { tId: 5, use: 'りんご樹', details: [
						            {dId: 0, label: 'わい化りんご',	life: 25 },
						            {dId: 1, label: 'その他',		life: 29 },
						        ]},
						        { tId: 6, use: 'ぶどう樹', details: [
						            {dId: 0, label: '温室ぶどう',	life: 12 },
						            {dId: 1, label: 'その他',		life: 15 },
						        ]},
						        { tId: 7, use: 'なし樹', details: [
						            {dId: 0, label: '-', life: 26 },
						        ]},
						        { tId: 8, use: '桃樹', details: [
						            {dId: 0, label: '-', life: 15 },
						        ]},
						        { tId: 9, use: '桜桃樹', details: [
						            {dId: 0, label: '-', life: 21 },
						        ]},
						        { tId: 10, use: 'びわ樹', details: [
						            {dId: 0, label: '-', life: 30 },
						        ]},
						        { tId: 11, use: 'くり樹', details: [
						            {dId: 0, label: '-', life: 25 },
						        ]},
						        { tId: 12, use: '梅樹', details: [
						            {dId: 0, label: '-', life: 25 },
						        ]},
						        { tId: 13, use: 'かき樹', details: [
						            {dId: 0, label: '-', life: 36 },
						        ]},
						        { tId: 14, use: 'あんず樹', details: [
						            {dId: 0, label: '-', life: 25 },
						        ]},
						        { tId: 15, use: 'すもも樹', details: [
						            {dId: 0, label: '-', life: 16 },
						        ]},
						        { tId: 16, use: 'いちじく樹', details: [
						            {dId: 0, label: '-', life: 11 },
						        ]},
						        { tId: 17, use: 'キウイフルーツ樹', details: [
						            {dId: 0, label: '-', life: 22 },
						        ]},
						        { tId: 18, use: 'ブルーベリー樹', details: [
						            {dId: 0, label: '-', life: 25 },
						        ]},
						        { tId: 19, use: 'パイナップル', details: [
						            {dId: 0, label: '-', life: 3 },
						        ]},
						        { tId: 20, use: '茶樹', details: [
						            {dId: 0, label: '-', life: 34 },
						        ]},
						        { tId: 21, use: 'オリーブ樹', details: [
						            {dId: 0, label: '-', life: 25 },
						        ]},
						        { tId: 22, use: 'つばき樹', details: [
						            {dId: 0, label: '-', life: 25 },
						        ]},
						        { tId: 23, use: '桑樹', details: [
						            {dId: 0, label: '立て通し', life: 18 },
						            {dId: 1, label: '根刈り、中刈り、高刈り', life: 9 },
						        ]},
                                { tId: 24, use: 'その他', details: [
                                    {dId: 0, label: 'その他', life: 0 },
                                  ]},
						    ]},
						    { id: 5, asset: '車両・運搬具', types: [
						        { tId: 0, use: '一般用のもの（特殊自動車・次の運送事業用等以外のもの）', details: [
						            {dId: 0, label: '自動車（２輪・３輪自動車を除く。） - 小型車（総排気量が0.66リットル以下のもの）', life: 4 },
						            {dId: 1, label: '自動車（２輪・３輪自動車を除く。） - 貨物自動車 - ダンプ式のもの',	life: 4 },
						            {dId: 2, label: '自動車（２輪・３輪自動車を除く。） - 貨物自動車 - その他のもの',	life: 5 },
						            {dId: 3, label: '自動車（２輪・３輪自動車を除く。） - 報道通信用のもの',		life: 5 },
						            {dId: 4, label: '自動車（２輪・３輪自動車を除く。） - その他のもの',		life: 6 },
						            {dId: 5, label: '２輪・３輪自動車',		life: 3 },
						            {dId: 6, label: '自転車',			life: 2 },
						            {dId: 7, label: 'リヤカー',			life: 4 },
                                    {dId: 8, label: 'その他', life: 0 },
						        ]},
						        { tId: 1, use: '運送事業用・貸自動車業用・自動車教習所用のもの', details: [
						            {dId: 0, label: '自動車（２輪・３輪自動車を含み、乗合自動車を除く。） - 小型車（貨物自動車にあっては積載量が２トン以下、その他のものにあっては総排気量が２リットル以下のもの）', life: 3 },
						            {dId: 1, label: '自動車（２輪・３輪自動車を含み、乗合自動車を除く。） - 大型乗用車（総排気量が３リットル以上のもの）', life: 5 },
						            {dId: 2, label: '自動車（２輪・３輪自動車を含み、乗合自動車を除く。） - その他のもの', life: 4 },
						            {dId: 3, label: '乗合自動車',		life: 5 },
						            {dId: 4, label: '自転車、リヤカー',		life: 2 },
						            {dId: 5, label: '被けん引車その他のもの',	life: 4 },
                                    {dId: 6, label: 'その他', life: 0 },
						        ]},
                                { tId: 2, use: 'その他', details: [
                                    {dId: 0, label: 'その他', life: 0 },
                                  ]},
						    ]},
						    { id: 6, asset: '工具', types: [
						        { tId: 0, use: '測定工具、検査工具（電気・電子を利用するものを含む。）', details: [
						            {dId: 0, label: '-', life: 5 },
						        ]},
						        { tId: 1, use: '治具、取付工具', details: [
						            {dId: 0, label: '-', life: 3 },
						        ]},
						        { tId: 2, use: '切削工具', details: [
						            {dId: 0, label: '-', life: 2 },
						        ]},
						        { tId: 3, use: '型（型枠を含む。）鍛圧工具、打抜工具', details: [
						            {dId: 0, label: 'プレスその他の金属加工用金型、合成樹脂、ゴム・ガラス成型用金型、鋳造用型', life: 2 },
						            {dId: 1, label: 'その他のもの', life: 3 },
						        ]},
						        { tId: 4, use: '活字、活字に常用される金属', details: [
						            {dId: 0, label: '購入活字（活字の形状のまま反復使用するものに限る。）', life: 2 },
						            {dId: 1, label: '自製活字、活字に常用される金属', life: 8 },
                                    {dId: 2, label: 'その他', life: 0 },
						        ]},
                                { tId: 5, use: 'その他', details: [
                                    {dId: 0, label: 'その他', life: 0 },
                                  ]},
						    ]},
						    { id: 7, asset: '器具・備品', types: [
						        { tId: 0, use: '家具、電気機器、ガス機器、家庭用品（他に揚げてあるものを除く。）', details: [
						            {dId:  0, label: '事務机、事務いす、キャビネット - 主として金属製のもの',	life: 15 },
						            {dId:  1, label: '事務机、事務いす、キャビネット - その他のもの',		life: 8 },
						            {dId:  2, label: '応接セット - 接客業用のもの',				life: 5 },
						            {dId:  3, label: '応接セット - その他のもの',				life: 8 },
						            {dId:  4, label: 'ベッド',							life: 8 },
						            {dId:  5, label: '児童用机、いす',						life: 5 },
						            {dId:  6, label: '陳列だな、陳列ケース - 冷凍機付・冷蔵機付のもの',		life: 6 },
						            {dId:  7, label: '陳列だな、陳列ケース - その他のもの',			life: 8 },
						            {dId:  8, label: 'その他の家具 - 接客業用のもの',				life: 5 },
						            {dId:  9, label: 'その他の家具 - その他のもの - 主として金属製のもの',	life: 15 },
						            {dId: 10, label: 'その他の家具 - その他のもの - その他のもの',		life: 8 },
						            {dId: 11, label: 'ラジオ、テレビジョン、テープレコーダーその他の音響機器',	life: 5 },
						            {dId: 12, label: '冷房用・暖房用機器',					life: 6 },
						            {dId: 13, label: '電気冷蔵庫、電気洗濯機その他これらに類する電気・ガス機器', life: 6 },
						            {dId: 14, label: '氷冷蔵庫、冷蔵ストッカー（電気式のものを除く。）',	 life: 4 },
						            {dId: 15, label: 'カーテン、座ぶとん、寝具、丹前その他これらに類する繊維製品', life: 3 },
						            {dId: 16, label: 'じゅうたんその他の床用敷物 - 小売業用・接客業用・放送用・レコード吹込用・劇場用のもの', life: 3 },
						            {dId: 17, label: 'じゅうたんその他の床用敷物 - その他のもの',		life: 6 },
						            {dId: 18, label: '室内装飾品 - 主として金属製のもの',			life: 15 },
						            {dId: 19, label: '室内装飾品 - その他のもの',				life: 8 },
						            {dId: 20, label: '食事・ちゅう房用品 - 陶磁器製・ガラス製のもの',		life: 2 },
						            {dId: 21, label: '食事・ちゅう房用品 - その他のもの',			life: 5 },
						            {dId: 22, label: 'その他のもの - 主として金属製のもの',			life: 15 },
						            {dId: 23, label: 'その他のもの - その他のもの',				life: 8 },
						        ]},
						        { tId: 1, use: '事務機器、通信機器', details: [
						            {dId: 0, label: '謄写機器、タイプライター - 孔版印刷・印書業用のもの',		life: 3 },
						            {dId: 1, label: '謄写機器、タイプライター - その他のもの',				life: 5 },
						            {dId: 2, label: '電子計算機 - パーソナルコンピュータ（サーバー用のものを除く。）',	life: 4 },
						            {dId: 3, label: '電子計算機 - その他のもの',					life: 5 },
						            {dId: 4, label: '複写機、計算機（電子計算機を除く。）、金銭登録機、タイムレコーダーその他これらに類するもの', life: 5 },
						            {dId: 5, label: 'その他の事務機器',							life: 5 },
						            {dId: 6, label: 'テレタイプライター、ファクシミリ',					life: 5 },
						            {dId: 7, label: 'インターホーン、放送用設備',					life: 6 },
						            {dId: 8, label: '電話設備その他の通信機器 - デジタル構内交換設備、デジタルボタン電話設備', life: 6 },
						            {dId: 9, label: '電話設備その他の通信機器 - その他のもの',				life: 10 },
						        ]},
						        { tId: 2, use: '時計、試験機器、測定機器', details: [
						            {dId: 0, label: '時計',		life: 10 },
						            {dId: 1, label: '度量衡器',		life: 5 },
						            {dId: 2, label: '試験・測定機器',	life: 5 },
                                    {dId: 3, label: 'その他', life: 0 },
						        ]},
						        { tId: 3, use: '光学機器、写真製作機器', details: [
						            {dId: 0, label: 'カメラ、映画撮影機、映写機、望遠鏡',	life: 5 },
						            {dId: 1, label: '引伸機、焼付機、乾燥機、顕微鏡',		life: 8 },
                                    {dId: 2, label: 'その他', life: 0 },
						        ]},
						        { tId: 4, use: '看板・広告器具', details: [
						            {dId: 0, label: '看板、ネオンサイン、気球',			life: 3 },
						            {dId: 1, label: 'マネキン人形、模型',			life: 2 },
						            {dId: 2, label: 'その他のもの - 主として金属製のもの',	life: 10 },
						            {dId: 3, label: 'その他のもの - その他のもの',		life: 5 },
						        ]},
						        { tId: 5, use: '容器、金庫', details: [
						            {dId: 0, label: 'ボンベ - 溶接製のもの',			life: 6 },
						            {dId: 1, label: 'ボンベ - 鍛造製のもの - 塩素用のもの',	life: 8 },
						            {dId: 2, label: 'ボンベ - 鍛造製のもの - その他のもの',	life: 10 },
						            {dId: 3, label: 'ドラムかん、コンテナーその他の容器 - 大型コンテナー（長さが６ｍ以上のものに限る。）', life: 7 },
						            {dId: 4, label: 'ドラムかん、コンテナーその他の容器 - その他のもの - 金属製のもの', life: 3 },
						            {dId: 5, label: 'ドラムかん、コンテナーその他の容器 - その他のもの - その他のもの', life: 2 },
						            {dId: 6, label: '金庫 - 手さげ金庫',	life: 5 },
						            {dId: 7, label: '金庫 - その他のもの',	life: 20 },
						        ]},
						        { tId: 6, use: '理容・美容機器', details: [
						            {dId: 0, label: '-', life: 5 },
						        ]},
						        { tId: 7, use: '医療機器', details: [
						            {dId:  0, label: '消毒殺菌用機器',						life: 4 },
						            {dId:  1, label: '手術機器',						life: 5 },
						            {dId:  2, label: '血液透析又は血しょう交換用機器',				life: 7 },
						            {dId:  3, label: 'ハバードタンクその他の作動部分を有する機能回復訓練機器',	life: 6 },
						            {dId:  4, label: '調剤機器',						life: 6 },
						            {dId:  5, label: '歯科診療用ユニット',					life: 7 },
						            {dId:  6, label: '光学検査機器 - ファイバースコープ',			life: 6 },
						            {dId:  7, label: '光学検査機器 - その他のもの',				life: 8 },
						            {dId:  8, label: 'その他のもの - レントゲンその他の電子装置を使用する機器 - 移動式のもの、救急医療用のもの、自動血液分析器', life: 4 },
						            {dId:  9, label: 'その他のもの - レントゲンその他の電子装置を使用する機器 - その他のもの', life: 6 },
						            {dId: 10, label: 'その他のもの - その他のもの - 陶磁器製・ガラス製のもの',	life: 3 },
						            {dId: 11, label: 'その他のもの - その他のもの - 主として金属製のもの',	life: 10 },
						            {dId: 12, label: 'その他のもの - その他のもの - その他のもの',		life: 5 },
						        ]},
						        { tId: 8, use: '娯楽・スポーツ器具', details: [
						            {dId: 0, label: 'たまつき用具',						  life: 8 },
						            {dId: 1, label: 'パチンコ器、ビンゴ器その他これらに類する球戯用具、射的用具', life: 2 },
						            {dId: 2, label: 'ご、しょうぎ、まあじゃん、その他の遊戯具',			  life: 5 },
						            {dId: 3, label: 'スポーツ具',						  life: 3 },
                                    {dId: 4, label: 'その他', life: 0 },
						        ]},
                                { tId: 9, use: 'その他', details: [
                                    {dId: 0, label: 'その他', life: 0 },
                                  ]},
						    ]},
						    { id: 8, asset: '機械・装置', types: [
						        { tId: 0, use: '農業用設備', details: [
						            {dId: 0, label: '-', life: 7 },
						        ]},
						        { tId: 1, use: '林業用設備', details: [
						            {dId: 0, label: '-', life: 5 },
						        ]},
						        { tId: 2, use: '食料品製造業用設備', details: [
						            {dId: 0, label: '-', life: 10 },
						        ]},
						        { tId: 3, use: '飲料・たばこ・飼料製造業用設備', details: [
						            {dId: 0, label: '-', life: 10 },
						        ]},
						        { tId: 4, use: '繊維工業用設備', details: [
						            {dId: 0, label: '炭素繊維製造設備 - 黒鉛化炉',	life: 3 },
						            {dId: 1, label: '炭素繊維製造設備 - その他の設備',	life: 7 },
						            {dId: 2, label: 'その他の設備', life: 7 },
						        ]},
						        { tId: 5, use: '木材・木製品（家具を除く。）製造業用設備', details: [
						            {dId: 0, label: '-', life: 8 },
						        ]},
						        { tId: 6, use: '家具・装備品製造業用設備', details: [
						            {dId: 0, label: '-', life: 11 },
						        ]},
						        { tId: 7, use: 'パルプ・紙・紙加工品製造業用設備', details: [
						            {dId: 0, label: '-', life: 12 },
						        ]},
						        { tId: 8, use: '印刷業・印刷関連業用設備', details: [
						            {dId: 0, label: 'デジタル印刷システム設備',			 life: 4 },
						            {dId: 1, label: '製本業用設備',				 life: 7 },
						            {dId: 2, label: '新聞業用設備 - モノタイプ・写真・通信設備', life: 3 },
						            {dId: 3, label: '新聞業用設備 - その他の設備',		 life: 10 },
						            {dId: 4, label: 'その他の設備',				 life: 10 },
						        ]},
						        { tId: 9, use: 'ゴム製品製造業用設備', details: [
						            {dId: 0, label: '-', life: 9 },
						        ]},
						        { tId: 10, use: 'なめし革・なめし革製品・毛皮製造業用設備', details: [
						            {dId: 0, label: '-', life: 9 },
						        ]},
						        { tId: 11, use: '窯業・土石製品製造業用設備', details: [
						            {dId: 0, label: '-', life: 9 },
						        ]},
						        { tId: 12, use: '鉄鋼業用設備', details: [
						            {dId: 0, label: '表面処理鋼材・鉄粉製造業・鉄スクラップ加工処理業用設備', life: 5 },
						            {dId: 1, label: '純鉄・原鉄・ベースメタル・フェロアロイ・鉄素形材・鋳鉄管製造業用設備', life: 9 },
						            {dId: 2, label: 'その他の設備', life: 14 },
						        ]},
						        { tId: 13, use: '金属製品製造業用設備', details: [
						            {dId: 0, label: '金属被覆、彫刻業・打はく、金属製ネームプレート製造業用設備', life: 6 },
						            {dId: 1, label: 'その他の設備', life: 10 },
						        ]},
						        { tId: 14, use: '林業用設備', details: [
						            {dId: 0, label: '-', life: 5 },
						        ]},
						        { tId: 15, use: '鉱業・採石業・砂利採取業用設備', details: [
						            {dId: 0, label: '石油・天然ガス鉱業用設備 - 坑井設備',	life: 3 },
						            {dId: 1, label: '石油・天然ガス鉱業用設備 - 掘さく設備',	life: 6 },
						            {dId: 2, label: '石油・天然ガス鉱業用設備 - その他の設備',	life: 12 },
						            {dId: 3, label: 'その他の設備', life: 6 },
						        ]},
						        { tId: 16, use: '総合工事業用設備', details: [
						            {dId: 0, label: '-', life: 6 },
						        ]},
						        { tId: 17, use: '倉庫業用設備', details: [
						            {dId: 0, label: '-', life: 12 },
						        ]},
						        { tId: 18, use: '運輸に附帯するサービス業用設備', details: [
						            {dId: 0, label: '-', life: 10 },
						        ]},
						        { tId: 19, use: '飲食料品卸売業用設備', details: [
						            {dId: 0, label: '-', life: 10 },
						        ]},
						        { tId: 20, use: '飲食料品小売業用設備', details: [
						            {dId: 0, label: '-', life: 9 },
						        ]},
						        { tId: 21, use: 'その他の小売業用設備', details: [
						            {dId: 0, label: 'ガソリン・液化石油ガススタンド設備',	life: 8 },
						            {dId: 1, label: 'その他の設備 - 主として金属製のもの',	life: 17 },
						            {dId: 2, label: 'その他の設備 - その他のもの',		life: 8 },
						        ]},
						        { tId: 22, use: '宿泊業用設備', details: [
						            {dId: 0, label: '-', life: 10 },
						        ]},
						        { tId: 23, use: '飲食店業用設備', details: [
						            {dId: 0, label: '-', life: 8 },
						        ]},
						        { tId: 24, use: '洗濯業・理容業・美容業・浴場業用設備', details: [
						            {dId: 0, label: '-', life: 13 },
						        ]},
						        { tId: 25, use: 'その他の生活関連サービス業用設備', details: [
						            {dId: 0, label: '-', life: 6 },
						        ]},
						        { tId: 26, use: '自動車整備業用設備', details: [
						            {dId: 0, label: '-', life: 15 },
						        ]},
						        { tId: 27, use: 'その他', details: [
						            {dId: 0, label: 'その他', life: 0 },
						        ]},
						    ]},
                            { id: 9, asset: 'その他', types: [
                                { tId: 0, use: 'その他', details: [
                                  {dId: 0, label: 'その他',		life: 0 },
                                ]},
                            ]},
						],
					}
		    		},
				created: function (index) {
					// console.log('created: ', this);
					var q_count = <?php echo $q_count; ?>;
					// console.log('created: ' + q_count);
					var itemName;	// 1つ目のselect
					var typeName;	// 2つ目のselect
					var itemResult;
					var typeResult;

					for(var ix=0; ix<q_count; ix++) {
						// { asset:"<?php echo $q_asset[$ix]; ?>", use:"<?php echo $q_use[$ix]; ?>", label:"<?php echo $q_detail[$ix]; ?>", life:"<?php echo $q_life[$ix]; ?>", itypes:"", idetails:"", name: "<?php echo $q_name[$ix]; ?>", num: "<?php echo $q_number[$ix]; ?>", cat: "<?php echo $q_unit[$ix]; ?>", unit: "<?php echo $q_price[$ix]; ?>" },
						itemName = this.lists[ix].asset;
						typeName = this.lists[ix].use;
						// console.log('itemName: '+ itemName, 'typeName' + typeName);

						// items から選択中のuseを探す
						if (itemName != "") {
							// itemResult = this.items.find((v) => v.asset === itemName);
							itemResult = this.items.find(function(v) {
								return v.asset === itemName;
							});
							// typeResult = this.items[itemResult.id].types.find((v) => v.use === typeName);
							typeResult = this.items[itemResult.id].types.find(function(v) {
								return v.use === typeName;	
							});
							// console.log('itemResult: ', itemResult, 'typeResult', typeResult);

							// 2つ目のselectのoptionをセットする
							this.lists[ix].itypes   = this.items[itemResult.id].types;
							// 3つ目のselectのoptionをセットする
							this.lists[ix].idetails = this.items[itemResult.id].types[typeResult.tId].details;
						} else {
							// 2つ目のselectのoptionをセットする
							this.lists[ix].itypes   = "";
							// 3つ目のselectのoptionをセットする
							this.lists[ix].idetails = "";
						}
					}
				},
				filters: {
					// 3桁ごとにカンマを渡す
					number_format: function(val) {
						// console.log('filters: ', val);
						let valStNe = String(val);
						// valStNe = valStNe.replace(/\D/g, '');
						return valStNe.toString().replace( /([0-9]+?)(?=(?:[0-9]{3})+$)/g , '$1,' );
					}
				},
				components: {
					'vuejs-datepicker':vuejsDatepicker
				},
				// 要素を追加する機能
				methods: {
					// 要素を追加
					addItem: function() {
						this.lists.push(this.independentObejct());
						this.count++;
					},
					// 要素を削除
					remove: function(i) {
						this.lists.splice(i, 1);
						this.count--;
					},
					// input areaに値を追加
					independentObejct: function() {
						return {
							asset:"",
							use:"",
							label:"",
							life:"",
							itypes:"",
							idetails:"",
							name: "",
							num:  0,	// 金額
							cat: "",
							unit: 0,	// 数量
							sum:  0,	// 合計
							use_required:false,
							label_required:false
						}
					},
					sum: function(list) {
						function removeComma(value) {
							var num = value.replace(/,/g, "");
							return parseInt(num);
						};
						// string型に直して、数量・金額それぞれreplace（カンマを取り除く）を実行する
						// let numNumber = removeComma(String(list.num));
						// if (isNaN(numNumber)) {
						//	list.num  = 0;
						//	numNumber = 0;
						// }

						var strVal = String(list.num);
						var dotPosition = 0;
						//　小数点が存在するか確認
						if(strVal.lastIndexOf('.') != -1){
							// 小数点があったら位置を取得
							dotPosition = (strVal.length-1) - strVal.lastIndexOf('.');
						}
						list.num = Number(list.num);
						let numNumber = String(list.num);
						if (isNaN(numNumber)) {
							list.num  = 0;
							numNumber = 0;
						}
						list.unit = Number(list.unit);
						let unitNumber = removeComma(String(list.unit));
						if (isNaN(unitNumber)) {
							list.unit  = 0;
							unitNumber = 0;
						}
						let sumNumber = numNumber * unitNumber;
						sumNumber = Math.floor(sumNumber);
						return sumNumber.toLocaleString(); // 計算結果に3桁ごとにあ
					},
					// 「初回お支払額」の3桁と￥をつける
					// initialOutInput: function(val) {
					// 	function addComma(value) {
					// 	    return value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
					// 	};
					// 	this.initialPayment = '¥' +addComma(val.target.value);
					// },
					// 「初回お支払額」のカンマ、円マークを削除する
					// initialInInput: function(val) {
					// 	function addComma(value) {
					// 	    return value.replace(/,/g, '').replace(/¥/g, '');
					// 	};
					// 	this.initialPayment = addComma(val.target.value);
					// },
					// 「頭金」の3桁と￥をつける
					depositlOutInput: function(val) {
						function addComma(value) {
							return value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
						};
						this.downPayment = '¥' +addComma(val.target.value);
					},
					// 「頭金」のカンマ、円マークを削除する
					depositlInInput: function(val) {
						function addComma(value) {
							return value.replace(/,/g, '').replace(/¥/g, '');
						};
						this.downPayment = addComma(val.target.value);
					},
					customFormatter: function(date) {
						return moment(date).format('YYYY/MM');
					},
					customFormat: function(date) {
						return moment(date).format('YYYY/MM/DD');
					},
					fetchAsset: function(index) {
						//console.log("fetchAsset", this, "index", index);
						// this.itemIndex  = index;

						// 初期化
						// this.lists[index].use      = "";
						this.lists[index].label    = "";
						// this.lists[index].life     = "";
						// this.lists[index].itypes   = "";
						// this.lists[index].idetails = "";

						// 選択中の情報を取得
						let itemName = this.lists[index].asset; // 1つ目のselect
						if (itemName == "" || itemName == "---") {
							this.lists[index].use_required   = false;
							this.lists[index].label_required = false;
							$('.btn').prop("disabled", false);
							// console.log('fetchAsset false');
						} else {
							this.lists[index].use_required   = false;
							this.lists[index].label_required = false;
							$('.btn').prop("disabled", false);
							// console.log('fetchAsset true');
						}

						// items から選択中のnameを探す
						// const itemResult = this.items.find((v) => v.asset === itemName);
						const itemResult = this.items.find(function(v) {
							return v.asset === itemName;
						});

						// 2つ目のselectのoptionをセットする
						this.lists[index].itypes = this.items[itemResult.id].types;
                        this.lists[index].use = this.items[itemResult.id].types[0].use;

                        // 3つ目のselectのoptionをセットする
                        this.lists[index].idetails = this.items[itemResult.id].types[0].details;
                        this.lists[index].label = this.items[itemResult.id].types[0].details[0].label;
                        this.lists[index].life     = this.items[itemResult.id].types[0].details[0].life;
					},　
					fetchUse: function(index) {
						//console.log("fetchUse", this, "index", index);
						// 初期化
						// this.lists[index].label    = "";
						// this.lists[index].life     = "";
						// this.lists[index].idetails = "";
						// 選択中の情報を取得
						let itemName = this.lists[index].asset; // 1つ目のselect
						let typeName = this.lists[index].use; // 2つ目のselect

						if (itemName != "") {
							if (typeName == "") {
								this.lists[index].use_required   = true;
								this.lists[index].label_required = true;
								$('.btn').prop("disabled", true);
								// console.log('fetchUse true');
							} else {
								this.lists[index].use_required   = false;
								this.lists[index].label_required = false;
								$('.btn').prop("disabled", false);
								// console.log('fetchUse false,true');
							}
						}
						// items から選択中のuseを探す
						// let itemResult = this.items.find((v) => v.asset === itemName);
						let itemResult = this.items.find(function(v) {
							return v.asset === itemName;
						});
                        if (this.lists[index].use == "") {
                          typeName = this.lists[index].use = this.items[itemResult.id].types[0].use;
                        }
                        console.log("itemResult", itemName);
						// let typeResult = this.items[itemResult.id].types.find((v) => v.use === typeName);
						let typeResult = this.items[itemResult.id].types.find(function(v) {
							return v.use === typeName;
						});

						// 3つ目のselectのoptionをセットする
						this.lists[index].idetails = this.items[itemResult.id].types[typeResult.tId].details;
						this.lists[index].life     = this.items[itemResult.id].types[typeResult.tId].details[0].life;
                        this.lists[index].label = this.items[itemResult.id].types[typeResult.tId].details[0].label;
					},
					fetchLabel: function(index) {
						// console.log("fetchLabel", this.lists[index], "index", index);
						// this.itemIndex = index;

						// 初期化
						// this.lists[index].life = "";

						// 選択中の情報を取得
						let itemName   = this.lists[index].asset; // 1つ目のselect
						let typeName   = this.lists[index].use;	  // 2つ目のselect
						let detailName = this.lists[index].label; // 3つ目のselect

						if (itemName != "") {
							if (typeName == "") {
								this.lists[index].use_required   = true;
								this.lists[index].label_required = true;
								$('.btn').prop("disabled", true);
								// console.log('fetchLabel true');
							} else {
								this.lists[index].use_required   = false;
								if (detailName == "") {
									this.lists[index].label_required = true;
									$('.btn').prop("disabled", true);
									// console.log('fetchLabel2 true');
								} else {
									this.lists[index].label_required = false;
									$('.btn').prop("disabled", false);
									// console.log('fetchLabel2 false');
								}
							}
						}

						// items から選択中のlifeを探す
						// let itemResult = this.items.find((v) => v.asset === itemName);
						let itemResult = this.items.find(function(v) {
							return v.asset === itemName;
						});
						// let typeResult = this.items[itemResult.id].types.find((v) => v.use === typeName);
						let typeResult = this.items[itemResult.id].types.find(function(v) {
							return v.use === typeName;
						});
						// let detailResult = this.items[itemResult.id].types[typeResult.tId].details.find((v) => v.label === detailName);
						let detailResult = this.items[itemResult.id].types[typeResult.tId].details.find(function(v) {
							return v.label === detailName;
						});

						// 4つ目のinputの初期値をセットする
						this.lists[index].life = this.items[itemResult.id].types[typeResult.tId].details[detailResult.dId].life;
					},
					status: function(validation) {
						return {
							// dirty: validation.$dirty,
							// error: validation.$error
							dirty: true,
							error: validation
						}
					},
				},
				computed: {
					// 小計
					shokei: function() {
						let shokeiNum = 0;
						// 修正　2020年5月25日
						// for (const list of this.lists) {
						// 	function removeComma(value) {
						// 		var num = value.replace(/,/g, "");
						// 		return parseInt(num);
						// 	};
						// 	let numShokei = removeComma(String(list.num));
						// 	let unitShokei = removeComma(String(list.unit));
						// 	shokeiNum += Number(numShokei) * Number(unitShokei);
						// }
						this.lists.forEach(function( list ) {
							function removeComma(value) {
								var num = value.replace(/,/g, "");
								return parseInt(num);
							};
							// let numShokei = removeComma(String(list.num));
							let numShokei  = String(list.num);
							let unitShokei = removeComma(String(list.unit));
							var sumNumber  = numShokei * unitShokei;
							sumNumber = Math.floor(sumNumber);
							// shokeiNum += Number(numShokei) * Number(unitShokei);
							shokeiNum += sumNumber;
						});
						shokeiNum = Math.round(shokeiNum * 1.1);
						let shokeiAnswer = shokeiNum.toLocaleString("jp",{style:"currency",currency:"JPY"});
						return shokeiAnswer;
						// let shokeiTarget = this.lists.reduce(function(i, list) { return i + Number((list.sum).replace(/[^0-9]/g, '')); }, 0);
						// let shokeiTargetComma = shokeiTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						// return shokeiTargetComma.toLocaleString();
					},
					// 内消費税
					shokeiTax: function() {
						var shokeiTaxTarget = Math.round(this.shokei.replace(/[^0-9]/g, '') / 1.1 * 0.1) || 0;
						// var shokeiTaxTarget = Math.round(this.shokei.replace(/[^0-9]/g, '') * 0.1) || 0;
						var shokeiTaxTargetComma = shokeiTaxTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						// console.log("shokei=", this.shokei, "shokeiTaxTarget=", shokeiTaxTarget, "shokeiTaxTargetComma=", shokeiTaxTargetComma);
						return shokeiTaxTargetComma.toLocaleString();
					},
					// 小計+消費税
					shokeiTotal: function() {
						// var shokeiTaxTarget = Number(this.shokei.replace(/[^0-9]/g, '')) + Number(this.shokeiTax.replace(/[^0-9]/g, '')) || 0;
						var shokeiTaxTarget = Number(this.shokei.replace(/[^0-9]/g, '')) || 0;
						var shokeiTaxTargetComma = shokeiTaxTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						// console.log("shokei=", this.shokei, "shokeiTaxTarget=", shokeiTaxTarget, "shokeiTaxTargetComma=", shokeiTaxTargetComma);
						return shokeiTaxTargetComma.toLocaleString();
					},
					// 見積もり金額
					estimatesPrice: function() {
						let estimatesPriceTarget = Number(this.shokei.replace(/[^0-9]/g, '')) + Number(this.adminFee.replace(/[^0-9]/g, '')) || 0;
						let estimatesPriceTargetComma = estimatesPriceTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						return estimatesPriceTargetComma.toLocaleString();
					},
					// 初回お支払額
					initialPayment: function() {
						let depoPay	= Number(this.downPayment.replace(/[^0-9]/g, ''));				// 頭金
						let monthlyPay = Number(this.monthlyPayment.replace(/[^0-9]/g, ''));	// 月々お支払額定義
						let firstPayment = depoPay + monthlyPay; 															// 頭金＋月々お支払額定義
						let firstPaymentComma = firstPayment.toLocaleString("jp",{style:"currency",currency:"JPY"});// 3桁ごとにカンマと円をつける
						return firstPaymentComma.toLocaleString() || 0;
					},
					// 事務管理手数料
					adminFee: function() {
						var adminFeeTarget = Math.ceil((this.adminPer * this.shokei.replace(/[^0-9]/g, '')) / 100) || 0;
						// 通貨記号と３桁ごとにカンマを付ける
						var adminFeeTargetComma = adminFeeTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						return adminFeeTargetComma.toLocaleString();
					},
					// 月々お支払額
					monthlyPayment: function() {
						let estiPay = Number(this.estimatesPrice.replace(/[^0-9]/g, ''));	// 見積もり合計
						let depoPay	= Number(this.downPayment.replace(/[^0-9]/g, ''));	// 頭金
						let numPay 	= Number(this.numberPayments);				// 分割支払い回数
						var monthlyPaymentTarget = Math.ceil((estiPay - depoPay ) / numPay) || 0;
						// 通貨記号と３桁ごとにカンマを付ける
						var monthlyPaymentTargetComma = monthlyPaymentTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						// return monthlyPaymentTargetComma.toLocaleString();
						return monthlyPaymentTargetComma.toLocaleString();
					},
					// 返済終了予定年月
					paymentFinish: function() {
						return moment(this.paymentStart).add(this.numberPayments - 1, 'M').format('YYYY/MM');
					}
				}
			});
		})();

		$(function() {
			$('#chatButton').on('click', function(){
				$('#sincloBox').toggleClass('chatOpen');
				$('#sincloBox').data('true');
			})
		})

		function chk(){
			tval = document.getElementById("rtxt").value;	//入力された文字列
			num  = tval.match(/\r\n/g);	//IE 用
			num  = tval.match(/\n/g);	//Firefox 用
			if(tval == "") {
				// alert("何も入力されていません。");
				gyosuu = 0;
			} else {
				if(num != null) {
					gyosuu = num.length + 1;
				} else {
					gyosuu = 1;
				}
			}
			// alert("行数は "+gyosuu+"行です。");
			const tline = document.getElementById("tline");
			tline.value = gyosuu;
		}
	</script>
	<script src='https://ws1.sinclo.jp/client/5e7812fdb5a66.js'></script>
</body>
</html>