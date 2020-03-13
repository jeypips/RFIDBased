<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$datePick = $_POST['pickDate'];

$newDate = date("Y-m-d ", strtotime($datePick));

$students = $con->getData("SELECT *, DATE_FORMAT(logb_login, '%M %d, %Y') logb_login FROM logged_book WHERE date(logb_login) = '$newDate'");

foreach($students as $key => $s){
	
	$data = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE students.stud_id = ".$s['stud_id']);
		foreach($data as $i => $d){
		
			$section = $con->getData("SELECT * FROM section WHERE sect_id = ".$d['stud_sect_id']);
			$data[$i]['stud_sect_id'] = $section[0];
			
			$year = $con->getData("SELECT * FROM year WHERE year_id = ".$d['stud_year_id']);
			$data[$i]['stud_year_id'] = $year[0];
			
		};
	$students[$key]['stud_id'] = $data[0];
	
};

header("Content-Type: application/json");
echo json_encode($students);

?>