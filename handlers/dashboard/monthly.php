<?php

date_default_timezone_set('Asia/Manila');

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$all_january = "";
$all_february = "";
$all_march = "";
$all_april = "";
$all_may = "";
$all_june = "";
$all_july = "";
$all_august = "";
$all_september = "";
$all_october = "";
$all_november = "";
$all_december = "";

if ((isset($_POST['year']))) {

	$year = ($_POST['year']=="")?"":$_POST['year'];
	
	$january = "'%$year-01-%'";
	$february = "'%$year-02-%'";
	$march = "'%$year-03-%'";
	$april = "'%$year-04-%'";
	$may = "'%$year-05-%'";
	$june = "'%$year-06-%'";
	$july = "'%$year-07-%'";
	$august = "'%$year-08-%'";
	$september = "'%$year-09-%'";
	$october = "'%$year-10-%'";
	$november = "'%$year-11-%'";
	$december = "'%$year-12-%'";
	
	$total_january = " WHERE logb_login LIKE $january";
	$total_february = " WHERE logb_login LIKE $february";
	$total_march = " WHERE logb_login LIKE $march";
	$total_april = " WHERE logb_login LIKE $april";
	$total_may = " WHERE logb_login LIKE $may";
	$total_june = " WHERE logb_login LIKE $june";
	$total_july = " WHERE logb_login LIKE $july";
	$total_august = " WHERE logb_login LIKE $august";
	$total_september = " WHERE logb_login LIKE $september";
	$total_october = " WHERE logb_login LIKE $october";
	$total_november = " WHERE logb_login LIKE $november";
	$total_december = " WHERE logb_login LIKE $december";
	
};

$data_january = $con->getData("SELECT logb_login FROM logged_book".$total_january);
$data_february = $con->getData("SELECT logb_login FROM logged_book".$total_february);
$data_march = $con->getData("SELECT logb_login FROM logged_book".$total_march);
$data_april = $con->getData("SELECT logb_login FROM logged_book".$total_april);
$data_may = $con->getData("SELECT logb_login FROM logged_book".$total_may);
$data_june = $con->getData("SELECT logb_login FROM logged_book".$total_june);
$data_july = $con->getData("SELECT logb_login FROM logged_book".$total_july);
$data_august = $con->getData("SELECT logb_login FROM logged_book".$total_august);
$data_september = $con->getData("SELECT logb_login FROM logged_book".$total_september);
$data_october = $con->getData("SELECT logb_login FROM logged_book".$total_october);
$data_november = $con->getData("SELECT logb_login FROM logged_book".$total_november);
$data_december = $con->getData("SELECT logb_login FROM logged_book".$total_december);

$data = array(
	"total"=> array(
		"january"=>$data_january,
		"february"=>$data_february,
		"march"=>$data_march,
		"april"=>$data_april,
		"may"=>$data_may,
		"june"=>$data_june,
		"july"=>$data_july,
		"august"=>$data_august,
		"september"=>$data_september,
		"october"=>$data_october,
		"november"=>$data_november,
		"december"=>$data_december
	)
);

header("Content-Type: application/json");
echo json_encode($data);

?>