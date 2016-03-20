<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");

$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$user=addslashes_mq(add_underscore($_POST['username']));
	$pass=addslashes_mq(md5($_POST['password']));
	if (isset($user) && !empty($user) &&  isset($pass) && !empty($pass)) {
		db_connect();
		$query="SELECT user_id,user,membership,birthdate,gender,language FROM users WHERE email='$user' AND pass='$pass'";
		if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res)) {
			list($user_id,$name,$membership,$birthdate,$gender,$language)=mysql_fetch_row($res);
			$_SESSION['user_id']=$user_id;
			$_SESSION['name']=$name;
			$_SESSION['membership']=$membership;
			$_SESSION['age']=$birthdate;
			
			$_SESSION['lang'] = $language;
  			setcookie("lang", $language, time() + (3600 * 24 * 30));
	
			$query="UPDATE users SET last_visit=UNIX_TIMESTAMP(NOW()) WHERE user_id='$user_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
						
			
			redirect2page('mailbox.php');
		} else {
			$topass['message']="<div class=\"alert\">".$lang['LOGIN_MESS_1']." <a href=\"forgot_pass.php\">Lost Password?</a></div>";
		}
	} else {
		$topass['message']="<div class=\"alert\">".$lang['LOGIN_MESS_1']." <a href=\"forgot_pass.php\">".$lang['LOGIN_MESS_2']."</a></div>";
	}
}
redirect2page("login.php",$topass);
?>
