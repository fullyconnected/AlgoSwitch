<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
$access_level=$access_matrix['addcnotice'][0];

db_connect();
check_login_member();

if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}
global $relative_path;


check_approval();


if(isset($_SESSION['user_id'])){
$query="SELECT profession,membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession,$mymembership)=mysql_fetch_row($res);
}


$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());

$tpl->set_var('relative_path', $relative_path);



$headline="";



if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";


if(isset($topass['message'])&&(!empty($topass['message']))){
$message=$topass['message'];}
else {$message="";}

	$headline=$topass['headline'];
	$ndetails=$topass['ndetails'];

$actype=$topass['actype'];
$adnum=$topass['adnum'];
}

else {
$message="";
$adnum="";
$headline="";
$ndetails="";
}

if(!isset($actype)||empty($actype)){
$actype=1;}

$tpl->set_file('middlecontent','shoutout/shoutitout.html');
$tpl->set_var('message',$message);
$tpl->set_var('add_update',$actype);
$tpl->set_var('adnum',$adnum);
$tpl->set_var('headline',stripslashes($headline));
$tpl->set_var('textcount',160);

$tpl->set_var('ndetails',stripslashes($ndetails));
$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,1);


$title=$lang['SHOUT_CREATE'];
include('blocks/block_index.php');
?>
