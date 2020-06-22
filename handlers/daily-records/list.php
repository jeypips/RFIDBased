<?php

date_default_timezone_set('Asia/Manila');

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$courses = $con->getData("SELECT * FROM course");

header("Content-Type: application/json");
echo json_encode($courses);

?>