<?php
session_start();
// ini_set("display_errors", 1);
require_once(__DIR__ . '/../../common/dbconnect.php');

if (empty($_SESSION['loginID'])) {
	header("Location:/");
	exit();
}

$week = ['日曜日',   // 0
	 '月曜日',   // 1
	 '火曜日',   // 2
	 '水曜日',   // 3
	 '木曜日',   // 4
	 '金曜日',   // 5
	 '土曜日',   // 6
];

$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row7 = mysqli_fetch_assoc($record);

if ($_SESSION['da_pdfbox'][0][2] > 0) {
	for($ix=0; $ix<$_SESSION['da_pdfbox'][0][2]; $ix++) {
		$depo_date[$ix] = $_SESSION['da_pdfbox'][$ix][1];
	}

	$sql = sprintf('SELECT * FROM `customer` WHERE u_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $_SESSION['loginID'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$ix = 0;
	while ($row0 = mysqli_fetch_assoc($record)) {
		$cu_c_id[$ix]   = $row0['c_id'];
		$cu_c_name[$ix] = $row0['c_name'];
		$cu_c_attr[$ix] = $row0['c_attr'];
		$ix++;
	}
	$cu_count = $ix;

	$sql = 'SELECT * FROM `data_quo` WHERE ';
	for($ix=0; $ix<$_SESSION['da_pdfbox'][0][2]; $ix++) {
		$sql .= 'da_id="'. $_SESSION['da_pdfbox'][$ix][0]. '"';
		if ($_SESSION['da_pdfbox'][0][2] > ($ix + 1)) {
			$sql .= ' OR ';
		}
	}
	$sql .= ' ORDER BY qu_id';
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$ix = 0;
	while ($row0 = mysqli_fetch_assoc($record)) {
		$qu_id[$ix] = $row0['qu_id'];
		$ix++;
	}
	$da_count = $ix;

	$_SESSION['da_pdfbox'][0][2] = 0;

	$sql = 'SELECT * FROM `quotation` WHERE ';
	for($ix=0; $ix<$da_count; $ix++) {
		$sql .= 'qu_id="'. $qu_id[$ix]. '"';
		if ($ix < ($da_count - 1)) {
			$sql .= ' OR ';
		}
	}
	$sql .= ' ORDER BY qu_id';
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$ix = 0;
	while ($row0 = mysqli_fetch_assoc($record)) {
		$qu_id1[$ix]           = $row0['qu_id'];
		$c_id1[$ix]            = $row0['c_id'];
		$u_id1[$ix]            = $row0['u_id'];
		$qu_bunrui[$ix]        = $row0['qu_bunrui'];
		$qu_custom_name[$ix]   = $row0['qu_custom_name'];
		$qu_custom_no[$ix]     = $row0['qu_custom_no'];
		$qu_name[$ix]          = $row0['qu_name'];
		$qu_location[$ix]      = $row0['qu_location'];
		$qu_paymentDate[$ix]   = $row0['qu_paymentDate'];
		$qu_deliveryDate[$ix]  = $row0['qu_deliveryDate'];
		$qu_deposit[$ix]       = $row0['qu_deposit'];
		$qu_price[$ix]         = number_format($row0['qu_price']);
		$qu_commission[$ix]    = $row0['qu_commission'];
		$qu_commit[$ix]        = number_format($row0['qu_commit']);
		$qu_initPayAmount[$ix] = $row0['qu_initPayAmount'];
		$qu_amount_pay[$ix]    = number_format($row0['qu_amount_pay']);
		$qu_installments[$ix]  = $row0['qu_installments'];
		$qu_startDate[$ix]     = $row0['qu_startDate'];
		$qu_endDate[$ix]       = $row0['qu_endDate'];
		$qu_note[$ix]          = $row0['qu_note'];
		$q_cost[$ix]           = number_format($row0['q_cost']);
		$q_alltotal[$ix]       = number_format($row0['q_alltotal']);
		$in_companyName[$ix]   = $row0['in_companyName'];
		$in_postal[$ix]        = $row0['in_postal'];
		$in_address1[$ix]      = $row0['in_address1'];
		$in_address2[$ix]      = $row0['in_address2'];
		$in_address3[$ix]      = $row0['in_address3'];
		$in_tel[$ix]           = $row0['in_tel'];
		$in_email[$ix]         = $row0['in_email'];
		$in_contactName[$ix]   = $row0['in_contactName'];
		$syohkei[$ix]          = $row0['syohkei'];
		$sms_memo[$ix]         = $row0['sms_memo'];
		$sms_flag[$ix]         = $row0['sms_flag'];
		$txt_line[$ix]         = $row0['txt_line'];
		// $memo[$ix]             = $row0['memo'];
		// $updated[$ix]          = $row0['updated'];

		if (ctype_digit($syohkei[$ix])) {
			$syohkei[$ix] = number_format($row0['syohkei']);
		}

		for($iz=0; $iz<$cu_count; $iz++) {
			if ($c_id1[$ix] == $cu_c_id[$iz]) {
				$qu_custom_name[$ix] = $cu_c_name[$iz];
				$qu_custom_attr[$ix] = $cu_c_attr[$iz];
				break;
			}
		}

		$qu_no[$ix] = sprintf("%06d", $qu_id1[$ix]);

		// $h_week = date("w", strtotime($depo_date[$ix]));
		if ($qu_paymentDate[$ix] == "末日") {
			$depo_date[$ix] = "毎月". $qu_paymentDate[$ix];
		} else {
			$depo_date[$ix] = "毎月". date('d日', strtotime($depo_date[$ix]));
		}

		// $h_week = date("w", strtotime($qu_deliveryDate[$ix]));
		$qu_deliveryDate[$ix] = date("Y年m月d日", strtotime($qu_deliveryDate[$ix]));

		$qu_startDate[$ix] = date("Y年m月", strtotime($qu_startDate[$ix]));

		$qu_endDate[$ix] = date("Y年m月", strtotime($qu_endDate[$ix]));

		if ($qu_initPayAmount[$ix] != "") {
			$qu_initPayAmount[$ix] = "¥". number_format($row0['qu_initPayAmount']);
		}

		if ($qu_deposit[$ix] != "") {
			$qu_deposit[$ix] = "¥". number_format($row0['qu_deposit']);
		}

		if ($row0['updated'] != "") {
			$updated[$ix] = date('Y年m月d日', strtotime($row0['updated']));
		} else {
			$updated[$ix] = date('Y年m月d日');
		}

		$ix++;
	}
	$q1_count = $ix;

	for($iy=0; $iy<$da_count; $iy++) {
		$q_total10[$iy][0] = 0;
		$q_total10[$iy][1] = 0;
		$q_total10[$iy][2] = 0;

		$sql = sprintf('SELECT * FROM `q_items` WHERE qu_id="%d" ORDER BY qu_id',
			mysqli_real_escape_string($db, $qu_id[$iy])
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$ix = 0;
		while ($row0 = mysqli_fetch_assoc($record)) {
			$qu_id2[$iy][$ix]   = $row0['qu_id'];
			$q_name[$iy][$ix]   = $row0['q_name'];
			$q_number[$iy][$ix] = $row0['q_number'];
			$q_unit[$iy][$ix]   = $row0['q_unit'];
			$q_price[$iy][$ix]  = $row0['q_price'];
			$q_total[$iy][$ix]  = $row0['q_total'];

			// if ($q_number[$iy][$ix] != "") {
			//	$q_number[$iy][$ix] = number_format($q_number[$iy][$ix]);
			// }
			if ($q_price[$iy][$ix] != "") {
				$q_price[$iy][$ix] = number_format($q_price[$iy][$ix]);
			}

			// 複数ページ対応
			if ($ix < 10) {
				$q_total10[$iy][0] += $q_total[$iy][$ix];
			} else if ($ix >= 10 && $ix < 30) {
				$q_total10[$iy][1] += $q_total[$iy][$ix];
			} else {
				$q_total10[$iy][2] += $q_total[$iy][$ix];
			}

			if ($q_total[$iy][$ix] != "") {
				$q_total[$iy][$ix] = number_format($q_total[$iy][$ix]);
			}

			$ix++;
		}
		if ($ix >= 10) {
			$q_total10[$iy][1] = $q_total10[$iy][0] + $q_total10[$iy][1];
			$q_total10[$iy][1] = number_format($q_total10[$iy][1]);
		}
		$q_total10[$iy][0] = number_format($q_total10[$iy][0]);

		$q2_count[$iy] = $ix;
	}

	$sql = 'SELECT * FROM `q_memo` WHERE ';
	for($ix=0; $ix<$da_count; $ix++) {
		$sql .= 'qu_id="'. $qu_id[$ix]. '"';
		if ($ix < ($da_count - 1)) {
			$sql .= ' OR ';
		}
	}
	$sql .= ' ORDER BY qu_id';
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$ix = 0;
	while ($row0 = mysqli_fetch_assoc($record)) {
		$qu_id3[$ix] = $row0['qu_id'];
		$q_memo[$ix] = $row0['q_memo'];
		$ix++;
	}
	$q3_count = $ix;
} else {
	header("Location:/");
	exit();
}

$now_date = date("Ymd");
// $h_week   = date("w", strtotime($now_date));
// $now_date = date("Y年m月d日", strtotime($now_date));

if ($_SESSION['da_pdfbox'][0][2] != 1) {
	$tmp_file = date('YmdHis');
	$tmp_dir1 = "/usr/home/haw1008ufet9/html/manage/tcpdf/tmp/";
	$tmp_dir2 = $tmp_dir1. $tmp_file;

	if (file_exists($tmp_dir2)) {
		for($ix=1; $ix<10; $ix++) {
			$tmp_dir2 += $ix;
			if (file_exists($tmp_dir2)) {
				// リトライ
			} else {
				// OK
				mkdir($tmp_dir2, 0755);
				$filename = $tmp_dir2. "/". $tmp_file;
				break;
			}
			$file = "./test.log";
			$data = "NG tmp_dir2=". $tmp_dir2;
			$data .= "\n";
			file_put_contents($file, $data, FILE_APPEND);
		}
	} else {
		mkdir($tmp_dir2, 0755);
		$filename = $tmp_dir2. "/". $tmp_file;
	}
}

/* ---------------------------------------------------------------------

	初期設定

--------------------------------------------------------------------- */
require_once(__DIR__ . '/tcpdf/tcpdf.php'); // TCPDFライブラリ
require_once(__DIR__ . '/fpdi/src/autoload.php'); // FPDIライブラリ

use setasign\Fpdi\TcpdfFpdi; // FPDIクラス読み込み
/*
	$pdf = new FPDI();
	$pdf = new TCPDF();

	$pdf = new Fpdi\TcpdfFpdi();
*/

// unset($pdf);

$memo_name = date("YmdHis");
$memo_file = "/usr/home/haw1008ufet9/html/manage/tcpdf/tmp_memo/". $memo_name. ".txt";

$pdf = new TcpdfFpdi(); // フォント設定

for($ix=0; $ix<$q1_count; $ix++) {
	/* ---------------------------------------------------------------------

		変数まとめ

	--------------------------------------------------------------------- */
	/* 契約情報 */
	$contract_num		= $qu_no[$ix];			// ナンバー
	$contract_date		= $updated[$ix];		// 日付
	$contract_company	= $qu_custom_name[$ix];		// 会社名
	if ($qu_custom_attr[$ix] == "法人") {
		$contract_c_attr = "御中";
	} else {
		$contract_c_attr = "様";
	}
	$contract_bunrui	= $qu_bunrui[$ix];		// 工事・商品分類
	$contract_name		= $qu_name[$ix];		// 工事・商品名称
	$contract_place 	= $qu_location[$ix];		// 工事・商品場所
	$contract_pay 		= $depo_date[$ix];		// 支払日
	$contract_deliver 	= $qu_deliveryDate[$ix];	// 受渡期日

	/* 貴社情報 */
//	if ($row7['u_type'] == "法人") {
//		$our_company 	= $row7['u_company'];		// 会社情報
//		$our_person 	= $row7['u_person'];		// 担当者名
//		$our_post	= $row7['u_postal'];		// 住所郵便番号
//		$our_address1	= $row7['u_address1'];		// 住所1
//		$our_address2	= $row7['u_address2'];		// 住所2
//		$our_address3	= $row7['u_address3'];		// 住所3
//		$our_tel	= $row7['u_tel'];		// 電話番号
//	} else {
//		$our_company 	= $row7['p_name'];		// 会社情報
//		$our_person 	= "";				// 担当者名
//		$our_post	= $row7['p_postal'];		// 住所郵便番号
//		$our_address1	= $row7['p_address1'];		// 住所1
//		$our_address2	= $row7['p_address2'];		// 住所2
//		$our_address3	= $row7['p_address3'];		// 住所3
//		$our_tel	= $row7['p_tel'];		// 電話番号
//	}

	$our_company 	= $in_companyName[$ix];		// 会社情報
	$our_person 	= "";				// 担当者名
	$our_post	= $in_postal[$ix];		// 住所郵便番号
	$our_address1	= $in_address1[$ix];		// 住所1
	$our_address2	= $in_address2[$ix];		// 住所2
	$our_address3	= $in_address3[$ix];		// 住所3
	$our_tel	= $in_tel[$ix];			// 電話番号

	/* 見積書情報 */
	$table_total		= "¥". $q_alltotal[$ix];	// 見積もり・請求合計
	$table_tax		= "¥". $q_cost[$ix];		// 消費税
	$table_name		= "¥". $qu_price[$ix];		// 工事・商品価格
	$table_fee		= "¥". $qu_commit[$ix];		// 割賦手数料
	$table_first		= $qu_initPayAmount[$ix];	// 初回お支払額
	$table_month		= "¥". $qu_amount_pay[$ix];	// 月々お支払額
	$table_split		= $qu_installments[$ix]. "回";	// 分割回数
	$table_deposit		= $qu_deposit[$ix];		// 頭金
	$table_repay_start	= $qu_startDate[$ix];		// 返済開始予定年月
	$table_repay_fin	= $qu_endDate[$ix];		// 返済終了予定年月
	$table_subtotal		= "¥". $syohkei[$ix];		// 小計

	$item_name	= array();	// 適用
	$item_num	= array();	// 数量
	$item_unit	= array();	// 単位
	$item_price	= array();	// 
	$item_amount	= array();	// 金額単価（税込）
	$item_amount10	= array();

	/* 見積もり項目 */
	for($iy=0; $iy<$q2_count[$ix]; $iy++) {
		$item_name[$iy]		= $q_name[$ix][$iy];	// 適用
		$item_num[$iy]		= $q_number[$ix][$iy];	// 数量
		$item_unit[$iy]		= $q_unit[$ix][$iy];	// 単位
		$item_price[$iy]	= $q_price[$ix][$iy];	// 
		$item_amount[$iy]	= $q_total[$ix][$iy];	// 金額単価（税込）
	}
	$item_amount10[0] = "¥". $q_total10[$ix][0];

	if ($iy >= 10) {
		$item_amount10[1] = "¥". $q_total10[$ix][1];
	}
	// if ($ix >= 30) {
	//	$item_amount10[2] = "¥". $q_total10[$ix][2];
	// }

	$leng = strlen($q_memo[$ix]);
	if ($leng != 0) {
		$str1 = $q_memo[$ix];
		$str2 = "";
		for($i=0; $i<4; $i++) {
			$str2 .= strstr($str1, "\n", TRUE);
			$str2 .= "\n";
			$len1 = strpos($str1, "\n");
			// $str1 = str_replace(array("\r\n", "\r", "\n"), '', $str1);
			$str1 = substr($str1, ($len1 + 1));
			$len2 = strlen($str1);
			if ($len2 == 0) {
				break;
			}
		}
		// file_put_contents($memo_file. "2", $str2);
		// file_put_contents($memo_file, $q_memo[$ix]);
		// unlink($memo_file);
	}
	// $item_remark = $q_memo[$ix];
	$item_remark = $str2;


	/* ---------------------------------------------------------------------

		コンテンツ

	--------------------------------------------------------------------- */

	if ($q2_count[$ix] <= 10) {
		// PDFが1枚の場合
		require(__DIR__ . "/single3.php");
	} else {
		$_SESSION['q2_count'] = $q2_count[$ix];
		// PDFが2枚の場合
		require(__DIR__ . "/multiple3.php");
	}

	// I: ブラウザに出力する(既定)、保存時のファイル名が$nameで指定した名前になる。
	// D: ブラウザで(強制的に)ダウンロードする。
	// F: ローカルファイルとして保存する。
	// S: PDFドキュメントの内容を文字列として出力する。

//	if ($da_count == 1) {
//		// PDFをアウトプット ダウンロード
//		$pdf->Output(sprintf("MyResume_%s.pdf", time()), 'D');
//	} else {
		// PDFをアウトプット 
		if ($_SESSION['da_pdfbox'][0][3] == 0) {
			$local_file = $filename. "_見積書_". ($ix + 1). ".pdf";
		} else {
			$local_file = $filename. "_請求書_". ($ix + 1). ".pdf";
		}
		$str = mb_convert_encoding($local_file, "SJIS", "UTF-8");
		// $local_file = $filename. "_". $ix. ".pdf";

		$pdf->Output($str, 'F');
//	}

	// 控え用PDF
	if ($q2_count[$ix] <= 10) {
		// PDFが1枚の場合
		require(__DIR__ . "/single3-2.php");
	} else {
		$_SESSION['q2_count'] = $q2_count[$ix];
		// PDFが2枚の場合
		require(__DIR__ . "/multiple3-2.php");
	}

	if ($_SESSION['da_pdfbox'][0][3] == 0) {
		$local_file = $filename. "_見積書控え_". ($ix + 1). ".pdf";
	} else {
		$local_file = $filename. "_請求書控え_". ($ix + 1). ".pdf";
	}
	$str = mb_convert_encoding($local_file, "SJIS", "UTF-8");
	// $local_file = $filename. "-2_". $ix. ".pdf";

	$pdf->Output($str, 'F');

	// unset($pdf);
	unset($item_name);
	unset($item_num);
	unset($item_unit);
	unset($item_price);
	unset($item_amount);
}

chdir($tmp_dir1);
$zip_name = $tmp_file. ".zip";
$cmd = "zip -r '". $zip_name. "' ./". $tmp_file. "/";
exec($cmd);

mb_http_output( "pass" );

// header('Content-Type: application/force-download');
// header('Content-Length: '.filesize($zipPath));
// header('Content-disposition: attachment; filename="'.$zip_name.'"');

header("Content-Type: application/zip");
header("Content-Transfer-Encoding: Binary");
// header("Content-Length: ".filesize($zipPath));
header('Content-Disposition: attachment; filename*=UTF-8\'\'' . $zip_name);
ob_end_clean();
readfile($tmp_dir1.$zip_name);

//サーバー内のzipを削除
unlink($tmp_dir1.$zip_name);
$cmd = "cd ". $tmp_dir1. ";rm -rf '". $tmp_file. "'";
// exec($cmd);

echo '<script>window.close();</script>';

// header("Location:/manage/data/");
// exit();
?>