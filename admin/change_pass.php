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
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$user=addslashes_mq($_POST['user']);
	$pass1=addslashes_mq($_POST['pass1']);
	$pass2=addslashes_mq($_POST['pass2']);
	if ($pass1==$pass2) {
		$passwordis = md5($pass1);
		$query="UPDATE users SET pass='$passwordis' WHERE user_id='$user'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$message="Password updated succesfully.";
	} else {
		$message="Passwords do not match. Please try again.";
	}
}

$tpl->set_file('content','admin/change_pass.html');
$content=$tpl->process('out','content');

$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','Change user password');
$tpl->set_var('message',$message);
$tpl->set_var('content',$content);
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('relative_path',$relative_path);

//
print $tpl->process('out','frame',0,1);
?>