<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=$access_matrix['preferences'][0];

db_connect();
check_login_member();

$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$news=((isset($_POST['news'])) ? (mysql_real_escape_string($_POST['news'])) : (0));
	$sendalert=((isset($_POST['sendalert'])) ? (mysql_real_escape_string($_POST['sendalert'])) : (0));
	$eformat=((isset($_POST['eformat'])) ? (mysql_real_escape_string($_POST['eformat'])) : (0));
	$allow_portfolio_comments=((isset($_POST['allow_portfolio_comments'])) ? (mysql_real_escape_string($_POST['allow_portfolio_comments'])) : (0));
	$allow_photo_comments=((isset($_POST['allow_photo_comments'])) ? (mysql_real_escape_string($_POST['allow_photo_comments'])) : (0));
	$allow_ratings=((isset($_POST['allow_ratings'])) ? (mysql_real_escape_string($_POST['allow_ratings'])) : (0));
	$recent_visits=((isset($_POST['recent_visits'])) ? (mysql_real_escape_string($_POST['recent_visits'])) : (0));
	$profilepriv=((isset($_POST['profilepriv'])) ? (mysql_real_escape_string($_POST['profilepriv'])) : (0));


	if(!isset($allow_portfolio_comments) || empty($allow_portfolio_comments)){
		$allow_portfolio_comments="0";
	}
	if(!isset($allow_photo_comments) || empty($allow_photo_comments)){
		$allow_photo_comments="0";
	}
	if(!isset($allow_ratings) || empty($allow_ratings)){
		$allow_ratings="0";
	}
	if(!isset($recent_visits) || empty($recent_visits)){
		$recent_visits="0";
	}
	$query="UPDATE user_preferences SET email_send_news='$news',email_send_alerts='$sendalert',email_format='$eformat',allow_portfolio_comments='$allow_portfolio_comments',allow_photo_comments='$allow_photo_comments',allow_ratings='$allow_ratings',recent_visits='$recent_visits',private_profile='$profilepriv' WHERE fk_user_id='".$_SESSION['user_id']."'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['PREFERENCES_SUCCESSFULLY_SAVED']."</font></div>";
}
redirect2page("preferences.php",$topass);
?>
