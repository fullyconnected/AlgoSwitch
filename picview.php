<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
$access_level=$access_matrix['picsview'][0];

db_connect();
check_login_member();
$nextlink='';
$prevlink='';
$nextlinkpic='';
$closingtag='';
$mymembership='';
$adultQ ='';
if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}
global $relative_path;

$_SESSION['thisimage'] = curPageURL();

$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());


$tpl->set_var('relative_path', $relative_path);

unset($_SESSION['foundphotocomments']);
$id = intval($_GET['id']);

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
	$user_id=addslashes_mq($_GET['user_id']);
} else {$user_id="";}


if (isset($_GET['picno'])) {
   $picno = intval($_GET['picno']);
   $pquery = "AND picture_number='".$picno."'";
   
} else {
   $picno = '';
   $pquery = '';
} 

if (isset($_SESSION['worksafe'])){

$adultQ = '';
}else{
$adultQ = 'AND adult !=1';

}
if(isset($_SESSION['user_id'])){
$query="SELECT profession,membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession,$mymembership)=mysql_fetch_row($res);
}

// private profile check

$privoption = check_profile_private2($user_id);
$friendscheck = is_buddy($user_id);

if ($mymembership ==5){

}else{

switch ($privoption) {

   case "1" :

if(is_buddy($user_id)){
//echo "buddys";
}else{

if ($user_id!=$myuser_id){
// echo "Only friends";
$friendlink ="<a href=\"${relative_path}processors/friends.php?action=addfriend&user_id=$user_id\"><b>Add as Friend</b>";
$membername=get_name($user_id);
$topass['message']="$membername has set there profile viewable to friends only.<br>$friendlink";
redirect2page("private_profile.php?u=$user_id",$topass);
}

}
   break;

   case "2" :

	if ($user_id!=$myuser_id){

	$topass['message']="This member has set there profile to private";
	redirect2page("private_profile.php?u=$user_id",$topass);
}
   break;

   default :
  // echo "everyone can view";
   break;

   }

}
// end private profile check

$query = "SELECT count(picture_name) FROM user_album2 WHERE fk_user_id='".$user_id."' AND id='".$id."' $adultQ";
$result = mysql_query($query) or trigger_error("SQL", E_USER_ERROR);
$query_data = mysql_fetch_row($result);
$numrows = $query_data[0];

$rows_per_page = 1;
$lastpage  = ceil($numrows/$rows_per_page);
if (isset($_GET['pageno'])) {
   $pageno = intval($_GET['pageno']);
   $piccounts = " ( ".$lang['ALBUMS_PICVIEW_PHOTO']." $pageno ".$lang['ALBUMS_PICVIEW_OF']." $lastpage ) ";
   $nexttext=$lang['NEXT'];
} else {
   $pageno = 1;
   $piccounts = "";
   $nexttext='';
} 

$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;


// @ errors supressed for direct linking to 18+ images (depricated)
$query = "SELECT picture_name,caption,created_on,views,imgtitle,adult,picture_number FROM user_album2 WHERE fk_user_id='".$user_id."' AND id='".$id."'  $adultQ $pquery ORDER BY picture_number ASC $limit";
@$result = mysql_query($query) or trigger_error("SQL");


$photo=@mysql_result($result,0,0);
$caption=@mysql_result($result,0,1); 
$caption=(stripslashes($caption));
$date =@mysql_result($result,0,2);
$views =@mysql_result($result,0,3);
$imgtitle=@mysql_result($result,0,4);
$adult=@mysql_result($result,0,5);
$picnumber=@mysql_result($result,0,6);

$tpl->set_var('albumnam',get_album_name($user_id,$id));

if ($pageno == 1) {
} else {

   $prevpage = $pageno-1;
   $prevlink = " <a id='slide_previous' href='{$_SERVER['PHP_SELF']}?pageno=$prevpage&user_id=$user_id&id=$id'><u>".$lang['PREV']."</u></a> ";

}
	if ($pageno == $lastpage) {

} else {

	$nextpage = $pageno+1;
	$nextlink= " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage&user_id=$user_id&id=$id'><u>$nexttext</u></a> ";
	$nextlinkpic= " <a id='slide_next' href='{$_SERVER['PHP_SELF']}?pageno=$nextpage&user_id=$user_id&id=$id'> ";
	$closingtag = "</a>";
} 

$tpl->set_var('user_id',$user_id);
$tpl->set_var('piccounts',$piccounts);
	$query="SELECT fk_user_id,comment_id,poster_id,comment,date_posted FROM photo_comments WHERE fk_user_id='$user_id' AND picture_name='$photo' ORDER BY comment_id DESC";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
		$foundphotocomments=array();

		
		$i=0;
		while ($rsrow=mysql_fetch_row($res)) {
	
			$poster=get_name($rsrow[2]);
			if(!isset($poster) || empty($poster)){
				$foundphotocomments[$i]['postername']="Anonymous";
			}
			else {
			$foundphotocomments[$i]['postername']=$poster;
		}
		
		$whoyou = get_photo($rsrow[2]);
		$gengen = get_gender($rsrow[2]);

			if ($whoyou=="no-pict.gif") {
			$whoyou="no-pict$gengen.gif";
			}
	
	            if(!isset($whoyou) || empty($whoyou)){
			$foundphotocomments[$i]['picom']="no-pic.gif";
	            }else{
	            $foundphotocomments[$i]['picom']=$whoyou;
	            }
			$foundphotocomments[$i]['user_id']=$rsrow[0];
			$foundphotocomments[$i]['poster_id']=$rsrow[2];
			$foundphotocomments[$i]['comment_id']=$rsrow[1];
			$foundphotocomments[$i]['comment']=$rsrow[3];
			$foundphotocomments[$i]['postdate']=$rsrow[4];
			$foundphotocomments[$i]['plink']=get_profile_link($rsrow[2]);
			$foundphotocomments[$i]['profes']=get_profession2($rsrow[2]);
	
			$ownerid=$rsrow[0];
			if(isset($_SESSION['user_id']) == $ownerid){
				$dellink="<a href=\"${relative_path}processors/comment_delete_photo.php?comment_id=$rsrow[1]&user_id=$ownerid&pageno=$pageno&id=$id\">".$lang['ALBUMS_COMMENT_DELETE']."</a>";}
				else{$dellink="";}
			$foundphotocomments[$i]['delete']=$dellink;
			$i++;
		}
		
		$_SESSION['foundphotocomments']=$foundphotocomments;
		
		
	}


if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";
}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));


$plink = get_profile_link($user_id);
$tpl->set_var('baseurl',_BASEURL_);




if ($myuser_id!=$user_id) {
$tpl->set_var('views',"$views");
	$query="UPDATE user_album2 SET views=views+1 WHERE fk_user_id='$user_id' AND picture_name ='$photo'";
	@mysql_query($query);
}else{
$tpl->set_var('views',"$views");
}


// photo options link

if(!empty($_SESSION['user_id'])){

if($_SESSION['user_id']===$user_id){


$optionlink = "<a href=\"image_options.php?id=$id&photo=$photo&pid=$myuser_id\">".$lang['ALBUMS_OPTIONS_TEXT']."</a>";

$tpl->set_var('optionlink',$optionlink);

}else {
$tpl->set_var('optionlink','');

}
}
if (empty($photo)){
redirect2page("$plink");
}

$showlist = get_photo_fav_list2($user_id,$photo);

if($showlist!=1){
$listword = $lang['PICVIEW_LIST'];
$tpl->set_var('listword',$listword);
$tpl->set_var('showlist',$showlist);
}else{
$tpl->set_var('showlist',"");
$listword = "";
$tpl->set_var('listword',$listword);
}
$_SESSION['redirect'] = curPageURL();
$tpl->set_var('picnumber',$picnumber);
$tpl->set_var('closingtag',$closingtag);
$tpl->set_var('nextlinkpic',$nextlinkpic);
$tpl->set_var('nextlink',$nextlink);
$tpl->set_var('prevlink',$prevlink);
$tpl->set_file('middlecontent','albums/pic_view_photo.html');

$tpl->set_var('photo',$photo);
$tpl->set_var('message',$message);
$tpl->set_var('id',$id);
$tpl->set_var('pageno',$pageno);

$date = date ("Y-m-d H:i:s", $date);
$ownername = get_name($user_id);

$tpl->set_var('photoowner',$ownername);
$tpl->set_var('ownerid',$user_id);
$tpl->set_var('plink',$plink);

$ownername2 = remove_underscore($ownername);
$tpl->set_var('photoowner2',$ownername2);
$tpl->set_var('photocount',$numrows);  
if(!empty($caption)){

$tpl->set_var('capword',"".$lang['ALBUMS_CAPTION'].":");
$tpl->set_var('caption',$caption); 
}else{

$tpl->set_var('capword',""); 
$tpl->set_var('caption',""); 
}
if(!empty($imgtitle)){

$tpl->set_var('titleword',"".$lang['ALBUMS_TITLE_TEXT'].":");
$tpl->set_var('imgtitle',remove_underscore($imgtitle)); 
}else{

$tpl->set_var('titleword',""); 
$tpl->set_var('imgtitle',""); 
}



$tpl->set_var('pdate',date($date)); 
$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,1);

if(!isset($myuser_id) || empty ($myuser_id)){
$tpl->set_file('outmenu','menu/outmenu.html');
$tpl->set_var('relative_path', $relative_path);
$outmenu=$tpl->process('out','outmenu',0,1);

}

else {

$tpl->set_file('outmenu','menu/outmenu_in.html');
$tpl->set_var('relative_path', $relative_path);
$outmenu=$tpl->process('out','outmenu',0,1);

}



$sitename=_SITENAME_;
$tpl->set_file('frame','frame.html');
$tpl->set_var('title',"$sitename - $ownername2  ".$lang['ALBUMS_ALBUM'].": ".get_album_name($user_id,$id)." ".$lang['ALBUMS_PICVIEW_PHOTO']." $pageno ".$lang['ALBUMS_PICVIEW_OF']." $lastpage");

$tpl->set_var('sitename',_SITENAME_);
$tpl->set_var('relative_path', $relative_path);

$tpl->set_var('outmenu',$outmenu);


$tpl->set_var('lang', $lang);

$tpl->set_var('middle_content',$middle_content);

print $tpl->process('out','frame',0,1);
?>
