<?php
session_start();
require_once(__DIR__ . '/../../../common/dbconnect.php');
require_once(__DIR__ . '/../simulation/sim_func.php');
require_once(__DIR__ . '/../../common/functions.php');

if (empty($_SESSION['loginID'])) {
	header("Location:/");
	exit();
}

$update_flag = 0;

if (empty($_GET['id'])) {
	header("Location:/");
	exit();
} else {
	$get_qu_id = $_GET['id'];

	$sql = sprintf('SELECT * FROM `w1_quotation` WHERE qu_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $get_qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row3 = mysqli_fetch_assoc($record);

	$sql = sprintf('SELECT * FROM `w1_simulation` WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $get_qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row7 = mysqli_fetch_assoc($record)) {
		// データあり
		if ($_GET['new'] == "1") {
			$sql = sprintf('DELETE FROM `w1_simulation` WHERE qu_id="%d"',
				mysqli_real_escape_string($db, $get_qu_id)
			);
			mysqli_query($db,$sql) or die(mysqli_error($db));
			$update_flag = 0;
		} else {
			$update_flag = 1;
		}
	}

	if ($update_flag == 0) {
		$sql = sprintf('INSERT INTO `w1_simulation` SET qu_id="%d", c_id="%d",
			u_id="%d", pt_number=1, qu_price="%s", qu_startDate="%s",
			pt1_commit="%s", pt2_commit="%s", pt3_commit="%s",
			pt1_deposit="%s", pt2_deposit="%s", pt3_deposit="%s",
			pt1_commission="%s", pt2_commission="%s", pt3_commission="%s",
			pt1_initPayAmount="%s", pt2_initPayAmount="%s", pt3_initPayAmount="%s",
			pt1_amount_pay="%s", pt2_amount_pay="%s", pt3_amount_pay="%s",
			pt1_installments="%s", pt2_installments="%s", pt3_installments="%s",
			pt1_alltotal="%s", pt2_alltotal="%s", pt3_alltotal="%s"',
			mysqli_real_escape_string($db, $get_qu_id),
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

	$sql = sprintf('SELECT * FROM `w1_simulation` WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $get_qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row7 = mysqli_fetch_assoc($record);

	$qu_price          = number_format($row7['qu_price']);			// 工事/商品価格
	$pt1_initPayAmount = number_format($row7['pt1_initPayAmount']);		// 初回お支払額1
	$pt2_initPayAmount = number_format($row7['pt2_initPayAmount']);		// 初回お支払額2
	$pt3_initPayAmount = number_format($row7['pt3_initPayAmount']);		// 初回お支払額3
	$pt1_deposit       = number_format($row7['pt1_deposit']);		// 頭金1
	$pt2_deposit       = number_format($row7['pt2_deposit']);		// 頭金2
	$pt3_deposit       = number_format($row7['pt3_deposit']);		// 頭金3

 	// 実行日
 	$qu_startDate = date("Y年m月d日", strtotime($row7['qu_startDate']));

//	// 分割回数
//	if ($row7['pt2_installments'] == "" || $row7['pt2_installments'] == 0) {
//		$row7['pt2_installments'] = "4";
//	}
//	if ($row7['pt3_installments'] == "" || $row7['pt3_installments'] == 0) {
//		$row7['pt3_installments'] = "4";
//	}

//	// 事務管理手数料率
//	if ($row7['pt2_commission'] == "" || $row7['pt2_commission'] == 0) {
//		$row7['pt2_commission'] = $row7['pt1_commission'];
//	}
//	if ($row7['pt3_commission'] == "" || $row7['pt3_commission'] == 0) {
//		$row7['pt3_commission'] = $row7['pt1_commission'];
//	}
}

if (!empty($_POST['send3'])) {
 	$qu_deposit1       = preg_replace('/[^0-9]/','', $_POST['qu_deposit1']);		// 頭金1の「¥」を取る
 	$qu_deposit2       = preg_replace('/[^0-9]/','', $_POST['qu_deposit2']);		// 頭金2の「¥」を取る
 	$qu_deposit3       = preg_replace('/[^0-9]/','', $_POST['qu_deposit3']);		// 頭金3の「¥」を取る
	$qu_initPayAmount1 = preg_replace('/[^0-9]/','', $_POST['qu_initPayAmount1']);		// 初回お支払額1の「￥」を取る
	$qu_initPayAmount2 = preg_replace('/[^0-9]/','', $_POST['qu_initPayAmount2']);		// 初回お支払額2の「￥」を取る
	$qu_initPayAmount3 = preg_replace('/[^0-9]/','', $_POST['qu_initPayAmount3']);		// 初回お支払額3の「￥」を取る
	$qu_amount_pay1    = preg_replace('/[^0-9]/','', $_POST['qu_amount_pay1']);		// 月々お支払額1の「￥」を取る
	$qu_amount_pay2    = preg_replace('/[^0-9]/','', $_POST['qu_amount_pay2']);		// 月々お支払額2の「￥」を取る
	$qu_amount_pay3    = preg_replace('/[^0-9]/','', $_POST['qu_amount_pay3']);		// 月々お支払額3の「￥」を取る
	$qu_commit1        = preg_replace('/[^0-9]/','', $_POST['qu_commit1']);			// 手数料総額1の「￥」を取る
	$qu_commit2        = preg_replace('/[^0-9]/','', $_POST['qu_commit2']);			// 手数料総額2の「￥」を取る
	$qu_commit3        = preg_replace('/[^0-9]/','', $_POST['qu_commit3']);			// 手数料総額3の「￥」を取る
	$q_alltotal1       = preg_replace('/[^0-9]/','', $_POST['q_alltotal1']);		// 総支払額1の「￥」を取る
	$q_alltotal2       = preg_replace('/[^0-9]/','', $_POST['q_alltotal2']);		// 総支払額2の「￥」を取る
	$q_alltotal3       = preg_replace('/[^0-9]/','', $_POST['q_alltotal3']);		// 総支払額3の「￥」を取る

	$sql = sprintf('UPDATE `w1_simulation` SET
		pt1_commit="%s", pt1_deposit="%s", pt1_commission="%s",
		pt1_initPayAmount="%s", pt1_amount_pay="%s", pt1_installments="%s",
		pt1_alltotal="%s",
		pt2_commit="%s", pt2_deposit="%s", pt2_commission="%s",
		pt2_initPayAmount="%s", pt2_amount_pay="%s", pt2_installments="%s",
		pt2_alltotal="%s",
		pt3_commit="%s", pt3_deposit="%s", pt3_commission="%s",
		pt3_initPayAmount="%s", pt3_amount_pay="%s", pt3_installments="%s",
		pt3_alltotal="%s" WHERE qu_id="%d"',
		mysqli_real_escape_string($db, $qu_commit1),
		mysqli_real_escape_string($db, $qu_deposit1),			// 頭金
		mysqli_real_escape_string($db, $_POST['qu_commission1']),	// 事務管理手数料率
		mysqli_real_escape_string($db, $qu_initPayAmount1),		// 初回お支払額
		mysqli_real_escape_string($db, $qu_amount_pay1),		// 月々お支払額
		mysqli_real_escape_string($db, $_POST['qu_installments1']),	// 分割回数
		mysqli_real_escape_string($db, $q_alltotal1),			// 総支払額
		mysqli_real_escape_string($db, $qu_commit2),
		mysqli_real_escape_string($db, $qu_deposit2),			// 頭金
		mysqli_real_escape_string($db, $_POST['qu_commission2']),	// 事務管理手数料率
		mysqli_real_escape_string($db, $qu_initPayAmount2),		// 初回お支払額
		mysqli_real_escape_string($db, $qu_amount_pay2),		// 月々お支払額
		mysqli_real_escape_string($db, $_POST['qu_installments2']),	// 分割回数
		mysqli_real_escape_string($db, $q_alltotal2),			// 総支払額
		mysqli_real_escape_string($db, $qu_commit3),
		mysqli_real_escape_string($db, $qu_deposit3),			// 頭金
		mysqli_real_escape_string($db, $_POST['qu_commission3']),	// 事務管理手数料率
		mysqli_real_escape_string($db, $qu_initPayAmount3),		// 初回お支払額
		mysqli_real_escape_string($db, $qu_amount_pay3),		// 月々お支払額
		mysqli_real_escape_string($db, $_POST['qu_installments3']),	// 分割回数
		mysqli_real_escape_string($db, $q_alltotal3),			// 総支払額
		mysqli_real_escape_string($db, $get_qu_id)
	);
	mysqli_query($db,$sql) or die(mysqli_error($db));

	if ($_POST['send3'] == "パターン1で確認する") {
		$sql = sprintf('UPDATE `w1_quotation` SET qu_deposit="%s", qu_commission="%s",
			qu_commit="%s", qu_initPayAmount="%s", qu_amount_pay="%s",
			qu_installments="%s", q_alltotal="%s" WHERE qu_id="%d"',
			mysqli_real_escape_string($db, $qu_deposit1),			// 頭金
			mysqli_real_escape_string($db, $_POST['qu_commission1']),	// 事務管理手数料率
			mysqli_real_escape_string($db, $qu_commit1),
			mysqli_real_escape_string($db, $qu_initPayAmount1),		// 初回お支払額
			mysqli_real_escape_string($db, $qu_amount_pay1),		// 月々お支払額
			mysqli_real_escape_string($db, $_POST['qu_installments1']),	// 分割回数
			mysqli_real_escape_string($db, $q_alltotal1),			// 総支払額
			mysqli_real_escape_string($db, $get_qu_id)
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));

		header("Location:/manage/estimates/new/payment.php?id=". $get_qu_id. "&ptn=1");
		exit();

// 	// $qu_startDate = str_replace('年', '', $_POST['qu_startDate']);
// 	// $qu_startDate = str_replace('月', '', $qu_startDate);
// 	// $qu_startDate = str_replace('日', '', $qu_startDate);
// 	// $qu_startDate = date('Y-m-d', strtotime($qu_startDate));

// 	$qu_price 	= str_replace('¥', '', $_POST['qu_price']);			// 工事商品価格の「¥」を取る
// 	$qu_price 	= str_replace(',', '', $qu_price);				// 工事商品価格の「,」を取る

// 	// $pt1_commit = $_POST['qu_price'] * ($_POST['qu_commission1'] / 100);
// 	// $pt2_commit = $_POST['qu_price'] * ($_POST['qu_commission2'] / 100);
// 	// $pt3_commit = $_POST['qu_price'] * ($_POST['qu_commission3'] / 100);

// 	header("Location:/manage/estimates/simulation/?id=". $get_qu_id);
// 	exit();
	} else if ($_POST['send3'] == "パターン2で確認する") {
		$sql = sprintf('UPDATE `w1_quotation` SET qu_deposit="%s", qu_commission="%s",
			qu_commit="%s", qu_initPayAmount="%s", qu_amount_pay="%s",
			qu_installments="%s", q_alltotal="%s" WHERE qu_id="%d"',
			mysqli_real_escape_string($db, $qu_deposit2),			// 頭金
			mysqli_real_escape_string($db, $_POST['qu_commission2']),	// 事務管理手数料率
			mysqli_real_escape_string($db, $qu_commit2),
			mysqli_real_escape_string($db, $qu_initPayAmount2),		// 初回お支払額
			mysqli_real_escape_string($db, $qu_amount_pay2),		// 月々お支払額
			mysqli_real_escape_string($db, $_POST['qu_installments2']),	// 分割回数
			mysqli_real_escape_string($db, $q_alltotal2),			// 総支払額
			mysqli_real_escape_string($db, $get_qu_id)
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));

		header("Location:/manage/estimates/new/payment.php?id=". $get_qu_id. "&ptn=2");
		exit();
	} else {
		$sql = sprintf('UPDATE `w1_quotation` SET qu_deposit="%s", qu_commission="%s",
			qu_commit="%s", qu_initPayAmount="%s", qu_amount_pay="%s",
			qu_installments="%s", q_alltotal="%s" WHERE qu_id="%d"',
			mysqli_real_escape_string($db, $qu_deposit3),			// 頭金
			mysqli_real_escape_string($db, $_POST['qu_commission3']),	// 事務管理手数料率
			mysqli_real_escape_string($db, $qu_commit3),
			mysqli_real_escape_string($db, $qu_initPayAmount3),		// 初回お支払額
			mysqli_real_escape_string($db, $qu_amount_pay3),		// 月々お支払額
			mysqli_real_escape_string($db, $_POST['qu_installments3']),	// 分割回数
			mysqli_real_escape_string($db, $q_alltotal3),			// 総支払額
			mysqli_real_escape_string($db, $get_qu_id)
		);
		mysqli_query($db,$sql) or die(mysqli_error($db));

		header("Location:/manage/estimates/new/payment.php?id=". $get_qu_id. "&ptn=3");
		exit();
	}
}
?>
<?php require_once(__DIR__ . '/../../common/head.php'); ?>
<?php require_once(__DIR__ . '/../../common/header.php'); ?>
	<main>
		<div class="main_wrap">
			<div class="main_wrap_inner">
				<?php require_once(__DIR__ . '/../../common/grobal-menu.php'); ?>
				<div class="pankuzu">
					<div class="container">
						<ul>
							<li><a href="/manage/estimates/"><span>見積書一覧</span></a></li>
							<!-- <li><a href="/manage/estimates/"><span>見積書</span></a></li> -->
							<li><a href="/manage/estimates/new/"><span>新規見積作成</span></a></li>
							<li><span>シミュレーション編集</span></li>
						</ul>
					</div>
				</div>
				<div class="main_title">
					<div class="container">
						<div class="main_title_headline">
							<h1>シミュレーション編集</h1>
						</div>
					</div>
				</div>
				<div id="simulationEdit" class="main_content input_area simulation_data">
					<div class="container">
						<div class="main_content_inner simulation_data_inner">
							<div class="simulation_data_comparison">
								<form action="" method="post" accept-charset="utf-8">
									<div class="simulation_data_comparison_inner">
										<table>
											<thead>
												<tr class="color">
													<th class="text-center">
														<span class="bold" style="font-size: 1.2em; display: block; margin-bottom: 10px;">工事/商品価格(税込)</span>
														<span class="number"><?php echo $qu_price; ?>円</span>
													</th>
													<th>パターン1</th>
													<th>パターン2</th>
													<th>パターン3</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<th>頭金</th>
													<!-- <td><input type="text" name="qu_deposit1" v-model="downPayment1" pattern="^[0-9,]+$" title="半角数字をご入力ください"></td>
													<td><input type="text" name="qu_deposit2" v-model="downPayment2" pattern="^[0-9,]+$" title="半角数字をご入力ください"></td>
													<td><input type="text" name="qu_deposit3" v-model="downPayment3" pattern="^[0-9,]+$" title="半角数字をご入力ください"></td> -->
													<td><input class="text-center" type="text" name="qu_deposit1" v-model="downPayment1" @focus="focusDownPayment1" @blur="blurDownPayment1"></td>
													<td><input class="text-center" type="text" name="qu_deposit2" v-model="downPayment2" @focus="focusDownPayment2" @blur="blurDownPayment2"></td>
													<td><input class="text-center" type="text" name="qu_deposit3" v-model="downPayment3" @focus="focusDownPayment3" @blur="blurDownPayment3"></td>
												</tr>
												<tr>
													<th>割賦手数料率</th>
													<!-- <td><input type="text" name="qu_commission1" v-model="adminPer1" pattern="^[0-9.]+$" title="半角数字をご入力ください"></td>
													<td><input type="text" name="qu_commission2" v-model="adminPer2" pattern="^[0-9.]+$" title="半角数字をご入力ください"></td>
													<td><input type="text" name="qu_commission3" v-model="adminPer3" pattern="^[0-9.]+$" title="半角数字をご入力ください"></td> -->
													<td><input class="text-center" type="text" name="qu_commission1" v-model="adminPer1"></td>
													<td><input class="text-center" type="text" name="qu_commission2" v-model="adminPer2"></td>
													<td><input class="text-center" type="text" name="qu_commission3" v-model="adminPer3"></td>
												</tr>
												<tr>
													<th>初回お支払額</th>
													<!-- <td><input type="text" name="qu_initPayAmount1" v-model="initialPayment1" value="0" pattern="^[0-9,]+$" title="半角数字をご入力ください"></td>
													<td><input type="text" name="qu_initPayAmount2" v-model="initialPayment2" value="0" pattern="^[0-9,]+$" title="半角数字をご入力ください"></td>
													<td><input type="text" name="qu_initPayAmount3" v-model="initialPayment3" value="0" pattern="^[0-9,]+$" title="半角数字をご入力ください"></td> -->
													<td><input class="calculation_area text-center" type="text" name="qu_initPayAmount1" v-model="initialPayment1" value="0" readonly="readonly"></td>
													<td><input class="calculation_area text-center" type="text" name="qu_initPayAmount2" v-model="initialPayment2" value="0" readonly="readonly"></td>
													<td><input class="calculation_area text-center" type="text" name="qu_initPayAmount3" v-model="initialPayment3" value="0" readonly="readonly"></td>
												</tr>
												<tr>
													<th>月々お支払額</th>
													<td><input class="calculation_area text-center" type="text" name="qu_amount_pay1" v-model="monthlyPayment1" readonly="readonly"></td>
													<td><input class="calculation_area text-center" type="text" name="qu_amount_pay2" v-model="monthlyPayment2" readonly="readonly"></td>
													<td><input class="calculation_area text-center" type="text" name="qu_amount_pay3" v-model="monthlyPayment3" readonly="readonly"></td>
												</tr>
												<tr>
													<th>お支払い回数</th>
													<td>
														<select name="qu_installments1" v-model="numberPayments1">
															<option v-for="split of 45" :key="split" v-bind:value="split+3">{{ split + 3 }}</option>
														</select>
														回払い
													</td>
													<td>
														<select name="qu_installments2" v-model="numberPayments2">
															<option v-for="split of 45" :key="split" v-bind:value="split+3">{{ split + 3 }}</option>
														</select>
														回払い
													</td>
													<td>
														<select name="qu_installments3" v-model="numberPayments3">
															<option v-for="split of 45" :key="split" v-bind:value="split+3">{{ split + 3 }}</option>
														</select>
														回払い
													</td>
												</tr>
												<tr>
													<th>割賦手数料</th>
													<td><input class="calculation_area text-center" type="text" name="qu_commit1" v-model="adminFee1" readonly="readonly"></td>
													<td><input class="calculation_area text-center" type="text" name="qu_commit2" v-model="adminFee2" readonly="readonly"></td>
													<td><input class="calculation_area text-center" type="text" name="qu_commit3" v-model="adminFee3" readonly="readonly"></td>
												</tr>
												<tr>
													<th>総支払額</th>
													<td><input class="calculation_area text-center" type="text" name="q_alltotal1" v-model="estimatesPrice1" value="" readonly="readonly" title="自動計算エリア"></td>
													<td><input class="calculation_area text-center" type="text" name="q_alltotal2" v-model="estimatesPrice2" value="" readonly="readonly" title="自動計算エリア"></td>
													<td><input class="calculation_area text-center" type="text" name="q_alltotal3" v-model="estimatesPrice3" value="" readonly="readonly" title="自動計算エリア"></td>
												</tr>
												<tr>
													<th></th>
													<td class="sendButton"><input type="submit" name="send3" value="パターン1で確認する"></td>
													<td class="sendButton"><input type="submit" name="send3" value="パターン2で確認する"></td>
													<td class="sendButton"><input type="submit" name="send3" value="パターン3で確認する"></td>
												</tr>
											</tbody>
										</table>
										<!-- <div class="submit_button"><input type="submit" name="send2" value="パターンを更新する"></div> -->
									</div>
									
									<div class="common_submit_button">
										<a href="/manage/estimates/new/">戻る</a>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="/manage/common/js/customer-data.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>　<!-- vue.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> <!-- input dateの修正 -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment.min.js"></script> <!-- 日付の計算 -->
	<script>
		(function() {
			// 項目を追加する機能
			var simulationEdit = new Vue ({
				el: '#simulationEdit',
				data:function () {
					return {
						shokei: "<?php echo $qu_price; ?>",
						downPayment1: "¥" + "<?php echo $pt1_deposit; ?>",		// 頭金
						downPayment2: "¥" + "<?php echo $pt2_deposit; ?>",		// 頭金
						downPayment3: "¥" + "<?php echo $pt3_deposit; ?>",		// 頭金
						adminPer1: "<?php echo $row7['pt1_commission']; ?>",		// 事務管理手数料率
						adminPer2: "<?php echo $row7['pt2_commission']; ?>",		// 事務管理手数料率
						adminPer3: "<?php echo $row7['pt3_commission']; ?>",		// 事務管理手数料率
						numberPayments1: <?php echo $row7['pt1_installments']; ?>,	// 分割回数
						numberPayments2: <?php echo $row7['pt2_installments']; ?>,	// 分割回数
						numberPayments3: <?php echo $row7['pt3_installments']; ?>,	// 分割回数
					}
				},
				// 要素を追加する機能
				methods: {
					// 「頭金1」のカンマ、円マークを削除する
					focusShokei: function(val) {
						function removeComma(value) {
							return value.replace(/,/g, '').replace(/¥/g, '');
						};
						this.shokei = removeComma(val.target.value);
					},
					// 「頭金1」の3桁と¥をつける
					blurShokei: function(val) {
						function addComma(value) {
							return value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
						};
						this.shokei = '¥' +addComma(val.target.value);
					},
					// 「頭金1」のカンマ、円マークを削除する
					focusDownPayment1: function(val) {
						function removeComma(value) {
							return value.replace(/,/g, '').replace(/¥/g, '');
						};
						this.downPayment1 = removeComma(val.target.value);
					},
					// 「頭金1」の3桁と¥をつける
					blurDownPayment1: function(val) {
						function addComma(value) {
							return value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
						};
						this.downPayment1 = '¥' +addComma(val.target.value);
					},
					// 「頭金2」のカンマ、円マークを削除する
					focusDownPayment2: function(val) {
						function removeComma(value) {
							return value.replace(/,/g, '').replace(/¥/g, '');
						};
						this.downPayment2 = removeComma(val.target.value);
					},
					// 「頭金2」の3桁と¥をつける
					blurDownPayment2: function(val) {
						function addComma(value) {
							return value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
						};
						this.downPayment2 = '¥' +addComma(val.target.value);
					},
					// 「頭金3」のカンマ、円マークを削除する
					focusDownPayment3: function(val) {
						function removeComma(value) {
							return value.replace(/,/g, '').replace(/¥/g, '');
						};
						this.downPayment3 = removeComma(val.target.value);
					},
					// 「頭金3」の3桁と¥をつける
					blurDownPayment3: function(val) {
						function addComma(value) {
							return value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
						};
						this.downPayment3 = '¥' +addComma(val.target.value);
					},
				},
				computed: {
					estimatesPrice1: function() {
						let estimatesPriceTarget1 = Number(this.shokei) + Math.ceil((Number(this.adminPer1) * Number(this.shokei)) / 100) || 0;
						let estimatesPriceTargetComma1 = estimatesPriceTarget1.toLocaleString("jp",{style:"currency",currency:"JPY"});
						return(estimatesPriceTargetComma1);
					},
					estimatesPrice2: function() {
						let estimatesPriceTarget2 = Number(this.shokei) + Math.ceil((Number(this.adminPer2) * Number(this.shokei)) / 100) || 0;
						let estimatesPriceTargetComma2 = estimatesPriceTarget2.toLocaleString("jp",{style:"currency",currency:"JPY"});
						return(estimatesPriceTargetComma2);
					},
					estimatesPrice3: function() {
						let estimatesPriceTarget3 = Number(this.shokei) + Math.ceil((Number(this.adminPer3) * Number(this.shokei)) / 100) || 0;
						let estimatesPriceTargetComma3 = estimatesPriceTarget3.toLocaleString("jp",{style:"currency",currency:"JPY"});
						return(estimatesPriceTargetComma3);
					},
					// // 月々お支払額1
					// monthlyPayment1: function() {
					// 	let monthlyPaymentTarget1 = Math.ceil((Number(this.estimatesPrice1.replace(/[^0-9]/g, '')) - (Number(this.initialPayment1) + Number(this.downPayment1))) / (Number(this.numberPayments1) - 1)) || 0;
					// 	// 通貨記号と３桁ごとにカンマを付ける
					// 	let monthlyPaymentTargetComma1 = monthlyPaymentTarget1.toLocaleString("jp",{style:"currency",currency:"JPY"});
					// 	return(monthlyPaymentTargetComma1);
					// },
					// 初回お支払額1
					initialPayment1: function() {
						let estiPay      = Number(this.estimatesPrice1.replace(/[^0-9]/g, ''));		// 見積もり合計
						let monthlyPay   = Number(this.monthlyPayment1.replace(/[^0-9]/g, ''));		// 月々お支払額定義
						let numPay 	 = Number(this.numberPayments1);				// 分割支払い回数
						let firstPayment = estiPay - monthlyPay * (numPay - 1); 			// 見積金額－月々お支払額×（分割支払い回数－１）
						// console.log("estiPay=", estiPay, " monthlyPay=", monthlyPay, " numPay=", numPay, " firstPayment=", firstPayment);
						let firstPaymentComma = firstPayment.toLocaleString("jp",{style:"currency",currency:"JPY"}); // 3桁ごとにカンマと円をつける
						return firstPaymentComma.toLocaleString() || 0;
					},
					// 初回お支払額2
					initialPayment2: function() {
						let estiPay      = Number(this.estimatesPrice2.replace(/[^0-9]/g, ''));		// 見積もり合計
						let monthlyPay   = Number(this.monthlyPayment2.replace(/[^0-9]/g, ''));		// 月々お支払額定義
						let numPay 	 = Number(this.numberPayments2);				// 分割支払い回数
						let firstPayment = estiPay - monthlyPay * (numPay - 1); 			// 頭金＋月々お支払額定義
						let firstPaymentComma = firstPayment.toLocaleString("jp",{style:"currency",currency:"JPY"}); // 3桁ごとにカンマと円をつける
						return firstPaymentComma.toLocaleString() || 0;
					},
					// 初回お支払額3
					initialPayment3: function() {
						let estiPay      = Number(this.estimatesPrice3.replace(/[^0-9]/g, ''));		// 見積もり合計
						let monthlyPay   = Number(this.monthlyPayment3.replace(/[^0-9]/g, ''));		// 月々お支払額定義
						let numPay 	 = Number(this.numberPayments3);				// 分割支払い回数
						let firstPayment = estiPay - monthlyPay * (numPay - 1); 			// 頭金＋月々お支払額定義
						let firstPaymentComma = firstPayment.toLocaleString("jp",{style:"currency",currency:"JPY"}); // 3桁ごとにカンマと円をつける
						return firstPaymentComma.toLocaleString() || 0;
					},
					// 月々お支払額
					monthlyPayment1: function() {
						let estiPay1 = Number(this.estimatesPrice1.replace(/[^0-9]/g, ''));		// 見積もり合計
						let depoPay1 = Number(this.downPayment1.replace(/[^0-9]/g, ''));		// 頭金
						let numPay1  = Number(this.numberPayments1);					// 分割支払い回数
						var monthlyPaymentTarget = Math.round((estiPay1 - depoPay1) / numPay1) || 0;
						// 通貨記号と３桁ごとにカンマを付ける
						var monthlyPaymentTargetComma = monthlyPaymentTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						// return monthlyPaymentTargetComma.toLocaleString();
						return monthlyPaymentTargetComma.toLocaleString();
					},
					// 月々お支払額2
					monthlyPayment2: function() {
						let estiPay2 = Number(this.estimatesPrice2.replace(/[^0-9]/g, ''));		// 見積もり合計
						let depoPay2 = Number(this.downPayment2.replace(/[^0-9]/g, ''));		// 頭金
						let numPay2  = Number(this.numberPayments2);					// 分割支払い回数
						var monthlyPaymentTarget = Math.round((estiPay2 - depoPay2) / numPay2) || 0;
						// 通貨記号と３桁ごとにカンマを付ける
						var monthlyPaymentTargetComma = monthlyPaymentTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						// return monthlyPaymentTargetComma.toLocaleString();
						return monthlyPaymentTargetComma.toLocaleString();
					},
					// 月々お支払額3
					monthlyPayment3: function() {
						let estiPay3 = Number(this.estimatesPrice3.replace(/[^0-9]/g, ''));		// 見積もり合計
						let depoPay3 = Number(this.downPayment3.replace(/[^0-9]/g, ''));		// 頭金
						let numPay3  = Number(this.numberPayments3);					// 分割支払い回数
						var monthlyPaymentTarget = Math.round((estiPay3 - depoPay3) / numPay3) || 0;
						// 通貨記号と３桁ごとにカンマを付ける
						var monthlyPaymentTargetComma = monthlyPaymentTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						// return monthlyPaymentTargetComma.toLocaleString();
						return monthlyPaymentTargetComma.toLocaleString();
					},
					// 事務管理手数料1
					adminFee1: function() {
						var adminFeeTarget = Math.ceil((this.adminPer1 * this.shokei.replace(/[^0-9]/g, '')) / 100) || 0;
						// 通貨記号と３桁ごとにカンマを付ける
						var adminFeeTargetComma = adminFeeTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						return adminFeeTargetComma.toLocaleString();
					},
					// 事務管理手数料2
					adminFee2: function() {
						var adminFeeTarget = Math.ceil((this.adminPer2 * this.shokei.replace(/[^0-9]/g, '')) / 100) || 0;
						// 通貨記号と３桁ごとにカンマを付ける
						var adminFeeTargetComma = adminFeeTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						return adminFeeTargetComma.toLocaleString();
					},
					// 事務管理手数料3
					adminFee3: function() {
						var adminFeeTarget = Math.ceil((this.adminPer3 * this.shokei.replace(/[^0-9]/g, '')) / 100) || 0;
						// 通貨記号と３桁ごとにカンマを付ける
						var adminFeeTargetComma = adminFeeTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						return adminFeeTargetComma.toLocaleString();
					},
					// 見積もり金額
					estimatesPrice1: function() {
						let estimatesPriceTarget = Number(this.shokei.replace(/[^0-9]/g, '')) + Number(this.adminFee1.replace(/[^0-9]/g, '')) || 0;
						let estimatesPriceTargetComma = estimatesPriceTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						return estimatesPriceTargetComma.toLocaleString();
					},
					estimatesPrice2: function() {
						let estimatesPriceTarget = Number(this.shokei.replace(/[^0-9]/g, '')) + Number(this.adminFee2.replace(/[^0-9]/g, '')) || 0;
						let estimatesPriceTargetComma = estimatesPriceTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						return estimatesPriceTargetComma.toLocaleString();
					},
					estimatesPrice3: function() {
						let estimatesPriceTarget = Number(this.shokei.replace(/[^0-9]/g, '')) + Number(this.adminFee3.replace(/[^0-9]/g, '')) || 0;
						let estimatesPriceTargetComma = estimatesPriceTarget.toLocaleString("jp",{style:"currency",currency:"JPY"});
						return estimatesPriceTargetComma.toLocaleString();
					},
				}
			});
		})();
	</script>
</body>
</html>