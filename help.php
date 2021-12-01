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
	<?php include_once("./common/header.php"); ?>
	<main id="searchFunction">
		<div class="otherScreen help">
			<div class="allWrapper">
				<div class="otherScreenInner">
					<h1>FAQ</h1>
					<div class="searchBox">
						<input v-model="keyword" type="text">
					</div>
				</div>
			</div>
		</div>
		<div class="otherContent">
			<div class="allWrapper">
				<div class="otherContentInner helpWrap">
					<div class="helpBox cf">
						<div class="helpBoxLeft">
							<span>システム/アカウント</span>
						</div>
						<div class="helpBoxRight">
							<div class="helpFaq" v-for="account in filteredAccounts">
								<div class="helpFaqQ">
									<p v-text="account.q"></p>
								</div>
								<div class="helpFaqA">
									<div class="helpFaqAInner">
										<p v-text="account.a"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="helpBox cf">
						<div class="helpBoxLeft">
							<span>機能について</span>
						</div>
						<div class="helpBoxRight">
							<div class="helpFaq" v-for="output in filterOutputs">
								<div class="helpFaqQ">
									<p v-text="output.q"></p>
								</div>
								<div class="helpFaqA">
									<div class="helpFaqAInner">
										<p v-text="output.a"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="helpBox cf">
						<div class="helpBoxLeft">
							<span>その他について</span>
						</div>
						<div class="helpBoxRight">
							<div class="helpFaq" v-for="other in filterOthers">
								<div class="helpFaqQ">
									<p v-text="other.q"></p>
								</div>
								<div class="helpFaqA">
									<div class="helpFaqAInner">
										<p v-text="other.a"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php include_once("./common/footer.php"); ?>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<script src="/common/js/footerFixed.js"></script>
	<script>
		new Vue({
			el: "#searchFunction",
			data: {
				keyword: "",
				accounts: [
					// {
					// 	q: "１．パスワードを忘れてしまった場合",
					// 	a:  "①ログイン画面の「パスワードをお忘れの方はこちら」をクリックします。\n"+"②「パスワードリセット」画面へ遷移後、さぽかっぷに登録しているメールアドレスを入力し 【パスワード再設定】をクリック\n"+"③時間が経過しても「再設定メール」が届かない場合は、以下の場合が考えられます。\n"+"\t・迷惑メールとしてはじかれている\n"+"\t・入力されたメールアドレスに誤りがある\n"+"④「パスワード再設定メール」に記載のURLをクリック後、パスワード変更画面が表示されるため新たなパスワードを入力し、「パスワードを登録」を押すことで再設定が完了いたします。"
					// },

					{
						q: "１．パスワードを忘れてしまった場合",
						a:  "①ログイン画面の「パスワードをお忘れの方はこちら」をクリックします。\n"+
						"②「パスワードリセット」画面へ遷移後、さぽかっぷに登録しているメールアドレスを入力し 【パスワード再設定】をクリック\n"+
						"③時間が経過しても「再設定メール」が届かない場合は、以下の場合が考えられます。\n"+
						"・迷惑メールとしてはじかれている\n"+
						"・入力されたメールアドレスに誤りがある\n"+
						"④「パスワード再設定メール」に記載のURLをクリック後、パスワード変更画面が表示されるため新たなパスワードを入力し、「パスワードを登録」を押すことで再設定が完了いたします。"
					},
					{
						q: "２．メール/SMSが届かない",
						a: "携帯・スマートフォンのアドレスをご利用の方\n"+
						"docomo、au、softbankなど各キャリアのセキュリティ設定のためユーザー受信拒否と認識されているか、お客様が迷惑メール対策等で、ドメイン指定受信を設定されている場合に、メールが正しく届かないことがございます。\n\n"+
						"以下ドメインを受信できるように設定をお願い致します。\n"+
						"@sapocup.jp\n\n"+
						"また上記ドメインの設定を以っても尚、メールが届かない場合には、以下対応をお願いいたします。\n"+
						"⑴迷惑メールフォルダに振り分けの可能性があるため、確認を行う\n"+
						"⑵キャリアや端末の設定が起因している可能性があるため、各種設定の確認を再度行う\n"+
						"⑶メールアドレスが半角で入力されているか確認する\n\n"+
						"上記対応後もメールの受信が確認できない場合には、お手数ですが、\n"+
						"さぽかっぷ内画面右上部、【お問い合わせ】より「内容」に会員番号※を記載の上ご連絡ください。\n"+
						"※会員番号は、【ログイン】→【会員情報】→「会員登録」よりご確認いただけます。"
					},
					{
						q: "３．chromeを使用しているがPDFの出力ができない	",
						a: "⑴パソコンで Chrome を開きます。\n"+
						"⑵ポップアップがブロックされているページに移動します。\n"+
						"⑶アドレスバーで、ポップアップのブロック ポップアップがブロックされました をクリックします。\n"+
						"⑷表示するポップアップのリンクをクリックします。\n"+
						"⑸そのサイトのポップアップを常に表示する場合は、[サイト] のポップアップとリダイレクトを常に許可する] 次に [完了] を選択します。"
					},
				],
				outputs: [
					{
						q: "１．新規顧客の追加方法が知りたい",
						a: "①ログイン→【顧客データ】→【新規顧客追加】\n"+
						"②ログイン→【顧客】→【新規顧客追加】"
					},
					{
						q: "２．顧客データの編集方法が知りたい",
						a: "ログイン→【顧客】→「顧客一覧」画面より【編集】→「顧客情報編集」画面より【顧客編集】"
					},
					{
						q: "３．お支払シミュレーションの使い方について",
						a: "各お客様のお支払シミュレーションは\n"+
						"ログイン→【見積書】→「見積書一覧」画面より【新規見積書作成】→【見積書作成】→【シミュレーション画面へ】\n"+
						"→【パターン1/2/3で送信する】→【見積書を登録】\n\n"+
						"又は、\n\n"+
						"ログイン→【見積書】→「見積書一覧」画面より→【シミュレーション】→【シミュレーション編集】\n"+
						"→【パターンを更新する】→シミュレーション画面より【パターン1/2/3】を選択→【見積書へ反映】\n\n"+
						"※見積書作成時・変更時にも算出することが可能です。\n\n"+
						"同画面上では、シミュレーションと返済スケジュールをご確認いただけます。\n"+

						"※反映されない場合には、見積書編集画面にて見積金額が入力されていることを、ご確認ください。"
					},
					{
						q: "４．回収日の確認方法について",
						a: "ログイン→【顧客データ】→入金予定日にてご確認いただけます。"
					},
					{
						q: "５．見積書・請求書入力方法について",
						a: "①新規見積書：ログイン→【見積書】→【新規見積書作成】→作成後【シミュレーション画面へ】→【パターン1/2/3を送信する】→【見積書を登録】\n"+
						"※商品/工事「見積金額」が未入力のままですと、シミュレーションには反映されません。ご注意ください。\n"+
						"②新規請求書：下記２つの方法より作成することが可能です。\n"+
						"⑴ログイン→【見積書】→【シミュレーション】→【見積書へ反映】→【請求書反映】\n"+
						"⑵ログイン→【見積書】→見積書名称を押下→【請求書反映】"
					},
					{
						q: "６．見積書・請求書編集方法について",
						a: "①見積書：下記二つの方法により編集することが可能です。\n"+
						"⑴ログイン→【見積書】→【編集】\n"+
						"⑵ログイン→【顧客データ/見積書】→【工事名称】→【見積書編集】\n"+
						"②請求書：下記二つの方法により編集することが可能です。\n"+
						"⑴ログイン→【見積書】→【シミュレーション】→【見積書反映】→【請求書反映】\n"+
						"⑵ログイン→【請求書】→【編集】"
					},
					{
						q: "７．見積書出力の操作方法について",
						a: "下記三つの方法より出力することが可能です。\n"+
						"⑴ログイン→【顧客データ】→出力したい見積書「名称」を選択する→「見積書詳細」画面において【出力】を押下\n"+
						"⑵ログイン→【顧客データ】又は【見積書】を押下→「名称」を選択→【出力】\n"+
						"⑶ログイン→【検索・帳票】→「対象データ」を選択、「顧客データ」の「顧客番号」「顧客氏名」を選択→【検索】→見積書「名称」を選択→【出力】"
					},
					{
						q: "８．請求書出力の操作方法について",
						a: "下記三つの方法より出力することが可能です。\n"+
						"⑴ログイン→【見積書】→【請求書】「請求書編集」より【請求書反映】→「請求書一覧」画面より、請求書「名称」を押下→【出力】\n"+
						"⑵ログイン→【請求書】→請求書「名称」を押下→【出力】\n"+
						"⑶ログイン→【検索・帳票】→対象データ「請求書」選択→顧客データ選択し【検索】→請求書「名称」を押下→【出力】"
					},
					{
						q: "９．見積書・請求書の削除方法について",
						a: "作成済みの見積書・請求書を削除したい場合には、【見積書・請求書一覧】→「見積書・請求書名称」→プレビュー画面右上に表示される削除ボタン（絵）を押下してください。"
					},
					{
						q: "１０．検索機能の操作方法について",
						a: "①見積書②請求書\n"+
						"顧客番号または顧客氏名により見積書・請求書の閲覧および検索が可能です。\n"+
						"また、閲覧可能となる上記帳票は、現在登録中の顧客一覧がベースとなっております。\n"+
						"③顧客一覧\n"+
						"ご選択いただいた顧客の基本情報を【編集】することが可能です。\n"+
						"④顧客データ管理\n"+
						"ご選択いただいた分割払い工事の情報を閲覧可能です。"
					},
					{
						q: "１１．「入金予定事前通知」の利用方法について",
						a: "ご登録いただいたメールアドレス宛に、毎月1日に当月分の「入金予定者一覧」をお送りします。当一覧では、入金予定者や、入金金額等をご確認いただけます。\n"+
						"会員様ご自身でのご設定は不要です。"
					},
					{
						q: "１２．データのバックアップについて",
						a: "さぽかっぷにご入力いただいた各種データにつきましては、定時的、自動的にバックアップされます。\n"+
						"ただし、会員登録期間内のデータ請求につきましては原則承っておりません。\n"+
						"開示請求が可能となる期間につきましては、退会後一年間 となります。\n"+
						"詳細は、『さぽかっぷ利用規約』にてご確認ください。"
					},
					{
						q: "１３．「入金案内」とはどのようなことを指すか",
						a: "①SMS依頼：お支払期日経過後も尚、未入金の顧客へ向け、さぽかっぷよりショートメッセージを送信し入金案内を代行。\n"+
						"②自動音声案内依頼：①SMSを以っても尚、未入金となった顧客に対し、月上限三回まで自動音声にて入金案内を代行。\n"+
						"③オペレーター案内依頼：②自動音声案内を以っても尚、未入金となった顧客に対し、さぽかっぷのオペレーターによりの電話入金案内が可能。"
					},
					{
						q: "１４．「入金案内」機能について、「依頼」を行うことができる期間は決まっているか",
						a: "入金予定日を含む月の翌月末日まで。"
					},
					{
						q: "１５．「入金案内」機能の受付時間について",
						a: "SMS依頼や、自動音声案内依頼、オペレーター依頼については、各ご選択いただきました後、運営側で依頼内容を確認の上ご指定の顧客様へご案内をさせていただく、といった手はずとなっております。\n"+
						"上記確認作業は、平日10：00~17：00の間に行われますので、営業時間外のご依頼に関しては、翌営業日の確認となります。"
					},
					{
						q: "１６．「お問い合わせチャット」機能について",
						a: "チャット機能をご契約いただいた会員様専用のお問い合わせチャンネルです。\n"+
						"さぽかっぷについて、ご利用方法や機能等のご質問にお答えいたします。"
					},
					{
						q: "１７．「お問い合わせチャット」機能の利用方法について",
						a: "チャット機能とは、会員登録後に「チャット」オプションを【選択】、その後決済手続きを完了させたユーザー様が利用可能なお問い合わせ窓口となります。サイト画面右上【お問い合わせチャット】を押下後、あらかじめ用意された質問事項の中から、ご自身の疑問点に近いものを選択いただき、さぽかっぷを利用する上でのご不明点を解決いただけるサポート機能となります。\n"+
						"受付時間は、土日祝日を除く10時~17時となります。"
					},
				],
				others: [
					{
						q: "１．会員情報の変更について",
						a: "ログイン→【会員情報】→【登録情報変更】→【会員情報更新】"
					},
					{
						q: "２．退会方法について",
						a: "ログイン→【会員情報】→【登録情報変更】→契約情報→基本契約480円プラン→【解約】"
					},
					{
						q: "３．退会後のさぽかっぷ機能に関して",
						a: "基本料金の解約により、利用者は退会したものとみなされ、退会前に追加した見積書、請求書、顧客データ等に関しては、【編集】や【反映】機能の使用ができなくなります。"
					},
					{
						q: "４．「顧客番号」とは何を指すのか",
						a: "さぽかっぷでは、ご登録いただいた「顧客」に対し「顧客番号」を付番いたします。\n"+
						"検索・帳票画面にて各種データの検索時に必要となります。\n\n"+
						"確認方法\n"+
						"ログイン→【顧客】→「顧客一覧」→「顧客番号」よりご確認ください。"
					},
					{
						q: "５．再申込について",
						a: "退会前に登録したメールアドレスを用いて、再度ユーザー登録を行うことが可能です。\n"+
						"その際、「新規申込」ではなく「再申込」ボタンより登録を行うものとします。この時、退会前に使用したパスワードは再使用できません。\n"+
						"また、「再申込」により、「編集」「反映」機能は使用可能となりますが、退会前にご登録いただいた各データを、同期させることはできません。"
					},
					{
						q: "６．退会後のデータのお取り扱いについて",
						a: "「退会」と「再申込」を二回以上繰り返した場合、前回分のデータのみのお取り扱いとなります。\n"+
						"この場合、前々回分のデータの閲覧は出来かねますが、弊社サーバー上には『さぽかっぷ利用規約』第8条に基づき、データの記録日より所定期間保管されます。"
					},
					{
						q: "７．「特定業者」とは",
						a: "賃貸物件管理会社様向けに開設したプランとなり、個々のご契約ごとにサービスのご提供内容が異なります。\n"+
						"詳細は、【お問い合わせ】よりご連絡ください。"
					},
					{
						q: "８．ご利用金額の請求屋号について",
						a: "利用規約第3条（利用料金等）6項　参照\n"+
						"『さぽかっぷの運営企業は株式会社AMBIENCEであるが、基本料金やオプション料金をクレジットカード決済いただく際に利用者のカード明細に掲載される請求者名は、決済システムの都合上親会社である「アールエムトラスト株式会社」となります。"
					}
				]
			},
			computed: {
				filteredAccounts: function() {
					let accounts = [];
					for(let i in this.accounts) {
						let account = this.accounts[i];

						if(account.q.indexOf(this.keyword) !== -1 || account.a.indexOf(this.keyword) !== -1) {
							accounts.push(account);
						}
					}
					return accounts;
				},
				filterOutputs: function() {
					let outputs = [];
					for(let i in this.outputs) {
						let output = this.outputs[i];

						if(output.q.indexOf(this.keyword) !== -1 || output.a.indexOf(this.keyword) !== -1) {
							outputs.push(output);
						}
					}
					return outputs;
				},
				filterOthers: function() {
					let others = [];
					for(let i in this.others) {
						let other = this.others[i];

						if(other.q.indexOf(this.keyword) !== -1 || other.a.indexOf(this.keyword) !== -1) {
							others.push(other);
						}
					}
					return others;
				}
			}
		});
	</script>
	<script>
		// ハンバーガーメニュー
		$("#nav-input").on("change",function(){
			if ($(this).prop("checked")) {
				$("#nav-content").addClass("navOpen");
			} else {
				$("#nav-content").removeClass("navOpen");
			}
		});

		// FAQのtoggle機能
		$(function(){
			$(".helpBoxRight").on("click", ".helpFaqQ", function(){
				$(this).toggleClass("rotate");
				$(this).next().animate({
					height: "toggle", opacity: "toggle"
				}, "normal");
			});
		});
	</script>
</body>
</html>