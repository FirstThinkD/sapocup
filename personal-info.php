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
					<h1>個人情報保護方針</h1>
				</div>
			</div>
		</div>
		<div class="otherContent">
			<div class="allWrapper">
				<div class="otherContentInner textWrap">
					<div class="textWrapInner">
						<h2>個人情報の保護に対する考え方</h2>
						<p>株式会社AMBIENCE(以下、「当社」といいます。）は、建設業許可、一級建築士事務所登録を受けた企業として設計から施工管理まで幅広く行っており、お客様からご提供いただきました個人情報の一つ一つがお客様のプライバシーを構成する重要な情報であることを深く認識し、業務において個人情報を取扱う場合には、確実・大切に扱うことはもちろん様々な情報に対し尊敬の念を持って取扱うと共に、個人情報に関する法律、当社の事業を通じて関係する全ての関係法令および個人情報保護のために定めた社内規定を、全ての役員、全ての社員が遵守することにより、お客様を尊重し、お客様からの当社に対する信頼にお応えしていきます。<br>
						制定年月日　2020年4月1日<br>
						株式会社AMBIENCE<br>
						代表取締役 松島　億</p>
						<p>当社は、当社が取り扱う全ての個人情報の保護について、社会的使命を十分に認識し、本人の権利の保護、個人情報に関する法規制等を遵守します。また、以下に示す方針を具現化するための個人情報保護マネジメントシステムを構築し、最新のＩＴ技術の動向、社会的要請の変化、経営環境の変動等を常に認識しながら、その継続的改善に、全社を挙げて取り組むことをここに宣言します。</p>
						<ul class="text_indent_two">
							<li>ａ）個人情報は、Archicollect事業・各種コンサルティング事業における当社の正当な事業遂行上並びに従業員の雇用、人事管理上必要な範囲に限定して、取得・利用及び提供をし、特定された利用目的の達成に必要な範囲を超えた個人情報の取扱い（目的外利用）を行いません。また、目的外利用を行わないための措置を講じます。</li>
							<li>ｂ）個人情報保護に関する法令、国が定める指針及びその他の規範を遵守致します。</li>
							<li>ｃ）個人情報の漏えい、滅失、き損などのリスクに対しては、合理的な安全対策を講じて防止すべく事業の実情に合致した経営資源を注入し個人情報セキュリティ体制を継続的に向上させます。また、個人情報保護上、問題があると判断された場合には速やかに是正措置を講じます。</li>
							<li>ｄ）個人情報取扱いに関する苦情及び相談に対しては、迅速かつ誠実に、適切な対応をさせていただきます。</li>
							<li>ｅ）個人情報保護マネジメントシステムは、当社を取り巻く環境の変化を踏まえ、適時・適切に見直してその改善を継続的に推進します。</li>
						</ul>
						<p>以上</p>
						<p>
						個人情報保護方針の内容についての相談窓口<br>
						株式会社AMBIENCE<br>
						〒103-0025<br>
						東京都中央区日本橋茅場町3-7-6<br>
						茅場町スクエアビル　6F<br>
						TEL　03-6661-1869　(受付時間　10：00～17：00※)<br>
						※土・日曜日、祝日、年末年始期間は翌営業日以降の対応とさせていただます。</p>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php include_once('./common/footer.php'); ?>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>
	<script>
		// ハンバーガーメニュー
		$('#nav-input').on('change',function(){
			if ($(this).prop('checked')) {
				$('#nav-content').addClass('navOpen');
			} else {
				$('#nav-content').removeClass('navOpen');
			}
		});

		// FAQのtoggle機能
		$(function(){
			$(".helpBoxRight").on('click', '.helpFaqQ', function(){
				$(this).toggleClass('rotate');
				$(this).next().animate({
					height: "toggle", opacity: "toggle"
				}, "normal");
			});
		});
	</script>
</body>
</html>