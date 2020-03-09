<?php

header("Content-Type: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);

include_once '../../db.php';

$con = new pdo_db("course");

if ($_POST['cour_id']) {
	
	$course = $con->updateObj($_POST,'cour_id');
	
} else {
	
	$course = $con->insertObj($_POST);
	echo $con->insertId;

}

?>