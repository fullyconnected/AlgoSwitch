<?php
//print_r($_COOKIE['posLat']).'<br>';
//print_r($_COOKIE['posLon']).'<br>';

function get_total_messages($user_id,$mailbox='inbox',$new=false) {
	
	
	$from='mail_inbox';
	if ($mailbox=='inbox') {
		$from='mail_inbox';
	} elseif ($mailbox=='outbox') {
		$from='mail_outbox';
	} elseif ($mailbox=='savedbox') {
		$from='mail_savedbox';
	}
	$query="SELECT count(mail_id) FROM $from WHERE user_id='$user_id'";
	if ($new) {
		$query.=" AND read_status=0";
	}
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$myreturn=mysql_result($res,0,0);
	return $myreturn;
}

function get_total_profile_views($user_id) {
	$query="SELECT views FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$views=mysql_result($res,0,0);
	} else {
		$views=0;
	}
	return $views;
}

function get_total_online() {
	$query="SELECT count(fk_user_id) FROM online_status";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$myreturn=mysql_result($res,0,0);
	return $myreturn;
}


function get_mlev($user_id) {
	$query="SELECT membership FROM users WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	return @mysql_result($res,0,0);
}



?>