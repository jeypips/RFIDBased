<?php

header("Content-Type: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);

include_once '../../db.php';

$con = new pdo_db("year");

$section = $_POST['section'];
unset($_POST['section']);

$dels = $_POST['dels'];
unset($_POST['dels']);

if ($_POST['year_id']) {
	
	$year = $con->updateObj($_POST,'year_id');
	$id = $_POST['year_id'];
	
} else {
	
	unset($_POST['id']);
	$year = $con->insertObj($_POST);
	$id = $con->insertId;
	echo $con->insertId;

}


# section info
if (count($dels)) {

	$con->table = "section";
	$delete = $con->deleteData(array("sect_id"=>implode(",",$dels)));		
		
};

if (count($section)) {

	$con->table = "section";

	foreach ($section as $index => $value) {
		
		$section[$index]['f_year_id'] = $id;

	}

	foreach ($section as $index => $value) {

		if ($value['sect_id']) {
			
			$row = $con->updateObj($section[$index],'sect_id');
			
		} else {
			
			unset($section[$index]['sect_id']);
			$row = $con->insertObj($section[$index]);
			
		}
	
	}
	
};


?>