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

header("Content-Type: application/json");
echo json_encode($students);

?>