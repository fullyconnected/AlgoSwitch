<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
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

$photos=get_album_cat($_SESSION['user_id']);
$picstable="";
$deletealbum ='';
$albumimage = '';
$albumnam = '';
$albumid = '';
$templateurl = get_my_template();
if (!empty($photos)) {
	$picstable="\n";
	$i=1;
	while (list($k)=each($photos)) {
		if (($i%$table_cols)==1) {$picstable.="<tr>\n";}
		//$albumimage = get_album_image($_SESSION['user_id'],$k);
		$albumimage =  $photos[$k]['imagefile'];	
		$albumid =  $photos[$k]['id'];	
		$albumnam = $photos[$k]['album_name'];	

		if (!file_exists(_IMAGESPATH_.'/'.$albumimage)) {
			$albumimage = 'no-pict.jpg';
			}

			if (empty($albumimage)){
		$albumimage = 'no-pict-upload.jpg';
		}
	

$deletealbum = "<a class=\"btn btn-mini\" href=\"${relative_path}processors/myprofile_album.php?deletealbum=$albumid\"><font size=1 color=\"red\">".$lang['ALBUMS_DELETE']."</font></a>";


$picstable.="<li class=\"span3\"><div><a href=\"${relative_path}myprofile_pictures.php?id=$albumid\"><img src=\""._MEDTHUMBSURL_."/$albumimage\" /></a><br>  


<span id=\"$albumid\" class=\"editText\">$albumnam</span><img src=\"templates/$templateurl/images/edit.png\" border=\"0\">
		<br>$deletealbum";
		$picstable.="</div></li>";
		
		
	
		$i++;
	}

	

	
}
$whoyoube = get_name($_SESSION['user_id']);
$tpl->set_var('username',$whoyoube);

$memberplan = get_member_payplan($_SESSION['user_id']);

if ($memberplan == 0){
$maxpics = get_site_option('max_user_pics');
}else{
$maxpics = get_member_maxpic($_SESSION['user_id']);
}

$tpl->set_var('user_id',$_SESSION['user_id']);
$tpl->set_var('max_pics',$maxpics);
$tpl->set_file('middlecontent','albums/myprofile_album.html');
$tpl->set_var('message',$message);
$tpl->set_var('picstable',$picstable);
$howmanyphotos = get_picturecount($_SESSION['user_id']);
$tpl->set_var('pic_count',$howmanyphotos);
$tpl->set_var('album_count',count($photos));
$tpl->set_var('lang', $lang);

$middle_content=$tpl->process('out','middlecontent',0,true);


$title=$lang['ALBUMS_TITLE'];
include('blocks/block_upload.php');
?>
