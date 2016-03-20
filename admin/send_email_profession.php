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
$tpl->set_file('content','admin/send_email_profession.html');
if (isset($_GET['name'])) {
	$name=addslashes_mq($_GET['name']);
	$checked='checked';
} else {
	$name='';
	$checked='';
}




$professions=array();
$i=0;


$query="SELECT pid,ptitle FROM profile_types";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res)) {
	




while(list($k,$v)= mysql_fetch_row($res)){
	
if ($k>0) {
		
$professions[$i]['profession_id']=$k;
		$professions[$i]['profession_name']=$v;
	}
	$i++;
}
}
$tpl->set_loop('professions',$professions);
$tpl->set_var('name',$name);
$tpl->set_var('checked',$checked);
$content=$tpl->process('out','content',1);

$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title',"Send email By Profession");
$tpl->set_var('baseurl',_BASEURL_);
//$tpl->set_var('message',$message);
$tpl->set_var('content',$content);
$tpl->set_var('relative_path',$relative_path);

print $tpl->process('out','frame',0,1);
?>
