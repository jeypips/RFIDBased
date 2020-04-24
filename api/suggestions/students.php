<?php


$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$data_stud = $con->getData("SELECT CONCAT(stud_fName,' ',stud_lName) fullname, stud_id FROM students");

header("Content-Type: application/json");
echo json_encode($data_stud);

?>