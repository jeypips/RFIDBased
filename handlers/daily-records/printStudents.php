<?php

date_default_timezone_set('Asia/Manila');

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$datePick = $_POST['pickDate'];

$newDate = date("Y-m-d ", strtotime($datePick));

$datas = $con->getData("SELECT *, DATE_FORMAT(logb_login, '%M %d, %Y') logb_login FROM logged_book WHERE date(logb_login) = '$newDate'");

foreach($datas as $key => $s){
	
	$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE students.stud_id = ".$s['stud_id']);
		foreach($students as $i => $d){
		
			$section = $con->getData("SELECT * FROM section WHERE sect_id = ".$d['stud_sect_id']);
			$students[$i]['stud_sect_id'] = $section[0];
			
			$year = $con->getData("SELECT * FROM year WHERE year_id = ".$d['stud_year_id']);
			$students[$i]['stud_year_id'] = $year[0];
			
		};
	$datas[$key]['stud_id'] = $students[0];
	
};

header("Content-Type: application/json");
echo json_encode($datas);

?>