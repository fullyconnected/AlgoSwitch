<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");

$access_level=_GUESTLEVEL_;

db_connect();
if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}



$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());


if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));
$numheadlines=get_site_option('max_headlines');

$tpl->set_file('middlecontent','info.html');



$tpl->set_var('message',$message);

$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,1);

$sitename=_SITENAME_;
$tw="0";
$title="Mobile Radius Social Networking Community";

include('blocks/block_main_frame.php');
?>