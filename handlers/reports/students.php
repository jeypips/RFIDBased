<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$from = date("Y-m-d", strtotime($_POST['from'] . "+1 days"));
$to = date("Y-m-d", strtotime($_POST['to']));

// $date1 = str_replace('-', '/', $from_);
// $from = date('m-d-Y',strtotime($from_ . "+1 days"));

// var_dump($from); exit();

// $students = $con->getData("SELECT *, DATE_FORMAT(logb_login, '%M %d, %Y') logb_login FROM logged_book");

$cour_id = isset($_POST['course']['cour_id'])?$_POST['course']['cour_id']:"";
$year_id = isset($_POST['year']['year_id'])?$_POST['year']['year_id']:"";
$sect_id = isset($_POST['section']['sect_id'])?$_POST['section']['sect_id']:"";
$stud_id = isset($_POST['name']['stud_id'])?$_POST['name']['stud_id']:"";

/* if($cour_id==!""&&$year_id==!""&&$sect_id==!""&&$stud_id==!"") {
	
	$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE (stud_id = '$stud_id' AND f_cour_id = '$cour_id') (stud_year_id = '$year_id' AND stud_sect_id = '$sect_id')");

} else if ($cour_id==!""&&$year_id==!""&&$sect_id==""&&$stud_id=="")  {
	
	$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE f_cour_id = '$cour_id' AND stud_year_id = '$year_id'");

} else if ($cour_id==!""&&$year_id==""&&$sect_id==""&&$stud_id==""){
	
	$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE f_cour_id = '$cour_id'");

} else if ($stud_id==!""){
	
	$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE stud_id = '$stud_id'");

} else {
	
	$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students");
	
};

foreach($students as $key => $s){
	
	$section = $con->getData("SELECT * FROM section WHERE sect_id = ".$s['stud_sect_id']);
	$students[$key]['stud_sect_id'] = $section[0];
	
	$year = $con->getData("SELECT * FROM year WHERE year_id = ".$s['stud_year_id']);
	$students[$key]['stud_year_id'] = $year[0];
	
	$course = $con->getData("SELECT * FROM course WHERE cour_id = ".$s['f_cour_id']);
	$students[$key]['f_cour_id'] = $course[0];
	
	$id = $s['stud_id'];
	
	$logs = $con->getData("SELECT *, DATE_FORMAT(logb_login, '%l:%i') time_in_out FROM logged_book WHERE date(logb_login) = '$newDate' AND stud_id = ".$s['stud_id']);
	
	if(empty($logs)){
		$logs = array(
			0=> array("time_in_out"=>"No time in/out"),
			1=> array("time_in_out"=>"No time in/out"),
			2=> array("time_in_out"=>"No time in/out"),
			3=>array("time_in_out"=>"No time in/out")
		);
	}
	
	$students[$key]['logs'] = $logs;
			
	
};
 */

if($stud_id==!"") {
	
	$logs = $con->getData("SELECT DISTINCT date(logb_login) date_only, logged_book.stud_id, DATE_FORMAT(logb_login, '%M %d, %Y') date_label, students.f_cour_id, students.stud_year_id, students.stud_sect_id, students.stud_id FROM logged_book LEFT JOIN students ON students.stud_id = logged_book.stud_id WHERE (logb_login BETWEEN '$from' AND '$to') AND logged_book.stud_id = '$stud_id'");

} else if ($cour_id==!""&&$year_id==!""&&$sect_id==!""&&$stud_id=="") {
	
	$logs = $con->getData("SELECT DISTINCT date(logb_login) date_only, logged_book.stud_id, DATE_FORMAT(logb_login, '%M %d, %Y') date_label, students.f_cour_id, students.stud_year_id, students.stud_sect_id, students.stud_id FROM logged_book LEFT JOIN students ON students.stud_id = logged_book.stud_id WHERE (logb_login BETWEEN '$from' AND '$to') AND (f_cour_id = '$cour_id' AND stud_year_id = '$year_id') AND stud_sect_id = '$sect_id'");
	
} else if ($cour_id==!""&&$year_id==!""&&$sect_id==""&&$stud_id=="") {
	
	$logs = $con->getData("SELECT DISTINCT date(logb_login) date_only, logged_book.stud_id, DATE_FORMAT(logb_login, '%M %d, %Y') date_label, students.f_cour_id, students.stud_year_id, students.stud_sect_id, students.stud_id FROM logged_book LEFT JOIN students ON students.stud_id = logged_book.stud_id WHERE (logb_login BETWEEN '$from' AND '$to') AND (f_cour_id = '$cour_id' AND stud_year_id = '$year_id')");

}
else if ($cour_id==!""&&$year_id==""&&$sect_id==""&&$stud_id=="") {
	
	$logs = $con->getData("SELECT DISTINCT date(logb_login) date_only, logged_book.stud_id, DATE_FORMAT(logb_login, '%M %d, %Y') date_label, students.f_cour_id, students.stud_year_id, students.stud_sect_id, students.stud_id FROM logged_book LEFT JOIN students ON students.stud_id = logged_book.stud_id WHERE (logb_login BETWEEN '$from' AND '$to') AND f_cour_id = '$cour_id'");
	
} else {
	
	$logs = $con->getData("SELECT DISTINCT date(logb_login) date_only, logged_book.stud_id, DATE_FORMAT(logb_login, '%M %d, %Y') date_label, students.f_cour_id, students.stud_year_id, students.stud_sect_id, students.stud_id FROM logged_book LEFT JOIN students ON students.stud_id = logged_book.stud_id WHERE logb_login BETWEEN '$from' AND '$to'");
	
}
// var_dump($logs); exit();

foreach($logs as $key => $log){
	
	$log_id = $log['stud_id'];
	
		$students = $con->getData("SELECT stud_id, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE stud_id = '$log_id'");
			
		foreach($students as $index => $s){
			
		$id = $s['stud_id'];
		$date_ = $log['date_only'];
		
		$attendance = $con->getData("SELECT *, DATE_FORMAT(logb_login, '%l:%i') time_in_out FROM logged_book WHERE date(logb_login) = '$date_' AND stud_id = ".$s['stud_id']);
		
		if(empty($attendance)){
			$attendance = array(
				0=> array("time_in_out"=>"No time in/out"),
				1=> array("time_in_out"=>"No time in/out"),
				2=> array("time_in_out"=>"No time in/out"),
				3=>array("time_in_out"=>"No time in/out")
			);
		}
		
		$students[$index]['attendance'] = $attendance;

	};
	
	$logs[$key]['stud_id'] = $students[0];
};

header("Content-Type: application/json");
echo json_encode($logs);

?>