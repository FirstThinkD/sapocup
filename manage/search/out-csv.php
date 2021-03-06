<?php
session_start();
require_once(__DIR__ . '/../../common/dbconnect.php');
require(__DIR__ . '/../data/functions.php');

if (empty($_SESSION['loginID'])) {
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

if ($_SESSION['dep_chkbox2'][0] == -1) {
	// echo "dep_chkbox2 ALL". "<br>";
	$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND sms_flag=1
		AND delFlag=0',
		mysqli_real_escape_string($db, $_SESSION['loginID'])
	);
} else if ($_SESSION['dep_chkbox2'][0] > 0) {
	// echo "dep_chkbox2". $_SESSION['dep_chkbox2'][0]. "<br>";
	$sql = 'SELECT * FROM `quotation` WHERE ';
	for($ix=0; $ix<$_SESSION['dep_chkbox2'][0]; $ix++) {
		// echo "dep_chkbox2=". $_SESSION['dep_chkbox2'][($ix + 1)]. "<br>";
		$sql .= 'qu_id="'. $_SESSION['dep_chkbox2'][($ix + 1)]. '"';
		if ($_SESSION['dep_chkbox2'][0] > ($ix + 1)) {
			$sql .= ' OR ';
		}
	}
}

if ($_SESSION['dep_chkbox2'][0] != 0) {
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$ix = 0;
	while ($row0 = mysqli_fetch_assoc($record)) {
		$qu_id[$ix]            = $row0['qu_id'];
		// $in_id[$ix]            = $row0['in_id'];
		$c_id[$ix]             = $row0['c_id'];
		$qu_bunrui[$ix]        = $row0['qu_bunrui'];
		$qu_custom_name[$ix]   = $row0['qu_custom_name'];
		// $qu_custom_no[$ix]     = $row0['qu_custom_no'];
		$qu_name[$ix]          = $row0['qu_name'];
		// $qu_location[$ix]      = $row0['qu_location'];
		// $qu_paymentDate[$ix]   = sprintf("毎月%02d日", $row0['qu_paymentDate']);
		// $qu_deliveryDate[$ix]  = date('Y年m月d日', strtotime($row0['qu_deliveryDate']));
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
		// $q_alltotal[$ix]       = $row0['q_alltotal'];
		// $in_companyName[$ix]   = $row0['in_companyName'];
		// $in_postal[$ix]        = $row0['in_postal'];
		// $in_address1[$ix]      = $row0['in_address1'];
		// $in_address2[$ix]      = $row0['in_address2'];
		// $in_address3[$ix]      = $row0['in_address3'];
		// $in_tel[$ix]           = $row0['in_tel'];
		// $in_email[$ix]         = $row0['in_email'];
		// $in_contactName[$ix]   = $row0['in_contactName'];

		// $customer_id[$ix]      = sprintf("%06s", $qu_id[$ix]);
		// if ($q_alltotal[$ix] != "") {
		//	if (ctype_digit($q_alltotal[$ix])) {
		//		$q_alltotal[$ix] = number_format($q_alltotal[$ix]). "円";
		//	} else {
		//		$q_alltotal[$ix] = $q_alltotal[$ix]. "円";
		//	}
		//	if (!(strstr($q_alltotal[$ix], ',') === False)) {
		//		$q_alltotal[$ix] = preg_replace('/"/', '""',$q_alltotal[$ix]);
		//		$q_alltotal[$ix] = '"' . $q_alltotal[$ix] . '"';
		//	}
		// }

		$sql2 = sprintf('SELECT * FROM `q_items` WHERE qu_id="%d" AND delFlag=0',
			mysqli_real_escape_string($db, $qu_id[$ix])
		);
		$record2 = mysqli_query($db, $sql2) or die(mysqli_error($db));
		$iz = 0;
		while($row2 = mysqli_fetch_assoc($record2)) {
			// $qi_id[$ix][$iz]       = $row2['q_id'];
			// $qi_qu_id[$ix][$iz]    = $row2['qu_id'];
			$qi_q_name[$ix][$iz]   = $row2['q_name'];
			$qi_q_number[$ix][$iz] = $row2['q_number'];
			$qi_q_unit[$ix][$iz]   = $row2['q_unit'];
			$qi_q_price[$ix][$iz]  = $row2['q_price'];
			$qi_q_total[$ix][$iz]  = $row2['q_total'];

			if (ctype_digit($qi_q_number[$ix][$iz])) {
				$qi_q_number[$ix][$iz] = number_format($qi_q_number[$ix][$iz]);
			}
			if (!(strstr($qi_q_number[$ix][$iz], ',') === False)) {
				$qi_q_number[$ix][$iz] = preg_replace('/"/', '""',$qi_q_number[$ix][$iz]);
				$qi_q_number[$ix][$iz] = '"' . $qi_q_number[$ix][$iz] . '"';
			}

			// if (ctype_digit($qi_q_total[$ix][$iz])) {
				$qi_q_total[$ix][$iz] = number_format($qi_q_total[$ix][$iz]). "円";
			// } else {
			//	$qi_q_total[$ix][$iz] = $qi_q_total[$ix][$iz]. "円";
			// }
			if (!(strstr($qi_q_total[$ix][$iz], ',') === False)) {
				$qi_q_total[$ix][$iz] = preg_replace('/"/', '""',$qi_q_total[$ix][$iz]);
				$qi_q_total[$ix][$iz] = '"' . $qi_q_total[$ix][$iz] . '"';
			}
			$iz++;
		}
		$qi_count[$ix] = $iz;

		$sql3 = sprintf('SELECT * FROM `q_item_dep` WHERE qu_id="%d" AND delFlag=0',
			mysqli_real_escape_string($db, $qu_id[$ix])
		);
		$record3 = mysqli_query($db, $sql3) or die(mysqli_error($db));
		$iz =0;
		while($row3 = mysqli_fetch_assoc($record3)) {
			// $dep_id[$ix][$iz]       = $row3['dep_id'];
			// $dep_qu_id[$ix][$iz]    = $row3['qu_id'];
			$dep_q_asset[$ix][$iz]  = $row3['q_asset'];
			$dep_q_use[$ix][$iz]    = $row3['q_use'];
			$dep_q_detail[$ix][$iz] = $row3['q_detail'];
			$dep_q_life[$ix][$iz]   = $row3['q_life'];
			$iz++;
		}
		$dep_count[$ix] = $iz;
		$ix++;
	}
	$qu_count = $ix;

	$sql4 = sprintf('SELECT * FROM `customer` WHERE u_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $_SESSION['loginID'])
	);
	$record4 = mysqli_query($db, $sql4) or die(mysqli_error($db));
	$ix = 0;
	while($row4 = mysqli_fetch_assoc($record4)) {
		$c_id[$ix] = $row4['c_id'];

		for($iy=0; $iy<$qu_count; $iy++) {
			if ($c_id[$ix] == $qu_custom_name[$iy]) {
				$qu_custom_name[$iy] = $row4['c_name'];
			}
		}
		$ix++;
	}
}

// echo "sql=". $sql. "<br>";
// echo "qu_count=". $qu_count. "<br>";
// for($ix=0; $ix<$qu_count; $ix++) {
//	echo "qu_id=". $qu_id[$ix]. " qi_count=". $qi_count[$ix]. " dep_count=". $dep_count[$ix]. "<br>";
// }
// exit();

$_SESSION['qu_chkbox'][0] = 0;

$dfile_name = date("YmdHis"). ".csv";
$dfile_path = "/usr/home/haw1008ufet9/html/manage/deposit/csv/". $dfile_name;

$ddata = "題名：減価償却一覧,\n";
if ($browser == "sp") {
	$str = pack('C*',0xEF,0xBB,0xBF). $ddata;
} else {
	$str = mb_convert_encoding($ddata, "SJIS", "UTF-8");
}
file_put_contents($dfile_path, $str, FILE_APPEND);

$ddata = "出力日：". date('Y年m月d日'). ",\n\n";
if ($browser == "sp") {
	$str = pack('C*',0xEF,0xBB,0xBF). $ddata;
} else {
	$str = mb_convert_encoding($ddata, "SJIS", "UTF-8");
}
file_put_contents($dfile_path, $str, FILE_APPEND);

for($ix=0; $ix<$qu_count; $ix++) {
	$ddata = ",対象分類,". $qu_bunrui[$ix]. ",,請求先,". $qu_custom_name[$ix]. ",,工事名,". $qu_name[$ix]. "\n";
	if ($browser == "sp") {
		$str = pack('C*',0xEF,0xBB,0xBF). $ddata;
	} else {
		$str = mb_convert_encoding($ddata, "SJIS", "UTF-8");
	}
	file_put_contents($dfile_path, $str, FILE_APPEND);

	$ddata = "NO,適用名称,区分,構造・用途,細目,耐用年数,数量,単位,金額\n";
	if ($browser == "sp") {
		$str = pack('C*',0xEF,0xBB,0xBF). $ddata;
	} else {
		$str = mb_convert_encoding($ddata, "SJIS", "UTF-8");
	}
	file_put_contents($dfile_path, $str, FILE_APPEND);

	for($iz=0; $iz<$qi_count[$ix]; $iz++) {
		$ddata = ($iz+1). ",". $qi_q_name[$ix][$iz]. ",". $dep_q_asset[$ix][$iz]. ",". $dep_q_use[$ix][$iz]. ",". $dep_q_detail[$ix][$iz]. ",". $dep_q_life[$ix][$iz]. ",". $qi_q_number[$ix][$iz]. ",". $qi_q_unit[$ix][$iz]. ",". $qi_q_total[$ix][$iz]. "\n";
		if ($browser == "sp") {
			$str = pack('C*',0xEF,0xBB,0xBF). $ddata;
		} else {
			$str = mb_convert_encoding($ddata, "SJIS", "UTF-8");
		}
		file_put_contents($dfile_path, $str, FILE_APPEND);
	}
	if ($ix < $qu_count) {
		$ddata = "\n";
		if ($browser == "sp") {
			$str = pack('C*',0xEF,0xBB,0xBF). $ddata;
		} else {
			$str = mb_convert_encoding($ddata, "SJIS", "UTF-8");
		}
		file_put_contents($dfile_path, $str, FILE_APPEND);
	}
}

header('Content-Type: application/force-download');
header('Content-Length: '.filesize($dfile_path));
header('Content-disposition: attachment; filename="'.$dfile_name.'"');
readfile($dfile_path);
unlink($dfile_path);
?>