<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$datePick = $_POST['pickDate'];

$newDate = date("Y-m-d ", strtotime($datePick));

$cour_id = isset($_POST['course']['cour_id'])?$_POST['course']['cour_id']:"";
$year_id = isset($_POST['year']['year_id'])?$_POST['year']['year_id']:"";

$workdays = array();
$type = CAL_GREGORIAN;
$month = date('n'); // Month ID, 1 through to 12.
$year = date('Y'); // Year in 4 digit 2009 format.
$day_count = cal_days_in_month($type, $month, $year); // Get the amount of days

//loop through all days
for ($i = 1; $i <= $day_count; $i++) {

        $date = $year.'/'.$month.'/'.$i; //format date
        $get_name = date('l', strtotime($date)); //get week day
        $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
		
        //if not a weekend add day to array
        if($day_name != 'Sun' && $day_name != 'Sat'){
            $workdays[] = $i;
        }

}

// look at items in the array uncomment the next line
   //print_r($workdays);

// var_dump($workdays); // print all stored date(var_dump is just to check data)
	
// exit();
	
	
$datas = $con->getData("SELECT stud_id, CONCAT(stud_fName,' ',stud_lName) fullname FROM students");

foreach($datas as $key => $value){
	
	$datas[$key]['dates'] = $workdays;
	
	$id = $value['stud_id'];
	
	foreach($datas[$key]['dates'] as $i => $v){
		
		$logs = $con->getData("SELECT DISTINCT DATE_FORMAT(logb_login, '%d') logb_login FROM logged_book WHERE stud_id = '$id' AND DAY(logb_login) = ".$v);
		
			foreach($logs as $index => $val){
				
				if($logs[$index]==[]) {
					
					$logs[$index]['asd'] = "123";
					
				} else {
					
					$logs[$index]['checked'] = "fa fa-check text-success";
					
				}
				
			};
		
		$datas[$key]['logs'][$i] = $logs;
		
	}
	// var_dump($datas); exit();

};

// foreach($workdays as $key => $value){
	
	// var_dump($value); exit();
	
	// $logs = $con->getData("SELECT DISTINCT DATE_FORMAT(logb_login, '%d') logb_login FROM logged_book WHERE DAY(logb_login) = ".$value);
	// $workdays[$key] = $logs;
	
// };
	
$students = array(

	"dates"=>$workdays,
	"students"=>$datas

);
header("Content-Type: application/json");
echo json_encode($students);

?>