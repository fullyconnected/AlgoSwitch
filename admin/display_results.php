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
if (isset($_SESSION['foundmembers']) && is_array($_SESSION['foundmembers'])) {
	$allrows=$_SESSION['foundmembers'];
} else {
	$allrows=array();
}
if (isset($_GET['offset']) && !empty($_GET['offset'])) {
	$offset=addslashes_mq($_GET['offset'],true);
} else {
	$offset=0;
}
global $PHP_SELF;
$totalrows=count($allrows);
if ($offset+_RESULTS_<$totalrows) {
	$nextlink="<a href=\"$PHP_SELF?offset=".($offset+_RESULTS_)."\">Next "._RESULTS_."</a>";
	$last=$offset+_RESULTS_;
} else {
	$nextlink='';
	$last=$totalrows;
}
if ($offset>0) {
	$prevlink="<a href=\"$PHP_SELF?offset=";
	if ($offset-_RESULTS_<0) {
		$prevlink.="0";
	} else {
		$prevlink.=$offset-_RESULTS_;
	}
	$prevlink.="\">Previous "._RESULTS_."</a>";
} else {
	$prevlink='';
}
$foundmembers=array();
for ($i=$offset;$i<$last;$i++) {
	$foundmembers[$i]=$allrows[$i];
}
$tpl->set_file('content','admin/display_results.html');
$tpl->set_var('title',"Search results");
$tpl->set_var('message',$message);
$tpl->set_var('count',$totalrows);
$tpl->set_var('nextlink',$nextlink);
$tpl->set_var('prevlink',$prevlink);
$tpl->set_loop('foundmembers',$foundmembers);

$content=$tpl->process('out','content',1);
$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','View members');
$tpl->set_var('content',$content);
$tpl->set_var('message',$message);
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('relative_path',$relative_path);

//
print $tpl->process('out','frame',0,1);
?>