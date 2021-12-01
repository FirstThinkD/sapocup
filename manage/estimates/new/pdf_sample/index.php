<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<title></title>
	<link rel="stylesheet" type="text/css" href="/manage/common/css/common.css">
	<link rel="stylesheet" type="text/css" href="/manage/common/css/main.css">
</head>
<body class="white">
	<main>
		<div class="main_content input_area pdf_demo">
			<div class="container">
				<div class="main_content_inner">
					<div class="pdf_fields sample">
						<div class="pdf_fields_total">
							<div class="pdf_total_wrap">
								<div class="pdf_top_text">
									<div class="textTitle">
										<p>No.</p>
										<p>見積書</p>
										<p>○○○○○○○○ 様</p>
										<p>2020年3月15日</p>
										<p>工事名称　○○○○○○</p>
										<p>工事場所<br>○○○○○○○○○○○○</p>
										<p>支払い条件　分割払い</p>
										<p>支払い日　○○○○</p>
										<p>受渡期日　○○○○</p>
										<p>見積有効期限　発行日より10日間</p>
									</div>
									<div class="textTime">
										<p>○○○○○○○○○○株式会社</p>
										<p>〒 ○○○-○○○○</p>
										<p>○○○○（住所）</p>
										<p>TEL：○○-○○○○-○○○○</p>
										<p>○○○○○○○○（名前）</p>
									</div>
								</div>
								<div class="pdf_total_top cf">
									<div class="top_total_money">
										<div class="top_total_money_inner">
											<p>見積もり金額</p>
											<input type="" name="">
										</div>
									</div>
									<div class="top_total_tax">
										<div class="top_total_tax_inner">
											<p>（内消費税等 / 10%）<input type="text"></p>
										</div>
									</div>
								</div>
								<div class="pdf_total_bottom">
									<p>《分割払い内容》</p>
									<div class="pdf_total_bottom_box cf">
										<div class="pdf_item cf">
											<div class="item_title">価格（税込）</div>
											<div class="item_number"><input type="" name=""></div>
										</div>
										<div class="pdf_item CF">
											<div class="item_title">事務管理手数料</div>
											<div class="item_number"><input type="" name=""></div>
										</div>
									</div>
									<div class="pdf_total_bottom_box cf">
										<div class="pdf_item CF">
											<div class="item_title">初回お支払額</div>
											<div class="item_number"><input type="" name=""></div>
										</div>
										<div class="pdf_item CF">
											<div class="item_title">月々お支払額</div>
											<div class="item_number"><input type="" name=""></div>
										</div>
										<div class="pdf_item CF">
											<div class="item_title">分割回数</div>
											<div class="item_number text-center">
												<select name="">
													<option value=""></option>
												</select>
												回払い
											</div>
										</div>
										<div class="pdf_item CF">
											<div class="item_title">頭金</div>
											<div class="item_number"><input type="" name=""></div>
										</div>
										<div class="pdf_item CF">
											<div class="item_title">返済開始予定年月</div>
											<div class="item_number"><input type="" name=""></div>
										</div>
										<div class="pdf_item CF">
											<div class="item_title">返済終了予定年月</div>
											<div class="item_number"><input type="" name=""></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<table>
							<thead>
								<tr>
									<th>No.</th>
									<th>適用</th>
									<th>数量</th>
									<th>単位</th>
									<th>単価(税込)</th>
									<th>金額</th>
								</tr>
							</thead>
							<tbody>
								<tr class="pdf_input">
									<td><input type="" name=""></td>
									<td><input type="" name=""></td>
									<td><input type="" name=""></td>
									<td><input type="" name=""></td>
									<td><input type="" name=""></td>
									<td class="totalPrice"><input type="" name=""></td>
								</tr>
								<tr class="pdf_total">
									<td></td>
									<td></td>
									<td colspan="3">小計</td>
									<td></td>
									<input type="hidden" name="subtotal" value="0">
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</main>
</body>
</html>