	<!-- グローバルナビ -->
	<header id="header">
		<div id="headline">
			<div class="headline_inner">
				<div class="container cf">
					<div class="headerline_logo">
						<img src="/common/img/logo.jpg" alt="">
					</div>
					<div class="headerine_option">
						<div class="headerline_option_inner">
							<div class="headerline_option_bottom">
								<div class="username"><?php echo $_SESSION['loginName']; ?> 様 <a href="/logout.php">［ログアウト］</a></div>
							</div>
							<div class="headerline_option_top cf">
								<div class="option_chat"><a id="chatButton">お問い合わせチャット</a></div>
								<div class="option_edit"><a href="/manage/user/">会員情報</a></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php $url2 = $_SERVER["REQUEST_URI"]; // 現在のURLを取得 ?>
		<?php $leng = strpos($url2, "?"); ?>
		<?php if ($leng != 0) { ?>
			<?php $url = substr($url2, 0, $leng); ?>
		<?php } else { ?>
			<?php $url = $url2; ?>
		<?php } ?>
		<nav id="nav">
			<div class="nav_inner">
				<ul class="container cf">
					<li class="mav_button <?php if ($url == "/manage/data/") { echo "selected"; }; ?>"><a href="/manage/data/da_stat.php">顧客データ</a></li>
					<li class="mav_button <?php if ($url == "/manage/estimates/" || $url == "/manage/estimates/new/" || $url == "/manage/estimates/edit/" || $url == "/manage/estimates/detail/" || $url == "/manage/estimates/simulation/" || $url == "/manage/estimates/simulation/edit/") { echo "selected"; }; ?>"><a href="/manage/estimates/">見積書</a></li><li class="mav_button <?php if ($url == "/manage/invoices/") { echo "selected"; }; ?>"><a href="/manage/invoices/">請求書</a></li>
					<li class="mav_button <?php if ($url == "/manage/clients/" || $url == "/manage/clients/new/" || $url == "/manage/clients/edit/") { echo "selected"; }; ?>"><a href="/manage/clients/">顧客</a></li>
					<li class="mav_button <?php if ($url == "/manage/search/") { echo "selected"; }; ?>"><a href="/manage/search/">検索・帳票</a></li>
					<li class="mav_button nav_right"><a href="">入金案内</a></li>
					<li class="mav_button nav_right"><a href="">データバックアップ</a></li>
				</ul>
			</div>
		</nav>
	</header>