<?php

date_default_timezone_set('Asia/Manila');

header("Content-Type: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db("students");

$delete = $con->deleteData(array("stud_id"=>implode(",",$_POST['stud_id'])));	

foreach ($_POST['stud_id'] as $stud_id) {
		
		$file = "../../pictures/".$stud_id.".jpg";
		if (file_exists($file)) unlink($file);
		
};

?>