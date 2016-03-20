<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();
$mailfrom = get_site_option('mailfrom');
define('_USE_QUEUE_',1);
define('_MAILFROM_',$mailfrom);
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
	} elseif ($action==-2) {
		$name=addslashes_mq($_POST['name']);
		$query="SELECT email FROM users WHERE user='$name'";
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
		
		} elseif ($action==-5) {
//$query="SELECT email FROM users a, user_album2 b WHERE a.user_id=b.fk_user_id AND b.picture_number ='1'";
//$query="SELECT email FROM users WHERE user_id not in (SELECT fk_user_id FROM user_album2) AND status ='2' ";

$query="SELECT u.email FROM `users` u LEFT JOIN  `user_album2` ua ON ua.fk_user_id = u.user_id WHERE  ua.fk_user_id IS NULL AND status = '2'";

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
	} elseif ($action>=1 && $action<=count($accepted_genders)) {
		$query="SELECT email FROM users WHERE gender='$action' ORDER BY user_id";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res)) {
			while ($rsrow=mysql_fetch_row($res)) {
				$sendto[]=$rsrow[0];
			}
		}
	}
	$totalusers=count($sendto);
	if (defined('_USE_QUEUE_') && _USE_QUEUE_==1 && $totalusers>10) {
				$insert="INSERT INTO mail_queue (mail_type,mail_from,mail_to,subject,body) VALUES ";
				$values='';
				$j=0;
				$k=0;
				for ($i=0;$i<count($sendto);$i++) {
					if ($j==100) {
						$query=$insert.$values;
						$query=substr($query,0,-1);
						if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
						$values='';
						$j=0;
						$k++;
	#print "$query<br>";
					}
					$values.="(1,'"._MAILFROM_."','".$sendto[$i]."','$subject','$body'),";
					$j++;
				}
				if (!empty($values)) {
					$query=$insert.$values;
					$query=substr($query,0,-1);
	#print "lastq: $query<br>";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					$k++;
				}
				$topass['message']="$totalusers emails queued for delivery split in $k queries.";
		} else {
				if ($totalusers<10) {
					foreach ($sendto as $email) {
						mail($email,$subject,$body,$headers);
					}
				}
				else {
					$piece=(int)($totalusers/10);
						for ($i=0;$i<$piece;$i++) {
							$headers="From: ".get_site_option('mailfrom')."\r\n";
							$headers.="Bcc: ";
							$recipients=array();

							$headers.=(join(',',$recipients));
							$headers.="\r\n";
							mail(_SITENAME_." member",$subject,$body,$headers);
						}
				}
		$topass['message']="Email sent to $totalusers members.";
	}
}
redirect2page('admin/controlpanel.php',$topass);
?>
