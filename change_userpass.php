<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
$access_level=$access_matrix['change_userpass'][0];

db_connect();
check_login_member();

if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}
global $relative_path;

$query="SELECT profession FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession)=mysql_fetch_row($res);


$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');

$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$tpl->set_var('relative_path', $relative_path);


if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));

$newpass=((isset($topass['newpass'])) ? ($topass['newpass']) : (""));

$tpl->set_file('middlecontent','change_userpass.html');
$tpl->set_var('message',$message);
$tpl->set_var('newpass',$newpass);
$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,1);

if(!isset($myuser_id) || empty ($myuser_id)){
$tpl->set_file('outmenu','menu/outmenu.html');
$tpl->set_var('relative_path', $relative_path);
$outmenu=$tpl->process('out','outmenu',0,1);
}

else {

$tpl->set_file('outmenu','menu/outmenu_in.html');
$tpl->set_var('relative_path', $relative_path);

$outmenu=$tpl->process('out','outmenu',0,1);

}
$title = "Change User/Pass";
$tpl->set_file('frame','frame.html');

$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('relative_path', $relative_path);

$tpl->set_var('outmenu',$outmenu);

$tpl->set_var('middle_content',$middle_content);
include('blocks/block_index.php');
?>
