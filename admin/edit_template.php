<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();

if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$act=addslashes_mq($_POST['act']);
	$filecontent=($_POST['filecontent']);
	if ($act==1) {
		$file=get_my_template('emails').'/'."activation_email.txt";
	} elseif ($act==2) {
		$file=get_my_template('emails').'/'."alert_email.txt";
	} elseif ($act==3) {
		$file=get_my_template('emails').'/'."del_pic_alert.txt";
	} elseif ($act==4) {
		$file=get_my_template('emails').'/'."account_change_alert.txt";
	} elseif ($act==5) {
		$file=get_my_template('emails').'/'."approval_email.txt";
	}
	$fp=fopen(_TPLPATH_.$file,'w');
	if ($fp) {
		if (!fwrite($fp,$filecontent)) {
			$message="Cannot write to file. Please check that you have write permissions on that file.";
		} else {
			$message="Page updated ok.";
		}
		fclose($fp);
	} else {
		$message="Cannot open file. Please check that you have write permissions on that file.";
	}
}

if (!isset($act)) {
	$act=addslashes_mq($_GET['act']);
}
if ($act==1) {
	$file=get_my_template('emails').'/'."activation_email.txt";
	$template_diz="<strong>Welcome email</strong>";
} elseif ($act==2) {
	$file=get_my_template('emails').'/'."alert_email.txt";
	$template_diz="<strong>New message alert</strong>";
} elseif ($act==3) {
	$file=get_my_template('emails').'/'."del_pic_alert.txt";
	$template_diz="<strong>Alert when admin deletes a user pic</strong>";
} elseif ($act==4) {
	$file=get_my_template('emails').'/'."account_change_alert.txt";
	$template_diz="<strong>Alert when admin changes a user status/membership</strong>";
} elseif ($act==5) {
	$file=get_my_template('emails').'/'."approval_email.txt";
	$template_diz="<strong>Email when you approve a profile</strong>";
}
$filecontent=fread($fp=fopen(_TPLPATH_.$file,'r'),filesize(_TPLPATH_.$file));
fclose($fp);

 $tpl = new phemplate(_TPLPATH_,'remove_nonjs');
$tpl->set_file('temp','admin/edit_template.html');
$tpl->set_var('filecontent',$filecontent);
$tpl->set_var('act',$act);
$tpl->set_var('template_diz',$template_diz);
$content=$tpl->process('out','temp');

$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title',"Edit a template");
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('message',$message);
$tpl->set_var('content',$content);
$tpl->set_var('relative_path',$relative_path);


print $tpl->process('out','frame',0,1);
?>