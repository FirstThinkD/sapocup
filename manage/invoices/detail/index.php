<?php
session_start();
require_once(__DIR__ . '/../../../common/dbconnect.php');

if ($_SESSION['loginID'] == "") {
	header("Location:/");
	exit();
}

// 曜日の設定
$week = ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'];
$date = date('w');

if ($_GET['id'] == "") {
	header("Location:/manage/data/");
	exit();
} else {
	$get_qu_id = $_GET['id'];
	$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $get_qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row1 = mysqli_fetch_assoc($record)) {
		// データあり
		$sql = sprintf('UPDATE `quotation` SET invoice_flag=1 WHERE qu_id="%d"',
			mysqli_real_escape_string($db, $get_qu_id)
		);
		mysqli_query($db, $sql) or die(mysqli_error($db));
	}
	// データなし
	$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $get_qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row3 = mysqli_fetch_assoc($record);

	$sql = sprintf('SELECT * FROM `q_memo` WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $get_qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row4 = mysqli_fetch_assoc($record);

	$sql = sprintf('SELECT * FROM `q_items` WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $get_qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$ix = 0;
	while($row0 = mysqli_fetch_assoc($record)) {
		$qu_id[$ix]   = $row0['qu_id'];
		$q_name[$ix]  = $row0['q_name'];
		$q_number[$ix]= $row0['q_number'];
		$q_unit[$ix]  = $row0['q_unit'];
		$q_price[$ix] = $row0['q_price'];
		$q_total[$ix] = $row0['q_total'];

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
	$item_count = $ix;
	if ($item_count >= 10) {
	} else {
		$item_count = 10;
	}

	$sql = sprintf('SELECT * FROM `customer` WHERE c_id="%d"',
		mysqli_real_escape_string($db, $row3['c_id'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row6 = mysqli_fetch_assoc($record);

	if ($row6['c_attr'] == "法人") {
		$customer['c_attr'] = "御中";
	} else {
		$customer['c_attr'] = "様";
	}

	$chk_date = date('Y-m');
	$sql = sprintf('SELECT * FROM `data_quo` WHERE qu_id="%d" AND
		depo_date LIKE "%s%%"',
		mysqli_real_escape_string($db, $row3['qu_id']),
		mysqli_real_escape_string($db, $chk_date)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row7 = mysqli_fetch_assoc($record)) {
	} else {
		$sql = sprintf('SELECT * FROM `data_quo` WHERE qu_id="%d" AND delFlag=0 ORDER BY yyyymm LIMIT 1',
			mysqli_real_escape_string($db, $row3['qu_id'])
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$row7 = mysqli_fetch_assoc($record);
	}

	$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d"',
		mysqli_real_escape_string($db, $row3['u_id'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row8 = mysqli_fetch_assoc($record);

//	if ($row8['u_type'] == "法人") {
//		$user_name     = $row8['u_company'];
//		$user_person   = $row8['u_person'];
//		$user_postal   = $row8['u_postal'];
//		$user_address1 = $row8['u_address1'];
//		$user_address2 = $row8['u_address2'];
//		$user_address3 = $row8['u_address3'];
//		$user_tel      = $row8['u_tel'];
//	} else {
//		$user_name     = $row8['p_name'];
//		$user_postal   = $row8['p_postal'];
//		$user_address1 = $row8['p_address1'];
//		$user_address2 = $row8['p_address2'];
//		$user_address3 = $row8['p_address3'];
//		$user_tel      = $row8['p_tel'];
//	}

	$user_name     = $row3['in_companyName'];
	$user_person   = "";
	$user_postal   = $row3['in_postal'];
	$user_address1 = $row3['in_address1'];
	$user_address2 = $row3['in_address2'];
	$user_address3 = $row3['in_address3'];
	$user_tel      = $row3['in_tel'];

	$qu_no = sprintf("%06d", $row3['qu_id']);

	if ($row3['qu_paymentDate'] == "末日") {
		$depo_date = $row3['qu_paymentDate'];
	} else {
		$depo_date = date('d日', strtotime($row7['depo_date']));
	}
	// $w_depo_date = date('w', strtotime($row7['depo_date']));

	$qu_deliveryDate = date('Y年m月d日', strtotime($row3['qu_deliveryDate']));
	$w_qu_deliveryDate = date('w', strtotime($row3['qu_deliveryDate']));

	$qu_startDate = date('Y年m月', strtotime($row3['qu_startDate']));
	$qu_endDate   = date('Y年m月', strtotime($row3['qu_endDate']));

	if ($row3['qu_initPayAmount'] == "") {
		$qu_initPayAmount = "";
	} else {
		$qu_initPayAmount = "¥". number_format($row3['qu_initPayAmount']);
	}

	if ($row3['qu_amount_pay'] == "") {
		$qu_amount_pay = "";
	} else {
		$qu_amount_pay = "¥". number_format($row3['qu_amount_pay']);
	}

	if ($row3['qu_deposit'] == "") {
		$qu_deposit = "";
	} else {
		$qu_deposit = "¥". number_format($row3['qu_deposit']);
	}

	if ($row3['updated'] != "") {
		$updated = date('Y年m月d日', strtotime($row3['updated']));
	} else {
		$updated = date('Y年m月d日');
	}
}

if (!empty($_POST['pdfout']) && $_POST['pdfout'] == "出力") {
	$_SESSION['da_pdfbox'] = array();
	$org_ym = date('Ym');

	$sql = sprintf('SELECT * FROM `data_quo` WHERE qu_id="%d" AND yyyymm="%s" AND delFlag=0',
		mysqli_real_escape_string($db, $get_qu_id),
		mysqli_real_escape_string($db, $org_ym)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row0 = mysqli_fetch_assoc($record)) {
	} else {
		$sql = sprintf('SELECT * FROM `data_quo` WHERE qu_id="%d" AND delFlag=0 ORDER BY yyyymm LIMIT 1',
			mysqli_real_escape_string($db, $get_qu_id)
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		$row0 = mysqli_fetch_assoc($record);
	}

	$_SESSION['da_pdfbox'][0][0] = $row0['da_id'];
	$_SESSION['da_pdfbox'][0][1] = $row0['depo_date'];
	$_SESSION['da_pdfbox'][0][2] = 1;
	$_SESSION['da_pdfbox'][0][3] = 1;	// 請求書

	$url = '<script>if (window.confirm("請求書のPDFを出力します。\n宜しいですか？")) { window.open("/manage/tcpdf/out-pdf3.php", "_blank");}</script>';
	echo $url;
}

$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row7 = mysqli_fetch_assoc($record);
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
								<li><span><a href="/manage/invoices/" class="text_link">請求書一覧</a></span></li>
								<li><span>請求書詳細</span></li>
							</ul>
						</div>
						<div class="main_title_inner">
							<div class="main_title_top">
								<span id="delete_box"><a href="" onClick="del_qu(<?php echo $get_qu_id; ?>);return false;"><img src="/manage/img/icon_dust_gray.png" alt="ゴミ箱のアイコン"></a></span>
								<p class="title">請求書<br><?php echo $row3['qu_name']; ?></p>
							</div>
							<div class="main_title_bottom">
								<div class="position_right_inner">
									<form action="" method="post" enctype="multipart/form-data">
										<ul class="cf">
											<li><span><input type="submit" name="pdfout" value="出力">出力</span></li>
											<?php if ($row7['exeFlag'] == 0) { ?>
											<li><a href="/manage/invoices/edit/?id=<?php echo $get_qu_id; ?>">請求書編集</a></li>
											<?php } else { ?>
											<li><a class="disable">請求書編集</a></li>
											<?php } ?>
											<li><a href="/manage/estimates/simulation/?id=<?php echo $get_qu_id; ?>">シミュレーション</a></li>
											<!-- <li><a href="/manage/invoices/">請求書反映</a></li> -->
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
												<p class="sub_title">請求書情報</p>
												<div class="estimates_table_field">
													<table>
														<tr>
															<th class="sp">請求書番号</th>
															<td class="text-right sp-text-left"><span class="estimates_num">No. <?php echo $qu_no; ?></span></td>
														</tr>
														<tr class="pc">
															<th class="estimates_title text-center">請求書</th>
														</tr>
														<tr>
															<th class="sp">日付</th>
															<td class="text-right sp-text-left"><span class="estimates_data"><?php echo $updated; ?></span></td>
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
																		<td colspan="2" class="bold"><span class="clients_name"><?php echo $row6['c_name']; ?></span><?php echo $customer['c_attr']; ?></td>
																	</tr>
																	<tr class="pc">
																		<th colspan="2" class="padding-bottom">下記の通り御請求申し上げ、手配いたします。</th>
																	</tr>
																	<tr>
																		<?php if ($row3['qu_bunrui'] == "工事") { ?>
																			<th class="padding-bottom">工事名称</th>
																		<?php } else { ?>
																			<th class="padding-bottom">商品名称</th>
																		<?php } ?>
																		<td class="padding-bottom"><?php echo $row3['qu_name']; ?></td>
																	</tr>
																	<tr>
																		<?php if ($row3['qu_bunrui'] == "工事") { ?>
																			<th class="padding-bottom">工事場所</th>
																		<?php } else { ?>
																			<th class="padding-bottom">納品場所</th>
																		<?php } ?>
																		<td class="padding-bottom"><?php echo $row3['qu_location']; ?></td>
																	</tr>
																	<tr>
																		<th>支払条件</th>
																		<td>分割払い</td>
																	</tr>
																	<tr>
																		<th>支払日</th>
																		<td>毎月<?php echo $depo_date; ?></td>
																	</tr>
																	<tr>
																		<th>受渡期日</th>
																		<td><?php echo $qu_deliveryDate; ?></td>
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
																	<td class="bold"><?php echo $user_name; ?></td>
																</tr>
																<?php if ($row8['u_type'] == "法人") { ?>
																<tr>
																	<th class="sp">担当者名</th>
																	<td class="padding-bottom"><?php echo $user_person; ?></td>
																</tr>
																<?php } else { ?>
																<tr>
																	<th class="sp">担当者名</th>
																	<td class="padding-bottom"></td>
																</tr>
																<?php } ?>
																<tr>
																	<th class="sp">住所1</th>
																	<td>〒<?php echo $user_postal; ?></td>
																</tr>
																<tr>
																	<th class="sp">住所1</th>
																	<td><?php echo $user_address1; ?></td>
																</tr>
																<tr>
																	<th class="sp">住所2</th>
																	<td><?php echo $user_address2; ?></td>
																</tr>
																<tr>
																	<th class="sp">住所3</th>
																	<td><?php echo $user_address3; ?></td>
																</tr>
																<tr>
																	<th class="sp">電話番号</th>
																	<td>TEL　<?php echo $user_tel; ?></td>
																</tr>
															</table>
														</div>
													</div>
												</div>
											</div>
											<div class="estimates_table">
												<div class="estimates_table_inner">
													<p class="sub_title">請求詳細</p>
													<div class="estimates_table_field">
														<div class="estimates_table_box">
															<table class="estimates_table_title">
																<tr>
																	<th><span class="total_title">請求金額合計</span><span class="total_nums">¥<?php echo number_format($row3['q_alltotal']); ?></span></th>
																	<td><span class="tax_name">(内消費税等/10%)</span><span class="tax_num">¥<?php echo number_format($row3['q_cost']); ?></span></td>
																</tr>
															</table>
															<p class="pc">《分割払い内容》</p>
															<table class="estimates_table_detail">
																<tr>
																	<?php if ($row3['qu_bunrui'] == "工事") { ?>
																		<th class="text-center sp-text-left">工事価格</th>
																	<?php } else { ?>
																		<th class="text-center sp-text-left">商品価格</th>
																	<?php } ?>
																	<td class="text-right sp-text-left">¥<?php echo number_format($row3['qu_price']); ?></td>
																	<th class="text-center sp-text-left">割賦手数料</th>
																	<td class="text-right sp-text-left">¥<?php echo number_format($row3['qu_commit']); ?></td>
																</tr>
															</table>
															<table class="estimates_table_detail">
																<tr>
																	<th class="text-center sp-text-left">初回お支払額</th>
																	<td class="text-right sp-text-left"><?php echo $qu_initPayAmount; ?></td>
																	<th class="text-center sp-text-left">月々お支払額</th>
																	<td class="text-right sp-text-left"><?php echo $qu_amount_pay; ?></td>
																</tr>
																<tr>
																	<th class="text-center sp-text-left">分割回数</th>
																	<td class="text-center sp-text-left"><?php echo $row3['qu_installments']; ?>回</td>
																	<th class="text-center sp-text-left">頭金</th>
																	<td class="text-right sp-text-left"><?php echo $qu_deposit; ?></td>
																</tr>
																<tr>
																	<th class="text-center sp-text-left">返済開始予定年月</th>
																	<td class="text-center sp-text-left"><?php echo $qu_startDate; ?></td>
																	<th class="text-center sp-text-left">返済終了予定年月</th>
																	<td class="text-center sp-text-left"><?php echo $qu_endDate; ?></td>
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
																	<?php for($ix=0; $ix<$item_count; $ix++) { ?>
																	<tr>
																		<td><span class="item_num"><span class="sp">No.</span><?php echo ($ix + 1); ?></span></td>
																		<td class="item_name">
																			<span class="item_detail_title">適用</span>
																			<span class="item_detail_name"><?php if (!empty($q_name1[$ix])) {echo $q_name1[$ix];} ?></span>
																			<span class="item_detail_name"><?php if (!empty($q_name2[$ix])) {echo $q_name2[$ix];} ?></span>
																		</td>
																		<td>
																			<span class="item_detail_title">数量</span>
																			<span class="item_detail_name"><?php echo $q_number[$ix]; ?></span>
																		</td>
																		<td>
																			<span class="item_detail_title">単位</span>
																			<span class="item_detail_name" style="white-space: nowrap"><?php if (!empty($q_name[$ix])) {echo $q_unit[$ix];} ?></span>
																		</td>
																		<td>
																			<span class="item_detail_title">単価(税抜き)</span>
																			<?php if (!empty($q_name[$ix])) { ?>
																			<span class="item_detail_name"><?php echo number_format($q_price[$ix]); ?></span>
																			<?php } else { ?>
																			<span class="item_detail_name"></span>
																			<?php } ?>
																		</td>
																		<td>
																			<span class="item_detail_title">金額</span>
																			<?php if (!empty($q_name[$ix])) { ?>
																			<span class="item_detail_name"><?php echo number_format($q_total[$ix]); ?></span>
																			<?php } else { ?>
																			<span class="item_detail_name"></span>
																			<?php } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<tr class="shokei">
																		<td colspan="2"></td>
																		<td colspan="3"><p style="line-height:180%">小計<br>(内消費税)</p></td>
																		<?php if (ctype_digit($row3['syohkei'])) { ?>
																			<td colspan="2">
																				<p style="line-height:180%">
																					¥<?php echo number_format($row3['syohkei']); ?>
																					(¥<?php echo number_format($row3['q_cost']); ?>)
																				</p>
																			</td>
																		<?php } else { ?>
																			<td colspan="2">
																				<p style="line-height:180%">
																					¥<?php echo ($row3['syohkei'] + $row3['q_cost']); ?>
																					(¥<?php echo number_format($row3['q_cost']); ?>)
																				</p>
																			</td>
																		<?php } ?>
																	</tr>
																	<tr class="remarks">
																		<td class="text-left" colspan="6">
																			<p>【備考】</p>
																			<p style="line-height:140%"><?php echo nl2br($row4['q_memo']); ?></p>
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
				location.href = "/manage/invoices/detail/func_del.php?id=" + qu_id;
			}
		}
	</script>
</body>
</html>

