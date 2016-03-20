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
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());


$tpl->set_var('relative_path', $relative_path);


if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";
}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));
if (!isset($_GET['mail_id']) || empty($_GET['mail_id'])) {
	error("No message selected for viewing.");
} else {
	$mail_id=addslashes_mq($_GET['mail_id']);
}
$query="SELECT b.user,a.from_id,a.subject,a.body,date_format(a.date_sent,'%b-%d-%Y'),a.mail_id,a.link FROM mail_inbox a,users b WHERE a.from_id=b.user_id AND a.mail_id='$mail_id' AND a.user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
if (mysql_num_rows($res)) {
	$rsrow=mysql_fetch_row($res);
}
$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$tpl->set_file('middlecontent','mailbox/mail_read.html');
$tpl->set_var('relative_path',$relative_path);
$tpl->set_var('message',$message);
$tpl->set_var('lang', $lang);
$tpl->set_var('from',stripslashes(isset($rsrow[0])));
$tpl->set_var('from_id',isset($rsrow[1]));
$tpl->set_var('subject',stripslashes(isset($rsrow[2])));



$rsrow[3]=preg_replace("/.PHPSESSID=[0-9a-zA-Z]+/","",isset($rsrow[3])); // why?



$tpl->set_var('body',nl2br(htmlentities(stripslashes($rsrow[3]))));
$tpl->set_var('date_sent',isset($rsrow[4]));
$tpl->set_var('mail_id',isset($rsrow[5]));
$mailbox_read = $lang['MAIL_READ'];
$tpl->set_var('mailbox_read',$mailbox_read);

$query="UPDATE mail_inbox SET read_status=1 WHERE mail_id='$mail_id' AND user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}


$middle_content='';
$mailbox="";
$mail_id="";
$from='mail_inbox';
if (isset($_GET['mail_id']) && !empty($_GET['mail_id'])) {
	$mail_id=addslashes_mq($_GET['mail_id']);
	$mailbox=isset($_GET['mailbox']) ? addslashes_mq($_GET['mailbox']) : '';
	if ($mailbox=='inbox') {
		$from='mail_inbox';
	} elseif ($mailbox=='outbox') {
		$from='mail_outbox';
	} elseif ($mailbox=='savedbox') {
		$from='mail_savedbox';
	}
	if (isset($_GET['move'])) {		// next/previous stuff
		if ($_GET['move']==1) {
			$query="SELECT mail_id FROM $from WHERE mail_id<'$mail_id' AND user_id='".$_SESSION['user_id']."' ORDER BY mail_id ASC LIMIT 1";
		} else {
			$query="SELECT mail_id FROM $from WHERE mail_id>'$mail_id' AND user_id='".$_SESSION['user_id']."' ORDER BY mail_id DESC LIMIT 1";
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$mail_id=mysql_result($res,0,0);
		}
	}
} else {
	trigger_error($messages="No message selected for viewing.");
}
$query="SELECT a.mail_id,a.subject,a.from_id,a.from_name,b.user,date_format(a.date_sent,'%W, %M %d, %Y %I:%i%p'),a.body,a.link FROM $from a LEFT JOIN users b ON a.from_id=b.user_id WHERE a.mail_id='$mail_id' AND a.user_id='".$_SESSION['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	list($mail_id,$subject,$from_id,$from_name,$name,$date_sent,$body,$link)=mysql_fetch_row($res);
	$tpl->set_file('middlecontent','mailbox/mail_read.html');
	$tpl->set_var('from',(!empty($name)) ? htmlentities(stripslashes($name)) : htmlentities(stripslashes($from_name)));
	$tpl->set_var('from_id',$from_id);
	$tpl->set_var('subject',stripslashes($subject));
	$body=nl2br(stripslashes($body));
	$body=str_replace(array(":))",":)",":D",":o)",":O)",";)",":p",":cool:",":x",":((",":(",":angry:",":embr:",":o",":O",":huh:",":lipsr:",":\/",":no:",":yes:",":devil:",":dead:"),array("<img src='images/smilies/cheesy.gif'>","<img src='images/smilies/smiley.gif'>","<img src='images/smilies/grin.gif'>","<img src='images/smilies/laugh.gif'>","<img src='images/smilies/laugh.gif'>","<img src='images/smilies/wink.gif'>","<img src='images/smilies/tongue.gif'>","<img src='images/smilies/cool.gif'>","<img src='images/smilies/kiss.gif'>","<img src='images/smilies/cry.gif'>","<img src='images/smilies/sad.gif'>","<img src='images/smilies/angry.gif'>","<img src='images/smilies/embarassed.gif'>","<img src='images/smilies/shocked.gif'>","<img src='images/smilies/shocked.gif'>","<img src='images/smilies/huh.gif'>","<img src='images/smilies/lipsrsealed.gif'>","<img src='images/smilies/undecided.gif'>","<img src='images/smilies/no.gif'>","<img src='images/smilies/yes.gif'>","<img src='images/smilies/devil.gif'>","<img src='images/smilies/dead.gif'>"),$body);
	$tpl->set_var('body',$body);
	$tpl->set_var('date_sent',$date_sent);
	$tpl->set_var('mail_id',$mail_id);
	$tpl->set_var('mailbox',$mailbox);
	$tpl->set_var('link',$link);
	$middle_content=$tpl->process('','middlecontent',TPL_FINISH | TPL_INCLUDE);
	$query="UPDATE $from SET read_status=1 WHERE mail_id='$mail_id' AND user_id='".$_SESSION['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}



$title="Read Mail";
include('blocks/block_main_frame.php');
?>
