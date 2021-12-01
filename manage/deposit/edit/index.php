<?php
session_start();
require_once(__DIR__ . '/../../../common/dbconnect.php');
require_once(__DIR__ . '/../../common/functions.php');
require_once(__DIR__ . '/../func_dep.php');

if (empty($_SESSION['loginID'])) {
	header("Location:/");
	exit();
}

if (empty($_SESSION['dep_err'])) {
	$_SESSION['dep_err'] = "";
}

if (empty($_GET['id'])) {
	header("Location:/");
	exit();
} else {
	$qu_id = $_GET['id'];
}

//入金消込->リマインドのキャンセル
if (!empty($_GET['new'])) {
    $sql = sprintf('UPDATE `quotation` SET notice_flag=1 WHERE qu_id="%d"', mysqli_real_escape_string($db, $qu_id) );
    mysqli_query($db, $sql) or die(mysqli_error($db));
    quotation();
}

$d_yymmdd  = "";
$d_money   = "";
$d_comment = "";

if (!empty($_POST['send2']) && $_POST['send2'] == "更新") {
	// print_r($_POST);
	// exit();

	$d_yymmdd  = $_POST['yymmdd'];
	$d_money   = $_POST['money'];
	$d_comment = $_POST['comment'];

	$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d" AND delFlag=0',
		mysqli_real_escape_string($db, $qu_id)
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row9 = mysqli_fetch_assoc($record);

	// list($erase_money, $erase_money_mi) = erase_get($qu_id, $row9['q_alltotal'], $row9['erase_money'], $row9['erase_money_mi']);

	// if (($erase_money_mi - $_POST['money']) < 0) {
	//	$url_para = "/manage/deposit/edit/dep_insert.php?id=". $_GET['id']. "&dl_id=". $_POST['dl_id']. "&yymmdd=". $_POST['yymmdd']. "&money=". $_POST['money']. "&comment=". $_POST['comment'];
	//	$url = '<script>if (window.confirm("入金消込後残高がマイナスとなりますが\n宜しいですか？")) { location.href = "'. $url_para. '";}</script>';
	// } else {
		$url_para = "/manage/deposit/edit/dep_insert.php?id=". $_GET['id']. "&dl_id=". $_POST['dl_id']. "&yymmdd=". $_POST['yymmdd']. "&money=". $_POST['money']. "&comment=". $_POST['comment'];
		$url = '<script>if (window.confirm("更新します。\n宜しいですか？")) { location.href = "'. $url_para. '";}</script>';
	// }
	echo $url;
}

$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row9 = mysqli_fetch_assoc($record);

list($erase_money, $erase_money_mi) = erase_get($qu_id, $row9['q_alltotal'], $row9['erase_money'], $row9['erase_money_mi']);

$sql = sprintf('SELECT * FROM `dep_list` WHERE qu_id="%d" AND delFlag=0
		ORDER BY dl_id DESC',
	mysqli_real_escape_string($db, $qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$ix =0;
while($row0 = mysqli_fetch_assoc($record)) {
	$dl_id[$ix]      = $row0['dl_id'];
	// $qu_id[$ix]      = $row0['qu_id'];
	$dl_yymmdd[$ix]  = date('Y年m月d日', strtotime($row0['dl_yymmdd']));
	$dl_money[$ix]   = number_format($row0['dl_money']);
	$dl_comment[$ix] = $row0['dl_comment'];
	$ix++;
}
$dl_count = $ix;

$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row7 = mysqli_fetch_assoc($record);
?>
<?php require_once(__DIR__ . '/../../common/header.php'); ?>
	<style>
		.simulation_sub_info2 {
		  width: 100%;
		}
		.simulation_sub_info3 {
		  width: 100%;
		}
		@media (min-width: 896px) {
			.simulation_sub_info3 {
				margin: auto 670px 30px;
			}
		}
		.simulation_sub_info4 {
		  /* display: inline-block; */
		  width: 100%;
		  
		}
		@media screen and (max-width: 895px) {
			.simulation_sub_info2,
			.simulation_sub_info3 {
				margin-bottom: 20px;
			}
		}
		@media screen and (min-width: 896px) {
		  .simulation_sub_info2 {
		    width: 600px;
		    height: 60px;
		    margin: auto 30px 30px;
		  }
		  .simulation_sub_info3 {
		    width: 300px;
		  }
		  .simulation_sub_info4 {
		    width: 800px;
		    margin: 30px 30px 50px;
		  }
		}

		.simulation_sub_info4 table th.depositCancellation {
			white-space: nowrap;
			background-color:#D0B0FF;
			text-align: center;
		}
		@media screen and (max-width: 895px) {
			.simulation_sub_info4 table tr {
				display: block;
				width: 50%;
				float: left;
			}
			.simulation_sub_info4 table tr th {
				width: 100%;
			}
			.simulation_sub_info4 table tr td {
				width: 100%;
				line-height: 1;
				font-size: 12px;
			}
			.simulation_sub_info4 table tr td input {
				text-align: right;
			}
		}
		@media screen and (min-width: 896px) {
			.simulation_sub_info4 table th.depositCancellation {
				width: 15%;
			}
			.simulation_sub_info4 table th.depositCancellation:nth-child(3) {
				width:40%;
			}
		}
		

		.simulation_sub_info4 table th,
		.simulation_sub_info4 table td,
		.simulation_sub_info3 table th,
		.simulation_sub_info3 table td,
		.simulation_sub_info2 table th,
		.simulation_sub_info2 table td {
		  border: 1px solid #e0e0e0;
		  padding: 10px;
		}
		@media screen and (max-width: 479px) {
		  .simulation_sub_info4 table th,
		  .simulation_sub_info4 table td,
		  .simulation_sub_info3 table th,
		  .simulation_sub_info3 table td,
		  .simulation_sub_info2 table th,
		  .simulation_sub_info2 table td {
		    display: block;
		    width: 50%;
		    float: left;
		  }
		}
		.simulation_sub_info4 table th,
		.simulation_sub_info3 table th,
		.simulation_sub_info2 table th {
		  background: #eee;
		}
		.simulation_sub_info4 table td,
		.simulation_sub_info3 table td,
		.simulation_sub_info2 table td {
		  text-align: right;
		}
		.main_title_bottom2 {
		  text-align: center;
		  margin-top: 20px;
		}
		.main_title_bottom2.position_right2 {
		  margin-top: 0;
		}
		@media screen and (min-width: 896px) {
		  .main_title_bottom2.position_right2 {
		    position: absolute;
		    right: 25%;
		    top: 42%;  /* 39% */
		    /* transform: translateY(-61%); */
		  }
		}
		@media screen and (max-width: 895px) {
		  .main_title_bottom2.position_right2 .position_right_inner {
		    text-align: center;
		  }
		  .main_title_bottom2.position_right2 .position_right_inner ul {
		    display: inline-block;
		  }
		}
		@media screen and (min-width: 896px) {
		  .main_title_bottom2 ul li input {
		    color:#fff;
		    padding: 15px 45px;
		    border-radius: 3px;
		    background-color:#87CEEB;
		  }
		}
		table.common_table thead.common_table_thead2 {
		  border-bottom: 1px solid #EAEAEA;
		}
		table.common_table thead.common_table_thead2 tr th {
		  line-height: 1.3;
		  padding: 10px 0px;
		  color: #9A9A9A;
		  font-size: 12px;
		}
		@media screen and (min-width: 896px) {
		  table.common_table thead.common_table_thead2 tr th {
		    text-align: center;
		    /* padding-left: 20px; */
		}
		table.common_table thead.common_table_tbody {
			border-bottom: 1px solid #EAEAEA;
		}
		table.common_table thead.common_table_tbody tr th {
			line-height: 1.3;
			padding: 10px 0px;
			color: #9A9A9A;
			font-size: 12px;
		}
		@media screen and (min-width: 896px) {
		  table.common_table thead.common_table_tbody tr th {
		    /* text-align: center; */
		    padding-left: 20px;
		}

		table.common_table td.common_table_cell2,
		table.common_table th.common_table_cell2 {
		  position: relative;
		}
		@media screen and (min-width: 896px) {
		  table.common_table td.common_table_cell2,
		  table.common_table th.common_table_cell2 {
		    text-align: center;
		  }
		  table.common_table td.common_table_cell2:nth-child(4),
		  table.common_table th.common_table_cell2:nth-child(4) {
		    text-align: left;
		  }
		}
		@media screen and (max-width: 895px) {
		  table.common_table td.common_table_cell2,
		  table.common_table th.common_table_cell2 {
		    display: block;
		  }
		}
		@media screen and (min-width: 896px) {
		  table.common_table td.common_table_cell2,
		  table.common_table th.common_table_cell2 {
		    padding: 7px;
		  }
		}
		@media screen and (max-width: 895px) {
		  table.customerdata_table > tbody > tr td.common_table_cell2 {
		    zoom: 1;
		  }
		  table.customerdata_table > tbody > tr td.common_table_cell2:before, table.customerdata_table > tbody > tr td.common_table_cell2:after {
		    content: "";
		    display: table;
		  }
		  table.customerdata_table > tbody > tr td.common_table_cell2:after {
		    clear: both;
		  }
		  table.customerdata_table > tbody > tr td.common_table_cell2:nth-child(n+4) {
		    text-align: left;
		    padding: 0px 15px;
		    border-right: 1px solid #EAEAEA;
		    border-left: 1px solid #EAEAEA;
		    background: #F8F8F8;
		    line-height: 2;
		    display: none;
		    opacity: 0;
		  }
		  table.customerdata_table > tbody > tr td.common_table_cell2:nth-child(n+4).open_detail {
		    display: block;
		    animation-name: openDetail;
		    animation-duration: .3s;
		    animation-fill-mode: forwards;
		  }
		  table.customerdata_table > tbody > tr td.common_table_cell2:nth-child(4) {
		    border-top: 1px solid #EAEAEA;
		    margin-top: 10px;
		    padding-top: 15px;
		  }
		  table.customerdata_table > tbody > tr td.common_table_cell2:last-child {
		    border-bottom: 1px solid #EAEAEA;
		    padding-bottom: 10px;
		  }
		  table.customerdata_table > tbody > tr td.common_table_cell2 .sp_customerdata_name {
		    display: block;
		    float: left;
		    width: 95px;
		    text-align: left;
		  }
		  table.customerdata_table > tbody > tr td.common_table_cell2 .customerdata_item {
		    display: block;
		    float: right;
		    padding-left: 95px;
		    margin-left: -95px;
		    width: 100%;
		    text-align: left;
		  }
		}
		@media screen and (min-width: 896px) {
		  table.customerdata_table > tbody > tr td.common_table_cell2 .sp_customerdata_name {
		    display: none;
		  }
		}
		.common_table_cell2.edit_menu ul li {
			display: inline-block;
		}
		.common_table_cell2.edit_menu ul li a,
		.common_table_cell2.edit_menu ul li input[type=submit] {
			display: inline-block;
			font-size: 12px;
			border-radius: 2px;
			padding: 5px 10px;
			background-color: #9AD1B8;
			color: #fff;
		}
		@media screen and (max-width: 895px) {
		  table.simulatoin_table tbody tr.common_table_tr td.common_table_cell2 {
		    display: table-cell;
		    padding: 5px 3px;
		  }
		}
		.submit_hover input:hover {
			opacity: 0.7;
		}
	</style>
	<link rel="stylesheet" type="text/css" href="/manage/common/css/jquery.datetimepicker.css">
	<main id="app" class="customer_data">
		<div class="main_inner">
			<?php require_once(__DIR__ . '/../../common/grobal-menu.php'); ?>
			<div class="main_wrap">
				<div class="main_title">
					<div class="all_wrapper sp_all">
						<div class="main_pankuzu">
							<ul>
								<li><span><a href="/manage/deposit/" class="text_link">入金消込</a></span></li>
								<li><span>入金消込一覧</span></li>
							</ul>
						</div>
						<div class="main_title_inner">
							<div class="main_title_top">
								<p class="title" style="font-size:28px;">入金消込一覧</p>
							</div>
							<?php if ($_SESSION['dep_err'] == "UPOK") { ?>
								<p class="error_message" style="font-size:16px;">更新しました</p>
								<?php $_SESSION['dep_err'] = ""; ?>
							<?php } ?>
							<?php if ($_SESSION['dep_err'] == "UPNG") { ?>
								<p class="error_message" style="font-size:16px;">請求書番号が見つかりませんでした。「入金消込画面」から再度、実施をお願いします。</p>
								<?php $_SESSION['dep_err'] = ""; ?>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="main_content simulation_content">
					<div class="all_wrapper">
						<div id="simulation_one" class="simulation_field open_field">
							<div class="main_content_inner">
								<div class="main_content_wrap">
									<div class="simulation_sub_info2">
										<table>
											<tbody>
												<tr class="cf">
													<th style="white-space: nowrap">返済予定開始日</th>
													<td><?php echo date('Y年m月d日',  strtotime($row9['qu_startDate'])); ?></td>
													<th>初回お支払額</th>
													<td>¥<?php echo number_format($row9['qu_initPayAmount']); ?></td>
												</tr>
												<tr class="cf">
													<th>ご利用予定金額</th>
													<td>¥<?php echo number_format($row9['qu_price']); ?></td>
													<th>月々お支払額</th>
													<td>¥<?php echo number_format($row9['qu_amount_pay']); ?></td>
												</tr>
												<tr class="cf">
													<th>頭金</th>
													<td>¥<?php echo number_format($row9['qu_deposit']); ?></td>
													<th style="white-space: nowrap">割賦手数料総額</th>
													<td>¥<?php echo number_format($row9['qu_commit']); ?></td>
												</tr>
												<tr class="cf">
													<th>分割回数</th>
													<td><?php echo $row9['qu_installments']; ?>回</td>
													<th class="total">総支払額</th>
													<td class="total">¥<?php echo number_format($row9['q_alltotal']); ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="simulation_sub_info3">
										<table>
											<tbody>
												<tr class="cf">
													<th style="white-space: nowrap; background-color:#D0B0FF;">総入金金額</th>
													<td>¥<?php echo number_format($erase_money); ?></td>
												</tr>
												<tr class="cf">
													<th style="white-space: nowrap; background-color:#D0B0FF;">入金消込後残高</th>
													<td>¥<?php echo number_format($erase_money_mi); ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div  style="border-bottom: 1px solid #EAEAEA;"></div>
									<form action="" method="post">
										<div class="simulation_sub_info4">
											<table>
												<tr>
													<th class="depositCancellation">入金日付</th>
													<th class="depositCancellation">入金金額</th>
													<th class="depositCancellation">備考</th>
													<th class="depositCancellation" style="display: none;"></th>
													<!-- <th></th> -->
												</tr>
												<tr>
													<div id="dl_id2"><input type="hidden" name="dl_id" value="0"></div>
													<td id="dl_date2">
														<vuejs-datepicker
															:format="customFormatter"
															:language="ja"
															class="input-border-none1"
															required="required"
															name="yymmdd"
															v-model="paymentStart"
														>
														</vuejs-datepicker>
														<!-- <input type="date" style="border:solid 0px; width:100%; box-sizing:border-box;" name="yymmdd" value="<?php echo $d_yymmdd; ?>" required> -->
													</td>
													<td id="dl_money2">
														<input type="text" name="money" value="<?php echo $d_money; ?>" id="in_money" pattern="^[0-9-]+$" maxlength="10" onchange="on_money()" title="半角数字のみ" required>
													</td>
													<td id="dl_comment2">
														<input type="text" name="comment" value="<?php echo $d_comment; ?>">
													</td>
													<td class="submit_hover" style="border: 0px none; text-align:center;">
														<?php if ($row7['exeFlag'] == 0) { ?>
															<input type="submit" style="color:#fff; padding: 10px 35px; border-radius: 3px; background-color:#87CEEB;" name="send2" value="更新">
														<?php } else { ?>
															<!-- <a class="disable" style="color:#fff; padding: 10px 35px; border-radius: 3px; background-color:#87CEEB;">更新</a> -->
															<a class="disable" style="color:#fff; padding: 10px 35px; border-radius: 3px; background-color:#87CEEB; opacity:1.0;">更新</a>
														<?php } ?>
													</td>
												</tr>
											</table>
										</div>
									</form>
									<p class="text-right simulate_sub">（単位：円）</p>
									<table class="common_table simulatoin_table">
										<thead class="common_table_thead2">
											<tr class="common_table_tr">
												<th>入金回数</th>
												<th>入金日付</th>
												<th>入金金額</th>
												<th class="common_table_cell2">備　考</th>
											</tr>
										</thead>
										<tbody class="common_table_tbody">
											<?php for($ix=0; $ix<$dl_count; $ix++) { ?>
												<tr class="common_table_tr">
													<td class="common_table_cell2"><div ondblclick="myFnc(<?php echo $dl_id[$ix]; ?>);"><?php echo ($ix + 1); ?></div></td>
													<td class="common_table_cell2"><div ondblclick="myFnc(<?php echo $dl_id[$ix]; ?>);"><?php echo $dl_yymmdd[$ix]; ?></div></td>
													<td class="common_table_cell2"><div ondblclick="myFnc(<?php echo $dl_id[$ix]; ?>);"><?php echo $dl_money[$ix]; ?></div></td>
													<td class="common_table_cell2"><div ondblclick="myFnc(<?php echo $dl_id[$ix]; ?>);"><?php echo $dl_comment[$ix]; ?></div></td>
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
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>　<!-- vue.js -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="/manage/common/js/customer-data.js"></script>
	<script src='https://ws1.sinclo.jp/client/5e7812fdb5a66.js'></script>
	<script src="/manage/deposit/edit/js/ajax_list.js"></script>
	<!-- vue.js data-picker -->
	<script src="https://unpkg.com/vuejs-datepicker"></script>
	<script src="https://cdn.jsdelivr.net/npm/vuejs-datepicker@1.6.2/dist/locale/translations/ja.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment.min.js"></script> <!-- 日付の計算 -->
	<script>
		function on_money() {
			var str = document.getElementById("in_money").value;
			// console.log("str=", str);
			document.getElementById("in_money").value = Number(str);
		}
	</script>
	<script>
		var estimateCalculate = new Vue ({
		  el: '#app',
			data:function() {
				return {
					ja: vdp_translation_ja.js,
					paymentStart: "<?php echo $dl_yymmdd; ?>"
				}
		  	}, 
			components: {
				'vuejs-datepicker':vuejsDatepicker
			},
			methods: {
				customFormatter: function(date) {
					return moment(date).format('YYYY/MM/DD');
				},
			}
		})
	</script>
</body>
</html>
