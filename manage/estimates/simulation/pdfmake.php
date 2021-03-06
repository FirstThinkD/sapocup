<?php
session_start();
require('../../../common/dbconnect.php');
require('../new/functions.php');

if ($_SESSION['loginID'] == "") {
	header("Location:/");
	exit();
}

if ($_GET['id'] == "") {
	header("Location:/");
	exit();
}

$week = ['日曜日',   // 0
	 '月曜日',   // 1
	 '火曜日',   // 2
	 '水曜日',   // 3
	 '木曜日',   // 4
	 '金曜日',   // 5
	 '土曜日',   // 6
];

$qu_id = $_GET['id'];

$sql = sprintf('SELECT * FROM `quotation` WHERE `qu_id`="%d"',
	mysqli_real_escape_string($db, $qu_id)
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row7 = mysqli_fetch_assoc($record);

$q_memo = memo_get($qu_id);

// $sql = sprintf('SELECT * FROM `q_memo` WHERE `qu_id`="%d"',
//	mysqli_real_escape_string($db, $qu_id)
// );
// $record = mysqli_query($db, $sql) or die(mysqli_error($db));
// $row8 = mysqli_fetch_assoc($record);

$sql = sprintf('SELECT * FROM `user` WHERE u_id="%d"',
	mysqli_real_escape_string($db, $row7['u_id'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row0 = mysqli_fetch_assoc($record);
if ($row0['u_type'] == "法人") {
	$user_name = $row0['u_company'];
} else {
	$user_name = $row0['p_name'];
}

$no_id = 10000000 + $row7['qu_id'];

$h_week = date("w", strtotime($row7['qu_startDate']));
$qu_paymentDate = date("Y年m月d日　$week[$h_week]", strtotime($row7['qu_startDate']));

$h_week = date("w", strtotime($row7['qu_deliveryDate']));
$qu_deliveryDate = date("Y年m月d日　$week[$h_week]", strtotime($row7['qu_deliveryDate']));

if (ctype_digit($row7['q_alltotal'])) {
	$q_alltotal = number_format($row7['q_alltotal']);
} else {
	$q_alltotal = $row7['q_alltotal'];
}

if (ctype_digit($row7['q_cost'])) {
	$q_cost = number_format($row7['q_cost']);
} else {
	$q_cost = $row7['q_cost'];
}

if (ctype_digit($row7['syohkei'])) {
	$syohkei = number_format($row7['syohkei']);
} else {
	$syohkei = $row7['syohkei'];
}

if (ctype_digit($row7['qu_amount_pay'])) {
	$qu_amount_pay = number_format($row7['qu_amount_pay']);
} else {
	$qu_amount_pay = $row7['qu_amount_pay'];
}

if (ctype_digit($row7['qu_commit'])) {
	$qu_commit = number_format($row7['qu_commit']);
} else {
	$qu_commit = $row7['qu_commit'];
}

if (ctype_digit($row7['qu_initPayAmount'])) {
	$qu_initPayAmount = number_format($row7['qu_initPayAmount']);
} else {
	$qu_initPayAmount = $row7['qu_initPayAmount'];
}

if (ctype_digit($row7['qu_deposit'])) {
	$qu_deposit = number_format($row7['qu_deposit']);
} else {
	$qu_deposit = $row7['qu_deposit'];
}

$qu_startDate = date("Y年m月", strtotime($row7['qu_startDate']));
$qu_endDate   = date("Y年m月", strtotime($row7['qu_endDate']));

$sql = sprintf('SELECT * FROM `q_items` WHERE qu_id="%d"',
	mysqli_real_escape_string($db, $qu_id)
);
$ix = 0;
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
while($row0 = mysqli_fetch_assoc($record)) {
	$q_name[$ix]   = $row0['q_name'];
	$q_number[$ix] = number_format($row0['q_number']);
	$q_unit[$ix]   = $row0['q_unit'];
	$q_price[$ix]  = number_format($row0['q_price']);
	$q_total[$ix]  = number_format($row0['q_total']);
	$ix++;
}
$q_cont = $ix;

$json_name   = json_encode($q_name);
$json_number = json_encode($q_number);
$json_unit   = json_encode($q_unit);
$json_price  = json_encode($q_price);
$json_total  = json_encode($q_total);

// $qu_memo = str_replace(PHP_EOL, '', $row8['q_memo']);

$today = date('Y年m月d日');

// echo '<script type="text/javascript">pdfMake.createPdf(docDefinition).open();</script>';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <!-- <title>PDF作成サンプル</title> -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="/common/js/pdfmake.min.js"></script>
  <script src="/common/js/vfs_fonts.js"></script>
</head>
<body>
  <script>
    //日本語フォント読み込み
    pdfMake.fonts = {
      IPAex: {
        normal:      'ipaexm.ttf',
        bold:        'AozoraMincho-bold.ttf',
        italics:     'ipaexm.ttf',
        bolditalics: 'ipaexm.ttf'
      }
    };

    /*--------------------------------------------------------------------------

      変数一覧　開始

    --------------------------------------------------------------------------*/
    let pdf_number    = "<?php echo $no_id; ?>";               // ナンバー
    let pdf_date      = "<?php echo $today; ?>";               //日付
    let pdf_billto    = "<?php echo $user_name; ?>";           // 請求先
    let pdf_construct = "<?php echo $row7['qu_name']; ?>";     // 請求先
    let pdf_company   = "<?php echo $row7['in_companyName']; ?>"; // 会社名
    let pdf_post      = "<?php echo $row7['in_postal']; ?>";
    let pdf_address1  = "<?php echo $row7['in_address1']; ?>"; // 住所1
    let pdf_address2  = "<?php echo $row7['in_address2']; ?>"; // 住所2
    let pdf_address3  = "<?php echo $row7['in_address3']; ?>"; // 住所3
    let pdf_tel       = "<?php echo $row7['in_tel']; ?>";      // 電話番号
    let pdf_name      = "<?php echo $row7['in_contactName']; ?>"; // 名前
    let pdf_place     = "<?php echo $row7['qu_location']; ?>"; // 工事場所
    let pdf_payment   = "<?php echo $qu_paymentDate; ?>";      // 支払い日
    let pdf_delivery  = "<?php echo $qu_deliveryDate; ?>";     // 受渡期日

    let pdf_estimate_constructionprice = "<?php echo $syohkei; ?>";     // 工事価格
    let pdf_estimate_totalprice   = "<?php echo $q_alltotal; ?>";       // 請求金額合計
    let pdf_estimate_tax          = "<?php echo $q_cost; ?>";           // 消費税
    let pdf_estimate_installment  = "<?php echo $qu_commit; ?>";        // 割賦手数料
    let pdf_estimate_firstpayment = "<?php echo $qu_initPayAmount; ?>"; // 初回お支払額
    let pdf_estimate_montylypayment = "<?php echo $qu_amount_pay; ?>";  // 月々お支払額
    let pdf_estimate_split   = "<?php echo $row7['qu_installments']; ?>"; // 分割回数
    let pdf_estimate_deposit = "<?php echo $qu_deposit; ?>";            // 頭金
    let pdf_estimate_start   = "<?php echo $qu_startDate; ?>";          // 返済開始予定年月
    let pdf_estimate_fin     = "<?php echo $qu_endDate; ?>";            // 返済終了予定年月

    let pdf_item_name   = <?php echo $json_name; ?>;   // 項目名前
    let pdf_item_num    = <?php echo $json_number; ?>; // 数量
    let pdf_item_unit   = <?php echo $json_unit; ?>;   // 単位
    let pdf_item_price  = <?php echo $json_price; ?>;  // 単価(税込)
    let pdf_item_amount = <?php echo $json_price; ?>;  // 金額

    let pdf_item_shokei = "<?php echo $syohkei; ?>";   // 小計
    let pdf_item_remarks = "<?php echo $q_memo; ?>";   // 備考
    /*--------------------------------------------------------------------------
      変数一覧　終了
    --------------------------------------------------------------------------*/

    var docDefinition = {
      pageMargins: [40, 25, 40, 25],
    /*--------------------------------------------------------------------------
      PDF本文　開始
    --------------------------------------------------------------------------*/
      content: [
        {
          table: {
            widths: ['80%', '20%'],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "No.　" + pdf_number, style: 'pdf_number_css', border: [false, false, false, true],},
            ]]
          },
        },

        // タイトル
        {
          text: [
            { text: '見積書', style: 'pdf_title_css' }
          ]
        },

        // 日付
        {
          text: [
            { text: pdf_date, style: 'pdf_date_css' }
          ]
        },

        // 請求先名前
        {
          table: {
          lineHeight: 1.2,
            widths: [200, 'auto'],
            body:[[
              { text: pdf_billto, style: 'pdf_billto_css', border: [false, false, false, true],},
              { text: "様", style: 'pdf_billto_css', border: [false, false, false, false],},
            ]]
          },
        },

        // 請求先情報
        {
          margin: [0, 0, 0, 5],
          table: {
            widths: ['50%', '20%', '30%'],
            body:[[
              { text: '下記の通りご請求申し上げます。\n\n工事名称\t\t' + pdf_construct + '\n\n\n工事場所\n' + pdf_place, style: 'pdf_billdetail_css', border: [false, false, false, false],},
              { text: '', border: [false, false, false, false],},
              { text: pdf_company + '\n〒' + pdf_post + '\n' + pdf_address1 + '\n' + pdf_address2 + '\n' + pdf_address3 + '\nTel:\t' + pdf_tel + '\n' + pdf_name, style: 'pdf_company_css', border: [false, false, false, false],},
            ]]
          },
        },

        // 請求先情報
        {
          margin: [0, 0, 0, 5],
          lineHeight: 1.2,
          table: {
            widths: [60, "*"],
            body:[[
              { text: "支払条件\n支払日\n受渡期日\n見積有効期限", style: 'pdf_conditions_css', border: [false, false, false, false],},
              { text: "分割支払\n" + pdf_payment + "\n" + pdf_delivery + "\n発行日より10日間", border: [false, false, false, false],},
            ]]
          },
        },

        // 見積書情報
        {
          table: {
            widths: [21, 96, 168, 90, 69, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "請求金額合計", style: 'pdf_estimate_title_totalprice_css', border: [false, false, false, true],},
              { text: "¥" + pdf_estimate_totalprice, style: 'pdf_estimate_totalprice_css', border: [false, false, false, true],},
              { text: "（内消費税等/10%)", style: 'pdf_estimate_title_tax_css', border: [false, false, false, false],},
              { text: "¥" + pdf_estimate_tax, style: 'pdf_estimate_tax_css', border: [false, false, false, false],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },

        // 見積書情報
        {
          table: {
            widths: [21, '*'],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "《分割払い内容》", border: [false, false, false, false],},
            ]]
          },
        },

        // 見積書テーブル
        {
          margin: [0, 0, 0, 10],
          table: {
            widths: [21, 84, 125, 84, 125, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "工事価格（税込）", style: 'tableTitle', border: [true, true, true, true],},
              { text: "¥" + pdf_estimate_constructionprice, style: 'tableContentRight', border: [true, true, true, true],},
              { text: "割賦手数料", style: 'tableTitle', border: [true, true, true, true],},
              { text: "¥" + pdf_estimate_installment, style: 'tableContentRight', border: [true, true, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 84, 125, 84, 125, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "初回お支払額", style: 'tableTitle', border: [true, true, true, true],},
              { text: "¥" + pdf_estimate_firstpayment, style: 'tableContentRight', border: [true, true, true, true],},
              { text: "月々お支払額", style: 'tableTitle', border: [true, true, true, true],},
              { text: "¥" + pdf_estimate_montylypayment, style: 'tableContentRight', border: [true, true, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 84, 125, 84, 125, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "分割回数", style: 'tableTitle', border: [true, false, true, true],},
              { text: pdf_estimate_split + "回払い", style: 'tableContentCenter', border: [true, false, true, true],},
              { text: "頭金", style: 'tableTitle', border: [true, false, true, true],},
              { text: "¥" + pdf_estimate_deposit, style: 'tableContentRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          margin: [0, 0, 0, 10],
          table: {
            widths: [21, 84, 125, 84, 125, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "返済開始予定年月", style: 'tableTitle', border: [true, false, true, true],},
              { text: pdf_estimate_start, style: 'tableContentCenter', border: [true, false, true, true],},
              { text: "返済終了予定年月", style: 'tableTitle', border: [true, false, true, true],},
              { text: pdf_estimate_fin, style: 'tableContentCenter', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },

        // テーブル項目
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "No", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "適用", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "数量", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "単位", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "単価(税込)", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "金額", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "1", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[0], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[0], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[0], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[0], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[0], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "2", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[1], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[1], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[1], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[1], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[1], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "3", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[2], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[2], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[2], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[2], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[2], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "4", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[3], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[3], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[3], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[3], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[3], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "5", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[4], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[4], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[4], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[4], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[4], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "6", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[5], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[5], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[5], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[5], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[5], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "7", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[6], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[6], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[6], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[6], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[6], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "8", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[7], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[7], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[7], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[7], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[7], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "9", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[8], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[8], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[8], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[8], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[8], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "10", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[9], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[9], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[9], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[9], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[9], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },

      /*---------------------------------------
        2枚目
      ---------------------------------------*/
        {
          margin: [0, 30, 0, 0],
          table: {
            widths: ['80%', '20%'],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "No. 　" + pdf_number, style: 'pdf_number_css', border: [false, false, false, true],},
            ]]
          },
        },

        {
          margin: [0, 20, 0, 30],
          text: [
            { text: pdf_date, style: 'pdf_date_css' }
          ]
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "No", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "適用", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "数量", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "単位", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "単価(税込)", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "金額", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "11", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[10], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[10], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[10], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[10], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[10], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "12", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[11], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[11], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[11], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[11], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[11], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "13", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[12], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[12], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[12], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[12], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[12], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "14", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[13], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[13], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[13], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[13], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[13], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "15", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[14], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[14], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[14], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[14], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[14], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "16", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[15], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[15], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[15], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[15], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[15], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "17", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[16], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[16], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[16], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[16], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[16], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "18", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[17], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[17], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[17], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[17], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[17], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "19", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[18], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[18], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[18], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[18], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[18], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "20", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[19], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[19], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[19], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[19], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[19], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "21", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[20], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[20], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[20], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[20], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[20], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "22", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[21], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[21], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[21], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[21], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[21], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "23", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[22], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[22], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[22], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[22], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[22], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "24", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[23], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[23], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[23], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[23], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[23], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "25", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[24], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[24], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[24], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[24], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[24], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "26", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[25], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[25], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[25], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[25], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[25], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "27", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[26], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[26], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[26], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[26], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[26], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "28", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[27], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[27], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[27], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[27], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[27], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "29", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[28], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[28], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[28], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[28], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[28], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "30", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[29], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[29], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[29], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[29], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[29], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },

      /* -------------------------------------

        3枚目

      --------------------------------------- */
        {
          margin: [0, 30, 0, 0],
          table: {
            widths: ['80%', '20%'],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "No.　" + pdf_number, style: 'pdf_number_css', border: [false, false, false, true],},
            ]]
          },
        },

        {
          margin: [0, 20, 0, 30],
          text: [
            { text: pdf_date, style: 'pdf_date_css' }
          ]
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "No", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "適用", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "数量", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "単位", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "単価(税込)", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "金額", style: 'tableItemTop', border: [true, true, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "31", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[30], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[30], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[30], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[30], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[30], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "32", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[31], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[31], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[31], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[31], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[31], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "33", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[32], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[32], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[32], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[32], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[32], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "34", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[33], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[33], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[33], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[33], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[33], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "35", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[34], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[34], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[34], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[34], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[34], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "36", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[35], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[35], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[35], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[35], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[35], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "37", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[36], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[36], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[36], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[36], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[36], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "38", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[37], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[37], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[37], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[37], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[37], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "39", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[38], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[38], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[38], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[38], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[38], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "40", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[39], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[39], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[39], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[39], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[39], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "41", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[40], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[40], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[40], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[40], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[40], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "42", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[41], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[41], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[41], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[41], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[41], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "43", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[42], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[42], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[42], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[42], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[42], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "44", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[43], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[43], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[43], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[43], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[43], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "45", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[44], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[44], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[44], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[44], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[44], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "46", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[45], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[45], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[45], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[45], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[45], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "47", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[46], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[46], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[46], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[46], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[46], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "48", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[47], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[47], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[47], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[47], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[47], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "49", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[48], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[48], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[48], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[48], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[48], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 22, 187, 24.5, 24.5, 71, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "50", style: 'tableItemNo', border: [true, false, true, true],},
              { text: pdf_item_name[49], style: 'tableItemLeft', border: [true, false, true, true],},
              { text: pdf_item_num[49], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_unit[49], style: 'tableItemCenter', border: [true, false, true, true],},
              { text: pdf_item_price[49], style: 'tableItemRight', border: [true, false, true, true],},
              { text: pdf_item_amount[49], style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [21, 218, 138, 71, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "", border: [true, false, true, true],},
              { text: "小計", style: 'tableItemCenter', border: [true, false, true, true],},
              { text: "¥" + pdf_item_shokei, style: 'tableItemRight', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          margin: [0, 0, 0, 10],
          table: {
            widths: [21, 445, 21],
            body:[[
              { text: "", border: [false, false, false, false],},
              { text: "【備考】\n" + pdf_item_remarks, style: 'pdf_item_remarks_css', border: [true, false, true, true],},
              { text: "", border: [false, false, false, false],},
            ]]
          },
        },
        {
          table: {
            widths: [260, 220, 10],
            body:[[
              { text: "上記内容及び裏面「割賦販売契約約款」を確認し、説明を受けて、\n支払い方法や金額及び自らの支払い能力等を十分に検討したうえで、\n上記の条件で" + pdf_company +"に対して工事の発注します。\nなお、" + pdf_company + "が当核工事を一括して他の建設業者に請け負せる\nことができるにつき、承諾します。", style: "noteSub",border: [false, false, false, false],},
              { text: "", style: "signatureSeal", border: [false, false, false, true],},
              { text: "㊞", style: "signatureSeal", border: [false, false, false, true],},
            ]]
          },
        },

    /*--------------------------------------------------------------------------
      PDF本文　終了
    --------------------------------------------------------------------------*/

    /*--------------------------------------------------------------------------
      style.css　開始
    --------------------------------------------------------------------------*/
      styles: {
        // テキスト左
        textLeft: {
          alignment: 'left',
        },

        // テキスト左
        textRight: {
          alignment: 'right',
        },

        // テキスト中央
        textCenter: {
          alignment: 'center',
        },

        // No.
        pdf_number_css: {
        },

        // タイトル
        pdf_title_css: {
          fontSize: 18,
          alignment: 'center',
        },

        // 日付
        pdf_date_css: {
          alignment: 'right',
        },

        // 請求先名前
        pdf_billto_css: {
          fontSize: 12,
        },
        // 請求先情報
        pdf_billdetail_css: {
        },

        // 請求先様
        pdf_company_css: {
          lineHeight: 1.2,
        },

        // 請求金額合計タイトル
        pdf_estimate_title_totalprice_css: {
          margin: [0, 10, 0, 0],
          bold: true,
        },

        // 請求金額合計
        pdf_estimate_totalprice_css: {
          fontSize: 20,
          lineHeight: 1,
          alignment: 'right',
          bold: true,
        },
        // 消費税タイトル
        pdf_estimate_title_tax_css: {
          fontSize: 8,
          margin: [0, 15, 0, 0],
        },

        // 消費税
        pdf_estimate_tax_css: {
          decoration: 'underline',
          alignment: 'right',
          margin: [0, 15, 0, 0],
          bold: true,
        },

        // テーブルタイトル
        tableTitle: {
          margin: [0, 6],
        },

        // 工事価格（税込）
        tableContentRight: {
          margin: [0, 6],
          alignment: 'right',
          bold: true,
        },

        // 割賦手数料
        tableContentCenter: {
          margin: [0, 6],
          alignment: 'center',
          bold: true,
        },

        // 項目テーブルトップ
        tableItemTop: {
          alignment: 'center',
        },

        tableItemNo: {
          margin: [0, 6, 0, 6],
          alignment: 'center',
        },
        tableItemLeft: {
          alignment: 'left',
          lineHeight: 1.1,
        },
        tableItemCenter: {
          margin: [0, 10, 0, 0],
          alignment: 'center',
        },
        tableItemRight: {
          margin: [0, 10, 0, 0],
          alignment: 'right',
        },
        pdf_item_remarks_css: {
          lineHeight: 5,
        },
        // 注意書き
        noteSub: {
          fontSize: 8,
        },
        signatureSeal: {
          margin: [0, 20, 0, 0],
        },
        // 裏面の文字
        backText: {
          bold: true,
          margin: [0, 6, 0, 0],
        },
        coolingOff: {
          color: "red",
        },
      },
      defaultStyle: {
        font: 'IPAex',
        black: true,
        lineHeight: 1,
        fontSize: 9,
      }
    /*--------------------------------------------------------------------------
      style.css　終了
    --------------------------------------------------------------------------*/
    };

    console.dir(docDefinition);  // Array:78(0-77)
    // console.dir(docDefinition.content);
    // console.dir(docDefinition.content[12]);  // テーブル名
    // console.dir(docDefinition.content[13]);  // table:1  table:10[22]  契約約款[23] - [26]
    // テーブル名[27]  table:11[28]  table:30[47]  契約約款[48] - [51]
    // テーブル名[52]  table:31[53]  table:50[72]
    // 小計[73] - [77]
    // console.dir(docDefinition.content[13].table.body[0]);
    // console.dir(docDefinition.content[13].table.body[0][1].text); // tableItemNo
    // console.dir(docDefinition.content[13].table.body[0][2].text); // pdf_item_name
    // console.dir(docDefinition.content[13].table.body[0][3].text); // pdf_item_num
    // console.dir(docDefinition.content[13].table.body[0][4].text); // pdf_item_unit
    // console.dir(docDefinition.content[13].table.body[0][5].text); // pdf_item_price
    // console.dir(docDefinition.content[13].table.body[0][6].text); // pdf_item_amount

    var q_cont = <?php echo $q_cont; ?>;

    if (q_cont < 11) {
        docDefinition.content.splice(23, 50);
    } else if (q_cont < 31) {
        docDefinition.content.splice(48, 25);
    }
    // console.log('docDefinition:' + docDefinition);

    function pdfmake() {
        return new Promise(function(resolve, reject) {
            setTimeout(function() {
                resolve(pdfMake.createPdf(docDefinition).open());
            }, 500);
        });
    }

    pdfmake().then(function(result) {
        // alert(result);
        window.close();
        return result;
    }).then(function(result) {
        alert('エラー1！失敗しました');
        window.close();
        return result;
    }).catch(function (error) {
        alert(error);
        window.close();
    });

    // alert('エラー4！失敗しました', error);

    // var result = new Promise(pdfMake.createPdf(docDefinition).open());
//    var result = pdfMake.createPdf(docDefinition).open();
    // window.close();
//    if ( result ) {
      // alert('OK-result:' + result);
//      setTimeout(windows.close(), 10000);
//    } else {
      // window.close();
//      setTimeout(windows.close(), 20000);
//    }
  </script>
</body>
</html>