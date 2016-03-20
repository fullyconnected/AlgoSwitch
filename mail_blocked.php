<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
$access_level=$access_matrix['mail_send'][0];

db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();

if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}
global $relative_path;
$foundblock='';

$approved = is_approved($_SESSION['user_id']);
if ($approved == 0){

$message=$lang['ACCOUNT_NOT_APPROVED'];
$topass['message']=$message;
$nextstep="members.php";
redirect2page($nextstep,$topass);
}

if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";
}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));


$totalrows=get_total_blocked($_SESSION['user_id']);


 $tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');


$tpl->set_var('relative_path', $relative_path);


$table_cols= '4';	

$tpl->set_file('middlecontent','mailbox/mail_blocked.html');
$totalrows=get_total_blocked($_SESSION['user_id']);

$query="SELECT blocked_id FROM mail_blocks WHERE user_id ='".$_SESSION['user_id']."' ORDER BY blocked_id ASC";
	
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
if (mysql_num_rows($res)) {
	$i=1;
	$foundblock="<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"1\" align=\"center\" valign=\"top\">";

	while ($rsrow=mysql_fetch_row($res)) {
		$name =get_name($rsrow[0]);
		$name2 =remove_underscore(get_name($rsrow[0]));
		$photo = get_photo($rsrow[0]);
		
if (($i%$table_cols)==1) {$myreturn.="<tr>\n";}
$foundblock.="\t<td align=\"center\" valign=\"top\">\n";
$foundblock.="<br><a class=\"link-newest\" href=\"$name\" title=\"$name2\"><img src=\"memberpictures/thumbs/$photo\" class=\"mainimage\" border=\"0\" alt=\"$name2\"></a><br /><b>$name2</b><br><a href=\"processors/mail_process.php?action=6&user_id=".$rsrow[0]."\">Unblock</a>";
$foundblock.="\t</td>\n";
			if ($i%$table_cols==0) {$foundblock.="</tr><tr><td>&nbsp;</td></tr>\n";}
			$i++;
		}
		$rest=($i-1)%$table_cols;
		if ($rest!=0) {
			$colspan=$table_cols-$rest;
			$foundblock.="\t<td".(($colspan==1) ? ("") : (" colspan=\"$colspan\""))."></td>\n</tr>\n";
		}

		
		$foundblock.="</table>\n ";

		$i++;
	}

$tpl->set_var('foundblock',$foundblock);
$tpl->set_var('totalrows',$totalrows);
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$tpl->set_var('lang', $lang);
$tpl->set_var('message',$message);

$middle_content=$tpl->process('out','middlecontent',0,1);

$title=$lang['MAIL_SEND_A_MESS'];
include('blocks/block_main_frame.php');
?>