<?php

date_default_timezone_set('Asia/Manila');

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$year = $con->getData("SELECT * FROM year WHERE year_id = $_POST[year_id]");

// section
$section = $con->getData("SELECT * FROM section WHERE f_year_id = ".$year[0]['year_id']);
$year[0]['section'] = $section;
$year[0]['dels'] = [];


header("Content-Type: application/json");
echo json_encode($year[0]);

?>