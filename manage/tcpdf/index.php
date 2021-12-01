<?php
ini_set("display_errors", 1);

/* ---------------------------------------------------------------------



	初期設定



--------------------------------------------------------------------- */
require_once(__DIR__ . '/tcpdf/tcpdf.php'); // TCPDFライブラリ
require_once(__DIR__ . '/fpdi/src/autoload.php'); // FPDIライブラリ
use setasign\Fpdi\TcpdfFpdi; // FPDIクラス読み込み
/*
	$pdf = new FPDI();
	$pdf = new TCPDF();

	$pdf = new Fpdi\TcpdfFpdi();
*/
$pdf = new TcpdfFpdi(); // フォント設定


/* ---------------------------------------------------------------------



	変数まとめ



--------------------------------------------------------------------- */
/* 契約情報 */
$contract_num		= "12";					// ナンバー
$contract_date		= "2020年4月3日　金曜日";		// 日付
$contract_company	= "アールエムトラスト株式会社";		// 会社名
$contract_name		= "自宅リフォーム";			// 工事・商品名称
$contract_place 	= "東京都東京市1-1-1";			// 工事・商品場所
$contract_pay 		= "2020年1月31日　金曜日";		// 支払日
$contract_deliver 	= "2021年12月31日　金曜日";		// 受渡期日

/* 貴社情報 */
$our_company 		= "ABC株式会社";			// 会社情報
$our_person 		= "松島　億";				// 担当者名
$our_post		= "000-0000";				// 住所郵便番号
$our_address1		= "東京都中央区日本橋茅場町3-7-6";	// 住所1
$our_address2		= "茅場町スクエアビル";			// 住所2
$our_address3		= "6F";					// 住所3
$our_tel		= "03-6231-1902";			// 住所1

/* 見積書情報 */
$table_total		= "¥719,667";				// 見積もり・請求合計
$table_tax		= "¥68,539"; 				// 消費税
$table_name		= "¥685,398";				// 工事・商品価格
$table_fee		= "¥9,240";				// 割賦手数料
$table_first		= "¥125,830";				// 初回お支払額
$table_month		= "¥25,819";				// 月々お支払額
$table_split		= "24回";				// 分割回数
$table_deposit		= "¥100,000";				// 頭金
$table_repay_start	= "2020年1月";				// 返済開始予定年月
$table_repay_fin	= "2021年12月";				// 返済終了予定年月
$table_subtotal		= "¥685,398";				// 小計

/* 見積もり項目 */
$item_name		= array("ダイニングチェア", "ダイニングチェア");	// 適用
$item_num		= array("11111111", "4", "4", "4", "4", "4", "4", "4", "4", "4", "4", "4", "4", "4", "4", "4");		// 数量
$item_unit		= array("テーブル", "個");		// 単位
$item_price		= array("132,000", "13,200");		// 
$item_amount		= array("132,000", "52,800");		// 金額単価（税込）
$item_remark		= "お問い合わせ";			// 



/* ---------------------------------------------------------------------



	コンテンツ



--------------------------------------------------------------------- */
// PDFが1枚の場合
require_once(__DIR__ . "/single.php");

// PDFが2枚の場合
// require_once(__DIR__ . "/multiple.php");

// PDFをアウトプット
$pdf->Output(sprintf("MyResume_%s.pdf", time()), 'D');
?>