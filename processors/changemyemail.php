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
	$curemail=addslashes_mq($_POST['currentemail'],true);
	$newemail=addslashes_mq($_POST['newemail'],true);
	$error=false;
	list($dbemail)=get_email($_SESSION['user_id']);
	list($toname)=get_name($_SESSION['user_id']);
	$message="";
	if (empty($newemail)) {
		$error=true;
		$message=$lang['CHANGE_EMAIL_ERROR_1'];
	} elseif (empty($curemail) && (!empty($newemail))) {
		$error=true;
		$message=$lang['CHANGE_EMAIL_ERROR_2'];
	} elseif (!empty($curemail) && (empty($newemail))) {
		$error=true;
		$message=$lang['CHANGE_EMAIL_ERROR_3'];
	} elseif ($newemail == $dbemail) {
		$error=true;
		$message=$lang['CHANGE_EMAIL_ERROR_4'];
	}

	if (!$error) {
		if (!empty($newemail)) {
			$query="UPDATE users SET status='_STATUSSUSPENDED_',email='$newemail' WHERE user_id='".$_SESSION['user_id']."'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			$user_id=$_SESSION['user_id'];
			$to="$newemail";
			$baseurl=_BASEURL_;
			$sitename=_SITENAME_;
			$subject=$lang['CHANGE_EMAIL_SUBJECT'];
			$content=$lang['CHANGE_EMAIL_CONTENT']."\n$baseurl\/processors/revalidate.php?user_id=$user_id\n\n";

			if(send_changeemail_email($to,$subject,$content)) {
			$message.="<br /><font class=alertsmall>".$lang['CHANGE_EMAIL_CHANGE_MESS']."</font>";
} else {$message=$lang['CHANGE_EMAIL_ERROR_5'];
			}
		}
	}
	$topass=array();
	$topass['message']=$message;
	$_SESSION['topass']=$topass;
}
redirect2page("login.php");
?>