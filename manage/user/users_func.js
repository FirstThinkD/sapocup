function send1(ptn, onoff, svc1flg){
	if (svc1flg == 0) {
		alert("退会状態でサービスの利用はできません。");
		return 0;
	}
	if (ptn == 1) {
		if (onoff == 1) {
			if (window.confirm("会員退会となります。\n退会後に再度会員登録をされても過去のデータのお取り扱いには制限がかかります。\n退会しますか？詳細はさぽかっぷ利用規約をご確認ください。")) {
				location.href = "/paygent/pay_stop.php?ptn=1&id=1";
			}
		} else {
			// if (window.confirm("基本サービスを登録します。\n宜しいですか？")) {
			//	location.href = "/paygent/?ptn=1&id=0";
			// }
		}
	} else {
		if (onoff == 0) {
			// 登録
			if (ptn == 2) {
				if (window.confirm("「SMS」サービスを登録します。\n宜しいですか？")) {
					location.href = "/paygent/pay_update.php?ptn=2&id=0";
				}
			} else if (ptn == 3) {
				if (window.confirm("「SMS+自動音声案内」サービスを登録します。\n宜しいですか？")) {
					location.href = "/paygent/pay_update.php?ptn=3&id=0";
				}
			} else if (ptn == 4) {
				if (window.confirm("「SMS+自動音声案内+オペレーター案内」サービスを登録します。\n宜しいですか？")) {
					location.href = "/paygent/pay_update.php?ptn=4&id=0";
				}
			} else {
				if (window.confirm("「使用者お問い合わせ対応」サービスを登録します。\n宜しいですか？")) {
					location.href = "/paygent/pay_update.php?ptn=5&id=0";
				}
			}
		} else {
			// 停止
			if (ptn == 2) {
				if (window.confirm("「SMS」サービスを停止します。\n宜しいですか？")) {
					location.href = "/paygent/pay_drop.php?ptn=2&id=1";
				}
			} else if (ptn == 3) {
				if (window.confirm("「SMS+自動音声案内」サービスを停止します。\n宜しいですか？")) {
					location.href = "/paygent/pay_drop.php?ptn=3&id=1";
				}
			} else if (ptn == 4) {
				if (window.confirm("「SMS+自動音声案内+オペレーター案内」サービスを停止します。\n宜しいですか？")) {
					location.href = "/paygent/pay_drop.php?ptn=4&id=1";
				}
			} else {
				if (window.confirm("「使用者お問い合わせ対応」サービスを停止します。\n宜しいですか？")) {
					location.href = "/paygent/pay_drop.php?ptn=5&id=1";
				}
			}
		}
	}
}
