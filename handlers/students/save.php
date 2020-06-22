<?php

date_default_timezone_set('Asia/Manila');

header("Content-Type: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);

include_once '../../db.php';

$con = new pdo_db("students");

session_start();

if ($_POST['stud_id']) {
	
	$id = $_POST['stud_id'];
	$_POST['update_by'] = $_SESSION['user_id']; 
	$student = $con->updateObj($_POST,'stud_id');
	
} else {
	
	$_POST['added_by'] = $_SESSION['user_id']; 
	$student = $con->insertObj($_POST);
	$id = $con->insertId;
	echo $id;
}

?>