<?php
echo $_SERVER['HTTP_USER_AGENT'];
echo "<br>";
$http_user = $_SERVER['HTTP_USER_AGENT'];
if (strstr($http_user, 'Firefox')) {
	echo "Firefox";
} else if (strstr($http_user, 'iPhone')) {
	echo "iPhone";
} else if (strstr($http_user, 'Chrome')) {
	echo "Chrome";
}
echo "<br>";
$ua=$_SERVER['HTTP_USER_AGENT'];
$browser = ((strpos($ua,'iPhone')!==false)||(strpos($ua,'iPod')!==false)||(strpos($ua,'Android')!==false));
if ($browser == true){
	$browser = 'sp';
} else {
	$browser = 'pc';
}
echo "browser=". $browser. "<br>";
?>
