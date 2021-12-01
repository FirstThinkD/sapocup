<?php
session_start();
require_once(__DIR__ . '/../../../common/dbconnect.php');
require_once(__DIR__ . '/../../common/functions.php');
require_once(__DIR__ . '/sim_func.php');

if (empty($_SESSION['loginID'])) {
	header("Location:/");
	exit();
}

if (empty($_GET['id']) && empty($_GET['qu_id'])) {
	header("Location:/");
	exit();
}

$pt_no = $_GET['id'];
$qu_id = $_GET['qu_id'];

/*-----------------------------------------------------------

		DBよりシミュレーション情報を取得

-------------------------------------------------------------*/
$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row3 = mysqli_fetch_assoc($record);

$sql = sprintf('SELECT * FROM `customer` WHERE c_id="%d"',
	mysqli_real_escape_string($db, $row3['c_id'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row4 = mysqli_fetch_assoc($record);

$sql = sprintf('SELECT * FROM `simulation` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row7 = mysqli_fetch_assoc($record);

// 3桁ごとにカンマを追加する
$pt1_amount_pay	= number_format((int) $row7['pt1_amount_pay']);	// 月々お支払額
$qu_price 	= number_format((int) $row7['qu_price']); 			// ご利用予定金額
$pt1_deposit 	= number_format((int) $row7['pt1_deposit']); 		// 頭金
$pt1_commission = number_format((int) $row7['pt1_commission']); // 割賦手数料率
$pt1_commit 	= number_format((int) $row7['pt1_commit']); 		// 割賦手数料総額
$pt1_alltotal 	= number_format((int) $row7['pt1_alltotal']); 	// 総支払額
$pt2_deposit 	= number_format((int) $row7['pt2_deposit']); 		// 頭金
$pt2_commission = number_format((int) $row7['pt2_commission']); // 割賦手数料率
$pt2_commit 	= number_format((int) $row7['pt2_commit']); 		// 割賦手数料総額
$pt2_alltotal 	= number_format((int) $row7['pt2_alltotal']); 	// 総支払額
$pt3_deposit 	= number_format((int) $row7['pt3_deposit']); 		// 頭金
$pt3_commission = number_format((int) $row7['pt3_commission']); // 割賦手数料率
$pt3_commit 	= number_format((int) $row7['pt3_commit']); 		// 割賦手数料総額
$pt3_alltotal 	= number_format((int) $row7['pt3_alltotal']); 	// 総支払額


// 商品価格
// if (ctype_digit($row7['qu_price'])) {
//	$qu_price = number_format($row7['qu_price']);
// } else {
//	$qu_price = $row7['qu_price'];
// }

$pt1_out = partition($row3['qu_paymentDate'], $row7['qu_startDate'], $row7['pt1_installments'], $row7['pt1_alltotal'], $row7['pt1_deposit'], $row7['pt1_initPayAmount']);
$pt2_out = partition($row3['qu_paymentDate'], $row7['qu_startDate'], $row7['pt2_installments'], $row7['pt2_alltotal'], $row7['pt2_deposit'], $row7['pt2_initPayAmount']);
$pt3_out = partition($row3['qu_paymentDate'], $row7['qu_startDate'], $row7['pt3_installments'], $row7['pt3_alltotal'], $row7['pt3_deposit'], $row7['pt3_initPayAmount']);

// 実行日
$qu_startDate = date("Y年m月d日", strtotime($row7['qu_startDate']));

// 割賦手数料総額
if (ctype_digit($row7['pt1_commit'])) {
	$pt1_commit = number_format($row7['pt1_commit']);
} else {
	$pt1_commit = $row7['pt1_commit'];
}
if (ctype_digit($row7['pt2_commit'])) {
	$pt2_commit = number_format($row7['pt2_commit']);
} else {
	$pt2_commit = $row7['pt2_commit'];
}
if (ctype_digit($row7['pt3_commit'])) {
	$pt3_commit = number_format($row7['pt3_commit']);
} else {
	$pt3_commit = $row7['pt3_commit'];
}

// 頭金
if (ctype_digit($row7['pt1_deposit'])) {
	$pt1_deposit = number_format($row7['pt1_deposit']);
} else {
	$pt1_deposit = $row7['pt1_deposit'];
}
if (ctype_digit($row7['pt2_deposit'])) {
	$pt2_deposit = number_format($row7['pt2_deposit']);
} else {
	$pt2_deposit = $row7['pt2_deposit'];
}
if (ctype_digit($row7['pt3_deposit'])) {
	$pt3_deposit = number_format($row7['pt3_deposit']);
} else {
	$pt3_deposit = $row7['pt3_deposit'];
}

// 事務管理手数料率
$pt1_commission = $row7['pt1_commission'];
$pt2_commission = $row7['pt2_commission'];
$pt3_commission = $row7['pt3_commission'];

// 初回お支払額
if (ctype_digit($row7['pt1_initPayAmount'])) {
	$pt1_initPayAmount = number_format($row7['pt1_initPayAmount']);
} else {
	$pt1_initPayAmount = $row7['pt1_initPayAmount'];
}
if (ctype_digit($row7['pt2_initPayAmount'])) {
	$pt2_initPayAmount = number_format($row7['pt2_initPayAmount']);
} else {
	$pt2_initPayAmount = $row7['pt2_initPayAmount'];
}
if (ctype_digit($row7['pt3_initPayAmount'])) {
	$pt3_initPayAmount = number_format($row7['pt3_initPayAmount']);
} else {
	$pt3_initPayAmount = $row7['pt3_initPayAmount'];
}

// 月々お支払額
if (ctype_digit($row7['pt1_amount_pay'])) {
	$pt1_amount_pay = number_format($row7['pt1_amount_pay']);
} else {
	$pt1_amount_pay = $row7['pt1_amount_pay'];
}
if (ctype_digit($row7['pt2_amount_pay'])) {
	$pt2_amount_pay = number_format($row7['pt2_amount_pay']);
} else {
	$pt2_amount_pay = $row7['pt2_amount_pay'];
}
if (ctype_digit($row7['pt3_amount_pay'])) {
	$pt3_amount_pay = number_format($row7['pt3_amount_pay']);
} else {
	$pt3_amount_pay = $row7['pt3_amount_pay'];
}

// 分割回数
$pt1_installments = $row7['pt1_installments'];
$pt2_installments = $row7['pt2_installments'];
$pt3_installments = $row7['pt3_installments'];

// 総支払額
if (ctype_digit($row7['pt1_alltotal'])) {
	$pt1_alltotal = number_format($row7['pt1_alltotal']);
} else {
	$pt1_alltotal = $row7['pt1_alltotal'];
}
if (ctype_digit($row7['pt2_alltotal'])) {
	$pt2_alltotal = number_format($row7['pt2_alltotal']);
} else {
	$pt2_alltotal = $row7['pt2_alltotal'];
}
if (ctype_digit($row7['pt3_alltotal'])) {
	$pt3_alltotal = number_format($row7['pt3_alltotal']);
} else {
	$pt3_alltotal = $row7['pt3_alltotal'];
}

if ($pt_no == 1) {
	$ptn_deposit      = $pt1_deposit;
	$ptn_commit       = $pt1_commit;
	$ptn_amount_pay   = $pt1_amount_pay;
	$ptn_installments = $pt1_installments;
	$ptn_commition       = $pt1_commission;
	$ptn_alltotal     = $pt1_alltotal;
	$ptn_count        = $pt1_out[0][5];
	for($i=0; $i<$ptn_count; $i++) {
		$ptn_day[$i]     = $pt1_out[$i][1];
		$ptn_price[$i]   = $pt1_out[$i][2];
		$ptn_month[$i]   = $pt1_out[$i][3];
		$ptn_payment[$i] = $pt1_out[$i][4];
	}
} else if ($pt_no == 2) {
	$ptn_deposit      = $pt2_deposit;
	$ptn_commit       = $pt2_commit;
	$ptn_amount_pay   = $pt2_amount_pay;
	$ptn_installments = $pt2_installments;
	$ptn_commition       = $pt2_commission;
	$ptn_alltotal     = $pt2_alltotal;
	$ptn_count        = $pt2_out[0][5];
	for($i=0; $i<$ptn_count; $i++) {
		$ptn_day[$i]     = $pt2_out[$i][1];
		$ptn_price[$i]   = $pt2_out[$i][2];
		$ptn_month[$i]   = $pt2_out[$i][3];
		$ptn_payment[$i] = $pt2_out[$i][4];
	}
} else {
	$ptn_deposit      = $pt3_deposit;
	$ptn_commit       = $pt3_commit;
	$ptn_amount_pay   = $pt3_amount_pay;
	$ptn_installments = $pt3_installments;
	$ptn_commition       = $pt3_commission;
	$ptn_alltotal     = $pt3_alltotal;
	$ptn_count        = $pt3_out[0][5];
	for($i=0; $i<$ptn_count; $i++) {
		$ptn_day[$i]     = $pt3_out[$i][1];
		$ptn_price[$i]   = $pt3_out[$i][2];
		$ptn_month[$i]   = $pt3_out[$i][3];
		$ptn_payment[$i] = $pt3_out[$i][4];
	}
}
if ($ptn_count > 20) {
	$ptn_count1 = 20;
} else {
	$ptn_count1 = $ptn_count;
}
/* ---------------------------------------------------------------------

	PDF機能

--------------------------------------------------------------------- */
require_once(__DIR__ . '/../../common/tcpdf/tcpdf.php');	// TCPDFライブラリ
require_once(__DIR__ . '/../../common/fpdi/src/autoload.php');	// FPDIライブラリ
use setasign\Fpdi\TcpdfFpdi;		// FPDIクラス読み込み
$pdf = new TcpdfFpdi();			// フォント設定
$pdf->SetMargins(18, 0);		// マージン設定（左右、上下）
$pdf->SetCellPadding(0, 0);		// パディング設定
$pdf->SetAutoPageBreak(false);
$pdf->setPrintHeader(false);		// ヘッダー削除
$pdf->setPrintFooter(false);		// フッター削除
$pdf->SetFillColor(255, 255, 255);	// 背景色

/* 変数まとめ

--------------------------------------------------------------------- */
// シミュレーション情報
$detail_start   = $qu_startDate;	// 返済予定開始日
$detail_payment = $qu_price;		// ご利用予定金額
$detail_deposit = $ptn_deposit;		// 頭金
$detail_percent = $ptn_commition;		// 割賦手数料率
$detail_month   = $ptn_amount_pay;	// 月々お支払額
$detail_count   = $ptn_installments;	// 分割回数
$detail_fee     = $ptn_commit;		// 割賦手数料総額
$detail_total   = $ptn_alltotal;	// 総支払額

// シミュレーションテーブル
$simulate_day     = $ptn_day;		// 返済日
$simulate_price   = $ptn_price;		// ご利用金額
$simulate_month   = $ptn_month;		// 月々お支払額
$simulate_payment = $ptn_payment;	// お支払い残高

/* 	1枚目　コンテンツ

--------------------------------------------------------------------- */


$pageCnt = 0;
$pageCnt = $pdf->setSourceFile(__DIR__ . '/../../common/pdf/simulation_top.pdf');
for( $i=1; $i <= $pageCnt; $i++ ){
	$pdf->addPage();
	$pdf->useTemplate($pdf->importPage($i));
}

// ==========================================
//
// シミュレーション詳細
//
// ==========================================

/*	タイトル
----------------------------------------- */

$pdf->setCellPaddings(0, 0, 0, 2.0);
$pdf->SetFont('kozminproregular', '', 8);
if ($row3['qu_bunrui'] == "工事") {
	$pdf->MultiCell( 0, 3, "工事発注者氏名", 0, 'L', 0, 1, 34, 27, true, false, false, true, 0, 'T');
	$pdf->MultiCell( 0, 3, "工事名称", 0, 'L', 0, 1, 34, "", true, false, false, true, 0, 'T');
} else {
	$pdf->MultiCell( 0, 3, "商品発注者氏名", 0, 'L', 0, 1, 34, 27, true, false, false, true, 0, 'T');
	$pdf->MultiCell( 0, 3, "商品名称", 0, 'L', 0, 1, 34, "", true, false, false, true, 0, 'T');
}
$pdf->MultiCell( 0, 3, $row4['c_name'], 0, 'L', 0, 1, 57, 27, true, false, false, true, 0, 'T');
$pdf->MultiCell( 0, 3, $row3['qu_name'], 0, 'L', 0, 1, 57, "", true, false, false, true, 0, 'T');

/*	1列目
----------------------------------------- */
$pdf->setCellPaddings(1.5, 2.2, 1.5, 2.0);
$pdf->SetFont('kozminproregular', '', 8);
$pdf->MultiCell( 31, 0, "返済予定開始日", 0, 'L', 0, 1, 34, 38.3, true, false, false, true, 0, 'T');
$pdf->MultiCell( 31, 0, "ご利用予定金額", 0, 'L', 0, 1, 34, "", true, false, false, true, 0, 'T');
$pdf->MultiCell( 31, 0, "頭金", 0, 'L', 0, 1, 34, "", true, false, false, true, 0, 'T');
$pdf->MultiCell( 31, 0, "割賦手数料率", 0, 'L', 0, 1, 34, "", true, false, false, true, 0, 'T');

/*	2列目
----------------------------------------- */
// $pdf->SetFont('kozminproregular', 'B', 8);
$pdf->MultiCell( 39.7, 0, $detail_start, 0, 'R', 0, 1, 65, 38.3, true, false, false, true, 0, 'T');
$pdf->MultiCell( 39.7, 0, $detail_payment, 0, 'R', 0, 1, 65, "", true, false, false, true, 0, 'T');
$pdf->MultiCell( 39.7, 0, $detail_deposit, 0, 'R', 0, 1, 65, "", true, false, false, true, 0, 'T');
$pdf->MultiCell( 39.7, 0, $detail_percent, 0, 'R', 0, 1, 65, "", true, false, false, true, 0, 'T');

/*	3列目
----------------------------------------- */
$pdf->SetFont('kozminproregular', '', 8);
$pdf->MultiCell( 31, 0, "月々お支払額", 0, 'L', 0, 1, 104.7, 38.3, true, false, false, true, 0, 'T');
$pdf->MultiCell( 31, 0, "分割回数", 0, 'L', 0, 1, 104.7, "", true, false, false, true, 0, 'T');
$pdf->MultiCell( 31, 0, "割賦手数料総額", 0, 'L', 0, 1, 104.7, "", true, false, false, true, 0, 'T');
$pdf->MultiCell( 31, 0, "総支払額", 0, 'L', 0, 1, 104.7, "", true, false, false, true, 0, 'T');

/*	4列目
----------------------------------------- */
// $pdf->SetFont('kozminproregular', 'B', 8);
$pdf->MultiCell( 39.7, 0, $detail_month, 0, 'R', 0, 1, 135.7, 38.3, true, false, false, true, 0, 'T');
$pdf->MultiCell( 39.7, 0, $detail_count, 0, 'R', 0, 1, 135.7, "", true, false, false, true, 0, 'T');
$pdf->MultiCell( 39.7, 0, $detail_fee, 0, 'R', 0, 1, 135.7, "", true, false, false, true, 0, 'T');
$pdf->MultiCell( 39.7, 0, $detail_total, 0, 'R', 0, 1, 135.7, "", true, false, false, true, 0, 'T');


// ==========================================
//
// シミュレーションテーブル
//
// ==========================================
$pdf->SetCellPadding(0);
$pdf->SetCellPaddings(0);
$pdf->SetFont('kozminproregular', '', 8);
$pdf->MultiCell( 188, 0, "（単位：円）", 0, "R", false, 1, 0, 84.7, true, 0, false, true, 0, 'M', false );

/*	回数
----------------------------------------- */
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->MultiCell( 13, 7.7, "回数", 0, 'C', 1, 1, 21, 88.7, true, false, false, true, 0, 0, 'B');
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('kozminproregular', '', 8);
for ($i = 1; $i < 21; $i++ ) {
	$pdf->MultiCell( 13, 7.7, $i, 0, 'C', 0, 1, 21, "", true, false, false, true, 0, 0, 'B');
}

/*	返済日
----------------------------------------- */
$pdf->SetCellPaddings(2, 0, 2, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->MultiCell( 35.3, 7.7, "返済日", 0, 'C', 1, 1, 34, 88.7, true, false, false, true, 0, 0, 'B');
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('kozminproregular', '', 8);
for ($i = 0; $i < $ptn_count1; $i++ ) {
	$pdf->MultiCell( 35.3, 7.7, $simulate_day[$i], 0, 'L', 0, 1, 34, "", true, false, false, true, 0, 0, 'B');
}

/*	ご利用金額
----------------------------------------- */
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->MultiCell( 44, 7.7, "ご利用金額", 0, 'C', 1, 1, 69.3, 88.7, true, false, false, true, 0, 0, 'B');
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('kozminproregular', '', 8);
for ($i = 0; $i < $ptn_count1; $i++ ) {
	$pdf->MultiCell( 44, 7.7, $simulate_price[$i], 0, 'R', 0, 1, 69.3, "", true, false, false, true, 0, 0, 'B');
}

/*	ご月々お支払額 $simulate_month
----------------------------------------- */
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->MultiCell( 31, 7.7, "月々お支払額", 0, 'C', 1, 1, 113.3, 88.7, true, false, false, true, 0, 0, 'B');
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('kozminproregular', '', 8);
for ($i = 0; $i < $ptn_count1; $i++ ) {
	$pdf->MultiCell( 31, 7.7, $simulate_month[$i], 0, 'R', 0, 1, 113.3, "", true, false, false, true, 0, 0, 'B');
}


/*	お支払後残高 $simulate_day
----------------------------------------- */
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->MultiCell( 44.3, 7.7, "お支払い残高", 0, 'C', 1, 1, 144.3, 88.7, true, false, false, true, 0, 0, 'B');
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('kozminproregular', '', 8);
for ($i = 0; $i < $ptn_count1; $i++ ) {
	$pdf->MultiCell( 44.3, 7.7, $simulate_payment[$i], 0, 'R', 0, 1, 144.3, "", true, false, false, true, 0, 0, 'B');
}


/*	2枚目　コンテンツ

--------------------------------------------------------------------- */

if ($ptn_count > 20) {
	$pageCnt = $pdf->setSourceFile(__DIR__ . '/../../common/pdf/simulation_last.pdf');
	for( $i=1 ; $i<=$pageCnt ; $i++ ){
		$pdf->addPage();
		$pdf->useTemplate($pdf->importPage($i));
	}

	// ==========================================
	//
	// シミュレーションテーブル
	//
	// ==========================================
	$pdf->SetFont('kozminproregular', '', 8);
	$pdf->MultiCell( 188, 0, "（単位：円）", 0, "R", false, 1, 0, 30.7, true, 0, false, true, 0, 'M', false );

	/*	回数
	----------------------------------------- */
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('kozminproregular', 'B', 8);
	$pdf->MultiCell( 13, 7.7, "回数", 0, 'C', 1, 1, 21, 34.7, true, false, false, true, 0, 0, 'B');
	$pdf->SetTextColor(0, 0, 0);
	for ($i=21; $i<49; $i++) {
		$pdf->MultiCell( 13, 7.7, $i, 0, 'C', 0, 1, 21, "", true, false, false, true, 0, 0, 'B');
	}

	/*	返済日
	----------------------------------------- */
	$pdf->SetCellPaddings(2, 0, 2, 0);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('kozminproregular', 'B', 8);
	$pdf->MultiCell( 35.3, 7.7, "返済日", 0, 'C', 1, 1, 34, 34.7, true, false, false, true, 0, 0, 'B');
	$pdf->SetTextColor(0, 0, 0);
	for ($i=20; $i<48; $i++ ) {
		if(isset($simulate_day[$i])) {
			$pdf->MultiCell( 35.3, 7.7, $simulate_day[$i], 0, 'L', 0, 1, 34, "", true, false, false, true, 0, 0, 'B');
		}
	}

	/*	ご利用金額
	----------------------------------------- */
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('kozminproregular', 'B', 8);
	$pdf->MultiCell( 44, 7.7, "ご利用金額", 0, 'C', 1, 1, 69.3, 34.7, true, false, false, true, 0, 0, 'B');
	$pdf->SetTextColor(0, 0, 0);
	for ($i=20; $i<48; $i++) {
		$pdf->MultiCell( 44, 7.7, $simulate_price[$i], 0, 'R', 0, 1, 69.3, "", true, false, false, true, 0, 0, 'B');
	}


	/*	ご月々お支払額 $simulate_month
	----------------------------------------- */
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('kozminproregular', 'B', 8);
	$pdf->MultiCell( 31, 7.7, "返済日", 0, 'C', 1, 1, 113.3, 34.7, true, false, false, true, 0, 0, 'B');
	$pdf->SetTextColor(0, 0, 0);
	for ($i=20; $i<48; $i++) {
		if(isset($simulate_month[$i])) {
			$pdf->MultiCell( 31, 7.7, $simulate_month[$i], 0, 'R', 0, 1, 113.3, "", true, false, false, true, 0, 0, 'B');
		}
	}


	/*	お支払後残高 $simulate_day
	----------------------------------------- */
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('kozminproregular', 'B', 8);
	$pdf->MultiCell( 44.3, 7.7, "返済日", 0, 'C', 1, 1, 144.3, 34.7, true, false, false, true, 0, 0, 'B');
	$pdf->SetTextColor(0, 0, 0);
	for ($i=20; $i<48; $i++) {
		if(isset($simulate_month[$i])) {
			$pdf->MultiCell( 44.3, 7.7, $simulate_payment[$i], 0, 'R', 0, 1, 144.3, "", true, false, false, true, 0, 0, 'B');
		}
	}
}

// I: ブラウザに出力する(既定)、保存時のファイル名が$nameで指定した名前になる。
// D: ブラウザで(強制的に)ダウンロードする。
// F: ローカルファイルとして保存する。
// S: PDFドキュメントの内容を文字列として出力する。

// PDFをアウトプット
$fileName =  date('YmdHis'). '.pdf';
$filePath = __DIR__ . '/pdf_tmp/' . $fileName;
$pdf -> Output($filePath, 'F');

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $fileName . '"');
// header('Content-Length: ' . filesize($pdf_file));
readfile($filePath);
unlink($filePath);
?>