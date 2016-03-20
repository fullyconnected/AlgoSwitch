<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();

 $tpl = new phemplate(_TPLPATH_,'remove_nonjs');
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$what=addslashes_mq($_POST['what']);
	if ($what==-1) {
		$inactive_days=intval($_POST['inactive_days']);
		$query="SELECT user_id FROM users WHERE last_visit<now()-INTERVAL $inactive_days DAY";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$iusers=array();
		for ($i=0;$i<mysql_num_rows($res);$i++) {
			$iusers[$i]=mysql_result($res,$i,0);
		}
		$inactive_users=join("','",$iusers);
		$query="DELETE FROM mail_inbox WHERE user_id IN ('$inactive_users')";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$total=count($iusers);
		$message="$total inboxes cleaned.";
	} elseif ($what==-2) {
		$query="DELETE FROM mail_inbox";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$message="All inboxes cleaned.";
	} elseif ($what>=1) {
		$query="SELECT user_id FROM users WHERE gender='$what'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$gusers=array();
		for ($i=0;$i<mysql_num_rows($res);$i++) {
			$gusers[$i]=mysql_result($res,$i,0);
		}
		$gender_users=join("','",$gusers);
		$query="DELETE FROM mail_inbox WHERE user_id IN ('$gender_users')";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$total=count($gusers);
		$message="$total inboxes cleaned.";
	}
	$content="";
} else {
	$tpl->set_file('content','admin/clean_inboxes.html');
	$genders=array();
	$i=0;
	while (list($k,$v)=each($accepted_genders)) {
		if ($k>0) {
			$genders[$i]['gender_id']=$k;
			$genders[$i]['gender_name']=$v;
		}
		$i++;
	}
	$tpl->set_loop('genders',$genders);
	$content=$tpl->process('out','content',1);
	$message="";
}
$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title',"Clean inboxes");
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('message',$message);
$tpl->set_var('content',$content);
$tpl->set_var('relative_path',$relative_path);

//
print $tpl->process('out','frame',0,1);
?>