<?php

date_default_timezone_set('Asia/Manila');

header("Content-Type: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);

include_once '../../db.php';

$con = new pdo_db("users");

if ($_POST['user_id']) {
	
	$user = $con->updateObj($_POST,'user_id');
	
} else {
	
	$user = $con->insertObj($_POST);
	echo $con->insertId;

}

?>