<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=$access_matrix['fanclub'][0];

db_connect();
check_login_member();
$base =_BASEURL_;
$approved = is_approved($_SESSION['user_id']);
if ($approved == 0){

$message="<div class=\"alert alert-error\">".$lang['ACCOUNT_NOT_APPROVED']."</div>";
$topass['message']=$message;
$nextstep="members.php";
redirect2page($nextstep,$topass);
}

$topass=array();
if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
$action=addslashes_mq($_REQUEST['action']);
}

if (isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id'])) {

$user_id=intval($_GET['user_id']);
}

if($action=='approval') {
	
$query="UPDATE IGNORE user_buddies SET approval='0' WHERE user_id='$user_id' AND buddy_id='".$_SESSION['user_id']."'";
		if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
$query="INSERT IGNORE INTO user_buddies SET user_id='".$_SESSION['user_id']."',buddy_id='$user_id', approval='0'";
		if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}

// send notification
						
	$who=remove_underscore(get_name($_SESSION['user_id']));
	$you=remove_underscore(get_name($user_id));
	$profilelink = get_profile_link($_SESSION['user_id']);
	$email=get_email($user_id);
	$base=_BASEURL_;
	$sitename=_SITENAME_;
	$what="confirmed you as a friend on $sitename";
	$domainzoid = _DOMAINURL_;
	$vid=$_SESSION['user_id'];
	$notice.="$who"; 
	$notice.=$lang['FRIENDS_EMAIL_1'];
	$notice.="$who";
	$notice.=$lang['FRIENDS_EMAIL_2'];
	
	$notice.="\n $domainzoid/$profilelink";
	send_notification($who,$what,$email,$notice);
// end send notification 						



$topass['message']="<div class=\"alert alert-success\">".$lang['FRIENDS_MESS_1']." $you ".$lang['FRIENDS_MESS_2']."</div>";
	



redirect2page("friendsrequest.php",$topass);
			
}

if($action=='delete'){

			$query="DELETE FROM user_buddies WHERE user_id='$user_id' AND buddy_id='".$_SESSION['user_id']."'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
							
			$query="DELETE FROM user_buddies WHERE user_id='".$_SESSION['user_id']."' AND buddy_id='$user_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}		
														
		
		//start send notification to friend
								$who=remove_underscore(get_name($_SESSION['user_id']));
								$you=remove_underscore(get_name($user_id));
								$profilelink = get_profile_link($_SESSION['user_id']);
								$email=get_email($user_id);
								$base=_BASEURL_;
								$sitename=_SITENAME_;
								$vid=$_SESSION['user_id'];
	$what=$lang['FRIENDS_DENIED'];
	$notice.="Dear"; 
	$notice.="$you,";
	$notice.=" $who ";
	$notice.=$lang['FRIENDS_MESS_3'];
	$notice.="$sitename.\n ";
	$notice.=$lang['FRIENDS_MESS_4']; 
	$notice.=" $who ";
	$notice.=$lang['FRIENDS_MESS_5'];
	$notice.=" $base/$profilelink";
								send_notification($who,$what,$email,$notice);
	//end send notification of addition to buddylist

		
								
			$topass['message']="".$lang['FRIENDS_MESS_6']." $you!";
			redirect2page("friendsrequest.php",$topass);
			
		} else {
			$topass['message']="";
		}


// deletes multiple friends from myfriends.php

if (isset($_GET['del']) && !empty($_GET['del']) && is_array($_GET['del'])) {
				$del=addslashes_mq($_GET['del']);
	
			
			 foreach ($del as $del){
				
			$fcount =count($del);	
				$query="DELETE FROM user_buddies WHERE user_id='$del' AND buddy_id='".$_SESSION['user_id']."'";
		if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
							
		$query="DELETE FROM user_buddies WHERE user_id='".$_SESSION['user_id']."' AND buddy_id='$del'";
		if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}		
	
			$topass['message']="<div class=\"alert alert-success\">".$lang['FRIENDS_MESS_7']." $fcount ".$lang['FRIENDS_MESS_8']."</div> ";
			
	}
				
			
			redirect2page("myfriends.php",$topass);
// end delete multiple friends
			}
if($action=='addfriend'){


if (is_buddy($user_id,true)) {
			$whou = remove_underscore(get_name($user_id));
			$topass['message']="<div class=\"alert alert-error\">".$lang['FRIENDS_MESS_9']." $whou </div> ";
			redirect2page("friendsrequest.php",$topass);
		} else {
			$query="INSERT INTO user_buddies SET user_id='".$_SESSION['user_id']."',buddy_id='$user_id', approval='1'";
			$ses = $_SESSION['user_id'];
			
			
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
				
			

									//start send notification to fan club owner
									$who=remove_underscore(get_name($_SESSION['user_id']));
									$you=remove_underscore(get_name($user_id));
									$profilelink = get_profile_link($_SESSION['user_id']);
									$email=get_email($user_id);
									$base=_BASEURL_;
									$sitename=_SITENAME_;
									$domainzoid = _DOMAINURL_;
				$what="".$lang['FRIENDS_MESS_10']." $sitename";
					$notice.="$who ";
					$notice.=$lang['FRIENDS_MESS_11'];
					$notice.="$sitename. ";
					$notice.=$lang['FRIENDS_MESS_12'];
					$notice.="$who ";
					$notice.=$lang['FRIENDS_MESS_13'];
					$notice.="$sitename.\n ";
					$notice.=$lang['FRIENDS_MESS_14'];
					$notice.="$who's ";
					$notice.=$lang['FRIENDS_MESS_15'];
					$notice.="$domainzoid/$profilelink";
									send_notification($who,$what,$email,$notice);
									//end send notification of addition to buddylist



			$topass['message']="<div class=\"alert alert-success\">".$lang['FRIENDS_MESS_16']." $you ".$lang['FRIENDS_MESS_17']."</div>";
		redirect2page("friendsrequest.php",$topass);	
			
		}
}
redirect2page("myfriends.php",$topass);
?>
