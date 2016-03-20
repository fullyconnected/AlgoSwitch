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
  //  $oldpass=addslashes_mq($_POST['oldpass'],true);
	$newpass=addslashes_mq($_POST['newpass'],true);
	$error=false;
	list($dbpass)=get_userpass($_SESSION['user_id']);

	$message="";

	if (empty($newpass)) {
    
		$error=true;
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['CHANGE_PASSWORD_ERROR_1']."</font></div>";
       
       }elseif((strlen($newpass)<3)) {
					$error=true;
					$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['CHANGE_PASSWORD_ERROR_2']."</font></div>";
	}

	if (!$error) {
		
		if (!empty($newpass)) {
			
			$newpass = md5($newpass);
			
			$query="UPDATE users SET pass='$newpass' WHERE user_id='".$_SESSION['user_id']."'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			$message.="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['CHANGE_PASSWORD_SUCCESS']."</font></div>";
		}
	}
	$topass=array();
	$topass['message']=$message;

	$topass['newpass']=$newpass;
	$_SESSION['topass']=$topass;
}
redirect2page("change_userpass.php");
?>