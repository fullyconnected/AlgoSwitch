#!/usr/local/bin/php -q
<?php //  sends emails from admin.  Set your cron up to run this every hour or so...
ini_set('include_path','.');
require(dirname(__FILE__).'/../includes/templates.inc.php');
require(dirname(__FILE__).'/../includes/apt_functions.inc.php');
require(dirname(__FILE__).'/../includes/vars.inc.php');
require(dirname(__FILE__).'/../includes/functions.inc.php');
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
set_time_limit(0);

$block_size=50;

$tpl=new phemplate(_TPLPATH_,'remove_nonjs');

$language = _DEFAULT_LANGUAGE_;


$tpl->set_file('alertmail','emails/'.$language.'/alert_email.txt');
$tpl->set_var('sitename',_SITENAME_);
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('contactusmail',get_site_option('mailcontactus'));

// messages first
$i=0;
do {
	$query="SELECT get_lock('mail_queue',10)";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_result($res,0,0)==1) {
		$query="SELECT mail_id,mail_from,mail_to,subject,body FROM mail_queue WHERE mail_type=2 LIMIT $block_size";
		if (!($res=@mysql_query($query))) {die(mysql_error());}
		$query="INSERT INTO inbox (read_status,user_id,from_id,subject,body,date_sent,approved) VALUES ";
		$sendto=array();
		$mail_ids=array();
		$i=0;
		while ($rsrow=mysql_fetch_row($res)) {
			$query.="(0,'".$rsrow[2]."','".$rsrow[1]."','".$rsrow[3]."','".$rsrow[4]."',now(),1),";
			$sendto[]=$rsrow[2];
			$mail_ids[]=$rsrow[0];
			$i++;
		}
		$query=substr($query,0,-1);
		if (!empty($sendto)) {
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
		if (!empty($mail_ids)) {
			$query="DELETE FROM mail_queue WHERE mail_id IN ('".join("','",$mail_ids)."')";
			if (!($res=@mysql_query($query))) {die(mysql_error());}
		}
		$query="SELECT release_lock('mail_queue')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (!empty($sendto)) {
			$query="SELECT a.first_name,a.email FROM users a,user_preferences b WHERE a.user_id=b.fk_user_id AND b.email_send_alerts=1 AND a.user_id IN ('".join("','",$sendto)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			for ($i=0;$i<mysql_num_rows($res);$i++) {
				$tpl->set_var('to',mysql_result($res,$i,0));
				$body=$tpl->process('','alertmail');
				$mailfrom=get_site_option('mailfrom');
				send_email($mailfron,mysql_result($res,$i,1),'New message at '._SITENAME_,$body,true);
			}
		}
	}
} while (!empty($i));

// now emails
$i=0;
do {
	$query="SELECT get_lock('mail_queue',10)";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_result($res,0,0)==1) {
		$query="SELECT mail_id,mail_from,mail_to,subject,body FROM mail_queue WHERE mail_type=1 LIMIT $block_size";
		if (!($res=@mysql_query($query))) {die(mysql_error());}
		$mail_ids=array();
		$i=0;
		while ($rsrow=mysql_fetch_row($res)) {
			send_email($rsrow[1],$rsrow[2],$rsrow[3],$rsrow[4],true);
			$mail_ids[]=$rsrow[0];
			$i++;
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (!empty($mail_ids)) {
			$query="DELETE FROM mail_queue WHERE mail_id IN ('".join("','",$mail_ids)."')";
			if (!($res=@mysql_query($query))) {die(mysql_error());}
		}
		$query="SELECT release_lock('mail_queue')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
} while (!empty($i));
?>
