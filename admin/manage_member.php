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
$tpl->set_file('content','admin/manage_member.html');

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
	$user_id=addslashes_mq($_GET['user_id']);
}

if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));


$table_cols=4;
$photos=array();
$photos=get_album_pic2($user_id);
$picstableadmin="";

if(empty($photos)){
$pictableadmin="";
$mptitle="";
$mpborder="0";}

else {

if (!empty($photos)) {
$mptitle="Manage Photos";
$mpborder="1";
	$picstableadmin="<table>\n";
	$i=1;
	while (list($k,$v)=each($photos)) {
		if (($i%$table_cols)==1) {$picstableadmin.="<tr>\n";}
		$picstableadmin.="\t<td align=\"center\">\n";
		$picstableadmin.="\t\t<img src=\""._THUMBSURL_."/$v\"  border=\"0\" /><br /><a href=\"do.php?act=delpic&pic=$k&user_id=$user_id\">Delete</a>";
		$picstableadmin.="\t</td>\n";
		if ($i%$table_cols==0) {$picstableadmin.="</tr>\n";}
		$i++;
	}
	$rest=($i-1)%$table_cols;
	if ($rest!=0) {
		$colspan=$table_cols-$rest;
		$picstableadmin.="\t<td".(($colspan==1) ? ("") : (" colspan=\"$colspan\""))."></td>\n</tr>\n";
	}
	$picstableadmin.="</table>\n";
	}

}
$query="SELECT user,status,membership FROM users WHERE user_id='$user_id'";
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
if (mysql_num_rows($res)) {
	list($name,$status,$membership)=mysql_fetch_row($res);

	$active='';
	$notactive='';
	$suspended='';
	$registered='';
	$subscriber='';
	$admin='';
	$forum='';
	if ($status==_STATUSACTIVE_) {
		$active='checked';
	} elseif ($status==_STATUSNOTACTIVE_) {
		$notactive='checked';
	} elseif ($status==_STATUSSUSPENDED_) {
		$suspended='checked';
	}
	if ($membership==_REGISTERLEVEL_) {
		$registered='checked';
	} elseif ($membership==_SUBSCRIBERLEVEL_) {
		$subscriber='checked';
	} elseif ($membership==_ADMINLEVEL_) {
		$admin='checked';
	}elseif ($membership==_ADMINFORUM_) {
		$forum='checked';
	}



	$titlename=$name;

	$tpl->set_var('titlename',htmlentities(stripslashes($titlename)));
	$tpl->set_var('user_id',$user_id);
	$tpl->set_var('name',htmlentities(stripslashes($name)));
	$tpl->set_var('picstableadmin',$picstableadmin);
	$tpl->set_var('active',$active);
	$tpl->set_var('notactive',$notactive);
	$tpl->set_var('suspended',$suspended);
	$tpl->set_var('registered',$registered);
	$tpl->set_var('subscriber',$subscriber);
	$tpl->set_var('mptitle',$mptitle);
	$tpl->set_var('mpborder',$mpborder);
	$tpl->set_var('admin',$admin);
	$tpl->set_var('forum',$forum);

	if (!is_approved($user_id)) {
		$tpl->set_var('approval_links',"<a href=\"approval.php?action=2&user_id=$user_id\">Don't approve</a>&nbsp;&nbsp;&nbsp;<a href=\"approval.php?action=1&user_id=$user_id\">Approve</a>");
	}

	$content=$tpl->process('out','content');

	$topass=array('return2'=>"manage_member.php");
	$topass['redir_qparams']="user_id=$user_id";
	$_SESSION['topass']=$topass;

	$tpl->set_file('frame','admin/frame.html');
	$tpl->set_var('title','View members');
	$tpl->set_var('content',$content);
	$tpl->set_var('message',$message);
	$tpl->set_var('baseurl',_BASEURL_);
	$tpl->set_var('relative_path',$relative_path);
	//
	print $tpl->process('out','frame',0,1);
} else {
?>
<script type="text/javascript">
	history.go(-1);
</script>
<?
}
?>