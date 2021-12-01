<?php
session_start();
require_once(__DIR__ . '/../../common/dbconnect.php');
require_once(__DIR__ . '/../common/functions.php');


// ログイン確認
if (empty($_SESSION['loginID'])) {
	header("Location:/login.php");
	exit();
}

/*-----------------------------------------------------------

	チェックボックス - CSV出力

-------------------------------------------------------------*/
if (!empty($_POST['csvout']) && $_POST['csvout'] == "リスト出力") {
	// print_r($_POST);
	// echo "<br>";
	// print_r($_SESSION);
	// exit();

	$_SESSION['cl_chkbox'] = array();
	$_SESSION['cl_chkbox'][0] = 0;
	$iy = 0;
	for($ix=0; $ix<($_POST['count']+1); $ix++) {
		if (!empty($_POST['chkbox'][$ix])) {
			$_SESSION['cl_chkbox'][($iy + 1)] = $_POST['chkbox'][$ix];
			$iy++;
		}
	}

	if ($iy != 0) {
		$_SESSION['cl_chkbox'][0] = $iy;
		// for($ix=1; $ix<$_SESSION['cl_chkbox'][0]; $ix++) {
		//	echo "cl_chkbox=". $_SESSION['cl_chkbox'][$ix]. "<br>";
		// }
		header("Location:/manage/invoices/out-csv.php");
		exit();
	}
}

/*-----------------------------------------------------------

	リスト一覧表示

-------------------------------------------------------------*/
// 請求書情報を取得
$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND invoice_flag=1 AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
// DB情報取得
$ix = 0;
while ($row0 = mysqli_fetch_assoc($record)) {
	$qu_id[$ix]            = $row0['qu_id'];
	// $in_id[$ix]            = $row0['in_id'];
	$c_id[$ix]             = $row0['c_id'];
	$u_id[$ix]             = $row0['u_id'];
	// $qu_bunrui[$ix]        = $row0['qu_bunrui'];
	$qu_custom_name[$ix]   = $row0['qu_custom_name'];
	// $qu_custom_no[$ix]     = $row0['qu_custom_no'];
	$qu_name[$ix]          = $row0['qu_name'];
	// $qu_location[$ix]      = $row0['qu_location'];
	$qu_paymentDate[$ix]   = $row0['qu_paymentDate'];
	$qu_deliveryDate[$ix]  = date('Y年m月d日', strtotime($row0['qu_deliveryDate']));
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
	$q_alltotal[$ix]       = number_format($row0['q_alltotal']);
	// $in_companyName[$ix]   = $row0['in_companyName'];
	// $in_postal[$ix]        = $row0['in_postal'];
	// $in_address1[$ix]      = $row0['in_address1'];
	// $in_address2[$ix]      = $row0['in_address2'];
	// $in_address3[$ix]      = $row0['in_address3'];
	// $in_tel[$ix]           = $row0['in_tel'];
	// $in_email[$ix]         = $row0['in_email'];
	// $in_contactName[$ix]   = $row0['in_contactName'];

	// $customer_id[$ix]      = 10000000 + $c_id[$ix];
	$customer_id[$ix]       = sprintf("%06s", $qu_id[$ix]);

	if ($qu_paymentDate[$ix] == "末日") {
		$qu_paymentDate[$ix] = "毎月". $qu_paymentDate[$ix];
	} else {
		$qu_paymentDate[$ix] = sprintf("毎月%02d日", $qu_paymentDate[$ix]);
	}

	$ix++;
}
$qu_count = $ix;

// 請求書情報を取得
$sql = sprintf('SELECT * FROM customer WHERE u_id="%d"',
	mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));	// データベース上でクエリを実行する
// $tables = mysqli_fetch_fields($record);			// テーブル全ての情報を配列で取得する
$ix = 0;
while ($row0 = mysqli_fetch_assoc($record)) {
	$cust_c_id[$ix]   = $row0['c_id'];
	$cust_u_id[$ix]   = $row0['u_id'];
	$cust_c_name[$ix] = $row0['c_name'];
	$ix++;
}
$c_count = $ix;

for($ix=0; $ix<$qu_count; $ix++) {
	for($iy=0; $iy<$c_count; $iy++) {
		if ($c_id[$ix] == $cust_c_id[$iy]) {
			$cust_name[$ix] = $cust_c_name[$iy];
			break;
		}
	}
}

$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row7 = mysqli_fetch_assoc($record);
?>

<?php require_once(__DIR__ . '../../common/header.php'); ?>
	<main class="customer_data">
		<div class="main_inner">
			<?php require_once(__DIR__ .'../../common/grobal-menu.php'); ?>
			<div class="main_wrap">
				<div class="main_title">
					<div class="all_wrapper sp_all">
						<div class="main_title_inner">
							<div class="main_title_top">
								<p class="title">請求書一覧</p>
							</div>
							<div class="main_title_bottom position_right">
								<div class="position_right_inner">
									<ul class="cf">
										<?php if ($row7['exeFlag'] == 0) { ?>
											<li><a href="/manage/estimates/new/">新規見積書作成</a></li>
										<?php } else { ?>
											<li><a class="disable">新規見積書作成</a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="main_content">
					<div class="all_wrapper sp_all">
						<div class="main_content_inner">
							<form action="" method="post" enctype="multipart/form-data">
								<div class="main_content_wrap">
									<table class="common_table customerdata_table">
										<thead class="common_table_thead">
											<tr class="common_table_tr">
												<th class="allCheck check_submit_button"><input class="check_submit_button" type="checkbox"></th>
												<th>請求書番号<br>名称</th>
												<th>請求先</th>
												<th>支払日</th>
												<th>受渡期日</th>
												<th>総金額</th>
												<th></th>
											</tr>
										</thead>
										<tbody class="common_table_tbody">
											<input type="hidden" name="count" value="<?= $qu_count; ?>">
											<?php for($ix=0; $ix<$qu_count; $ix++) { ?>
												<tr class="common_table_tr">
													<td class="common_table_cell singleCheck">
														<input class="check_submit_button" type="checkbox" name="chkbox[]" value="<?= $qu_id[$ix]; ?>">
													</td>
													<td class="common_table_cell">
														<span class="client_num"><?= $customer_id[$ix]; ?></span>
														<span class="client_name"><a href="/manage/invoices/detail/?id=<?= h($qu_id[$ix]); ?>" class="text_link"><?= h($qu_name[$ix]); ?></a></span>
														<span class="detail_button">詳細</span>
													</td>
													<td class="common_table_cell"><?= h($cust_name[$ix]); ?></a></td>
													<td class="common_table_cell text_right">
														<span class="sp_customerdata_name">支払日</span>
														<span class="customerdata_item"><?= h($qu_paymentDate[$ix]); ?></span>
													</td>
													<td class="common_table_cell text-center">
														<span class="sp_customerdata_name">受渡期日</span>
														<span class="customerdata_item"><?= h($qu_deliveryDate[$ix]); ?></span>
													</td>
													<td class="common_table_cell">
														<span class="sp_customerdata_name">総金額</span>
														<span class="customerdata_item">¥<?= h($q_alltotal[$ix]); ?></span>
													</td>
													<td class="common_table_cell edit_menu">
														<ul class="cf">
															<?php if ($row7['exeFlag'] == 0) { ?>
																<li><a href="/manage/invoices/edit/?id=<?= h($qu_id[$ix]); ?>">編集</a></li>
															<?php } else { ?>
																<li><a class="disable">編集</a></li>
															<?php } ?>
														</ul>
													</td>
												</tr>
											<?php }; ?>
										</tbody>
									</table>
								</div>
								<div id="other_submit_field">
									<div class="all_wrapper cf">
										<div class="other_submit_field_wrap">
											<div id="check_submit_field" class="other_submit_field_content">
												<div class="other_submit_field_inner">
													<p class="field_title">チェックした項目</p>
													<ul class="cf">
														<li><input type="submit" name="csvout" value="リスト出力"></li>
														<!-- <li class="pc"><a href="">請求書出力</a></li>
														<li><a href="">削除</a></li> -->
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="/manage/common/js/customer-data.js"></script>
	<script>
		// 全てをチェック入れるボタン
		$('.allCheck input[type="checkbox"]').on('click', function() {
			var tgt= $(this).parents('.common_table').find('.singleCheck input[type="checkbox"]');
			tgt.prop('checked', this.checked);
		});

		// 全てにチェックが入ると、全てチェックボックスボタン
		var ckBox = $('.singleCheck input[type="checkbox"]');
		ckBox.on('click', function() {
			var ckThisCk = $(this).parents('.common_table').find('.singleCheck input[type="checkbox"]:checked');
			var ckThisDef = $(this).parents('.common_table').find('.singleCheck input[type="checkbox"]');
			var allCk = $(this).parents('.common_table').find('.allCheck input[type="checkbox"]');

			if ($(ckThisCk).length == $(ckThisDef).length){
				allCk.prop('checked', 'checked');
			}else{
				allCk.prop('checked', false);
			}
		});

		// sinclo
		$(function() {
			$('#chatButton').on('click', function(){
				$('#sincloBox').toggleClass('chatOpen');
				$('#sincloBox').data('true');
			})
		})
	</script>
	<script src='https://ws1.sinclo.jp/client/5e7812fdb5a66.js'></script>
</body>
</html>

