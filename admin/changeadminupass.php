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
$user1='';
$pass0='';
$user2='';
$pass2='';
$pass1='';
$query="SELECT email, pass FROM users WHERE membership='5' AND user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($user1,$pass0)=mysql_fetch_row($res);

$message='';

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$user1=addslashes_mq($_POST['user1']);
	$user2=addslashes_mq($_POST['user2']);
	$pass1=addslashes_mq($_POST['pass1']);
	$pass2=addslashes_mq($_POST['pass2']);
	$pass0=addslashes_mq($_POST['pass0']);
	if(empty($user2) AND empty($pass1) AND empty($pass2)){
	$message="No information has been entered. Nothing has been changed";
	$user1=$user1;
	$user2=$user2;
	$pass0=$pass0;
	$pass1=$pass1;
	$pass2=$pass2;
	}
	elseif(!empty($pass1) AND (empty($pass2))){
	$message="Please re-enter the password in the confirm password field";
	$user1=$user1;
	$user2=$user2;
	$pass0=$pass0;
	$pass1=$pass1;
	$pass2=$pass2;}

	elseif($pass1!=$pass2){
	$message="Passwords do not match. Please try again";
	$user1=$user1;
	$user2=$user2;
	$pass0=$pass0;
	$pass1=$pass1;
	$pass2=$pass2;
	}

	else {
	if(empty($user2)){$user2=$user1;}
	if(empty($pass1)){$pass1=$pass0;}

		$thepasswordis = md5($pass1);
		$query="UPDATE users SET email='$user2', pass='$thepasswordis' WHERE email='$user1'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$message="Information updated succesfully.";
		$user1=$user2;
		$pass0=$pass1;
		$pass1='';
		$pass2='';
		$user2='';

			}
}

$tpl->set_file('content','admin/changeadminupass.html');
$tpl->set_var('user1',$user1);
$tpl->set_var('user2',$user2);

$tpl->set_var('pass0',$pass0);

$tpl->set_var('pass1',$pass1);
$tpl->set_var('pass2',$pass2);
$content=$tpl->process('out','content');

$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','Change admin email/password');
$tpl->set_var('message',$message);
$tpl->set_var('user1',$user1);
$tpl->set_var('pass0',$pass0);
$tpl->set_var('pass1',$pass1);
$tpl->set_var('pass2',$pass2);
$tpl->set_var('content',$content);
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('relative_path',$relative_path);

//
print $tpl->process('out','frame',0,1);
?>
