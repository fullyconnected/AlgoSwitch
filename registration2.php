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


$_SESSION['fbloggedin']=1;

global $relative_path;

if(isset($_SESSION['user_id'])){
$query="SELECT profession,membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession,$mymembership)=mysql_fetch_row($res);
}


$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$tpl->set_var('relative_path', $relative_path);

if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));
if (isset($_SESSION['reg_name'])) {
	$name=$_SESSION['reg_name'];
} elseif (isset($_SESSION['name'])) {
	$name=$_SESSION['name'];
} else {
	$name='';
}


if (isset($_SESSION['reg_email'])) {
	$email=$_SESSION['reg_email'];
} elseif (isset($_SESSION['user_id'])) {
	$email=get_email($_SESSION['user_id']);
} else {
	$email='';
}
if (isset($_SESSION['reg_id'])) {
	$user_id=$_SESSION['reg_id'];
} elseif (isset($_SESSION['user_id'])) {
	$user_id=$_SESSION['user_id'];
} else {
	$user_id='';
}
$tpl->set_file('middlecontent','register2.html');
$tpl->set_var('message',$message);
$tpl->set_var('name',htmlentities(stripslashes($name)));
$tpl->set_var('email',htmlentities(stripslashes($email)));
$tpl->set_var('user_id',$user_id);
$tpl->set_var('sitename',_SITENAME_);
$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,1);



$title=$lang['REGISTRATION2_PAGE_TITLE'];
include('blocks/block_reg.php');
?>