<?php
session_start();
require_once(__DIR__ . '/common/dbconnect.php');

$token = $_GET['token'];
$u_id = $_GET['u_id'];

$sql = sprintf('SELECT COUNT(*) AS user_count FROM user WHERE (u_id="%s") AND (u_pass="%s" OR p_pass="%s")',
    mysqli_real_escape_string($db, $u_id),
    mysqli_real_escape_string($db, $token),
    mysqli_real_escape_string($db, $token)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row0 = mysqli_fetch_assoc($record);
if ($row0['user_count'] == 1) {
    $sql = sprintf('SELECT * FROM user WHERE (u_id="%s") AND (u_pass="%s" OR p_pass="%s")',
        mysqli_real_escape_string($db, $u_id),
        mysqli_real_escape_string($db, $token),
        mysqli_real_escape_string($db, $token)
    );
    $record = mysqli_query($db, $sql) or die(mysqli_error($db));
    while($row0 = mysqli_fetch_assoc($record)) {
        // OK
        if($row0['delFlag']==2){
            $sql = sprintf('UPDATE `user` SET pass_count="0" , delFlag="0", service1_fl=1 WHERE u_id="%d"',
                mysqli_real_escape_string($db, $row0['u_id'])
            );
            mysqli_query($db, $sql) or die(mysqli_error($db));
        }

		if ($row0['u_type'] == "法人") {
			$to_mail   = $row0['u_email'];
			$user_name = $row0['u_company'];
		} else {
			$to_mail   = $row0['p_email'];
			$user_name = $row0['p_name'];
		}
        $to = $to_mail;
        $title = "【さぽかっぷ】会員登録完了のお知らせ";
        $body  = "※このメールはシステムからの自動返信です". "\r\n\r\n";
        $body .= $user_name. " 様". "\r\n\r\n";
        $body .= "「さぽかっぷ」運営企業の株式会社ANBIENCEでございます。". "\r\n";
        $body .= "この度は、「さぽかっぷ」にご登録いただきまして誠にありがとうございます。". "\r\n";
        $body .= "会員入会の手続きは、完了いたしました。". "\r\n\r\n";
        $body .= "このメールは、ご登録時に確認のため送信させていただいております。". "\r\n";
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

        $header  = "From:sapocup01@sapocup.jp". "\r\n";
        $header .= "Bcc:sapocup01@sapocup.jp". "\r\n";
        $header .= "Bcc:nakazawa6097@gmail.com". "\r\n";
        // $header .= "Bcc:chankan77@gmail.com". "\r\n";

        @mb_language('ja');
        @mb_internal_encoding('UTF-8');
        @mb_send_mail($to, $title, $body, $header);

        $file = "/usr/home/haw1008ufet9/html/paygent/log/post_response.log";
        $data = date('Y/m/d H:i:s');
        $data .= " u_id=". $u_id. " END to_mail=". $to_mail. " user_name=". $user_name. " complete-register";
        $data .= "\n";
        @file_put_contents($file, $data, FILE_APPEND);

        unset($_SESSION['customer_id']);

		/* auto login
        $_SESSION['loginID'] = $row0['u_id'];
        if ($row0['u_type'] == "法人") {
            $_SESSION['loginName'] = $row0['u_company'];
            $_SESSION['loginMail'] = $row0['u_email'];
        } else {
            $_SESSION['loginName'] = $row0['p_name'];
            $_SESSION['loginMail'] = $row0['p_email'];
        }
        $_SESSION['data_ymd'] = date('Ymd');
        $_SESSION['service5_chat'] = $row0['service5_fl'];
        $_SESSION['user_type']     = $row0['u_type'];
        $_SESSION['user_exeFlag']  = $row0['exeFlag'];
        $_SESSION['user_runFlag']  = 1;

        header("Location:/manage/data/");
        exit();
		*/
    }
} else {
	header("Location:/");
	exit();
}?>
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
					<h1>本登録完了</h1>
				</div>
			</div>
		</div>
		<div class="otherContent">
			<div class="allWrapper">
				<div class="otherContentInner loginWrap">
					<div class="loginBox">
						<div class="inputBox">
						    <p>本登録をご完了いただきましてありがとうございます。</p>
						    <p>ご登録いただきましたアドレスに本登録完了メールを送信致しました。</p><br/>
						    <p>※メール発行には、暫く時間がかかる場合がございます。</p>
						    <p>暫く経ってもメールが届かない場合はお問い合わせください。</p>
						</div>
					</div>
					<div class="loginOther">
						<a href="/login.php">ログイン画面へ</a>
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