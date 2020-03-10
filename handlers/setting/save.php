<?php

header("Content-Type: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);

include_once '../../db.php';

$con = new pdo_db("users");

if ($_POST['user_id']) {
	
	$user = $con->updateObj($_POST,'user_id');
	
}

?>