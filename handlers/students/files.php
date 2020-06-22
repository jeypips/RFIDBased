<?php

date_default_timezone_set('Asia/Manila');

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db("students");

header("Content-type: application/json");

switch ($_GET['r']) {
	
	case "upload_profile_picture":
		
		$dir = "../../pictures/";
		
		move_uploaded_file($_FILES['file']['tmp_name'],$dir."$_GET[id]$_GET[en]");
		
		$save_photo = array(
			"stud_id"=>$_GET['id'],
			"stud_photo"=>"pictures/$_GET[id]$_GET[en]"
		);
		
		$con->updateData($save_photo,'stud_id');

	break;
	
};

?>