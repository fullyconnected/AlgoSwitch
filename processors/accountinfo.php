<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
require("../includes/class.inputfilter.php");
$access_level=_REGISTERLEVEL_;


db_connect();
check_login_member();
$filterObj = new InputFilter(NULL, NULL, 1, 0, 0);
$topass=array();
$error=false;
$user_id = $_SESSION['user_id'];
$phone2 = ''; // unused






 
if ($_GET) {

	$name=$filterObj->process($_GET['name']);
	$plink=$filterObj->process($_GET['plink']);
	$gender=$filterObj->process($_GET['gender']);
	$country=$filterObj->process($_GET['country']);
	$us_state=$filterObj->process($_GET['state']);
	$city=$filterObj->process($_GET['city']);
	$language=$filterObj->process($_GET['lang']);
	
/*
if(!empty($country) && empty($us_state) && empty($city)){
	
		$latlon =  lat_lon($country,1);
		
}elseif(!empty($country) && !empty($us_state) && empty($city)){
	
		$latlon =  lat_lon($us_state,2);
		
}elseif(!empty($country) && !empty($us_state) && !empty($city)){
	
		$latlon =  lat_lon($city,3);
}else{
	
	$latlon = '';
}
	
	$lat = $latlon['latitude'];
	$lon = $latlon['longitude'];
*/

	$lat = $_COOKIE['posLat'];
	$lon = $_COOKIE['posLon'];




	
	$zip=$filterObj->process($_GET['zip']);
	$addr=$filterObj->process($_GET['addr']);
	$phone1=$filterObj->process($_GET['phone1']);
	$website=$filterObj->process($_GET['website']);
   	$birthmonth=$filterObj->process($_GET['birthmonth']);
	$birthday=$filterObj->process($_GET['birthday']);
	$birthyear=$filterObj->process($_GET['birthyear']);
	$birthdate=$birthyear."-".$birthmonth."-".$birthday;
	$plink = $filterObj->process($plink);
	$plink = cleanString($plink);

// name check
if (!empty($name) && (strlen($name)>=3) && !strstr($name,'%') && !strstr($name,'?') && !strstr($name,'&') && !strstr($name,'\'') && !strstr($name,'"') && !strstr($name,'\\') && !strstr($name,'/')) 


{
			$query="SELECT user_id FROM users WHERE user='$name'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			list($name_zid)=mysql_fetch_row($res);
			$myuserid=$_SESSION['user_id'];
			if(isset($name_zid)){
			if($name_zid != $myuserid){
				$error=true;
				$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ACCOUNT_INFO_NAME_TAKEN']."</font></div>";
		}
		}} else {
			$error=true;
			$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ACCOUNT_INFO_NAME_INVALID']." </font></div>";

		}


// end name check

if (!empty($plink) && (strlen($plink)>=3) && !strstr($plink,'%') && !strstr($plink,'?') && !strstr($plink,'&') && !strstr($plink,'\'') && !strstr($plink,'"') && !strstr($plink,' ') && !strstr($plink,'\\') && !strstr($plink,'/')) 


{
			$query="SELECT user_id FROM users WHERE profilelink='$plink'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			list($name_id)=mysql_fetch_row($res);
			$myuserid=$_SESSION['user_id'];
			if(isset($name_id)){
			if($name_id != $myuserid){
				$error=true;
				$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ACCOUNT_INFO_PROFILE_TAKEN']."</font></div>";
		}
		}} else {
			$error=true;
			$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ACCOUNT_INFO_PROFILE_INVALID']."</font></div>";

		}
		
	
		
		
		
	if ($birthmonth == _CHOOSE_) {
	$error=true;
		$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ACCOUNT_INFO_BIRTH_MONTH_MESS']."</font></div>";
		
		}
		
		if ($birthday == _CHOOSE_) {
	$error=true;
		$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ACCOUNT_INFO_BIRTH_DAY_MESS']."</font></div>";
		
		}
		
		
	if ($birthyear == _CHOOSE_) {
	$error=true;
		$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ACCOUNT_INFO_BIRTH_YEAR_MESS'] ."</font></div>";
		
		}

		$yearnow=date("Y");
		if($birthyear > $yearnow){
		$error=true;
		$topass['message']="<div class=\"dotz\" align=\"center\">
<font class=\"alert\">".$lang['ACCOUNT_INFO_THE_CURRENT_YEAR_IS']." $yearnow ".$lang['ACCOUNT_INFO_YOU_HAVE_SELECTED']." $birthyear ".$lang['ACCOUNT_INFO_YOU_HAVE_SELECTED']."</font></div>";
		}

		}


   	  global $unaccepted_profile_names;

   	if (in_array($plink, $unaccepted_profile_names)) {
   	    $error=true;
         	 $topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_24']."</font></div>";
   	}
		
		


if (!$error) {

$query="UPDATE users SET user='$name',profilelink='$plink',gender='$gender',country='$country',us_state='$us_state',city='$city',birthdate='$birthdate',phone1='$phone1',phone2='$phone2',website='$website',zip='$zip',addr='$addr',lat='$lat',lon='$lon',language='$language' WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}

$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ACCOUNT_INFO_UPDATE_SUCCESS']."</font></div>";
}

$_SESSION['age']=$birthdate;
$topass['country']=$country;
$topass['name']=$name;
$topass['gender']=$gender;
$topass['us_state']=$us_state;
$topass['city']=$city;
$topass['zip']=$zip;
$topass['plink']=$plink;
$topass['addr']=$addr;
$topass['phone1']=$phone1;
$topass['website']=$website;
$topass['birthday']=$birthday;
$topass['birthmonth']=$birthmonth;
$topass['birthyear']=$birthyear;

$_SESSION['lang'] = $language;
setcookie("lang", $language, time() + (3600 * 24 * 30));

$topass['language']=$language;

 
 
 

redirect2page("accountinfo.php",$topass);

?>
