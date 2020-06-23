<?php

date_default_timezone_set('Asia/Manila');

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$from = date("Y-m-d", strtotime($_POST['from'] . "+1 days"));
$to = date("Y-m-d", strtotime($_POST['to']. "+1 days"));

$cour_id = isset($_POST['course']['cour_id'])?$_POST['course']['cour_id']:"";
$year_id = isset($_POST['year']['year_id'])?$_POST['year']['year_id']:"";
$sect_id = isset($_POST['section']['sect_id'])?$_POST['section']['sect_id']:"";
$stud_id = isset($_POST['name']['stud_id'])?$_POST['name']['stud_id']:"";

if($cour_id==""&&$year_id==""&&$sect_id==""&&$stud_id==!"") { // name
	
	$students = $con->getData("SELECT CONCAT(stud_fName,' ',stud_lName) fullname, stud_RFID, stud_id, f_cour_id, stud_year_id, stud_sect_id, date_added, added_by, update_by, gender FROM students WHERE is_deleted = '0' AND (date_added BETWEEN '$from' AND '$to') AND stud_id = '$stud_id'");

} else if ($cour_id==!""&&$year_id==!""&&$sect_id==!""&&$stud_id=="") { // name null
	
	$students = $con->getData("SELECT CONCAT(stud_fName,' ',stud_lName) fullname, stud_RFID, stud_id, f_cour_id, stud_year_id, stud_sect_id, date_added, added_by, update_by, gender FROM students WHERE is_deleted = '0' AND (date_added BETWEEN '$from' AND '$to') AND (f_cour_id = '$cour_id' AND stud_year_id = '$year_id') AND stud_sect_id = '$sect_id'");
	
} else if ($cour_id==!""&&$year_id==!""&&$sect_id==""&&$stud_id=="") { // course and year
	
	$students = $con->getData("SELECT CONCAT(stud_fName,' ',stud_lName) fullname, stud_RFID, stud_id, f_cour_id, stud_year_id, stud_sect_id, date_added, added_by, update_by, gender FROM students WHERE is_deleted = '0' AND (date_added BETWEEN '$from' AND '$to') AND (f_cour_id = '$cour_id' AND stud_year_id = '$year_id')");

} else if ($cour_id==!""&&$year_id==""&&$sect_id==""&&$stud_id=="") { // course 
	
	$students = $con->getData("SELECT CONCAT(stud_fName,' ',stud_lName) fullname, stud_RFID, stud_id, f_cour_id, stud_year_id, stud_sect_id, date_added, added_by, update_by, gender FROM students WHERE is_deleted = '0' AND (date_added BETWEEN '$from' AND '$to') AND f_cour_id = '$cour_id'");
	
}else if ($cour_id==""&&$year_id==!""&&$sect_id==""&&$stud_id=="") { // year/grade
	
	$students = $con->getData("SELECT CONCAT(stud_fName,' ',stud_lName) fullname, stud_RFID, stud_id, f_cour_id, stud_year_id, stud_sect_id, date_added, added_by, update_by, gender FROM students WHERE is_deleted = '0' AND (date_added BETWEEN '$from' AND '$to') AND stud_year_id = '$year_id'");
	
} else if ($cour_id==""&&$year_id==!""&&$sect_id==!""&&$stud_id=="") { // year and section
	
	$students = $con->getData("SELECT CONCAT(stud_fName,' ',stud_lName) fullname, stud_RFID, stud_id, f_cour_id, stud_year_id, stud_sect_id, date_added, added_by, update_by, gender FROM students WHERE is_deleted = '0' AND (date_added BETWEEN '$from' AND '$to') AND (stud_year_id = '$year_id' AND stud_sect_id = '$sect_id')");
	
} else { // ALL
	
	$students = $con->getData("SELECT CONCAT(stud_fName,' ',stud_lName) fullname, stud_RFID, stud_id, f_cour_id, stud_year_id, stud_sect_id, date_added, added_by, update_by, gender FROM students WHERE is_deleted = '0' AND date_added BETWEEN '$from' AND '$to'");
	
}

foreach($students as $key => $s){
		
	$section = $con->getData("SELECT * FROM section WHERE sect_id = ".$s['stud_sect_id']);
	$students[$key]['stud_sect_id'] = $section[0];
	
	$year = $con->getData("SELECT * FROM year WHERE year_id = ".$s['stud_year_id']);
	$students[$key]['stud_year_id'] = $year[0];
	
	$course = $con->getData("SELECT * FROM course WHERE cour_id = ".$s['f_cour_id']);
	$students[$key]['f_cour_id'] = $course[0];
	
	$added_by = $con->getData("SELECT user_id, name FROM users WHERE user_id = ".$s['added_by']);
	$students[$key]['added_by'] = $added_by[0];
	
	$update_by = $con->getData("SELECT user_id, name FROM users WHERE user_id = ".$s['update_by']);
	$students[$key]['update_by'] = $update_by[0];
	
};

header("Content-Type: application/json");
echo json_encode($students);

?>