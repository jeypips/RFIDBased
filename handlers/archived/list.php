<?php

date_default_timezone_set('Asia/Manila');

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students");


foreach($students as $key => $s){
		
	$section = $con->getData("SELECT * FROM section WHERE sect_id = ".$s['stud_sect_id']);
	$students[$key]['stud_sect_id'] = $section[0];
	
	$year = $con->getData("SELECT * FROM year WHERE year_id = ".$s['stud_year_id']);
	$students[$key]['stud_year_id'] = $year[0];
	
	$course = $con->getData("SELECT * FROM course WHERE cour_id = ".$s['f_cour_id']);
	$students[$key]['f_cour_id'] = $course[0];
	
};

header("Content-Type: application/json");
echo json_encode($students);

?>