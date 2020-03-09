<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$profile = $con->getData("SELECT * FROM profiles WHERE id = $_POST[id]");

$profile[0]['pwd'] = ($profile[0]['pwd'])?1:0;

if($profile[0]['status']=="Student") {
	
	$profile[0]['status'] = 1;

} elseif ($profile[0]['status']=="Employed"){
	
	$profile[0]['status'] = 2;
	
} elseif ($profile[0]['status']=="Unemployed"){
	
	$profile[0]['status'] = 3;
	
}	else {
	
	$profile[0]['status'] = 4;
	
}

header("Content-Type: application/json");
echo json_encode($profile[0]);

?>