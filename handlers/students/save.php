<?php

header("Content-Type: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);

include_once '../../db.php';

$con = new pdo_db("profiles");

session_start();

$_POST['date_of_birth'] = (isset($_POST['date_of_birth']))?date("Y-m-d",strtotime($_POST['date_of_birth'])):NULL;

if($_POST['status']==1) 
{
	$_POST['status'] = "Student";
	
} elseif ($_POST['status']==2){
	
	$_POST['status'] = "Employed";
	
} elseif ($_POST['status']==3) {
	
	$_POST['status'] = "Unemployed";
	
} else {
	
	$_POST['status'] = "N/A";
	
}


if ($_POST['id']) {
	
	$id = $_POST['id'];
	$user = $con->updateObj($_POST,'id');
	
	$con->table = "notifications";
	
	$notification = array(
		"users_id"=>$_SESSION['id'],
		"profiles_id"=>$id,
		"transactions_id"=>null,
		"name"=>"Profiles",
		"description"=>"Profile update"
	);

	$con->insertData($notification);
	
} else {
	
	$user = $con->insertObj($_POST);
	$id = $con->insertId;
	echo $con->insertId;
	
	$con->table = "notifications";
	
	$notification = array(
		"users_id"=>$_SESSION['id'],
		"profiles_id"=>$id,
		"transactions_id"=>null,
		"name"=>"Profiles",
		"description"=>"New Profile added"
	);

	$con->insertData($notification);

}

?>