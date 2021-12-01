<?php
ini_set("display_errors", 1);
/* ---------------------------------------------------------------------



	PDF設定



--------------------------------------------------------------------- */
require_once(__DIR__ . '/tcpdf/tcpdf.php'); // TCPDFライブラリ
require_once(__DIR__ . '/fpdi/src/autoload.php'); // FPDIライブラリ
use setasign\Fpdi\TcpdfFpdi; // FPDIクラス読み込み
$pdf = new TcpdfFpdi(); // フォント設定
$pdf->SetMargins(18, 0); // マージン設定（左右、上下）
$pdf->SetCellPadding(0, 0); // パディング設定
$pdf->SetAutoPageBreak(false);
$pdf->setPrintHeader(false); // ヘッダー削除
$pdf->setPrintFooter(false); // フッター削除
$pdf->SetFillColor(255, 255, 255); // 背景色

$pageCnt = 0;
$pageCnt = $pdf->setSourceFile(__DIR__ . '/pdf/multiple_top.pdf');
for( $i=1 ; $i<=$pageCnt ; $i++ ){
	$pdf->addPage();
	$pdf->useTemplate($pdf->importPage($i));
}

/* ---------------------------------------------------------------------



	1枚目　コンテンツ
	Cell( セル幅,　セル高さ, 表示する文字列, 0:境界線なし 1:境界線あり, テキストの行数,  テキストの位置（L、T、R、B） )



--------------------------------------------------------------------- */

// No.
$pdf->SetFont('kozminproregular', '', 7);
$pdf->SetXY(158.0, 23.0);
$pdf->Cell(29, 0, "No.\t{$contract_num}" , 0, 0, 'L');


// タイトル
$pdf->SetXY(17.0, 28.0);
$pdf->SetFont('kozminproregular', 'B', 11);
$pdf->Cell(0, 4, "請求書" , 0, 0, 'C');

// 日付
$pdf->SetXY(158.0, 34.0);
$pdf->SetFont('kozminproregular', '', 7);
$pdf->Cell(0, 0, $contract_date , 0, 0, 'L');


// 請求先
$pdf->SetXY(17.0, 37.0);
$pdf->SetFont('kozminproregular', 'B', 11);
$pdf->Cell(60, 4, $contract_company , 0, 0, 'L');

$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->Cell(0, 4, "様" , 0, 0, 'L');

$pdf->SetXY(17.0, 43.0);
$pdf->SetFont('kozminproregular', '', 8);
$pdf->Cell(60, 4, "下記の通りお見積もり申し上げ、手配いたします。" , 0, 0, 'L');

// 請求情報
$pdf->SetXY(17.0, 50.0);
$pdf->Cell(22, 4, "工事名称" , 0, 0, 'L');
$pdf->Cell(0, 4, $contract_name , 0, 0, 'L');

$pdf->SetXY(17.0, 60.0);
$pdf->Cell(22, 4, "工事場所" , 0, 0, 'L');
$pdf->Cell(0, 4, $contract_place , 0, 0, 'L');

$pdf->MultiCell( 0, 4, "分割払い", 0, 'J', 0, 1, 17, 70, true, false, false, true, 5, 'T');
$pdf->MultiCell( 0, 4, "支払日", 0, 'J', 0, 1, 17, "", true, false, false, true, 5, 'T');
$pdf->MultiCell( 0, 4, "受渡期日", 0, 'J', 0, 1, 17, "", true, false, false, true, 5, 'T');
$pdf->MultiCell( 0, 4, "見積有効期限", 0, 'J', 0, 1, 17, "", true, false, false, true, 5, 'T');

$pdf->MultiCell( 0, 4, "支払条件", 0, 'J', 0, 1, 39, 70, true, false, false, true, 5, 'T');
$pdf->MultiCell( 0, 4, $contract_pay, 0, 'J', 0, 1, 39, "", true, false, false, true, 5, 'T');
$pdf->MultiCell( 0, 4, $contract_deliver, 0, 'J', 0, 1, 39, "", true, false, false, true, 5, 'T');
$pdf->MultiCell( 0, 4, "発行日より10日間", 0, 'J', 0, 1, 39, "", true, false, false, true, 5, 'T');

// 会社情報
$pdf->SetFont('kozminproregular', 'B', 9);
$pdf->MultiCell( 0, 5, $our_company, 0, 'J', 0, 1, 129, 42, true, false, false, true, 5, 'T');
$pdf->SetFont('kozminproregular', '', 8);
$pdf->MultiCell( 0, 4, $our_person, 0, 'J', 0, 1, 133, "", true, false, false, true, 5, 'T');

$pdf->MultiCell( 0, 4, "〒" . "\t" . $our_post, 0, 'J', 0, 1, 129, 55, true, false, false, true, 5, 'T');
$pdf->MultiCell( 0, 4, $our_address1, 0, 'J', 0, 1, 129, "", true, false, false, true, 5, 'T');
if ($our_address2) {
	 $pdf->MultiCell( 0, 4, $our_address2, 0, 'J', 0, 1, 129, "", true, false, false, true, 5, 'T');
}
if ($our_address3) {
	$pdf->MultiCell( 0, 4, $our_address3, 0, 'J', 0, 1, 129, "", true, false, false, true, 5, 'T');
}
$pdf->MultiCell( 0, 4, "TEL" . "\t" . $our_tel, 0, 'J', 0, 1, 129, "", true, false, false, true, 5, 'T');

// 見積もり金額合計
$pdf->SetXY(35.0, 90.0);
$pdf->SetFont('kozminproregular', 'B', 9);
$pdf->Cell(22, 13, "見積金額合計" , 0, 0, '', '', '', '', '', '', 'B');
$pdf->SetFont('kozminproregular', 'B', 20);
$pdf->Cell(70, 13, $table_total , 0, 0, 'R', '', '', '', '', '', 'B');
$pdf->SetFont('kozminproregular', '', 7);
$pdf->Cell(16, 13, "" , 0, 0, '', '', '', '', '', '', '');
$pdf->Cell(12, 13, "(内消費税等/10%)" , 0, 0, 'R', '', '', '', '', '', 'B');
$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->Cell(20, 13, $table_tax , 0, 0, 'R', '', '', '', '', '', 'B');

$pdf->SetXY(35.0, 104.0);
$pdf->SetFont('kozminproregular', '', 7);
$pdf->Cell(0, 4, "《分割払い内容》" , 0, 0);



// 見積もり詳細
$pdf->SetCellPadding(2); // パディング設定
$pdf->SetXY(34.0, 108.0);
$pdf->SetFont('kozminproregular', '', 8);
$pdf->Cell(31, 7.8, "商品価格" , 0, 0, 'C');
$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->Cell(40, 7.8, $table_name , 0, 0, 'R');
$pdf->SetFont('kozminproregular', '', 8);
$pdf->Cell(31, 7.8, "割賦手数料" , 0, 0, 'C');
$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->Cell(40, 7.8, $table_fee , 0, 0, 'R');

$pdf->SetFont('kozminproregular', '', 8);
$pdf->MultiCell( 31, 7.8, "初回お支払額", 0, 'C', 0, 1, 34, 119, true, false, false, true, 5, 'T');
$pdf->MultiCell( 31, 7.8, "分割回数", 0, 'C', 0, 1, 34, "", true, false, false, true, 5, 'T');
$pdf->MultiCell( 31, 7.8, "返済開始予定年月", 0, 'C', 0, 1, 34, "", true, false, false, true, 5, 'T');

$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->MultiCell( 40, 7.8, $table_first, 0, 'R', 0, 1, 65, 119, true, false, false, true, 5, 'T');
$pdf->MultiCell( 40, 7.8, $table_split, 0, 'C', 0, 1, 65, "", true, false, false, true, 5, 'T');
$pdf->MultiCell( 40, 7.8, $table_repay_start, 0, 'C', 0, 1, 65, "", true, false, false, true, 5, 'T');

$pdf->SetFont('kozminproregular', '', 8);
$pdf->MultiCell( 31, 7.8, "月々お支払額", 0, 'C', 0, 1, 105, 119, true, false, false, true, 5, 'T');
$pdf->MultiCell( 31, 7.8, "頭金", 0, 'C', 0, 1, 105, "", true, false, false, true, 5, 'T');
$pdf->MultiCell( 31, 7.8, "返済終了予定年月", 0, 'C', 0, 1, 105, "", true, false, false, true, 5, 'T');

$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->MultiCell( 40, 7.8, $table_month, 0, 'R', 0, 1, 136, 119, true, false, false, true, 5, 'T');
$pdf->MultiCell( 40, 7.8, $table_deposit, 0, 'R', 0, 1, 136, "", true, false, false, true, 5, 'T');
$pdf->MultiCell( 40, 7.8, $table_repay_fin, 0, 'C', 0, 1, 136, "", true, false, false, true, 5, 'T');


// ==========================================
//
// 見積もり項目
//
// ==========================================


/*	No.
------------------------------ */
$pdf->SetFont('kozminproregular', '', 7);
$pdf->SetCellPadding(0.1);
$pdf->MultiCell( 9.2, 0, "No.", 0, 'C', 1, 1, 34, 146, true, false, false, true, 5, 'M');

$pdf->SetCellPaddings(0.3, 3, 0.3, 1); // パディング設定
$pdf->SetFont('kozminproregular', '', 8);
for ($i = 1; $i <= 10; $i++) {
	$pdf->MultiCell( 9.2, 7.7, $i, 0, 'C', 0, 1, 34, "", true, false, false, true, 5, 'C');
}


/*	適用
------------------------------ */
$pdf->SetFont('kozminproregular', '', 7);
$pdf->SetCellPadding(0.3);
$pdf->MultiCell( 61.4, 0, "適用", 0, 'C', 1, 1, 43.2, 146, true, false, false, true, 5, 'M');

$pdf->SetCellPadding(0.3); 
$pdf->SetFont('kozminproregular', '', 8);
for ($i = 0; $i < 10; $i++ ) {
	if(isset($item_name[$i])) {
		if (mb_strwidth($item_name[$i], "UTF-8") > 112) {
			// 縮小化
			$pdf->SetCellPaddings(0.3, 1.3, 0.3, 1.3);
			$pdf->SetFont('kozminproregular', '', 5);
			// 出力
			$pdf->MultiCell( 61.4, 7.7, mb_strimwidth($item_name[$i], 0, 112, "", 'UTF-8'), 0, 'L', 0, 2, 43.2, "", true);
			// 初期化
			$pdf->SetCellPadding(0.3); 
			$pdf->SetFont('kozminproregular', '', 8);
		} elseif (mb_strwidth($item_name[$i], "UTF-8") > 93) {
			// 縮小化
			$pdf->SetCellPaddings(0.3, 1.3, 0.3, 1.3);
			$pdf->SetFont('kozminproregular', '', 5);
			// 出力
			$pdf->MultiCell( 61.4, 7.7, $item_name[$i], 0, 'L', 0, 2, 43.2, "", true);
			// 初期化
			$pdf->SetCellPadding(0.3); 
			$pdf->SetFont('kozminproregular', '', 8);
		} elseif (mb_strwidth($item_name[$i], "UTF-8") > 80) {
			// 縮小化
			$pdf->SetCellPaddings(0.3, 1, 0.3, 1);
			$pdf->SetFont('kozminproregular', '', 6);
			// 出力
			$pdf->MultiCell( 61.4, 7.7, $item_name[$i], 0, 'L', 0, 2, 43.2, "", true);
			// 初期化
			$pdf->SetCellPadding(0.3); // パディング設定
			$pdf->SetFont('kozminproregular', '', 8);
		} elseif (mb_strwidth($item_name[$i], "UTF-8") > 70) {
			// 縮小化
			$pdf->SetCellPaddings(0.3, 0.7, 0.3, 0.7);
			$pdf->SetFont('kozminproregular', '', 7);
			// 出力
			$pdf->MultiCell( 61.4, 7.7, $item_name[$i], 0, 'L', 0, 2, 43.2, "", true);
			// 初期化
			$pdf->SetCellPadding(0.3); // パディング設定
			$pdf->SetFont('kozminproregular', '', 8);
		} else {
			$pdf->MultiCell( 61.4, 7.7, $item_name[$i], 0, 'L', 0, 2, 43.2, "", true);
		}
	}
}


/*	数量
--------------------- */
$pdf->SetFont('kozminproregular', '', 7);
$pdf->SetCellPadding(0.1); // パディング設定
$pdf->MultiCell( 13.2, 0, "数量", 0, 'C', 1, 1, 104.6, 146, true, false, false, true, 5, 'M');
$pdf->SetCellPaddings(0.3, 3, 0.3, 1); // パディング設定
for ($i = 0; $i < 10; $i++ ) {
	if(isset($item_num[$i])) {
		$pdf->MultiCell( 13.2, 7.7, $item_num[$i], 0, 'C', 0, 1, 104.6, "", 'B');
	}
	
}

/*	単位
--------------------- */
$pdf->SetCellPadding(0.1); // パディング設定
$pdf->MultiCell( 13.2, 0, "単位", 0, 'C', 1, 1, 117.8, 146, true, false, false, true, 5, 'M');
$pdf->SetCellPaddings(0.3, 3, 0.3, 1); // パディング設定
for ($i = 0; $i < 10; $i++ ) {
	if(isset($item_unit[$i])) {
		$pdf->MultiCell( 13.2, 7.7, $item_unit[$i], 0, 'C', 0, 1, 117.8, "", true, false, false, true, 5, 'B');
	}
}

/*	単価（税込）
--------------------- */
$pdf->SetCellPadding(0.1); // パディング設定
$pdf->MultiCell( 17.6, 0, "単価(税込)", 0, 'C', 1, 1, 131, 146, true, false, false, true, 5, 'M');
$pdf->SetCellPaddings(0.5, 3, 0.5, 1); // パディング設定
for ($i = 0; $i < 10; $i++ ) {
	if(isset($item_price[$i])) {
		$pdf->MultiCell( 17.6, 7.7, $item_price[$i], 0, 'R', 0, 1, 131, "", true, false, false, true, 5, 'B');
	}
}

/*	金額
--------------------- */
$pdf->SetCellPadding(0.1); // パディング設定
$pdf->MultiCell( 26.4, 0, "金額", 0, 'C', 1, 1, 148.6, 146, true, false, false, true, 5, 'M');
$pdf->SetCellPaddings(0.5, 3, 0.5, 1); // パディング設定
for ($i = 0; $i < 10; $i++ ) {
	if(isset($item_amount[$i])) {
		$pdf->MultiCell( 26.4, 7.7, $item_amount[$i], 0, 'R', 0, 1, 148.6, "", true, false, false, true, 5, 'B');
	}
}

/*	小計
--------------------- */
$pdf->SetXY(105.0, 228.0);
$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->Cell(44, 0, "小計" , 0, 0, 'C');
$pdf->Cell(26.4, 00, $item_amount10[0] , 0, 0, 'C');

/* ---------------------------------------------------------------------



	2枚目　規約



--------------------------------------------------------------------- */

// 2枚目
$pdf->addPage();
$pdf->SetTopMargin(12); // マージントップ
$pdf->SetAutoPageBreak(false);
$pdf->SetCellPadding(0); // パディング設定
$pdf->setPrintHeader(false); // ヘッダー削除
$pdf->setPrintFooter(false); // フッター削除
$pdf->SetFillColor(255, 255, 255); // 背景色
$pdf->SetFont('kozminproregular', '', 7);
$html = <<< EOF

<style>
	div.h1 {
		font-weight: bold;
	}
	table,
	table tr {
		width: 100%;
	}
	table.wrap {
		width: 100%;
		line-height: 1.5;
	}
	table.inner {
		width: 100%;
	}
	table.inner tr {
		width: 100%;
	}
	table.inner th.title {
		font-weight: bold;
		line-height: 2;
	}
	table.inner tr.listNum th {
		width: 6%;
		text-align: right;
	}
	table.inner tr.listNum td {
		width: 94%;
	}
	table.inner tr.listNumUnder th {
		width: 8%;
		text-align: right;
	}
	table.inner tr.listNumUnder td {
		width: 92%;
	}
	div.red {
		color: red;
		border: 1px solid red;
	}
	div.red table.inner th.space {
		width: 1%;
	}
	div.red table.inner td {
		width: 99%;
	}
	div.red table.inner tr.dot th.listDot {
		width: 5%;
	}
	div.red table.inner tr.dot td {
		width: 94%;
	}
</style>
<div class="h1">割賦販売契約約款</div>
<table class="wrap">
	<tr>
		<td style="width: 45%;">
			<table class="inner">
				<tr><th colspan="2" class="title">第１条（約款の適用および契約内容）</th></tr>
				<tr>
					<td colspan="2">{$our_company}（以下「甲」といいます）と御請求書に記載の発注者（以下「乙」といいます）は、甲の工事または商品（以下「本商品」といいます）の発注に関し、以下の割賦販売契約約款（以下「本約款」といいます）に従うものとし、本商品の割賦販売に関する「割賦販売契約」（以下「本契約」といいます）を締結します。</td>
				</tr>
				<tr><th colspan="2" class="title">第２条（本契約の申込方法および承諾等）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、本契約の申込み（以下「本申込」という）をするときは本約款には、本約款に同意のうえ、甲所定の申込手続を行うものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>前項の場合において、乙は、甲が申込内容を確認するための書類が必要と判断する場合、当該書類を提出するものとします。</td>
				</tr>
				<tr class="listNum">
					<th>３.</th>
					<td>甲は、次の場合には本契約の申込みを承諾しないことがあります。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(1)</th>
					<td>乙が甲との間で締結している本契約に基づく各月の支払い総額が甲が定める基準を満たすことができないおそれがあるとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(2)</th>
					<td>甲の業務遂行上支障があるとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(3)</th>
					<td>その他甲が不適当と判断したとき。</td>
				</tr>
				<tr><th colspan="2" class="title">第３条（契約の成立時点）</th></tr>
				<tr>
					<td colspan="2">本契約は、甲が乙からの申込みを所定の手続きをもって承諾し、所定の方法で承諾の通知を受けた時をもって成立するものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第４条（分割払金の支払期日および支払方法）</th></tr>
				<tr>
					<td colspan="2">乙は、甲が契約後に交付または送付（電子メールによる送信を含みます）する書面（電磁的に交付するものを含む。以下「契約完了書面」といいます）に記載の分割払金を、契約完了書面に記載の支払期日から、支払うものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第５条（入金案内）</th></tr>
				<tr>
					<td colspan="2">利用者は、本オプション機能を利用する前に、顧客に対し、分割金の未払いが起こった場合には弊社より「SMS」「自動音声」「オペレーター」案内のいずれかより連絡がいく場合がある旨を十分に説明の上で同意させるものとし、弊社は当該同意がなされているものとみなすことができるものとします。万一、顧客から弊社に対し、個人情報の流用の観点で指摘があった場合であっても、弊社は利用者から顧客に対して事前に説明を行い同意を得ているものとみなし、その責任はすべて利用者が負うものとし、弊社は一切責任を負わないものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第６条（商品引渡し及び所有権の移転）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>甲は、本契約が成立した後、所定の時期に本商品を購入者に引渡すものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>本商品の所有権は、本商品の支払いが完了した際に、乙へ所有権移転するものとします。</td>
				</tr>
				<tr class="listNum">
					<th>３.</th>
					<td>乙は、本商品の所有権移転前においては、本商品を担保に供し、譲渡し、するまたは転売することができないものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第７条（届出事項の変更）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、甲に届け出た氏名もしくは名称、住所または連絡先等を変更した場合は、速やかに甲所定の方法により甲に届け出るものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>乙は、前項の届出がないために、甲等からの通知または送付書類等が延着または不到達となった場合には、通常到達すべき時に到達したものと甲等がみなすことに異議のないものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第８条（契約上の地位の譲渡）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、本契約に係る契約上の地位を譲渡することができないものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>前項の定めは、相続または法人の合併、分割等により本契約に係る契約上の地位が承継される場合には適用しないものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第９条（期限の利益喪失）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、次のいずれかの事由に該当したときは、当然に本契約に基づく債務について期限の利益を失い、直ちに債務の全てを履行するものとします。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(1)</th>
					<td>分割払金の支払を遅滞し、その支払を書面で20日以上の相当の期間を定めて催告されたにもかかわらず、その期間内に支払わなかったとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(2)</th>
					<td>自ら振り出した手形、小切手が不渡りになったとき、または一般の支払を停止したとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(3)</th>
					<td>差押、仮差押、保全差押、仮処分の申し立てまたは滞納処分を受けたとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(4)</th>
					<td>破産、民事再生、特別清算、会社更生その他裁判上の倒産処理手続の申し立てを受けたとき、または自らこれらの申し立てをしたとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(5)</th>
					<td>その他乙の信用状態が著しく悪化したとき。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>甲は、乙が前項のいずれかに該当する場合は、本契約を解除することができるものとします。</td>
				</tr>
			</table>
		</td>
		<td style="width: 10%;"></td>
		<td style="width: 45%;">
			<table class="inner">
				<tr><th colspan="2" class="title">第１０条（損害遅延金）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、分割代金を遅滞したときは、支払期日の翌日から支払日にいたるまで、当該分割払金に対し年6.00％を上限とした額の遅延損害金を支払うものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>乙は、本契約に基づく債務について期限の利益を喪失したときは、期限の利益喪失の日から完済の日に至るまで、本商品の割賦販売価格から既に支払われた分割代金の合計額を控除した残金に対し、年6.00％を上限とした額の遅延損害金を支払うものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１１条（手数料の負担等）</th></tr>
				<tr>
					<td colspan="2">乙は、分割代金の支払に関する手数料を負担するものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１２条（早期一括返済）</th></tr>
				<tr>
					<td colspan="2">乙は、甲等に申し出ることにより、分割払金の残額を一括して支払うことができるものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１３条（反社会的勢力との関係の遮断）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は本契約締結日において、暴力団、暴力団員、暴力団員でなくなった時から５年を経過しない者、暴力団準構成員、暴力団関係企業、総会屋等、社会運動等標ぼうゴロ又は特殊知能暴力集団等、これらの共生者、その他これらに準ずる者、テロリスト（疑いがある場合も含む）に該当しないことを表明し、かつ将来にわたっても該当しないことを確約します。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>乙が第１項各号に該当した場合、またはは第１項の規定に基づく確約に関して虚偽の申告をしたことが判明した場合、甲は直ちに本契約を解除することができ、且つ、甲に生じた損害の賠償を請求できるものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１４条（割賦債権の譲渡）</th></tr>
				<tr>
					<td colspan="2">甲は、乙に対する本契約に基づく債権を第三者に譲渡することがあります。この場合において、ご契約者様は、当該債権の譲渡及び甲が購入者の個人情報を譲渡先に提供することをあらかじめ同意するものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１５条（個人情報の取扱い）</th></tr>
				<tr>
					<td colspan="2">甲は乙に関する個人情報の取扱いに関するプライバシーポリシーを定め、これを甲のホームページ等において公表するか、甲の求めに応じて遅滞なく回答します。</td>
				</tr>
				<tr><th colspan="2" class="title">第１６条（合意管轄裁判所）</th></tr>
				<tr>
					<td colspan="2">乙は、本商品、本約款及び本契約について紛争が生じた場合、訴額の如何にかかわらず、甲の本店所在地を管轄する地方裁判所を第一審の専属的合意管轄裁判所とすることに同意するものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１７条（補則）</th></tr>
				<tr>
					<td colspan="2">この約款に定めなき事項が生じた場合、甲と乙は契約の趣旨に従い、誠意を持って協議・解決に努めるものとします。<br><br></td>
				</tr>
			</table>
			<div class="red">
				<table class="inner">
					<tr><td colspan="3"></td></tr>
					<tr>
						<th class="space"></th>
						<th colspan="2" class="title">クーリングオフについて（説明書）</th>
					</tr>
					<tr>
						<th class="space"></th>
						<td colspan="2">ご契約いただきます工事または商品等の販売につきましては、この割賦販売契約約款及びクーリングオフについての説明書の内容を十分にご確認ください。この割賦販売契約に「特定商品取引に関する法律」が適用される場合、お客様はこの説明書面受領日から起算して８日以内は、書面をもって契約の解除（クーリングオフと呼びます）をすることができ、その効力はお客様が解除する旨の書面を甲に対して発したときに生じるものとします。</td>
					</tr>
					<tr>
						<th class="space"></th>
						<td colspan="2">ただし、次のような場合等にはクーリングオフの権利行使はできません。</td>
					</tr>
					<tr class="dot">
						<th class="space"></th>
						<th class="listDot">・</th>
						<td>お客様が工事や商品等を営業用に利用する場合</td>
					</tr>
					<tr class="dot">
						<th class="space"></th>
						<th class="listDot">・</th>
						<td>甲の営業所等で契約の申込みまたは契約締結がなされた場合</td>
					</tr>
					<tr class="dot">
						<th class="space"></th>
						<th class="listDot">・</th>
						<td>お客様からのご請求によりご自宅での契約の申込みまたは契約締結がなされた場合</td>
					</tr>
					<tr>
						<th class="space"></th>
						<td colspan="2">上記期間内にクーリングオフがあった場合、甲は契約の解除に伴う損害賠償または違約金支払を請求することはありません。万一、クーリングオフがあった場合に、既に商品等の引渡しが行われている時は、その引取りに要する費用は甲の負担とします。また、契約解除のお申し出の際に既に受領した金員がある場合、すみやかにその金額を無利息にて返還いたします。</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
EOF;

$pdf->writeHTML($html);


$file = "./test.log";
$data = "q2_count=". $_SESSION['q2_count'];
$data .= "\n";
file_put_contents($file, $data, FILE_APPEND);

if ($_SESSION['q2_count'] > 30) {

/* ---------------------------------------------------------------------



	3枚目　続き



--------------------------------------------------------------------- */

$pageCnt = 0;
$pageCnt = $pdf->setSourceFile(__DIR__ . '/pdf/multiple_continue.pdf');
for( $i=1 ; $i<=$pageCnt ; $i++ ){
	$pdf->addPage();
	$pdf->useTemplate($pdf->importPage($i));
}

// No.
$pdf->SetFont('kozminproregular', '', 7);
$pdf->SetXY(158.0, 23.0);
$pdf->Cell(29, 0, "No.\t{$contract_num}" , 0, 0, 'L');

// 日付
$pdf->SetXY(158.0, 34.0);
$pdf->Cell(0, 0, $contract_date , 0, 0, 'L');



// ==========================================
//
// 見積もり項目
//
// ==========================================


/*	No.
------------------------------ */
$pdf->SetFont('kozminproregular', '', 7);
$pdf->SetCellPadding(0.1);
$pdf->MultiCell( 9.2, 0, "No.", 0, 'C', 1, 1, 34, 65, true, false, false, true, 5, 'M');

$pdf->SetCellPadding(2);
$pdf->SetFont('kozminproregular', '', 8);
for ($i = 11; $i <= 30; $i++) {
	$pdf->MultiCell( 9.2, 7.7, $i, 0, 'C', 0, 1, 34, "", true, false, false, true, 5, 'C');
}


/*	適用
------------------------------ */
$pdf->SetFont('kozminproregular', '', 7);
$pdf->SetCellPadding(0.3);
$pdf->MultiCell( 61.4, 0, "適用", 0, 'C', 1, 1, 43.2, 65, true, false, false, true, 5, 'M');

$pdf->SetFont('kozminproregular', '', 8);
for ($i = 10; $i < 30; $i++ ) {
	if(isset($item_name[$i])) {
		if (mb_strwidth($item_name[$i], "UTF-8") > 112) {
			// 縮小化
			$pdf->SetCellPaddings(0.3, 1.3, 0.3, 1.3);
			$pdf->SetFont('kozminproregular', '', 5);
			// 出力
			$pdf->MultiCell( 61.4, 7.7, mb_strimwidth($item_name[$i], 0, 112, "", 'UTF-8'), 0, 'L', 0, 2, 43.2, "", true);
			// 初期化
			$pdf->SetCellPadding(0.3); 
			$pdf->SetFont('kozminproregular', '', 8);
		} elseif (mb_strwidth($item_name[$i], "UTF-8") > 93) {
			// 縮小化
			$pdf->SetCellPaddings(0.3, 1.3, 0.3, 1.3);
			$pdf->SetFont('kozminproregular', '', 5);
			// 出力
			$pdf->MultiCell( 61.4, 7.7, $item_name[$i], 0, 'L', 0, 2, 43.2, "", true);
			// 初期化
			$pdf->SetCellPadding(0.3); 
			$pdf->SetFont('kozminproregular', '', 8);
		} elseif (mb_strwidth($item_name[$i], "UTF-8") > 80) {
			// 縮小化
			$pdf->SetCellPaddings(0.3, 1, 0.3, 1);
			$pdf->SetFont('kozminproregular', '', 6);
			// 出力
			$pdf->MultiCell( 61.4, 7.7, $item_name[$i], 0, 'L', 0, 2, 43.2, "", true);
			// 初期化
			$pdf->SetCellPadding(0.3); // パディング設定
			$pdf->SetFont('kozminproregular', '', 8);
		} elseif (mb_strwidth($item_name[$i], "UTF-8") > 70) {
			// 縮小化
			$pdf->SetCellPaddings(0.3, 0.7, 0.3, 0.7);
			$pdf->SetFont('kozminproregular', '', 7);
			// 出力
			$pdf->MultiCell( 61.4, 7.7, $item_name[$i], 0, 'L', 0, 2, 43.2, "", true);
			// 初期化
			$pdf->SetCellPadding(0.3); // パディング設定
			$pdf->SetFont('kozminproregular', '', 8);
		} else {
			$pdf->MultiCell( 61.4, 7.7, $item_name[$i], 0, 'L', 0, 2, 43.2, "", true);
		}
	}
}


/*	数量
--------------------- */
$pdf->SetFont('kozminproregular', '', 7);
$pdf->SetCellPadding(0.1); // パディング設定
$pdf->MultiCell( 13.2, 0, "数量", 0, 'C', 1, 1, 104.6, 65, true, false, false, true, 5, 'M');
$pdf->SetCellPaddings(0.3, 3, 0.3, 1); // パディング設定
for ($i = 10; $i < 30; $i++ ) {
	if(isset($item_num[$i])) {
		$pdf->MultiCell( 13.2, 7.7, $item_num[$i], 0, 'C', 0, 1, 104.6, "", true, false, false, true, 5, 'B');
	}
}

/*	単位
--------------------- */
$pdf->SetCellPadding(0.1); // パディング設定
$pdf->MultiCell( 13.2, 0, "単位", 0, 'C', 1, 1, 117.8, 65, true, false, false, true, 5, 'M');
$pdf->SetCellPaddings(0.3, 3, 0.3, 1); // パディング設定
$unit_num = count($item_unit);
for ($i = 10; $i < 30; $i++ ) {
	if(isset($item_unit[$i])) {
		$pdf->MultiCell( 13.2, 7.7, $item_unit[$i], 1, 'C', 0, 1, 117.8, "", true, false, false, true, 0, 'B');
	}
}


/*	単価（税込）
--------------------- */
$pdf->SetCellPadding(0.1); // パディング設定
$pdf->MultiCell( 17.6, 0, "単価(税込)", 0, 'C', 1, 1, 131, 65, true, false, false, true, 5, 'M');
$pdf->SetCellPaddings(0.5, 3, 0.5, 1); // パディング設定
$price_num = count($item_price);
for ($i = 10; $i < 30; $i++ ) {
	if(isset($item_price[$i])) {
		$pdf->MultiCell( 17.6, 7.7, $item_price[$i], 0, 'R', 0, 1, 131, "", true, false, false, true, 0, 'B');
	}
}

/*	金額
--------------------- */
$pdf->SetCellPadding(0.1); // パディング設定
$pdf->MultiCell( 26.4, 0, "金額", 0, 'C', 1, 1, 148.6, 65, true, false, false, true, 5, 'M');
$pdf->SetCellPaddings(0.5, 3, 0.5, 1); // パディング設定
$amount_num = count($item_amount);
for ($i = 10; $i < 30; $i++ ) {
	if(isset($item_amount[$i])) {
		$pdf->MultiCell( 26.4, 7.7, $item_amount[$i], 0, 'R', 0, 1, 148.6, "", true, false, false, true, 5, 'B');
	}
}

/*	小計
--------------------- */
$pdf->SetXY(105.0, 224.0);
$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->Cell(44, 0, "小計" , 0, 0, 'C');
$pdf->Cell(26.4, 00, $item_amount10[1] , 0, 0, 'C');

/* ---------------------------------------------------------------------



	4枚目　規約



--------------------------------------------------------------------- */

// 2枚目
$pdf->addPage();
$pdf->SetTopMargin(12); // マージントップ
$pdf->SetAutoPageBreak(false);
$pdf->SetCellPadding(0); // パディング設定
$pdf->setPrintHeader(false); // ヘッダー削除
$pdf->setPrintFooter(false); // フッター削除
$pdf->SetFillColor(255, 255, 255); // 背景色
$pdf->SetFont('kozminproregular', '', 7);
$html = <<< EOF

<style>
	div.h1 {
		font-weight: bold;
	}
	table,
	table tr {
		width: 100%;
	}
	table.wrap {
		width: 100%;
		line-height: 1.5;
	}
	table.inner {
		width: 100%;
	}
	table.inner tr {
		width: 100%;
	}
	table.inner th.title {
		font-weight: bold;
		line-height: 2;
	}
	table.inner tr.listNum th {
		width: 6%;
		text-align: right;
	}
	table.inner tr.listNum td {
		width: 94%;
	}
	table.inner tr.listNumUnder th {
		width: 8%;
		text-align: right;
	}
	table.inner tr.listNumUnder td {
		width: 92%;
	}
	div.red {
		color: red;
		border: 1px solid red;
	}
	div.red table.inner th.space {
		width: 1%;
	}
	div.red table.inner td {
		width: 99%;
	}
	div.red table.inner tr.dot th.listDot {
		width: 5%;
	}
	div.red table.inner tr.dot td {
		width: 94%;
	}
</style>
<div class="h1">割賦販売契約約款</div>
<table class="wrap">
	<tr>
		<td style="width: 45%;">
			<table class="inner">
				<tr><th colspan="2" class="title">第１条（約款の適用および契約内容）</th></tr>
				<tr>
					<td colspan="2">{$our_company}（以下「甲」といいます）と御請求書に記載の発注者（以下「乙」といいます）は、甲の工事または商品（以下「本商品」といいます）の発注に関し、以下の割賦販売契約約款（以下「本約款」といいます）に従うものとし、本商品の割賦販売に関する「割賦販売契約」（以下「本契約」といいます）を締結します。</td>
				</tr>
				<tr><th colspan="2" class="title">第２条（本契約の申込方法および承諾等）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、本契約の申込み（以下「本申込」という）をするときは本約款には、本約款に同意のうえ、甲所定の申込手続を行うものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>前項の場合において、乙は、甲が申込内容を確認するための書類が必要と判断する場合、当該書類を提出するものとします。</td>
				</tr>
				<tr class="listNum">
					<th>３.</th>
					<td>甲は、次の場合には本契約の申込みを承諾しないことがあります。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(1)</th>
					<td>乙が甲との間で締結している本契約に基づく各月の支払い総額が甲が定める基準を満たすことができないおそれがあるとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(2)</th>
					<td>甲の業務遂行上支障があるとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(3)</th>
					<td>その他甲が不適当と判断したとき。</td>
				</tr>
				<tr><th colspan="2" class="title">第３条（契約の成立時点）</th></tr>
				<tr>
					<td colspan="2">本契約は、甲が乙からの申込みを所定の手続きをもって承諾し、所定の方法で承諾の通知を受けた時をもって成立するものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第４条（分割払金の支払期日および支払方法）</th></tr>
				<tr>
					<td colspan="2">乙は、甲が契約後に交付または送付（電子メールによる送信を含みます）する書面（電磁的に交付するものを含む。以下「契約完了書面」といいます）に記載の分割払金を、契約完了書面に記載の支払期日から、支払うものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第５条（入金案内）</th></tr>
				<tr>
					<td colspan="2">利用者は、本オプション機能を利用する前に、顧客に対し、分割金の未払いが起こった場合には弊社より「SMS」「自動音声」「オペレーター」案内のいずれかより連絡がいく場合がある旨を十分に説明の上で同意させるものとし、弊社は当該同意がなされているものとみなすことができるものとします。万一、顧客から弊社に対し、個人情報の流用の観点で指摘があった場合であっても、弊社は利用者から顧客に対して事前に説明を行い同意を得ているものとみなし、その責任はすべて利用者が負うものとし、弊社は一切責任を負わないものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第６条（商品引渡し及び所有権の移転）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>甲は、本契約が成立した後、所定の時期に本商品を購入者に引渡すものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>本商品の所有権は、本商品の支払いが完了した際に、乙へ所有権移転するものとします。</td>
				</tr>
				<tr class="listNum">
					<th>３.</th>
					<td>乙は、本商品の所有権移転前においては、本商品を担保に供し、譲渡し、するまたは転売することができないものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第７条（届出事項の変更）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、甲に届け出た氏名もしくは名称、住所または連絡先等を変更した場合は、速やかに甲所定の方法により甲に届け出るものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>乙は、前項の届出がないために、甲等からの通知または送付書類等が延着または不到達となった場合には、通常到達すべき時に到達したものと甲等がみなすことに異議のないものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第８条（契約上の地位の譲渡）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、本契約に係る契約上の地位を譲渡することができないものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>前項の定めは、相続または法人の合併、分割等により本契約に係る契約上の地位が承継される場合には適用しないものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第９条（期限の利益喪失）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、次のいずれかの事由に該当したときは、当然に本契約に基づく債務について期限の利益を失い、直ちに債務の全てを履行するものとします。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(1)</th>
					<td>分割払金の支払を遅滞し、その支払を書面で20日以上の相当の期間を定めて催告されたにもかかわらず、その期間内に支払わなかったとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(2)</th>
					<td>自ら振り出した手形、小切手が不渡りになったとき、または一般の支払を停止したとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(3)</th>
					<td>差押、仮差押、保全差押、仮処分の申し立てまたは滞納処分を受けたとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(4)</th>
					<td>破産、民事再生、特別清算、会社更生その他裁判上の倒産処理手続の申し立てを受けたとき、または自らこれらの申し立てをしたとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(5)</th>
					<td>その他乙の信用状態が著しく悪化したとき。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>甲は、乙が前項のいずれかに該当する場合は、本契約を解除することができるものとします。</td>
				</tr>
			</table>
		</td>
		<td style="width: 10%;"></td>
		<td style="width: 45%;">
			<table class="inner">
				<tr><th colspan="2" class="title">第１０条（損害遅延金）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、分割代金を遅滞したときは、支払期日の翌日から支払日にいたるまで、当該分割払金に対し年6.00％を上限とした額の遅延損害金を支払うものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>乙は、本契約に基づく債務について期限の利益を喪失したときは、期限の利益喪失の日から完済の日に至るまで、本商品の割賦販売価格から既に支払われた分割代金の合計額を控除した残金に対し、年6.00％を上限とした額の遅延損害金を支払うものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１１条（手数料の負担等）</th></tr>
				<tr>
					<td colspan="2">乙は、分割代金の支払に関する手数料を負担するものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１２条（早期一括返済）</th></tr>
				<tr>
					<td colspan="2">乙は、甲等に申し出ることにより、分割払金の残額を一括して支払うことができるものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１３条（反社会的勢力との関係の遮断）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は本契約締結日において、暴力団、暴力団員、暴力団員でなくなった時から５年を経過しない者、暴力団準構成員、暴力団関係企業、総会屋等、社会運動等標ぼうゴロ又は特殊知能暴力集団等、これらの共生者、その他これらに準ずる者、テロリスト（疑いがある場合も含む）に該当しないことを表明し、かつ将来にわたっても該当しないことを確約します。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>乙が第１項各号に該当した場合、またはは第１項の規定に基づく確約に関して虚偽の申告をしたことが判明した場合、甲は直ちに本契約を解除することができ、且つ、甲に生じた損害の賠償を請求できるものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１４条（割賦債権の譲渡）</th></tr>
				<tr>
					<td colspan="2">甲は、乙に対する本契約に基づく債権を第三者に譲渡することがあります。この場合において、ご契約者様は、当該債権の譲渡及び甲が購入者の個人情報を譲渡先に提供することをあらかじめ同意するものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１５条（個人情報の取扱い）</th></tr>
				<tr>
					<td colspan="2">甲は乙に関する個人情報の取扱いに関するプライバシーポリシーを定め、これを甲のホームページ等において公表するか、甲の求めに応じて遅滞なく回答します。</td>
				</tr>
				<tr><th colspan="2" class="title">第１６条（合意管轄裁判所）</th></tr>
				<tr>
					<td colspan="2">乙は、本商品、本約款及び本契約について紛争が生じた場合、訴額の如何にかかわらず、甲の本店所在地を管轄する地方裁判所を第一審の専属的合意管轄裁判所とすることに同意するものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１７条（補則）</th></tr>
				<tr>
					<td colspan="2">この約款に定めなき事項が生じた場合、甲と乙は契約の趣旨に従い、誠意を持って協議・解決に努めるものとします。<br><br></td>
				</tr>
			</table>
			<div class="red">
				<table class="inner">
					<tr><td colspan="3"></td></tr>
					<tr>
						<th class="space"></th>
						<th colspan="2" class="title">クーリングオフについて（説明書）</th>
					</tr>
					<tr>
						<th class="space"></th>
						<td colspan="2">ご契約いただきます工事または商品等の販売につきましては、この割賦販売契約約款及びクーリングオフについての説明書の内容を十分にご確認ください。この割賦販売契約に「特定商品取引に関する法律」が適用される場合、お客様はこの説明書面受領日から起算して８日以内は、書面をもって契約の解除（クーリングオフと呼びます）をすることができ、その効力はお客様が解除する旨の書面を甲に対して発したときに生じるものとします。</td>
					</tr>
					<tr>
						<th class="space"></th>
						<td colspan="2">ただし、次のような場合等にはクーリングオフの権利行使はできません。</td>
					</tr>
					<tr class="dot">
						<th class="space"></th>
						<th class="listDot">・</th>
						<td>お客様が工事や商品等を営業用に利用する場合</td>
					</tr>
					<tr class="dot">
						<th class="space"></th>
						<th class="listDot">・</th>
						<td>甲の営業所等で契約の申込みまたは契約締結がなされた場合</td>
					</tr>
					<tr class="dot">
						<th class="space"></th>
						<th class="listDot">・</th>
						<td>お客様からのご請求によりご自宅での契約の申込みまたは契約締結がなされた場合</td>
					</tr>
					<tr>
						<th class="space"></th>
						<td colspan="2">上記期間内にクーリングオフがあった場合、甲は契約の解除に伴う損害賠償または違約金支払を請求することはありません。万一、クーリングオフがあった場合に、既に商品等の引渡しが行われている時は、その引取りに要する費用は甲の負担とします。また、契約解除のお申し出の際に既に受領した金員がある場合、すみやかにその金額を無利息にて返還いたします。</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
EOF;

$pdf->writeHTML($html);

}

/* ---------------------------------------------------------------------



	5枚目　続き



--------------------------------------------------------------------- */

$pageCnt = 0;
$pageCnt = $pdf->setSourceFile(__DIR__ . '/pdf/multiple_fin.pdf');
for( $i=1 ; $i<=$pageCnt ; $i++ ){
	$pdf->addPage();
	$pdf->useTemplate($pdf->importPage($i));
}

// No.
$pdf->SetFont('kozminproregular', '', 7);
$pdf->SetXY(158.0, 23.0);
$pdf->Cell(29, 0, "No.\t{$contract_num}" , 0, 0, 'L');

// 日付
$pdf->SetXY(158.0, 34.0);
$pdf->Cell(0, 0, $contract_date , 0, 0, 'L');


// ==========================================
//
// 見積もり項目
//
// ==========================================


/*	No.
------------------------------ */
$pdf->SetFont('kozminproregular', '', 7);
$pdf->SetCellPadding(0.1);
$pdf->MultiCell( 9.2, 0, "No.", 0, 'C', 1, 1, 34, 65, true, false, false, true, 5, 'M');

$pdf->SetCellPadding(2);
$pdf->SetFont('kozminproregular', '', 8);
if ($_SESSION['q2_count'] > 30) {
	$imin = 31;
	$imax = 50;
} else {
	$imin = 11;
	$imax = 30;
}
for ($i = $imin; $i <= $imax; $i++) {
	$pdf->MultiCell( 9.2, 7.7, $i, 0, 'C', 0, 1, 34, "", true, false, false, true, 5, 'C');
}


/*	適用
------------------------------ */
$pdf->SetFont('kozminproregular', '', 7);
$pdf->SetCellPadding(0.3);
$pdf->MultiCell( 61.4, 0, "適用", 0, 'C', 1, 1, 43.2, 65, true, false, false, true, 5, 'M');

$pdf->SetFont('kozminproregular', '', 8);
if ($_SESSION['q2_count'] > 30) {
	$imin = 30;
	$imax = 50;
} else {
	$imin = 10;
	$imax = 30;
}
for ($i = $imin; $i < $imax; $i++ ) {
	if(isset($item_name[$i])) {
		if (mb_strwidth($item_name[$i], "UTF-8") > 112) {
			// 縮小化
			$pdf->SetCellPaddings(0.3, 1.3, 0.3, 1.3);
			$pdf->SetFont('kozminproregular', '', 5);
			// 出力
			$pdf->MultiCell( 61.4, 7.7, mb_strimwidth($item_name[$i], 0, 112, "", 'UTF-8'), 0, 'L', 0, 2, 43.2, "", true);
			// 初期化
			$pdf->SetCellPadding(0.3); 
			$pdf->SetFont('kozminproregular', '', 8);
		} elseif (mb_strwidth($item_name[$i], "UTF-8") > 93) {
			// 縮小化
			$pdf->SetCellPaddings(0.3, 1.3, 0.3, 1.3);
			$pdf->SetFont('kozminproregular', '', 5);
			// 出力
			$pdf->MultiCell( 61.4, 7.7, $item_name[$i], 0, 'L', 0, 2, 43.2, "", true);
			// 初期化
			$pdf->SetCellPadding(0.3); 
			$pdf->SetFont('kozminproregular', '', 8);
		} elseif (mb_strwidth($item_name[$i], "UTF-8") > 80) {
			// 縮小化
			$pdf->SetCellPaddings(0.3, 1, 0.3, 1);
			$pdf->SetFont('kozminproregular', '', 6);
			// 出力
			$pdf->MultiCell( 61.4, 7.7, $item_name[$i], 0, 'L', 0, 2, 43.2, "", true);
			// 初期化
			$pdf->SetCellPadding(0.3); // パディング設定
			$pdf->SetFont('kozminproregular', '', 8);
		} elseif (mb_strwidth($item_name[$i], "UTF-8") > 70) {
			// 縮小化
			$pdf->SetCellPaddings(0.3, 0.7, 0.3, 0.7);
			$pdf->SetFont('kozminproregular', '', 7);
			// 出力
			$pdf->MultiCell( 61.4, 7.7, $item_name[$i], 0, 'L', 0, 2, 43.2, "", true);
			// 初期化
			$pdf->SetCellPadding(0.3); // パディング設定
			$pdf->SetFont('kozminproregular', '', 8);
		} else {
			$pdf->MultiCell( 61.4, 7.7, $item_name[$i], 0, 'L', 0, 2, 43.2, "", true);
		}
	}
}


/*	数量
--------------------- */
$pdf->SetFont('kozminproregular', '', 7);
$pdf->SetCellPadding(0.1); // パディング設定
$pdf->MultiCell( 13.2, 0, "数量", 0, 'C', 1, 1, 104.6, 65, true, false, false, true, 5, 'M');
$pdf->SetCellPaddings(0.3, 3, 0.3, 1); // パディング設定
for ($i = $imin; $i < $imax; $i++ ) {
	if(isset($item_num[$i])) {
		$pdf->MultiCell( 13.2, 7.7, $item_num[$i], 0, 'C', 0, 1, 104.6, "", true, false, false, true, 5, 'B');
	}
}

/*	単位
--------------------- */
$pdf->SetCellPadding(0.1); // パディング設定
$pdf->MultiCell( 13.2, 0, "単位", 0, 'C', 1, 1, 117.8, 65, true, false, false, true, 5, 'M');
$pdf->SetCellPaddings(0.3, 3, 0.3, 1); // パディング設定
$unit_num = count($item_unit);
for ($i = $imin; $i < $imax; $i++ ) {
	if(isset($item_unit[$i])) {
		$pdf->MultiCell( 13.2, 7.7, $item_unit[$i], 1, 'C', 0, 1, 117.8, "", true, false, false, true, 0, 'B');
	}
}


/*	単価（税込）
--------------------- */
$pdf->SetCellPadding(0.1); // パディング設定
$pdf->MultiCell( 17.6, 0, "単価(税込)", 0, 'C', 1, 1, 131, 65, true, false, false, true, 5, 'M');
$pdf->SetCellPaddings(0.5, 3, 0.5, 1); // パディング設定
$price_num = count($item_price);
for ($i = $imin; $i < $imax; $i++ ) {
	if(isset($item_price[$i])) {
		$pdf->MultiCell( 17.6, 7.7, $item_price[$i], 0, 'R', 0, 1, 131, "", true, false, false, true, 0, 'B');
	}
}

/*	金額
--------------------- */
$pdf->SetCellPadding(0.1); // パディング設定
$pdf->MultiCell( 26.4, 0, "金額", 0, 'C', 1, 1, 148.6, 65, true, false, false, true, 5, 'M');
$pdf->SetCellPaddings(0.5, 3, 0.5, 1); // パディング設定
$amount_num = count($item_amount);
for ($i = $imin; $i < $imax; $i++ ) {
	if(isset($item_amount[$i])) {
		$pdf->MultiCell( 26.4, 7.7, $item_amount[$i], 0, 'R', 0, 1, 148.6, "", true, false, false, true, 5, 'B');
	}
}

/*	小計
--------------------- */
$pdf->SetXY(105.0, 224.0);
$pdf->SetFont('kozminproregular', 'B', 8);
$pdf->Cell(44, 0, "小計" , 0, 0, 'C');
$pdf->Cell(26.4, 00, $table_subtotal , 0, 0, 'C');

/*	備考
--------------------- */
$pdf->SetFont('kozminproregular', '', 7);
$pdf->SetCellPaddings(1, 1, 1, 0);
$pdf->MultiCell( 142, 1, "【備考】", 0, 'L', 1, 1, 34, 231.7, true, false, false, true, 5, 'T');
$pdf->SetCellPaddings(1, 0, 1, 1);
// $pdf->MultiCell( 141, 14, $item_remark, 0, 'L', 1, 1, 34, "", true, false, false, true, 5, 'T');
$pdf->MultiCell( 141, 12, $item_remark, 0, 'L', 1, 1, 34);

/*	注意事項
--------------------- */
$pdf->MultiCell( 70, 10, "上記案内及び裏面「割賦販売契約約款」を確認し、説明を受けて、支払い方法や金額及び自らの支払い能力等を十分に検討したうえで商品の発注をします。", 0, 'L', 0, 2, 17, 265, true);

/*	署名
--------------------- */
$pdf->SetXY(117.7, 265.0);
$pdf->Cell(20, 7, "お名前" , 0, 0, 'L', false, 'M');
$pdf->Cell(50, 7, "" , 0, 0, 'L', false, 'M');
$pdf->Cell(5, 7, "" , 0, 0, 'L', false, 'M');

/* ---------------------------------------------------------------------



	6枚目　規約



--------------------------------------------------------------------- */
$pdf->addPage();
$pdf->SetTopMargin(12); // マージントップ
$pdf->SetAutoPageBreak(false);
$pdf->SetCellPadding(0); // パディング設定
$pdf->setPrintHeader(false); // ヘッダー削除
$pdf->setPrintFooter(false); // フッター削除
$pdf->SetFillColor(255, 255, 255); // 背景色
$pdf->SetFont('kozminproregular', '', 7);
$html = <<< EOF

<style>
	div.h1 {
		font-weight: bold;
	}
	table,
	table tr {
		width: 100%;
	}
	table.wrap {
		width: 100%;
		line-height: 1.5;
	}
	table.inner {
		width: 100%;
	}
	table.inner tr {
		width: 100%;
	}
	table.inner th.title {
		font-weight: bold;
		line-height: 2;
	}
	table.inner tr.listNum th {
		width: 6%;
		text-align: right;
	}
	table.inner tr.listNum td {
		width: 94%;
	}
	table.inner tr.listNumUnder th {
		width: 8%;
		text-align: right;
	}
	table.inner tr.listNumUnder td {
		width: 92%;
	}
	div.red {
		color: red;
		border: 1px solid red;
	}
	div.red table.inner th.space {
		width: 1%;
	}
	div.red table.inner td {
		width: 99%;
	}
	div.red table.inner tr.dot th.listDot {
		width: 5%;
	}
	div.red table.inner tr.dot td {
		width: 94%;
	}
</style>
<div class="h1">割賦販売契約約款</div>
<table class="wrap">
	<tr>
		<td style="width: 45%;">
			<table class="inner">
				<tr><th colspan="2" class="title">第１条（約款の適用および契約内容）</th></tr>
				<tr>
					<td colspan="2">{$our_company}（以下「甲」といいます）と御請求書に記載の発注者（以下「乙」といいます）は、甲の工事または商品（以下「本商品」といいます）の発注に関し、以下の割賦販売契約約款（以下「本約款」といいます）に従うものとし、本商品の割賦販売に関する「割賦販売契約」（以下「本契約」といいます）を締結します。</td>
				</tr>
				<tr><th colspan="2" class="title">第２条（本契約の申込方法および承諾等）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、本契約の申込み（以下「本申込」という）をするときは本約款には、本約款に同意のうえ、甲所定の申込手続を行うものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>前項の場合において、乙は、甲が申込内容を確認するための書類が必要と判断する場合、当該書類を提出するものとします。</td>
				</tr>
				<tr class="listNum">
					<th>３.</th>
					<td>甲は、次の場合には本契約の申込みを承諾しないことがあります。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(1)</th>
					<td>乙が甲との間で締結している本契約に基づく各月の支払い総額が甲が定める基準を満たすことができないおそれがあるとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(2)</th>
					<td>甲の業務遂行上支障があるとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(3)</th>
					<td>その他甲が不適当と判断したとき。</td>
				</tr>
				<tr><th colspan="2" class="title">第３条（契約の成立時点）</th></tr>
				<tr>
					<td colspan="2">本契約は、甲が乙からの申込みを所定の手続きをもって承諾し、所定の方法で承諾の通知を受けた時をもって成立するものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第４条（分割払金の支払期日および支払方法）</th></tr>
				<tr>
					<td colspan="2">乙は、甲が契約後に交付または送付（電子メールによる送信を含みます）する書面（電磁的に交付するものを含む。以下「契約完了書面」といいます）に記載の分割払金を、契約完了書面に記載の支払期日から、支払うものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第５条（入金案内）</th></tr>
				<tr>
					<td colspan="2">利用者は、本オプション機能を利用する前に、顧客に対し、分割金の未払いが起こった場合には弊社より「SMS」「自動音声」「オペレーター」案内のいずれかより連絡がいく場合がある旨を十分に説明の上で同意させるものとし、弊社は当該同意がなされているものとみなすことができるものとします。万一、顧客から弊社に対し、個人情報の流用の観点で指摘があった場合であっても、弊社は利用者から顧客に対して事前に説明を行い同意を得ているものとみなし、その責任はすべて利用者が負うものとし、弊社は一切責任を負わないものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第６条（商品引渡し及び所有権の移転）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>甲は、本契約が成立した後、所定の時期に本商品を購入者に引渡すものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>本商品の所有権は、本商品の支払いが完了した際に、乙へ所有権移転するものとします。</td>
				</tr>
				<tr class="listNum">
					<th>３.</th>
					<td>乙は、本商品の所有権移転前においては、本商品を担保に供し、譲渡し、するまたは転売することができないものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第７条（届出事項の変更）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、甲に届け出た氏名もしくは名称、住所または連絡先等を変更した場合は、速やかに甲所定の方法により甲に届け出るものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>乙は、前項の届出がないために、甲等からの通知または送付書類等が延着または不到達となった場合には、通常到達すべき時に到達したものと甲等がみなすことに異議のないものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第８条（契約上の地位の譲渡）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、本契約に係る契約上の地位を譲渡することができないものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>前項の定めは、相続または法人の合併、分割等により本契約に係る契約上の地位が承継される場合には適用しないものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第９条（期限の利益喪失）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、次のいずれかの事由に該当したときは、当然に本契約に基づく債務について期限の利益を失い、直ちに債務の全てを履行するものとします。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(1)</th>
					<td>分割払金の支払を遅滞し、その支払を書面で20日以上の相当の期間を定めて催告されたにもかかわらず、その期間内に支払わなかったとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(2)</th>
					<td>自ら振り出した手形、小切手が不渡りになったとき、または一般の支払を停止したとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(3)</th>
					<td>差押、仮差押、保全差押、仮処分の申し立てまたは滞納処分を受けたとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(4)</th>
					<td>破産、民事再生、特別清算、会社更生その他裁判上の倒産処理手続の申し立てを受けたとき、または自らこれらの申し立てをしたとき。</td>
				</tr>
				<tr class="listNumUnder">
					<th>(5)</th>
					<td>その他乙の信用状態が著しく悪化したとき。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>甲は、乙が前項のいずれかに該当する場合は、本契約を解除することができるものとします。</td>
				</tr>
			</table>
		</td>
		<td style="width: 10%;"></td>
		<td style="width: 45%;">
			<table class="inner">
				<tr><th colspan="2" class="title">第１０条（損害遅延金）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は、分割代金を遅滞したときは、支払期日の翌日から支払日にいたるまで、当該分割払金に対し年6.00％を上限とした額の遅延損害金を支払うものとします。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>乙は、本契約に基づく債務について期限の利益を喪失したときは、期限の利益喪失の日から完済の日に至るまで、本商品の割賦販売価格から既に支払われた分割代金の合計額を控除した残金に対し、年6.00％を上限とした額の遅延損害金を支払うものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１１条（手数料の負担等）</th></tr>
				<tr>
					<td colspan="2">乙は、分割代金の支払に関する手数料を負担するものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１２条（早期一括返済）</th></tr>
				<tr>
					<td colspan="2">乙は、甲等に申し出ることにより、分割払金の残額を一括して支払うことができるものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１３条（反社会的勢力との関係の遮断）</th></tr>
				<tr class="listNum">
					<th>１.</th>
					<td>乙は本契約締結日において、暴力団、暴力団員、暴力団員でなくなった時から５年を経過しない者、暴力団準構成員、暴力団関係企業、総会屋等、社会運動等標ぼうゴロ又は特殊知能暴力集団等、これらの共生者、その他これらに準ずる者、テロリスト（疑いがある場合も含む）に該当しないことを表明し、かつ将来にわたっても該当しないことを確約します。</td>
				</tr>
				<tr class="listNum">
					<th>２.</th>
					<td>乙が第１項各号に該当した場合、またはは第１項の規定に基づく確約に関して虚偽の申告をしたことが判明した場合、甲は直ちに本契約を解除することができ、且つ、甲に生じた損害の賠償を請求できるものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１４条（割賦債権の譲渡）</th></tr>
				<tr>
					<td colspan="2">甲は、乙に対する本契約に基づく債権を第三者に譲渡することがあります。この場合において、ご契約者様は、当該債権の譲渡及び甲が購入者の個人情報を譲渡先に提供することをあらかじめ同意するものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１５条（個人情報の取扱い）</th></tr>
				<tr>
					<td colspan="2">甲は乙に関する個人情報の取扱いに関するプライバシーポリシーを定め、これを甲のホームページ等において公表するか、甲の求めに応じて遅滞なく回答します。</td>
				</tr>
				<tr><th colspan="2" class="title">第１６条（合意管轄裁判所）</th></tr>
				<tr>
					<td colspan="2">乙は、本商品、本約款及び本契約について紛争が生じた場合、訴額の如何にかかわらず、甲の本店所在地を管轄する地方裁判所を第一審の専属的合意管轄裁判所とすることに同意するものとします。</td>
				</tr>
				<tr><th colspan="2" class="title">第１７条（補則）</th></tr>
				<tr>
					<td colspan="2">この約款に定めなき事項が生じた場合、甲と乙は契約の趣旨に従い、誠意を持って協議・解決に努めるものとします。<br><br></td>
				</tr>
			</table>
			<div class="red">
				<table class="inner">
					<tr><td colspan="3"></td></tr>
					<tr>
						<th class="space"></th>
						<th colspan="2" class="title">クーリングオフについて（説明書）</th>
					</tr>
					<tr>
						<th class="space"></th>
						<td colspan="2">ご契約いただきます工事または商品等の販売につきましては、この割賦販売契約約款及びクーリングオフについての説明書の内容を十分にご確認ください。この割賦販売契約に「特定商品取引に関する法律」が適用される場合、お客様はこの説明書面受領日から起算して８日以内は、書面をもって契約の解除（クーリングオフと呼びます）をすることができ、その効力はお客様が解除する旨の書面を甲に対して発したときに生じるものとします。</td>
					</tr>
					<tr>
						<th class="space"></th>
						<td colspan="2">ただし、次のような場合等にはクーリングオフの権利行使はできません。</td>
					</tr>
					<tr class="dot">
						<th class="space"></th>
						<th class="listDot">・</th>
						<td>お客様が工事や商品等を営業用に利用する場合</td>
					</tr>
					<tr class="dot">
						<th class="space"></th>
						<th class="listDot">・</th>
						<td>甲の営業所等で契約の申込みまたは契約締結がなされた場合</td>
					</tr>
					<tr class="dot">
						<th class="space"></th>
						<th class="listDot">・</th>
						<td>お客様からのご請求によりご自宅での契約の申込みまたは契約締結がなされた場合</td>
					</tr>
					<tr>
						<th class="space"></th>
						<td colspan="2">上記期間内にクーリングオフがあった場合、甲は契約の解除に伴う損害賠償または違約金支払を請求することはありません。万一、クーリングオフがあった場合に、既に商品等の引渡しが行われている時は、その引取りに要する費用は甲の負担とします。また、契約解除のお申し出の際に既に受領した金員がある場合、すみやかにその金額を無利息にて返還いたします。</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
EOF;

$pdf->writeHTML($html);