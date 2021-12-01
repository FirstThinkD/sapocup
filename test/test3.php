<?php

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>テキストエリアの行数を数える</title>
	<script type="text/javascript">
		function chk(str){
			var l = str.length;
			var m = l - 1;
			var n = l - 2;
			var o = escape(str.charAt(m));
			var p = escape(str.charAt(n));
			if((o=='%0A')&&(p=='%0D')){
				var estr = escape(str);
				var en = estr.length;
				var em = en - 6;
				var estr2 = estr.slice(0,em);
				str = unescape(estr2);
			} else {
				str = str.slice(0,m);
			}
			document.forms[0].summary1.value = str;
		}
	</script>
</head>
<body>
	<form>
		<textarea name="summary1" rows="4" onscroll="chk(this.value);"></textarea>
	</form> 
</body>
</html>