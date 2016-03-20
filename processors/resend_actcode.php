<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");

db_connect();

$topass=array();
$user_id=addslashes_mq($_GET['user_id']);
$query="SELECT user,email,access_key,user,pass,user_id FROM users WHERE user_id='$user_id'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
if (mysql_num_rows($res)) {
	$rsrow=mysql_fetch_row($res);
	send_activation_code($rsrow[5],$rsrow[0],$rsrow[1],$rsrow[2],$rsrow[3],$rsrow[4]);
	$message=$lang['ACTIVATE_SENT'];
} else {
	$message=$lang['ACTIVATE_ERROR_1'];
}
$topass['message']=$message;
redirect2page('registration2.php',$topass);
?>