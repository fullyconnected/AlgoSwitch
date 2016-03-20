<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");

$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$user=addslashes_mq($_POST['username']);
	
	$pass=addslashes_mq($_POST['password']);
	
	if (isset($user) && !empty($user) &&  isset($pass) && !empty($pass)) {
		db_connect();
		$query="SELECT user_id,user,membership,email FROM users WHERE user='$user' OR email='$user' AND pass='$pass'";
		if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res)) {
			list($user_id,$name,$membership)=mysql_fetch_row($res);
			$_SESSION['user_id']=$user_id;
			$_SESSION['name']=$name;
			$_SESSION['membership']=$membership;
			$query="UPDATE users SET last_visit=UNIX_TIMESTAMP(NOW()) WHERE user_id='$user_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			redirect2page('mailbox.php');
		} else {
			$topass['message']="<div>".$lang['RELOGIN_ERROR_1']." <a href=\"forgot_pass.php\">".$lang['RELOGIN_ERROR_2']."</div>";
		}
	} else {
		$topass['message']="<div>".$lang['RELOGIN_ERROR_3']." <a href=\"forgot_pass.php\">".$lang['RELOGIN_ERROR_2']."</div>";
	}
}
redirect2page("login.php",$topass);
?>