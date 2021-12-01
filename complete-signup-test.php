<?php
session_start();
require_once(__DIR__ . '/common/dbconnect.php');

$file = "/usr/home/haw1008ufet9/html/paygent/log/post_response.log";
$data = date('Y/m/d H:i:s');
$data .= " u_id=". $_SESSION['customer_id']. " START";
$data .= "\n";
file_put_contents($file, $data, FILE_APPEND);

$_SESSION['customer_id'] = 1;
$_SESSION['user_signup'] = 'signup';


if ($_SESSION['customer_id'] != "") {
	$sql = sprintf('SELECT * FROM user WHERE u_id="%d" AND delFlag=2',
		mysqli_real_escape_string($db, $_SESSION['customer_id'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	if ($row0 = mysqli_fetch_assoc($record)) {
//		$sql = sprintf('UPDATE user SET delFlag=0, service1_fl=1 WHERE u_id="%d"',
//			mysqli_real_escape_string($db, $_SESSION['customer_id'])
//		);
//		mysqli_query($db,$sql) or die(mysqli_error($db));
        $u_id = $row0['u_id'];
		if ($row0['u_type'] == "法人") {
			$to_mail   = $row0['u_email'];
			$user_name = $row0['u_company'];
            $reg_url = 'https://sapocup.jp/complete-register.php?u_id='.$u_id.'&token='.$row0['u_pass'];
		} else {
			$to_mail   = $row0['p_email'];
			$user_name = $row0['p_name'];
            $reg_url = 'https://sapocup.jp/complete-register.php?u_id='.$u_id.'&token='.$row0['p_pass'];
		}
		unset($_SESSION['PAY_SERVICE']);
		unset($_SESSION['verifi_url']);
		// unset($_SESSION['customer_id']);
		unset($_SESSION['connect_id']);
		unset($_SESSION['CONNECT_PS']);
	} else {
		// customer_idが不一致
		header("Location:/");
		exit();
	}
    $to_mail = "kastumoto1019@gmail.com";

	$to = $to_mail;
	if ($_SESSION['user_signup'] == "signup") {
		$title = "【さぽかっぷ】仮会員登録完了のお知らせ";
	} else {
		$title = "【さぽかっぷ】会員登録（再申込）完了のお知らせ";
	}
	$body  = "※このメールはシステムからの自動返信です". "\r\n\r\n";
	$body .= $user_name. " 様". "\r\n\r\n";
	$body .= "「さぽかっぷ」運営企業の株式会社ANBIENCEでございます。". "\r\n";
	if ($_SESSION['user_signup'] == "signup") {
        $body .= "この度は、「さぽかっぷ」に仮登録いただきまして誠にありがとうございます。". "\r\n";
        $body .= "下記のURLを開いて本登録を完了させてください。". "\r\n";
        $body .= $reg_url . "\r\n";
	} else {
        $body .= "この度は、「さぽかっぷ」に仮登録いただきまして誠にありがとうございます。". "\r\n";
		$body .= "会員再申込のお手続きが、完了いたしました。". "\r\n";
		$body .= "※なお、退会時にご登録いただいているＩＤ（メールアドレス）とパスワードにてログインいただくと、". "\r\n";
		$body .= "以前ご登録いただきました情報について、閲覧・出力のみご利用いただけます。". "\r\n\r\n";
	}
	$body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━". "\r\n";
	$body .= "■お客様の会員番号（ログインID）". "\r\n";
	$body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━". "\r\n";
	$body .= "会員番号：". $u_id. "\r\n\r\n";
	$body .= "会員番号およびパスワードは、". "\r\n";
	$body .= "「さぽかっぷ」にログインする際に必要となります。". "\r\n";
	$body .= "忘れず保管をお願いいたします。". "\r\n\r\n";
	$body .= "また、ご不明な点がございましたら". "\r\n";
	$body .= "下記までお気軽にお問い合わせくださいませ。". "\r\n";
	$body .= "――――――――――――". "\r\n\r\n";
	$body .= "「さぽかっぷ」". "\r\n";
	$body .= "URL：https://sapocup.jp/contact.php". "\r\n";
	$body .= "メールアドレス：sapocup01@sapocup.jp". "\r\n\r\n";
	$body .= "営業時間：　平日 10:00～17:00". "\r\n\r\n";
	$body .= "【運営元：株式会社AMBIENCE】". "\r\n";
	$body .= "住所：〒103-0025　東京都中央区日本橋茅場町3-7-6". "\r\n\r\n";

	$header = "From:sapocup01@sapocup.jp". "\r\n";
	$header .= "Bcc:sapocup01@sapocup.jp". "\r\n";
	$header .= "Bcc:nakazawa6097@gmail.com". "\r\n";
	// $header .= "Bcc:chankan77@gmail.com". "\r\n";

	mb_language('ja');
	mb_internal_encoding('UTF-8');
	mb_send_mail($to, $title, $body, $header);

	$file = "/usr/home/haw1008ufet9/html/paygent/log/post_response.log";
	$data = date('Y/m/d H:i:s');
	$data .= " u_id=". $_SESSION['customer_id']. " END to_mail=". $to_mail. " user_name=". $user_name. " user_signup=". $_SESSION['user_signup'];
	$data .= "\n";
	file_put_contents($file, $data, FILE_APPEND);

	unset($_SESSION['customer_id']);
} else {
	header("Location:/");
	exit();
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
</head>
<body>
	<?php include_once('./common/header.php'); ?>
	<main id="searchFunction">
		<div class="otherScreen help">
			<div class="allWrapper">
				<div class="otherScreenInner">
					<h1>仮登録完了</h1>
				</div>
			</div>
		</div>
		<div class="otherContent">
			<div class="allWrapper">
				<div class="otherContentInner loginWrap">
					<div class="loginBox">
						<div class="inputBox">
							<?php if ($_SESSION['user_signup'] == "signup") { ?>
                                <p>ご登録メールアドレスに本登録用メールを送信しました。</p>
                                <p style="white-space: nowrap">メールに記載されているURLにアクセス、本登録を完了してください。</p>
								<?php $_SESSION['user_signup'] = ""; ?>
							<?php } else { ?>
								<p>入会（再申込）のお手続きが完了いたしました。</p>
								<p style="white-space: nowrap">ご登録いただいたメールアドレスに、登録完了メールをお送りいたします。</p>
								<?php $_SESSION['user_signup'] = ""; ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php include_once('./common/footer.php'); ?>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<script src="/common/js/footerFixed.js"></script>
	<script>
		// ハンバーガーメニュー
		$('#nav-input').on('change',function(){
			if ($(this).prop('checked')) {
				$('#nav-content').addClass('navOpen');
			} else {
				$('#nav-content').removeClass('navOpen');
			}
		});

	</script>
</body>
</html>