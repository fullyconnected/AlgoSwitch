<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
$access_level=_GUESTLEVEL_;

db_connect();
check_login_member();


global $relative_path;
if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}
if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$tpl=new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$tpl->set_var('lang', $lang);

$message=((isset($topass['message'])) ? ($topass['message']) : (""));


$tpl->set_file('middlecontent','index.html');





$siteurl = _BASEURL_;
$tpl->set_var('path',$siteurl);
$tpl->set_var('message',$message);
$middle_content=$tpl->process('out','middlecontent',0,1);

$sitename=_SITENAME_;
$title="$sitename";






include('blocks/block_index.php');
?>
