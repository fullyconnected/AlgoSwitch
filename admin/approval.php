<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();

$user_id=intval($_REQUEST['user_id']);
$action=intval($_REQUEST['action']);
if ($action==1) {
	$query="UPDATE users SET is_approved=1 WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	$email=get_email($user_id);
	$headers="From: ".get_site_option('mailfrom')."\r\n";
	
	if(empty($_SESSION['lang'])){
		$thelang = 'en';
	}else{
		
	$thelang = $_SESSION['lang'];
	}
	
	$tpl = new phemplate(_TPLPATH_.get_my_template('emails').'/'.$thelang.'/','remove_nonjs');
	
	
	$tpl->set_file('approval_email','approval_email.txt');
	$tpl->set_var('name',htmlentities(get_name($user_id)));

	$tpl->set_var('sitename',_SITENAME_);
	$tpl->set_var('baseurl',_BASEURL_);
	$body=unix2dos($tpl->process('out','approval_email'));
	@mail($email,"Your profile has been approved!",$body,$headers);
	$message="The user profile has been approved.";
} elseif ($action==2) {
	 $tpl = new phemplate(_TPLPATH_,'remove_nonjs');
	$tpl->set_file('content','admin/approval_reason.html');
	$tpl->set_var('user_id',$user_id);
	$content=$tpl->process('out','content');
	$tpl->set_file('frame','admin/frame.html');
	$tpl->set_var('title',"None Approval");
	$tpl->set_var('baseurl',_BASEURL_);
	$tpl->set_var('message','');
	$tpl->set_var('content',$content);
	$tpl->set_var('relative_path',$relative_path);
	
	//
	print $tpl->process('out','frame',0,1);
	die();
} elseif ($action==3) {
	$reason=addslashes_mq($_POST['reason']);
	$email=get_email($user_id);
	$headers="From: ".get_site_option('mailfrom')."\r\n";
	@mail($email,"Your profile has not been approved",$reason,$headers);
	$message="Message sent!";
}
$topass['message']=$message;
redirect2page("admin/profile_view.php?user_id=$user_id",$topass);
?>