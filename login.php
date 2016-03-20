<?php
session_start();
require("includes/vars.inc.php");
require("includes/templates.inc.php");
require("includes/functions.inc.php");
require("includes/apt_functions.inc.php");
$access_level=_GUESTLEVEL_;

db_connect();
$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$joincell = "";
$submitajobmsg="";
if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));




if(!isset($message) || empty($message)){

$message="<div class=\"alert\">".$lang['LOGIN_MESS']."</div>";

}


$tpl->set_file('middlecontent','login.html');
$tpl->set_var('message',$message);
$tpl->set_var('sitename',_SITENAME_);
$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,1);

$title=$lang['LOGIN_PAGE_TITLE'];
include('blocks/block_index.php');
?>
