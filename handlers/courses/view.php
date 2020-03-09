<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$course = $con->getData("SELECT * FROM course WHERE cour_id = $_POST[cour_id]");

header("Content-Type: application/json");
echo json_encode($course[0]);

?>