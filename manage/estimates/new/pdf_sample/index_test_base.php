<style>
*,
*:before,
*:after {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}
html, body, div, span, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
abbr, address, cite, code,
del, dfn, em, img, ins, kbd, q, samp,
small, strong, sub, sup, var,
b, i,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, figcaption, figure,
footer, header, hgroup, menu, nav, section, summary,
time, mark, audio, video {
  margin:0;
  padding:0;
  border:0;
  outline:0;
  font-size:100%;
  vertical-align:baseline;
  background:transparent;
}
body {
  line-height:1;
}
article,aside,details,figcaption,figure,
footer,header,hgroup,menu,nav,section {
  display:block;
}
ul {
  list-style: none
}
blockquote, q {
  quotes:none;
}
blockquote:before, blockquote:after,
q:before, q:after {
  content:'';
  content:none;
}
a {
  margin:0;
  padding:0;
  font-size:100%;
  vertical-align:baseline;
  background:transparent;
}
ins {
  background-color:#ff9;
  color:#000;
  text-decoration:none;
}
mark {
  background-color:#ff9;
  color:#000;
  font-style:italic;
  font-weight:bold;
}
del {
  text-decoration: line-through;
}
abbr[title], dfn[title] {
  border-bottom:1px dotted;
  cursor:help;
}
table {
  border-collapse:collapse;
  border-spacing:0;
}
hr {
  display:block;
  height:1px;
  border:0;  
  border-top:1px solid #cccccc;
  margin:1em 0;
  padding:0;
}
input, select {
  vertical-align:middle;
}


/* ------------------------------------------------------------------------



  HTML tag



------------------------------------------------------------------------ */
body {
  color: #162533;
  background-color: #f5f7f9;
  font-size: 16px;
  letter-spacing: .08em;
  font-family: 'Hiragino Kaku Gothic Pro','Hiragino Kaku Gothic ProN',Meiryo,'MS PGothic',sans-serif;
}
a {
  text-decoration: none;
  color: #162533;
}
p, .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
  line-height: 1.8;
}
img {
  width: 100%;
  height: auto;
}
h1, h2, h3, h4, .h2, .h3, .h4, .h5 {
  font-weight: 700;
}
select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}
button, input, select {
  font-size: 16px;
}
button, input[type="submit"] {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    padding: 0;
    border: none;
    outline: none;
    background: transparent;
    color: #fff;
}
select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  border: none;
  outline: none;
}
input, select {
  background-color: #fff;
  border: 1px solid #eee;
}

/* ------------------------------------------------------------------------



  HTML class



------------------------------------------------------------------------ */

.text-center {
  text-align: center !important;
}
.text-right {
  text-align: right !important;
}
.text-left {
  text-align: left !important;
}
.mb-none {
  margin-bottom: 0 !important;
}
.pb-none {
  padding-bottom: 0 !important;
}
/* -------------------------------

  container

------------------------------- */
.container {
  max-width: 1240px;
  min-width: 1240px;
  padding: 0 30px;
  margin: auto;
}
.input_area .container {
  max-width: 1080px;
  min-width: 1080px;
}

/* -------------------------------

  cf

------------------------------- */
.cf:before,
.cf:after {
  content:"";
  display:table;
}
.cf:after {
  clear:both;
}
.cf {
  zoom:1;
}

.submit_button {
  margin: 90px auto auto;
  width: 200px;
}
.submit_button a,
.submit_button input[type="submit"] {
  text-align: center;
  display: block;
  width: 100%;
  height: 40px;
  line-height: 40px;
  color: #fff;
  background-color: #f2994a;
}
.submit_button a:hover,
.submit_button input[type=submit]:hover {
  opacity: 0.6;
  text-decoration: none;
  cursor: pointer;
}





/* ------------------------------------------------------------------------



  header



------------------------------------------------------------------------ */
header#header {
  background-color: #fff;
  text-align: left;
}
/* -------------------------------

  header - headerline

------------------------------- */
#headline {
  padding: 10px 0px;
}
#headline .headerline_logo {
  float: left;
}
#headline .headerline_logo img {
  width: 200px;
}
#headline .headerine_option {
  float: right;
  font-size: 12px;
}
#headline .option_chat {
  display: inline-block;
}
#headline .option_edit {
  display: inline-block;
}
#headline .headerline_option_bottom {
  text-align: right;
  margin-bottom: 5px;
}
#headline .username,
#headline .username a {
  color: #9c9c9c;
}
#headline .username a:hover {
  text-decoration: underline;
}
#headline .headerline_option_top {
  text-align: right;
}
#headline .headerline_option_top a {
  display: inline-block;
  padding: 5px 10px;
  border-radius: 3px;
  border: 1px solid #f2994a;
  color: #f2994a;
}
#headline .headerline_option_top a:hover {
  background: #f2994a;
  color: #fff;
}

/* -------------------------------

  nav

------------------------------- */
nav#nav {
  width: 100%;
  background-color: #3b3f47;
}
nav#nav ul {
  white-space: nowrap;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  letter-spacing: -.4em;
}
nav#nav ul li {
  display: inline-block;
  letter-spacing: normal;
}
nav#nav ul li.nav_right {
  float: right;
}
nav#nav ul li a {
  display: block;
  padding: 15px 30px;
  color: #fff;
  font-size: 14px;
  text-align: center;
  -webkit-transition: .3s all;
  transition: .3s all;
}
nav#nav ul li.nav_right a {
  color: #a6a6a6;
  pointer-events:none;
}
nav#nav ul li.nav_right a {
  padding: 15px 15px;
}
nav#nav ul li.selected a {
  background-color: #00acc1;
}
nav#nav ul li a:hover {
  background-color: #00acc1;
}



/* ------------------------------------------------------------------------



  main



------------------------------------------------------------------------ */
.main_wrap {
  padding-bottom: 60px;
}
/* -------------------------------

  main - pankuzu

------------------------------- */
.pankuzu {
  padding: 10px 0px 0px;
  text-align: left;
}
.pankuzu ul li {
  display: inline-block;
}
.pankuzu ul li:after {
  content: ">";
  display: inline-block;
  padding: 0px 6px 0px 10px;
  font-size: 11px;
}
.pankuzu ul li:last-child:after {
  content: none;
}
.pankuzu ul li a {
  color: #00acc1;
  text-decoration: underline;
}
.pankuzu ul li a:hover {
  text-decoration: none;
}
.pankuzu ul li span {
  font-size: 12px;
}
/* -------------------------------

  main - title

------------------------------- */
.main_title {
  padding: 40px 0px 50px;
  text-align: center;
}
.main_title .main_title_headline .main_title_headline_inner {
  display: inline-block;
}
.main_title .main_title_headline h1 {
  font-size: 20px;
  font-weight: 700;
  color: #162533;
}
.main_title_link {
  padding-top: 20px;
}
.main_title_link ul {
  display: inline-block;
}
.main_title_link ul li {
  margin-right: 15px;
  float: left;
}
.main_title_link ul li:last-child {
  margin-right: 0;
}
.main_title_link ul li a {
  display: inline-block;
  padding: 10px 20px;
  color: #fff;
}
.main_title_link ul li input {
  display: inline-block;
  padding: 6px 20px;
  color: #fff;
}

.main_title_link ul li a.create_link {
  background-color: #f2994a;
}
.main_title_link ul li a.list_link {
  background-color: #00acc1;
}
.main_title_link ul li input {
  background-color: #00acc1;
}
/* -------------------------------

  main - content

------------------------------- */
.main_content .container {
  padding: 30px;
  background-color: #fff;
  border: 1px solid #e0e0e0;
}
.main_content .container.table_field {
  padding: 0;
  background-color: rgba(255,255,255,0);
  border: none;
}

/* ------------------------------------------------------------------------



  table



------------------------------------------------------------------------ */
p.table_supplement {
  text-align: right;
  font-size: 12px;
  color: #858c90;
}
table.common_table {
  width: 100%;
  margin-bottom: 16px;
  max-width: 100%;
  background-color: transparent;
  border-collapse: collapse;
  border-spacing: 0;
  background-color: #fff;
  border: 1px solid #c8cfd7;
}
table.common_table thead tr {
  background: #464B54;
  color: #fff;
}
table.common_table thead th {
  padding: 6px;
  text-align: left;
  vertical-align: middle;
  color: #fff;
  font-weight: normal;
  white-space: nowrap;
  font-size: 12px;
}
table.common_table tr {
  border-bottom: 1px solid #d9e0e8;
}
table.common_table tr:nth-child(2n) {
  background-color: #f9f9f9;
}

table.common_table td {
  padding: 10px 6px;
  vertical-align: top;
  font-size: 13px;
  vertical-align: middle;
}
table.common_table td.address_field {
  line-height: 1.4;
  font-size: 12px;
}
table.common_table td select {
  padding: 5px 15px;
}
table.common_table td a {
  text-decoration: underline;
  color: #00acc1;
}
table.common_table td a:hover {
  text-decoration: none;
}
table.common_table td.button_link a {
  font-size: 12px;
  border-radius: 3px;
  padding: 5px 10px;
  background-color: #00acc1;
  color: #fff;
  text-decoration: none;
}
table.common_table td.button_link a:hover {
  opacity: .6;
}
table.common_table a.table_link.disable {
  background-color: #c8c8c8;
  cursor: auto;
}

/* ------------------------------------------------------------------------



  Vue.js



------------------------------------------------------------------------ */

[v-cloak] {
  display: none;
  text-indent: -9999px;
}

.data_title .main_title_headline {
	text-align: center;
}
.data_title_inner {
	position: relative;
	display: inline-block;
	width: 450px;
}
.main_title_headline .thisMonth {
	color: #aaaaaa;
	font-size: 14px;
}
.main_title_headline .paganetion {
	background: #00acc1;
	color: #fff;
	padding: 10px 15px;
	position: absolute;
	top: 50%;
	-webkit-transform: translateY(-50%);
	transform: translateY(-50%);
	font-size: 14px;
}
.main_title_headline .nextMonth {
	left: 0;
}
.main_title_headline .prevMonth {
	right: 0;
}
/* -------------------------------

  パーソナル

------------------------------- */
.data_fields table.common_table .dunning {
	background-color: #FF684A;
	text-align: center;
}
.data_fields table.common_table th.depositMemo,
.data_fields table.common_table td.depositMemo {
	text-align: center;
}
.data_fields table.common_table td.smsInput,
.data_fields table.common_table td.telInput {
	width: 40px;
	text-align: center;
}

/* ------------------------------------------------------------------------



	見積書一覧　/manage/estimates/index.php



------------------------------------------------------------------------ */

table.common_table td.estimates_link {
  width: 260px;
}
table.common_table a.table_link.delete {
	background-color: #3b3f47;
	border-radius: 50%;
}
table.common_table a.table_link:not(.disable):hover {
  opacity: 0.6;
 }
table.common_table a.table_link:last-child {
  margin-right: 0;
}





/* ------------------------------------------------------------------------



  見積もり作成機能　/manage/estimates/new/index.php



------------------------------------------------------------------------ */


.estimates_title {
	font-weight: 700;
	padding-left: 20px;
	padding-bottom: 5px;
	margin-bottom: 20px;
	border-bottom: 1px solid #c4c4c4;
}


/* -------------------------------

  パーソナル

------------------------------- */
.personal_fields {
	margin-bottom: 60px;
	padding-top: 30px;
	text-align: left;
}
.personal_fields input,
.personal_fields select {
	background-color: #f9f9f9;
	border: 1px solid #e5e5e5;
	font-size: 14px;
	width: 100%;
	padding: 10px;
}
.personal_fields_left {
	float: left;
	width: 50%;
	border-right: 1px solid #c4c4c4;
	padding-right: 50px;
}
.personal_fields_block {
	margin-bottom: 10px;
}
.personal_fields_block.margin_none {
	margin-bottom: 0;
}
.personal_fields_block .label_box {
	position: relative;
	text-align:right;
	float: left;
	width: 160px;
	padding-top: 5px;
}
.personal_fields_block .label_box.must:after {
	content: "必須";
	position: absolute;
	right: 3px;
	top: 10px;
	display: inline-block;
	padding: 1px;
	border-radius: 2px;
	background-color: #ff3333;
	color: #fff;
	font-weight: 700;
	font-size: 10px;
}
.personal_fields_block .label_box p {
	font-size: 14px;
	padding-right: 30px;
}
.personal_detail .personal_fields_block .label_box p {
	font-size: 12px;
}
.personal_fields_block .input_box {
	float: right;
	width: 100%;
	margin-left: -160px;
	padding-left: 160px;
}
.personal_fields_block.sub_title {
	margin-bottom: 0;
	padding-bottom: 10px;
}
.personal_fields_block.sub_title .label_box p {
	font-size: 10px;
	line-height: 1;
}
.personal_fields_block .percent {
	position: relative;
}
.personal_fields_block .percent input {
	padding-right: 40px;
}
.personal_fields_block .percent:before {
	content: "%";
	display: block;
	width: 30px;
	position: absolute;
	right: 0;
	top: 50%;
	-webkit-transform: translateY(-50%);
	transform: translateY(-50%);
}
.construction_class {
	width: 200px;
}
.construction_class .class_box {
	position: relative;
	width: 100px;
	float: left;
	height: 39px;
}
.construction_class .class_box input[type=radio] {
	width: 100%;
	height: 100%;
	opacity: 0;
}
.construction_class .class_box label {
	position: absolute;
	top: 0;
	left: 0;
	font-size: 14px;
	color: #b6b6b6;
	border: 1px solid #e5e5e5;
	background-color: #f9f9f9;
	width: 100%;
	height: 100%;
	line-height: 39px;
	text-align: center;
	pointer-events: none;
}
.construction_class .class_box input:checked + label {
	background: #00acc1;
    border: 1px solid #00acc1;
    color: #fff;
}
.personal_fields_right {
	float: left;
	width: 50%;
	padding-left: 60px;
}

.personal_detail {
	border: 1px solid #e5e5e5;
}
.personal_detail a {
	position: relative;
	width: 100%;
	text-align: left;
	color: #0190a2;
	display: block;
	cursor: pointer;
	font-size: 14px;
	padding: 10px 20px;
}
.personal_fields .personal_detail_inner {
	padding: 10px 20px;
}
.personal_fields .personal_detail_inner p.title {
	padding-left: 0;
}
.personal_fields .personal_detail a span img {
  width: 12px;
}
.input_area .container p.title {
  font-weight: 700;
  font-size: 12px;
  padding-left: 10px;
  margin-bottom: 3px;
}

/* -------------------------------

  見積書トータル

------------------------------- */
.pdf_fields,
.pdf_fields input,
.pdf_fields select {
	font-family: "游明朝", YuMincho, "Hiragino Mincho ProN W3", "ヒラギノ明朝 ProN W3", "Hiragino Mincho ProN", "HG明朝E", "ＭＳ Ｐ明朝", "ＭＳ 明朝", serif
}
.pdf_fields_total {
	padding-left: 40px;
}
.pdf_fields_total input,
.pdf_fields_total select {
	background-color: #f9f9f9;
    border: 1px solid #e5e5e5;
    font-size: 14px;
    padding: 10px;
}


.pdf_total_top {

}
.top_total_money {
	float: left;
	width: 68%;
	margin-right: 2%;
	font-weight: 700;
}
.top_total_money_inner {
	font-size: 22px;
	position: relative;
	padding: 10px 10px 3px;
	border-bottom: 1px solid #ccc;
}
.top_total_money_inner input[readonly='readonly'],
.top_total_money_inner select[readonly='readonly'] {
	width: 300px;
	font-size: 32px;
	position: absolute;
    right: 0;
    bottom: 0;
    border: none;
    padding: 0 10px 0 0;
    text-align: right;
    pointer-events : none;
    background: #fff;
}
.pdf_total_bottom_box input[readonly='readonly'],
.pdf_total_bottom_box select[readonly='readonly'] {
    pointer-events : none;
    background-color: #dddddd;
}
.top_total_tax {
	float: left;
	width: 30%;
	padding-top: 30px;
	font-size: 12px;
}
.top_total_tax_inner {
	position: relative;
}
.top_total_tax_inner input[readonly='readonly'] {
	position: absolute;
	width: 120px;
	right: 0;
	bottom: 0px;
	padding: 0 10px 0 0;
	border: none;
	text-align: right;
    pointer-events : none;
    background-color: #fff;
}
.pdf_total_bottom {}
.pdf_total_bottom_box {
	margin-bottom: 20px;
}
.pdf_item {
	float: left;
	width: 50%;
	border: 1px solid #ccc;
}
.pdf_total_bottom_box:nth-of-type(1) .pdf_item:first-child {
	border-right: none;
}
.pdf_total_bottom_box:nth-of-type(2) .pdf_item:nth-child(odd) {
	border-right: none;
}
.pdf_total_bottom_box:nth-of-type(2) .pdf_item:nth-child(1),
.pdf_total_bottom_box:nth-of-type(2) .pdf_item:nth-child(2),
.pdf_total_bottom_box:nth-of-type(2) .pdf_item:nth-child(3),
.pdf_total_bottom_box:nth-of-type(2) .pdf_item:nth-child(4) {
	border-bottom: none;
}
.pdf_item .item_title {
	float: left;
	font-size: 14px;
	width: 45%;
	padding: 5px;
}
.pdf_item .item_number {
	float: left;
	width: 55%;
	padding: 5px;
	border-left: 1px solid #ccc;
}
.pdf_item .item_number input {
	width: 100%;
	text-align: right;
}

/* -------------------------------

  見積書詳細

------------------------------- */

.pdf_fields {
	text-align: left;
}
.pdf_fields table {
	width: 100%;
	border-collapse: collapse;
}
.pdf_fields table thead th {
	background-color: #eee;
    border: solid 1px #cccccc;
	font-weight: 400;
	font-size: 14px;
	padding: 10px;
	text-align: center;
}
.pdf_fields table th:nth-child(1),
.pdf_fields table td:nth-child(1) {
	width: 4%;
	padding: 0 10px 0 0;
}
.pdf_fields table td {
    border: solid 1px #cccccc;
    padding: 10px;
}
.pdf_fields table th:nth-child(1),
.pdf_fields table td:nth-child(1) {
	border: none;
	background-color: rgba(0,0,0,0);
}
.pdf_fields table td:nth-child(2) {
	width: 36%;
}
.pdf_fields table td:nth-child(3) {
	width: 10%;
}
.pdf_fields table td:nth-child(4) {
	width: 10%;
}
.pdf_fields table td:nth-child(5) {
	width: 20%;
}
.pdf_fields table td:nth-child(6) {
	width: 20%;
}
.pdf_fields table td input {
	border: none;
	width: 100%;
	padding: 5px;
    font-size: 14px;
	border-bottom: 1px dashed #cccccc;
}
.pdf_fields table td input:focus {
	outline: none;
	border-bottom: 1px dashed #3b3f47;
}
.pdf_fields table td.totalPrice input {
	display: block;
	width: 100%;
	height: 40px;
	font-size: 16px;
	background-color: #f5f5f5;
    border-bottom: none;
}
.pdf_input span.delete {
	position: relative;
	display: block;
	width: 20px;
	height: 20px;
	background-color: #3b3f47;
	border-radius: 50%;
	text-align: center;
}
.pdf_input span.delete img {
	width: 50%;
	position: absolute;
	top: 50%;
	left: 50%;
	-webkit-transform: translate(-50%,-50%);
    transform: translate(-50%,-50%);
}

.pdf_total td:nth-child(1) {
	border:none;
}
.pdf_total td:nth-child(2) {
	border:none;
}
.pdf_total td:nth-child(3) {
	text-align: center;
}
.pdf_total td:nth-child(4) {
	text-align: right;
}
.pdf_total td a {
	cursor: pointer;
	font-size: 14px;
	border: 1px solid #d5d5d5;
	color: #858c90;
	border-radius: 3px;
	padding: 3px 10px;
	display: block;
}





/* ------------------------------------------------------------------------



	シミュレーション　/manage/estimates/simulation/index.php



------------------------------------------------------------------------ */


/* -------------------------------

	シミュレーション - 比較

------------------------------- */
.simulation_data_comparison_inner {}
.simulation_data_comparison_inner table {
	width: 100%;
}
.simulation_data_comparison_inner table tbody {
	background-color: #fff;
}
.simulation_data_comparison_inner table thead th:nth-child(2),
.simulation_data_comparison_inner table thead th:nth-child(3),
.simulation_data_comparison_inner table thead th:nth-child(4) {
	color: #fff;
}
.simulation_data_comparison_inner table thead th:nth-child(2) {
	background: #00B6CC;
}
.simulation_data_comparison_inner table thead th:nth-child(3) {
	background: #0ABF6D;
}
.simulation_data_comparison_inner table thead th:nth-child(4) {
	background: #00D6B2;
}
.simulation_data_comparison_inner table tbody tr:last-child th,
.simulation_data_comparison_inner table tbody tr:last-child td {
	font-weight: 700;
}
.simulation_data_comparison_inner table th,
.simulation_data_comparison_inner table td {
	padding: 16px;
}
.simulation_data_comparison_inner table thead th {
	text-align: center;
	vertical-align: middle;
	border-right: #f5f7f9 2px solid;
}
.simulation_data_comparison_inner table thead th:last-child {
	border-right: none;
}

.simulation_data_comparison_inner table thead th span.name {
	font-size: 12px;
	margin-bottom: 10px;
	color: #aaaaaa;
	display: block;
}
.simulation_data_comparison_inner table thead th span.number {
	font-size: 20px;
}
.simulation_data_comparison_inner table tbody th {
    color: #777777;
	font-size: 14px;
	font-weight: 400;
}
.simulation_data_comparison_inner table tbody tr:nth-child(2n) {
	background-color: #f4f4f4;
}
.simulation_data_comparison_inner table th {
	text-align: left;
}
.simulation_data_comparison_inner table td {
	text-align: center;
	font-size: 16px;
	padding: 16px 0px;
}
.simulation_data_comparison_inner table tbody td span.edit {
	display: inline-block;
	padding: 5px 10px;
	font-size: 12px;
	background-color: #f2994a;
	color: #fff;
	border-radius: 3px;
	font-weight: 700;
}

/* -------------------------------

	シミュレーション - タブ

------------------------------- */
.simulation_data_tab {
	margin: 35px auto;
	width: 700px;
}
.data_tab_box {
	cursor: pointer;
	width: 33.3333%;
	float: left;
	text-align: center;
	height: 60px;
	line-height: 60px;
	color: #bbbbbb;
	background: #fff;
	-webkit-transition: .3s all;
	transition: .3s all;
}
.data_tab_box:hover {
	background-color: #E0E0E0;
}
.data_tab_box.selectTab {
	position: relative;
	color: #fff;
	background-color: #00acc1;
}
.data_tab_box.selectTab:before {
	content: "";
	position: absolute;
	left: 50%;
	bottom: -20px;
	-webkit-transform: translateX(-50%);
	transform: translateX(-50%);
	display: block;
	width: 0;
	height: 0;
	border-style: solid;
	border-width: 20px 15px 0 15px;
	border-color: #00acc1 transparent transparent transparent;
}
.tab_box.selectTab {
	border-left: 1px solid #c4c4c4;
	border-top: 1px solid #c4c4c4;
	border-right: 1px solid #c4c4c4;
	border-bottom: 1px solid #fff;
}




/* -------------------------------

	シミュレーション - テーブル

------------------------------- */

.simulation_data_panel_inner .main_title_link {
	text-align: center;
	padding-bottom: 40px;
}
.simulation_data_panel .data_panel_box {
	display: none;
	opacity: 0;
}
.simulation_data_panel .data_panel_box.openPanel {
	display: block;
	opacity: 1;
}
/*
	シミュレーション - テーブル - 情報
*/

.data_info_box {
	width: 700px;
	margin: auto auto 30px;
}
.data_panel_box .none {
	text-align: center;
}
.data_panel_box .none .submit_button {
	margin-top: 60px;
}
.data_info_box_inner table {
	width: 100%;
	font-size: 13px;
	background-color: #fff;
	border-collapse: collapse;
}
.data_info_box_inner table th,
.data_info_box_inner table td {
	padding: 15px;
	border: 1px solid #e0e0e0;
}
.data_info_box_inner table th {
	font-weight: 400;
	background: #fafafa;
	text-align: left;
}
.data_info_box_inner table td {
	text-align: right;
}
.data_info_box_inner table tr:nth-child(1) td,
.data_info_box_inner table tr:nth-child(3) td {
	background-color: #FFEADB;
}
.data_info_box_inner table tr:nth-child(2) td,
.data_info_box_inner table tr:nth-child(4) td {
	background-color: #F5D1B9;
}
.data_info_box_inner table tr:nth-child(4) > *:nth-child(3),
.data_info_box_inner table tr:nth-child(4) > *:nth-child(4) {
	background-color: #f2994a;
	color: #fff;
}

/* モーダル */
.modal{
	display: none;
	position: fixed;
	top: 30px;
}
.modal__bg{
	background: rgba(0,0,0,0.8);
	height: 100vh;
	position: absolute;
	width: 100%;
}
.modal__content{
	background: #fff;
	left: 50%;
	padding: 40px;
	position: absolute;
	top: 50%;
	transform: translate(-50%,-50%);
	width: 60%;
}
/*
	シミュレーション - テーブル - テーブル
*/

.simulation_data_panel_table table.common_table {
	margin-bottom: 0;
}
.simulation_data_comparison_inner .submit_button {
	margin-top: 60px;
}




/* ------------------------------------------------------------------------



	シミュレーション　編集　/manage/estimates/simulation/edit/



------------------------------------------------------------------------ */

#simulationEdit table input,
#simulationEdit table select {
	width: 200px;
	border: 1px solid #e5e5e5;
	font-size: 14px;
	padding: 10px;
}




/* ------------------------------------------------------------------------



	会員情報　/manage/user/index.php



------------------------------------------------------------------------ */

.owner_wrap_box:nth-child(1) {
	border-bottom: 1px solid #c4c4c4;
}
.owner_wrap_box_inner {
	padding: 30px;
}
.owner_headline {
	float:left;
	width: 200px;
	padding: 30px;
	font-weight: 700;
}
.owner_content {
	float: right;
	width: 100%;
	margin-left: -200px;
	padding-left: 200px;
}
.owner_content_inner {
	border-left: 1px solid #c4c4c4;
	padding: 30px;
}
.owner_content_inner .submit_button {
	margin-top: 60px;
}
.owner_content_box {
	margin-bottom: 15px;
}
.owner_content_left {
	float: left;
	width: 160px;
}
.owner_content_right {
	float: right;
	width: 100%;
	margin-left: -180px;
	padding-left: 180px;
}
.owner_content_box_two {
	width: 48%;
	float: left;
}
.owner_content_box_two:first-child {
	margin-right: 4%;
}
.owner_content_box_two .title {
	float: left;
	width: 30%;
	padding-top: 10px;
}
.owner_content_box_two .input {
	float: left;
	width: 70%;
}
.owner_content_right input,
.owner_content_box_two .input input {
	background-color: #f9f9f9;
	border: 1px solid #e5e5e5;
	font-size: 14px;
	width: 100%;
	padding: 10px;
}


/* -------------------------------

	会員情報 - 契約情報

------------------------------- */
.owner_content_inner table {
	width: 100%;
	border-collapse: collapse;
}
.owner_content_inner table td,
.owner_content_inner table th {
	width: 50%;
	border: solid 1px white;
	text-align: center;
	padding: 20px 10px;
	vertical-align: middle;
	color: #297FCA;
	line-height: 1.8;
}
.owner_content_inner table th {
	font-weight: 400;
}
.owner_content_inner table tr:nth-child(even) th {
	background: #EAEFF7;
}
.owner_content_inner table tr:nth-child(odd) th {
	background: #D2DEEF;
}
.owner_content_inner table td {
	text-align: center;
	background: #D2DEEF;
	font-size: 18px;
}
.owner_content_inner table td a,
.owner_content_inner table td span {
	display: inline-block;
	width: 100px;
	padding: 5px 0px;
	background: #297FCA;
	color: #fff;
	font-size: 12px;
}
.owner_content_inner table td a.contracted,
.owner_content_inner table td span.contracted {
	background: #ff3333;
}


/* ------------------------------------------------------------------------



  検索・帳票　/manage/search/



------------------------------------------------------------------------ */
.input_search {
	width: 400px;
	margin: auto;
	background-color: #fff;
}
.input_search_inner {
	width: 100%;
	padding: 30px;
}
.input_search_inner .submit_button {
	margin-top: 30px;
}
.input_search_item {
	margin-bottom: 30px;
}
.input_search table {
	text-align: left;
	border-collapse: separate;
	border-spacing: 10px;
}
.input_search_person table {
	width: 100%;
}
.input_search_person table td:nth-child(1) {
	width: 75px;
}
.input_search select {
	width: 100%;
	border-radius: 1px;
	padding: 10px;
}


/* ------------------------------------------------------------------------



	見積書サンプル　/manage/estimates/new/pdf_sample/



------------------------------------------------------------------------ */
body.white {
	background-color: #fff;
}
.main_content.pdf_demo {
	margin-top: 30px;
}
.main_content.pdf_demo .container {
	border: none;
}
</style>
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