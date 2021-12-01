<?php
session_start();

if ($_SESSION['loginID'] == "") {
	header("Location:/");
	exit();
}

unset($_SESSION['loginID']);
unset($_SESSION['loginName']);

header("Location:/login.php");
exit();
?>