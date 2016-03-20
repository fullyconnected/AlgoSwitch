<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();

$message='';
$user_id=addslashes_mq($_POST['user_id']);
$status=addslashes_mq($_POST['status']);
$membership=addslashes_mq($_POST['membership']);
if ($status==1) {
	$status=_STATUSACTIVE_;
	$textstatus="Status: Active account;";
} elseif ($status==2) {
	$status=_STATUSNOTACTIVE_;
	$textstatus="Status: Not active account;";
} elseif ($status==3) {
	$status=_STATUSSUSPENDED_;
	$textstatus="Status: Suspended account;";
}
if ($membership==1) {
	$membership=_REGISTERLEVEL_;
	$textmembership="Access level: Free member";
} elseif ($membership==2) {
	$membership=_SUBSCRIBERLEVEL_;
	$textmembership="Access level: Paying member";
} elseif ($membership==5) {
	$membership=_ADMINLEVEL_;
	$textmembership="Access level: Administrator";
} elseif ($membership==4) {
	$membership=_ADMINFORUM_;
	$textmembership="Access level: Forum Administrator";
}
if($user_id!=1){
$query="UPDATE users SET status='$status',membership='$membership' WHERE user_id='$user_id'";
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
send_newstatus_alert($user_id,$textstatus." ".$textmembership);
$message="User changed!";
$topass['message']=$message;
redirect2page("admin/manage_member.php?user_id=$user_id",$topass);
}
redirect2page("admin/manage_member.php?user_id=$user_id",$topass);

?>
