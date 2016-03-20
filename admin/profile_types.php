<?php

session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
include ("includes/mysql.class.php");
$access_level=_ADMINLEVEL_;
$html ='';
$message = '';
db_connect();
check_login_member();
$db = new db_mysql(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_); 
$tpl = new phemplate(_TPLPATH_,'remove_nonjs');
$r = $db->query("SELECT * FROM profile_types ORDER BY id") or $db->raise_error();

while($row = $db->fetch_array($r)) {
$zid = $row['id'];
$pid = $row['pid'];
$ptitle = $row['ptitle'];
$html .= "
<tr class=\"$zid\">
<td></td>
<td height=\"20px\" id=\"pid|$zid\" class=\"edit\" align=\"left\">$pid</td>
<td height=\"20px\" id=\"ptitle|$zid\" class=\"edit\" align=\"left\">$ptitle</td>
<td><a href=\"javascript: ajaxDelete('$zid');\">Del</a></td></tr>";
}


$tpl->set_var('data',$html);

$tpl->set_file('content','admin/profiletypes.html');



$content=$tpl->process('out','content',1);
$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','Profile Types Setup');
$tpl->set_var('content',$content);
$tpl->set_var('message',$message);
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('relative_path',$relative_path);

print $tpl->process('out','frame',0,1);
?>
