<?php

header("Content-Type: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db("users");

$delete = $con->deleteData(array("user_id"=>implode(",",$_POST['user_id'])));	

foreach ($_POST['user_id'] as $user_id) {
		
		$file = "../../pictures/".$user_id.".jpg";
		if (file_exists($file)) unlink($file);
		
};

?>