<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$student = $con->getData("SELECT * FROM students WHERE stud_id = $_POST[stud_id]");

$course = $con->getData("SELECT * FROM course WHERE cour_id = ".$student[0]['f_cour_id']);
$student[0]['f_cour_id'] = $course[0];

header("Content-Type: application/json");
echo json_encode($student[0]);

?>