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
$tpl->set_file('content','admin/send_email.html');
if (isset($_GET['name'])) {
	$name=addslashes_mq($_GET['name']);
	$checked='checked';
} else {
	$name='';
	$checked='';
}
$genders=array();
$i=0;
while (list($k,$v)=each($accepted_genders)) {
	if ($k>0) {
		$genders[$i]['gender_id']=$k;
		$genders[$i]['gender_name']=$v;
	}
	$i++;
}
$tpl->set_loop('genders',$genders);
$tpl->set_var('name',$name);
$tpl->set_var('checked',$checked);
$content=$tpl->process('out','content',1);

$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title',"Send email");
$tpl->set_var('baseurl',_BASEURL_);
//$tpl->set_var('message',$message);
$tpl->set_var('content',$content);
$tpl->set_var('relative_path',$relative_path);

print $tpl->process('out','frame',0,1);
?>
