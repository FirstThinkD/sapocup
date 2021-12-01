<?php
session_start();
require_once('../../common/dbconnect.php');

if ($_SESSION['loginID'] == "") {
	header("Location:/");
	exit();
}

$ua=$_SERVER['HTTP_USER_AGENT'];
$browser = ((strpos($ua,'iPhone')!==false)||(strpos($ua,'iPod')!==false)||(strpos($ua,'Android')!==false));
if ($browser == true){
	$browser = 'sp';
} else {
	$browser = 'pc';
}

if ($_SESSION['qu_chkbox'][0] == -1) {
	// echo "qu_chkbox ALL". "<br>";
	$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $_SESSION['loginID'])
	);
} else if ($_SESSION['qu_chkbox'][0] > 0) {
	// echo "qu_chkbox". $_SESSION['qu_chkbox'][0]. "<br>";
	$sql = 'SELECT * FROM `quotation` WHERE u_id="'. $_SESSION['loginID']. '" AND delFlag=0 AND ';
	for($ix=0; $ix<$_SESSION['qu_chkbox'][0]; $ix++) {
		// echo "qu_chkbox=". $_SESSION['qu_chkbox'][($ix + 1)]. "<br>";
		$sql .= 'qu_id="'. $_SESSION['qu_chkbox'][($ix + 1)]. '"';
		if ($_SESSION['qu_chkbox'][0] > ($ix + 1)) {
			$sql .= ' OR ';
		}
	}

}
// echo "sql=". $sql;

$qu_count = 0;
if ($_SESSION['qu_chkbox'][0] != 0) {
	// $sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND delFlag=0',
	//	mysqli_real_escape_string($db, $_SESSION['loginID'])
	// );
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$ix = 0;
	while ($row0 = mysqli_fetch_assoc($record)) {
		$qu_id[$ix]            = $row0['qu_id'];
		$in_id[$ix]            = $row0['in_id'];
		$c_id[$ix]             = $row0['c_id'];
		$qu_bunrui[$ix]        = $row0['qu_bunrui'];
		$qu_custom_name[$ix]   = $row0['qu_custom_name'];
		$qu_custom_no[$ix]     = $row0['qu_custom_no'];
		$qu_name[$ix]          = $row0['qu_name'];
		$qu_location[$ix]      = $row0['qu_location'];
		$qu_paymentDate[$ix]   = sprintf("毎月%02d日", $row0['qu_paymentDate']);
		$qu_deliveryDate[$ix]  = $row0['qu_deliveryDate'];
		// $qu_deposit[$ix]       = $row0['qu_deposit'];
		// $qu_commission[$ix]    = $row0['qu_commission'];
		// $qu_initPayAmount[$ix] = $row0['qu_initPayAmount'];
		// $qu_installments[$ix]  = $row0['qu_installments'];
		// $qu_startDate[$ix]     = $row0['qu_startDate'];
		// $qu_endDate[$ix]       = $row0['qu_endDate'];
		// $qu_note[$ix]          = $row0['qu_note'];
		// $qu_pdf[$ix]           = $row0['qu_pdf'];
		// $qu_dir[$ix]           = $row0['qu_dir'];
		// $qu_date[$ix]          = $row0['qu_date'];
		// $qu_number[$ix]        = $row0['qu_number'];
		// $qu_place[$ix]         = $row0['qu_place'];
		// $q_subtotal[$ix]       = $row0['q_subtotal'];
		// $q_cost[$ix]           = $row0['q_cost'];
		$q_alltotal[$ix]       = $row0['q_alltotal'];
		// $in_companyName[$ix]   = $row0['in_companyName'];
		// $in_postal[$ix]        = $row0['in_postal'];
		// $in_address1[$ix]      = $row0['in_address1'];
		// $in_address2[$ix]      = $row0['in_address2'];
		// $in_address3[$ix]      = $row0['in_address3'];
		// $in_tel[$ix]           = $row0['in_tel'];
		// $in_email[$ix]         = $row0['in_email'];
		// $in_contactName[$ix]   = $row0['in_contactName'];

		// $customer_id[$ix]      = 10000000 + $c_id[$ix];
		$customer_id[$ix]      = sprintf("%06s", $qu_id[$ix]);
		if ($q_alltotal[$ix] != "") {
			if (ctype_digit($q_alltotal[$ix])) {
				$q_alltotal[$ix] = number_format($q_alltotal[$ix]). "円";
			} else {
				$q_alltotal[$ix] = $c_loan_total[$ix]. "円";
			}
			if (!(strstr($q_alltotal[$ix], ',') === False)) {
				$q_alltotal[$ix] = preg_replace('/"/', '""',$q_alltotal[$ix]);
				$q_alltotal[$ix] = '"' . $q_alltotal[$ix] . '"';
			}
		}

		if ($qu_deliveryDate[$ix] != "") {
			$qu_deliveryDate[$ix] = date('Y年m月d日', strtotime($qu_deliveryDate[$ix]));
		}

		$ix++;
	}
	$qu_count = $ix;

	$sql = sprintf('SELECT * FROM `customer` WHERE u_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $_SESSION['loginID'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$ix = 0;
	while($row0 = mysqli_fetch_assoc($record)) {
		$c_id[$ix]   = $row0['c_id'];

		for($iy=0; $iy<$qu_count; $iy++) {
			if ($c_id[$ix] == $qu_custom_name[$iy]) {
				$qu_custom_name[$iy] = $row0['c_name'];
			}
		}
		$ix++;
	}
}

$_SESSION['qu_chkbox'][0] = 0;

$dfile_name = date("YmdHis"). ".csv";
$dfile_path = "/usr/home/haw1008ufet9/html/manage/estimates/csv/". $dfile_name;

$ddata = "題名：見積書一覧,\n";
if ($browser == "sp") {
	$str = pack('C*',0xEF,0xBB,0xBF). $ddata;
} else {
	$str = mb_convert_encoding($ddata, "SJIS", "UTF-8");
}
file_put_contents($dfile_path, $str, FILE_APPEND);

$ddata = "出力日：". date('Y年m月d日'). ",\n";
if ($browser == "sp") {
	$str = pack('C*',0xEF,0xBB,0xBF). $ddata;
} else {
	$str = mb_convert_encoding($ddata, "SJIS", "UTF-8");
}
file_put_contents($dfile_path, $str, FILE_APPEND);

$ddata = "見積書番号,名称,請求先,支払日,受渡期日,総金額\n";
if ($browser == "sp") {
	$str = pack('C*',0xEF,0xBB,0xBF). $ddata;
} else {
	$str = mb_convert_encoding($ddata, "SJIS", "UTF-8");
}
file_put_contents($dfile_path, $str, FILE_APPEND);

for($ix=0; $ix<$qu_count; $ix++) {
	$ddata = "=\"". $customer_id[$ix]. "\",". $qu_name[$ix]. ",". $qu_custom_name[$ix]. ",". $qu_paymentDate[$ix]. ",". $qu_deliveryDate[$ix]. ",". $q_alltotal[$ix]. "\n";
	if ($browser == "sp") {
		$str = pack('C*',0xEF,0xBB,0xBF). $ddata;
	} else {
		$str = mb_convert_encoding($ddata, "SJIS", "UTF-8");
	}
	file_put_contents($dfile_path, $str, FILE_APPEND);
}

header('Content-Type: application/force-download');
header('Content-Length: '.filesize($dfile_path));
header('Content-disposition: attachment; filename="'.$dfile_name.'"');
readfile($dfile_path);
unlink($dfile_path);

// header("Location:/manage/estimates/");
// exit();
?>