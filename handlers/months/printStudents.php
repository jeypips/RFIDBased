<?php

date_default_timezone_set('Asia/Manila');

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

// $datePick = $_POST['pickDate'];

// $newDate = date("Y-m-d ", strtotime($datePick));
$newDate = date("Y-m-d ");

$pickyear = $_POST['year'];
$pickmonth = $_POST['month']['month'];

// var_dump($newDate); exit();

$cour_id = isset($_POST['course']['cour_id'])?$_POST['course']['cour_id']:"";
$year_id = isset($_POST['selectyear']['year_id'])?$_POST['selectyear']['year_id']:"";

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

if($cour_id==""&&$year_id==!"") {
	
	$datas = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE stud_year_id = '$year_id'");

} else if ($cour_id==!""&&$year_id=="")  {
	
	$datas = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE f_cour_id = '$cour_id'");

} else if ($cour_id==!""&&$year_id==!""){
	
	$datas = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students WHERE f_cour_id = '$cour_id' AND stud_year_id = '$year_id'");

} else {
	
	$datas = $con->getData("SELECT *, CONCAT(stud_fName,' ',stud_lName) fullname FROM students");
	
};

foreach($datas as $key => $value){
	
	$datas[$key]['dates'] = $workdays;
	
	$id = $value['stud_id'];
	
	foreach($datas[$key]['dates'] as $i => $v){
		
		$sam = $v;
		
		$logs = $con->getData("SELECT DISTINCT DATE_FORMAT(logb_login, '%Y-%m-%d') logb_login FROM logged_book WHERE stud_id = '$id' AND date(logb_login) = '$sam'");
		
			foreach($logs as $index => $val){
				
				if($logs[$index]==[]) {
					
					$logs[$index]['checked'] = "";
					
				} else {
					
					$logs[$index]['checked'] = "fa fa-check text-success";
					
				}
				
			};
		
		$datas[$key]['logs'][$i] = $logs;
		
	}
	
};

$students = array(

	"days"=>$days,
	"students"=>$datas

);

header("Content-Type: application/json");
echo json_encode($students);

?>