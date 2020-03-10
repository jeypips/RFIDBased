<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$students = $con->getData("SELECT count(*) no_students FROM students");
$logged_book = $con->getData("SELECT count(*) no_logged_book FROM logged_book");
$users = $con->getData("SELECT count(*) no_users FROM users");
$courses = $con->getData("SELECT count(*) no_courses FROM course");

$dashboard = array(
	"no_students"=>(count($students))?$students[0]['no_students']:0,
	"no_logged_book"=>(count($logged_book))?$logged_book[0]['no_logged_book']:0,
	"no_users"=>(count($users))?$users[0]['no_users']:0,
	"no_courses"=>(count($courses))?$courses[0]['no_courses']:0,
);

echo json_encode($dashboard);

?>