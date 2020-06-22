<?php

date_default_timezone_set('Asia/Manila');

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$cour_id = isset($_POST['course']['cour_id'])?$_POST['course']['cour_id']:"";
$year_id = isset($_POST['grade']['year_id'])?$_POST['grade']['year_id']:"";
$sect_id = isset($_POST['section']['sect_id'])?$_POST['section']['sect_id']:"";
$stud_id = isset($_POST['name']['stud_id'])?$_POST['name']['stud_id']:"";

$where = "";

if ( (isset($_POST['year'])) && (isset($_POST['month'])) ) {

	$year = ($_POST['year']=="")?"":$_POST['year'];
	$month = $_POST['month']['month'];

	$year_month = "'%$year-$month-%'";
	
	if ($month == "-") $year_month = "'$year-%'";

	// $w = " WHERE logb_login LIKE $year_month";
	
	if($cour_id==!""&&$year_id==!""&&$sect_id==!""&&$stud_id==!"") {
		
		$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname, DATE_FORMAT(date_added, '%M %d, %Y') date_added FROM students WHERE stud_id = '$stud_id' AND (date_added LIKE $year_month AND f_cour_id = '$cour_id') AND (stud_year_id = '$year_id' AND stud_sect_id = '$sect_id')");

	} else if ($cour_id==!""&&$year_id==!""&&$sect_id==""&&$stud_id=="")  {
		
		$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname, DATE_FORMAT(date_added, '%M %d, %Y') date_added FROM students WHERE (stud_year_id = '$year_id' AND f_cour_id = '$cour_id') AND date_added LIKE $year_month");

	}else if ($cour_id==!""&&$year_id==""&&$sect_id==""&&$stud_id==""){

		$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname, DATE_FORMAT(date_added, '%M %d, %Y') date_added FROM students WHERE f_cour_id = '$cour_id' AND date_added LIKE $year_month");

	}else if ($cour_id==""&&$year_id==""&&$sect_id==""&&$stud_id==!""){

		$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname, DATE_FORMAT(date_added, '%M %d, %Y') date_added FROM students WHERE stud_id = '$stud_id' AND date_added LIKE $year_month");

	} else if ($cour_id==""&&$year_id==!""&&$sect_id==""&&$stud_id==""){

		$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname, DATE_FORMAT(date_added, '%M %d, %Y') date_added FROM students WHERE stud_year_id = '$year_id' AND date_added LIKE $year_month");

	} else {
		
		$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname, DATE_FORMAT(date_added, '%M %d, %Y') date_added FROM students WHERE date_added LIKE $year_month");
	}

};


// $overall = $con->getData("SELECT COUNT(*) count FROM logged_book".$w);

foreach($students as $key => $s){
		
	$section = $con->getData("SELECT * FROM section WHERE sect_id = ".$s['stud_sect_id']);
	$students[$key]['stud_sect_id'] = $section[0];
	
	$year = $con->getData("SELECT * FROM year WHERE year_id = ".$s['stud_year_id']);
	$students[$key]['stud_year_id'] = $year[0];
	
};

$con->table = "logs";

$log = $con->insertData(array("users_id"=>$_SESSION['user_id'],"description"=>"Print Monthly Report"));

header("Content-Type: application/json");
echo json_encode($students);

?>