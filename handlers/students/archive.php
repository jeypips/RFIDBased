<?php

header("Content-Type: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$student = $con->getData("SELECT * FROM students WHERE stud_id = $_POST[stud_id]");

$con->table = "students";

$dismiss = $con->updateData(array("stud_id"=>$_POST['stud_id'],"is_deleted"=>1),'stud_id');

header("Content-Type: application/json");
echo json_encode($student[0]);

?>