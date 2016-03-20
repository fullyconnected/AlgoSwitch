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
if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$step=0;
if (isset($_POST['step']) && !empty($_POST['step'])) {
	$step=addslashes_mq($_POST['step']);
}

$message='';
if ($step==1) {
	$name=addslashes_mq($_POST['name']);
	if (strlen($name)<3) {
		$message="Search string too short. Enter a search string of at least 3 chars!";
		$tpl->set_file('content','admin/content_suspend_index.html');
		$tpl->set_var('message',$message);
	} else {
		$query="SELECT user_id,user,ELT(status+1,'Suspended','Not Active','Active'),ELT(membership,'Registered Member','Subscribed Member','Admin') FROM users WHERE user LIKE '%$name%' AND status<>0";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res)) {
			$searchresult=array();
			$i=0;
			while ($rsrow=mysql_fetch_row($res)) {
				$searchresult[$i]['user_id']=$rsrow[0];
				
				
				
				
				
				$searchresult[$i]['username']=$rsrow[1];
				$searchresult[$i]['name']=$rsrow[2];
				$searchresult[$i]['status']=$rsrow[3];
				$searchresult[$i]['membership']=$rsrow[4];
				$i++;
			}
			$tpl->set_file('content','admin/content_suspend.html');
			$tpl->set_loop('searchresult',$searchresult);
		} else {
			$tpl->set_file('content','admin/content_suspend_index.html');
			$message="Your search for <b>$name</b> returned no information. Please refine your search criteria and try again.";
			$tpl->set_var('message',$message);
		}
	}
} elseif ($step==2) {
	$user_id=addslashes_mq($_POST['user_id']);
	
	if ($user_id == 1){
				$message="You can not delete Admin";
$topass['message']=$message;
$nextstep="admin/suspend.php";
redirect2page($nextstep,$topass);
}
	
	$query="UPDATE users SET status=0 WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error());}
	$message="User suspended!";
	$tpl->set_file('content','admin/content_suspend_index.html');
	$tpl->set_var('message',$message);
} else {
	$message="";
	$tpl->set_file('content','admin/content_suspend_index.html');
}

$content=$tpl->process('out','content',1);
$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','Suspend users');
$tpl->set_var('content',$content);
$tpl->set_var('relative_path',$relative_path);
$tpl->set_var('message',$message);
$tpl->set_var('baseurl',_BASEURL_);

//
print $tpl->process('out','frame',0,1);
?>