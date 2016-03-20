<?php

function get_image_album_pulldown($user_id,$albumid){  

$b = '';
$option = '';
$sql = "SELECT id,album_name FROM user_album_cat WHERE fk_user_id='$user_id'";
 $result = @mysql_query($sql) or die(mysql_error());
 $num = @mysql_num_rows($result);
 if ($num < 1) {
$myreturn=NULL;
  } else{
  	while ($row=mysql_fetch_array($result)){

	 $id=$row[0];
     $nam = $row[1];

 if ($id == $albumid){
	$selected = "SELECTED";
	}else{
	 $selected = "";
	 }
$option.="<option id=\"$id\"  value=\"$id\" $selected >$nam</option>";
		}
 $myreturn = ' <select name="malbum">'.$option.' </select>';
  
return $myreturn;
	}  
}
function has_main_photo($user_id) {
$query="SELECT picture_name FROM user_album2 WHERE fk_user_id='$user_id' AND mainphoto !=0 AND adult !=1 ";
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	@$question=mysql_result($res,0,0);
if (!empty($question)){
$myreturn = 1;
		}else{
$myreturn = 0;
	}
return $myreturn;
}
function get_key($user_id) {
	$myreturn='';
	$query="SELECT access_key FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	} else {
		$myreturn=false;
	}
	return $myreturn;
}


function get_email($user_id) {
	$query="SELECT email FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	return mysql_result($res,0,0);
}


function get_userid_by_user($username) {
	$query="SELECT user_id FROM users WHERE user='$username'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	} else {
		$myreturn=0;
	}
	return $myreturn;
}
function get_profile_link_name($name) {  // gets user_id from name
	$query="SELECT user_id FROM users WHERE profilelink='$name'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	} else {
		$myreturn=0;
	}
	return $myreturn;
}
function get_profile_link($user_id) {  // gets profile link from user_id
	$query="SELECT profilelink FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	} else {
		$myreturn=0;
	}
	return $myreturn;
}
function get_profile_link_user($id) {  // gets profile link from name
	$id = mysql_real_escape_string($id);
	$query="SELECT profilelink FROM users WHERE user = '$id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	} else {
		$myreturn=0;
	}
	return $myreturn;
}
function get_userid_by_name($name) {
	$query="SELECT user_id FROM users WHERE user='$name'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	} else {
		$myreturn=0;
	}
	return $myreturn;
}


function get_userpass($user_id) {
	$myreturn=array('','');
	$query="SELECT user,pass FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_fetch_row($res);
	}
	return $myreturn;
}


function get_name($user_id) {
	$myreturn='';
	$query="SELECT user FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return ucwords($myreturn);
}




function get_name_session() {
	
	$myreturn='';
	@$query="SELECT user FROM users WHERE user_id='".$_SESSION['user_id']."'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return ucwords($myreturn);
}

function get_account_details($user_id) {
	$query="SELECT status,membership FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		list($status,$membership)=mysql_fetch_row($res);
	} else {
		$status=0;
		$membership=0;
	}
	return array($status,$membership);
}



function get_photo($user_id) {
      
      
	
	$myreturn='';
	$query="SELECT picture_name FROM user_album2 WHERE fk_user_id='$user_id' AND mainphoto = '1' ";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	
if (empty($myreturn)) {
	$query="SELECT picture_name FROM user_album2 WHERE fk_user_id='$user_id'  ";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	}
	
	
	if (empty($myreturn)) {
	    
	
	    $myreturn="no-pict.gif";}
	    
	    
	return $myreturn;
}
 
  

function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}


function get_total_buddies_online($user_id) {
	$joined=join("','",get_buddy_ids($user_id,false));
	$query="SELECT count(*) FROM online_status WHERE fk_user_id IN ('$joined')";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$myreturn=mysql_result($res,0,0);
	return $myreturn;
}


function get_onlinebuddy_ids($user_id) {
	$joined=join("','",get_buddy_ids($user_id,false));
	$query="SELECT fk_user_id FROM online_status WHERE fk_user_id IN ('$joined')";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	for ($i=0;$i<mysql_num_rows($res);$i++) {
		$myreturn[$i]=mysql_result($res,$i,0);
	}
	return $myreturn;
}


function get_buddy_ids($user_id,$useoffset=true,$offset=0) {
	$myreturn=array();
	$query="SELECT buddy_id FROM user_buddies WHERE user_id='$user_id'";
	if ($useoffset) {
		$query.=" LIMIT $offset,"._RESULTS_;
	}
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	for ($i=0;$i<mysql_num_rows($res);$i++) {
		$myreturn[$i]=mysql_result($res,$i,0);
	}
	return $myreturn;
}

function get_whoadded_ids($user_id,$useoffset=true,$offset=0) {
	$myreturn=array();
	$query="SELECT user_id FROM user_buddies WHERE buddy_id='$user_id'";
	if ($useoffset) {
		$query.=" LIMIT $offset,"._RESULTS_;
	}
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	for ($i=0;$i<mysql_num_rows($res);$i++) {
		$myreturn[$i]=mysql_result($res,$i,0);
	}
	return $myreturn;
}

function is_mailblocked($to_id,$from_id) {
	$blocked=0;
	$query="SELECT count(*) FROM mail_blocks WHERE user_id='$to_id' AND blocked_id='$from_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$blocked=mysql_result($res,0,0);
	}
	return $blocked;
}

function is_buddy($user_id) {
	$myreturn=false;
			if(isset($_SESSION['user_id']) && (!empty($_SESSION['user_id']))){
				$myuid=$_SESSION['user_id'];
			}else {$myuid="";}


	$query="SELECT count(*) FROM user_buddies WHERE user_id='$myuid' AND buddy_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res) && mysql_result($res,0,0)) {
			$myreturn=true;
		}
	$query="SELECT count(*) FROM user_buddies WHERE user_id='$user_id' AND buddy_id='$myuid'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res) && mysql_result($res,0,0)) {
			$myreturn=true;
		}


	return $myreturn;
}


function is_fan($user_id) {
	$myreturn=false;
	if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
	$myuid=$_SESSION['user_id'];}
	else{$myuid='';}
	$query="SELECT count(*) FROM user_buddies WHERE buddy_id='$myuid' AND user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res) && mysql_result($res,0,0)) {
			$myreturn=true;
		}
		return $myreturn;
}



function is_member($user_id) { // validate account
	$myreturn=false;
	$query="SELECT user_id FROM users WHERE user_id='$user_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=true;
	}
	return $myreturn;
}

function get_gender($user_id) {
	$myreturn=0;
	$query="SELECT gender FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}

function get_member_payplan($user_id){
$myreturn=0;
	$query="SELECT payplan FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}
function get_member_maxpic($user_id){
$query="SELECT maxphoto FROM `sell_plans_paypal` u LEFT JOIN users ua ON ua.payplan=u.plan_id WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}

function get_member_maxmess($user_id){
$query="SELECT maxmess FROM `sell_plans_paypal` u LEFT JOIN users ua ON ua.payplan=u.plan_id WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}


function is_online($user_id) {
	$online=false;
	$query="SELECT count(*) FROM online_status WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res) && mysql_result($res,0,0)) {
		$online=true;
	}
	return $online;
}


function is_empty_album($user_id) { 
	$empty=false;
	$query="SELECT picture1,picture2,picture3,picture4 FROM user_album WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	list ($picture1,$picture2,$picture3,$picture4)=mysql_fetch_row($res);
	if (empty($picture1) && empty($picture2) && empty($picture3) && empty($picture4)) {
		$empty=true;
	}
	return $empty;
}

function is_audio_empty($user_id) { 
	$empty=false;
	$query="SELECT file_name, artist_name FROM user_audio WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	list ($filename,$artist)=mysql_fetch_row($res);
	if (empty($filename) && empty($artist)) {
		$empty=true;
	}
	return $empty;
}

function is_album_cat_empty($user_id,$albumid){   // Checks if Album is empty or not.
$empty = true;
$query="SELECT id FROM user_album2 WHERE fk_user_id='$user_id' AND id='$albumid'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		list($bumid)=mysql_fetch_row($res);
if (empty($bumid)) {
		$empty=false;
	}
	return $empty;
	}

}
function countalbums($user_id){

	$query="SELECT count(fk_user_id) FROM user_album_cat WHERE fk_user_id = '$user_id'";
		if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	list ($count)=mysql_fetch_row($res);
	
	return $count;
	
}
function count_album_images($user_id,$id){

	$query="SELECT count(picture_name) from user_album2 WHERE fk_user_id = '$user_id' AND id='$id'";
	$res=@mysql_query($query);
	return mysql_result($res,0,0);
	
}

function check_approval(){
global $lang;
$approved = is_approved($_SESSION['user_id']);
if ($approved == 0){

$message=$lang['ACCOUNT_NOT_APPROVED'];
$topass['message']=$message;
$nextstep="members.php";
redirect2page($nextstep,$topass);
	}

}
	

function is_public_album($user_id) {// not used yet?
	$myreturn=false;
	$query="SELECT album_private FROM user_preferences WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res) && (mysql_result($res,0,0))) {
		$myreturn=false;
	} else {
		$myreturn=true;
	}
	return $myreturn;
}


function is_send_newmessage_alerts($to_id) {
	$send=false;
	$query="SELECT email_send_alerts FROM user_preferences WHERE fk_user_id='$to_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res) && mysql_result($res,0,0)) {
		$send=true;
	}
	return $send;
}


function is_featured($user_id) {
	$myreturn=false;
	$query="SELECT count(*) FROM featured WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res) && mysql_result($res,0,0)) {
		$myreturn=true;
	}
	return $myreturn;
}


function is_approved($user_id) {
	$myreturn=false;
	$query="SELECT is_approved FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res) && mysql_result($res,0,0)) {
		$myreturn=true;
	}
	return $myreturn;
}


function get_site_option($option) {
	$myreturn=0;
	if ($option=='filter_urls') {
		$query="SELECT filter_urls FROM site_options";
	} elseif ($option=='filter_emails') {
		$query="SELECT filter_emails FROM site_options";
	} elseif ($option=='filter_words') {
		$query="SELECT filter_words FROM site_options";
	} elseif ($option=='toprated_size') {
		$query="SELECT toprated_size FROM site_options";
	} elseif ($option=='use_ratings') {
		$query="SELECT use_ratings FROM site_options";
	} elseif ($option=='max_messages') {
		$query="SELECT max_messages FROM site_options";
	} elseif ($option=='signup_alerts') {
		$query="SELECT signup_alerts FROM site_options";
	} elseif ($option=='mailfrom') {
		$query="SELECT mailfrom FROM site_options";
	} elseif ($option=='mailcontactus') {
		$query="SELECT mailcontactus FROM site_options";
	} elseif ($option=='auto_approve') {
		$query="SELECT auto_approve FROM site_options";
	} elseif ($option=='max_user_pics') {
	$query="SELECT max_user_pics FROM site_options";
	}elseif ($option=='max_ads') {
		$query="SELECT max_ads FROM site_options";
	}elseif ($option=='max_headlines') {
		$query="SELECT max_headlines FROM site_options";
	}elseif ($option=='use_comments_portfolios') {
		$query="SELECT use_comments_portfolios FROM site_options";
	}elseif ($option=='use_comments_photos') {
		$query="SELECT use_comments_photos FROM site_options";
	}elseif ($option=='auto_expire_ads') {
		$query="SELECT auto_expire_ads FROM site_options";
	}elseif ($option=='email_confirmation') {
	$query="SELECT email_confirmation FROM site_options";
	}
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}



function get_member_site_option($option) {
	$myreturn=0;
	if ($option=='allow_photo_comments') {
	$query="SELECT allow_photo_comments FROM user_preferences";
	}elseif ($option=='allow_portfolio_comments') {
		$query="SELECT allow_portfolio_comments FROM user_preferences";
	}elseif ($option=='allow_ratings') {
		$query="SELECT allow_ratings FROM user_preferences";
	}elseif ($option=='recent_visits') {
		$query="SELECT recent_visits FROM user_preferences";
	}elseif ($option=='use_comments_photos') {
		$query="SELECT use_comments_photos FROM site_options";
	}elseif ($option=='auto_expire_ads') {
		$query="SELECT auto_expire_ads FROM site_options";
	}
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}


function messages_tpl_array($mail_ids,$mailbox='inbox') {
	global $relative_path;

	$joined=join("','",$mail_ids);
	$myreturn=array();
	$from='mail_inbox';
	if ($mailbox=='inbox') {
		$from='mail_inbox';
	} elseif ($mailbox=='outbox') {
		$from='mail_outbox';
	} elseif ($mailbox=='savedbox') {
		$from='mail_savedbox';
	}
	$query="SELECT a.mail_id,a.subject,a.from_id,a.from_name,b.user,date_format(a.date_sent,'%m/%d/%Y'),a.read_status FROM $from a LEFT JOIN users b ON a.from_id=b.user_id WHERE a.mail_id IN ('$joined') ORDER BY a.date_sent DESC,a.mail_id DESC";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$i=0;
	while ($rsrow=mysql_fetch_row($res)) {
	
	$fprof=get_profession2($rsrow[2]);
		list($mail_id,$subject,$from_id,$from_name,$name,$date_sent,$read_status)=$rsrow;
		$myreturn[$i]['i']=$i;
		$myreturn[$i]['profession']=$fprof;
		$myreturn[$i]['mail_id']=$mail_id;
		$myreturn[$i]['subject']=stripslashes($subject);
		$myreturn[$i]['from_id']=$from_id;
		$myreturn[$i]['sender']=(!empty($name)) ? htmlentities(stripslashes($name)) : htmlentities(stripslashes($from_name));
		$myreturn[$i]['plink']=get_profile_link($from_id);
		$myreturn[$i]['date_sent']=$date_sent;
		$myreturn[$i]['mailbox']=$mailbox;
		
		$imageurl = _TPLURL_.'/'.get_my_template();
		if ($read_status==0) {
			$myreturn[$i]['read_status']="<img src=\"$imageurl/images/unreadmail.gif\" border=\"0\" title=\"Unread Mail\" />";
		} else {
			$myreturn[$i]['read_status']="<img src=\"$imageurl/images/readmail.gif\" border=\"0\" title=\"Read Mail\" />";
		}
		if (is_online($from_id)) {
			$myreturn[$i]['online_status']="<img src=\"$imageurl/images/online.gif\" border=\"0\" title=\"Member Online Now\" />";
		} else {
			$myreturn[$i]['online_status']="<img src=\"$imageurl/images/notonline.gif\" border=\"0\" title=\"Member Online Now\" />";
		}
		$myreturn[$i]['myclass']=(($i%2) ? ('trodd') : ('treven'));
		$i++;
	}
	return $myreturn;
}



function get_user_messages($user_id,$mailbox='inbox',$useoffset=true,$offset=0,$results=_RESULTS_) {
	$myreturn=array();
	$from='mail_inbox';
	if ($mailbox=='inbox') {
		$from='mail_inbox';
	} elseif ($mailbox=='outbox') {
		$from='mail_outbox';
	} elseif ($mailbox=='savedbox') {
		$from='mail_savedbox';
	}
	$query="SELECT mail_id FROM $from WHERE user_id='$user_id' ORDER BY mail_id DESC";
	if ($useoffset) {
		$query.=" LIMIT $offset,$results";
	}
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	for ($i=0;$i<mysql_num_rows($res);$i++) {
		$myreturn[$i]=mysql_result($res,$i,0);
	}
	return $myreturn;
}


function delete_messages($user_id,$messages,$mailbox='inbox') {
	$from='mail_inbox';
	if ($mailbox=='inbox') {
		$from='mail_inbox';
	} elseif ($mailbox=='outbox') {
		$from='mail_outbox';
	} elseif ($mailbox=='savedbox') {
		$from='mail_savedbox';
	}
	if (isset($messages) && !empty($messages)) {
		if (is_array($messages)) {
			$mails2del=join("','",array_values($messages));
		} else {
			$mails2del=$messages;
		}
		$query="DELETE FROM $from WHERE mail_id IN ('$mails2del') AND user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	}
}


function remove_text_urls($text) {
	$text=preg_replace("/((http:\/\/)|(www\.)|(ftp:\/\/))[^\s\n\r\t]+/","<banned URL>",$text);
	return $text;
}


function remove_text_emails($text) {
	$text=preg_replace("/[^\s\n\r\t]+?\@[^\s\n\r\t]+?\.[^\s\n\r\t\?!]+/","<banned email>",$text);
	return $text;
}

function show_index_total_online() {
	db_connect();
	return get_total_online();
}


function show_index_total_members() {
	db_connect();
	return get_total_members();
}


function get_total_members() {
	$query="SELECT count(*) FROM users";
	$res=@mysql_query($query);
	return mysql_result($res,0,0);
}


// a bit inaccurate since it cannot count the messages in other folders but inbox.
function get_messages_sent_today() {
	$query="SELECT count(*) FROM mail_inbox WHERE from_id='".$_SESSION['user_id']."' AND (TO_DAYS(date_sent)-TO_DAYS(now()))=0";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$myreturn=mysql_result($res,0,0);
	return $myreturn;
}


function set_membership($user_id,$level) {
	$query="UPDATE users SET membership='$level' WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
}

function send_changeemail_email($to,$subject,$content,$language=_DEFAULT_LANGUAGE_) {
	$tpl = new phemplate(_TPLPATH_.get_my_template('emails').'/'.$language.'/','remove_nonjs');
	$tpl->set_file('changeemail_email','changemyemail_text.txt');
	$tpl->set_var('content',htmlentities(stripslashes($content)));
	$message=unix2dos($tpl->process('out','changeemail_email'));
	$sentok=send_email(get_site_option('mailfrom'),$to,$subject,$message);
	return $sentok;
}

function send_notification($who,$what,$email,$notice,$language=_DEFAULT_LANGUAGE_) {
	$subject="$who has $what";
	$tpl = new phemplate(_TPLPATH_.get_my_template('emails').'/'.$language.'/','remove_nonjs');
	$tpl->set_file('alertmail','notification_alert.txt');
	$tpl->set_var('notice',$notice);
	$tpl->set_var('sitename',_SITENAME_);
	$tpl->set_var('baseurl',_BASEURL_);
	$tpl->set_var('contactusmail',get_site_option('mailcontactus'));
	$message=unix2dos($tpl->process('','alertmail',TPL_FINISH));
	$sentok=send_email(get_site_option('mailfrom'),$email,$subject,$message,true);
	return $sentok;
}
function send_activation_code($reg_id,$name,$email,$code,$username,$password,$language=_DEFAULT_LANGUAGE_) {
	global $lang;
	
	$tpl = new phemplate(_TPLPATH_.get_my_template('emails').'/'.$language.'/','remove_nonjs');
	$tpl->set_file('activation_email','activation_email.txt');
	$tpl->set_var('name',htmlentities(stripslashes($name)));
	$tpl->set_var('act_code',$code);
	$tpl->set_var('user_id',$reg_id);
	$tpl->set_var('username',htmlentities(stripslashes($username)));
	$tpl->set_var('password',htmlentities(stripslashes($password)));
	$tpl->set_var('baseurl',_BASEURL_);
	$message=unix2dos($tpl->process('out','activation_email'));
	$sentok=send_email(get_site_option('mailfrom'),$email,""._SITENAME_." ".$lang['REGISTRATION_ACTIVATION_EMAIL_TITLE']."",$message,"".$lang['REGISTRATION_ACTIVATION_EMAIL_FROM']." ".get_site_option('mailfrom')."\r\n");
	return $sentok;
}

function send_password($name,$email,$code,$username,$password,$language=_DEFAULT_LANGUAGE_) {
	global $lang;

	$tpl = new phemplate(_TPLPATH_.get_my_template('emails').'/'.$language.'/','remove_nonjs');
	$tpl->set_file('password_email','password_email.txt');
	$tpl->set_var('name',htmlentities(stripslashes($name)));
	$tpl->set_var('act_code',$code);
	$tpl->set_var('username',htmlentities(stripslashes($username)));
	$tpl->set_var('password',htmlentities(stripslashes($password)));
	$tpl->set_var('email',htmlentities(stripslashes($email)));
	$tpl->set_var('sitemail',get_site_option('mailfrom'));
	
	$tpl->set_var('baseurl',_BASEURL_);
	$message=unix2dos($tpl->process('out','password_email'));
	$sentok=send_email(get_site_option('mailfrom'),$email,_SITENAME_." ".$lang['ALERT_ACCOUNT_INFO']."",$message,"".$lang['ALERT_FROM'].": ".get_site_option('mailfrom')."\r\n");
	return $sentok;
}



function send_newmessage_alert($from_id,$to_id,$language=_DEFAULT_LANGUAGE_) {
	global $lang;
	$email=get_email($to_id);
	$to=get_name($to_id);
	$from=get_name($from_id);
	$tpl = new phemplate(_TPLPATH_.get_my_template('emails').'/'.$language.'/','remove_nonjs');
	$tpl->set_file('alertmail','alert_email.txt');
	$tpl->set_var('to',$to);
	$tpl->set_var('from',$from);
	$tpl->set_var('sitename',_SITENAME_);
	$tpl->set_var('baseurl',_BASEURL_);
	$tpl->set_var('contactusmail',get_site_option('mailcontactus'));
	$message=unix2dos($tpl->process('out','alertmail'));
	$sentok=send_email(get_site_option('mailfrom'),$email,"".$lang['ALERT_NEW_MESS']." "._SITENAME_,$message,"".$lang['ALERT_FROM'].": ".get_site_option('mailfrom'));
	return $sentok;
}

function send_alert_job_reply($from_id,$to_id,$parsedsubj,$parsedbody,$language=_DEFAULT_LANGUAGE_) {
	global $lang;
	$email=get_email($to_id);
	$to=get_name($to_id);
	$from=get_name($from_id);
	$sitename=_SITENAME_;
	$appendmess="\n\nMessage Subject: $parsedsubj\n\nText of Message\n$parsedbody\n\nYou may also read your message on the $sitename website";
 	$tpl = new phemplate(_TPLPATH_.get_my_template('emails').'/'.$language.'/','remove_nonjs');
	$tpl->set_file('alertmail','alert_email.txt');
	$tpl->set_var('to',$to);
	$tpl->set_var('from',$from);
	$tpl->set_var('sitename',_SITENAME_);
	$tpl->set_var('baseurl',_BASEURL_);
	$tpl->set_var('appendmess',$appendmess);
	$tpl->set_var('contactusmail',get_site_option('mailcontactus'));
	$message=unix2dos($tpl->process('out','alertmail'));
	$sentok=send_email(get_site_option('mailfrom'),$email,"".$lang['ALERT_REPLIED_TO_CASTING']." "._SITENAME_,$message,"".$lang['ALERT_FROM'].": ".get_site_option('mailfrom'));
	return $sentok;
}

function send_delpic_alert($to_id,$language=_DEFAULT_LANGUAGE_) {
	global $lang;
	$email=get_email($to_id);
	$name=get_name($to_id);
	$tpl = new phemplate(_TPLPATH_.get_my_template('emails').'/'.$language.'/','remove_nonjs');
	$tpl->set_file('alertmail','del_pic_alert.txt');
	$tpl->set_var('name',$name);
	$tpl->set_var('sitename',_SITENAME_);
	$tpl->set_var('baseurl',_BASEURL_);
	$tpl->set_var('contactusmail',get_site_option('mailcontactus'));
	$message=unix2dos($tpl->process('out','alertmail'));
	$sentok=send_email(get_site_option('mailfrom'),$email,"Picture deleted at "._SITENAME_,$message,"From: ".get_site_option('mailfrom'));
	return $sentok;
}


function send_newstatus_alert($to_id,$newstatus,$language=_DEFAULT_LANGUAGE_) {
	$email=get_email($to_id);
	$name=get_name($to_id);
 	$tpl = new phemplate(_TPLPATH_.get_my_template('emails').'/'.$language.'/','remove_nonjs');
	$tpl->set_file('alertmail','account_change_alert.txt');
	$tpl->set_var('name',$name);
	$tpl->set_var('newstatus',$newstatus);
	$tpl->set_var('sitename',_SITENAME_);
	$tpl->set_var('baseurl',_BASEURL_);
	$tpl->set_var('contactusmail',get_site_option('mailcontactus'));
	$message=unix2dos($tpl->process('out','alertmail'));
	$sentok=send_email(get_site_option('mailfrom'),$email,"New account status at "._SITENAME_,$message,"From: ".get_site_option('mailfrom'));
	return $sentok;
}


function send_newsignup_alert($name,$email,$gender,$language=_DEFAULT_LANGUAGE_) {
	$message="User details:\n";
	$message.="Name: $name\n";
	$message.="Email address: $email\n";
	$message.="Gender: $gender\n";
	$sentok=send_email(get_site_option('mailcontactus'),"New signup at "._SITENAME_,$message,"From: ".get_site_option('mailfrom'));
	return $sentok;
}



/**
	*	error report
	*/
	function error( $msg, $level = '')
	{
		$lvl = E_USER_WARNING;
		if ('fatal' == $level) $lvl = E_USER_ERROR;
		if (isset($that->error_handler))
		{
			$that->error_handler->report($lvl, $msg);
		}
		else
		{
			trigger_error("\n<br><font color='#CC0099'><b>$level:</b> $msg</font><br>\n",$lvl);
			if ('fatal' == $level) { exit; }
		}
	}



function get_membership_level($user_id) {
$myreturn='';
	$query="SELECT status,membership FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		list($status,$membership)=mysql_fetch_row($res);
	} else {
		$status=0;
		$membership=0;
	}
	if($membership == 1){$memberlevelword="general, non-VIP member <a href=\"upgrade.php\"><img src=\"images/paypalbn.gif\" border=\"0\"></a>";}
	elseif($membership >= 2){$memberlevelword="<font class=alert>VIP Member</font>";}
	$myreturn="$memberlevelword";
	return $myreturn;
}

function is_vip($user_id) {
$myreturn='';
	$query="SELECT payplan FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		list($membership)=mysql_fetch_row($res);
	}
	return $membership;
}




function get_upgrade_link($user_id) {
$myreturn='';
	$query="SELECT status,membership FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		list($status,$membership)=mysql_fetch_row($res);
	} else {
		$status=0;
		$membership=0;
	}
	global $relative_path;

	if($membership == 1){$upgradelink="<a href=\"{$relative_path}subscribe.php\">Upgrade to premier membership</a>";}
	else {$upgradelink="";}
	$myreturn="$upgradelink";
	return $myreturn;
}

function get_pass($user_id) {
	$myreturn=array('','');
	$query="SELECT pass FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_fetch_row($res);
	}
	return $myreturn;
}


/*
function update_onlines() {
	$query="DELETE FROM online_status WHERE last_activity<=now()-INTERVAL "._EXPIRE_." MINUTE";
	@mysql_query($query);
}
*/
function get_picturecount($user_id){
		$howmany="";
		$query="SELECT count(picture_number) FROM user_album2 WHERE fk_user_id='$user_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
				if (mysql_num_rows($res)) {
		$howmany=mysql_result($res,0,0);
		}
		return $howmany;
}
function get_audiocount($user_id){
		$howmany="";
		$query="SELECT count(id) FROM user_audio WHERE user_id='$user_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
				if (mysql_num_rows($res)) {
		$howmany=mysql_result($res,0,0);
		}
		return $howmany;
}

function check_login_member() {
	
	global $lang;
	global $access_level;
	$comingfrom="";
	if ($access_level!=0) {
		if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
		if($comingfrom == "voting_system"){
		$topass=array("message"=>"You need to have an account to vote. <a href=\"${relative_path}registration1.php\"> Click here to create an account</a>");}
		else{
			$topass=array("message"=>"<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['LOGIN_PLEASE']."</font></div>");}
			redirect2page('login.php',$topass);
		}
		list($status,$membership)=get_account_details($_SESSION['user_id']);
		if ($status==_STATUSSUSPENDED_) {
$topass=array('message'=>"<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['LOGIN_ACCOUNT_SUSPENDED']."</font></div>");
			redirect2page('login.php',$topass);
		}
		if ($status==_STATUSNOTACTIVE_) {
			redirect2page("registration2.php");
		}
		if ($membership<$access_level) {
$topass=array('message'=>"<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['LOGIN_ACCOUNT_YOU_HAVE_TO_UPGRADE']."</font></div>");
			redirect2page('upgrade.php',$topass);
		}
		if (is_online($_SESSION['user_id'])) {
			$query="UPDATE online_status SET last_activity=UNIX_TIMESTAMP(NOW()) WHERE fk_user_id='".$_SESSION['user_id']."'";
			mysql_query($query);
		} else {
			$query="INSERT INTO online_status SET last_activity=UNIX_TIMESTAMP(NOW()),fk_user_id='".$_SESSION['user_id']."'";
			mysql_query($query);
		}
	} elseif (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
		if (is_online($_SESSION['user_id'])) {
			$query="UPDATE online_status SET last_activity=now() WHERE fk_user_id='".$_SESSION['user_id']."'";
			mysql_query($query);
		} else {
			$query="INSERT INTO online_status SET last_activity=now(),fk_user_id='".$_SESSION['user_id']."'";
			mysql_query($query);
		}
		
	}
	
	
	
	
	
//	update_onlines();
}


function get_album_pic2($user_id,$id=0,$picno=0) {
	$myreturn=array();
	$ageallowed = _AGEALLOWED_;
	$adultQ ='';
	if (empty($_SESSION['age'])){
	$adultQ = 'AND adult !=1';
	}
	if (!empty($_SESSION['age'])){
	$realage = determine_age($_SESSION['age']);
	if ($realage <= $ageallowed){
	$adultQ = 'AND adult !=1';
		}

	}
	$query="SELECT picture_number,picture_name,id FROM user_album2 WHERE fk_user_id='$user_id' $adultQ";
	if (!empty($picno)) {
		$query.=" AND picture_number='$picno'";
	}
	if (!empty($id)) {
		$query.=" AND id='$id'";
	}
	
	$query.=" ORDER BY picture_number ASC ";
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	while ($rsrow=mysql_fetch_row($res)) {
		$myreturn[$rsrow[0]]=$rsrow[1];
	}
	return $myreturn;
}

function get_album_pic($user_id,$picno=0) {
	$myreturn=array();
	$query="SELECT picture_number,picture_name FROM user_album2 WHERE fk_user_id='$user_id'";
	if (!empty($picno)) {
		$query.=" AND picture_number='$picno'";
	}
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	while ($rsrow=mysql_fetch_row($res)) {
		$myreturn[$rsrow[0]]=$rsrow[1];
	}
	return $myreturn;
}
function get_album_audio($user_id) {
	$myreturn=array();
	$query="SELECT id,file_name FROM user_audio WHERE user_id='$user_id'";
	
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	while ($rsrow=mysql_fetch_row($res)) {
		$myreturn[$rsrow[0]]=$rsrow[1];
	}
	return $myreturn;
}

function get_album_cat($user_id,$picno=0) {
	$myreturn=array();
	$query="SELECT id,album_name,imagefile FROM user_album_cat WHERE fk_user_id='$user_id'";
	if (!empty($picno)) {
		$query.=" AND id='$picno' ";
	}
	$query.=" order by created_on DESC";
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}

        $data = array();
        while($row = mysql_fetch_assoc($res))
         {
               $data[] = $row;
         }
        return $data;
}





function get_video_vid2($user_id,$vidno=0) {
	$myreturn=array();
	$query="SELECT video_number,video_link,video_name FROM user_videos WHERE fk_user_id='$user_id'";
	if (!empty($vidno)) {
		$query.=" AND video_number='$vidno'";
	}
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	while ($rsrow=mysql_fetch_row($res)) {
		$myreturn[$rsrow[0]]=$rsrow[1];
	}
	return $myreturn;
}
function get_cnotices($user_id,$picno=0) {
	$myreturn=array();
	$query="SELECT adnum,headline,ndetails FROM cnotices WHERE fk_user_id='$user_id'";
	if (!empty($picno)) {
		$query.=" AND adnum='$picno'";
	}
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	while ($rsrow=mysql_fetch_row($res)) {
		$myreturn[$rsrow[0]]=$rsrow[1];
	}
	return $myreturn;
}
function get_shoutout($user_id,$picno=0) {
	$myreturn=array();
	$query="SELECT adnum,headline,ndetails FROM shoutout WHERE fk_user_id='$user_id'";
	if (!empty($picno)) {
		$query.=" AND adnum='$picno'";
	}
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	while ($rsrow=mysql_fetch_row($res)) {
		$myreturn[$rsrow[0]]=$rsrow[1];
	}
	return $myreturn;
}

function is_empty_album2($user_id) {
	$myreturn=false;
	$query="SELECT count(*) FROM user_album2 WHERE fk_user_id='$user_id'";
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$count=mysql_result($res,0,0);
	if (empty($count)) {
		$myreturn=true;
	}
	return $myreturn;
}

function has_cnotice($user_id) {
	$myreturn=false;
	$query="SELECT count(*) FROM cnotices WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res) && mysql_result($res,0,0)) {
		$myreturn=true;
	}
	return $myreturn;
}

function exists($user_id) {
	$myreturn=false;
	$query="SELECT count(*) FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res) && mysql_result($res,0,0)) {
		$myreturn=true;
	}
	return $myreturn;
}

function existsname($name) {
 	$myreturn=false;
 	$query="SELECT count(*) FROM users WHERE name='$name'";
 	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
 	if (mysql_num_rows($res) && mysql_result($res,0,0)) {
 		$myreturn=true;
 	}
 	return $myreturn;
}

function get_profession($user_id) {
	$myreturn='';
	$query="SELECT profession FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}
/*
function get_profession2($user_id) {
$myreturn = '';
$query="SELECT ptitle,pid FROM `profile_types` u LEFT JOIN users ua ON ua.profession = u.pid WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
		$memlev = get_mlev($user_id);
		if ($memlev == 5) {
		$myreturn = _ADMINPROF_;
		
		}
	return $myreturn;
}*/


function get_profession2($user_id) {
      global $accepted_professions;
        
	$myreturn='';
	$query="SELECT profession FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	
	if($user_id!=1){
	    
	    
	    $prof = @$accepted_professions[$myreturn];

}else{
	    
	
	    $prof = _ADMINPROF_;
     
}
	return $prof;
}





function get_profile_pulldown($user_id=0,$any=0,$search=0,$nosel=0,$class=0){  
$option = '';
$selected = '';
$myreturn ='';

if ($user_id !=0){
  	 $zpid = get_profession($user_id);
	 }else{
	 $zpid = '';
	 
}

 if ($any !=0){
	$optio="<option value=\"0\" $selected>Any</option>"; 
	 }else{
$optio = '';
}

if ($search ==1){
	$optio="<option value=\"0\" $selected>Any</option>"; 
 $zpid=$any;
	 }else{
$optio = '';


}
$sql = "SELECT pid, ptitle FROM profile_types ORDER BY id ASC";
 $result = mysql_query($sql) or die(mysql_error());
 $num = mysql_num_rows($result);
 if ($num < 1) {
 $myreturn.= 'Define profile types in admin!';
	return $myreturn;
break;
  }else{
  	while ($row=mysql_fetch_array($result)){
     $id=$row[0];
     $b = $row[1];
	 if ($id == $zpid){
	$selected = "SELECTED";
	}else{
	 $selected = "";
	 }
$option.="<option value=\"$id\" $selected >$b</option>";
		}
		
		if($nosel==1){
		$myreturn = $optio.$option;
		}else{
		$myreturn = "<select class=\"$class\" name=\"profession\">$optio $option </select>";
		}
return $myreturn;
	}  
}
function get_profiletype($user_id) { // Get the profile type from user_id
	$myreturn='';
	$query="SELECT ptitle,pid FROM `profile_types` u LEFT JOIN users ua ON ua.profession = u.pid WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}

function get_profile_type($pid) { // Get the profile type from user_id
	$myreturn='';
	$query="SELECT ptitle FROM `profile_types` WHERE pid='$pid'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}



function professions_list(){
$baseurl = _BASEURL_;
$out = '';
global  $relative_path;
$query ="SELECT ptitle,pid FROM profile_types ORDER BY pid ASC";
if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		while ($rsrow=mysql_fetch_row($res)) {
		$ptitle=$rsrow[0];
			$pid=$rsrow[1];

$out .= "<li><a href=\"$baseurl/search/results.php?searchtype=advanced&profession=$pid&country=-1&city=&us_state=-1&miles=5&zip=&sortby=new&name=&agemin=18&agemax=73&gender=-1&heightmin=-1&heightmax=18&ethnicity=-1&haircolor=-1&hairlength=-1&eyecolor=-1&photo=on\">$ptitle's</a></li>\n";


}
return $out;

}

function getrandphum($user_id) {
		mt_srand(make_seed());
		$randmain="";
		$query="SELECT picture_number,picture_name FROM user_album2 WHERE fk_user_id='$user_id' ORDER BY RAND(".mt_rand().") LIMIT 1";
		if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		while ($rsrow=mysql_fetch_row($res)) {
			$pnum=$rsrow[0];
			$pname=$rsrow[1];
			if (file_exists(_IMAGESPATH_."/$pname")) {
			$randmain=$pnum;
			}
		}
		return $randmain;
}

function get_random_album_pic($user_id) {
		mt_srand(make_seed());
		$myreturn="";
	$query="SELECT picture_name FROM user_album2 WHERE fk_user_id='$user_id' AND adult !=1 ORDER BY RAND(".mt_rand().") LIMIT 1";
		if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		while ($rsrow=mysql_fetch_row($res)) {
			$pname=$rsrow[0];

			if (file_exists(_IMAGESPATH_."/$pname")) {
			$myreturn=$pname;
			}
		}
		return $myreturn;
}
// user preferences
function check_profile_private2($user_id) {
	$myreturn='';
	$query="SELECT private_profile FROM user_preferences WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
return $myreturn;
}

function check_allow_ratings($user_id) {
	$ratings_stat=false;
	$query="SELECT allow_ratings FROM user_preferences WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res) && mysql_result($res,0,0)) {
			$ratings_stat=true;
		}
		return $ratings_stat;
}

function check_allow_portfolio_comments($user_id) {
	$alpc=false;
	$query="SELECT allow_portfolio_comments FROM user_preferences WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res) && mysql_result($res,0,0)) {
			$alpc=true;
		}
		return $alpc;
}


function is_admin($user_id) {

		$myreturn='';
		$query="SELECT membership FROM users WHERE user_id='$user_id'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
		list($alevel)=mysql_fetch_row($res);
		if($alevel >= _ADMINLEVEL_){
			$myreturn=true;
		}else{
			$myreturn=false;
		}
		}
		return $myreturn;
}



function is_userblocked($to_id,$from_id) {
	$blocked=0;
	$query="SELECT count(*) FROM mail_blocks WHERE user_id='$to_id' AND blocked_id='$from_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$blocked=mysql_result($res,0,0);
	}
	return $blocked;
}
function get_blocked_ids($user_id,$useoffset=true,$offset=0,$results=_RESULTS_) {
	$myreturn=array();
	$query="SELECT blocked_id FROM mail_blocks WHERE user_id='$user_id'";
	if ($useoffset) {
		$query.=" LIMIT $offset,$results";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);$i++) {
		$myreturn[$i]=mysql_result($res,$i,0);
	}
	return $myreturn;
}


function get_total_blocked($user_id) {
	$query="SELECT count(blocked_id) FROM mail_blocks WHERE user_id='$user_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$myreturn=mysql_result($res,0,0);
	return $myreturn;
}

function get_ownerlevel($user_id) {
	$ownerlevel="";
	$query="SELECT membership FROM users WHERE user_id='$user_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		list($ownerlevel)=mysql_fetch_row($res);

}
	return $ownerlevel;
}



function wordcount($str) { 
  return count(explode(" ",$str));
} 




function get_album_name($user_id,$id){
	$albumname='';
	$query="SELECT album_name FROM user_album_cat WHERE fk_user_id='$user_id' AND id='$id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		list($albumname)=mysql_fetch_row($res);
	}
	return $albumname;
}

function get_album_image($user_id,$id){
	$albumimage='';
	$query="SELECT imagefile FROM user_album_cat WHERE fk_user_id='$user_id' AND id='$id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		list($albumimage)=mysql_fetch_row($res);
}
if(empty($albumimage)){
	$albumimage="no-pict.jpg";
}
	return $albumimage;
}

function imageResize($image,$target) {
$img = getimagesize($image); 
$width = $img[0];
$height = $img[1];
if ($width > $height) {
$percentage = ($target / $width);
} else {
$percentage = ($target / $height);
}

$width = round($width * $percentage);
$height = round($height * $percentage);

return "width=\"$width\" height=\"$height\" border=\"0\"";

} 
 
function scaleimage($location, $maxw=NULL, $maxh=NULL, $altz=NULL){
    $img = getimagesize($location);
    if($img){
        $w = $img[0];
        $h = $img[1];
		$dim = array('w','h');
        foreach($dim AS $val){
            $max = "max{$val}";
            if(${$val} > ${$max} && ${$max}){
                $alt = ($val == 'w') ? 'h' : 'w';
                $ratio = ${$alt} / ${$val};
                ${$val} = ${$max};
                ${$alt} = ${$val} * $ratio;
            }
        }

        return("<img class='mainimage' src='{$location}' alt='{$altz}' width='{$w}' height='{$h}' />");
    }
}
function get_age($user_id){  // gets the members age 
$age = '';
$query="SELECT DATE_FORMAT(NOW(),'%Y')-DATE_FORMAT(birthdate,'%Y')-(DATE_FORMAT(NOW(),'00-%m-%d')<DATE_FORMAT(birthdate,'00-%m-%d')),DAYOFYEAR(birthdate) FROM users where user_id='".$user_id."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		list($age)=mysql_fetch_row($res);
		
		
	}
	return $age;
}
function get_total_friends($user_id) {  // count friends
	$query="SELECT count(buddy_id) FROM user_buddies WHERE buddy_id='$user_id' and approval='0'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$myreturn=mysql_result($res,0,0);
	return $myreturn;
}
function get_total_friendsrequest($userid) {  // count friend requests
	$query="SELECT count(buddy_id) FROM user_buddies WHERE buddy_id='$userid' AND approval='1'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$myreturn=mysql_result($res,0,0);
	return $myreturn;
}

function is_friend($userid) { // are you my friend? :)
	$myreturn=false;
	if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
	$myuid=$_SESSION['user_id'];}
	else{$myuid='';}
	$query="SELECT count(*) FROM user_buddies WHERE buddy_id='$myuid' AND user_id='$userid' AND approval='1'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res) && mysql_result($res,0,0)) {
			$myreturn=true;
		}
		return $myreturn;
}
function get_total_buddies($user_id) {
	$query="SELECT count(buddy_id) FROM user_buddies WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$myreturn=mysql_result($res,0,0);
	return $myreturn;
}

function determine_age($birth_date)
{
$birth_date_time = strtotime($birth_date);
$to_date = date('m/d/Y', $birth_date_time);

list($birth_month, $birth_day, $birth_year) = explode('/', $to_date);

$now = time();

$current_year = date("Y");

$this_year_birth_date = $birth_month.'/'.$birth_day.'/'.$current_year;
$this_year_birth_date_timestamp = strtotime($this_year_birth_date);

$years_old = $current_year - $birth_year;

if($now < $this_year_birth_date_timestamp)
{
/* his/her birthday hasn't yet arrived this year */
$years_old = $years_old - 1;
}

return $years_old;
}


function getCountry($id,$iso='')
{ 
		if(!empty($iso)){
		$name = $iso;
		}else{
			$name='name';
		}
		
        /*************************************************************************\
        |* Get the country name
        \*************************************************************************/
		$sql    = "SELECT $name FROM geo_countries WHERE con_id=$id";
        $result = mysql_Query($sql);
        $country =mysql_fetch_row($result);
		return $country[0];
}
function getCity($id)
{
        /*************************************************************************\
        |* Get the city name
        \*************************************************************************/
        $sql    = "SELECT name FROM geo_cities WHERE cty_id=$id";
        $result = mysql_Query($sql);
		$city = mysql_fetch_row($result);
	
        return $city[0];
}
function getRegion($id)
{
        /*************************************************************************\
        |* Get the region name
        \*************************************************************************/
        $sql    = "SELECT name FROM geo_states WHERE sta_id=$id";
        $result = mysql_Query($sql);
		$state = mysql_fetch_row($result);
        return $state[0];
}


?>
