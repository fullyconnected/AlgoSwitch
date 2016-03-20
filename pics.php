<?php
session_start();
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
require("includes/vars.inc.php");
$access_level=$access_matrix['picsview'][0];
$user_id = '';
$adultQ = '';
$nextlink='';
$prevlink='';
$plink='';

db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();
	
$usrID = (int)$_GET['user_id'];
$countalbums ='0';
$picstable ='';
if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}
else {$myuser_id = "";}
global $relative_path;
$whoyoube = get_name($usrID);

$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$tpl->set_var('relative_path', $relative_path);


if(!empty($_GET['id'])){
		
		$id = (intval($_GET['id']));
		$tpl->set_file('middlecontent','albums/albums_pic_grid.html');	
	}else{
		//query check for first album	
		$q = "select MAX(id) from user_album_cat WHERE fk_user_id=$usrID";
		$result = mysql_query($q);
		$data = mysql_fetch_array($result);
		$id = $data[0];	
	
		$tpl->set_file('middlecontent','albums/albums_pic_cat.html');	
}





// albums 
$table_cols=4;
$imgurl=_IMAGESURL_;

$Aphotos=get_album_cat($usrID);



$albumtable="";
if (!empty($Aphotos)) {
	$albumtable="";
	$albumtable.="\t";
	$i=1;

	while (list($k)=each($Aphotos)) 
	{
		$albumnam = $Aphotos[$k]['album_name'];
		
		$albumimage = $Aphotos[$k]['imagefile'];
		$albumid = $Aphotos[$k]['id'];
		if (!file_exists(_IMAGESPATH_.'/'.$albumimage)) {
				$albumimage = 'no-pict.jpg';
				}
		
		if (empty($albumimage)){
		$albumimage = 'no-pict.jpg';
		}

		

$countalbums = countalbums($usrID);


$albumtable.= "<li class=\"span3\">
	
	


<a href=\"$plink\"><div><a href=\"${relative_path}pics.php?user_id=$usrID&id=$albumid \" title=\"$albumnam\"><img src=\""._MEDTHUMBSURL_."/$albumimage\" width=\"100%\"></a>";


$albumtable.="<br><a href=\"${relative_path}pics.php?user_id=$user_id&id=$k\" title=\"$albumnam\"><span>$albumnam</span></a></div></li>";


		$i++;
	}

}

 
	if ($countalbums == 1){
	$tpl->set_var('albumtable','');
		}else{
	$tpl->set_var('albumtable',$albumtable);
	}	
// end albums
$countalbumimages = count_album_images($usrID,$id);

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
	$user_id=addslashes_mq($_GET['user_id']);
} else {$user_id="";}

if (isset($_GET['pageno'])) {
   $pageno = $_GET['pageno'];
} else {
   $pageno = 1;
} 

$plink = get_profile_link($user_id);
$ageallowed = _AGEALLOWED_;
//if(!empty($_SESSION['age'])) {
//$realage = determine_age($_SESSION['age']);	
//}

/*
if (empty($_SESSION['age'])){
$adultQ = 'AND adult !=1';

}
if ($realage <= $ageallowed){
$adultQ = 'AND adult !=1';
}*/

$query = "SELECT count(picture_name), count(views) FROM user_album2 WHERE fk_user_id='".$user_id."' AND id='".$id."' $adultQ";
$result = mysql_query($query) or trigger_error("SQL", E_USER_ERROR);
$query_data = mysql_fetch_row($result);
$numrows = $query_data[0];

$rows_per_page = 12;
$table_cols= "4";
$lastpage  = ceil($numrows/$rows_per_page);	
$pageno = (int)$pageno;
if ($pageno > $lastpage) {
   $pageno = $lastpage;
} 
if ($pageno < 1) {
   $pageno = 1;
}

$limit = 'ORDER BY picture_number ASC LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;


$_SESSION['lastpage'] = curPageURL();



/*
if (empty($_SESSION['worksafe'])){
$onoff = "<a href=\"worksafe.php?foo=0\"><span class=\"grey\">".$lang['ALBUMS_OFF']."</span></a>|".$lang['ALBUMS_ON'] ."";

}
if(isset($_SESSION['worksafe'])){
$worksafe = $_SESSION['worksafe'];
}else{
$worksafe =1;
}
switch ($worksafe){

	case "1" :
$onoff = "<a href=\"worksafe.php?foo=0\"><span class=\"grey\">".$lang['ALBUMS_OFF']."</span></a>|".$lang['ALBUMS_ON']."";
break;

case "0" :
$onoff = "OFF|<a href=\"worksafe.php?foo=1\"><span class=\"grey\">".$lang['ALBUMS_ON']."</span></a>";
break;

}
*/
$query = "SELECT picture_name,id,picture_number,fk_user_id,views,adult,imgtitle FROM user_album2 WHERE fk_user_id='".$user_id."' AND id='".$id."' $adultQ $limit";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}

if (mysql_num_rows($res)) {
	
		//$picstable="<table width=\"100%\" border=\"10\" cellspacing=\"1\" align=\"center\" valign=\"top\">";

		$i = $pageno*$rows_per_page +$numrows - $numrows -$rows_per_page +1;

		$ageallowed = _AGEALLOWED_;
	if (!empty($_SESSION['age'])){
		$realage = $_SESSION['age'];
	}else{
		$realage = '';
	}
	while ($i < $rsrow=mysql_fetch_row($res)) {

		$adulton = $rsrow[5];
		$imgtitle = remove_underscore($rsrow[6]);
		
	if ($realage <= $ageallowed  && $adulton ==1){
		$zlink ='';
		$photos	= "adultplus.jpg";
		
		}else{
	$zlink ="<a href=\"${relative_path}picview.php?pageno=$i&user_id=$user_id&id=$id\" class=\"vertical\" title=\"$imgtitle\">";
	$photos= $rsrow[0];
}

	if (empty($_SESSION['age']) && $adulton ==1   ){
	
	
	
	$zlink = "<a href=\"#\" class=\"vertical\" title=\"$imgtitle\">";
	$photos= "adultplus.jpg";
}
	//$image = "$zlink<img class=\"mainimage\" src=\""._MEDTHUMBSURL_."/$photos\" border=\"0\" />";

$image =		"{ url: '"._IMAGESURL_."/$photos', caption: ''},";


	$picturenum = $rsrow[2];
	$memberid = $rsrow[3];
	$viewcount =  $rsrow[4];


// $total_photo_comments1 ".$lang['ALBUMS_COMMENTS']."<br> $viewcount ".$lang['ALBUMS_VIEWS']." <br /> $listed
$picstable.="$image";


		
			$i++;
		
		
		}
		
}
		
		
if ($pageno == 1) {
} else {

   $prevpage = $pageno-1;
   $prevlink = " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage&user_id=$user_id&id=$id'><u>PREV</u></a> ";
}
	
	$piccounts = " ( Page $pageno of $lastpage ) ";  //  
	if ($pageno == $lastpage) {
 
} else {
   $nextpage = $pageno+1;
   $nextlink= " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage&user_id=$user_id&id=$id'><u>NEXT</u></a> ";

   
   
} 
if ($numrows == 0){
$message = "<div class=\"dotz\" align=\"center\"><font class=\"alert\">this album is empty</font></div>";
}

if ($countalbums ==1){
$albumcount =" ";
}else{
$albumcount ="($countalbums) Albums ";
}


$tpl->set_var('albumtable',$albumtable);
$tpl->set_var('albumcount',$albumcount);

$tpl->set_var('id',$id);
//$tpl->set_var('onoff',$onoff);

$tpl->set_var('username',$whoyoube);
$tpl->set_var('albumname',get_album_name($usrID,$id));

/*
if (isset($id)){
	
$tpl->set_file('middlecontent','albums/albums_pic_grid.html');	
	
}else{
	
$tpl->set_file('middlecontent','albums/albums_pic_view.html');	
	
}
*/

$tpl->set_var('picstable',$picstable);




$tpl->set_var('countalbumimages',$countalbumimages);
$tpl->set_var('piccounts',$piccounts);

$tpl->set_var('user_id',$usrID);
$tpl->set_var('plink',$plink);
$tpl->set_var('nextlink',$nextlink);
$tpl->set_var('prevlink',$prevlink);
$ownername = get_name($user_id);
$tpl->set_var('photoowner',$ownername);
$ownername2 = remove_underscore($ownername);
$tpl->set_var('photoowner2',$ownername2);

$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,true);

$sitename=_SITENAME_;
$title="$sitename - $ownername2 ".get_album_name($myuser_id,$id);
include('blocks/block_gallery.php');
?>
