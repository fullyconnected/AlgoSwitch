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
$tpl->set_file('content','admin/featured_setup.html');

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$user_id=intval(addslashes_mq($_POST['user_id']));
	if(is_member($user_id)){
	$query="INSERT INTO featured SET fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
}
}
if (isset($_GET['act']) & !empty($_GET['act'])) {
	$action=addslashes_mq($_GET['act']);
	if ($action=='delete') {
		$feat_id=addslashes_mq($_GET['feat_id']);
		$query="DELETE FROM featured WHERE feat_id='$feat_id'";
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	}
}

$query="SELECT feat_id,fk_user_id FROM featured ORDER BY feat_id";
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
$featured=array();
if (mysql_num_rows($res)) {
	$i=0;
	while ($rsrow=mysql_fetch_row($res)) {
		$featured[$i]['feat_id']=$rsrow[0];
		$featured[$i]['feat_name']= get_name($rsrow[1]);
		$i++;
	}
}
$tpl->set_loop('featured',$featured);


$content=$tpl->process('out','content',1);
$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','Featured Members Setup');
$tpl->set_var('content',$content);

$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('relative_path',$relative_path);

//
print $tpl->process('out','frame',0,1);
?>