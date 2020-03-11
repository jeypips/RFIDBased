<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$where = "";

if ( (isset($_POST['year'])) && (isset($_POST['month'])) ) {

	$year = ($_POST['year']=="")?"":$_POST['year'];
	$month = $_POST['month']['month'];

	$year_month = "'%$year-$month-%'";
	if ($month == "-") $year_month = "'$year-%'";

	$where = " WHERE date_added LIKE $year_month";

};

$students = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname, DATE_FORMAT(date_added, '%M %d, %Y') date_added FROM students".$where);

foreach($students as $key => $s){
		
	$section = $con->getData("SELECT * FROM section WHERE sect_id = ".$s['stud_sect_id']);
	$students[$key]['stud_sect_id'] = $section[0];
	
	$year = $con->getData("SELECT * FROM year WHERE year_id = ".$s['stud_year_id']);
	$students[$key]['stud_year_id'] = $year[0];
	
};

header("Content-Type: application/json");
echo json_encode($students);

?>