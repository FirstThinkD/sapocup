<?php
session_start();
require_once(__DIR__ . '/../../common/dbconnect.php');
require_once(__DIR__ . '/../common/functions.php');

if ($_SESSION['loginID'] == "") {
	header("Location:/");
	exit();
}

$message = "";
$message2 = "";
if (!empty($_POST['paygent_btn']) && $_POST['paygent_btn'] == "変更") {

    $_SESSION['verifi_url']  = "/manage/user/";
    $_SESSION['customer_id'] = $_SESSION['loginID'];
    // $_SESSION['connect_id']  = $connect_id;
    $_SESSION['PAY_SERVICE'] = SERVICE1;
    $_SESSION['CONNECT_PS']  = CONNECT_PASSWORD;

    header("Location: /paygent/index.php?url=/paygent/change_response.php");
    exit();
}
if (!empty($_POST['send2']) && $_POST['send2'] == "会員情報更新") {
	if (false === strpos($_POST['s_tel'], "090") && false === strpos($_POST['s_tel'], "080") && false === strpos($_POST['s_tel'], "070")) {
		$message = "tel_no";
	}
	if (strlen($_POST['s_tel']) != 11) {
		$message = "tel_no";
	}

	if ($message == "") {
		if ($_POST['s_passwd'] != "") {
			$passwd = md5($_POST['s_passwd']);

			$sql = sprintf('SELECT * FROM `user` WHERE (u_email="%s" OR p_email="%s") AND (u_pass="%s" OR p_pass="%s") AND delFlag=0',
				mysqli_real_escape_string($db, $_POST['s_email']),
				mysqli_real_escape_string($db, $_POST['s_email']),
				mysqli_real_escape_string($db, $passwd),
				mysqli_real_escape_string($db, $passwd)
			);
			$record = mysqli_query($db, $sql) or die(mysqli_error($db));
			if ($row0 = mysqli_fetch_assoc($record)) {
				// 重複
				$message = "passNG";
			}
		}
	}
	if ($message == "") {
		$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
			mysqli_real_escape_string($db, $_SESSION['loginID'])
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		if ($row0 = mysqli_fetch_assoc($record)) {
			if ($row0['u_type'] == "法人") {
				$s_postal = $_POST['s_postal11']. "-". $_POST['s_postal22'];
				$sql = sprintf('UPDATE user SET u_company="%s", u_ceo="%s",
					u_postal="%s", u_address1="%s", u_address2="%s", u_address3="%s",
					u_tel="%s", u_email="%s" WHERE u_id="%d"',
					mysqli_real_escape_string($db, $_POST['s_company']),
					mysqli_real_escape_string($db, $_POST['s_name']),
					mysqli_real_escape_string($db, $s_postal),
					mysqli_real_escape_string($db, $_POST['s_address1']),
					mysqli_real_escape_string($db, $_POST['s_address2']),
					mysqli_real_escape_string($db, $_POST['s_address3']),
					mysqli_real_escape_string($db, $_POST['s_tel']),
					mysqli_real_escape_string($db, $_POST['s_email']),
//					mysqli_real_escape_string($db, $_POST['s_card_id']),
//					mysqli_real_escape_string($db, $_POST['s_strdate']),
					mysqli_real_escape_string($db, $_SESSION['loginID'])
				);
				mysqli_query($db,$sql) or die(mysqli_error($db));

				if ($_POST['s_passwd'] != "") {
					$passwd = md5($_POST['s_passwd']);

					$sql = sprintf('UPDATE user SET u_pass="%s" WHERE u_id="%d"',
						mysqli_real_escape_string($db, $passwd),
						mysqli_real_escape_string($db, $_SESSION['loginID'])
					);
					mysqli_query($db,$sql) or die(mysqli_error($db));
					$message = "passOK";
				}
			} else {
				$s_postal = $_POST['s_postal11']. "-". $_POST['s_postal22'];
				$sql = sprintf('UPDATE user SET p_name="%s",
					p_postal="%s", p_address1="%s", p_address2="%s", p_address3="%s",
					p_tel="%s", p_email="%s", customer_card_id="%s", strdate="%s" WHERE u_id="%d"',
					mysqli_real_escape_string($db, $_POST['s_name']),
					mysqli_real_escape_string($db, $s_postal),
					mysqli_real_escape_string($db, $_POST['s_address1']),
					mysqli_real_escape_string($db, $_POST['s_address2']),
					mysqli_real_escape_string($db, $_POST['s_address3']),
					mysqli_real_escape_string($db, $_POST['s_tel']),
					mysqli_real_escape_string($db, $_POST['s_email']),
                    mysqli_real_escape_string($db, $_POST['s_card_id']),
					mysqli_real_escape_string($db, $_POST['s_strdate']),
					mysqli_real_escape_string($db, $_SESSION['loginID'])
				);
				mysqli_query($db,$sql) or die(mysqli_error($db));

				if ($_POST['s_passwd'] != "") {
					$passwd = md5($_POST['s_passwd']);

					$sql = sprintf('UPDATE user SET p_pass="%s" WHERE u_id="%d"',
						mysqli_real_escape_string($db, $passwd),
						mysqli_real_escape_string($db, $_SESSION['loginID'])
					);
					mysqli_query($db,$sql) or die(mysqli_error($db));
					$message = "passOK";
				}
			}
			if ($message == "") {
				$message = "editOK";
			}

			if ($message == "passOK") {
				$to = $_POST['s_email'];
				$title = "【さぽかっぷ】パスワード変更完了のお知らせ";
				$body  = "※このメールはシステムからの自動返信です". "\r\n\r\n";
				$body .= $_POST['s_name']. " 様". "\r\n\r\n";
				$body .= "「さぽかっぷ」運営企業の株式会社ANBIENCEでございます。". "\r\n";
				$body .= "いつも、「さぽかっぷ」をご利用いただき誠にありがとうございます。". "\r\n";
				$body .= "パスワードの変更手続きは、完了いたしました。". "\r\n\r\n";
				$body .= "このメールは、ご登録時に確認のため送信させていただいております。". "\r\n";
				$body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━". "\r\n";
				$body .= "■お客様の会員番号（ログインID）". "\r\n";
				$body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━". "\r\n";
				//$body .= "ログインID：". $to. "\r\n";
				$body .= "会員番号　：". $row0['u_id']. "\r\n\r\n";
				$body .= "ログインIDおよびパスワードは、". "\r\n";
				$body .= "「さぽかっぷ」のサービスにログインする際に必要となります。". "\r\n";
				$body .= "忘れず保管をお願いいたします。". "\r\n\r\n";
				$body .= "また、ご不明な点がございましたら". "\r\n";
				$body .= "下記までお気軽にお問い合わせくださいませ。". "\r\n\r\n";
				$body .= "――――――――――――". "\r\n";
				$body .= "「さぽかっぷ」". "\r\n";
				$body .= "URL：https://sapocup.jp/contact.php". "\r\n";
				$body .= "メールアドレス：sapocup01@sapocup.jp". "\r\n\r\n";
				$body .= "営業時間：　平日 10:00～17:00". "\r\n\r\n";
				$body .= "【運営元：株式会社AMBIENCE】". "\r\n";
				$body .= "住所：〒103-0025　東京都中央区日本橋茅場町3-7-6". "\r\n\r\n";

				$header  = "From:sapocup01@sapocup.jp". "\r\n";
				$header .= "Bcc:sapocup01@sapocup.jp". "\r\n";
				// $header .= "Bcc:chankan77@gmail.com". "\r\n";
				// $header .= "Bcc:nakazawa6097@gmail.com". "\r\n";

				mb_language('ja');
				mb_internal_encoding('UTF-8');
				mb_send_mail($to, $title, $body, $header);
			}

			if ($_POST['s_email'] != $_POST['s_email2']) {
				$message2 = "emailOK";

				$to = $_POST['s_email'];
				$title = "【さぽかっぷ】ログインID（メールアドレス）変更完了のお知らせ";
				$body  = "※このメールはシステムからの自動返信です". "\r\n\r\n";
				$body .= $_POST['s_name']. " 様". "\r\n\r\n";
				$body .= "「さぽかっぷ」運営企業の株式会社ANBIENCEでございます。". "\r\n";
				$body .= "いつも、「さぽかっぷ」をご利用いただき誠にありがとうございます。". "\r\n";
				$body .= "ログインID(メールアドレス)の変更手続きは、完了いたしました。". "\r\n\r\n";
				$body .= "このメールは、ご登録時に確認のため送信させていただいております。". "\r\n";
				$body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━". "\r\n";
				$body .= "■お客様の会員番号（ログインID）". "\r\n";
				$body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━". "\r\n";
				$body .= "会員番号：". $row0['u_id']. "\r\n\r\n";
				$body .= "ログインIDおよびパスワードは、". "\r\n";
				$body .= "「さぽかっぷ」のサービスにログインする際に必要となります。". "\r\n";
				$body .= "忘れず保管をお願いいたします。". "\r\n\r\n";
				$body .= "また、ご不明な点がございましたら". "\r\n";
				$body .= "下記までお気軽にお問い合せくださいませ。". "\r\n";
				$body .= "――――――――――――". "\r\n\r\n";
				$body .= "「さぽかっぷ」". "\r\n";
				$body .= "URL：https://sapocup.jp/contact.php". "\r\n";
				$body .= "メールアドレス：sapocup01@sapocup.jp". "\r\n\r\n";
				$body .= "営業時間：　平日 10:00～17:00". "\r\n\r\n";
				$body .= "【運営元：株式会社AMBIENCE】". "\r\n";
				$body .= "住所：〒103-0025　東京都中央区日本橋茅場町3-7-6". "\r\n\r\n";

				$header  = "From:sapocup01@sapocup.jp". "\r\n";
				$header .= "Bcc:sapocup01@sapocup.jp". "\r\n";
				// $header .= "Bcc:chankan77@gmail.com". "\r\n";
				// $header .= "Bcc:nakazawa6097@gmail.com". "\r\n";

				mb_language('ja');
				mb_internal_encoding('UTF-8');
				mb_send_mail($to, $title, $body, $header);
			}
		}
	}
}

$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_SESSION['loginID'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
if ($row7 = mysqli_fetch_assoc($record)) {
	// OK
	$s_id   = $row7['u_id'];
	$u_type = $row7['u_type'];
	if ($u_type == "法人") {
		$s_passwd   = $row7['u_pass'];
		$s_name     = $row7['u_ceo'];
		$s_company  = $row7['u_company'];
		$s_postal   = $row7['u_postal'];
		$s_address1 = $row7['u_address1'];
		$s_address2 = $row7['u_address2'];
		$s_address3 = $row7['u_address3'];
		$s_email    = $row7['u_email'];
		$s_tel      = $row7['u_tel'];
		if ($s_postal != "") {
		  $str = strstr($s_postal, '-', TRUE);
		  if ($str != "") {
		    $s_postal1 = $str;
		    $s_postal2 = substr($s_postal, (strpos($s_postal, '-') + 1));
		  } else {
		      $leng = strlen($s_postal);
		      if ($leng > 2) {
		        $s_postal1 = substr($s_postal, 0, 3);
		        $s_postal2 = substr($s_postal, 3);
		      } else {
		        $s_postal1 = substr($s_postal, 0, $leng);
		        $s_postal2 = "";
		      }
		  }
		} else {
		  $s_postal1 = "";
		  $s_postal2 = "";
		}
	} else {
		$s_passwd   = $row7['p_pass'];
		$s_name     = $row7['p_name'];
		$s_postal   = $row7['p_postal'];
		$s_address1 = $row7['p_address1'];
		$s_address2 = $row7['p_address2'];
		$s_address3 = $row7['p_address3'];
		$s_email    = $row7['p_email'];
		$s_tel      = $row7['p_tel'];
		if ($s_postal != "") {
		  $str = strstr($s_postal, '-', TRUE);
		  if ($str != "") {
		    $s_postal1 = $str;
		    $s_postal2 = substr($s_postal, (strpos($s_postal, '-') + 1));
		  } else {
		      $leng = strlen($s_postal);
		      if ($leng > 2) {
		        $s_postal1 = substr($s_postal, 0, 3);
		        $s_postal2 = substr($s_postal, 3);
		      } else {
		        $s_postal1 = substr($s_postal, 0, $leng);
		        $s_postal2 = "";
		      }
		  }
		} else {
		  $s_postal1 = "";
		  $s_postal2 = "";
		}
	}
	$s_exeFlag = $row7['exeFlag'];
	$s_strdate = $row7['strdate'];
//    $s_card_id  = $row7['customer_card_id'];
	$s_enddate = $row7['enddate'];
} else {
	// NG
}


//if ($row7['exeFlag'] == 1)
{
	$sql = sprintf('SELECT * FROM `paygent` WHERE u_id="%d" AND ptn=1',
		mysqli_real_escape_string($db, $_SESSION['loginID'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$row8 = mysqli_fetch_assoc($record);
    if(!empty($row8)){
        $s_card_id = $row8['masked_card_number1'];
        $s_strdate = $row8['str_date'];
        $s_enddate = $row8['end_date'];
    }
}
?>
<?php require_once(__DIR__ . '/../common/header.php'); ?>
	<main>
		<div class="main_inner">
			<?php require_once(__DIR__ . '/../common/grobal-menu.php'); ?>
			<div class="main_wrap">
				<div class="main_title">
					<div class="all_wrapper">
						<div class="main_pankuzu">
							<ul>
								<li><span><a href="/manage/data/" class="text_link">顧客データ</a></span></li>
								<li><span>会員情報</span></li>
							</ul>
						</div>
						<div class="main_title_inner">
							<div class="main_title_top">
								<p class="title">会員情報変更</p>
							</div>
						</div>
						<?php if ($message == "editOK") { ?>
							<p class="error_message">会員情報を更新しました。</p>
						<?php } ?>
						<?php if ($message == "passOK") { ?>
							<p class="error_message">パスワードのご変更手続きが完了いたしました。<br>ご登録いただいたメールアドレスに完了メールをお送りいたします。</p>
						<?php } ?>
						<?php if ($messa入会日ge == "passNG") { ?>
							<p class="error_message">パスワードが重複しています。<br>再度、パスワードを変更してください。</p>
						<?php } ?>
						<?php if ($message == "emailErr") { ?>
							<p class="error_message">Eメールアドレスが重複しています。別名で登録してください。</p>
						<?php } ?>
						<?php if ($message == "tel_no") { ?>
							<p class="error_message">携帯電話番号のみ11桁を指定してください。</p>
						<?php } ?>
						<?php if ($message2 == "emailOK") { ?>
							<p class="error_message">ログインID（メールアドレス）のご変更手続きが完了いたしました。<br>ご登録いただいたメールアドレスに完了メールをお送りいたします。</p>
						<?php } ?>
						<?php if (isset($_SESSION['user_exemess']) && $_SESSION['user_exemess'] == "userEnd") { ?>
							<p class="error_message">退会のお手続きが完了いたしました。<br>ご登録いただいたメールアドレスに完了メールをお送りいたします。</p>
							<?php $_SESSION['user_exemess'] = ""; ?>
						<?php } ?>
					</div>
				</div>
				<div class="main_content">
					<div class="all_wrapper sp_all">
						<div class="main_content_inner estimates_new_inner">
							<!-- <form action="" method="post" accept-charset="utf-8"> -->
								<div class="main_content_wrap estimates_new_wrap">
									<div class="estimates_new_content">
										<div class="estimates_new_content_inner cf">
											<div class="estimates_company_info">
												<form action="" method="post">
													<p class="company_title">会員詳細情報</p>
													<div class="estimates_company_info_inner">
														<table class="estimates_new_company user_detail">
															<tr>
																<th>会員番号</th>
																<td><input class="calculation_area" type="text" name="s_id" value="<?= h($s_id); ?>" readonly title="会員番号は変更できません"></td>
															</tr>
															<!--
															<tr>
																<th>パスワード<br>(半角英数字8桁)</th>
																<td><input id="password" type="password" name="s_passwd" minlength="8"></td>
															</tr>
															<tr>
																<th></th>
																<td style=" float:left; white-space: nowrap;"><input type="checkbox" id="passcheck" />パスワードを表示</td>
															</tr>
															-->
															<tr>
																<th>代表者名</th>
																<td><input type="text" name="s_name" value="<?= h($s_name); ?>" required></td>
															</tr>
															<?php if ($u_type == "法人") { ?>
															<tr>
																<th>会社名</th>
																<td><input type="text" name="s_company" value="<?= h($s_company); ?>" required></td>
															</tr>
															<?php } ?>
															<tr>
																<th>郵便番号</th>
																<td class="addressBox post"><input type="text" name="s_postal11" value="<?= h($s_postal1); ?>" maxlength="3" required>-<input type="text" name="s_postal22" value="<?= h($s_postal2); ?>" onKeyUp="AjaxZip3.zip2addr('s_postal11','s_postal22','s_address1','s_address2','s_address2');" maxlength="4" required></td>
															</tr>
															<tr>
																<th>都道府県</th>
																<td><input type="text" name="s_address1" value="<?= h($s_address1); ?>" required></td>
															</tr>
															<tr>
																<th>市区町村</th>
																<td><input type="text" name="s_address2" value="<?= h($s_address2); ?>" required></td>
															</tr>
															<tr>
																<th>番地</th>
																<td><input type="text" name="s_address3" value="<?= h($s_address3); ?>" required></td>
															</tr>
															<tr>
																<!-- <th>ID<br>（メールアドレス）</th> -->
																<th>メールアドレス</th>
																<td><input type="email" name="s_email" value="<?= h($s_email); ?>" title="IDは変更できません"></td>
																<input type="hidden" name="s_email2" value="<?= h($s_email); ?>">
															</tr>
															<tr>
																<th>連絡先(携帯電話)</th>
																<td><input type="text" name="s_tel" value="<?= h($s_tel); ?>" required></td>
															</tr>
															<tr>
																<th>クレジットカード情報変更</th>
																<td><?= h($s_card_id); ?>
                                                                    <input type="submit" name="paygent_btn" class="paygent_btn" value="変更">
                                                                </td>
															</tr>
															<tr>
																<th>入会日</th>
																<td><input class="calculation_area" type="text" name="s_strdate" value="<?= h($s_strdate); ?>" readonly title="変更できません"></td>
															</tr>
															<tr>
																<th>退会日</th>
																<td><input class="calculation_area" type="text" name="s_enddate" value="<?= h($s_enddate); ?>" readonly title="変更できません"></td>
															</tr>
														</table>
													</div>
													<div class="common_submit_button">
														<?php if ($row7['exeFlag'] == 0) { ?>
															<input type="submit" name="send2" value="会員情報更新">
														<?php } else { ?>
															<a class="disable">会員情報更新</a>
														<?php } ?>
													</div>
												</form>
											</div>
											<div class="estimates_company_info">
												<p class="company_title">ご契約中のサービス</p>
												<div class="estimates_company_info_inner sp_table_right">
													
													<table class="payment_filed">
														<thead>
															<tr>
																<th colspan="2">ご契約内容</th>
																<th>月額料金(税込)</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<th>導入</th>
																<th>会員登録</th>
																<?php if ($row7['exeFlag'] == 0) { ?>
																	<td rowspan="7">480円/月<a href="" onClick="send1(1, <?php echo $row7['service1_fl']; ?>, <?php echo $row7['service1_fl']; ?>);return false;" class="contract_info <?php if ($row7['service1_fl'] == 1) { echo 'contracted'; } ?>"><?php if ($row7['service1_fl'] == 1) { echo '解約'; } else { echo '選択'; } ?></a></td>
																<?php } else { ?>
																	<td rowspan="7">480円/月<a href="" class="disable contract_info <?php if ($row7['service1_fl'] == 1) { echo 'contracted'; } ?>"><?php if ($row7['service1_fl'] == 1) { echo '解約'; } else { echo '選択'; } ?></a></td>
																<?php } ?>
															</tr>
															<tr>
																<th rowspan="3">ツール操作</th>
																<th>顧客データの編集・一覧・出力</th>
															</tr>
															<tr>
																<th>お支払シミュレーション・出力</th>
															</tr>
															<tr>
																<th>見積書の作成・一覧・出力</th>
															</tr>
															<tr>
																<th rowspan="3">分割案内</th>
																<th>入金月の確認・一覧・出力</th>
															</tr>
															<tr>
																<th>請求書の作成・一覧・出力</th>
															</tr>
															<tr>
																<th>入金予定事前通知(メール)</th>
															</tr>
															<tr>
																<th rowspan="3">入金案内</th>
																<th>SMS</th>
																<?php if ($row7['exeFlag'] == 0) { ?>
																	<?php if ($row7['service2_fl'] == 1 || ($row7['service2_fl'] == 0 && $row7['service3_fl'] == 0 && $row7['service4_fl'] == 0)) { ?>
																		<td>400円/月<a href="" onClick="send1(2, <?php echo $row7['service2_fl']; ?>, <?php echo $row7['service1_fl']; ?>);return false;" class="contract_info <?php if ($row7['service2_fl'] == 1) { echo 'contracted'; } ?>"><?php if ($row7['service2_fl'] == 1) { echo '解約'; } else { echo '選択'; } ?></a></td>
																	<?php } else { ?>
																		<td>400円/月</td>
																	<?php } ?>
																<?php } else { ?>
																	<td>400円/月<a href="" class="disable contract_info <?php if ($row7['service2_fl'] == 1) { echo 'contracted'; } ?>"><?php if ($row7['service2_fl'] == 1) { echo '解約'; } else { echo '選択'; } ?></a></td>
																<?php } ?>
															</tr>
															<tr>
																<th>SMS+自動音声案内</th>
																<?php if ($row7['exeFlag'] == 0) { ?>
																	<?php if ($row7['service3_fl'] == 1 || ($row7['service2_fl'] == 0 && $row7['service3_fl'] == 0 && $row7['service4_fl'] == 0)) { ?>
																		<td>800円/月<a href="" onClick="send1(3, <?php echo $row7['service3_fl']; ?>, <?php echo $row7['service1_fl']; ?>);return false;" class="contract_info <?php if ($row7['service3_fl'] == 1) { echo 'contracted'; } ?>"><?php if ($row7['service3_fl'] == 1) { echo '解約'; } else { echo '選択'; } ?></a></td>
																	<?php } else { ?>
																		<td>800円/月</td>
																	<?php } ?>
																<?php } else { ?>
																	<td>800円/月<a href="" class="disable contract_info <?php if ($row7['service3_fl'] == 1) { echo 'contracted'; } ?>"><?php if ($row7['service3_fl'] == 1) { echo '解約'; } else { echo '選択'; } ?></a></td>
																<?php } ?>
															</tr>
															<tr>
																<th>SMS+自動音声案内+オペレーター案内</th>
																<?php if ($row7['exeFlag'] == 0) { ?>
																	<?php if ($row7['service4_fl'] == 1 || ($row7['service2_fl'] == 0 && $row7['service3_fl'] == 0 && $row7['service4_fl'] == 0)) { ?>
																		<td>1,000円/月<a href="" onClick="send1(4, <?php echo $row7['service4_fl']; ?>, <?php echo $row7['service1_fl']; ?>);return false;" class="contract_info <?php if ($row7['service4_fl'] == 1) { echo 'contracted'; } ?>"><?php if ($row7['service4_fl'] == 1) { echo '解約'; } else { echo '選択'; } ?></a></td>
																	<?php } else { ?>
																		<td>1,000円/月</td>
																	<?php } ?>
																<?php } else { ?>
																	<td>1,000円/月<a href="" class="disable contract_info <?php if ($row7['service4_fl'] == 1) { echo 'contracted'; } ?>"><?php if ($row7['service4_fl'] == 1) { echo '解約'; } else { echo '選択'; } ?></a></td>
																<?php } ?>
															</tr>
															<tr>
																<th>チャット</th>
																<th>使用者お問い合わせ対応</th>
																<?php if ($row7['exeFlag'] == 0) { ?>
																	<td>400円/月<a href="" onClick="send1(5, <?php echo $row7['service5_fl']; ?>, <?php echo $row7['service1_fl']; ?>);return false;" class="contract_info <?php if ($row7['service5_fl'] == 1) { echo 'contracted'; } ?>"><?php if ($row7['service5_fl'] == 1) { echo '解約'; } else { echo '選択'; } ?></a></td>
																<?php } else { ?>
																	<td>400円/月<a href="" class="disable contract_info <?php if ($row7['service5_fl'] == 1) { echo 'contracted'; } ?>"><?php if ($row7['service5_fl'] == 1) { echo '解約'; } else { echo '選択'; } ?></a></td>
																<?php } ?>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							<!-- </form> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<script src="/manage/user/users_func.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="/manage/common/js/customer-data.js"></script>
	<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript">
		$(function() {
			$('#passcheck').change(function(){
				if ( $(this).prop('checked') ) {
					$('#password').attr('type','text');
				} else {
					$('#password').attr('type','password');
				}
			});
		});
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