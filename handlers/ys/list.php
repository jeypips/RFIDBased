<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$years = $con->getData("SELECT * FROM year");

foreach($years as $key => $y){
	
	$count = $con->getData("SELECT count(stud_year_id) count FROM students WHERE stud_year_id = ".$y['year_id']);
	$years[$key]['total'] = $count[0];
	
};

header("Content-Type: application/json");
echo json_encode($years);

?>