<?php

date_default_timezone_set('Asia/Manila');

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$students = $con->getData("SELECT count(*) no_students FROM students");
$logged_book = $con->getData("SELECT count(*) no_logged_book FROM logged_book");
$users = $con->getData("SELECT count(*) no_users FROM users");
$courses = $con->getData("SELECT count(*) no_courses FROM course");
$sa = $con->getData("SELECT count(*) no_sa FROM users WHERE groups = 'Super Admin'");
$admin = $con->getData("SELECT count(*) no_admin FROM users WHERE groups = 'Admin'");
$teacher = $con->getData("SELECT count(*) no_teacher FROM users WHERE groups = 'Teacher'");
$male = $con->getData("SELECT count(*) no_male FROM students WHERE gender = 'Male'");
$female = $con->getData("SELECT count(*) no_female FROM students WHERE gender = 'Female'");

$dashboard = array(
	"no_students"=>(count($students))?$students[0]['no_students']:0,
	"no_logged_book"=>(count($logged_book))?$logged_book[0]['no_logged_book']:0,
	"no_users"=>(count($users))?$users[0]['no_users']:0,
	"no_courses"=>(count($courses))?$courses[0]['no_courses']:0,
	"no_male"=>(count($male))?$male[0]['no_male']:0,
	"no_female"=>(count($female))?$female[0]['no_female']:0,
	"accounts" => array(
		"sa"=>(count($sa))?$sa[0]['no_sa']:0,
		"admin"=>(count($admin))?$admin[0]['no_admin']:0,
		"teacher"=>(count($teacher))?$teacher[0]['no_teacher']:0,
	)
);

echo json_encode($dashboard);

?>