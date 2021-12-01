<?php
session_start();
$_SESSION['user_runFlag'] = 0;
$_SESSION['user_signup'] = "signup";
//[ただいま修正中となります。]の削除
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<link rel="stylesheet" type="text/css" href="/common/css/other.css?ver=1.0">
	<link rel="stylesheet" type="text/css" href="/common/css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP&display=swap" rel="stylesheet">
</head>
<body>
	<?php include_once('./common/header.php'); ?>
	<main id="searchFunction">
		<div class="otherScreen help">
			<div class="allWrapper">
				<div class="otherScreenInner">
					<h2><span><b style="color: #174773;display: inline">基本料金プラン（４８０円）</b>新規申込</span></h2>
                    <p style="padding: 20px;font-size: 100%;"><span>※オプションにつきましては登録後にサービスの追加が可能です。</span></p>
                </div>
            </div>
		</div>
		<div class="otherContent">
			<div class="allWrapper">
				<div class="otherContentInner signupWrap">
                    <!--<p>ただいま改修中のため、新規登録ができかねます。</p>-->
					<ul class="cf"><li id="personal"><a href="/personal-signup.php"></a><li id="company"><a href="/company-signup.php"></a><li id="specific"><a href="/contact.php"></a></ul>
					<!-- <?php
					$ua = getenv('HTTP_USER_AGENT');
					$letter = '<ul class="cf"><li id="personal"><a href="/personal-signup.php"></a><li id="company"><a href="/company-signup.php"></a><li id="specific"><a href=""></a></ul>';
					 
					if (strstr($ua, 'Edge') || strstr($ua, 'Edg')) {
					  echo $letter;
					} elseif (strstr($ua, 'Trident') || strstr($ua, 'MSIE')) {
					  echo "<p style='font-size: 1.2em;'>現在、IEブラウザは未対応です。下記いずれかのブラウザをご利用くださいませ。<br><br>Google Chrom　Firefox</p>";
					} elseif (strstr($ua, 'OPR') || strstr($ua, 'Opera')) {
					  echo $letter;
					} elseif (strstr($ua, 'Chrome')) {
					  echo $letter;
					} elseif (strstr($ua, 'Firefox')) {
					  echo $letter;
					} elseif (strstr($ua, 'Safari')) {
					  echo $letter;
					} else {
					  echo "<p style='font-size: 1.2em;'>ブラウザが未対応です。下記いずれかのブラウザをご利用くださいませ。<br><br>Google Chrom　Firefox</p>";
					}
					?> -->
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