<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$stud_id = $_POST['stud_id'];

$load = "";

$photo = '../../pictures/'.$stud_id.'.jpg';

if (file_exists($photo))
{
	$photo = "pictures/$stud_id.jpg";
	
} else{
	
	$photo = "pictures/avatar.png";
	
}
$load['photo'] = $photo;

header("Content-Type:application/json");
echo json_encode($load);

?>