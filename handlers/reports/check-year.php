<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$sections = $con->getData("SELECT * FROM section");

echo json_encode($sections);

?>