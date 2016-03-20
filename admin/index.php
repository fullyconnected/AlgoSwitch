<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/templates.inc.php");
require('../includes/functions.inc.php');
require('../includes/apt_functions.inc.php');
$access_level=_ADMINLEVEL_;
db_connect();
$mymembership = '';
if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));

if (isset($_SESSION['user_id']))
{
	$query="SELECT membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
	if (!($res=mysql_query($query))) {
		error(mysql_error(),__LINE__,__FILE__);
	}
	list($mymembership)=mysql_fetch_row($res);
}


if ($mymembership ==5){
redirect2page("admin/controlpanel.php");	


}



$tpl = new phemplate(_TPLPATH_,'remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/');
$tpl->set_file('frame','admin/login.html');
$tpl->set_var('title',"Webmaster Login");
$tpl->set_var('sitenam',_SITENAME_);
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('message',$message);
$tpl->set_var('relative_path',$relative_path);
print $tpl->process('out','frame',false,true);
?>