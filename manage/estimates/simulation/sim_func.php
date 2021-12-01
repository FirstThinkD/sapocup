<?php
function partition($strday, $strdate, $count, $allpay, $deposit, $initpay) {
	// strday:支払日, strdate:実行日, count:分割回数, allpay:総支払額, deposit:頭金, initpay:初回お支払額
	$out  = array();
	$out2 = array();

	// echo "strdate=". $strdate. " count=". $count. " allpay=". $allpay. " deposit=". $deposit. " initpay=". $initpay. "<br>";
	// echo "allpay/count=". ($allpay / $count). "\n";

	$w_count = (int)$count;
	if ($count == "") {
		$w_count = 0;
	}
	$w_allpay = (int)$allpay;
	if ($allpay == "") {
		$w_allpay = 0;
	}
	$w_deposit = (int)$deposit;
	if ($deposit == "") {
		$w_deposit = 0;
	}
	$w_initpay = (int)$initpay;
	if ($initpay == "") {
		$w_initpay = 0;
	}

	if ($w_count == 0) {
		$out2[0][5] = 0;
		return ($out2);
	}

	$str_date = date('Ymd', strtotime(date($strdate)));
	$w_pay = $w_allpay;

	$skei = (($w_pay - $w_deposit) / $w_count);
	$pay = floor($skei);					// 切り捨て
	$hasu = $skei - $pay;
	// $pay = ceil(($w_pay - $w_deposit) / $w_count);	// 切り上げ

	$out[0][0] = 1;
	$out[0][1] = $str_date;
	$out[0][2] = $w_pay;
	$out[0][3] = ($w_deposit + $pay + ($hasu * $w_count));
	$w_pay -= ($w_deposit + $pay + ($hasu * $w_count));
	$out[0][4] = $w_pay;

	if ($strday == "末日") {
		$w_dateDD  = "31";
	} else {
		$w_dateDD  = date('d', strtotime(date($str_date)));
	}
	for($ix=1; $ix<$w_count; $ix++) {
		$w_dateYM = date('Ym', strtotime(date($str_date)));
		$w_date1  = $w_dateYM. "01";
		$w_date2  = date('Ymd', strtotime($w_date1. " +1 month"));
		$w_date3  = date('Ymt', strtotime($w_date2));
		$w_dateYM = date('Ym', strtotime(date($w_date2)));
		$w_date4  = $w_dateYM. $w_dateDD;

		if ($w_date3 > $w_date4) {
			$str_date = $w_date4;
		} else {
			$str_date = $w_date3;
		}
		// echo "str_date=". $str_date. "<br>";

		$out[$ix][0] = ($ix + 1);
		$out[$ix][1] = $str_date;
		$out[$ix][2] = $w_pay;
		$out[$ix][3] = $pay;
		$w_pay -= $pay;
		$out[$ix][4] = $w_pay;
	}

	if ($w_pay < 0) {
		// echo "ix=". $ix. " pay=". $pay. " w_pay=". $w_pay. " w_pay2=". $w_pay2. "\n";
		// exit();

		$w_pay2 = $w_pay;
		$w_pay = $w_allpay;
		// $pay = ceil(($w_pay - $w_deposit) / $w_count);		// 切り上げ
		// $out[0][2] = ($w_pay + $w_pay2);
		$out[0][3] = ($w_deposit + $pay + $w_pay2);
		$w_pay -= ($w_deposit + $pay + $w_pay2);
		$out[0][4] = $w_pay;
		for($ix=1; $ix<$w_count; $ix++) {
			// $out[$ix][0] = ($ix + 1);
			// $out[$ix][1] = $str_date;
			$out[$ix][2] = $w_pay;
			$out[$ix][3] = $pay;
			$w_pay2 = $pay;
			$w_pay -= $pay;
			$out[$ix][4] = $w_pay;
			// $str_date = date('Ymd', strtotime($str_date. " +1 month"));
		}
	}

	if ($out[($w_count - 1)][4] != 0) {
		$out[($w_count - 1)][3] += $out[($w_count - 1)][4];
		$out[($w_count - 1)][4] = 0;
	}

	for($ix=0; $ix<$w_count; $ix++) {
		$out2[$ix][0] = $out[$ix][0];
		$out2[$ix][1] = date("Y年m月d日", strtotime($out[$ix][1]));
		$out2[$ix][2] = number_format($out[$ix][2]);
		$out2[$ix][3] = number_format($out[$ix][3]);
		$out2[$ix][4] = number_format($out[$ix][4]);
		$out2[$ix][6] = $out[$ix][1];
		$out2[$ix][7] = $out[$ix][2];
		$out2[$ix][8] = $out[$ix][3];
		$out2[$ix][9] = $out[$ix][4];
		$out2[$ix][10] = date("Ym", strtotime($out[$ix][1]));
	}
	$out2[0][5] = $ix;

	return ($out2);
}
?>