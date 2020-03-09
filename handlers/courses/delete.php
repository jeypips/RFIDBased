<?php

header("Content-Type: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db("course");

$delete = $con->deleteData(array("cour_id"=>implode(",",$_POST['cour_id'])));	

?>