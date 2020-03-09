<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students");

header("Content-Type: application/json");
echo json_encode($students);

?>