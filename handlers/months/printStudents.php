<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

// $datePick = $_POST['pickDate'];

// $newDate = date("Y-m-d ", strtotime($datePick));

// $students = $con->getData("SELECT *, DATE_FORMAT(logb_login, '%M %d, %Y') logb_login FROM logged_book");

$cour_id = isset($_POST['course']['cour_id'])?$_POST['course']['cour_id']:"";
$year_id = isset($_POST['selectyear']['year_id'])?$_POST['selectyear']['year_id']:"";

if($cour_id==""&&$year_id==!"") {
	
	$datas = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE stud_year_id = '$year_id'");

} else if ($cour_id==!""&&$year_id=="")  {
	
	$datas = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE f_cour_id = '$cour_id'");

} else if ($cour_id==!""&&$year_id==!""){
	
	$datas = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE f_cour_id = '$cour_id' AND stud_year_id = '$year_id'");

} else {
	
	$datas = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students");
	
};

header("Content-Type: application/json");
echo json_encode($datas);

?>