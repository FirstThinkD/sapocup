<?php
session_start();
require_once(__DIR__ . '/../../../../common/dbconnect.php');

// GETメソッドでリクエストした値を取得
$id = $_GET['id'];

$sql = "SELECT * FROM dep_list WHERE delFlag=0 AND dl_id=". $id;
// $sql = sprintf('SELECT * FROM `dep_list` WHERE qu_id="%d" AND delFlag=0',
//	mysqli_real_escape_string($db, $id)
// );
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
$row = mysqli_fetch_assoc($record);

$productList[] = array(
	'dl_id'      => $row['dl_id'],
	'dl_yymmdd'  => date('Y-m-d', strtotime($row['dl_yymmdd'])),
	'dl_money'   => $row['dl_money'],
	'dl_comment' => $row['dl_comment']
);

header('Content-type: application/json');
echo json_encode($productList);