<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_REGISTERLEVEL_;

db_connect();
check_login_member();

$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
$cancelreason=mysql_real_escape_string($_POST['cancelreason']);

if(empty($cancelreason)){
$cancelreason=$lang['CHANGE_MEMBERSHIP_NO_REASON'];}

	$user_id=$_SESSION['user_id'];
	if(isset($_SESSION['user_id'])){
$query="SELECT profession,membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession,$mymembership)=mysql_fetch_row($res);
}
if($mymembership == 5){
$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['CHANGE_MEMBERSHIP_NO_CANCEL_ADMIN']."</font></div>";
redirect2page('inform_page.php',$topass);
}

	
	list($status,$membership)=get_account_details($user_id);
	$mailbody=(($membership==_REGISTERLEVEL_) ? ("Regular") : ("Paid"))." member: ".get_name($user_id)." canceled his/her registration citing reason below:\n\n$cancelreason\n\nCancelation occurred on: ".date('M-d-Y');
	mail(get_site_option('mailcontactus'),"Cancelation at "._SITENAME_,$mailbody,"From: ".get_site_option('mailfrom'));
	$query="DELETE FROM mail_inbox WHERE user_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		$query="DELETE FROM mail_outbox WHERE user_id='$user_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}    
		$query="DELETE FROM mail_savedbox WHERE user_id='$user_id'";
       if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);} 
	
	
	$query="DELETE FROM user_buddies WHERE buddy_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
    	$query="DELETE FROM user_buddies WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	
    	$query="DELETE FROM featured WHERE feat_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}

   	 $query="DELETE FROM mail_blocks WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$query="DELETE FROM online_status WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$query="DELETE FROM user_preferences WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}

	$query="DELETE FROM profile_extended WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
  
  
	

	$query="DELETE FROM profile_views WHERE profile_user_id='$user_id'";
 	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		
	$query="DELETE FROM profile_views WHERE viewer_user_id='$user_id'";
 	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	
	$query="DELETE FROM profile_comments WHERE poster_id='$user_id'";
    if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
    
	$query="DELETE FROM profile_comments WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	
	
	$query="DELETE FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$query="DELETE FROM profile_comments WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	$query="DELETE FROM cnotices WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
// delete images
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
				
		// now delete from list
	
	
		
	$query="DELETE FROM user_album2 WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}

	$query="DELETE FROM user_album_cat WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}

	
	

// end delete images

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


		$topass['continue_link']="processors/logout.php";
		$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['CANCEL_MEMBERSHIP_DONE']."</font></div>";


	redirect2page('inform_page.php',$topass);
}
redirect2page('members.php');
?>
