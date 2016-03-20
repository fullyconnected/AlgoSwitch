<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
$access_level=$access_matrix['mydetails'][0];

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


if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));

$tpl->set_file('middlecontent','change_profile_type.html');
$tpl->set_var('message',$message);
$tpl->set_var('profession_options',vector2options($accepted_professions,$profession,array(_ANY_,_NDISCLOSED_,_CHOOSE_)));

$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,1);

$title="Change Profile type";
include('blocks/block_main_frame.php');
?>
