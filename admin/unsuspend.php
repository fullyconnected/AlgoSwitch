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

$message='';
$content='';
$step=0;
if (isset($_POST['step']) && !empty($_POST['step'])) {
	$step=addslashes_mq($_POST['step']);
}

if ($step==1) {
	$user_id=addslashes_mq($_POST['user_id']);
	$query="UPDATE users SET status="._STATUSACTIVE_." WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error());}
	$message="User unsuspended!";
} else {
	$query="SELECT user_id,user,ELT(status,'Suspended','Not Active','Active'),ELT(membership,'Registered Member','Subscribed Member','Admin') FROM users WHERE status=0";
	if (!($res=mysql_query($query))) {error(mysql_error());}
	if (mysql_num_rows($res)) {
		$searchresult=array();
		$i=0;
		while ($rsrow=mysql_fetch_row($res)) {
			$searchresult[$i]['user_id']=$rsrow[0];
			$searchresult[$i]['username']=$rsrow[1];
		
			$searchresult[$i]['status']=$rsrow[3];
			$searchresult[$i]['membership']=$rsrow[4];
			$i++;
		}
		$tpl->set_file('content','admin/content_unsuspend.html');
		$tpl->set_loop('searchresult',$searchresult);
		$content=$tpl->process('out','content',1);
	} else {
		$message="There are no suspended users. Use this menu only when you have one or more suspended users that you wish to unsuspend.";
	}
}

$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','Unsuspend users');
$tpl->set_var('message',$message);
$tpl->set_var('content',$content);
$tpl->set_var('relative_path',$relative_path);
$tpl->set_var('baseurl',_BASEURL_);

//
print $tpl->process('out','frame',0,1);
?>