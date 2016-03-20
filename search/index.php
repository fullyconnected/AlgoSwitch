<?php


//error_reporting(0);
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
db_connect();
$message = '';
$next ='';
$prev ='';
if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];

}else{

$myuser_id = 0;

}
$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());

//  paging 

// Number of records to show per page
$recordsPerPage = 4; 

// default startup page
$pageNum = 1;

if(isset($_GET['p'])) {
  $pageNum = $_GET['p'];
  settype($pageNum, 'integer');
}

$offset = ($pageNum - 1) * $recordsPerPage;

// paging end?


if(isset($_GET['radius'])){
	
unset($_COOKIE['selectedradius']); 
$selectedradius = $_GET['radius'];
setcookie("selectedradius",$selectedradius, time() + (3600 * 24 * 30), '/');

}else{

if (isset($_COOKIE['selectedradius'])){
	
	$selectedradius = $_COOKIE['selectedradius'];
	setcookie("selectedradius",$selectedradius, time() + (3600 * 24 * 30), '/');
	
}else{

    $selectedradius = _DEFAULT_RADIUS_;
}
//  ;)

if(isset($_COOKIE['selectedradius'])){
$selectedradius = $_COOKIE['selectedradius'];
}


}


if(isset($_GET['length'])){
	
//unset($_COOKIE['length']); 

$length = $_GET['length'];


setcookie("length",$length, time() + (3600 * 24 * 30), '/');

}else{

if(isset($_COOKIE['length'])){
$length = $_COOKIE['length'];
setcookie("length",$length, time() + (3600 * 24 * 30), '/');

}
	
if(!empty($_COOKIE['length'])){
$length = $_COOKIE['length'];
}else{
	
	$length = _DEFAULT_RADIUS_TYPE_;
	
}	
	
}
if(empty($_COOKIE['posLat'])|| (empty($_COOKIE['posLon']))){
	redirect2page("index.php");
}
$lat = $_COOKIE['posLat'];
$lon = $_COOKIE['posLon'];



if ($length == 1){

$lengthtextmiles = 'Miles';	

$sql = "SELECT user_id,user,gender,country,us_state,city,birthdate,profilelink,last_visit, (((ACOS(SIN('".$lat."' * PI() / 180) * SIN(lat * PI() / 180) + COS(".$lat." * PI() / 180) * COS(lat * PI() / 180) * COS(('".$lon."' - lon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)) AS distance FROM users WHERE is_approved = '1' AND status='"._STATUSACTIVE_."' AND user_id!=1 AND user_id!=$myuser_id  HAVING distance<='$selectedradius'  ORDER BY distance ASC LIMIT $offset, $recordsPerPage";
$sqlnolimit = "SELECT user_id,user,gender,country,us_state,city,birthdate,profilelink,last_visit, (((ACOS(SIN('".$lat."' * PI() / 180) * SIN(lat * PI() / 180) + COS(".$lat." * PI() / 180) * COS(lat * PI() / 180) * COS(('".$lon."' - lon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)) AS distance FROM users WHERE is_approved = '1' AND status='"._STATUSACTIVE_."' AND user_id!=1 AND user_id!=$myuser_id  HAVING distance<='$selectedradius'  ORDER BY distance ASC";

}elseif ($length == 2 ){

$lengthtextmiles = 'Kilometres';

$sql = "SELECT user_id,user,gender,country,us_state,city,birthdate,profilelink,last_visit, (((ACOS(SIN('".$lat."' * PI() / 180) * SIN(lat * PI() / 180) + COS(".$lat." * PI() / 180) * COS(lat * PI() / 180) * COS(('".$lon."' - lon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515))*1.609344 AS distance FROM users WHERE is_approved = '1' AND status='"._STATUSACTIVE_."' AND user_id!=1 AND user_id!=$myuser_id HAVING distance<='$selectedradius' ORDER BY distance ASC LIMIT $offset, $recordsPerPage ";

$sqlnolimit = "SELECT user_id,user,gender,country,us_state,city,birthdate,profilelink,last_visit, (((ACOS(SIN('".$lat."' * PI() / 180) * SIN(lat * PI() / 180) + COS(".$lat." * PI() / 180) * COS(lat * PI() / 180) * COS(('".$lon."' - lon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515))*1.609344 AS distance FROM users WHERE is_approved = '1' AND status='"._STATUSACTIVE_."' AND user_id!=1 AND user_id!=$myuser_id HAVING distance<='$selectedradius' ORDER BY distance ASC";


}




$tpl->set_var('accepted_miles',vector2options($accepted_distance,$selectedradius,array(_CHOOSE_,_NDISCLOSED_)));

$tpl->set_var('accepted_radiustext',vector2options($accepted_radiustext,$length,array(_CHOOSE_,_NDISCLOSED_)));

$sel = mysql_query($sql) or die($sql."<br/><br/>".mysql_error());

$sellimit = mysql_query($sqlnolimit) or die($sql."<br/><br/>".mysql_error());


$foundmembers= array();
$i=0;
//print_r($sql);

$num_rows = mysql_num_rows($sellimit);




while ($row = mysql_fetch_row($sel))
	

{
/* // who time?
		if(strtotime($mysql_timestamp) > strtotime("-30 minutes")) {
		 $thisvar = true;
*/
	$foundmembers[$i]['country']=getCountry($row[3]);
	$foundmembers[$i]['state']=getRegion($row[4]);
	$foundmembers[$i]['city']=getCity($row[5]);
	$foundmembers[$i]['profilelink']=$row[7];
	$foundmembers[$i]['distance']=floor($row[9]).' '.$lengthtextmiles;
	$foundmembers[$i]['age']=determine_age($row[6]);
	$foundmembers[$i]['name']=$row[1];
	$foundmembers[$i]['gender']=$accepted_genders[$row[2]];
	$foundmembers[$i]['last_visit']=nicetime($row[8],1);
	$foundmembers[$i]['user_id']=$row[0];
	$foton = get_photo($row[0]);

	if ($foton == 'no-pict.gif') {
		
	$gender = $row[2];
	$foton="no-pict$gender.gif";
	}
	$foundmembers[$i]['picture']=_MEDTHUMBSURL_.'/'.$foton;
	
     //$numrows = $row[0];  # 4

     $maxPage = ceil($num_rows/$recordsPerPage);

     $nav = '';

     for($page = 1; $page <= $maxPage; $page++) {
     if ($page == $pageNum)     {
        $nav .= "<li class=\"currentpage\">$page</li>";
     }
     else
     {
    
         $nav .= "<li><a href=\"javascript:htmlData('processors/new_members_ajax.php','p=$page')\">$page</a></li>";
     }
   }

   if ($pageNum > 1) {

     $page = $pageNum - 1;
     $prev = "<a href=\"index.php?p=$page\"><strong>Prev</strong></a></li>";

     $first = "<a href=\"index.php?p=1\"><strong>&#171;</strong></a></li>";
   }
   else {
     $prev = '';
     $first = '';
   }

   if ($pageNum < $maxPage) {
     $page = $pageNum + 1;
     $next = "<a href=\"index.php?p=$page\"><strong>Next</strong></a>";

     $last = "<a href=\"index.php?p=$maxPage\"><strong>&#187;</strong></a>";
   }
   else {
     $next = '';
     $last = '';
   }
	



	$i++;
}

$tpl->set_var('next',$next);
$tpl->set_var('prev',$prev);


$tpl->set_var('lang', $lang);
$tpl->set_var('milestext',$lengthtextmiles);

$tpl->set_file('middlecontent','search/aroundme.html');

$tpl->set_var('message',$message);
$tpl->set_var('siteURL',_BASEURL_);
$tpl->set_loop('foundmembers',$foundmembers);
$middle_content=$tpl->process('out','middlecontent',1);
$title=$lang['SEARCH_RESULTS_LOCATION'];
include('../blocks/block_search.php');
?>
