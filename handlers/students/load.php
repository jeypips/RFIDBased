<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$stud_id = $_POST['stud_id'];

$load = [];

$photo_ph = 'pictures/avatar.png';

$get_photo = $con->getData("SELECT stud_photo FROM students WHERE stud_id = $stud_id");

if (count($get_photo)) {

	$check = "../../".$get_photo[0]['stud_photo'];

	if (file_exists($check)) $load['photo'] = $get_photo[0]['stud_photo'];
	else $load['photo'] = $photo_ph;
	
}

header("Content-Type:application/json");
echo json_encode($load);

?>