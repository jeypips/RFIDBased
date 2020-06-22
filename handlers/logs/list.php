<?php

date_default_timezone_set('Asia/Manila');

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$logs = $con->getData("SELECT *, DATE_FORMAT(system_log, '%M %d, %Y | %h:%i %p') system_log FROM logs");

foreach($logs as $key => $log) {
	
	$user = $con->getData("SELECT user_id, name, user_name FROM users WHERE user_id = ".$log['users_id']);
	$logs[$key]['users_id'] = $user[0];
	
};

header("Content-Type: application/json");
echo json_encode($logs);

?>