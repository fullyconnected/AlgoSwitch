<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/pager.php");
require("includes/apt_functions.inc.php");
$access_level=$access_matrix['portfolio_promote'][0];
$message = '';
$thestate = '';
$picname = '';

db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();


if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}
global $relative_path;
$mymembership='';
if(isset($_SESSION['user_id'])){
$query="SELECT profession,membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession,$mymembership)=mysql_fetch_row($res);
}
if($mymembership == 5){$admin=1;}

 $tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
 $tpl->set_var('relative_path', $relative_path);
 $tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
	$user_id=addslashes_mq($_GET['user_id']);
	$u2="&user_id=$user_id";
}else{
$user_id=$_SESSION['user_id'];
$u2="&user_id=$user_id";
}
if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

	$message=$topass['message'];
}

$page = 1;
// how many records per page
$size = 8;
 
// Get the current page from $_GET
if (isset($_GET['page'])){
    $page = (int) $_GET['page'];
}
	
	$sqly = "SELECT COUNT(user_id) as num FROM user_buddies  WHERE approval ='0' AND buddy_id='$user_id' ";
	$total_pages = mysql_fetch_array(mysql_query($sqly));
	$total_pages = $total_pages['num'];
	$pagination = new Pagination();
	$pagination->setLink("myfriends.php?page=%s$u2");
	$pagination->setPage($page);
	$pagination->setSize($size);
	$pagination->setTotalRecords($total_pages);
 	$pagy = $pagination->create_links();
    global $relative_path;

$query="SELECT DISTINCT id,buddy_id,user_id FROM user_buddies WHERE buddy_id='$user_id' AND approval ='0' ORDER BY user_id ASC ". $pagination->getLimitSql();
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$myreturn='';
	
	if (mysql_num_rows($res)) {
	
			
		$i=1;
		while ($i < $rsrow=mysql_fetch_row($res)) {
           		
			$index = $rsrow[0];  
			$newuser_id = $rsrow[2];
  	 
		$query="SELECT user_id, user, city, us_state, country, profilelink FROM users WHERE user_id='$newuser_id'";
  
                 	if (!($new=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
				
		$rsnrow=mysql_fetch_row($new);                      
		$picname = get_photo($rsnrow[0]);
		$gengen = get_gender($rsnrow[0]);	
			
			
			if ($picname=="no-pict.gif") {
			$picname="no-pict$gengen.gif";
			}

             if (isset($rsnrow[4]) ){
				 
				$location = getCountry( $rsnrow[4]).'';
			 }
   				
	$zlink = $rsnrow[5];
	$mage = get_age($rsnrow[0]);
	$uname = remove_underscore(get_name($rsnrow[0]));
	$myreturn.="<li class=\"span3\"><input type=\"checkbox\" name=\"del[$index]\" value=\"$rsnrow[0]\" />
	<a href=\"$zlink\">
	
	<div class=\"\"><img src=\"memberpictures/medthumbs/$picname\" width=\"100%\" border=\"0\" alt=\"$uname\">
	<br />
	<a  href=\"$zlink\">    $uname,  $mage</a> 
	
	
	<strong class=\"pull-right\">$location</strong><br>

<a class=\"btn btn-warning pull-right\" href=\"mail_send.php?user_id=$rsnrow[0]\" title=\" $uname\">".$lang['FRIENDS_SEND_MAIL']."</a>";


$myreturn.="</div></li>";

	$i++;



	}

	
	}

if (!empty($myreturn)){
$friends = $myreturn;
}else{

//

$friends = "<div align=\"center\"><h3>".$lang['FRIENDS_NO_FRIENDS']."</h3></div>";

}

					

$profilelink=_BASEURL_."/".get_name($_SESSION['user_id']);

$tpl->set_var('profilelink',$profilelink);

$name = get_name($user_id);
$tpl->set_file('middlecontent','friends/myfriends.html');
$tpl->set_var('message',$message);
$tpl->set_var('friends',$friends);
$tpl->set_var('name',$name);
$tpl->set_var('friendcount',get_total_friends($user_id));
$tpl->set_var('friendrcount',get_total_friendsrequest($user_id));
$tpl->set_var('paging',$pagy);
$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,true);

$title=$lang['FRIENDS_MY'];
include('blocks/block_main_frame.php');
?>