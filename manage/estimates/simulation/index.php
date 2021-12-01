<?php
session_start();
require_once(__DIR__ . '/../../../common/dbconnect.php');
require_once(__DIR__ . '/../../common/functions.php');
require_once(__DIR__ . '/sim_func.php');

if (empty($_SESSION['loginID'])) {
	header("Location:/");
	exit();
}

if (empty($_SESSION['sim_err'])) {
	$_SESSION['sim_err'] = "";
}

if (empty($_GET['id'])) {
	header("Location:/");
	exit();
} else {
	$qu_id = $_GET['id'];
	$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row3 = mysqli_fetch_assoc($record);

	$sql = sprintf('SELECT * FROM `simulation` WHERE qu_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row0 = mysqli_fetch_assoc($record)) {
		// // データあり
		// $pt_number = $row0['pt_number'];
		// if ($row0['pt_number'] == 1) {
		// 	$sql = sprintf('UPDATE `simulation` SET qu_price="%s",
		// 		qu_startDate="%s", pt1_commit="%s", pt1_deposit="%s",
		// 		pt1_commission="%s", pt1_initPayAmount="%s", pt1_amount_pay="%s",
		// 		pt1_installments="%s", pt1_alltotal="%s" WHERE qu_id="%d"',
		// 		mysqli_real_escape_string($db, $row3['qu_price']),	    // 商品価格
		// 		mysqli_real_escape_string($db, $row3['qu_startDate']),	    // 実行日
		// 		mysqli_real_escape_string($db, $row3['qu_commit']),	    // 事務管理手数料総額
		// 		mysqli_real_escape_string($db, $row3['qu_deposit']),	    // 頭金
		// 		mysqli_real_escape_string($db, $row3['qu_commission']),    // 事務管理手数料率
		// 		mysqli_real_escape_string($db, $row3['qu_initPayAmount']), // 初回お支払額
		// 		mysqli_real_escape_string($db, $row3['qu_amount_pay']),    // 月々お支払額
		// 		mysqli_real_escape_string($db, $row3['qu_installments']),  // 分割回数
		// 		mysqli_real_escape_string($db, $row3['q_alltotal']),	    // 総支払額
		// 		mysqli_real_escape_string($db, $qu_id)
		// 	);
		// 	mysqli_query($db,$sql) or die(mysqli_error($db));
		// } else if ($row0['pt_number'] == 2) {
		// 	$sql = sprintf('UPDATE `simulation` SET qu_price="%s",
		// 		qu_startDate="%s", pt2_commit="%s", pt2_deposit="%s",
		// 		pt2_commission="%s", pt2_initPayAmount="%s", pt2_amount_pay="%s",
		// 		pt2_installments="%s", pt2_alltotal="%s" WHERE qu_id="%d"',
		// 		mysqli_real_escape_string($db, $row3['qu_price']),	    // 商品価格
		// 		mysqli_real_escape_string($db, $row3['qu_startDate']),	    // 実行日
		// 		mysqli_real_escape_string($db, $row3['qu_commit']),	    // 事務管理手数料総額
		// 		mysqli_real_escape_string($db, $row3['qu_deposit']),	    // 頭金
		// 		mysqli_real_escape_string($db, $row3['qu_commission']),    // 事務管理手数料率
		// 		mysqli_real_escape_string($db, $row3['qu_initPayAmount']), // 初回お支払額
		// 		mysqli_real_escape_string($db, $row3['qu_amount_pay']),    // 月々お支払額
		// 		mysqli_real_escape_string($db, $row3['qu_installments']),  // 分割回数
		// 		mysqli_real_escape_string($db, $row3['q_alltotal']),	    // 総支払額
		// 		mysqli_real_escape_string($db, $qu_id)
		// 	);
		// 	mysqli_query($db,$sql) or die(mysqli_error($db));
		// } else {
		// 	$sql = sprintf('UPDATE `simulation` SET qu_price="%s",
		// 		qu_startDate="%s", pt3_commit="%s", pt3_deposit="%s",
		// 		pt3_commission="%s", pt3_initPayAmount="%s", pt3_amount_pay="%s",
		// 		pt3_installments="%s", pt3_alltotal="%s" WHERE qu_id="%d"',
		// 		mysqli_real_escape_string($db, $row3['qu_price']),	    // 商品価格
		// 		mysqli_real_escape_string($db, $row3['qu_startDate']),	    // 実行日
		// 		mysqli_real_escape_string($db, $row3['qu_commit']),	    // 事務管理手数料総額
		// 		mysqli_real_escape_string($db, $row3['qu_deposit']),	    // 頭金
		// 		mysqli_real_escape_string($db, $row3['qu_commission']),    // 事務管理手数料率
		// 		mysqli_real_escape_string($db, $row3['qu_initPayAmount']), // 初回お支払額
		// 		mysqli_real_escape_string($db, $row3['qu_amount_pay']),    // 月々お支払額
		// 		mysqli_real_escape_string($db, $row3['qu_installments']),  // 分割回数
		// 		mysqli_real_escape_string($db, $row3['q_alltotal']),	    // 総支払額
		// 		mysqli_real_escape_string($db, $qu_id)
		// 	);
		// 	mysqli_query($db,$sql) or die(mysqli_error($db));
		// }
	} else {
		if ($_GET['id2'] == "12345678") {
			header("Location:/manage/estimates/new/simulation.php?id=". $qu_id);
			exit();
		}
		// データなし
		$pt_number = 1;
		$sql = sprintf('INSERT INTO `simulation` SET qu_id="%d", c_id="%d",
			u_id="%d", pt_number=1, qu_price="%s", qu_startDate="%s",
			pt1_commit="%s", pt2_commit="%s", pt3_commit="%s",
			pt1_deposit="%s", pt2_deposit="%s", pt3_deposit="%s",
			pt1_commission="%s", pt2_commission="%s", pt3_commission="%s",
			pt1_initPayAmount="%s", pt2_initPayAmount="%s", pt3_initPayAmount="%s",
			pt1_amount_pay="%s", pt2_amount_pay="%s", pt3_amount_pay="%s",
			pt1_installments="%s", pt2_installments="%s", pt3_installments="%s",
			pt1_alltotal="%s", pt2_alltotal="%s", pt3_alltotal="%s"',
			mysqli_real_escape_string($db, $_GET['id']),
			mysqli_real_escape_string($db, $row3['c_id']),
			mysqli_real_escape_string($db, $row3['u_id']),
			mysqli_real_escape_string($db, $row3['qu_price']),		// 商品価格
			mysqli_real_escape_string($db, $row3['qu_startDate']),		// 実行日
			mysqli_real_escape_string($db, $row3['qu_commit']),		// 事務管理手数料総額
			mysqli_real_escape_string($db, $row3['qu_commit']),		// 事務管理手数料総額
			mysqli_real_escape_string($db, $row3['qu_commit']),		// 事務管理手数料総額
			mysqli_real_escape_string($db, $row3['qu_deposit']),		// 頭金
			mysqli_real_escape_string($db, $row3['qu_deposit']),		// 頭金
			mysqli_real_escape_string($db, $row3['qu_deposit']),		// 頭金
			mysqli_real_escape_string($db, $row3['qu_commission']),		// 事務管理手数料率
			mysqli_real_escape_string($db, $row3['qu_commission']),		// 事務管理手数料率
			mysqli_real_escape_string($db, $row3['qu_commission']),		// 事務管理手数料率
			mysqli_real_escape_string($db, $row3['qu_initPayAmount']),	// 初回お支払額
			mysqli_real_escape_string($db, $row3['qu_initPayAmount']),	// 初回お支払額
			mysqli_real_escape_string($db, $row3['qu_initPayAmount']),	// 初回お支払額
			mysqli_real_escape_string($db, $row3['qu_amount_pay']),		// 月々お支払額
			mysqli_real_escape_string($db, $row3['qu_amount_pay']),		// 月々お支払額
			mysqli_real_escape_string($db, $row3['qu_amount_pay']),		// 月々お支払額
			mysqli_real_escape_string($db, $row3['qu_installments']),	// 分割回数
			mysqli_real_escape_string($db, $row3['qu_installments']), 	// 分割回数
			mysqli_real_escape_string($db, $row3['qu_installments']),	// 分割回数
			mysqli_real_escape_string($db, $row3['q_alltotal']),		// 総支払額
			mysqli_real_escape_string($db, $row3['q_alltotal']),		// 総支払額
			mysqli_real_escape_string($db, $row3['q_alltotal'])		// 総支払額
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));
	}

	/*-----------------------------------------------------------

		DBよりシミュレーション情報を取得

	-------------------------------------------------------------*/
	$sql = sprintf('SELECT * FROM `simulation` WHERE qu_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $_GET['id'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row7 = mysqli_fetch_assoc($record);

	// 3桁ごとにカンマを追加する
	$pt1_amount_pay = number_format((int) $row7['pt1_amount_pay']);	// 月々お支払額
	$qu_price 	= number_format((int) $row7['qu_price']); 	// ご利用予定金額
	$pt1_deposit 	= number_format((int) $row7['pt1_deposit']); 	// 頭金
	$pt1_commit 	= number_format((int) $row7['pt1_commit']); 	// 割賦手数料総額
	$pt1_alltotal 	= number_format((int) $row7['pt1_alltotal']); 	// 総支払額
	$pt2_deposit 	= number_format((int) $row7['pt2_deposit']); 	// 頭金
	$pt2_commit 	= number_format((int) $row7['pt2_commit']); 	// 割賦手数料総額
	$pt2_alltotal 	= number_format((int) $row7['pt2_alltotal']); 	// 総支払額
	$pt3_deposit 	= number_format((int) $row7['pt3_deposit']); 	// 頭金
	$pt3_commit 	= number_format((int) $row7['pt3_commit']); 	// 割賦手数料総額
	$pt3_alltotal 	= number_format((int) $row7['pt3_alltotal']); 	// 総支払額

	// 商品価格
	if (ctype_digit($row7['qu_price'])) {
		$qu_price = number_format($row7['qu_price']);
	} else {
		$qu_price = $row7['qu_price'];
	}

	$pt1_out = partition($row3['qu_paymentDate'], $row7['qu_startDate'], $row7['pt1_installments'], $row7['pt1_alltotal'], $row7['pt1_deposit'], $row7['pt1_initPayAmount']);
	$pt2_out = partition($row3['qu_paymentDate'], $row7['qu_startDate'], $row7['pt2_installments'], $row7['pt2_alltotal'], $row7['pt2_deposit'], $row7['pt2_initPayAmount']);
	$pt3_out = partition($row3['qu_paymentDate'], $row7['qu_startDate'], $row7['pt3_installments'], $row7['pt3_alltotal'], $row7['pt3_deposit'], $row7['pt3_initPayAmount']);

	// echo "pt1=". $pt1_out[0][5]. " pt2=". $pt2_out[0][5]. " pt3=". $pt3_out[0][5];
	// 実行日
	$qu_startDate = date("Y年m月d日", strtotime($row7['qu_startDate']));

	// 事務管理手数料総額
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
}

$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row8 = mysqli_fetch_assoc($record);

$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row9 = mysqli_fetch_assoc($record);
?>
<?php require_once(__DIR__ . '/../../common/header.php'); ?>
	<main class="customer_data">
		<div class="main_inner">
			<?php require_once(__DIR__ . '/../../common/grobal-menu.php'); ?>
			<div class="main_wrap">
				<div class="main_title">
					<div class="all_wrapper sp_all">
						<div class="main_pankuzu">
							<ul>
								<!-- <li><span><a href="/manage/data/" class="text_link">顧客データ</a></span></li> -->
								<li><span><a href="/manage/estimates/" class="text_link">見積書一覧</a></span></li>
								<li><span><a href="/manage/estimates/detail/?id=<?php echo $qu_id; ?>" class="text_link">見積書詳細</a></span></li>
								<li><span>シミュレーション</span></li>
							</ul>
						</div>
						<div class="main_title_inner">
							<div class="main_title_top">
								<span class="date">シミュレーション</span>
								<p class="title"><?php echo $row9['qu_name']; ?></p>
							</div>
							<?php if ($_SESSION['sim_err'] == "UPOK") { ?>
								<p class="error_message">※見積書を更新しました。</p>
								<?php $_SESSION['sim_err'] = ""; ?>
							<?php } ?>
							<div class="main_title_bottom position_right">
								<div class="position_right_inner">
									<ul class="cf">
										<?php if ($row8['exeFlag'] == 0) { ?>
										<li><a href="/manage/estimates/simulation/edit/?id=<?php echo $qu_id; ?>">シミュレーション編集</a></li>
										<?php } else { ?>
										<li><a class="disable">シミュレーション編集</a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- <div class="main_title_top">
					<span id="delete_box"><a href="" onClick="del_qu(<?php echo $qu_id; ?>);return false;"><img src="/manage/img/icon_dust_gray.png" alt="ゴミ箱のアイコン"></a></span>
				</div> -->
				<div class="main_content simulation_content">
					<div class="all_wrapper">
						<div class="simulate_tab">
							<div class="simulate_tab_list">
								<ul class="cf">
									<li id="tab_one" class="simulation_tab active"><span>パターン1</span></li>
									<li id="tab_two" class="simulation_tab"><span>パターン2</span></li>
									<li id="tab_three" class="simulation_tab"><span>パターン3</span></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="main_content simulation_content">
					<div class="all_wrapper">
						<div id="simulation_one" class="simulation_field open_field">
							<div class="main_content_inner">
								<div class="main_content_wrap">

									<div class="simulation_field_title">
										<p>パターン1</p>
									</div>
									<div class="simulate_system pc">
										<div class="simulate_system_inner">
											<ul class="cf">
												<?php if ($row8['exeFlag'] == 0) { ?>
												<li><a href="/manage/estimates/simulation/sim_up.php?id=1&qu_id=<?php echo $qu_id; ?>">見積書へ反映</a></li>
												<?php } else { ?>
												<li><a class="disable">見積書へ反映</a></li>
												<?php } ?>
												<li><a href="javascript:void(0)" id="print_pattern_one" onclick="newwinprint(1, <?php echo $qu_id; ?>);">印刷</a></li>
											</ul>
										</div>
									</div>
									<div class="simulation_sub_info">
										<table>
											<tbody>
												<tr class="cf">
													<th>返済予定開始日</th>
													<td><?php if(isset($row7['qu_startDate'])) { echo h(date('Y年m月d日',  strtotime($row7['qu_startDate']))); } ?></td>
													<th>月々お支払額</th>
													<!-- <td>¥<?php if(isset($row7['pt1_amount_pay'])) { echo h($row7['pt1_amount_pay']); } ?></td> -->
													<td>¥<?php if(isset($pt1_amount_pay)) { echo h($pt1_amount_pay); } ?></td>
												</tr>
												<tr class="cf">
													<th>ご利用予定金額</th>
													<!-- <td>¥<?php if(isset($row7['qu_price'])) { echo h($row7['qu_price']); } ?></td> -->
													<td>¥<?php if(isset($qu_price)) { echo h($qu_price); } ?></td>
													<th>分割回数</th>
													<td><?php if(isset($row7['pt1_installments'])) { echo h($row7['pt1_installments']); } ?>回</td>
												</tr>
												<tr class="cf">
													<th>頭金</th>
													<!-- <td>¥<?php if(isset($row7['pt1_deposit'])) { echo h($row7['pt1_deposit']); } ?></td> -->
													<td>¥<?php if(isset($pt1_deposit)) { echo h($pt1_deposit); } ?></td>
													<th>割賦手数料総額</th>
													<!-- <td>¥<?php if(isset($row7['pt1_commit'])) { echo h($row7['pt1_commit']); } ?></td> -->
													<td>¥<?php if(isset($pt1_commit)) { echo h($pt1_commit); } ?></td>
												</tr>
												<tr class="cf">
													<th>割賦手数料率</th>
													<td><?php if(isset($row7['pt1_commission'])) { echo h($row7['pt1_commission']); } ?>%</td>
													<th class="total">総支払額</th>
													<!-- <td class="total">¥<?php if(isset($row7['pt1_alltotal'])) { echo h($row7['pt1_alltotal']); } ?></td> -->
													<td class="total">¥<?php if(isset($pt1_alltotal)) { echo h($pt1_alltotal); } ?></td>
												</tr>
											</tbody>
										</table>
									</div>

									<p class="text-right simulate_sub">（単位：円）</p>
									<table class="common_table simulatoin_table">
										<thead class="common_table_thead">
											<tr class="common_table_tr">
												<th>回数</th>
												<th>返済日</th>
												<th>ご利用金額</th>
												<th>月々お支払額</th>
												<th>お支払い残高</th>
											</tr>
										</thead>
										<tbody class="common_table_tbody">
											<?php for($ix=0; $ix<$pt1_out[0][5]; $ix++) { ?>
												<tr class="common_table_tr">
													<td class="common_table_cell"><?= h($pt1_out[$ix][0]); ?></td>
													<td class="common_table_cell"><?= h($pt1_out[$ix][1]); ?></td>
													<td class="common_table_cell"><?= h($pt1_out[$ix][2]); ?></td>
													<td class="common_table_cell"><?= h($pt1_out[$ix][3]); ?></td>
													<td class="common_table_cell"><?= h($pt1_out[$ix][4]); ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div id="simulation_two" class="simulation_field">
							<div class="main_content_inner">
								<div class="main_content_wrap">

									<div class="simulation_field_title">
										<p>パターン2</p>
									</div>
									<div class="simulate_system pc">
										<div class="simulate_system_inner">
											<ul class="cf">
												<?php if ($row8['exeFlag'] == 0) { ?>
												<li><a href="/manage/estimates/simulation/sim_up.php?id=2&qu_id=<?php echo $qu_id; ?>">見積書へ反映</a></li>
												<?php } else { ?>
												<li><a class="disable">見積書へ反映</a></li>
												<?php } ?>
												<li><a href="javascript:void(0)" id="print_pattern_one" onclick="newwinprint(2, <?php echo $qu_id; ?>)">印刷</a></li>
											</ul>
										</div>
									</div>
									<div class="simulation_sub_info">
										<table>
											<tbody>
												<tr>
													<th>返済予定開始日</th>
													<td><?php if(isset($row7['qu_startDate'])) { echo h(date('Y年m月d日',  strtotime($row7['qu_startDate']))); } ?></td>
													<th>月々お支払額</th>
													<!-- <td>¥<?php if(isset($row7['pt2_amount_pay'])) { echo h($row7['pt2_amount_pay']); } ?></td> -->
													<td>¥<?php if(isset($pt2_amount_pay)) { echo h($pt2_amount_pay); } ?></td>
												</tr>
												<tr>
													<th>ご利用予定金額</th>
													<!-- <td>¥<?php if(isset($row7['qu_price'])) { echo h($row7['qu_price']); } ?></td> -->
													<td>¥<?php if(isset($qu_price)) { echo h($qu_price); } ?></td>
													<th>分割回数</th>
													<td><?php if(isset($row7['pt2_installments'])) { echo h($row7['pt2_installments']); } ?>回</td>
												</tr>
												<tr>
													<th>頭金</th>
													<!-- <td>¥<?php if(isset($row7['pt2_deposit'])) { echo h($row7['pt2_deposit']); } ?></td> -->
													<td>¥<?php if(isset($pt2_deposit)) { echo h($pt2_deposit); } ?></td>
													<th>割賦手数料総額</th>
													<!-- <td>¥<?php if(isset($row7['pt2_commit'])) { echo h($row7['pt2_commit']); } ?></td> -->
													<td>¥<?php if(isset($pt2_commit)) { echo h($pt2_commit); } ?></td>
												</tr>
												<tr>
													<th>割賦手数料率</th>
													<td><?php if(isset($row7['pt2_commission'])) { echo h($row7['pt2_commission']); } ?>%</td>
													<th class="total">総支払額</th>
													<!-- <td class="total">¥<?php if(isset($row7['pt2_alltotal'])) { echo h($row7['pt2_alltotal']); } ?></td> -->
													<td class="total">¥<?php if(isset($pt2_alltotal)) { echo h($pt2_alltotal); } ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<p class="text-right simulate_sub">（単位：円）</p>
									<table class="common_table simulatoin_table">
										<thead class="common_table_thead">
											<tr class="common_table_tr">
												<th>回数</th>
												<th>返済日</th>
												<th>ご利用金額</th>
												<th>月々お支払額</th>
												<th>お支払い残高</th>
											</tr>
										</thead>
										<tbody class="common_table_tbody">
											<?php for($ix=0; $ix<$pt2_out[0][5]; $ix++) { ?>
												<tr class="common_table_tr">
													<td class="common_table_cell"><?= h($pt2_out[$ix][0]); ?></td>
													<td class="common_table_cell"><?= h($pt2_out[$ix][1]); ?></td>
													<td class="common_table_cell text-right"><?= h($pt2_out[$ix][2]); ?></td>
													<td class="common_table_cell text-right"><?= h($pt2_out[$ix][3]); ?></td>
													<td class="common_table_cell text-right"><?= h($pt2_out[$ix][4]); ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div id="simulation_three" class="simulation_field">
							<div class="main_content_inner">
								<div class="main_content_wrap">
									<div class="simulation_field_title">
										<p>パターン3</p>
									</div>
									<div class="simulate_system pc">
										<div class="simulate_system_inner">
											<ul class="cf">
												<?php if ($row8['exeFlag'] == 0) { ?>
												<li><a href="/manage/estimates/simulation/sim_up.php?id=3&qu_id=<?php echo $qu_id; ?>">見積書へ反映</a></li>
												<?php } else { ?>
												<li><a class="disable">見積書へ反映</a></li>
												<?php } ?>
												<li><a href="javascript:void(0)" id="print_pattern_one" onclick="newwinprint(3, <?php echo $qu_id; ?>)">印刷</a></li>
											</ul>
										</div>
									</div>
									<div class="simulation_sub_info">
										<table>
											<tbody>
												<tr>
													<th>返済予定開始日</th>
													<td><?php if(isset($row7['qu_startDate'])) { echo h(date('Y年m月d日',  strtotime($row7['qu_startDate']))); } ?></td>
													<th>月々お支払額</th>
													<!-- <td>¥<?php if(isset($row7['pt3_amount_pay'])) { echo h($row7['pt3_amount_pay']); } ?></td> -->
													<td>¥<?php if(isset($pt3_amount_pay)) { echo h($pt3_amount_pay); } ?></td>
												</tr>
												<tr>
													<th>ご利用予定金額</th>
													<!-- <td>¥<?php if(isset($row7['qu_price'])) { echo h($row7['qu_price']); } ?></td> -->
													<td>¥<?php if(isset($qu_price)) { echo h($qu_price); } ?></td>
													<th>分割回数</th>
													<td><?php if(isset($row7['pt3_installments'])) { echo h($row7['pt3_installments']); } ?>回</td>
												</tr>
												<tr>
													<th>頭金</th>
													<!-- <td>¥<?php if(isset($row7['pt3_deposit'])) { echo h($row7['pt3_deposit']); } ?></td> -->
													<td>¥<?php if(isset($pt3_deposit)) { echo h($pt3_deposit); } ?></td>
													<th>割賦手数料総額</th>
													<!-- <td>¥<?php if(isset($row7['pt3_commit'])) { echo h($row7['pt3_commit']); } ?></td> -->
													<td>¥<?php if(isset($pt3_commit)) { echo h($pt3_commit); } ?></td>
												</tr>
												<tr>
													<th>割賦手数料率</th>
													<td><?php if(isset($row7['pt3_commission'])) { echo h($row7['pt3_commission']); } ?>%</td>
													<th class="total">総支払額</th>
													<!-- <td class="total">¥<?php if(isset($row7['pt3_alltotal'])) { echo h($row7['pt3_alltotal']); } ?></td> -->
													<td class="total">¥<?php if(isset($pt3_alltotal)) { echo h($pt3_alltotal); } ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<p class="text-right simulate_sub">（単位：円）</p>
									<table class="common_table simulatoin_table">
										<thead class="common_table_thead">
											<tr class="common_table_tr">
												<th>回数</th>
												<th>返済日</th>
												<th>ご利用金額</th>
												<th>月々お支払額</th>
												<th>お支払い残高</th>
											</tr>
										</thead>
										<tbody class="common_table_tbody">
											<?php for($ix=0; $ix<$pt3_out[0][5]; $ix++) { ?>
												<tr class="common_table_tr">
													<td class="common_table_cell"><?php if(isset($pt3_out[$ix][0])) { echo h($pt3_out[$ix][0]); } ?></td>
													<td class="common_table_cell"><?php if(isset($pt3_out[$ix][1])) { echo h($pt3_out[$ix][1]); } ?></td>
													<td class="common_table_cell text-right"><?php if(isset($pt3_out[$ix][2])) { echo h($pt3_out[$ix][2]); } ?></td>
													<td class="common_table_cell text-right"><?php if(isset($pt3_out[$ix][3])) { echo h($pt3_out[$ix][3]); } ?></td>
													<td class="common_table_cell text-right"><?php if(isset($pt3_out[$ix][4])) { echo h($pt3_out[$ix][4]); } ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div id="other_submit_field">
							<div class="all_wrapper cf">
								<div class="other_submit_field_wrap">
									<div id="check_submit_field" class="other_submit_field_content">
										<div class="other_submit_field_inner">
											<p class="field_title">選択中のパターンを</p>
											<ul class="cf">
												<li><a href="/manage/estimates/simulation/sim_up.php?id=1&qu_id=<?php echo $qu_id; ?>">見積書へ反映</a></li>
											</ul>
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
	<script src="/manage/common/js/customer-data.js"></script>
	<script>
		// シミュレーションフィールドタブ機能
		$(function(){
			$('.simulation_tab').click(function(){
				$('.active').removeClass('active');
				$(this).addClass('active');
				if($("#tab_one").hasClass('active')) {
					$("#simulation_one").addClass("open_field");
					$("#simulation_two").removeClass("open_field");
					$("#simulation_three").removeClass("open_field");
				};
				if($("#tab_two").hasClass('active')) {
					$("#simulation_two").addClass("open_field");
					$("#simulation_one").removeClass("open_field");
					$("#simulation_three").removeClass("open_field");
				};
				if($("#tab_three").hasClass('active')) {
					$("#simulation_three").addClass("open_field");
					$("#simulation_one").removeClass("open_field");
					$("#simulation_two").removeClass("open_field");
				};
			});
		});
		function newwinprint(ptn, qu_id){
			window.open("/manage/estimates/simulation/sim_pdf.php?id=" + ptn + "&qu_id=" + qu_id);
		}
	</script>
	<script>
		function del_qu(qu_id) {
			if (window.confirm("シミュレーションデータを削除します。\n宜しいですか？")) {
				location.href = "/manage/estimates/simulation/func_del.php?id=" + qu_id;
			}
		}
	</script>
	<script>
		// sinclo
		$(function() {
			$('#chatButton').on('click', function(){
				$('#sincloBox').toggleClass('chatOpen');
				$('#sincloBox').data('true');
			});
		});
	</script>
	<script src='https://ws1.sinclo.jp/client/5e7812fdb5a66.js'></script>
</body>
</html>
