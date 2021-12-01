<?php
session_start();
require_once(__DIR__ . '/../../../common/dbconnect.php');
require_once(__DIR__ . '/../../common/functions.php');

// ログイン確認
if (empty($_SESSION['loginID'])) {
	header("Location:/login.php");
	exit();
}
if (empty($_GET['id'])) {
	header("Location:/login.php");
	exit();
}

//　送られたIDを取得
$qu_id = $_GET['id'];

if (!empty($_POST['pdfout']) && $_POST['pdfout'] == "出力") {
	$_SESSION['da_pdfbox'] = array();
	$org_ym = date('Ym');

	$sql = sprintf('SELECT * FROM `data_quo` WHERE qu_id="%d" AND yyyymm="%s" AND delFlag=0',
		mysqli_real_escape_string($db, $qu_id),
		mysqli_real_escape_string($db, $org_ym)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row0 = mysqli_fetch_assoc($record)) {
	} else {
		$sql = sprintf('SELECT * FROM `data_quo` WHERE qu_id="%d" AND delFlag=0 ORDER BY yyyymm LIMIT 1',
			mysqli_real_escape_string($db, $qu_id)
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$row0 = mysqli_fetch_assoc($record);
	}

	$_SESSION['da_pdfbox'][0][0] = $row0['da_id'];
	$_SESSION['da_pdfbox'][0][1] = $row0['depo_date'];
	$_SESSION['da_pdfbox'][0][2] = 1;
	$_SESSION['da_pdfbox'][0][3] = 0;	// 見積書

	$url = '<script>if (window.confirm("見積書のPDFを出力します。\n宜しいですか？")) { window.open("/manage/tcpdf/out-pdf3.php", "_blank");}</script>';
	echo $url;
}

// 曜日の設定
$week = ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'];
$date = date('w');

// 見積書情報取得
$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row0 = mysqli_fetch_assoc($record);
$quotation['qu_id']		= $row0['qu_id']; 						// ID
$quotation['c_id']		= $row0['c_id']; 						// クライアントID
$quotation['u_id']		= $row0['u_id']; 						// ユーザーID
$quotation['qu_bunrui']		= $row0['qu_bunrui']; 						// 商品 or 工事
$quotation['in_contactName']	= $row0['in_contactName'];					// 担当者情報
$quotation['qu_name']		= $row0['qu_name']; 						// 工事名称
$quotation['qu_location']	= $row0['qu_location']; 					// 工事場所
// $payDay			= $row0['qu_paymentDate'];					// 支払日
$payMonth 			= date('Y-m');							// 今月
$payMonthJa 			= date('Y年m月');						// 今月（年 月）
// $payWeek			= $week[date('w', strtotime(date('Y-m') . '-' . $payDay))];	// 支払曜日
$quotation['qu_paymentDate']	= $row0['qu_paymentDate'];					// 支払日
$quotation['qu_startDate']	= date('Y年m月', strtotime($row0['qu_startDate'])); 		// 開始月
$w_qu_startDate			= date('w', strtotime($row0['qu_startDate'])); 			// 支払曜日
$quotation['qu_deliveryDate']	= date('Y年m月d日', strtotime($row0['qu_deliveryDate']));	// 受渡期日
$quotation['w_qu_deliveryDate']	= $week[date('w', strtotime($row0['qu_deliveryDate']))]; 	// 受渡期日_曜日

$quotation['q_alltotal']	= number_format($row0['q_alltotal'], 0); 			// 見積書 - 合計金額
$quotation['q_cost']		= number_format($row0['q_cost'], 0); 				// 見積書 - 消費税
$quotation['qu_price']		= number_format($row0['qu_price'], 0); 				// 見積書 - 商品価格
$quotation['qu_commit']		= number_format($row0['qu_commit'], 0); 			// 見積書 - 割賦手数料
$quotation['qu_commission']	= $row0['qu_commission']; 					// 見積書 - 割賦手数料率
$quotation['qu_initPayAmount']	= $row0['qu_initPayAmount'];		 			// 見積書 - 初回お支払額
$quotation['qu_amount_pay']	= number_format($row0['qu_amount_pay'], 0); 			// 見積書 - 月々お支払額
$quotation['qu_installments']	= $row0['qu_installments']; 					// 見積書 - 分割回数
$quotation['qu_deposit']	= $row0['qu_deposit']; 						// 見積書 - 頭金
$quotation['qu_startDate']	= date('Y年m月', strtotime($row0['qu_startDate'])); 		// 見積書 - 返済開始予定年月
$quotation['qu_endDate']	= date('Y年m月', strtotime($row0['qu_endDate'])); 		// 見積書 - 返済終了予定年月
$quotation['syohkei']		= $row0['syohkei']; 						// 見積書 - 小計

if (ctype_digit($quotation['syohkei'])) {
	$quotation['syohkei'] = number_format($row0['syohkei']);
}

$quotation['qu_no'] = sprintf("%06d", $quotation['qu_id']);

if ($quotation['qu_paymentDate'] != "末日") {
	$quotation['qu_paymentDate'] = $row0['qu_paymentDate']. "日";				// 支払日
}
if (ctype_digit($quotation['qu_initPayAmount'])) {
	$quotation['qu_initPayAmount'] = number_format($quotation['qu_initPayAmount']);	// 見積書 - 初回お支払額
}
if (ctype_digit($quotation['qu_deposit'])) {
	$quotation['qu_deposit'] = number_format($quotation['qu_deposit']);		// 見積書 - 頭金
}

if ($row0['updated'] != "") {
	$updated = date('Y年m月d日', strtotime($row0['updated']));
} else {
	$updated = date('Y年m月d日');
}

$user['u_company']  = $row0['in_companyName'];	// 見積元名
$user['p_name']     = "";			// 見積元担当者名
$user['p_postal']   = $row0['in_postal'];	// 見積元郵便番号
$user['p_address1'] = $row0['in_address1'];	// 見積元住所1
$user['p_address2'] = $row0['in_address2'];	// 見積元住所2
$user['p_address3'] = $row0['in_address3'];	// 見積元住所3
$user['p_tel']      = $row0['in_tel'];		// 見積元電話番号


$q_name   = array();					// 見積もり項目名
// $q_number[$ix] = number_format($rows['q_number']);	// 見積もり個数
// $q_unit[$ix]   = $rows['q_unit'];			// 見積もり単位
// $q_price[$ix]  = number_format($rows['q_price']);	// 見積もり単価
// $q_total[$ix]  = number_format($rows['q_total']);	// 見積もり金額

// 見積項目
$sql = sprintf('SELECT * FROM `q_items` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$ix = 0;
while($rows = mysqli_fetch_assoc($record)) {
	$q_id[$ix]     = $rows['q_id'];				// 見積もりID
	$q_name[$ix]   = $rows['q_name'];			// 見積もり項目名
	// $q_number[$ix] = number_format($rows['q_number']);	// 見積もり個数
	$q_number[$ix] = $rows['q_number'];			// 見積もり個数
	$q_unit[$ix]   = $rows['q_unit'];			// 見積もり単位
	$q_price[$ix]  = number_format($rows['q_price']);	// 見積もり単価
	$q_total[$ix]  = $rows['q_total'];			// 見積もり金額
	$q_total[$ix]  = number_format($q_total[$ix]);		// 見積もり金額

	$leng = strlen($q_name[$ix]);
	if ($leng > 25) {
		$q_name1[$ix] = mb_substr($q_name[$ix], 0, 25);
		if ($leng > 50) {
			$q_name2[$ix] = mb_substr($q_name[$ix], 25, 25);
		} else {
			$q_name2[$ix] = mb_substr($q_name[$ix], 25);
		}
	} else {
		$q_name1[$ix] = $q_name[$ix];
		$q_name2[$ix] = "";
	}
	$ix++;
}
$q_count = $ix;
if ($q_count >= 10) {
} else {
	$q_count = 10;
}

// $qu_commission = str_replace('.', '', $quotation['qu_commission']);
// var_dump(ceil(($qu_commission / 10000) * $qu_price));
// $w_commit =  ceil(($qu_commission / 10000) * $qu_price);
// echo $w_commit;

// $q_alltotal = number_format($qu_price + $w_commit);	// 見積金額合計
// $qu_commit  = number_format($w_commit);		// 割賦手数料
// $qu_price   = number_format($qu_price);		// 商品価格

// ユーザー情報
$sql = sprintf('SELECT * FROM `customer` WHERE c_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $quotation['c_id'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row2 = mysqli_fetch_assoc($record);
$customer['c_name'] = $row2['c_name']; 		// クライアント名
if ($row2['c_attr'] == "法人") {
	$customer['c_attr'] = "御中";
} else {
	$customer['c_attr'] = "様";
}

// 顧客情報
$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row7 = mysqli_fetch_assoc($record);
// if ($row7['u_type'] == "法人") {
//	$user['u_company']	= $row7['u_company'];	// 見積元名
//	$user['p_name']		= $row7['u_person'];	// 見積元担当者名
//	$user['p_postal']	= $row7['u_postal'];	// 見積元郵便番号
//	$user['p_address1']	= $row7['u_address1'];	// 見積元住所1
//	$user['p_address2']	= $row7['u_address2'];	// 見積元住所2
//	$user['p_address3']	= $row7['u_address3'];	// 見積元住所3
//	$user['p_tel']		= $row7['u_tel'];	// 見積元電話番号
// } else {
//	$user['u_company']	= $row7['p_name'];	// 見積元名
//	$user['p_name']		= "";			// 見積元担当者名
//	$user['p_postal']	= $row7['p_postal'];	// 見積元郵便番号
//	$user['p_address1']	= $row7['p_address1'];	// 見積元住所1
//	$user['p_address2']	= $row7['p_address2'];	// 見積元住所2
//	$user['p_address3']	= $row7['p_address3'];	// 見積元住所3
//	$user['p_tel']		= $row7['p_tel'];	// 見積元電話番号
// }

// 顧客情報
$sql = sprintf('SELECT * FROM `q_memo` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row4 = mysqli_fetch_assoc($record);
$memo['q_memo']	= $row4['q_memo'];	// メモ

// $sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
//	mysqli_real_escape_string($db, $_SESSION['loginID'])
// );
// $record = mysqli_query($db, $sql) or die(mysqli_error($db));
// $row7 = mysqli_fetch_assoc($record);
?>
<?php require_once(__DIR__ . '/../../common/header.php'); ?>
	<main class="customer_data">
		<div class="main_inner">
			<?php require_once(__DIR__ .'/../../common/grobal-menu.php'); ?>
			<div class="main_wrap">
				<div class="main_title">
					<div class="all_wrapper">
						<div class="main_pankuzu">
							<ul>
								<!-- <li><span><a href="/manage/data/" class="text_link">顧客データ</a></span></li> -->
								<li><span><a href="/manage/estimates/" class="text_link">見積書一覧</a></span></li>
								<!-- <li><span><a href="/manage/estimates/detail/" class="text_link">見積書詳細</a></span></li> -->
								<li><span>見積書詳細</span></li>
							</ul>
						</div>
						<div class="main_title_inner">
							<div class="main_title_top">
								<span id="delete_box"><a href="" onClick="del_qu(<?php echo $qu_id; ?>);return false;"><img src="/manage/img/icon_dust_gray.png" alt="ゴミ箱のアイコン"></a></span>
								<p class="title">見積書<br><?= h($quotation['qu_name']); ?></p>
							</div>
							<div class="main_title_bottom">
								<div class="position_right_inner">
									<form action="" method="post" enctype="multipart/form-data">
										<ul class="cf fourBox">
											<li><span><input type="submit" name="pdfout" value="出力">出力</span></li>
											<?php if ($row7['exeFlag'] == 0) { ?>
											<li><a href="/manage/estimates/edit/?id=<?php echo $qu_id; ?>">見積書編集</a></li>
											<?php } else { ?>
											<li><a class="disable">見積書編集</a></li>
											<?php } ?>
											<li><a href="/manage/estimates/simulation/?id=<?php echo $qu_id; ?>">シミュレーション</a></li>
											<?php if ($row7['exeFlag'] == 0) { ?>
											<li><a href="/manage/invoices/detail/?id=<?php echo $qu_id; ?>">請求書反映</a></li>
											<?php } else { ?>
											<li><a class="disable">請求書反映</a></li>
											<?php } ?>
										</ul>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="main_content">
					<div class="all_wrapper sp_all">
						<div class="main_content_inner">
							<div class="main_content_wrap">
								<div class="estimates_content">
									<div class="estimates_content_wrap">
										<div class="estimates_content_inner">
											<!-- PDFテンプレート -->
											<div class="estimates_headline">
												<p class="sub_title">見積書情報</p>
												<div class="estimates_table_field">
													<table>
														<tr>
															<th class="sp">見積書番号</th>
															<td class="text-right sp-text-left"><span class="estimates_num">No. <?= h($quotation['qu_no']); ?></span></td>
														</tr>
														<tr class="pc">
															<th class="estimates_title text-center">見積書</th>
														</tr>
														<tr>
															<th class="sp">日付</th>
															<td class="text-right sp-text-left"><span class="estimates_data"><?= $updated; ?></span></td>
														</tr>
													</table>
												</div>
											</div>
											<div class="estimates_company cf">
												<div class="estimates_clients">
													<div class="estimates_clients_inner">
														<div class="estimates_table_field">
															<table class="contract_field">
																<tbody>
																	<tr>
																		<th class="sp">顧客名</th>
																		<td colspan="2" class="bold"><span class="clients_name"><?= h($customer['c_name']); ?></span><?php echo $customer['c_attr']; ?></td>
																	</tr>
																	<tr class="pc">
																		<th colspan="2" class="padding-bottom">下記の通り御見積り申し上げ、手配いたします。</th>
																	</tr>
																	<tr>
																		<?php if ($quotation['qu_bunrui'] == "工事") { ?>
																			<th class="padding-bottom">工事名称</th>
																		<?php } else { ?>
																			<th class="padding-bottom">商品名称</th>
																		<?php } ?>
																		<td class="padding-bottom"><?= h($quotation['qu_name']); ?></td>
																	</tr>
																	<tr>
																		<?php if ($quotation['qu_bunrui'] == "工事") { ?>
																			<th class="padding-bottom">工事場所</th>
																		<?php } else { ?>
																			<th class="padding-bottom">納品場所</th>
																		<?php } ?>
																		<td class="padding-bottom"><?= h($quotation['qu_location']); ?></td>
																	</tr>
																	<tr>
																		<th>支払条件</th>
																		<td>分割払い</td>
																	</tr>
																	<tr>
																		<th>支払日</th>
																		<td>毎月<?= h($quotation['qu_paymentDate']); ?></td>
																	</tr>
																	<tr>
																		<th>受渡期日</th>
																		<td><?= h($quotation['qu_deliveryDate']); ?></td>
																	</tr>
																	<tr>
																		<th>見積有効期限</th>
																		<td>発行日より10日間</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												<div class="estimates_our">
													<p class="sub_title">会社情報</p>
													<div class="estimates_table_field">
														
														<div class="estimates_our_inner">
															<table>
																<tr>
																	<th class="sp">会社名</th>
																	<td class="bold">
																		<?php echo h($user['u_company']); ?>
																	</td>
																</tr>
																<tr>
																	<th class="sp">担当者名</th>
																	<td class="padding-bottom"><?php echo $user['p_name']; ?></td>
																</tr>
																<tr>
																	<th class="sp">住所1</th>
																	<td>〒<?= h($user['p_postal']); ?></td>
																</tr>
																<?php if (!empty($user['p_address1'])) { ?>
																<tr>
																	<th class="sp">住所1</th>
																	<td><?= h($user['p_address1']);?></td>
																</tr>
																<?php } ?>
																<?php if (!empty($user['p_address2'])) { ?>
																<tr>
																	<th class="sp">住所2</th>
																	<td><?= h($user['p_address2']);?></td>
																</tr>
																<?php } ?>
																<?php if (!empty($user['p_address3'])) { ?>
																<tr>
																	<th class="sp">住所3</th>
																	<td><?= h($user['p_address3']);?></td>
																</tr>
																<?php } ?>
																<tr>
																	<th class="sp">電話番号</th>
																	<td>TEL　<?= h($user['p_tel']);?></td>
																</tr>
															</table>
														</div>
													</div>
												</div>
											</div>
											<div class="estimates_table">
												<div class="estimates_table_inner">
													<p class="sub_title">見積詳細</p>
													<div class="estimates_table_field">
														<div class="estimates_table_box">
															<table class="estimates_table_title">
																<tr>
																	<th><span class="total_title">見積金額合計</span><span class="total_nums">¥<?= h($quotation['q_alltotal']); ?></span></th>
																	<td><span class="tax_name">(内消費税等/10%)</span><span class="tax_num">¥<?= h($quotation['q_cost']); ?></span></td>
																</tr>
															</table>
															<p class="pc">《分割払い内容》</p>
															<table class="estimates_table_detail">
																<tr>
																	<?php if ($quotation['qu_bunrui'] == "工事") { ?>
																		<th class="text-center sp-text-left">工事価格</th>
																	<?php } else { ?>
																		<th class="text-center sp-text-left">商品価格</th>
																	<?php } ?>
																	<td class="text-right sp-text-left">¥<?= h($quotation['qu_price']); ?></td>
																	<th class="text-center sp-text-left">割賦手数料</th>
																	<td class="text-right sp-text-left">¥<?= h($quotation['qu_commit']); ?></td>
																</tr>
															</table>
															<table class="estimates_table_detail">
																<tr>
																	<th class="text-center sp-text-left">初回お支払額</th>
																	<td class="text-right sp-text-left">¥<?= h($quotation['qu_initPayAmount']); ?></td>
																	<th class="text-center sp-text-left">月々お支払額</th>
																	<td class="text-right sp-text-left">¥<?= h($quotation['qu_amount_pay']); ?></td>
																</tr>
																<tr>
																	<th class="text-center sp-text-left">分割回数</th>
																	<td class="text-center sp-text-left"><?= h($quotation['qu_installments']); ?>回</td>
																	<th class="text-center sp-text-left">頭金</th>
																	<td class="text-right sp-text-left">¥<?= h($quotation['qu_deposit']); ?></td>
																</tr>
																<tr>
																	<th class="text-center sp-text-left">返済開始予定年月</th>
																	<td class="text-center sp-text-left"><?= h($quotation['qu_startDate']); ?></td>
																	<th class="text-center sp-text-left">返済終了予定年月</th>
																	<td class="text-center sp-text-left"><?= h($quotation['qu_endDate']); ?></td>
																</tr>
															</table>
														</div>
													</div>
													<p class="sub_title">見積項目</p>
													<div class="estimates_table_field">

														<div class="estimates_table_box">

															<table class="estimates_table_item">
																<thead>
																	<tr>
																		<th>No.</th>
																		<th>適用</th>
																		<th>数量</th>
																		<th>単位</th>
																		<th>単価(税抜き)</th>
																		<th>金額(税抜き)</th>
																	</tr>
																</thead>
																<tbody>
																	<?php for($ix=0; $ix<$q_count; $ix++) { ?>
																		<tr>
																			<td><span class="item_num"><span class="sp">No.</span><?= h($ix + 1); ?></span></td>
																			<td class="item_name">
																				<span class="item_detail_title">適用</span>
																				<span class="item_detail_name"><?php if (!empty($q_name1[$ix])) {echo $q_name1[$ix];} ?></span>
																				<span class="item_detail_name"><?php if (!empty($q_name2[$ix])) {echo $q_name2[$ix];} ?></span>
																			</td>
																			<td>
																				<span class="item_detail_title">数量</span>
																				<span class="item_detail_name"><?php if (!empty($q_number[$ix])) {echo $q_number[$ix];} ?></span>
																			</td>
																			<td>
																				<span class="item_detail_title">単位</span>
																				<span class="item_detail_name" style="white-space: nowrap"><?php if (!empty($q_unit[$ix])) {echo $q_unit[$ix];} ?></span>
																			</td>
																			<td>
																				<span class="item_detail_title">単価(税抜き)</span>
																				<span class="item_detail_name"><?php if (!empty($q_price[$ix])) {echo $q_price[$ix];} ?></span>
																			</td>
																			<td>
																				<span class="item_detail_title">金額</span>
																				<span class="item_detail_name"><?php if (!empty($q_total[$ix])) {echo $q_total[$ix];} ?></span>
																			</td>
																		</tr>
																	<?php } ?>
																	<tr class="shokei">
																		<td colspan="2"></td>
																		<td colspan="3"><p style="line-height:180%">小計<br>(内消費税)</p></td>
																		<td><p style="line-height:180%">¥<?= h($quotation['syohkei']) ;?><br>(¥<?= h($quotation['q_cost']) ;?>)</p></td>
																	</tr>
																	<tr class="remarks">
																		<td class="text-left" colspan="6">
																			<p>【備考】</p>
																			<p style="line-height:140%"><?php echo nl2br($memo['q_memo']); ?></p>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
											<div class="estimates_signature pc">
												<table>
													<tr>
														<td>上記内容及び裏面「割賦販売契約約款」を確認し、説明を受けて、 発注した工事の代金につき、上記の通りお支払いします。</td>
														<td></td>
														<td><span class="signature_name">お名前</span><span class="signature_in">㊞</span></td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/object-fit-images/3.2.3/ofi.js"></script>
	<script src="/manage/common/js/customer-data.js"></script>
	<script>
		function del_qu(qu_id) {
			if (window.confirm("見積書／請求書データを削除します。\n宜しいですか？")) {
				location.href = "/manage/estimates/detail/func_del.php?id=" + qu_id;
			}
		}
	</script>
</body>
</html>

