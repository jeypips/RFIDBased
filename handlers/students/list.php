<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE is_deleted = '0'");

foreach($students as $key => $student){
	
	$course = $con->getData("SELECT * FROM course WHERE cour_id =".$student['f_cour_id']);
	$students[$key]['f_cour_id'] = $course[0];
	
	$section = $con->getData("SELECT * FROM section WHERE sect_id = ".$student['stud_sect_id']);
	$students[$key]['stud_sect_id'] = $section[0];
	
	$year = $con->getData("SELECT * FROM year WHERE year_id = ".$student['stud_year_id']);
	$students[$key]['stud_year_id'] = $year[0];
	
}

header("Content-Type: application/json");
echo json_encode($students);

?>