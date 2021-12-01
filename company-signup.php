<?php
session_start();
require_once('./common/dbconnect.php');

$signError = "";
if (!empty($_POST['next2']) && $_POST['next2'] == "確認画面へ") {
	$_SESSION['u_company']      = $_POST['u_company'];
	$_SESSION['u_company_kana'] = $_POST['u_company_kana'];
	$_SESSION['u_ceo']          = $_POST['u_ceo'];
	$_SESSION['u_ceo_kana']     = $_POST['u_ceo_kana'];
	$_SESSION['u_person']       = $_POST['u_person'];
	$_SESSION['u_person_kana']  = $_POST['u_person_kana'];
	$_SESSION['u_department']   = $_POST['u_department'];
	$_SESSION['u_postal1']      = $_POST['u_postal1'];
	$_SESSION['u_postal2']      = $_POST['u_postal2'];
	$_SESSION['u_address1']     = $_POST['u_address1'];
	$_SESSION['u_address2']     = $_POST['u_address2'];
	$_SESSION['u_address3']     = $_POST['u_address3'];
	$_SESSION['u_tel']          = $_POST['u_tel'];
	$_SESSION['u_email1']       = $_POST['u_email1'];
	$_SESSION['u_email2']       = $_POST['u_email2'];
	$_SESSION['u_pass1']        = $_POST['u_pass1'];
	$_SESSION['u_pass2']        = $_POST['u_pass2'];

	if ($_SESSION['u_email1'] != $_SESSION['u_email2']) {
		$signError = "failEmail";
	}

	if ($signError == "") {
		if ($_SESSION['u_pass1'] != $_SESSION['u_pass2']) {
			$signError = "failPass";
		}
	}

	if ($signError == "") {
		if (false === strpos($_SESSION['u_tel'], "090") && false === strpos($_SESSION['u_tel'], "080") && false === strpos($_SESSION['u_tel'], "070")) {
			$signError = "tel_no";
		}
		if (strlen($_SESSION['u_tel']) != 11) {
			$signError = "tel_no";
		}
	}

	if ($signError == "") {
		$sql = sprintf('SELECT * FROM user WHERE delFlag=0 AND
			(u_email="%s" OR p_email="%s")',
			mysqli_real_escape_string($db, $_SESSION['u_email1']),
			mysqli_real_escape_string($db, $_SESSION['u_email1'])
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		if ($row0 = mysqli_fetch_assoc($record)) {
			// NG
			$signError = "failEmail2";
		}
	}

	if ($signError == "") {
		header("Location:https://sapocup.jp/verification-company.php");
		exit();
	}
}

if (empty($_SESSION['u_company'])) {
	$_SESSION['u_company']      = "";
	$_SESSION['u_company_kana'] = "";
	$_SESSION['u_ceo']          = "";
	$_SESSION['u_ceo_kana']     = "";
	$_SESSION['u_person']       = "";
	$_SESSION['u_person_kana']  = "";
	$_SESSION['u_department']   = "";
	$_SESSION['u_postal1']      = "";
	$_SESSION['u_postal2']      = "";
	$_SESSION['u_address1']     = "";
	$_SESSION['u_address2']     = "";
	$_SESSION['u_address3']     = "";
	$_SESSION['u_tel']          = "";
	$_SESSION['u_email1']       = "";
	$_SESSION['u_email2']       = "";
	$_SESSION['u_pass1']        = "";
	$_SESSION['u_pass2']        = "";
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<link rel="stylesheet" type="text/css" href="/common/css/other.css">
	<link rel="stylesheet" type="text/css" href="/common/css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP&display=swap" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript">
	<!--
		$(function() {
			$('#passcheck1').change(function(){
				if ( $(this).prop('checked') ) {
					$('#password1').attr('type','text');
				} else {
					$('#password1').attr('type','password');
				}
			});
			$('#passcheck2').change(function(){
				if ( $(this).prop('checked') ) {
					$('#password2').attr('type','text');
				} else {
					$('#password2').attr('type','password');
				}
			});
		});
	// -->
	</script>
</head>
<body>
	<?php include_once('./common/header.php'); ?>
	<main id="searchFunction">
		<div class="otherScreen help">
			<div class="allWrapper">
				<div class="otherScreenInner">
					<h1>会員登録（法人）</h1>
				</div>
			</div>
		</div>
		<div class="otherContent" id="memberRegistation">
			<div class="allWrapper">
				<div class="otherContentInner loginWrap">
					<div class="loginBox">
						<form action="" method="post">
							<?php if ($signError == "failEmail") { ?>
								<font color="#f05b72">メールアドレスが一致しません。</font>
							<?php } ?>
							<?php if ($signError == "failEmail2") { ?>
								<font color="#f05b72">メールアドレスが重複しています。</font>
							<?php } ?>
							<?php if ($signError == "failPass") { ?>
								<font color="#f05b72">パスワードが一致しません。</font>
							<?php } ?>
							<?php if ($signError == "tel_no") { ?>
								<font color="#f05b72">携帯電話番号のみ11桁を指定してください。</font>
							<?php } ?>

							<div class="inputBox">
								<p>会社名<span class="must">必須</span></p>
								<input type="text" class="form-control" name="u_company" value="<?php echo $_SESSION['u_company']; ?>" required placeholder="株式会社AMBIENCE">
							</div>
							<div class="inputBox">
								<p>会社名（カタカナ）<span class="must">必須</span></p>
								<input type="text" class="form-control" name="u_company_kana" value="<?php echo $_SESSION['u_company_kana']; ?>" title="カナ文字をご入力ください" required placeholder="アンビエンス">
							</div>
							<div class="inputBox">
								<p>代表者名<span class="must">必須</span></p>
								<input type="text" class="form-control" name="u_ceo" value="<?php echo $_SESSION['u_ceo']; ?>" required placeholder="さぽかっぷ">
							</div>
							<div class="inputBox">
								<p>代表者名（カタカナ）<span class="must">必須</span></p>
								<input type="text" class="form-control" name="u_ceo_kana" value="<?php echo $_SESSION['u_ceo_kana']; ?>" pattern="[\u30A1-\u30F6,　,ー,]*" title="カナ文字をご入力ください" required placeholder="サポカップ">
							</div>
							<div class="inputBox">
								<p>担当者名</p>
								<input type="text" class="form-control" name="u_person" value="<?php echo $_SESSION['u_person']; ?>" placeholder="さぽかっぷ">
							</div>
							<div class="inputBox">
								<p>担当者名（カタカナ）</p>
								<input type="text" class="form-control" name="u_person_kana" value="<?php echo $_SESSION['u_person_kana']; ?>" pattern="[\u30A1-\u30F6,　,ー,]*" title="カナ文字をご入力ください" placeholder="サポカップ">
							</div>
							<div class="inputBox">
								<p>部署</p>
								<input type="text" class="form-control" name="u_department" value="<?php echo $_SESSION['u_department']; ?>" placeholder="営業部">
							</div>
							<div class="inputBox">
								<p>郵便番号<span class="must">必須</span></p>
								<div class="inputBoxAddress cf">
									<div class="addressBox post">
										<input type="text" class="postalInput" name="u_postal1" value="<?php echo $_SESSION['u_postal1']; ?>" maxlength="3" placeholder="123" required>-<input type="text" class="postalInput" name="u_postal2" value="<?php echo $_SESSION['u_postal2']; ?>" maxlength="4" onKeyUp="AjaxZip3.zip2addr('u_postal1','u_postal2','u_address1','u_address2','u_address2');" placeholder="4567" required>
										<!-- <input type="text" class="form-control" name="u_postal" value="<?php echo $_SESSION['u_postal']; ?>" placeholder="1234567" title="郵便番号をご入力ください" required onKeyUp="AjaxZip3.zip2addr(this,'','u_address1','u_address2');"> -->
									</div>
									<div class="addressBox">
										<p>都道府県<span class="must">必須</span></p>
										<input type="text" class="form-control" name="u_address1" value="<?php echo $_SESSION['u_address1']; ?>" placeholder="東京都" required>
									</div>
									<div class="addressBox">
										<p>市区町村<span class="must">必須</span></p>
										<input type="text" class="form-control" name="u_address2" value="<?php echo $_SESSION['u_address2']; ?>" required>
									</div>
									<div class="addressBox">
										<p>番地<span class="must">必須</span></p>
										<input type="text" class="form-control" name="u_address3" value="<?php echo $_SESSION['u_address3']; ?>" required>
									</div>
								</div>
							</div>
							<div class="inputBox">
								<p>連絡先<span class="must">必須</span></p>
								<input type="text" class="form-control" name="u_tel" value="<?php echo $_SESSION['u_tel']; ?>" maxlength="11" placeholder="09012345678" title="半角数字11文字をご入力ください" required>
							</div>
							<div class="inputBox">
								<p>ログインID(メールアドレス)<span class="must">必須</span></p>
								<input type="email" class="form-control" name="u_email1" value="<?php echo $_SESSION['u_email1']; ?>" required>
							</div>
							<div class="inputBox">
								<p>ログインID(メールアドレス)確認用<span class="must">必須</span></p>
								<input type="email" class="form-control" name="u_email2" value="<?php echo $_SESSION['u_email2']; ?>" required>
							</div>
							<div class="inputBox">
								<p>パスワード(半角英数字８桁)<span class="must">必須</span></p>
								<input type="password" id="password1" class="form-control" name="u_pass1" value="<?php echo $_SESSION['u_pass1']; ?>" minlength="8" required>
								<input type="checkbox" id="passcheck1" /> パスワードを表示
							</div>
							<div class="inputBox">
								<p>パスワード確認用(半角英数字８桁)<span class="must">必須</span></p>
								<input type="password" id="password2" class="form-control" name="u_pass2" value="<?php echo $_SESSION['u_pass2']; ?>" minlength="8" required>
								<input type="checkbox" id="passcheck2" /> パスワードを表示
							</div>
							<div class="modalPanel">
								<div id="app">
									<!-- 規約モーダル -->
									<div class="modal-mask" :class="{'isShown': isModalActive}">
										<div class="modal-wrapper">
											<div class="modal-container">


												<div class="modal-header">
										            <slot name="header">
														<p class="h4">ご同意いただく個人情報の規約</p>
													</slot>
												</div>

												<div class="modal-body">
													<slot name="body">
														<div class="modal-content">
															<p class="h5">さぽかっぷ基本規約</p>
															<p>さぽかっぷ基本規約（以下「本規約」といいます。）は、株式会社AMBIENCE（以下「弊社」といいます。）が提供する「さぽかっぷ」の名を冠したサービス（以下「本サービス」といいます。）をご利用の際に適用されます。利用者は、本規約に同意の上、本サービスをお申し込みください。</p>
															<p class="h5">第1条（定義）</p>
															<p>本規約における各用語の定義は以下のとおりとします。</p>
															<ul>
																<li>1.「利用者」とは、本規約に同意した上で本サービスの申込みを行い、弊社の承諾のうえで本サービスを利用する者をいいます。</li>
																<li>2.「利用者記録情報」とは、利用者が本サービスの申込み及び利用の過程で入力した情報であって、弊社の管理するサーバーに記録される情報をいいます。</li>
															</ul>
															<p class="h5">第2条（適用）</p>
															<ul>
																<li>1.利用者は、本規約が本サービスに関する利用者と弊社との関係について定めるものであることを理解し、本規約に同意の上、本規約を誠実に遵守するものとします。</li>
																<li>2.本サービスに関し、個別のサービス毎に弊社が別途個別規約その他の条件（以下「個別規約等」といいます。）を定める場合、これらの個別規約等は本規約の一部を構成するものとし、本規約と合わせて弊社と利用者との間の契約（以下「本契約」といいます。）の一部となります。</li>
																<li>3.弊社は、本規約（個別規約等を含みます。以下同じ。）の変更の内容及び時期を利用者に通知することにより、本規約を変更することができるものとします。なお、本規約の変更後に利用者が本サービスを利用した場合、利用者は当該変更後の本規約に同意したものとみなします。</li>
																<li>4.本契約と個別規約等に相違がある場合、個別規約等が優先して適用されるものとします。</li>
															</ul>
															<p class="h5">第3条（IDの発行、管理等）</p>
															<ul>
																<li>1.利用者は、弊社が定める方式により、本サービスの利用申込みに関するウェブページ上において必要事項を入力して送信することで、弊社からIDが発行され、本サービスの利用を開始することができます。</li>
																<li>2.利用者は、本サービスを利用する際に必要となるID、パスワード（以下「ID等」といいます。）を自己の責任で管理するものとし、ID等を第三者に開示、貸与することはできません。</li>
																<li>3.弊社は、ID等の認証後の本サービスの利用については、当該IDを付与された正当な利用者による利用とみなします。ID等の認証後は、万が一当該利用者以外の第三者が利用していた場合であっても、弊社は一切責任を負いません。</li>
																<li>4.利用者は、ID等の盗難や不正利用等の事実を知った場合、直ちにその旨を弊社に通知するものとします。この場合において、弊社から指示があったときは、これに従い対応するものとします。</li>
																<li>5.利用者のID等の管理に起因して第三者に損害が発生した場合、その責任は利用者が負うものとし、弊社は一切責任を負わないものとします。また、ID等が不正に利用されたことにより、弊社に損害が生じた場合、利用者は、弊社に対しその損害を賠償するものとします。</li>
															</ul>
															<p class="h5">第4条（権利帰属）</p>
															<ul>
																<li>1.弊社ウェブサイト及び本サービスに関する知的財産権は、全て弊社又は弊社にライセンスを許諾している者に帰属しており、本規約に基づく本サービスの利用許諾は、弊社ウェブサイト又は本サービスに関する弊社又は弊社にライセンスを許諾している者の知的財産権の使用許諾を意味するものではありません。</li>
																<li>2.利用者は、利用者記録情報について、自らが入力その他送信することについて必要な第三者の同意手続を行うなど適法な権利を有していること、及び利用者記録情報の入力及び弊社への提供が第三者の権利を侵害していないことについて、弊社に対して表明し、保証するものとします。</li>
															</ul>
															<p class="h5">第5条（情報の取扱い）</p>
															<ul>
																<li>1.弊社は、利用者記録情報を、善良な管理者の注意義務をもって保管するものとします。</li>
																<li>2.弊社は、利用者記録情報及び利用者の本サービスに関する利用記録から、個人及び利用者を識別することができない統計データを作成することができるものとします。当該統計データは、本サービスのために弊社が利用できるほか、第三者に提供することができるものとし、利用者は予めこれを承諾するものとします。</li>
																<li>3.弊社が前項に定める範囲で統計情報を利用しているにもかかわらず、利用者又は弊社が個人情報の保有当事者からクレーム等を受けた場合、利用者は、自らの費用と責任でこれを解決するものとし、弊社にいかなる責任も負担させないものとします。</li>
															</ul>
															<p class="h5">第6条（免責事項）</p>
															<ul>
																<li>1.本規約に明示的に規定されている場合を除き、弊社は、本サービスの機能、その信頼性、利用可能性、完全性について具体的な保証を行いません。</li>
																<li>2.弊社は、本サービスを、利用者とその顧客との間においてなされる自社割賦取引の便宜のためのツールを提供するのみであり、利用者とその顧客との間における紛争や回収の遅延・不能等が生じた場合であっても、一切損失の補償等の対応を行わず、何ら責任を負わないものとします。</li>
															</ul>
															<p class="h5">第7条（利用者の責任等）</p>
															<ul>
																<li>1.本サービスを利用者が利用する為に必要な環境や設備（インターネット回線や、パソコン等のハードウェア、Webブラウザ等のソフトウェアなど一切のものをいいます。）は利用者自らが自身の責任と費用において適切に用意する必要があり、弊社はこれらの環境や設備に関する責任を一切負いません。</li>
																<li>2.弊社より利用者に対して連絡を行う際には、登録されたメールアドレス宛に連絡を行います。利用者は、弊社からの連絡を受信できるよう登録メールアドレス情報を正しく維持するものとし、何らかの理由で電子メール受信ができなくなった場合には、利用者は速やかに登録メールアドレスの変更を行うなど弊社から送信された電子メールを受領できる措置をとるものとします。弊社からの連絡を受信できなかった為に利用者が被った不利益、損害の責任は利用者が負うものとし、弊社は一切の責任を負いません。</li>
																<li>3.利用者は、前条に定める弊社の免責事項を了解したうえで本サービスを利用するものとし、利用者は、顧客の与信判断を含む顧客との間の割賦販売取引に関する一切の事項につき全て自らの判断により決定するものとします。利用者は、その顧客との間における紛争や回収の遅延・不能等が生じた場合であっても、全て自らの責任と負担において処理するものとします。</li>
																<li>4.利用者が本サービスの利用により第三者（他の本サービス利用者も含みます。）に対し損害を与えた場合、利用者は自己の責任でこれを解決し、弊社にいかなる責任も負担させないものとします。</li>
															</ul>
															<p class="h5">第8条（業務委託）</p>
															<p>弊社は、本サービスに関する業務の全部又は一部を業務に必要な範囲内で第三者に委託することができるものとします。</p>
															<p class="h5">第9条（禁止事項）</p>
															<ul>
																<li>1.利用者は、弊社の承諾なく、次の各号に定める行為を行ってはならないものとします。</li>
																<ul>
																	<li>(1)	本サービスを弊社が認めた本サービスの利用目的以外の目的で使用すること</li>
																	<li>(2)	本サービスの複製、分解、追加、付加、編集、消去、削除、改変、改造その他方法、態様の如何を問わず、本サービスの現状を変更すること</li>
																	<li>(3)	本サービスのリバースエンジニアリング、逆コンパイル、逆アセンブルその他方法、態様の如何を問わず本サービスの解析を行うこと</li>
																	<li>(4)	本サービスにつき、有償無償を問わず、譲渡、転貸、質入、担保設定その他態様の如何を問わず占有の移転、使用権の設定等を行うこと</li>
																	<li>(5)	本サービスを受ける権利の譲渡、再許諾、再販売、担保設定その他態様の如何を問わず使用許諾等を行うこと</li>
																	<li>(6)	著作権表示、所有権を表す標章等を削除、除去その他方法、態様の如何を問わず変更すること</li>
																	<li>(7)	弊社又は第三者の財産権（知的財産権を含みます。）、プライバシー、名誉その他の権利を侵害すること</li>
																	<li>(8)	本サービスを違法な目的で利用すること</li>
																	<li>(9)	第三者になりすまして本サービスを利用すること</li>
																	<li>(10) 意図的に有害なコンピュータープログラム等を送信すること</li>
																	<li>(11) 弊社の設備に無権限でアクセスすること</li>
																	<li>(12) 本サービス及びその他の弊社の事業運営に支障をきたすおそれのある行為を行うこと</li>
																	<li>(13) 弊社従業員に対し、脅迫的な言動をし、又は暴力を用いる行為を行うこと</li>
																	<li>(14) 本規約、法令若しくは公序良俗に反する行為、弊社若しくは第三者の信用を毀損する行為、又は弊社若しくは第三者に不利益を与える行為を行うこと</li>
																	<li>(15) その他前各号に該当する恐れがある行為又はこれに類する行為を行うこと</li>
																</ul>
																<li>2.利用者は、前項の規定に違反して弊社に損害を与えた場合、弊社に対し当該損害を賠償する責任を負うものとします。</li>
															</ul>
															<p class="h5">第10条（本契約上の地位の譲渡等）</p>
															<ul>
																<li>1.利用者は、弊社の書面による事前の承諾なく、本契約上の地位を含む本規約に基づく権利若しくは義務につき、第三者に対し、譲渡、移転、担保設定その他の処分をすることはできません。</li>
																<li>2.弊社が本サービスにかかる事業を他社に譲渡した場合には、当該事業譲渡に伴い本契約上の地位、本規約に基づく権利及び義務並びに利用者の情報等を当該事業譲渡の譲受人に譲渡することができるものとし、利用者は、かかる譲渡につき本項において予め同意したものとします。なお、本項に定める事業譲渡には、通常の事業譲渡のみならず、会社分割その他事業が移転するあらゆる場合を含みます。</li>
															</ul>
															<p class="h5">第11条（反社会的勢力の排除）</p>
															<ul>
																<li>1.弊社及び利用者は、相互に、次の各号について表明し、保証するものとします。</li>
																<ul>
																	<li>(1) 自らが、暴力団、暴力団員、暴力団員でなくなった時から5年経過しない者、暴力団準構成員、暴力団関係企業、総会屋、社会運動等標ぼうゴロ、特殊知能暴力集団その他これらに準ずる者（以下、総称して「反社会的勢力」といいます。）ではないこと。</li>
																	<li>(2) 反社会的勢力と次の関係を有していないこと。
																	<ul>
																		<li>①自ら若しくは第三者の不正の利益を図る目的、又は第三者に損害を与える目的をもって反社会的勢力を利用していると認められる関係</li>
																		<li>②反社会的勢力に対して資金等を提供し、又は便宜を供与するなど反社会的勢力の維持、運営に協力し、又は関与している関係</li>
																	</ul>
																	<li>(3) 自らの役員（取締役、執行役、執行役員、監査役、相談役、会長その他、名称の如何を問わず、経営に実質的に関与している者をいう。）が、反社会的勢力ではないこと、及び反社会的勢力と社会的に非難されるべき関係を有していないこと。</li>
																	<li>(4) 反社会的勢力に自己の名義を利用させ、本契約を締結するものでないこと。</li>
																	<li>(5) 自ら又は第三者を利用して本契約に関して次の行為をしないこと。
																		<ul>
																			<li>①暴力的な要求行為</li>
																			<li>②法的な責任を超えた不当な要求行為</li>
																			<li>③取引に関して、脅迫的な言動をし、又は暴力を用いる行為</li>
																			<li>④風説を流布し、偽計又は威力を用いて弊社の業務を妨害し、又は信用を毀損する行為</li>
																			<li>⑤その他前各号に準ずる行為</li>
																		</ul>
																	</li>
																	<li>2.弊社及び利用者は、相手方が前項に違反した場合、何ら通告することなく、本契約の全部又は一部を解除することができるものとします。</li>
																	<li>3.弊社又は利用者が前項に基づいて本契約の全部又は一部を解除した場合、相手方に損害が生じても解除した当事者はその賠償責任を負わないものとします。</li>
															</ul>
															<p class="h5">第12条（サービスの中断）</p>
															<ul>
																<li>1.弊社は、利用者に対し事前に通知の上、弊社の定める日程でシステムのメンテナンス作業等を行うことがあり、その期間中は本サービスの全部又は一部の提供を中断することができるものとします。</li>
																<li>2.弊社は、以下の各号のいずれかに該当すると判断した場合、利用者への事前の通知又は承諾を要せず、一時的に本サービスの全部又は一部の提供を中断できるものとします。</li>
																<ul>
																	<li>(1)	メンテナンスを緊急に行う場合</li>
																	<li>(2)	火災、停電等により、本サービスの全部又は一部の提供ができなくなった場合</li>
																	<li>(3)	地震、噴火、洪水、津波等の天災により本サービスの全部又は一部の提供ができなくなった場合</li>
																	<li>(4)	戦争、暴動、騒乱、労働争議により本サービスの全部又は一部の提供ができなくなった場合</li>
																	<li>(5)	本サービスと連携している他社のサービスに関し保守、停止その他システムの障害等により本サービスの全部又は一部の提供ができなくなった場合</li>
																	<li>(6)	その他の不可抗力により本サービスの全部又は一部の提供ができなくなった場合</li>
																</ul>
																<li>3.前２項による中断によって利用者に損害が発生したとしても、弊社は一切責任を負わないものとします。</li>
															</ul>
															<p class="h5">第13条（サービスの終了）</p>
															<p>弊社は、本サービス終了の１ヶ月前までに利用者に通知を行うことにより、本サービスの提供を終了できるものとし、その場合は本サービスの終了と同時に弊社と利用者間の契約も終了します。</p>
															<p class="h5">第14条（分離性）</p>
															<p>本規約の条項の一部が、法令上無効であるとされた場合であっても、かかる無効とされた条項以外の本規約の各条項は引き続き有効なものとして、弊社及び利用者に適用されるものとします。</p>
															<p class="h5">第15条（準拠法及び管轄裁判所）</p>
															<ul>
																<li>1.本規約の準拠法は日本法とします。</li>
																<li>2.本規約に起因し、又は関連する一切の紛争については、東京地方裁判所を第一審の専属的合意管轄裁判所とします。</li>
															</ul>
															<p class="text-right">以上</p>
															<p>最終更新日：2020月2日29日</p>
														</div>
														<div class="modal-content">
															<p class="h5">さぽかっぷ利用規約</p>
															<p>株式会社AMBIENCE（以下「弊社」といいます。）が提供する「さぽかっぷ」をご利用いただくにあたっては、「さぽかっぷ基本規約」のほか、「さぽかっぷ利用規約」（以下「本規約」といいます。）が適用されます。<br>以下に定める本規約をご確認いただき同意の上、さぽかっぷをお申込み下さい。</p>
															<p class="h5">第1条（定義）</p>
															<p>本規約における各用語の定義は以下のとおりとします。</p>
															<ul>
																<li>1.「利用者」とは、本規約に同意した上でさぽかっぷの申込みを行い、弊社の承諾のうえでさぽかっぷを利用する者をいいます。</li>
																<li>2.「利用者記録情報」とは、利用者がさぽかっぷの利用の過程で入力した情報であって、弊社の管理するサーバーに記録される情報をいいます。</li>
															</ul>
															<p class="h5">第2条（サービス内容）</p>
															<ul>
																<li>1.さぽかっぷは現状のままで提供されるものであり、弊社は利用者にさぽかっぷに関する不具合の不存在を保証するものではありません。</li>
																<li>2.弊社は、さぽかっぷの内容及び利用者がさぽかっぷを通じて入手した情報等について、その完全性、正確性、確実性、有用性等につき、いかなる責任も負わないものとします。</li>
															</ul>
															<p class="h5">第3条（利用料金等）</p>
															<ul>
																<li>1.さぽかっぷの利用料金は、弊社が別途定めるものとします。</li>
																<li>2.利用者は、さぽかっぷの利用料金を弊社が定める期日までに弊社指定の方法で支払うものとします。なお、口座振込の方法で支払いを行う際の振込手数料については、利用者が負担するものとします。</li>
																<li>3.利用者は、支払期日までにさぽかっぷの利用料金を支払わない場合、支払期日の翌日から支払いが完了するまでの期間について、年14.6%の割合で計算した額を遅延損害金として支払うものとし、その料金その他の債務が支払われるまでの間、本サービスを停止することができます。弊社は、弊社に故意又は重過失ある場合を除き、利用者から支払いを受けた利用料金を返金しないものとします。</li>
																<li>4.弊社は、利用者が料金の支払いにおいて本サービスの提供を停止するときは、利用者に対しその理由及び停止期間を弊社の定める方法により通知します。但し、緊急の場合は、この限りではありません。</li>
																<li>5.当社は、いかなる場合でも利用料金の日割り計算による減額を行わないものとます。</li>
																<li>6.さぽかっぷの運営企業は株式会社AMBIENCEであるが、基本料金やオプション料金をクレジットカード決済いただく際に利用者のカード明細に掲載される請求者名は、決済システムの都合上親会社である「アールエムトラスト株式会社」となります。</li>
															</ul>
															<p class="h5">第4条（お支払シミュレーション）</p>
															<p>「お支払シミュレーション」は、あくまで一定の条件の下における支払い例を示すものであり、当機能には顧客に対する与信判断に関する機能は一切含まれておりません。そのため、利用者は、当該機能により示された支払い例をもとに自ら顧客に対する与信判断を行　うものとし、当社は利用者における与信判断には一切関与せず、また顧客による未払い等が発生した場合であっても、弊社は一切責任を負いません。</p>
															<p class="h5">第5条（入金予定事前通知）</p>
															<ul>
																<li>1.利用者が、「入金予定事前通知」を使用の際、当機能は、利用者に対して顧客の当月入金予定日をお知らせするものであり、顧客に対し督促行為をするものではありません。また、当該機能により未収金の全部または一部の回収を保証するものではありません。</li>
																<li>2.弊社は、毎月一日に当月分の「入金予定者リスト」をユーザーの登録済みのメールアドレスに送信します。</li>
															</ul>
															<p>さぽかっぷのオプション機能である「分割シミュレーション」は、あくまで一定の条件の下における支払い例を示すものであり、当機能には顧客に対する与信判断に関する機能は一切含まれておりません。そのため、利用者は、当該機能により示された支払い例をもとに自ら顧客に対する与信判断を行うものとし、当社は利用者における与信判断には一切関与せず、また一切責任を負いません。</p>
															<p class="h5">第6条（入金案内）</p>
															<ul>
																<li>1.利用者が、「入金案内」のうち、「SMS」「自動音声案内」「オペレーター案内」のそれぞれをオプションとして選択した際、当社は入金予定日を含む月から翌月末日まで各依頼を受け付け入金案内を行うものとし、その際SMSにて利用者に対し報告メールをお送りいたします。</li>
																<li>2.営業時間外に行われた各依頼への対応は翌営業日に行われものとします。</li>
																<li>3.当機能は、顧客に対して未払いの状態であることの案内をするものであり、督促行為をするものではありません。また、当該機能により未収金の全部または一部の回収を保証するものではありません。</li>
																<li>4.利用者は、本オプション機能を利用する前に、顧客に対し、分割金の未払いが起こった場合には弊社より「SMS」「自動音声」「オペレーター」案内のいずれかより連絡がいく場合がある旨を十分に説明の上で同意させるものとし、弊社は当該同意がなされているものとみなすことができるものとします。<br>万一、顧客から弊社に対し、個人情報の流用の観点で指摘があった場合であっても、弊社は利用者から顧客に対して事前に説明を行い同意を得ているものとみなし、その責任はすべて利用者が負うものとし、弊社は一切責任を負わないものとします。</li>
															</ul>
															<p>利用者が、さぽかっぷのオプション機能である「督促」を使用の際、当機能は、利用者に対して未払いの状態であることの案内をするものであり、督促行為をするものではありません。また、当該機能により未収金の全部または一部の回収を保証するものではありません。</p>
															<p class="h5">第7条（チャット）</p>
															<ul>
																<li>1.利用者は、弊社の定める曜日及び時間帯に限り、ツール内お問い合わせフォームやチャットにより、さぽかっぷの利用方法に関してのみ担当者に質問することができます。ただし、当該質問の内容により、お答えできない場合があります。</li>
																<li>2.弊社は、利用者からの質問に回答を行ったとしても、その回答内容の完全性、正確性、確実性、有用性等につき、いかなる責任も負わないものとします。</li>
															</ul>
															<p class="h5">第8条（顧客データの保存）</p>
															<ul>
																<li>1.弊社は、入力された顧客情報である「見積書」と「請求書」に各記載のある「初回お支払い日」より個人事業主において8年間、法人においては11年間保存します。ただし、利用者は自己の責任において利用者記録情報を保存することにより、バックアップ作業を行うものとします。</li>
																<li>2.弊社は、利用者記録情報が弊社のサーバーに記録されている場合、当該利用者記録情報が弊社のサーバーに記録された日から起算して8年間、11年間を経過した場合、弊社のサーバーから削除することができるものとします。</li>
																<li>3.本契約について利用者が基本料金契約を解約した時点で本契約の終了とみなし、契約が終了した翌日以降1年以内は、会員であったときに作成した情報については引き出すことができるものとします。</li>
															</ul>
															<p class="h5">第9条（情報の取扱い）</p>
															<ul>
																<li>1.弊社は、利用者記録情報を、善良な管理者の注意義務をもって保管するものとします。</li>
																<li>2.弊社は、利用者記録情報を、本規約に別途規定する場合及び以下に定める場合を除き、第三者に開示又は提供しないものとします。</li>
																<ul>
																	<li>(1) 法令又は官公庁により開示又は提供を法的に義務づけられた場合</li>
																	<li>(2) 開示又は提供につき、利用者の同意を得た場合</li>
																	<li>(3) 利用者に対し、本規約に基づく義務の履行を請求する場合</li>
																	<li>(4)	利用者に対するさぽかっぷの提供に関し、紛争等が発生した場合</li>
																	<li>(5) その他重要な法益を保護する必要のある緊急事態が生じた場合</li>
																</ul>
																<li>3.弊社は、利用者記録情報及び利用者のさぽかっぷに関する利用記録から、個人及び利用者を識別することができない統計データを作成することができるものとします。当該統計データは、さぽかっぷ及び弊社のその他のサービスのために弊社が利用できるほか、第三者に提供することができるものとし、利用者は予めこれを承諾するものとします。</li>
																<li>4.弊社が前項に定める範囲で統計情報を利用しているにもかかわらず、利用者又は弊社が個人情報の保有当事者からクレーム等を受けた場合、利用者は、自らの費用と責任でこれを解決するものとし、弊社にいかなる責任も負担させないものとします。</li>
															</ul>
															<p class="h5">第10条（個人情報の取扱い）</p>
															<ul>
																<li>1.弊社は、「個人情報の保護に関する法律」（平成15年法律第57号。以下単に「個人情報保護法」という。）を遵守し、本規約に基づきさぽかっぷを通じて弊社に　委託した「個人情報」（個人情報保護法の定義と同義とします。）を善良な管理者の注意義務をもって取り扱うものとします。</li>
																<li>2.弊社は、委託された個人情報を、さぽかっぷ利用契約の履行目的にのみ利用し、それ以外の目的で利用しないものとします。</li>
																<li>3.弊社は、さぽかっぷに登録した個人情報の漏えい、滅失、毀損の防止その他の個人情報の安全管理のために必要かつ適切な措置を講じるものとします。万が一、さぽかっぷに登録した個人情報の漏えい、滅失、毀損があった場合、弊社が適切と判断する方法で利用者に告知を行います。</li>
																<li>4.弊社は、さぽかっぷの利用契約が終了したときは、利用者の要求があった場合、速やかにさぽかっぷに登録した個人情報（バックアップ等の複製物を含みます。）をすべて消去又は廃棄するものとします。</li>
																<li>5.弊社は、さぽかっぷにかかる業務を第三者に再委託することができるものとします。なお、弊社は当該第三者に本規約と同等の守秘義務を課すものとし、弊社は再委託に必要な範囲で個人情報を当該第三者に提供できるものとします。</li>
																<li>6.利用者は、弊社による個人情報の管理状況を調査・確認するため、1年間に1回以下の頻度で、報告を求めることができるものとします。</li>
															</ul>
															<p class="h5">第11条（解除）</p>
															<ul>
																<li>1.弊社は、次の各号に掲げる事由のいずれかが発生した場合、直ちに、弊社が必要と判断する期間における利用者のさぽかっぷの利用の停止又は本契約の解除をすることができるものとします。</li>
																<li>2.利用者が本規約に違反したとき、また、催告を受けたにもかかわらず弊社が定めた6か月以内の期間においても是正されない場合には、弊社による本契約の解除ができるものとします。</li>
																<li>3.利用者の重要な財産について差押え、仮差押え、仮処分、強制執行若しくは競売の申立てがなされたとき、又は租税公課を滞納し督促を受けたとき。</li>
																<li>4.利用者に関して、民事再生手続開始、会社更生手続開始、破産手続開始、特別清算開始その他の法的倒産処理手続の開始の申立て若しくは特定調停の申立てがあったとき、私的整理に入ったとき、又は手形若しくは小切手を不渡りとしたときその他支払停止状態に至ったとき。</li>
																<li>5.利用者が資本減少若しくは解散の手続に入ったとき又は裁判により解散したとき。</li>
																<li>6.利用者が法令に基づく事業停止若しくは事業禁止の命令を受け、若しくは許認可等　が取り消され、又は事業の全部若しくは重要な一部につき廃止、休止若しくは譲渡の手続に入ったとき。</li>
																<li>7.前各号のほか利用者に対する債権保全を必要とする相当の事由が生じたとき。</li>
																<li>8.利用者又はその役員若しくは従業員が法令に違反したとき。</li>
																<li>9.利用者の親会社（会社法第2条第4号に定める親会社をいう。）若しくは親会社と同様に経営を支配している者に変更があったとき、又は新たにそれらの者に経営を支配されるに至ったとき。</li>
																<li>10.前項に定める事由により、利用者のさぽかっぷの利用が停止された場合でも、利用者は利用が停止された期間中のさぽかっぷの利用料金を負担するものとします。</li>
																<li>11.利用者が第1項各号のいずれかに該当した場合、弊社に対する本契約に基づくすべての債務につき当然に期限の利益を失い、直ちに当該債務全部を弁済しなければならないものとします。</li>
															</ul>
															<p class="h5">第12条（解約）</p>
															<ul>
																<li>1.利用者は、解約を希望する月の前月末日までに、弊社指定の届出を行うことにより、解約希望月の末日をもって本契約の全部又は一部を解約できるものとします。</li>
																<li>2.利用料金は月極となるため、利用者は、解約日を含む月の　末日までに発生した利用料について、解約後も支払義務を負います。</li>
																<li>3.利用者による本契約の解約により、利用者は退会したものとみなされ、退会前に追加した見積書、請求書、顧客データ等に関しては、「編集」や「反映」機能の使用ができなくなります。</li>
																<li>4.利用者は、退会前に登録したメールアドレスを用いて、再度ユーザー登録を行うことが可能です。その際、「新規申込」ではなく「再申込」ボタンより登録を行うものとします。この時、退会前に使用したパスワードは再使用できません。また、「再申込」により、「編集」「反映」機能は使用可能となりますが、退会前にご登録いただいた各データを、同期させることはできません。</li>
																<li>5.「退会」と「再申込」を二回以上繰り返した場合、前回分のデータのみのお取り扱いとなります。</li>
															</ul>
															<p class="h5">第13条（損害賠償）</p>
															<p>さぽかっぷに関し、弊社が利用者に損害を与えた場合であっても、弊社に故意又は重過失が認められる場合を除き、弊社は一切その責任を負いません。弊社に故意又は重過失が認められる場合、弊社の責任は利用者に損害が発生した月の利用料金相当額を上限とします。</p>
															<p class="h5">第14条（分離性）</p>
															<p>本規約の条項の一部が、法令上無効であるとされた場合であっても、かかる無効とされた条項以外の本規約の各条項は引き続き有効なものとして、弊社及び利用者に適用されるものとします。</p>
															<p class="text-right">以上</p>
															<p>最終更新日：2020年9月15日</p>
														</div>
													</slot>
												</div>

												<div class="modal-footer">
													<slot name="footer">
														<button type="button" class="modal-back-button" @click="toggleModal();" style="cursor:pointer;">戻る</button>
														<button type="button" id="removeHabit" class="modal-default-button" @click="toggleModal(); removeH();" style="cursor:pointer;">同意する</button>
													</slot>
												</div>
											</div>
										</div>
									</div>
									<!-- 規約モーダル　終わり -->
								</div>
								<button id="showModal" type="button" @click="showModal" style="cursor:pointer;">利用規約を読む</button>
							</div>

							<div id="habbitButton" class="inputBox submitBox" v-bind:class="{ no_button: isActive }"><input type="submit" name="next2" value="確認画面へ" style="cursor:pointer"></div>
							
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php include_once('./common/footer.php'); ?>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
	<script src="/common/js/footerFixed.js"></script>
	<script src="/common/js/input.js"></script>
	<script src="/common/js/prefectures.js"></script>
	<script>
		// ハンバーガーメニュー
		$('#nav-input').on('change',function(){
			if ($(this).prop('checked')) {
				$('#nav-content').addClass('navOpen');
			} else {
				$('#nav-content').removeClass('navOpen');
			}
		});

		
		// 住所自動保管機能
		import axiosJsonpAdapter from 'axios-jsonp'

		const ZIPCODE_API_URL = 'http://zipcloud.ibsnet.co.jp/api/search'

		export default {
		  data:function() {
		    return {
		      zipcode: '',
		      prefecture: '',
		      address: ''
		    }
		  },

		  methods: {
		    async fetchAddress: function() {
		      // 郵便番号のバリデーションチェック
		      const reg = /^\d{7}$/
		      if (!reg.test(this.zipcode)) return

		      // 住所apiを叩く
		      const res = await this.$axios.$get(ZIPCODE_API_URL, {
		        adapter: axiosJsonpAdapter,
		        params: {
		          zipcode: this.zipcode
		        }
		      })

		      // 存在しない郵便番号でapiを叩くと200以外のステータスが返ってくる
		      if (res.status !== 200) return

		      // 返却されたデータを挿入する
		      this.prefecture = res.results[0].address1
		      this.address = res.results[0].address2 + res.results[0].address3
		    }
		  }
		}
	</script>
</body>
</html>