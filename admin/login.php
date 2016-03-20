<?php
session_start();
require('../includes/vars.inc.php');
require("../includes/functions.inc.php");
require('../includes/apt_functions.inc.php');
$access_level=_ADMINLEVEL_;

$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$user=addslashes_mq($_POST['username']);
	$pass=addslashes_mq($_POST['password']);
	if (isset($user) && !empty($user) &&  isset($pass) && !empty($pass)) {
		db_connect();
		$pass = md5($pass);
		$query="SELECT user_id,email,membership,last_visit FROM users WHERE email='$user' and pass='$pass'";
		    if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		    if (mysql_num_rows($res)) {
		      list($user_id,$name,$membership,$llogged)=mysql_fetch_row($res);
		      if ($membership>=$access_level) {
		        $_SESSION['user_id']=$user_id;
		        $_SESSION['name']=$name;
		        $_SESSION['llogged']=$llogged;

		        $query="UPDATE users SET last_visit=now() WHERE user_id='$user_id'";
				if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}

					redirect2page('admin/users/index.php');
			} else {
				$topass['message']='You are not allowed here!';
			}
		} else {
			$topass['message']='Invalid email or password. Please try again!';
		}
	} else {
		$topass['message']='Invalid email or password. Please try again!';
	}
}
redirect2page("admin/index.php",$topass);
?>