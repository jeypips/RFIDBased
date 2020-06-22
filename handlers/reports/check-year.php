<?php

date_default_timezone_set('Asia/Manila');

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$sections = $con->getData("SELECT * FROM section");

echo json_encode($sections);

?>