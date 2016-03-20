<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
$access_level=_GUESTLEVEL_;

db_connect();

check_login_member();

if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}
global $relative_path;

if(isset($_SESSION['user_id'])){
$query="SELECT profession,membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession,$mymembership)=mysql_fetch_row($res);
}


$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
 $tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());



$tpl->set_var('relative_path', $relative_path);
$u = (int)$_GET['u'];
$photo = get_photo($u);
$proff = get_profession2($u);
$name = get_name($u);
$gender = get_gender($u);
$age = get_age($u);
$message='';
$continue_link="index.php";
if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

	$message=$topass['message'];
	if (isset($topass['continue_link'])) {
		$continue_link=$topass['continue_link'];
	}
}
$tpl->set_file('middlecontent','profiles/private_profile.html');
$tpl->set_var('message',$message);
$tpl->set_var('proff',$proff);
$tpl->set_var('photo',$photo);
$tpl->set_var('name',$name);
$tpl->set_var('age',$age);
$tpl->set_var('u',$u);
$tpl->set_var('gender',$accepted_genders[$gender]);
$tpl->set_var('continue_link',$continue_link);

$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,1);

$title="Private Profile";
include('blocks/block_index.php');
?>
