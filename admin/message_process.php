<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();

set_time_limit(0);
 $tpl = new phemplate(_TPLPATH_,'remove_nonjs');

$topass=array();
$error=false;
$message="";
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$from=addslashes_mq($_POST['from']);

	$query="SELECT user_id FROM users WHERE user='$from'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$from_id=mysql_result($res,0,0);
	} else {
		$error=true;
		$topass['message']="Invalid FROM field! Message not sent.";
	}
	if (isset($_POST['subject']) && !empty($_POST['subject'])) {
		$subject=addslashes_mq($_POST['subject']);
	} else {
		$error=true;
		$topass['message']="No subject specified. Message not sent.";
	}
	if (isset($_POST['body']) && !empty($_POST['body'])) {
		$body=unix2dos(addslashes_mq($_POST['body']));
	} else {
		$error=true;
		$topass['message']="No message body! Message not sent.";
	}
	$action=0;
	if (isset($_POST['act']) && !empty($_POST['act'])) {
		$action=addslashes_mq($_POST['act']);
	}
	if (!$error) {
		$sendto=array();
		if ($action==-4) {
			$name=addslashes_mq($_POST['name']);
			$query="SELECT user_id FROM users WHERE user='$name'";
		} elseif ($action==-3) {
			$query="SELECT user_id FROM users";
		} elseif ($action==-2) {
			$query="SELECT user_id FROM users WHERE membership="._REGISTERLEVEL_;
		} elseif ($action==-1) {
			$query="SELECT user_id FROM users WHERE membership="._SUBSCRIBERLEVEL_;
		} elseif ($action>=1 && $action<=count($accepted_genders)) {
			$query="SELECT user_id FROM users WHERE gender='$action'";
		} else {
			$error=true;
			$topass['message']="You have to specify a member/group where to send the message";
		}
		if (!$error) {
			if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
			if (mysql_num_rows($res)) {
				for ($i=0;$i<mysql_num_rows($res);$i++) {
					$sendto[]=mysql_result($res,$i,0);
				}
			}


        @mysql_free_result($res); 
	$totalusers=count($sendto);
        if ($totalusers>49) {
				$insert="INSERT INTO mail_inbox (mail_id,read_status,user_id,from_id,subject,body,link,date_sent,from_name,message_type) VALUES ";
				$values='';
				$j=0;
				$k=0;
				for ($i=0;$i<count($sendto);$i++) {
					if ($j==75) {
						$query=$insert.$values;
						$query=substr($query,0,-1);
						if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
						$values='';
						$j=0;
						$k++;
	#print "$query<br>";
					}
					$values.="('',0,'$user_id','$from_id','$subject','$body','',now(),'$from','1'),";
                                        if (is_send_newmessage_alerts($user_id)) {send_newmessage_alert($from_id,$user_id);}
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
				if ($totalusers<51) {
					foreach ($sendto as $user_id) {
                                          $query="INSERT INTO mail_inbox VALUES('',0,'$user_id','$from_id','$subject','$body','',now(),'$from','1')";        
			           	  if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
				          if (is_send_newmessage_alerts($user_id)) {send_newmessage_alert($from_id,$user_id);}
					}
				}

		$topass['message']="Email sent to $totalusers members.";
	}



		} else {
			$topass['from']=$from;
			$topass['subject']=$subject;
			$topass['body']=$body;
			$topass['name']=$name;
			$topass['act']=$act;
			redirect2page('admin/send_message.php',$topass);
		}
	} else {
		$topass['from']=$from;
		$topass['subject']=$subject;
		$topass['body']=$body;
		$topass['name']=$name;
		$topass['act']=$act;
		redirect2page('admin/send_message.php',$topass);
	}
}
redirect2page('admin/controlpanel.php',$topass);
?>