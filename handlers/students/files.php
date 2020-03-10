<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

header("Content-type: application/json");

switch ($_GET['r']) {
	
	case "upload_profile_picture":
		
		$dir = "../../pictures/";
		
		move_uploaded_file($_FILES['file']['tmp_name'],$dir."$_GET[id]$_GET[en]");

	break;
	
};

?>