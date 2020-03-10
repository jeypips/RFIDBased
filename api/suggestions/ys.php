<?php


$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$years = $con->getData("SELECT * FROM year");

foreach ($years as $key => $year) {		

	$sections = $con->getData("SELECT * FROM section WHERE f_year_id = ".$year['year_id']);
	$years[$key]['sections'] = $sections;	

};

header("Content-Type: application/json");
echo json_encode($years);

?>