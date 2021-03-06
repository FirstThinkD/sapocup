    //日本語フォント読み込み
    pdfMake.fonts = {
      IPAex: {
        normal:      'ipaexm.ttf',
        bold:        'AozoraMincho-bold.ttf',
        italics:     'ipaexm.ttf',
        bolditalics: 'ipaexm.ttf'
      }
    };

    /*　変数一覧　開始　---------------------------------------*/
    let pdf_number = "10001";　// ナンバー
    let pdf_date = "2020年3月20日";　//日付
    let pdf_billto = "株式会社あああ";　// 請求先
    let pdf_construct= "自宅リフォーム";　// 請求先
    let pdf_company = "Atkid";　// 会社名
    let pdf_post = "192-0363";
    let pdf_address1 = "東京都東京市東京町";　// 住所1
    let pdf_address2 = "111-1111-111";　// 住所2
    let pdf_address3 = "ヤマダビル";　// 住所3
    let pdf_tel = "080-1292-0000";　// 電話番号
    let pdf_name = "中澤　寛"; // 名前
    let pdf_place = "自宅リフォーム";　// 工事場所
    let pdf_payment = "2020年1月31日　金曜日"; // 支払い日
    let pdf_delivery = "2021年12月31日　金曜日"; // 支払い日

    let pdf_estimate_constructionprice = "685,398"; // 工事価格
    let pdf_estimate_totalprice = "719,667";　// 請求金額合計
    let pdf_estimate_tax = "68,539";　// 消費税
    let pdf_estimate_installment = "34,269";　// 割賦手数料
    let pdf_estimate_firstpayment = "125,830";　// 初回お支払額
    let pdf_estimate_montylypayment = "25,819";　// 月々お支払額
    let pdf_estimate_split = "24";　// 分割回数
    let pdf_estimate_deposit = "100,000";　// 頭金
    let pdf_estimate_start = "2020年1月";　// 返済開始予定年月
    let pdf_estimate_fin = "2021年12月";　// 返済終了予定年月

    let pdf_item_name = [
      "玄関扉 交換玄関扉 交換玄関扉 交換玄関扉 交換玄関扉 交換玄関扉 交換玄関扉 交換",
      "玄関扉 交換玄関扉 交換玄関扉 交換玄関扉 交換玄関扉 交換玄関扉 交換",
    ]; // 項目名前
    let pdf_item_num = [
      "4",
    ]; // 数量
    let pdf_item_unit = [
      "個"
    ]; // 単位
    let pdf_item_price = [
      "122,472",
    ]; // 単価(税込)
    let pdf_item_amount = [
      "489,888",
    ]; // 金額

    let pdf_item_shokei = "685,398"; // 小計
    let pdf_item_remarks = ""; // 備考

    /*　変数一覧　終了　---------------------------------------*/


    var docDefinition = {
      pageMargins: [40, 25, 40, 25],
    /*　PDF本文　開始　---------------------------------------*/
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
            widths: [200,　"auto"],
            body:[[
              { text: pdf_billto, style: 'pdf_billto_css', border: [false, false, false, true],},
              { text: "様", style: 'pdf_billto_css', border: [false, false, false, false],},
            ]]
          },
        },
        // 請求先情報
        {
          margin: [0, 0, 0, 10],
          table: {
            widths: ['50%',　'20%', '30%'],
            body:[[
              { text: "下記の通りご請求申し上げます。\n\n工事名称\t\t" + pdf_construct + "\n\n\n工事場所\n" + pdf_place, style: 'pdf_billdetail_css', border: [false, false, false, false],},
              { text: "", border: [false, false, false, false],},
              { text: pdf_company + '\n〒' + pdf_post + '\n' + pdf_address1 + '\n' + pdf_address2 + '\n' + pdf_address3 + '\nTel:\t' + pdf_tel + '\n' + pdf_name, style: 'pdf_company_css', border: [false, false, false, false],},
            ]]
          },
        },
        // 請求先情報
        {
          margin: [0, 0, 0, 10],
          lineHeight: 1.2,
          table: {
            widths: ['10%',　'90%'],
            body:[[
              { text: "支払条件\n支払日\n受渡期日", style: 'pdf_conditions_css', border: [false, false, false, false],},
              { text: "分割支払\n" + pdf_payment + "\n" + pdf_delivery, border: [false, false, false, false],},
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
            widths: ['5%', '95%'],
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
              { text: "【備考】\n" + pdf_item_remarks, style: "pdf_item_remarks_css", border: [true, false, true, true],},
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

        // 裏面
        {
          text: "割賦販売契約約款", bold: true, margin: 5,
        },
        {
          layout: 'noBorders',
          margin: [5, 0, 5, 0],
          fontSize: 7,
          lineHeight: 1.4,
          table: {
            widths: ["*", 30, "*"],
            body: [[
              [
                { text: "第１条（約款の適用および契約内容）", style: "backText"},
                { text: pdf_billto + "（以下「甲」といいます）と御請求書に記載の発注者（以下「乙」といいます）は、甲の工事または商品（以下「本商品」といいます）の発注に関し、以下の割賦販売契約約款（以下「本約款」といいます）に従うものとし、本商品の割賦販売に関する「割賦販売契約」（以下「本契約」といいます）を締結します。" },
                { text: "第２条（本契約の申込方法および承諾等）", style: "backText"},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "1."},
                  { width: "auto", text: "乙は、本契約の申込み（以下「本申込」という）をするときは本約款には、本約款に同意のうえ、甲所定の申込手続を行うものとします。"},
                ],},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "2."},
                  { width: "auto", text: "前項の場合において、乙は、甲が申込内容を確認するための書類が必要と判断する場合、当該書類を提出するものとします。"},
                ],},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, heavy: true, text: "3."},
                  { width: "auto", text: "甲は、次の場合には本契約の申込みを承諾しないことがあります。"},
                ],},
                { margin: [ 10, 0, 0, 0],
                  columns: [
                  { width: 15, thin: true,text: "(1)"},
                  { width: "auto", text: "乙が甲との間で締結している本契約に基づく各月の支払い総額が甲が定める基準を満たすことができないおそれがあるとき。"},
                ],},
                { margin: [ 10, 0, 0, 0],
                  columns: [
                  { width: 15, text: "(2)"},
                  { width: "auto", text: "甲の業務遂行上支障があるとき。"},
                ],},
                { margin: [ 10, 0, 0, 0],
                  columns: [
                  { width: 15, text: "(3)"},
                  { width: "auto", text: "その他甲が不適当と判断したとき。"},
                ],},
                { text: "第３条（契約の成立時点）", style: "backText"},
                { text: "本契約は、甲が乙からの申込みを所定の手続きをもって承諾し、所定の方法で承諾の通知を受けた時をもって成立するものとします。" },
                { text: "第４条（分割払金の支払期日および支払方法）", style: "backText"},
                { text: "乙は、甲が契約後に交付または送付（電子メールによる送信を含みます）する書面（電磁的に交付するものを含む。以下「契約完了書面」といいます）に記載の分割払金を、契約完了書面に記載の支払期日から、支払うものとします。" },
                { text: "第５条（入金案内）", style: "backText"},
                { text: "利用者は、本オプション機能を利用する前に、顧客に対し、分割金の未払いが起こった場合には弊社より「SMS」「自動音声」「オペレーター」案内のいずれかより連絡がいく場合がある旨を十分に説明の上で同意させるものとし、弊社は当該同意がなされているものとみなすことができるものとします。万一、顧客から弊社に対し、個人情報の流用の観点で指摘があった場合であっても、弊社は利用者から顧客に対して事前に説明を行い同意を得ているものとみなし、その責任はすべて利用者が負うものとし、弊社は一切責任を負わないものとします。" },
                { text: "第６条（商品引渡し及び所有権の移転）", style: "backText"},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "1."},
                  { width: "auto", text: "甲は、本契約が成立した後、所定の時期に本商品を購入者に引渡すものとします。"},
                ],},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "2."},
                  { width: "auto", text: "本商品の所有権は、本商品の支払いが完了した際に、乙へ所有権移転するものとします。"},
                ],},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "3."},
                  { width: "auto", text: "乙は、本商品の所有権移転前においては、本商品を担保に供し、譲渡し、するまたは転売することができないものとします。"},
                ],},
                { text: "第７条（届出事項の変更）", style: "backText", },
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "1."},
                  { width: "auto", text: "乙は、甲に届け出た氏名もしくは名称、住所または連絡先等を変更した場合は、速やかに甲所定の方法により甲に届け出るものとします。"},
                ],},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "2."},
                  { width: "auto", text: "乙は、前項の届出がないために、甲等からの通知または送付書類等が延着または不到達となった場合には、通常到達すべき時に到達したものと甲等がみなすことに異議のないものとします。"},
                ],},
                { text: "第８条（契約上の地位の譲渡）", style: "backText"},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "1."},
                  { width: "auto", text: "乙は、本契約に係る契約上の地位を譲渡することができないものとします。"},
                ],},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "2."},
                  { width: "auto", text: "前項の定めは、相続または法人の合併、分割等により本契約に係る契約上の地位が承継される場合には適用しないものとします。"},
                ],},



                { text: "第９条（期限の利益喪失）", style: "backText"},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "1."},
                  { width: "auto", text: "乙は、次のいずれかの事由に該当したときは、当然に本契約に基づく債務について期限の利益を失い、直ちに債務の全てを履行するものとします。"},
                ],},
                { margin: [ 10, 0, 0, 0],
                  columns: [
                  { width: 15, text: "(1)"},
                  { width: "auto", text: "分割払金の支払を遅滞し、その支払を書面で20日以上の相当の期間を定めて催告されたにもかかわらず、その期間内に支払わなかったとき。"},
                ],},
                { margin: [ 10, 0, 0, 0],
                  columns: [
                  { width: 15, text: "(2)"},
                  { width: "auto", text: "自ら振り出した手形、小切手が不渡りになったとき、または一般の支払を停止したとき。"},
                ],},
                { margin: [ 10, 0, 0, 0],
                  columns: [
                  { width: 15, text: "(3)"},
                  { width: "auto", text: "差押、仮差押、保全差押、仮処分の申し立てまたは滞納処分を受けたとき。"},
                ],},
                { margin: [ 10, 0, 0, 0],
                  columns: [
                  { width: 15, text: "(4)"},
                  { width: "auto", text: "破産、民事再生、特別清算、会社更生その他裁判上の倒産処理手続の申し立てを受けたとき、または自らこれらの申し立てをしたとき。"},
                ],},
                { margin: [ 10, 0, 0, 0],
                  columns: [
                  { width: 15, text: "(5)"},
                  { width: "auto", text: "その他乙の信用状態が著しく悪化したとき。"},
                ],},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "2."},
                  { width: "auto", text: "甲は、乙が前項のいずれかに該当する場合は、本契約を解除することができるものとします。"},
                ],},
                { text: "第１０条（損害遅延金）", style: "backText"},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "1."},
                  { width: "auto", text: "乙は、分割代金を遅滞したときは、支払期日の翌日から支払日にいたるまで、当該分割払金に対し年6.00％を上限とした額の遅延損害金を支払うものとします。"},
                ],},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "2."},
                  { width: "auto", text: "乙は、本契約に基づく債務について期限の利益を喪失したときは、期限の利益喪失の日から完済の日に至るまで、本商品の割賦販売価格から既に支払われた分割代金の合計額を控除した残金に対し、年6.00％を上限とした額の遅延損害金を支払うものとします。"},
                ],},
                { text: "第１1条（手数料の負担等）", style: "backText"},
                { text: "乙は、分割代金の支払に関する手数料を負担するものとします。"},
              ],
              { text: ""},
              [
                { text: "第１２条（早期一括返済）", style: "backText"},
                { text: "乙は、甲等に申し出ることにより、分割払金の残額を一括して支払うことができるものとします。"},
                { text: "第１３条（反社会的勢力との関係の遮断）", style: "backText"},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "1."},
                  { width: "auto", text: "乙は本契約締結日において、暴力団、暴力団員、暴力団員でなくなった時から５年を経過しない者、暴力団準構成員、暴力団関係企業、総会屋等、社会運動等標ぼうゴロ又は特殊知能暴力集団等、これらの共生者、その他これらに準ずる者、テロリスト（疑いがある場合も含む）に該当しないことを表明し、かつ将来にわたっても該当しないことを確約します。"},
                ],},
                { margin: [ 5, 0, 0, 0],
                  columns: [
                  { width: 10, text: "2."},
                  { width: "auto", text: "乙が第１項各号に該当した場合、またはは第１項の規定に基づく確約に関して虚偽の申告をしたことが判明した場合、甲は直ちに本契約を解除することができ、且つ、甲に生じた損害の賠償を請求できるものとします。"},
                ],},
                { text: "第１４条（割賦債権の譲渡）", style: "backText"},
                { text: "甲は、乙に対する本契約に基づく債権を第三者に譲渡することがあります。この場合において、ご契約者様は、当該債権の譲渡及び甲が購入者の個人情報を譲渡先に提供することをあらかじめ同意するものとします。"},
                { text: "第１５条（個人情報の取扱い）", style: "backText"},
                { text: "甲は乙に関する個人情報の取扱いに関するプライバシーポリシーを定め、これを甲のホームページ等において公表するか、甲の求めに応じて遅滞なく回答します。"},
                { text: "第１６条（合意管轄裁判所）", style: "backText"},
                { text: "乙は、本商品、本約款及び本契約について紛争が生じた場合、訴額の如何にかかわらず、甲の本店所在地を管轄する地方裁判所を第一審の専属的合意管轄裁判所とすることに同意するものとします。"},
                { text: "第１７条（補則）", style: "backText"},
                { text: "この約款に定めなき事項が生じた場合、甲と乙は契約の趣旨に従い、誠意を持って協議・解決に努めるものとします。", margin: [0, 0, 0, 10]},
                { text: "クーリングオフについて（説明書）", style: "coolingOff"},
                { text: "ご契約いただきます工事または商品等の販売につきましては、この割賦販売契約約款及びクーリングオフについての説明書の内容を十分にご確認ください。", style: "coolingOff"},
                { text: "この割賦販売契約に「特定商品取引に関する法律」が適用される場合、お客様はこの説明書面受領日から起算して８日以内は、書面をもって契約の解除（クーリングオフと呼びます）をすることができ、その効力はお客様が解除する旨の書面を甲に対して発したときに生じるものとします。", style: "coolingOff"},
                { text: "ただし、次のような場合等にはクーリングオフの権利行使はできません。", style: "coolingOff"},
                { margin: [ 0, 0, 0, 0],
                  columns: [
                  { width: 8, text: "・", style: "coolingOff"},
                  { width: "auto", text: "お客様が工事や商品等を営業用に利用する場合", style: "coolingOff"},
                ],},
                { margin: [ 0, 0, 0, 0],
                  columns: [
                  { width: 8, text: "・", style: "coolingOff"},
                  { width: "auto", text: "甲の営業所等で契約の申込みまたは契約締結がなされた場合", style: "coolingOff"},
                ],},
                { margin: [ 0, 0, 0, 0],
                  columns: [
                  { width: 8, text: "・", style: "coolingOff"},
                  { width: "auto", text: "お客様からのご請求によりご自宅での契約の申込みまたは契約締結がなされた場合", style: "coolingOff"},
                ],},
                { text: "上記期間内にクーリングオフがあった場合、甲は契約の解除に伴う損害賠償または違約金支払を請求することはありません。万一、クーリングオフがあった場合に、既に商品等の引渡しが行われている時は、その引取りに要する費用は甲の負担とします。また、契約解除のお申し出の際に既に受領した金員がある場合、すみやかにその金額を無利息にて返還いたします。", style: "coolingOff"},
              ],
            ]]
          },
        },
      ],
    /*　PDF本文　終了　---------------------------------------*/

    /*　style.css　開始　---------------------------------------*/
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
    /*　style.css　終了　---------------------------------------*/

    };
    pdfMake.createPdf(docDefinition).open();
