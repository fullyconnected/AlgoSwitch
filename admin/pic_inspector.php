<?php
session_start();
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
require("../includes/vars.inc.php");

$access_level=_ADMINLEVEL_;

db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();


 
 $tpl = new phemplate(_TPLPATH_,'remove_nonjs');
$message="";
$prevlink='';
$nextlink='';
$error=false;
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$start_id=addslashes_mq($_POST['start_id']);
} elseif (isset($_GET['start_id']) && !empty($_GET['start_id'])) {
	$start_id=$_GET['start_id'];
}

if (isset($_GET['delpic'])) {
	$delpic=addslashes_mq($_GET['delpic']);
	$curpic="";
	$query="SELECT picture_name FROM user_album2 WHERE fk_user_id='$start_id' AND picture_number='$delpic'";
	if (!($res=@mysql_query($query))) {$message=mysql_error();}
	if (mysql_num_rows($res)) {
		$curpic=mysql_result($res,0,0);
	}
	if (!empty($curpic)) {

		//remove pic comments from table photo_comments if any
		//first check for comments
		

		$query="DELETE FROM user_album2 WHERE fk_user_id='$start_id' AND picture_number='$delpic'";
		if (!($res=@mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		@unlink(_IMAGESPATH_."/$curpic");
		@unlink(_THUMBSPATH_."/$curpic");
	
	
	
	
	}
}


$query="SELECT min(user_id),max(user_id) FROM users";
if (!($res=@mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
if (mysql_num_rows($res)) {
	list($min_id,$max_id)=mysql_fetch_row($res);
} else {
	$error=true;
	$message="Could not retrieve data from the database!";
}
if (!$error) {
	if (!isset($start_id) && empty($start_id)) {
		$start_id=$min_id;
	}
	if ($start_id<$max_id) {
		$query="SELECT min(user_id) FROM users WHERE user_id>'$start_id'";
		if (!($res=@mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res)) {
			$nextlink="<a href=\"pic_inspector.php?start_id=".mysql_result($res,0,0)."\">Next</a>";
		}
	}
	if ($start_id>$min_id) {
		$query="SELECT max(user_id) FROM users WHERE user_id<'$start_id'";
		if (!($res=@mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res)) {
			$prevlink="<a href=\"pic_inspector.php?start_id=".mysql_result($res,0,0)."\">Previous</a>";
		}
	}
}

if (!$error) {
	$user_id=$start_id;
	$name="";
	$query="SELECT a.user_id,a.profilelink,user FROM users a WHERE user_id='$start_id'";
	if (!($res=@mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		list($user_id,$plink,$name)=mysql_fetch_row($res);
	}


	$pictures=get_album_pic2($start_id);
	$i=0;

	if (empty($pictures)) {
	$nophoto='no-pict.gif';
	$dellink="";
			$photos[$i]['picture_name']=_IMAGESURL_."/$nophoto";
			$photos[$i]['dellink']=$dellink;

		}

		else {
	$photos=array();

	while (list($k,$v)=each($pictures)) {
	$dellink="<a class=dellinkpicinspect href=\"${relative_path}admin/pic_inspector.php?start_id=$start_id&delpic=$k\">Delete</a>";

		$photos[$i]['picture_name']=_THUMBSURL_."/$v";
		$photos[$i]['picture_number']=$k;
		$photos[$i]['dellink']=$dellink;
		$i++;
	}
}
	$tpl->set_file('content','admin/pic_inspector.html');
	$tpl->set_var('prevlink',$prevlink);
	$tpl->set_var('nextlink',$nextlink);
	$tpl->set_var('start_id',$start_id);

	$tpl->set_var('name',$name);
	$tpl->set_var('plink',$plink);
	$tpl->set_loop('photos',$photos);
	$content=$tpl->process('out','content',1);
}

$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','Picture Inspector');
$tpl->set_var('content',$content);
$tpl->set_var('message',$message);
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('relative_path',$relative_path);


print $tpl->process('out','frame',0,1);
?>
