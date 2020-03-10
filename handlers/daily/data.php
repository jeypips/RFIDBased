<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

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
foreach ($_courses as $ci => $course) {

	$content = [];
	
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
		
		$h = str_pad($h,5,"0",STR_PAD_LEFT);
		
		$start_h = date("Y-m-d ").$h.":00";
		$end_h = date("Y-m-d H:i:s",strtotime("+1 Hour",strtotime($start_h)));

		$logs = $con->getData("SELECT * FROM logged_book WHERE logb_login >= '$start_h' AND logb_login < '$end_h'");

		// var_dump($logs);

		$content[] = array(
			"d"=>count($logs)
		);

	};

	$courses[] = $content;

};

$daily = array(
	"header"=>$header,
	"courses"=>$courses
);

header("Content-Type: application/json");
echo json_encode($daily);

?>