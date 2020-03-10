<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$datePick = $_POST['pickDate'];

$newDate = date("Y-m-d ", strtotime($datePick));

// var_dump($newDate); exit();
$header = [];
for ($i = 8; $i < 13; $i++) {
	$header[] = array(
		"h"=>"$i:00"
	);
}

for ($i = 1; $i < 10; $i++) {
	$header[] = array(
		"h"=>"$i:00"
	);
}

$_courses = $con->getData("SELECT * FROM course");

$courses = [];
$total = [];

foreach ($_courses as $ci => $course) {

	$content = [];
	$contentT = [];
	
	$content[] = array(
		"d"=>$course['cour_description']
	);
	
	foreach ($header as $hi => $h) {
		
		$h = $h['h'];
		
		if ($hi>4) {
			
			$ex_h = explode(":",$h);
			$h_no = intval($ex_h[0])+12;
			
			$h = $h_no.":".$ex_h[1];
			
		};
		
		$cour = $course['cour_id'];
		
		$h = str_pad($h,5,"0",STR_PAD_LEFT);
		
		$start_h = $newDate.$h.":00";
		// var_dump($start_h); exit();
		$end_h = date("$newDate H:i:s",strtotime("+1 Hour",strtotime($start_h)));
		// var_dump($end_h); exit();
		
		//courses
		$logs = $con->getData("SELECT * FROM logged_book lb INNER JOIN students s ON s.stud_id = lb.stud_id INNER JOIN course c ON c.cour_id = s.f_cour_id WHERE s.f_cour_id = '$cour' AND logb_login >= '$start_h' AND logb_login < '$end_h'");
		
		$content[] = array(
			"d"=>count($logs)
		);
		
		// footer total
		$footer = $con->getData("SELECT COUNT(*) as countPerHour FROM logged_book lb INNER JOIN students s ON s.stud_id = lb.stud_id INNER JOIN course c ON c.cour_id = s.f_cour_id WHERE lb.logb_login >= '$start_h' AND logb_login < '$end_h'");
	
		$contentT[] = $footer[0];
		
		// subtotal
		$res = $con->getData("SELECT COUNT(*) as loginCountNow FROM logged_book lb INNER JOIN students s ON s.stud_id = lb.stud_id INNER JOIN course c ON c.cour_id = s.f_cour_id WHERE s.f_cour_id = '$cour' AND date(logb_login) = '$newDate'");
		$subtotal = array(
			"total"=>$res[0]['loginCountNow']
		);
		
		// overall total
		$overall = $con->getData("SELECT COUNT(*) as overall FROM logged_book WHERE date(logb_login) = '$newDate'");
			
		
	};
	
	$courses[] = $content;
	$subtotal_t[] = $subtotal;
	$total[] = $contentT;
	
};

$daily = array(
	"header"=>$header,
	"courses"=>$courses,
	"total"=>$total[0],
	"subtotal"=>$subtotal_t,
	"overall"=>$overall[0]['overall']
	
);

header("Content-Type: application/json");
echo json_encode($daily);

?>