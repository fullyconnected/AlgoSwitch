<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require('../includes/strip_symbols.php');
require('../includes/strip_punctuation.php');
require("../includes/apt_functions.inc.php");
require("../includes/class.inputfilter.php");

$access_level=$access_matrix['change_userpass'][0];


db_connect();
check_login_member();
$filterObj = new InputFilter(NULL, NULL, 0, 0, 1);
$topass=array();
$error=false;

if ($_SERVER['REQUEST_METHOD']=='POST') {

$album_title = mysql_real_escape_string(addslashes_mq($filterObj->process(trim($_REQUEST['album_title']))));
	
if (empty($album_title)) {
		$error=true;
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ALBUMS_POST_ERROR_1']."</font></div>";
	}	
if (!$error) {

$tupload = time();
$query="INSERT INTO user_album_cat SET fk_user_id='".$_SESSION['user_id']."',album_name='$album_title', created_on='$tupload'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ALBUMS_POST_SUCCESS']."</font></div>";
      }
}
$topass=array('message'=>$message);
redirect2page("myprofile_album.php",$topass);
?>
