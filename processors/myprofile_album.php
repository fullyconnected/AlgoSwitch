<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
include_once("../includes/thumbnail.inc.php");
$access_level=$access_matrix['myprofile_album'][0];
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();

$message='';
$howmanyphotos = get_picturecount($_SESSION['user_id']);

$memberplan = get_member_payplan($_SESSION['user_id']);
if ($memberplan != 0){
$max_user_pics=get_member_maxpic($_SESSION['user_id']);
}else{
$max_user_pics=get_site_option('max_user_pics');
}


if ($_SERVER['REQUEST_METHOD']=='POST') {
	$id =intval($_POST['id']);

	$error=false;
	if (isset($_FILES['new_pic']['tmp_name']) && is_uploaded_file($_FILES['new_pic']['tmp_name'])) {
		$ext=strtolower(substr(strrchr($_FILES['new_pic']['name'],"."),1));
		if (!in_array($ext,$accepted_upload_extensions)) {
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ALBUMS_MESS_TOO_SMALL']."</font></div>";
		} elseif ($_FILES['new_pic']['size']==0) {
			$error=true;
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ALBUMS_MESS_TOO_SMALL']."</font></div>";
		} elseif ($max_user_pics == $howmanyphotos){
		$error= true;
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ALBUMS_MESS_MAX_ALLOWED']."</font></div>";

		}elseif ($_FILES['new_pic']['size']>_MAXPICSIZE_) {
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ALBUMS_MESS_TOO_BIG']."</font></div>";
		}else {
		
	$query = "SELECT max(picture_number) ,count(picture_number) FROM user_album2 WHERE fk_user_id='".$_SESSION['user_id']."' AND id='$id'";
	$result = mysql_query($query) or trigger_error("SQL", E_USER_ERROR);
	$query_data = mysql_fetch_row($result);

			if (isset($query_data[0])){
			$piccounting = $query_data[1];
			$numrows = $query_data[0];
			$picno = $numrows+1;
			}else{
			$picno = 1;	

			}
			$tupload = time();
			$photo=$tupload.'-'.$_SESSION['user_id']."_pic$picno.".$ext;
			if (file_exists(_IMAGESPATH_.'/'.$photo)) {
				@rename(_IMAGESPATH_.'/'.$photo,_IMAGESPATH_.'/'.$photo.'-');
			}
			if (!move_uploaded_file($_FILES['new_pic']['tmp_name'], _IMAGESPATH_.'/'.$photo)) {
				$error=true;
				$message.="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ALBUMS_ERROR_TO_MOVE']."</font></div>";
				$photo='';
				@rename(_IMAGESPATH_.'/'.$photo.'-',_IMAGESPATH_.'/'.$photo);
                
				@chmod(_IMAGESPATH_."/$photo",0644);
			}
		}
		
		
		if (!$error) {
		$emptyalbum = is_album_cat_empty($_SESSION['user_id'],$id); // check if album is empty
		$tupload = time();
$query="INSERT INTO user_album2 SET fk_user_id='".$_SESSION['user_id']."',picture_number='$picno',picture_name='$photo', mainphoto='0',id='$id',created_on='$tupload'";
if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		
            $userwatermark = _WATERMARK_;
			if ($userwatermark == 1){
			$pt = PhpThumb::getInstance();
			$pt->registerPlugin('GdWatermarkLib','gd');
			$thumb = PhpThumbFactory::create(_IMAGESPATH_."/$photo");  // LARGE PHOTO
			$thumb->resize(_BIGHEIGHT_,_BIGWIDTH_)->createWatermark(_IMAGESPATH_."/watermark/watermark.png");
			$thumb->save(_IMAGESPATH_."/$photo");
			}else{
			$thumb = PhpThumbFactory::create(_IMAGESPATH_."/$photo");  // LARGE PHOTO
			$thumb->resize(_BIGHEIGHT_,_BIGWIDTH_);
			$thumb->save(_IMAGESPATH_."/$photo");
			}

			$thumb = PhpThumbFactory::create(_IMAGESPATH_."/$photo");   // MEDIUM THUMBNAILS
			$thumb->resize(_MEDTHUMBHEIGHT_,_MEDTHUMBWIDTH_);
			$thumb->save(_MEDTHUMBSPATH_."/$photo");
		
			$thumb = PhpThumbFactory::create(_IMAGESPATH_."/$photo");  // SMALL THUMBNAILS
			$thumb->resize(_THUMBHEIGHT_,_THUMBWIDTH_);
			$thumb->save(_THUMBSPATH_."/$photo");
			$message.="<div class=\"dotz\" align=\"center\"><font class=\"alert\">Photo Uploaded!</font></div>";
				
				
				
// check if album has a main photo selected
		$checkformainphoto = has_main_photo($_SESSION['user_id']);
		$getrndphoto = get_random_album_pic($_SESSION['user_id']);

	if (!empty($getrndphoto) &&($checkformainphoto !=1)){  
	$query="UPDATE user_album2 SET mainphoto = 1  WHERE picture_name='".$getrndphoto."' AND adult !=1";
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	
	
	
	}
	
		if ($emptyalbum == false){
		$query="UPDATE user_album_cat SET imagefile='".$photo."' WHERE id='".$id."' AND fk_user_id='".$_SESSION['user_id']."'";
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	}
		
			}
	
		}
	
		
	} else {
	if (isset($_GET['delete']) && !empty($_GET['delete']) && ($_GET['delete']>=1) && ($_GET['delete'])) {
		$id = intval($_GET['id']);
		$del=addslashes_mq($_GET['delete']);
		$foton=addslashes_mq($_GET['foton']);
	
		
		
		$query="DELETE FROM user_album2 WHERE fk_user_id='".$_SESSION['user_id']."' AND picture_number='$del' AND picture_name='$foton'";
		if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		if (file_exists(_IMAGESPATH_.'/'.$foton)) {
					@unlink(_IMAGESPATH_.'/'.$foton);
		}
				if (file_exists(_THUMBSPATH_.'/'.$foton)) {
					@unlink(_THUMBSPATH_.'/'.$foton);
		}
		if (file_exists(_MEDTHUMBSPATH_.'/'.$foton)) {
					@unlink(_MEDTHUMBSPATH_.'/'.$foton);
		}
		
		
// get new random photo if main photo deleted
		$checkformainphoto = has_main_photo($_SESSION['user_id']);
		$getrndphoto = get_random_album_pic($_SESSION['user_id']);

		if (!empty($getrndphoto) &&($checkformainphoto !=1)){  
		$query="UPDATE user_album2 SET mainphoto = 1  WHERE picture_name='".$getrndphoto."'";
		if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		}
// end new main bild
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ALBUMS_MESS_IMAGE_DELETE']."</font></div>";
	} else {
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ALBUMS_MESS_INVALID_SELECTED']."</font></div>";

}
if (isset($_GET['deletealbum']) && !empty($_GET['deletealbum']) && ($_GET['deletealbum']>=1)) {
		
$delalbum = addslashes_mq($_GET['deletealbum']);
$emptyalbum = is_album_cat_empty($_SESSION['user_id'],$delalbum); // check if album is empty
if ($emptyalbum == false){
$query="DELETE FROM user_album_cat WHERE fk_user_id='".$_SESSION['user_id']."' AND id='$delalbum'";
		if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
$message = "<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ALBUMS_MESS_ALBUM_DELETED']."</font></div>";
$topass=array('message'=>$message);
redirect2page("myprofile_album.php",$topass);

}else{
$message = "<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ALBUMS_MESS_NOT_EMPTY']."</font></div>";
$topass=array('message'=>$message);
redirect2page("myprofile_album.php",$topass);
	}
}
if (isset($_GET['highlight']) && !empty($_GET['highlight']) && !empty($_GET['id'])) {

$id = intval($_GET['id']);
$highlight = mysql_real_escape_string($_GET['highlight']);
$thephoto=mysql_real_escape_string($_GET['foton']);


$query="SELECT * FROM user_album2 WHERE fk_user_id='".$_SESSION['user_id']."' AND picture_name ='$thephoto' AND id='$id'";


if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($unumber,$picnum,$picname,$id,$mainphoto,$createdate,$caption,$views,$adult,$imgtitle)=mysql_fetch_row($res);

if($adult == 1){

$message = "<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ALBUMS_MESS_NO_ADULT']."</font></div>";
$topass=array('message'=>$message);
redirect2page("myprofile_pictures.php?id=$id",$topass);
}


$query="UPDATE user_album_cat SET imagefile='".$thephoto."' WHERE id='".$id."' AND fk_user_id='".$_SESSION['user_id']."'";
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}

$message = "<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ALBUMS_MESS_HIGHLIGTED']."</font></div>";

$topass=array('message'=>$message);
redirect2page("myprofile_pictures.php?id=$id",$topass);
	}
}

$topass=array('message'=>$message);
redirect2page("myprofile_pictures.php?id=$id",$topass);

?>
