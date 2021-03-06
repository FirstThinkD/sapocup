<?php
session_start();
require_once(__DIR__ . '/../../../common/dbconnect.php');
require_once(__DIR__ . '/../../common/functions.php');
// require_once(__DIR__ . '/../simulation/sim_func.php');
require_once(__DIR__ . '/functions2.php');

if (empty($_SESSION['loginID'])) {
	header("Location:/");
	exit();
}

if (empty($_GET['id']) || empty($_GET['ptn'])) {
	header("Location:/");
	exit();
}

$get_qu_id = $_GET['id'];
$get_ptnno = $_GET['ptn'];

$sql = sprintf('SELECT * FROM `w1_simulation` WHERE qu_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $get_qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row2 = mysqli_fetch_assoc($record);

if ($get_ptnno == "1") {
	$qu_commit2        = $row2['pt1_commit'];
	$qu_deposit2       = $row2['pt1_deposit'];
	$qu_commission2    = $row2['pt1_commission'];
	$qu_initPayAmount2 = $row2['pt1_initPayAmount'];
	$qu_amount_pay2    = $row2['pt1_amount_pay'];
	$qu_installments2  = $row2['pt1_installments'];
	$q_alltotal2       = $row2['pt1_alltotal'];
} else if ($get_ptnno == "2") {
	$qu_commit2        = $row2['pt2_commit'];
	$qu_deposit2       = $row2['pt2_deposit'];
	$qu_commission2    = $row2['pt2_commission'];
	$qu_initPayAmount2 = $row2['pt2_initPayAmount'];
	$qu_amount_pay2    = $row2['pt2_amount_pay'];
	$qu_installments2  = $row2['pt2_installments'];
	$q_alltotal2       = $row2['pt2_alltotal'];
} else {
	$qu_commit2        = $row2['pt3_commit'];
	$qu_deposit2       = $row2['pt3_deposit'];
	$qu_commission2    = $row2['pt3_commission'];
	$qu_initPayAmount2 = $row2['pt3_initPayAmount'];
	$qu_amount_pay2    = $row2['pt3_amount_pay'];
	$qu_installments2  = $row2['pt3_installments'];
	$q_alltotal2       = $row2['pt3_alltotal'];
}

$sql = sprintf('SELECT * FROM `w1_quotation` WHERE qu_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $get_qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row3 = mysqli_fetch_assoc($record);

$ptn_out = partition2($row3['qu_paymentDate'], $row2['qu_startDate'], $qu_installments2, $q_alltotal2, $qu_deposit2, $qu_initPayAmount2);
$qu_endDate2 = date('Y-m-d', strtotime($ptn_out[($ptn_out[0][5] - 1)][6]));

// $sql = sprintf('INSERT INTO `quotation` SET c_id="%d", u_id="%d",
//		qu_bunrui="%s", qu_custom_name="%s", qu_custom_no="%d",
//		qu_name="%s", qu_location="%s", qu_paymentDate="%s",
//		qu_deliveryDate="%s", qu_deposit="%s", qu_price="%s",
//		qu_commission="%s", qu_commit="%s", qu_initPayAmount="%s",
//		qu_amount_pay="%s", qu_installments="%s", qu_startDate="%s",
//		qu_endDate="%s", q_cost="%s", q_alltotal="%s", in_companyName="%s",
//		in_postal="%s", in_address1="%s", in_address2="%s",
//		in_address3="%s", in_tel="%s", in_email="%s",
//		in_contactName="%s", syohkei="%s"',
//		mysqli_real_escape_string($db, $row3['c_id']),
//		mysqli_real_escape_string($db, $row3['u_id']),
//		mysqli_real_escape_string($db, $row3['qu_bunrui']),
//		mysqli_real_escape_string($db, $row3['qu_custom_name']),
//		mysqli_real_escape_string($db, $row3['qu_custom_no']),
//		mysqli_real_escape_string($db, $row3['qu_name']),
//		mysqli_real_escape_string($db, $row3['qu_location']),
//		mysqli_real_escape_string($db, $row3['qu_paymentDate']),
//		mysqli_real_escape_string($db, $row3['qu_deliveryDate']),
//		mysqli_real_escape_string($db, $row3['qu_deposit']),
//		mysqli_real_escape_string($db, $row3['qu_price']),
//		mysqli_real_escape_string($db, $row3['qu_commission']),
//		mysqli_real_escape_string($db, $row3['qu_commit']),
//		mysqli_real_escape_string($db, $row3['qu_initPayAmount']),
//		mysqli_real_escape_string($db, $row3['qu_amount_pay']),
//		mysqli_real_escape_string($db, $row3['qu_installments']),
//		mysqli_real_escape_string($db, $row3['qu_startDate']),
//		mysqli_real_escape_string($db, $row3['qu_endDate']),
//		mysqli_real_escape_string($db, $row3['q_cost']),
//		mysqli_real_escape_string($db, $row3['q_alltotal']),
//		mysqli_real_escape_string($db, $row3['in_companyName']),
//		mysqli_real_escape_string($db, $row3['in_postal']),
//		mysqli_real_escape_string($db, $row3['in_address1']),
//		mysqli_real_escape_string($db, $row3['in_address2']),
//		mysqli_real_escape_string($db, $row3['in_address3']),
//		mysqli_real_escape_string($db, $row3['in_tel']),
//		mysqli_real_escape_string($db, $row3['in_email']),
//		mysqli_real_escape_string($db, $row3['in_contactName']),
//		mysqli_real_escape_string($db, $row3['syohkei'])
// );

$sql = sprintf('INSERT INTO `quotation` SET c_id="%d", u_id="%d",
		qu_bunrui="%s", qu_custom_name="%s", qu_custom_no="%d",
		qu_name="%s", qu_location="%s", qu_paymentDate="%s",
		qu_deliveryDate="%s", qu_deposit="%s", qu_price="%s",
		qu_commission="%s", qu_commit="%s", qu_initPayAmount="%s",
		qu_amount_pay="%s", qu_installments="%s", qu_startDate="%s",
		qu_endDate="%s", q_cost="%s", q_alltotal="%s", erase_money_mi="%s",
		in_companyName="%s", in_postal="%s", in_address1="%s",
		in_address2="%s", in_address3="%s", in_tel="%s", in_email="%s",
		in_contactName="%s", syohkei="%s"',
		mysqli_real_escape_string($db, $row3['c_id']),
		mysqli_real_escape_string($db, $row3['u_id']),
		mysqli_real_escape_string($db, $row3['qu_bunrui']),
		mysqli_real_escape_string($db, $row3['qu_custom_name']),
		mysqli_real_escape_string($db, $row3['qu_custom_no']),
		mysqli_real_escape_string($db, $row3['qu_name']),
		mysqli_real_escape_string($db, $row3['qu_location']),
		mysqli_real_escape_string($db, $row3['qu_paymentDate']),
		mysqli_real_escape_string($db, $row3['qu_deliveryDate']),
		mysqli_real_escape_string($db, $qu_deposit2),
		mysqli_real_escape_string($db, $row3['qu_price']),
		mysqli_real_escape_string($db, $qu_commission2),
		mysqli_real_escape_string($db, $qu_commit2),
		mysqli_real_escape_string($db, $qu_initPayAmount2),
		mysqli_real_escape_string($db, $qu_amount_pay2),
		mysqli_real_escape_string($db, $qu_installments2),
		mysqli_real_escape_string($db, $row3['qu_startDate']),
		mysqli_real_escape_string($db, $qu_endDate2),
		mysqli_real_escape_string($db, $row3['q_cost']),
		mysqli_real_escape_string($db, $q_alltotal2),
		mysqli_real_escape_string($db, $q_alltotal2),
		mysqli_real_escape_string($db, $row3['in_companyName']),
		mysqli_real_escape_string($db, $row3['in_postal']),
		mysqli_real_escape_string($db, $row3['in_address1']),
		mysqli_real_escape_string($db, $row3['in_address2']),
		mysqli_real_escape_string($db, $row3['in_address3']),
		mysqli_real_escape_string($db, $row3['in_tel']),
		mysqli_real_escape_string($db, $row3['in_email']),
		mysqli_real_escape_string($db, $row3['in_contactName']),
		mysqli_real_escape_string($db, $row3['syohkei'])
);
mysqli_query($db,$sql) or die(mysqli_error($db));

$sql = sprintf('UPDATE `w1_quotation` SET qu_deposit="%s", qu_commission="%s",
		qu_commit="%s", qu_initPayAmount="%s", qu_amount_pay="%s",
		qu_installments="%s", qu_endDate="%s", q_alltotal="%s"
		WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_deposit2),
		mysqli_real_escape_string($db, $qu_commission2),
		mysqli_real_escape_string($db, $qu_commit2),
		mysqli_real_escape_string($db, $qu_initPayAmount2),
		mysqli_real_escape_string($db, $qu_amount_pay2),
		mysqli_real_escape_string($db, $qu_installments2),
		mysqli_real_escape_string($db, $qu_endDate2),
		mysqli_real_escape_string($db, $q_alltotal2),
		mysqli_real_escape_string($db, $get_qu_id)
);
mysqli_query($db,$sql) or die(mysqli_error($db));

w1_cust_update($get_qu_id);

$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND delFlag=0 ORDER BY qu_id DESC LIMIT 1',
		mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row10 = mysqli_fetch_assoc($record);

$sql = sprintf('SELECT * FROM `w1_q_items` WHERE qu_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $get_qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$ix = 0;
while($row4 = mysqli_fetch_assoc($record)) {
	$q_name[$ix]   = $row4['q_name'];
	$q_number[$ix] = $row4['q_number'];
	$q_unit[$ix]   = $row4['q_unit'];
	$q_price[$ix]  = $row4['q_price'];
	$q_total[$ix]  = $row4['q_total'];
	$ix++;
}
$item_count = $ix;

for($ix=0; $ix<$item_count; $ix++) {
	$sql = sprintf('INSERT INTO `q_items` SET qu_id="%d",
			q_name="%s", q_number="%s", q_unit="%s",
			q_price="%s", q_total="%s"',
			mysqli_real_escape_string($db, $row10['qu_id']),
			mysqli_real_escape_string($db, $q_name[$ix]),
			mysqli_real_escape_string($db, $q_number[$ix]),
			mysqli_real_escape_string($db, $q_unit[$ix]),
			mysqli_real_escape_string($db, $q_price[$ix]),
			mysqli_real_escape_string($db, $q_total[$ix])
	);
	mysqli_query($db, $sql) or die(mysqli_error($db));
}

$sql = sprintf('SELECT * FROM `w1_q_item_dep` WHERE qu_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $get_qu_id)
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
$item_dep_count = $ix;

for($ix=0; $ix<$item_dep_count; $ix++) {
	$sql = sprintf('INSERT INTO `q_item_dep` SET qu_id="%d",
			q_asset="%s", q_use="%s", q_detail="%s", q_life="%s"',
			mysqli_real_escape_string($db, $row10['qu_id']),
			mysqli_real_escape_string($db, $q_asset[$ix]),
			mysqli_real_escape_string($db, $q_use[$ix]),
			mysqli_real_escape_string($db, $q_detail[$ix]),
			mysqli_real_escape_string($db, $q_life[$ix])
	);
	mysqli_query($db,$sql) or die(mysqli_error($db));
}

$sql = sprintf('SELECT * FROM `w1_q_memo` WHERE qu_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $get_qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row5 = mysqli_fetch_assoc($record);

$sql = sprintf('INSERT INTO `q_memo` SET qu_id="%d", q_memo="%s"',
		mysqli_real_escape_string($db, $row10['qu_id']),
		mysqli_real_escape_string($db, $row5['q_memo'])
);
mysqli_query($db, $sql) or die(mysqli_error($db));

$sql = sprintf('SELECT * FROM `w1_data_quo` WHERE qu_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $get_qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$ix = 0;
while($row6 = mysqli_fetch_assoc($record)) {
	$u_id[$ix]            = $row6['u_id'];
	$c_id[$ix]            = $row6['c_id'];
	$yyyymm[$ix]          = $row6['yyyymm'];
	$qu_custom_name[$ix]  = $row6['qu_custom_name'];
	$qu_name[$ix]         = $row6['qu_name'];
	$q_alltotal[$ix]      = $row6['q_alltotal'];
	$qu_installments[$ix] = $row6['qu_installments'];
	$monthly_pay[$ix]     = $row6['monthly_pay'];
	$qu_startDate[$ix]    = $row6['qu_startDate'];
	$qu_endDate[$ix]      = $row6['qu_endDate'];
	$depo_date[$ix]       = $row6['depo_date'];
	$depo_memo[$ix]       = $row6['depo_memo'];
	$ix++;
}
$quo_count = $ix;

for($ix=0; $ix<$quo_count; $ix++) {
	$sql = sprintf('INSERT INTO `data_quo` SET qu_id="%d", u_id="%d", c_id="%d",
			yyyymm="%s", qu_custom_name="%s", qu_name="%s",
			q_alltotal="%s", qu_installments="%s", monthly_pay="%s",
			qu_startDate="%s", qu_endDate="%s", depo_date="%s",
			depo_memo="%s"',
			mysqli_real_escape_string($db, $row10['qu_id']),
			mysqli_real_escape_string($db, $u_id[$ix]),
			mysqli_real_escape_string($db, $c_id[$ix]),
			mysqli_real_escape_string($db, $yyyymm[$ix]),
			mysqli_real_escape_string($db, $qu_custom_name[$ix]),
			mysqli_real_escape_string($db, $qu_name[$ix]),
			mysqli_real_escape_string($db, $q_alltotal[$ix]),
			mysqli_real_escape_string($db, $qu_installments[$ix]),
			mysqli_real_escape_string($db, $monthly_pay[$ix]),
			mysqli_real_escape_string($db, $qu_startDate[$ix]),
			mysqli_real_escape_string($db, $qu_endDate[$ix]),
			mysqli_real_escape_string($db, $depo_date[$ix]),
			mysqli_real_escape_string($db, $depo_memo[$ix])
	);
	mysqli_query($db, $sql) or die(mysqli_error($db));
}

$sql = sprintf('SELECT * FROM `w1_data_opt` WHERE qu_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $get_qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$ix = 0;
while($row7 = mysqli_fetch_assoc($record)) {
	$u_id[$ix]     = $row7['u_id'];
	$c_id[$ix]     = $row7['c_id'];
	$yyyymm[$ix]   = $row7['yyyymm'];
	$sms_serv[$ix] = $row7['sms_serv'];
	$service1[$ix] = $row7['service1'];
	$service2[$ix] = $row7['service2'];
	$service3[$ix] = $row7['service3'];
	$operator[$ix] = $row7['operator'];
	$ix++;
}
$opt_count = $ix;

for($ix=0; $ix<$opt_count; $ix++) {
	$sql = sprintf('INSERT INTO `data_opt` SET qu_id="%d", u_id="%d", c_id="%d",
			yyyymm="%s", sms_serv="%s",
			service1="%s", service2="%s", service3="%s", operator="%s"',
			mysqli_real_escape_string($db, $row10['qu_id']),
			mysqli_real_escape_string($db, $u_id[$ix]),
			mysqli_real_escape_string($db, $c_id[$ix]),
			mysqli_real_escape_string($db, $yyyymm[$ix]),
			mysqli_real_escape_string($db, $sms_serv[$ix]),
			mysqli_real_escape_string($db, $service1[$ix]),
			mysqli_real_escape_string($db, $service2[$ix]),
			mysqli_real_escape_string($db, $service3[$ix]),
			mysqli_real_escape_string($db, $operator[$ix])
	);
	mysqli_query($db, $sql) or die(mysqli_error($db));
}

$sql = sprintf('SELECT * FROM `w1_simulation` WHERE qu_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $get_qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row8 = mysqli_fetch_assoc($record);

$sql = sprintf('INSERT INTO `simulation` SET qu_id="%d", c_id="%d",
		u_id="%d", pt_number=1, qu_price="%s", qu_startDate="%s",
		pt1_commit="%s", pt2_commit="%s", pt3_commit="%s",
		pt1_deposit="%s", pt2_deposit="%s", pt3_deposit="%s",
		pt1_commission="%s", pt2_commission="%s", pt3_commission="%s",
		pt1_initPayAmount="%s", pt2_initPayAmount="%s", pt3_initPayAmount="%s",
		pt1_amount_pay="%s", pt2_amount_pay="%s", pt3_amount_pay="%s",
		pt1_installments="%s", pt2_installments="%s", pt3_installments="%s",
		pt1_alltotal="%s", pt2_alltotal="%s", pt3_alltotal="%s"',
		mysqli_real_escape_string($db, $row10['qu_id']),
		mysqli_real_escape_string($db, $row8['c_id']),
		mysqli_real_escape_string($db, $row8['u_id']),
		mysqli_real_escape_string($db, $row8['qu_price']),		// ????????????
		mysqli_real_escape_string($db, $row8['qu_startDate']),		// ?????????
		mysqli_real_escape_string($db, $row8['pt1_commit']),		// ???????????????????????????
		mysqli_real_escape_string($db, $row8['pt2_commit']),		// ???????????????????????????
		mysqli_real_escape_string($db, $row8['pt3_commit']),		// ???????????????????????????
		mysqli_real_escape_string($db, $row8['pt1_deposit']),		// ??????
		mysqli_real_escape_string($db, $row8['pt2_deposit']),		// ??????
		mysqli_real_escape_string($db, $row8['pt3_deposit']),		// ??????
		mysqli_real_escape_string($db, $row8['pt1_commission']),	// ????????????????????????
		mysqli_real_escape_string($db, $row8['pt2_commission']),	// ????????????????????????
		mysqli_real_escape_string($db, $row8['pt3_commission']),	// ????????????????????????
		mysqli_real_escape_string($db, $row8['pt1_initPayAmount']),	// ??????????????????
		mysqli_real_escape_string($db, $row8['pt2_initPayAmount']),	// ??????????????????
		mysqli_real_escape_string($db, $row8['pt3_initPayAmount']),	// ??????????????????
		mysqli_real_escape_string($db, $row8['pt1_amount_pay']),	// ??????????????????
		mysqli_real_escape_string($db, $row8['pt2_amount_pay']),	// ??????????????????
		mysqli_real_escape_string($db, $row8['pt3_amount_pay']),	// ??????????????????
		mysqli_real_escape_string($db, $row8['pt1_installments']),	// ????????????
		mysqli_real_escape_string($db, $row8['pt2_installments']), 	// ????????????
		mysqli_real_escape_string($db, $row8['pt3_installments']),	// ????????????
		mysqli_real_escape_string($db, $row8['pt1_alltotal']),		// ????????????
		mysqli_real_escape_string($db, $row8['pt2_alltotal']),		// ????????????
		mysqli_real_escape_string($db, $row8['pt3_alltotal'])		// ????????????
);
mysqli_query($db,$sql) or die(mysqli_error($db));

// w1_data_opt
$sql = sprintf('DELETE FROM `w1_data_opt` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $get_qu_id)
);
 mysqli_query($db,$sql) or die(mysqli_error($db));

// w1_data_quo
$sql = sprintf('DELETE FROM `w1_data_quo` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $get_qu_id)
);
mysqli_query($db,$sql) or die(mysqli_error($db));

// w1_q_items
$sql = sprintf('DELETE FROM `w1_q_items` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $get_qu_id)
);
mysqli_query($db,$sql) or die(mysqli_error($db));

// w1_q_item_dep
$sql = sprintf('DELETE FROM `w1_q_item_dep` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $get_qu_id)
);
mysqli_query($db,$sql) or die(mysqli_error($db));

// w1_q_memo
$sql = sprintf('DELETE FROM `w1_q_memo` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $get_qu_id)
);
mysqli_query($db,$sql) or die(mysqli_error($db));

// w1_quotation
$sql = sprintf('DELETE FROM `w1_quotation` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $get_qu_id)
);
mysqli_query($db,$sql) or die(mysqli_error($db));

// w1_simulation
$sql = sprintf('DELETE FROM `w1_simulation` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $get_qu_id)
);
mysqli_query($db,$sql) or die(mysqli_error($db));


$_SESSION['tmp1_qu_id'] = "";
$_SESSION['estim_new'] = "OK";
header("Location:/manage/estimates/new/");
exit();
?>