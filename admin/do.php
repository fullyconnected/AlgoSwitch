<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();

if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));

$topass=array();

$offset="";
$backto="";
		
if (isset($_GET['act']) && !empty($_GET['act'])) {
	$action=addslashes_mq($_GET['act']);
	$user_id=addslashes_mq($_GET['user_id']);

	if (isset($_GET['offset']) && !empty($_GET['offset'])) {
	$offset=addslashes_mq($_GET['offset']);
	}
	if (isset($_GET['backto']) && !empty($_GET['backto'])) {
	$backto=addslashes_mq($_GET['backto']);
	}
	if ($action=='delete') {
	
	$query="SELECT profession,membership FROM users WHERE user_id='".$user_id."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession,$mymembership)=mysql_fetch_row($res);

if($mymembership == 5){
$topass['message']="You can not delete the admin account";
redirect2page('admin/controlpanel.php',$topass);
}

		$query="DELETE FROM mail_inbox WHERE user_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$query="DELETE FROM mail_outbox WHERE user_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}    
		$query="DELETE FROM mail_savedbox WHERE user_id='$user_id'";
     		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);} 
		$query="DELETE FROM user_buddies WHERE buddy_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$query="DELETE FROM mail_blocks WHERE user_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$query="DELETE FROM online_status WHERE fk_user_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$query="DELETE FROM profile_comments WHERE fk_user_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$query="DELETE FROM photo_comments WHERE fk_user_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$query="DELETE FROM video_comments WHERE fk_user_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		
		
	

		$query="DELETE FROM user_buddies WHERE buddy_id='$user_id'";
		if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
   		$query="DELETE FROM user_buddies WHERE user_id='$user_id'";
		if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}	
		
		$query="DELETE FROM featured WHERE feat_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		
	
		
		$query="DELETE FROM user_preferences WHERE fk_user_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	
	
	
		$query="DELETE FROM profile_views WHERE profile_user_id='$user_id'";
 	 	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		
		$query="DELETE FROM profile_views WHERE viewer_user_id='$user_id'";
 	 	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		
		$query="DELETE FROM shoutout WHERE fk_user_id='$user_id'";
        	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	
		$query="DELETE FROM users WHERE user_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$query="SELECT picture_name FROM user_album2 WHERE fk_user_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		
			for ($i=0;$i<mysql_num_rows($res);$i++) {
				$photo=mysql_result($res,$i,0);
				if (file_exists(_IMAGESPATH_."/$photo")) {
					@unlink(_IMAGESPATH_."/$photo");
				}
				if (file_exists(_THUMBSPATH_."/$photo")) {
					@unlink(_THUMBSPATH_."/$photo");
				}
			
			if (file_exists(_MEDTHUMBSPATH_."/$photo")) {
					@unlink(_MEDTHUMBSPATH_."/$photo");
				}
			}

	$query="DELETE FROM user_album2 WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	$query="DELETE FROM user_album_cat WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}

// delete audio
$query="SELECT file_name FROM user_audio WHERE user_id='$user_id'";
			if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
				for ($i=0;$i<mysql_num_rows($res);$i++) {
					$audio=mysql_result($res,$i,0);
					if (file_exists(_AUDIOPATH_."/$audio")) {
						@unlink(_AUDIOPATH_."/$audio");
					}
	
				
				}
		$query="DELETE FROM user_audio WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
// end delete audio



		$message="Member Deleted";
	} elseif ($action=='deletead') {
	$ad_id=addslashes_mq($_GET['adid']);
	$uid=addslashes_mq($_GET['user_id']);
	$query="DELETE FROM cnotices WHERE adnum='$ad_id' AND fk_user_id='$uid'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	$message="Casting Notice Deleted";

	} elseif($action=='addtsr'){
	$query="UPDATE users SET show_in_random='1' WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	$topass['message']="User has been added to the random showcase";
	$sendback="$backto?user_id=$user_id&amp;offset=$offset";
	redirect2page($sendback,$topass);

	}elseif($action=='remfsr'){
	$query="UPDATE users SET show_in_random='0' WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	$topass['message']="User has been removed from the random showcase";
	$sendback="$backto?user_id=$user_id&amp;offset=$offset";
	redirect2page($sendback,$topass);

	}elseif ($action=='delpic') {
		$pic=addslashes_mq($_GET['pic']);
				$user_id=addslashes_mq($_GET['user_id']);
			        $query="SELECT picture_name FROM user_album2 WHERE fk_user_id='$user_id' AND picture_number='$pic'";
		        	if (!($res=@mysql_query($query))) {$message=mysql_error();}
		                $curpic="";
				if (mysql_num_rows($res)) {
		        	        $curpic=mysql_result($res,0,0);
			        }
			        if (!empty($curpic)) {

						//remove pic comments from table photo_comments if any
								//first check for comments
								$query="SELECT picture_name FROM photo_comments WHERE fk_user_id='$user_id' AND picture_name='$curpic'";
								if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
								//now delete comments if found
								if (mysql_num_rows($res)) {
									$query="DELETE FROM photo_comments WHERE fk_user_id='$user_id' AND picture_name='$curpic'";
									if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
								}

		        	        $query="DELETE FROM user_album2 WHERE fk_user_id='$user_id' AND picture_number='$pic'";
			                if (!($res=@mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		        	        @unlink(_IMAGESPATH_."/$curpic");
		        	        @unlink(_THUMBSPATH_."/$curpic");
							@unlink(_MEDTHUMBSPATH_."/$curpic");
					$message="Picture deleted";
					send_delpic_alert($user_id);
		}
	}
	$topass['message']=$message;
	redirect2page("admin/controlpanel.php",$topass);
} else {
	$topass['message']="Invalid request method";
	redirect2page("admin/controlpanel.php",$topass);
}
?>
