<?php
session_start();

$error = "";

if (isset($_POST['send2']) && $_POST['send2'] == "送信") {
	//print_r($_POST);
    $admin_mail='sapocup02@sapocup.jp';
    if($_POST['usage']==1){
        $admin_mail='sapocup01@sapocup.jp';
    }
	$to = $_POST['email'];
	$title = "【さぽかっぷ】お問い合わせ頂きありがとうございます";
	$body  = $_POST['s_name']. " 様". "\r\n\r\n\r\n";
	$body .= "この度は、お問い合せ頂き、誠にありがとうございました。". "\r\n";
	$body .= "下記送信内容にて受付いたしました。". "\r\n\r\n";
	$body .= "後日、改めて担当者よりご連絡をさせていただきます。". "\r\n\r\n";
	$body .= "2～3日経過してもご返信が無い場合、". "\r\n";
   	$body .= "お手数ですが、直接お問い合わせください。". "\r\n\r\n\r\n";
	$body .= "-----". "\r\n\r\n";
	$body .= "Eメールアドレス：". $_POST['email']. "\r\n";
	$body .= "法人名：". $_POST['corporation']. "\r\n";
	$body .= "氏名：". $_POST['s_name']. "\r\n";
	$body .= "件名：". $_POST['s_subject']. "\r\n";
	$body .= "お問い合わせカテゴリー：". $_POST['category']. "\r\n";
	$body .= "お問い合わせ内容：". "\r\n";
	$body .= $_POST['naiyo']. "\r\n";
	$body .= "-----". "\r\n\r\n";

	$body .= "このメールは、配信専用のアドレスで配信されております。". "\r\n";
	$body .= "このメールに返信頂いても、返信内容の確認およびご返答ができません。". "\r\n";
	$body .= "あらかじめご了承ください。". "\r\n";

	$header  = "From:{$admin_mail}". "\r\n";
	$header .= "Bcc:{$admin_mail}". "\r\n";
	// $header .= "Bcc:chankan77@gmail.com". "\r\n";
	// $header .= "Bcc:nakazawa6097@gmail.com". "\r\n";

	mb_language('ja');
	mb_internal_encoding('UTF-8');
	mb_send_mail($to, $title, $body, $header);

	$error = "message";
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
					<h1>お問い合わせ</h1>
					<p>受付時間： 10:00〜17:00（土日除く）</p>
				</div>
			</div>
		</div>
		<div class="otherContent">
			<div class="allWrapper">
				<div class="otherContentInner loginWrap">
					<div class="loginBox">
						<form action="" method="post" accept-charset="utf-8">
							<?php if ($error == "message") { ?>
							<font color="#f05b72">メールを送信しましたので、ご確認ください。</font>
							<font color="#f05b72">後ほど担当者からご連絡させて頂きます。</font>
							<?php } ?>
							<div class="inputBox usage">
								<p>お客様のご利用状況につきまして<span class="must">※</span></p>
                                <ul class="cf">
                                    <li><span class="category">
                                            <input id="usage2" type="radio" name="usage" value="2">
                                            <label for="usage2">既にご利用いただいている方</label>
                                        </span>
                                    </li>
                                    <li><span class="category">
                                            <input id="usage1" type="radio" name="usage" value="1" checked="checked">
                                            <label for="usage1">ご利用をご検討中の方</label>
                                        </span>
                                    </li>
                                </ul>
							</div>
							<div class="inputBox">
								<p>Eメールアドレス<span class="must">※</span></p>
								<input type="email" class="form-control blue_input" name="email" required="required">
							</div>
							<div class="inputBox">
								<!-- <p>法人名<span class="must">※</span></p> -->
								<p>法人名</p>
								<input type="text" class="form-control blue_input" name="corporation">
							</div>
							<div class="inputBox">
								<p>氏名<span class="must">※</span></p>
								<input type="text" class="form-control blue_input" name="s_name" required="required">
							</div>
							<div class="inputBox">
								<p>件名<span class="must">※</span></p>
								<input type="text" class="form-control blue_input" name="s_subject" required="required">
							</div>
							<div class="inputBox categoryList">
								<p>お問い合わせカテゴリー<span class="must">※</span></p>
								<ul class="cf">
									<li><span class="category"><input type="radio" name="category" value="サービス">サービス</span></li>
									<li><span class="category"><input type="radio" name="category" value="料金">料金</span></li>
									<li><span class="category"><input type="radio" name="category" value="導入">導入</span></li>
								</ul>
							</div>
							<div class="inputBox">
								<p>内容</p>
								<textarea name="naiyo" readonly="readonly"></textarea>
							</div>
							<div class="inputBox submitBox">
								<input type="submit" name="send2" value="送信" disabled>
							</div>
						</form>
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

		// カテゴリー
		$('.categoryList ul li').on('click',function(){
			$('.categoryList ul li').removeClass('selected');
			$(this).addClass('selected');
			if($('.categoryList ul li').hasClass('selected')) {
				$('.inputBox textarea').attr('readonly',false);
				$('.inputBox input[type=submit]').attr('disabled',false);
			};
		});
	</script>
</body>
</html>