<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$datePick = $_POST['pickDate'];

$newDate = date("Y-m-d ", strtotime($datePick));

// $students = $con->getData("SELECT *, DATE_FORMAT(logb_login, '%M %d, %Y') logb_login FROM logged_book");

$cour_id = isset($_POST['course']['cour_id'])?$_POST['course']['cour_id']:"";
$year_id = isset($_POST['year']['year_id'])?$_POST['year']['year_id']:"";

if($cour_id==""&&$year_id==!"") {
	
	$datas = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE stud_year_id = '$year_id'");

} else if ($cour_id==!""&&$year_id=="")  {
	
	$datas = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE f_cour_id = '$cour_id'");

} else if ($cour_id==!""&&$year_id==!""){
	
	$datas = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE f_cour_id = '$cour_id' AND stud_year_id = '$year_id'");

} else {
	
	$datas = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students");
	
};

foreach($datas as $key => $s){
	
	$section = $con->getData("SELECT * FROM section WHERE sect_id = ".$s['stud_sect_id']);
	$datas[$key]['stud_sect_id'] = $section[0];
	
	$year = $con->getData("SELECT * FROM year WHERE year_id = ".$s['stud_year_id']);
	$datas[$key]['stud_year_id'] = $year[0];
	
	$course = $con->getData("SELECT * FROM course WHERE cour_id = ".$s['f_cour_id']);
	$datas[$key]['f_cour_id'] = $course[0];
	
	$logs = $con->getData("SELECT *, DATE_FORMAT(logb_login, '%l:%i') time_in_out FROM logged_book WHERE date(logb_login) = '$newDate' AND stud_id = ".$s['stud_id']);
	if(empty($logs)){
		$logs = array(
			0=> array("time_in_out"=>"No time in/out"),
			1=> array("time_in_out"=>"No time in/out"),
			2=> array("time_in_out"=>"No time in/out"),
			3=>array("time_in_out"=>"No time in/out")
		);
	}
	$datas[$key]['logs'] = $logs;
			
	
};

header("Content-Type: application/json");
echo json_encode($datas);

?>