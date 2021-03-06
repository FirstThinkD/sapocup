<?php
session_start();
require('../../../common/dbconnect.php');
include "../../../tcpdf/tcpdf.php";

if ($_SESSION['loginID'] == "") {
	header("Location:/");
	exit();
}

if ($_GET['id'] == "") {
	header("Location:/");
	exit();
}
$qu_id = $_GET['id'];

$now_date = date("YmdHis");
$fullpath = "/usr/home/haw1008ufet9/html/manage/estimates/new/pdf/". $now_date;
mkdir($fullpath, 0755);

$filename1 = $fullpath. "/". $now_date. ".pdf";
pdf_write("1", $qu_id, $filename1);

$now_date2 = ($now_date + 1);
$filename2 = $fullpath. "/". $now_date2. ".pdf";
pdf_write("2", $qu_id, $filename2);

$pdf_name = "/manage/estimates/new/pdf/". $now_date. "/". $now_date. ".pdf";

$sql = sprintf('UPDATE `quotation` SET qu_pdf="%s", qu_dir="%s" WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $pdf_name),
	mysqli_real_escape_string($db, $now_date),
	mysqli_real_escape_string($db, $qu_id)
);
mysqli_query($db,$sql) or die(mysqli_error($db));

header("Location:/manage/estimates/detail/?id=". $qu_id);
exit();


function pdf_write($func, $qu_id, $filename) {
	require('../../../common/dbconnect.php');

	$sql = sprintf('SELECT * FROM `q_items` WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_id)
	);
	$ix = 0;
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	while($row0 = mysqli_fetch_assoc($record)) {
		$q_name[$ix]   = $row0['q_name'];
		$q_number[$ix] = number_format($row0['q_number']);
		$q_unit[$ix]   = $row0['q_unit'];
		$q_price[$ix]  = number_format($row0['q_price']);
		$q_total[$ix]  = number_format($row0['q_total']);
		$ix++;
	}
	$q_cont = $ix;

	$tcpdf = new TCPDF();
	$tcpdf->AddPage(); // 新しいpdfページを追加

	$tcpdf->SetFont("kozgopromedium", "", 10); // デフォルトで用意されている日本語フォント

$html = <<< EOF
<style>
h1 {
	font-size: 24px; // 文字の大きさ
	color: #ff00ff; // 文字の色
	text-align: center; // テキストを真ん中に寄せる
}
p {
	font-size: 12px; // 文字の大きさ
	color: #000000; // 文字の色
	text-align: left; // テキストを左に寄せる
}
</style>
EOF;
if ($func == 1) {
$html .= <<< EOF
<h1>侍エンジニア塾</h1>
EOF;
} else {
$html .= <<< EOF
<h1>侍エンジニア塾（テスト）</h1>
EOF;
}
$html .= <<< EOF
<p>今日は侍エンジニア塾についてお話させていただきます。</p>
<div id="estimateCalculate" class="pdf_fields row" style="border-bottom: 1px solid #000;">
	<table>
		<thead>
			<tr>
				<th></th>
				<th>項目</th>
				<th>数量</th>
				<th>単位</th>
				<th>単価</th>
				<th>金額</th>
			</tr>
		</thead>
	</table>
</div>
EOF;
for($ix=0; $ix<$q_cont; $ix++) {
$html .= <<< EOF
		<tbody>
			<tr class="pdf_input" v-for="ready in 6" v-cloak>
				<td></td>
				<td>$q_name[$ix]</td>
				<td style="text-align:right;">$q_number[$ix]</td>
				<td style="text-align:right;">$q_unit[$ix]</td>
				<td style="text-align:right;">$q_price[$ix]</td>
				<td style="text-align:right;">$q_total[$ix]</td>
			</tr>
		</tbody>
EOF;
}
$html .= <<< EOF
<p>終了　$q_cont件</p>
EOF;

	$tcpdf->writeHTML($html); // 表示htmlを設定
	$tcpdf->Output($filename, 'F'); //  ローカルファイルとして保存
	// $tcpdf->Output($, 'D'); // pdf表示設定
	return 0;
}
?>
