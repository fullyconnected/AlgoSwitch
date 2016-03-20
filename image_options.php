<?php // move image, edit keywords, image name, caption, 18 plus
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
require("includes/thumbnail.inc.php");

$access_level=$access_matrix['myprofile_album'][0];
db_connect();
check_login_member();

if(!empty($_GET['rotate'])){
$rotate = $_GET['rotate'];
}else{
$rotate = '';
}

if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}
global $relative_path;

$photo = addslashes_mq($_GET['photo']);
if (empty($photo)){
redirect2page("myprofile_album.php");
}
$id = addslashes_mq(intval($_GET['id']));
$pid = addslashes_mq(intval($_GET['pid']));
$name = get_name($myuser_id);
$proff = get_profession2($myuser_id);
$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$query="SELECT * FROM user_album2 WHERE fk_user_id='".$_SESSION['user_id']."' AND picture_name ='$photo' AND id='$id'";


if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($unumber,$picnum,$picname,$id,$mainphoto,$createdate,$caption,$views,$adult,$imgtitle)=mysql_fetch_row($res);


if($mainphoto == 1){
$pcheck ='CHECKED';
}else{
$pcheck = '';
}

$mainimage = "\t\t<input ".$pcheck." type=\"checkbox\" name=\"mainimage\"  value=\"1\"\">";
;


$moveimage = get_image_album_pulldown($myuser_id,$id);
$tpl->set_var('moveimage',$moveimage);
$tpl->set_var('adult',$adult);

$tpl->set_var('mainimage',$mainimage);

$tpl->set_var('relative_path', $relative_path);



if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";


if(isset($topass['message'])&&(!empty($topass['message']))){
$message=$topass['message'];}
else {
$message="";
}

$pid = $topass['pid'];
$id = $topass['id'];

	
}

else {
$message="";


}
if (isset($_SESSION['id'])){
$id = $_SESSION['id'];
}

$thumb = PhpThumbFactory::create(_IMAGESPATH_."/".$photo);

if ($rotate == 'cw'){

$thumb->rotateImage('CW');


$thumb->resize(_BIGHEIGHT_,_BIGWIDTH_);
$thumb->save(_IMAGESPATH_."/$photo");


$thumb->resize(_MEDTHUMBHEIGHT_,_MEDTHUMBWIDTH_);
$thumb->save(_MEDTHUMBSPATH_."/$photo");

$thumb->resize(_THUMBHEIGHT_,_THUMBWIDTH_);
$thumb->save(_THUMBSPATH_."/$photo");
}
if ($rotate == 'ccw'){

$thumb->rotateImage('CCW');


$thumb->resize(_BIGHEIGHT_,_BIGWIDTH_);
$thumb->save(_IMAGESPATH_."/$photo");


$thumb->resize(_MEDTHUMBHEIGHT_,_MEDTHUMBWIDTH_);
$thumb->save(_MEDTHUMBSPATH_."/$photo");

$thumb->resize(_THUMBHEIGHT_,_THUMBWIDTH_);
$thumb->save(_THUMBSPATH_."/$photo");

}


$piclink = "picview.php?user_id=$myuser_id&id=$id&pageno=$pid";
$albumname = get_album_name($myuser_id,$_GET['id']);
$randy = md5( uniqid (rand(), 1)); // random string

$tpl->set_file('middlecontent','albums/image_options.html');
$tpl->set_var('piclink',$piclink);
$tpl->set_var('id',$id);   
$tpl->set_var('randy',$randy);   
$tpl->set_var('pid',$pid);
$tpl->set_var('name',$name);
$tpl->set_var('userid',$myuser_id);
$tpl->set_var('proff',$proff);
$tpl->set_var('photo',$photo);
$tpl->set_var('albumname',$albumname);
$tpl->set_var('imgtitle',$imgtitle);
$tpl->set_var('message',$message);
$tpl->set_var('caption',stripslashes($caption));
$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,1);

$title=$lang['ALBUMS_IMAGE_OPTIONS_PAGE_TITLE'];
include('blocks/block_main_frame.php');
?>
