<?php
session_start();
require("../../includes/vars.inc.php");
require("../../includes/functions.inc.php");
require("../../includes/templates.inc.php");
require("../../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();

$tpl = new phemplate(_TPLPATH_,'remove_nonjs');
$tpl->set_file('content','admin/users/index.html');


$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('title','Member Managment');

$tpl->set_file('frame','admin/frame.html');
$content=$tpl->process('out','content',1);


$tpl->set_var('content',$content);


$tpl->set_var('relative_path',$relative_path);

print $tpl->process('out','frame',0,1);