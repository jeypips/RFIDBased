<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students");

foreach($students as $key => $student){
	
	$course = $con->getData("SELECT * FROM course WHERE cour_id =".$student['f_cour_id']);
	$students[$key]['f_cour_id'] = $course[0];
	
}

header("Content-Type: application/json");
echo json_encode($students);

?>