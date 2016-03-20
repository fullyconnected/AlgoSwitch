<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
$access_level=$access_matrix['mail_send'][0];

db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();

if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}
global $relative_path;


$approved = is_approved($_SESSION['user_id']);
if ($approved == 0){

$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ACCOUNT_NOT_APPROVED']."</font></div>";
$topass['message']=$message;
$nextstep="members.php";
redirect2page($nextstep,$topass);
}

if(isset($_SESSION['user_id'])){
$query="SELECT profession,membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession,$mymembership)=mysql_fetch_row($res);
}


$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());

$tpl->set_var('relative_path', $relative_path);


if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));
$subject='';
$body='';
$to='';
if (isset($topass['action'])) {
	$mail_id=$topass['mail_id'];


	$query="SELECT b.user,a.subject,a.body,DATE_FORMAT(a.date_sent,'%m-%d-%Y') FROM mail_inbox a,users b WHERE a.from_id=b.user_id AND a.mail_id='$mail_id' AND a.user_id='".$_SESSION['user_id']."'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		list($from,$subject,$body,$date_sent)=mysql_fetch_row($res);
		if ($topass['action']=='reply') {
			$subject="Re: $subject";
			$body="On $date_sent, $from wrote:\n".$body;
			$to=$from;
		}
		if ($topass['action']=='forward') {
			$subject="$subject (fwd)";
			$body="On $date_sent, $from wrote:\n".$body;
			$to='';
		}
	}
} elseif (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
	$to=get_name(addslashes_mq($_GET['user_id']));
	$subject='';
	$body='';
}
if (isset($topass['to'])) {
	$to=$topass['to'];
}
if (isset($topass['subject'])) {
	$subject=$topass['subject'];
}
if (isset($topass['body'])) {
	$body=$topass['body'];
}

$tpl->set_file('middlecontent','mailbox/mail_send.html');
$tpl->set_var('message',$message);
$tpl->set_var('to',stripslashes($to));
$tpl->set_var('subject',stripslashes($subject));
$tpl->set_var('body',stripslashes($body));
$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,1);

$title="Send A Message";
include('blocks/block_main_frame.php');
?>
