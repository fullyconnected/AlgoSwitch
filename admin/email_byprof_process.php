<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;
$professionscount = '';
db_connect();
check_login_member();

set_time_limit(0);
 $tpl = new phemplate(_TPLPATH_,'remove_nonjs');
$headers="From: ".get_site_option('mailfrom');

$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$subject=addslashes_mq($_POST['subject']);
	$body=unix2dos(addslashes_mq($_POST['body']));
	$action=0;
	if (isset($_POST['act']) && !empty($_POST['act'])) {
		$action=addslashes_mq($_POST['act']);
	}
	$sendto=array();
	if ($action==-3) {
		$email=addslashes_mq($_POST['email']);
		$sendto[]=$email;
	} elseif ($action=-2) {
		//$name=addslashes_mq($_POST['name']);
		$action=addslashes_mq($_POST['act']);

		$query="SELECT email FROM users WHERE profession='$action'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res)) {
			while ($rsrow=mysql_fetch_row($res)) {
				$sendto[]=$rsrow[0];

			}
		}
	} elseif ($action==-1) {
		$query="SELECT email FROM users WHERE status="._STATUSNOTACTIVE_;
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res)) {
			while ($rsrow=mysql_fetch_row($res)) {
				$sendto[]=$rsrow[0];
			}
		}
	} elseif ($action==-4) {
		$query="SELECT email FROM users";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res)) {
			while ($rsrow=mysql_fetch_row($res)) {
				$sendto[]=$rsrow[0];
			}
		}
		// count professions 		
		$query="SELECT pid FROM profile_types";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$professionscount = mysql_num_rows($res);

	} elseif ($action>=1 && $action<=$professionscount) {


		$query="SELECT email FROM users WHERE profession='$action' ORDER BY user_id";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res)) {
			while ($rsrow=mysql_fetch_row($res)) {
				$sendto[]=$rsrow[0];
			}
		}
	}
	$totalusers=count($sendto);


	if ($totalusers<10) {
		foreach ($sendto as $email) {
			mail($email,$subject,$body,$headers);
		}
	} else {
		$piece=(int)($totalusers/10);
		for ($i=0;$i<$piece;$i++) {
			$headers="From: ".get_site_option('mailfrom')."\r\n";
			$headers.="Bcc: ";
			$recipients=array();
			for ($j=0;$j<10;$j++) {
				$recipients[]=$sendto[$i*10+$j];
			}
			$headers.=(join(',',$recipients));
			$headers.="\r\n";
			mail(_SITENAME_." member",$subject,$body,$headers);
		}
	}
	$topass['message']="Email sent to $totalusers members.";
}
redirect2page('admin/controlpanel.php',$topass);
?>
