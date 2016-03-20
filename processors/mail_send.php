<?php

session_cache_limiter('nocache');
session_start();
require_once("../includes/functions.inc.php");
require_once("../includes/templates.inc.php");
require_once("../includes/apt_functions.inc.php");
require_once("../includes/vars.inc.php");
$access_level=$access_matrix['mail_send'][0];
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);

$topass=array();

	if (isset($_POST['to']) && !empty($_POST['to'])) {
		
		$to_id=get_userid_by_name(mysql_real_escape_string(addslashes_mq($_POST['to'])));
		if (empty($to_id)) {
			$error=true;
			$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['MAIL_PROC_NOT_FOUND']."</font></div>";
			
			
			$topass['to']=$_POST['to'];
			$topass['subject']=$_POST['subject'];
			$topass['body']=$_POST['writehere'];
			
			
			redirect2page("mail_send.php",$topass);
		}
			if (is_userblocked($to_id,$_SESSION['user_id'])) {
			$error=true;
			$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['MAIL_PROC_YOU_ARE_BLOCKED']."</font></div>";
				redirect2page("mail_send.php",$topass);
		}
		
		

	//$subject="";
	//$body="";
	if (isset($_POST['subject']) && !empty($_POST['subject'])) {
		if ((strpos($_POST['subject'],"\r")!==false) || (strpos($_POST['subject'],"\n")!==false)) {
							    // dont send the email and show an error message
							    $error=true;
							    $topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['MAIL_PROC_SUBJECT_ERROR']."</font></div>";
		redirect2page("mail_send.php",$topass);
		}
		$subject=mysql_real_escape_string(addslashes_mq($_POST['subject']));
		if (empty($subject)) {
			$error=true;
			$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['MAIL_PROC_SUBJECT_NULL']."</font></div>";
		}
	} else {
		$subject=$_messages['core'][116];
	}
	if (isset($_POST['writehere']) && !empty($_POST['writehere'])) {
		$body=mysql_real_escape_string(addslashes_mq($_POST['writehere']));
		if (empty($body)) {
			$error=true;
			$topass['message']=$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['MAIL_PROC_MESS_EMPTY']."</font></div>";
		}
	} else {
		$error=true;
		$topass['message']=$_messages['core'][70];
	}
	if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
		$oldtopass=$_SESSION['topass'];
		$_SESSION['topass']="";
		if (isset($oldtopass['action'])) {
			if ($oldtopass['action']=='reply') {
				$access_level=$access_matrix['mail_reply'][0];
				if ($oldtopass['to']!=mysql_real_escape_string(addslashes_mq($_POST['to']))) {
					$access_level=$access_matrix['mail_send'][0];		// attempting to trick us? :)
				}
			} elseif ($oldtopass['action']=='forward') {
				$access_level=$access_matrix['mail_forward'][0];
			}
		}
		unset($oldtopass);
	}

	
$memberplan = get_member_payplan($_SESSION['user_id']);
if ($memberplan != 0){
$max_messages=get_member_maxmess($_SESSION['user_id']);
}else{
$max_messages=get_site_option('max_messages');
}

	
	
	
	if ((get_messages_sent_today()>=$max_messages) && !empty($max_messages)) {
		$error=true;
		$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['MAIL_PROC_LIMIT']."</font></div>";
		redirect2page("mailbox.php",$topass,"mailbox=inbox");
	}
	
		$query="INSERT INTO mail_inbox SET message_type=1,user_id='$to_id',from_id='".$_SESSION['user_id']."',from_name='".$_SESSION['name']."',subject='$subject',body='$body',date_sent=now()";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (is_send_newmessage_alerts($to_id)) {
			send_newmessage_alert($_SESSION['user_id'],$to_id,$_SESSION['lang']);
		}
		$query="INSERT INTO mail_outbox SET message_type=1,user_id='".$_SESSION['user_id']."',from_id='$to_id',from_name='".mysql_real_escape_string(addslashes_mq($_POST['to']))."',subject='$subject',body='$body',date_sent=now()";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (isset($_POST['save']) && !empty($_POST['save'])) {
			$query="INSERT INTO mail_savedbox SET message_type=1,user_id='".$_SESSION['user_id']."',from_id='$to_id',from_name='".mysql_real_escape_string(addslashes_mq($_POST['to']))."',subject='$subject',body='$body',date_sent=now()";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
		$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['MAIL_PROC_MESS_SENT']."</font></div>";
		redirect2page("mailbox.php",$topass,"mailbox=inbox");
	} else {
		$topass['to']=$_POST['to'];
		$topass['subject']=$_POST['subject'];
		$topass['body']=$_POST['writehere'];
	}


redirect2page("mail_send.php",$topass);
?>
