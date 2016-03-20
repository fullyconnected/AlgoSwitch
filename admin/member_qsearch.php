<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();

 $tpl = new phemplate(_TPLPATH_,'remove_nonjs');

if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));
$tpl->set_file('content','admin/member_qsearch_index.html');
$tpl->set_var('message',$message);
$tpl->set_var('gender_options',vector2options($accepted_genders,_ANY_,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('registerlevel',_REGISTERLEVEL_);
$tpl->set_var('subscriberlevel',_SUBSCRIBERLEVEL_);

$content=$tpl->process('out','content');
$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','View members');
$tpl->set_var('content',$content);
$tpl->set_var('message',$message);
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('relative_path',$relative_path);

print $tpl->process('out','frame',0,1);
?>
