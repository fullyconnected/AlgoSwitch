<?php // image options
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
require("../includes/class.inputfilter.php");
$access_level=$access_matrix['myprofile_album'][0];


db_connect();
check_login_member();
$filterObj = new InputFilter(NULL, NULL, 1, 1, 1);
$topass=array();
$error=false;
$oldid = '';

if ($_SERVER['REQUEST_METHOD']=='POST') {
		
	$id = intval($_POST['id']);
	$mainphoto = mysql_real_escape_string(addslashes_mq($_POST['mainphoto']));
	$mainimage = intval($_POST['mainimage']);  //  on/off   1/0
	$photo = mysql_real_escape_string(addslashes_mq($_POST['photo']));
	$pid = intval($_POST['pid']);  	
    $caption = mysql_real_escape_string(addslashes_mq($filterObj->process($_POST['caption'])));
	$imgtitle=mysql_real_escape_string($_REQUEST['imgtitle']);
	$imgtitle = $filterObj->process($imgtitle);
	$adult = intval($_POST['adultcheck']);

	$malbum = mysql_real_escape_string($_POST['malbum']); // move album
	$search_arr = array("<a href");
	$replace_arr = array("<a rel=\"nofollow\" target=\"_blank\" href");
	$caption = str_replace($search_arr,$replace_arr,$caption);

	if(preg_match("/\\w{40,}/", $caption)){
	$error=true;
	$topass['message']="<div class=\"dotz\"><font class=\"alert\">We cannot accept words that have 40 or more letters.</font></div>";
	$topass['caption']=stripslashes($caption);
	$topass['id']=$id;
	$topass['pid']=$pid;
	redirect2page("image_options.php?id=$id&photo=$photo&pid=$pid",$topass);

	}


	$caplimit=200;
	$caption=preg_replace("/\s+/"," ",$caption);
	$cntmydiz=split(' ',$caption);
	$worddif=(count($cntmydiz) - $caplimit);
	$yours=count($cntmydiz);

	if (count($cntmydiz) > $caplimit){
	$error=true;
	$topass['message']="<div class=\"dotz\"><font class=\"alert\">Your caption cannot contain more than ($caplimit) words. <br> You have tried to submit ($yours) words. <br> Please edit your caption by ($worddif) words.</font></div>";
	$topass['caption']=stripslashes($caption);
	$topass['id']=$id;
	$topass['pid']=$pid;
	redirect2page("image_options.php?id=$id&photo=$photo&pid=$pid",$topass);
	}



if($mainimage=='1' && $adult =='1'){
$topass['message']="<div class=\"alert\">".$lang['ALBUMS_MESS_ADULT_MAIN_IMAGE']."</div>";
	$topass['caption']=$caption;
	$topass['id']=$id;
	$topass['pid']=$pid;
	redirect2page("image_options.php?id=$id&photo=$photo&pid=$pid",$topass);
}
// count images in album id
$query = "SELECT max(picture_number) FROM user_album2 WHERE fk_user_id='".$_SESSION['user_id']."' AND id='".$malbum."'";
$result = mysql_query($query) or trigger_error("SQL", E_USER_ERROR);
$query_data = mysql_fetch_row($result);
$numrows = $query_data[0];
$picid = $numrows+1;

if($mainimage=='1'){


$query="UPDATE user_album2 SET mainphoto = 1  WHERE picture_name='".$photo."'";
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
$query="UPDATE user_album2 SET mainphoto = 0  WHERE fk_user_id='".$_SESSION['user_id']."' AND picture_name != '".$photo."'";
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}


}
//update captions/image title
$query="UPDATE user_album2 SET caption='$caption',imgtitle='$imgtitle',adult='$adult',id='$id' WHERE fk_user_id='".$_SESSION['user_id']."' AND picture_name ='$photo' ";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}


//move image
if ($oldid != $malbum){
$query="UPDATE user_album2 SET id = '$malbum', picture_number='$picid' WHERE fk_user_id='".$_SESSION['user_id']."' AND picture_name ='$photo'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
}
//update album_id photo_comments

	
}	


$_SESSION['id']=$malbum;
redirect2page("image_options.php?id=$malbum&photo=$photo&pid=$pid",$topass);
?>
