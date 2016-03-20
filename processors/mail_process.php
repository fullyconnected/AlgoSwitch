<?php

session_cache_limiter('nocache');
session_start();
require_once("../includes/functions.inc.php");
require_once("../includes/templates.inc.php");
require_once("../includes/apt_functions.inc.php");
require_once("../includes/vars.inc.php");
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
			
				
$topass=array();
$mailbox='inbox';
if ($_SERVER['REQUEST_METHOD']=='GET') {
	if (isset($_GET['action']) && !empty($_GET['action'])) {
		$error=0;
		$action=addslashes_mq($_GET['action']);
		$mailbox=isset($_GET['mailbox']) ? addslashes_mq($_GET['mailbox']) : '';
		if ($action==2) { // reply to the message
			check_login_member($access_matrix['mail_reply'][0]);
			if (isset($_GET['mail_id']) && !empty($_GET['mail_id'])) {
				$mail_id=addslashes_mq($_GET['mail_id']);
				$topass['mail_id']=$mail_id;
				$topass['action']='reply';
				$topass['mailbox']=$mailbox;
				redirect2page("mail_send.php",$topass);
			}
		} elseif ($action==3) { // forward message
			check_login_member($access_matrix['mail_forward'][0]);
			if (isset($_GET['mail_id']) && !empty($_GET['mail_id'])) {
				$mail_id=addslashes_mq($_GET['mail_id']);
				$topass['mail_id']=$mail_id;
				$topass['action']='forward';
				$topass['mailbox']=$mailbox;
				redirect2page("mail_send.php",$topass);
			}
		} elseif ($action==1) { // delete message
			check_login_member(min($access_matrix['inbox'][0],$access_matrix['outbox'][0],$access_matrix['sendbox'][0]));
			if (isset($_GET['mail_id']) && !empty($_GET['mail_id'])) {
				$mail_id=array(addslashes_mq($_GET['mail_id']));
				delete_messages($_SESSION['user_id'],$mail_id,$mailbox);
				$topass['message']=$_messages['core'][73];
			}
		} elseif ($action==4) { // delete selected messages
			check_login_member(min($access_matrix['inbox'][0],$access_matrix['outbox'][0],$access_matrix['savedbox'][0]));
			if (isset($_GET['del']) && !empty($_GET['del']) && is_array($_GET['del'])) {
				$del=addslashes_mq($_GET['del']);
				delete_messages($_SESSION['user_id'],$del,$mailbox);
				$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['MAIL_PROC_MESS_DELETED']."</font></div>";
			}
		} elseif ($action==5) { // block user
			check_login_member($access_matrix['block_members'][0]);
			if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
				$blocked_id=addslashes_mq($_GET['user_id']);

				if (!is_userblocked($_SESSION['user_id'],$blocked_id)) {
					$doblock =  get_name($blocked_id);
					
					$query="INSERT INTO mail_blocks SET user_id='".$_SESSION['user_id']."',blocked_id='$blocked_id'";

				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				
					
				
					$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['MAIL_PROC_BLOCK_1']." $doblock ".$lang['MAIL_PROC_BLOCK_2']."</font></div>";
				} else {
					$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['MAIL_PROC_BLOCK_3']." $doblock ".$lang['MAIL_PROC_BLOCK_4']."</font></div>";
				}
			}
		} elseif ($action==6) { // unblock user
			check_login_member($access_matrix['block_members'][0]);
			if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
				$blocked_id=addslashes_mq($_GET['user_id']);
				$blockedname=get_name($blocked_id);
				if (is_userblocked($_SESSION['user_id'],$blocked_id)) {
					$query="DELETE FROM mail_blocks WHERE blocked_id='$blocked_id' and user_id='".$_SESSION['user_id']."'";
					$doblock =  get_name($blocked_id);
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
						
$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['MAIL_PROC_BLOCK_5']."$doblock</font></div>";
					redirect2page("mail_blocked.php",$topass);
				} else {
					$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">$doblock ".$lang['MAIL_PROC_BLOCK_6']."</font></div>";
				}
			}
		} elseif ($action==7) { // new message
//			check_login_member($access_matrix['mail_send'][0]);	// don't check now, we'll check on mail_send page.
			redirect2page("mail_send.php");
		} elseif ($action==8) { // save to savedbox
			check_login_member($access_matrix['savedbox'][0]); 
			if (isset($_GET['del']) && !empty($_GET['del']) && is_array($_GET['del'])) {
				$del=addslashes_mq($_GET['del']);
				$from='mail_inbox';
				if ($mailbox=='inbox') {
					$from='mail_inbox';
				} elseif ($mailbox=='outbox') {
					$from='mail_outbox';
				} elseif ($mailbox=='savedbox') {
					$from='mail_savedbox';
				}
				$mails2move=join("','",array_values($del));
				$query="INSERT INTO mail_savedbox (read_status,user_id,from_id,from_name,subject,body,link,date_sent,message_type) SELECT read_status,user_id,from_id,from_name,subject,body,link,date_sent,message_type FROM $from WHERE mail_id IN ('$mails2move') AND user_id='".$_SESSION['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query="DELETE FROM $from WHERE mail_id IN ('$mails2move') AND user_id='".$_SESSION['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['MAIL_PROC_MESS_SAVED']."</font></div>";
			}
		}
	}
}
redirect2page("mailbox.php",$topass,"mailbox=$mailbox");
?>