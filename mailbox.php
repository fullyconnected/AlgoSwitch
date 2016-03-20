<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
$access_level=$access_matrix['inbox'][0];

db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();

if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}
global $relative_path;



if(isset($_SESSION['user_id'])){
$query="SELECT profession,membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession,$mymembership)=mysql_fetch_row($res);
}


$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');


$tpl->set_var('relative_path', $relative_path);

$mailbox_name ='';
$header_from = '';

if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";
}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));

$mailbox=isset($_GET['mailbox']) ? addslashes_mq($_GET['mailbox']) : 'inbox';
$access_level=$access_matrix['inbox'][0];
if ($mailbox=='inbox') {
	$from='mail_inbox';
	$mailbox_name=$lang['MAIL_MY_INBOX'];
	$header_from="";

	$access_level=$access_matrix['inbox'][0];
} elseif ($mailbox=='outbox') {
	$from='mail_outbox';
	$mailbox_name=$lang['MAIL_MY_OUTBOX'];
	

	
	$header_from="";

	$access_level=$access_matrix['inbox'][0];
} elseif ($mailbox=='savedbox') {
	$from='mail_savedbox';
	$mailbox_name=$lang['MAIL_MY_SAVED'];
	$header_from="";

	$access_level=$access_matrix['inbox'][0];
}


$offset=(isset($_REQUEST['offset'])) ? (addslashes_mq($_REQUEST['offset'])) : ($offset=0);
$results=(isset($_REQUEST['results']) && !empty($_REQUEST['results'])) ? addslashes_mq($_REQUEST['results']) : ($results=_RESULTS_);

$total_mails=get_total_messages($_SESSION['user_id'],$mailbox);
$new_mails=get_total_messages($_SESSION['user_id'],$mailbox,true);


 
$tpl->set_file('middlecontent','mailbox/mail_inbox.html');


$mail_interface=array();
if (!empty($total_mails)) {
	$mail_interface=messages_tpl_array(get_user_messages($_SESSION['user_id'],$mailbox,true,$offset,$results),$mailbox);
	// dummy vars
	$from="mail_inbox a LEFT JOIN users b ON a.from_id=b.user_id";
	$where="a.user_id='".$_SESSION['user_id']."'";
	$tpl->set_var('pager1',create_pager($from,$where,$offset,$results));
	$tpl->set_var('pager2',create_pager($from,$where,$offset,$results));

} else {
	$tpl->set_var('no_mail_message',"<font class=\"alert\">".$lang['MAIL_NO_MAIL']."</font>");
}

$tpl->set_loop('mail_interface',$mail_interface);
$tpl->set_var('mailbox_name',$mailbox_name);
$tpl->set_var('mailbox',$mailbox);
$tpl->set_var('header_from',$header_from);
$tpl->set_var('message',$message);
$tpl->set_var('messages_in_inbox',$total_mails);
$tpl->set_var('unread_messages',$new_mails);

$tpl->set_var('lang', $lang);
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$middle_content=$tpl->process('','middlecontent',TPL_FINISH | TPL_INCLUDE | TPL_LOOP);

$title=_SITENAME_.' '.$lang['MAIL_MY_MAILBOX'];
include('blocks/block_main_frame.php');
?>