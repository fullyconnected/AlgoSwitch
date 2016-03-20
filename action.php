<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");

db_connect();
$domain = _DOMAINURL_;



if (isset($_GET['id']) && empty($_GET['id']))
{

	$topass['message']="No members were found matching your search";
	redirect2page("inform_page.php",$topass);
}

if (isset($_GET['id']) && !empty($_GET['id']) && (!is_numeric($_GET['id'])))
{
	$id=addslashes_mq($_GET['id']);
	$name = get_profile_link_user($id);
	header( "Location: $domain/$name" ) ;
}
if (isset($_GET['id']) && !empty($_GET['id']) && (is_numeric($_GET['id'])))
{
	$id=addslashes_mq($_GET['id']);
	$name = get_profile_link($id);
	//redirect2page($name);
	if (empty($name)) {
		$topass['message']="No members were found matching your criteria";
		redirect2page("inform_page.php",$topass);
	} else {
		header( "Location: $domain/$name" ) ;
	}
}
?>
