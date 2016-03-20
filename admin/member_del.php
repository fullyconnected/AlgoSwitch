<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();

$message='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$user_id=addslashes_mq($_POST['user_id']);
	$query="DELETE FROM mail_inbox WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	$query="DELETE FROM mail_outbox WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	$query="DELETE FROM mail_savedbox WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	$query="DELETE FROM mail_blocks WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	$query="DELETE FROM online_status WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}

	$query="DELETE FROM user_buddies WHERE user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	$query="DELETE FROM user_preferences WHERE fk_user_id='$user_id'";
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
				}


	$query="DELETE FROM user_album2 WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	$message="Member expeled";
}

$query="SELECT user_id,user,name FROM users ORDER BY name";
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
$users=array();
$names=array();
$user_ids=array();
while ($rsrow=mysql_fetch_row($res)) {
	$users[$rsrow[0]]=$rsrow[1];
	$names[$rsrow[0]]=$rsrow[2];
}

 $tpl = new phemplate(_TPLPATH_,'remove_nonjs');
$tpl->set_file('temp','admin/member_del.html');
$tpl->set_var('usernames_options',vector2options($users,0));
$tpl->set_var('names_options',vector2options($names,0));
$content=$tpl->process('out','temp');

$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title',"Expel member");
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('message',$message);
$tpl->set_var('content',$content);
$tpl->set_var('relative_path',$relative_path);

//
print $tpl->process('out','frame',0,1);
?>