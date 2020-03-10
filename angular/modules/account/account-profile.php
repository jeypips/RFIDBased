<?php

require_once '../../../db.php';
// require_once '../../../system_privileges.php';
// require_once '../../../classes.php';

session_start();

if (!isset($_SESSION['user_id'])) header('X-Error-Message: Session timeout', true, 500);

$con = new pdo_db("users");

$account = $con->get(["user_id"=>$_SESSION['user_id']],["name, groups"]);

$avatar = "angular/modules/account/avatar.png";

$session_user_id = $_SESSION['user_id'];

$photo = '../../../pictures/'.$session_user_id.'.jpg';

if (file_exists($photo))
{
	$photo = "pictures/$session_user_id.jpg";
	
} else{
	
	$photo = "angular/modules/account/avatar.png";
	
}

/* 
$con->table = "groups";

$group_privileges = $con->get(array("id"=>$account[0]['groups']),["privileges"]);

$pages_access = [];
if (count($group_privileges)) {
	if ($group_privileges[0]['privileges']!=NULL) {

		$privileges_obj = new privileges(system_privileges,$group_privileges[0]['privileges']);
		$pages_access = $privileges_obj->getPagesPrivileges();

	};
}

$account[0]['pages_access'] = $pages_access;
 */
$profile = array(
	"fullname"=>$account[0]['name'],
	"picture"=>$photo,
	"groups"=>$account[0]['groups'],
	// "pages_access"=>$pages_access,
);

echo json_encode($profile);

?>