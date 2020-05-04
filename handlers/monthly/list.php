<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$cour_id = isset($_POST['course']['cour_id'])?$_POST['course']['cour_id']:"";
$year_id = isset($_POST['grade']['year_id'])?$_POST['grade']['year_id']:"";
$sect_id = isset($_POST['section']['sect_id'])?$_POST['section']['sect_id']:"";
$stud_id = isset($_POST['name']['stud_id'])?$_POST['name']['stud_id']:"";

$pickyear = $_POST['year'];

$pickmonth = "";
if($_POST['month']['month']=="-") $pickmonth="05";
else $pickmonth=$_POST['month']['month'];
	
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

$workdays = array();
$days = array();
$type = CAL_GREGORIAN;
// $month = date('n'); // Month ID, 1 through to 12.
// $year = date('Y'); // Year in 4 digit 2009 format.
$day_count = cal_days_in_month($type, $pickmonth, $pickyear); // Get the amount of days

//loop through all days
for ($i = 1; $i <= $day_count; $i++) {

        $date = $pickyear.'-'.$pickmonth.'-0'.$i; //format date
        $get_name = date('l', strtotime($date)); //get week day
        $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
		
        //if not a weekend add day to array
        if($day_name != 'Sun' && $day_name != 'Sat'){
            $workdays[] = $date;
            $days[] = $i;
        }

}

if ( (isset($_POST['year'])) && (isset($_POST['month'])) ) {

	$year = ($_POST['year']=="")?"":$_POST['year'];
	$month = $_POST['month']['month'];

	$year_month = "'%$year-$month-%'";
	
	if ($month == "-") $year_month = "'$year-%'";

	if($cour_id==!""&&$year_id==!""&&$sect_id==!""&&$stud_id==!"") {
		
		$checks = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname, DATE_FORMAT(date_added, '%M %d, %Y') date_added FROM students WHERE stud_id = '$stud_id' AND (date_added LIKE $year_month AND f_cour_id = '$cour_id') AND (stud_year_id = '$year_id' AND stud_sect_id = '$sect_id')");

	} else if ($cour_id==!""&&$year_id==!""&&$sect_id==""&&$stud_id=="")  {
		
		$checks = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname, DATE_FORMAT(date_added, '%M %d, %Y') date_added FROM students WHERE (stud_year_id = '$year_id' AND f_cour_id = '$cour_id') AND date_added LIKE $year_month");

	}else if ($cour_id==!""&&$year_id==""&&$sect_id==""&&$stud_id==""){

		$checks = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname, DATE_FORMAT(date_added, '%M %d, %Y') date_added FROM students WHERE f_cour_id = '$cour_id' AND date_added LIKE $year_month");

	}else if ($cour_id==""&&$year_id==""&&$sect_id==""&&$stud_id==!""){

		$checks = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname, DATE_FORMAT(date_added, '%M %d, %Y') date_added FROM students WHERE stud_id = '$stud_id' AND date_added LIKE $year_month");

	} else if ($cour_id==""&&$year_id==!""&&$sect_id==""&&$stud_id==""){

		$checks = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname, DATE_FORMAT(date_added, '%M %d, %Y') date_added FROM students WHERE stud_year_id = '$year_id' AND date_added LIKE $year_month");

	} else {
		
		$checks = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname, DATE_FORMAT(date_added, '%M %d, %Y') date_added FROM students WHERE date_added LIKE $year_month");
	}

};

foreach($checks as $key => $value){
	
	$checks[$key]['dates'] = $workdays;
	
	$id = $value['stud_id'];
	
	foreach($checks[$key]['dates'] as $i => $v){
		
		$sam = $v;
		
		$logs = $con->getData("SELECT DISTINCT DATE_FORMAT(logb_login, '%Y-%m-%d') logb_login FROM logged_book WHERE stud_id = '$id' AND date(logb_login) = '$sam'");
		
			foreach($logs as $index => $val){
				
				if($logs[$index]==[]) {
					
					$logs[$index]['checked'] = "";
					
				} else {
					
					$logs[$index]['checked'] = "fa fa-check text-success";
					
				}
				
			};
		
		$checks[$key]['logs'][$i] = $logs;
		
	}
	
};

$data = array(
	"students"=>$students,
	"days"=>$days,
	"checks"=>$checks
	/* "total"=> array(
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
		"december"=>$data_december,
		"overall"=>$overall[0]
	) */
);

header("Content-Type: application/json");
echo json_encode($data);

?>