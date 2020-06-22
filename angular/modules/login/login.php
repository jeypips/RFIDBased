<?php

date_default_timezone_set('Asia/Manila');

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../../db.php';

$user_name = (isset($_POST['user_name']))?$_POST['user_name']:"";
$user_password = (isset($_POST['user_password']))?$_POST['user_password']:"";

$con = new pdo_db();
$sql = "SELECT user_id FROM users WHERE user_name = '$user_name' AND user_password = '$user_password'";
$account = $con->getData($sql);

if (($con->rows) > 0) {
	session_start();
	$_SESSION['user_id'] = $account[0]['user_id'];
	echo json_encode(array("login"=>true));
} else {
	echo json_encode(array("login"=>false));
}

$con->table = "logs";

$log = $con->insertData(array("users_id"=>$account[0]['user_id'],"description"=>"Logged in"));

?>