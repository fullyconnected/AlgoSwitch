<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
require("includes/vars.inc.php");
$access_level=$access_matrix['myprofile_album'][0];


db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();

if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}
global $relative_path;

if(empty($_GET['id'])){
$message="WTF!";
$topass=array('message'=>$message);
redirect2page("myprofile_album.php",$topass);
}else{
$id = (INT)$_GET['id'];
}

if(isset($_SESSION['user_id'])){
$query="SELECT profession,membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession,$mymembership)=mysql_fetch_row($res);
}

$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
 $tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$tpl->set_var('relative_path', $relative_path);

if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";
}

$message=((isset($topass['message'])) ? ($topass['message']) : (""));
$table_cols=4;
$photos=array();
$photos=get_album_pic2($_SESSION['user_id'],$id);
$tpl->set_var('id',$id);
$picstable="";
$randy = md5( uniqid (rand(), 1));


if (!empty($photos)) {
	$picstable="";
	$i=1;
	while (list($k,$v)=each($photos)) {

	$imalink = "${relative_path}image_options.php?id=$id&photo=$v&pid=$k";
	if (($i%$table_cols)==1) {$picstable.="<tr>\n";}
		

	$picstable.="<li class=\"span2\"><div><a href=\"$imalink\"><img class=\"mainimage\" src=\""._MEDTHUMBSURL_."/$v?$randy\"  border=\"0\" /></a><br />
<a class = \"btn btn-danger btn-mini\" href=\"${relative_path}processors/myprofile_album.php?delete=$k&id=$id&foton=$v\">".$lang['ALBUMS_DELETE_PHOTO']."</a>
<a class =  \"btn btn-info btn-mini\" href=\"${relative_path}image_options.php?id=$id&photo=$v&pid=$k\">".$lang['ALBUMS_IMAGE_OPTIONS']."</a>
<a class = \"btn btn-primary btn-mini\" href=\"${relative_path}processors/myprofile_album.php?highlight=$k&id=$id&foton=$v\">".$lang['ALBUMS_MAKE_HIGHLIGHT']."</a>";


	
			
		$i++;
	}

	$picstable.="</div></li> ";

}
$whoyoube = get_name($_SESSION['user_id']);
$tpl->set_var('username',$whoyoube);
$tpl->set_var('id',$id);
$memberplan = get_member_payplan($_SESSION['user_id']);
if ($memberplan != 0){
$max_user_pics=get_member_maxpic($_SESSION['user_id']);
}else{
$max_user_pics=get_site_option('max_user_pics');
}

$tpl->set_var('albumname',get_album_name($_SESSION['user_id'],$id));

$tpl->set_file('middlecontent','albums/myprofile_pictures.html');
$tpl->set_var('message',$message);
$tpl->set_var('picstable',$picstable);
$tpl->set_var('pic_count',count($photos));




$tpl->set_var('max_user_pics',$max_user_pics);




if (count($photos)>=$max_user_pics) {
	$tpl->set_var('formstyle',"style='visibility:hidden;display:block'");
}




$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,true);


$title="Photo Manager";
include('blocks/block_upload.php');
?>
