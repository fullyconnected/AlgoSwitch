<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
require("includes/profile_functions.php");
$access_level=$access_matrix['member_view'][0];

db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);

check_login_member();
$get_my_template = get_my_template();
if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}
else {$myuser_id = "";}
$mymembership='';
$links_menu='';
global $accepted_ages;

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
	$user_id=addslashes_mq($_GET['user_id']);
}elseif (!isset($_GET['user_id']) && (isset($_GET['profilelink']))){
	$plink=$_GET['profilelink'];
	
	
	$user_id=get_profile_link_name($plink);
	
	
	if(!exists($user_id)){
		$topass['message']="User does not appear to be a member";
		redirect2page('inform_page.php',$topass);
	}
} elseif (!isset($_GET['user_id']) && !isset($_GET['by_name']) && (isset($_SESSION['user_id']))){

	$user_id=$_SESSION['user_id'];

}else {$user_id="";

redirect2page("login.php");

}
global $relative_path;

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
$topass['message']="$membername ".$lang['PROFILE_PRIVATE_FRIENDS']."<br>$friendlink";

	redirect2page("private_profile.php?u=$user_id",$topass);
}

}
   break;

   case "2" :
  // echo "only me";
	if ($user_id!=$myuser_id){

	$topass['message']=$lang['PROFILE_PRIVATE'];
	redirect2page("private_profile.php?u=$user_id",$topass);
}
   break;

   default :
  // echo "everyone can view";
   break;

   }

}
// end private profile check


$name = get_name($user_id);
$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$tpl->set_var('relative_path', $relative_path);


$query="SELECT website,firstname,lastname,profilelink,gender,DATE_FORMAT(NOW(),'%Y')-DATE_FORMAT(birthdate,'%Y')-(DATE_FORMAT(NOW(),'00-%m-%d')<DATE_FORMAT(birthdate,'00-%m-%d')),country,us_state,city,my_diz,membership,views,joindate,last_visit,lat,lon FROM users WHERE user_id='$user_id'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($website,$firstname,$lastname,$plink,$gender,$age,$country,$us_state,$city,$my_diz,$membership,$views,$joindate,$lastvisit,$lat,$lon)=mysql_fetch_row($res);

$nameclean = remove_underscore($name);
$website = preg_replace('#^[^:/.]*[:/]+#i', '', $website); //this regex remove http:// from website url
$picpath = _MEDTHUMBSURL_;	
$izvip = is_vip($user_id);
$vip_image = 'vip.gif';
$foton = get_photo($user_id);

if ($foton == 'no-pict.gif') {
$foton="no-pict$gender.gif";
}


$base = _BASEURL_;

$templatesurl = _TPLURL_.'/'.get_my_template();


$mainphoto = '<img class="mainimage" src="img.php?src=/memberpictures/'.$foton.'&amp;w=200&amp;h=250&amp;zc=1" alt="'.$name.'" border="0" />';



$titlename='';
if ($user_id==$myuser_id) {

	/// 


} else {
	$titlename=$name."'s";

$links_menu.="<a class=\"btn btn-large btn-success\" href=\"${relative_path}mail_send.php?user_id=$user_id\">".$lang['PROFILE_CONTACT']."</a>";

if(is_buddy($user_id)){


$links_menu.="";
	
	
	}else {

$links_menu.="<a class=\"btn btn-large btn-warning\" href=\"${relative_path}processors/friends.php?action=addfriend&user_id=$user_id\">".$lang['PROFILE_ADD_FRIEND']."</a>";}

}

// website link
if ($website === '' || $website === '0'){
$tpl->set_var('formstyle1',"style='visibility:hidden;display:none'");
}else {
$tpl->set_var('website',$website);
}
$tpl->set_var('poster',$myuser_id);

// Custom profiles according to profession here  EX: change html page name.. 
    				$pprofession=get_profession($user_id);
					if($pprofession == "1") {
					$switch="profiles/general_view.html"; // models
                    
                    }elseif($pprofession == "2"){ 
					$switch="profiles/general_view.html";// photographers
					}else {
					$switch="profiles/general_view.html";// everyone else
			}
			
$tpl->set_file('middlecontent',$switch);
/////
	

$tpl->set_var('titlename',htmlentities(stripslashes($titlename)));

$tpl->set_var('links_menu',$links_menu);

$tpl->set_var('relative_path',$relative_path);
$link_back="";

$nameclean = remove_underscore($name);
$tpl->set_var('name',ucwords(strtolower($name)));
$tpl->set_var('name2',ucwords(strtolower($nameclean)));
$tpl->set_var('firstname',ucwords(strtolower($firstname)));
$tpl->set_var('lastname',ucwords(strtolower($lastname)));
if (is_admin($user_id)){

$tpl->set_var('joindate',"");
}else{
$joindate = date("m-d-Y", $joindate);
$joinhtml =  "<span>".$lang['PROFILE_JOINED']."</td><td align=\"left\">$joindate</span>";
$tpl->set_var('joindate',$joinhtml);
}

$lastvisit = date("Y-m-d G:i:s",$lastvisit); // format for nicetime func
$lastvisit =  nicetime($lastvisit);


$tpl->set_var('lastvisit',$lastvisit);



$pprof=get_profession2($user_id);
$photos=get_album_pic2($user_id);				
$photocount = get_picturecount($user_id);  

$tpl->set_var('views',$views);
$tpl->set_var('proff',$pprof);
$tpl->set_var('pcount',$photocount);


$tpl->set_var('mainphoto',$mainphoto);
$tpl->set_var('user_id',$user_id);
$tpl->set_var('pic_count',count($photos));

if(!isset($my_diz)||empty($my_diz)){
	$my_diz='';
$tpl->set_var('my_diz',"".$lang['PROFILE_BIO_DEFAULT']."");
}else {
$tpl->set_var('my_diz',stripslashes_mq($my_diz));
}
//$my_diz=stripslashes_mq($my_diz);
if(!isset($gender)||empty($gender)){
$tpl->set_var('gender',$lang['PROFILE_INFO_UNKNOWN']);
}else {
$tpl->set_var('gender',$accepted_genders[$gender]);
}


$tpl->set_var('age',$age);
// Recent profile views
if(!empty($_SESSION['user_id'])){
if($user_id != $_SESSION['user_id']){
profile_visit($user_id,$_SESSION['user_id']);
}
}
$recentviewers = show_last_visted($user_id,5);
$tpl->set_var('recentviewers',$recentviewers);						
// EOF

$title="Viewing Profile: $titlename";
$tpl->set_var('title',$title);

if (friendcount($user_id) > 8){
$viewallfriends = "<a href=\"friends.php?uid=$user_id\"><b>".$lang['PROFILE_VIEW_ALL_FRIENDS']."</b></a>";
}else{
$viewallfriends = "";
}

$tpl->set_var('viewallfriends',$viewallfriends);


if (friendcount($user_id) == 1){
$s = '';
}else{
$s = 's';
}
$tpl->set_var('s',$s);
$tpl->set_var('friendcount',friendcount($user_id));


if ($myuser_id!=$user_id) {
$tpl->set_var('views',$views);
	$query="UPDATE users SET views=views+1 WHERE user_id='$user_id'";
	@mysql_query($query);
}


$membermapshow = show_map_bit(0,$lat,$lon);
$tpl->set_var('membermap',$membermapshow);

$tpl->set_var('country',getCountry($country));

$tpl->set_var('state',getRegion($us_state));

$tpl->set_var('city',getCity($city));


$tpl->set_var('lang', $lang);
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$middle_content=$tpl->process('out','middlecontent',0,true);
$sname = _SITENAME_;

$title=remove_underscore($name)." / $pprof ";


include('blocks/block_profile.php');
?>
