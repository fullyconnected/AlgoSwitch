<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
$access_level=_GUESTLEVEL_;

db_connect();
check_login_member();
if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}


$message=((isset($topass['message'])) ? ($topass['message']) : (""));
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


$tpl->set_file('middlecontent','forgot_pass.html');
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('sitename',_SITENAME_);
$tpl->set_var('domainurl',_DOMAINURL_);

unset($_SESSION['security_code']); 

$scode = '<img src="CaptchaSecurityImages.php">';

$tpl->set_var('scode',$scode);
$tpl->set_var('lang', $lang);
$tpl->set_var('message',$message);
$middle_content=$tpl->process('out','middlecontent');

$title=$lang['FORGOT_PASSWORD_TITLE'];
include('blocks/block_index.php');
?>
