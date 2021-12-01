<?php
session_start();
require_once('../../../common/dbconnect.php');

if ($_SESSION['loginID'] == "" || $_GET['id'] == "") {
	header("Location:/");
	exit();
}

$sql = sprintf('SELECT * FROM `quotation` WHERE qu_id="%d" AND delFlag=0',
	mysqli_real_escape_string($db, $_GET['id'])
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row1 = mysqli_fetch_assoc($record);

$fileName = $row1['qu_dir']. ".zip";
$dir_Name = " ./". $row1['qu_dir']. "/";
$dir = '/usr/home/haw1008ufet9/html/manage/estimates/new/pdf';
$command =  'cd ' . $dir . ';' . 'zip -r '. $fileName . $dir_Name;
exec($command);
$zipPath = $dir . '/' . $fileName;

// header('Content-Type: application/force-download');
header( 'Content-Type: application/zip');
header( 'Content-Disposition: attachment; filename="'. $fileName. '"');
header( 'Content-Length: '. filesize($zipPath));
ob_clean();
flush();
readfile($zipPath);
unlink($zipPath);

header("Location:/manage/estimates/detail/");
exit();
?>
