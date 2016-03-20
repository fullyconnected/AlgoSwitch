<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=$access_matrix['change_userpass'][0];

db_connect();
check_login_member();

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$profession=addslashes_mq($_POST['profession'],true);
	$error=false;

	$message="";

	if (empty($profession)) {
	$error=true;
	$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['CHANGE_PROFILE_ERROR_1']."</font></div>";
	}



	if (!$error) {
		if (!empty($profession)) {
			$query="UPDATE users SET profession='$profession' WHERE user_id='".$_SESSION['user_id']."'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['CHANGE_PROFILE_SUCCESS']."</font></div>";
		}

	}
	$topass=array();
	$topass['message']=$message;
	$topass['profession']=$profession;
	$_SESSION['topass']=$topass;
}
redirect2page("change_profile_type.php");
?>