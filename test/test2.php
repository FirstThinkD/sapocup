<?php

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>テキストエリアの行数を数える</title>
	<script type="text/javascript">
	<!--
		function chk(){
			tval = document.getElementById("rtxt").value;//入力された文字列
			num = tval.match(/\r\n/g); //IE 用
			num = tval.match(/\n/g);   //Firefox 用

			if(tval=="") {
				alert("何も入力されていません。");
				return;
			}
			if(num!=null) {
				gyosuu = num.length + 1;
			} else{
				gyosuu = 1;
			}
			alert("行数は "+gyosuu+"行です。");
	       }
	//-->
	</script>
</head>
<body>
	<form >
		<textarea rows="3" cols="50" id="rtxt" onblur="chk()"></textarea>
	</form>
</body>
</html>