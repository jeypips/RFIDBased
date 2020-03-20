<?php

date_default_timezone_set('Asia/Manila');

header("Content-Type: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);

include_once '../db.php';

$con = new pdo_db("logged_book");

$rfID = $_POST['stud_RFID'];

$sqlResult = $con->getData("SELECT * FROM students WHERE stud_RFID = $rfID");

$exists = false;
$name = "";
$photo = "";
$course = "";
$year_section = "";
$datetime = "";
$time = "";
$p_cp = "";

if (count($sqlResult)) {
	//rfid exist
	$studID = $sqlResult[0]['stud_id'];
	$timeNow = date("Y-m-d H:i:s");
	
	$student_log = array(
		"stud_id"=>$studID,
		"logb_login"=>$timeNow
	);
	
	$sql = "SELECT * FROM logged_book WHERE stud_id = ".$sqlResult[0]['stud_id']." AND logb_login LIKE '".date("Y-m-d")."%'";
	$getLogs = $con->getData($sql);
	$c_logs = count($getLogs);
	
	if ($c_logs<4) $con->insertData($student_log);
	
	$exists = true;
	
	$name = $sqlResult[0]['stud_fName']." ".$sqlResult[0]['stud_mName']." ".$sqlResult[0]['stud_lName'];

	$photo = (file_exists("../".$sqlResult[0]['stud_photo']))?$sqlResult[0]['stud_photo']:"pictures/avatar.png";

	$p_cp = $sqlResult[0]['parent_contact_no'];

	$courseDescription = $con->getData("SELECT cour_description FROM course WHERE cour_id = ".$sqlResult[0]['f_cour_id']);
	$course = $courseDescription[0]['cour_description'];

	$yearDescription = $con->getData("SELECT year_description FROM year WHERE year_id = ".$sqlResult[0]['stud_year_id']);	
	
	$sectDescription = $con->getData("SELECT sect_description FROM section WHERE sect_id = ".$sqlResult[0]['stud_sect_id']);
	
	$year_section = $yearDescription[0]['year_description'];
	
	if (count($sectDescription)) {
		$year_section."-".$sectDescription[0]['sect_description'];
	};
	// $datetime = date("M j, Y h:i:s A",strtotime($timeNow));
	$datetime = date("Y-m-d h:i:s A",strtotime($timeNow));
	$time = date("h:i:s A",strtotime($timeNow));	
	
}

$response = array(
	"exists"=>$exists,
	"name"=>$name,
	"photo"=>$photo,
	"course"=>$course,
	"year_section"=>$year_section,
	"datetime"=>$datetime,
	"time"=>$time,
	"p_cp"=>$p_cp
);

if (count($sqlResult)) {
	
	// Count Today's logs
	$sql = "SELECT * FROM logged_book WHERE stud_id = ".$sqlResult[0]['stud_id']." AND logb_login LIKE '".date("Y-m-d")."%'";
	$getLogs = $con->getData($sql);
	
	$c_logs = count($getLogs);
	
	$response['sms'] = generateSms($response,(($c_logs%2)==0));
	$response['send'] = ($c_logs<4)?true:false;
	$response['logout'] = (($c_logs%2)==0);
	$response['error'] = "";
	
	if ($response['p_cp'] == "") {
		$response['send'] = false;
		$response['error'] = "No mobile number specified, SMS message was not sent";
	};
	
	$response['connected'] = is_connected();
};

function generateSms($data,$mode) {

	$gate = ($mode==1)?"exit":"entrance";

	$msg = "SNHS | ".$data['name']." has passed the $gate gate at ".$data['datetime'];

	$pre = "63";
	$cp_no = $data['p_cp'];
	if (strlen($cp_no)==11) {
		$cp_no = $pre.substr($cp_no,1);
	};
    $destination = urlencode($cp_no);
    $message = $msg;
    $message = html_entity_decode($message, ENT_QUOTES, 'utf-8');
    $message = urlencode($message);
      
    $username = urlencode("sly");
    $password = urlencode("b@DQq7xFxYKB2NL");
    $sender_id = urlencode("ACLS");
    $type = 1;

    $fp = "https://www.isms.com.my/isms_send.php";
    $fp .= "?un=$username&pwd=$password&dstno=$destination&msg=$message&type=$type&sendid=$sender_id&agreedterm=YES";
      
	return $fp;
	
};

function is_connected()
{
    $connected = @fsockopen("www.google.com", 80); 
    //website, port  (try 80 or 443)
    if ($connected){
        $is_conn = true; //action when connected
        fclose($connected);
    }else{
        $is_conn = false; //action in connection failure
    }
    return $is_conn;

}

echo json_encode($response);

?>