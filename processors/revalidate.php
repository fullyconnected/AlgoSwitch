<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_REGISTERLEVEL_;

db_connect();

if ($_SERVER['REQUEST_METHOD']=='GET') {
	$user_id=intval($_GET['user_id']);
	$error=false;
	if (empty($user_id)) {
		$error=true;
		$message=$lang['REVALIDATE_ERROR_1'];
	}

	if (!$error) {
		if (!empty($user_id)) {
			$query="UPDATE users SET status='2' WHERE user_id='$user_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REVALIDATE_SUCESSFUL_SON']."</font></div>";
					}
	}
	$topass=array();
	$topass['message']=$message;
	$_SESSION['topass']=$topass;
}
redirect2page("login.php");
