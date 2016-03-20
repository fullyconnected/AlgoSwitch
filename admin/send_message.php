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
$tpl->set_file('content','admin/send_message.html');

if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));
$query="SELECT user FROM users WHERE user_id = 1";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
	 $adminname=mysql_result($res,0,0);
	}
$from=((isset($topass['from'])) ? ($topass['from']) : (""));

if (empty($from)){
	$from = $adminname;
}


$name=((isset($topass['name'])) ? ($topass['name']) : (""));
$checked=((isset($topass['name'])) ? ("checked") : (""));
$subject=((isset($topass['subject'])) ? ($topass['subject']) : (""));
$body=((isset($topass['body'])) ? ($topass['body']) : (""));
if (isset($_GET['name'])) {
	$name=addslashes_mq($_GET['name']);
	$checked='checked';
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
$tpl->set_var('from',$from);
$tpl->set_var('subject',$subject);
$tpl->set_var('body',$body);
$tpl->set_var('name',$name);
$tpl->set_var('checked',$checked);
$content=$tpl->process('out','content',1);

$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title',"Send message");
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('message',$message);
$tpl->set_var('relative_path',$relative_path);
$tpl->set_var('content',$content);

//
print $tpl->process('out','frame',0,1);
?>