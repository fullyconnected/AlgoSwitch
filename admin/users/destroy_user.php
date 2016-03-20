<?php
session_start();
require("../../includes/vars.inc.php");
require("../../includes/functions.inc.php");
require("../../includes/templates.inc.php");
require("../../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;
db_connect();
check_login_member();


$user_id = intval($_REQUEST['id']);

if($user_id !='1'){
    
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

            
}


echo json_encode(array('success'=>true));
?>