<?php
session_start();
require_once(__DIR__ . '/../../../common/dbconnect.php');
require_once(__DIR__ . '/../simulation/sim_func.php');
require_once(__DIR__ . '/../../common/functions.php');

if (empty($_SESSION['loginID'])) {
	header("Location:/");
	exit();
}

if (empty($_GET['id']) || empty($_GET['ptn'])) {
	header("Location:/");
	exit();
} else {
	$get_qu_id = $_GET['id'];
	$get_ptnno = $_GET['ptn'];

	$sql = sprintf('SELECT * FROM `w1_quotation` WHERE qu_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $get_qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row3 = mysqli_fetch_assoc($record);

	$sql = sprintf('SELECT * FROM `w1_simulation` WHERE qu_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $get_qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row7 = mysqli_fetch_assoc($record);

	/*-----------------------------------------------------------

		DBよりシミュレーション情報を取得

	-------------------------------------------------------------*/
	// 商品価格
	if (ctype_digit($row7['qu_price'])) {
		$qu_price = number_format($row7['qu_price']);
	} else {
		$qu_price = $row7['qu_price'];
	}

	// 実行日
	$qu_startDate = date("Y年m月d日", strtotime($row7['qu_startDate']));

	if ($get_ptnno == 1) {
		// パターン1
		// 3桁ごとにカンマを追加する
		$ptn_commit 	   = number_format($row7['pt1_commit']);		// 割賦手数料総額
		$ptn_deposit 	   = number_format($row7['pt1_deposit']);		// 頭金
		$ptn_commission    = $row7['pt1_commission'];				// 事務管理手数料率
		$ptn_initPayAmount = number_format($row7['pt1_initPayAmount']);		// 初回お支払額
		$ptn_amount_pay    = number_format($row7['pt1_amount_pay']);		// 月々お支払額
		$ptn_installments  = $row7['pt1_installments'];				// 分割回数
		$ptn_alltotal 	   = number_format($row7['pt1_alltotal']);		// 総支払額

		$ptn_out = partition($row3['qu_paymentDate'], $row7['qu_startDate'], $row7['pt1_installments'], $row7['pt1_alltotal'], $row7['pt1_deposit'], $row7['pt1_initPayAmount']);
		// echo "pt1=". $ptn_out[0][5];
	} else if ($get_ptnno == 2) {
		// パターン2
		// 3桁ごとにカンマを追加する
		$ptn_commit 	   = number_format($row7['pt2_commit']);		// 割賦手数料総額
		$ptn_deposit 	   = number_format($row7['pt2_deposit']);		// 頭金
		$ptn_commission    = $row7['pt2_commission'];				// 事務管理手数料率
		$ptn_initPayAmount = number_format($row7['pt2_initPayAmount']);		// 初回お支払額
		$ptn_amount_pay    = number_format($row7['pt2_amount_pay']);		// 月々お支払額
		$ptn_installments  = $row7['pt2_installments'];				// 分割回数
		$ptn_alltotal 	   = number_format($row7['pt2_alltotal']);		// 総支払額

		$ptn_out = partition($row3['qu_paymentDate'], $row7['qu_startDate'], $row7['pt2_installments'], $row7['pt2_alltotal'], $row7['pt2_deposit'], $row7['pt2_initPayAmount']);
		// echo "pt2=". $ptn_out[0][5];
	} else {
		// パターン3
		// 3桁ごとにカンマを追加する
		$ptn_commit 	   = number_format($row7['pt3_commit']);		// 割賦手数料総額
		$ptn_deposit 	   = number_format($row7['pt3_deposit']);		// 頭金
		$ptn_commission    = $row7['pt3_commission'];				// 事務管理手数料率
		$ptn_initPayAmount = number_format($row7['pt3_initPayAmount']);		// 初回お支払額
		$ptn_amount_pay    = number_format($row7['pt3_amount_pay']);		// 月々お支払額
		$ptn_installments  = $row7['pt3_installments'];				// 分割回数
		$ptn_alltotal 	   = number_format($row7['pt3_alltotal']);		// 総支払額

		$ptn_out = partition($row3['qu_paymentDate'], $row7['qu_startDate'], $row7['pt3_installments'], $row7['pt3_alltotal'], $row7['pt3_deposit'], $row7['pt3_initPayAmount']);
		// echo "pt3=". $ptn_out[0][5];
	}
}
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
								<li><span><a href="/manage/estimates/" class="text_link">見積書一覧</a></span></li>
								<li><span><a href="/manage/estimates/new/" class="text_link">新規見積書作成</a></span></li>
								<li><span><a href="/manage/estimates/new/simulation.php?id=<?php echo $get_qu_id; ?>" class="text_link">シミュレーション編集</a></span></li>
								<li><span>シミュレーションパターン</span></li>
							</ul>
						</div>
						<!-- <div class="main_title_inner">
							<div class="main_title_top">
								<span class="date">シミュレーション</span>
								<p class="title"><?php echo $row3['qu_name']; ?></p>
							</div>
							<?php if ($_SESSION['sim_err'] == "UPOK") { ?>
								<p class="error_message">※見積書を更新しました。</p>
								<?php $_SESSION['sim_err'] = ""; ?>
							<?php } ?>
							<div class="main_title_bottom position_right">
								<div class="position_right_inner">
									<ul class="cf">
										<?php if ($row8['exeFlag'] == 0) { ?>
										<li><a href="/manage/estimates/simulation/edit/?id=<?php echo $get_qu_id; ?>">シミュレーション編集</a></li>
										<?php } else { ?>
										<li><a class="disable">シミュレーション編集</a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div> -->
					</div>
				</div>
				<div class="main_content simulation_content">
					<div class="all_wrapper">
						<div id="simulation_one" class="simulation_field open_field">
							<div class="main_content_inner" style="padding: 0 !important; background: none !important;">
								<div class="main_content_wrap">
									<div class="simulation_field_title">
										<p>パターン<?php echo $get_ptnno; ?></p>
									</div>
									<div class="simulate_system">
										<div class="simulate_system_inner">
											<ul class="cf">
												<!-- <?php if ($row8['exeFlag'] == 0) { ?>
												<li><a href="/manage/estimates/simulation/sim_up.php?id=1&qu_id=<?php echo $get_qu_id; ?>">見積書へ反映</a></li>
												<?php } else { ?>
												<li><a class="disable">見積書へ反映</a></li>
												<?php } ?> -->
												<li><a href="javascript:void(0)" onclick="back_qu(<?php echo $get_qu_id; ?>);">前へ戻る</a></li>
												<li><a href="javascript:void(0)" onclick="commit_qu(<?php echo $get_ptnno; ?>, <?php echo $get_qu_id; ?>);">見積書を登録</a></li>
											</ul>
										</div>
									</div>
									<div class="simulation_sub_info">
										<table>
											<tbody>
												<tr class="cf">
													<th>返済予定開始日</th>
													<td><?php echo $qu_startDate; ?></td>
													<th>月々お支払額</th>
													<td>¥<?php echo $ptn_amount_pay; ?></td>
												</tr>
												<tr class="cf">
													<th>ご利用予定金額</th>
													<td>¥<?php echo $qu_price; ?></td>
													<th>分割回数</th>
													<td><?php echo $ptn_installments; ?>回</td>
												</tr>
												<tr class="cf">
													<th>頭金</th>
													<td>¥<?php echo $ptn_deposit; ?></td>
													<th>割賦手数料総額</th>
													<td>¥<?php echo $ptn_commit; ?></td>
												</tr>
												<tr class="cf">
													<th>割賦手数料率</th>
													<td><?php echo $ptn_commission; ?>%</td>
													<th class="total">総支払額</th>
													<td class="total">¥<?php echo $ptn_alltotal; ?></td>
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
											<?php for($ix=0; $ix<$ptn_out[0][5]; $ix++) { ?>
												<tr class="common_table_tr">
													<td class="common_table_cell"><?php echo $ptn_out[$ix][0]; ?></td>
													<td class="common_table_cell"><?php echo $ptn_out[$ix][1]; ?></td>
													<td class="common_table_cell"><?php echo $ptn_out[$ix][2]; ?></td>
													<td class="common_table_cell"><?php echo $ptn_out[$ix][3]; ?></td>
													<td class="common_table_cell"><?php echo $ptn_out[$ix][4]; ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
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

		function back_qu(qu_id) {
			location.href = "/manage/estimates/new/simulation.php?id=" + qu_id;
		}

		function commit_qu(ptn, qu_id) {
			location.href = "/manage/estimates/new/pay_insert.php?id=" + qu_id + "&ptn=" + ptn;
		}

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
