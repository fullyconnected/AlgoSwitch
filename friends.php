<?php
session_start();

require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
require("includes/pager.php");
$access_level=$access_matrix['member_view'][0];

db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();

if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
	$user_id=addslashes_mq($_GET['user_id']);
}elseif (!isset($_GET['user_id']) && (isset($_GET['uname']))){
	$thename=$_GET['uname'];
	$user_id=get_userid_by_name($thename);
	if(!exists($user_id)){
		$topass['message']="User does not appear to be a member";
		redirect2page('inform_page.php',$topass);
	}
} elseif (!isset($_GET['user_id']) && !isset($_GET['by_name']) && (isset($_SESSION['user_id']))){
	$user_id=$_SESSION['user_id'];
}else {$user_id="";}

global $relative_path;

$mymembership='';
if(isset($_SESSION['user_id'])){
$query="SELECT profession,membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession,$mymembership)=mysql_fetch_row($res);
}

if($mymembership == 5){$admin=1;}

$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());

$tpl->set_var('relative_path', $relative_path);


if (isset($_GET['uid']) && !empty($_GET['uid'])) {
	$uid=addslashes_mq($_GET['uid']);
	$u2="&uid=$uid";
}



$page = 1;
// how many records per page
$size = 8;
 
// Get the current page from $_GET
if (isset($_GET['page'])){
    $page = (int) $_GET['page'];
}

$sqly = "SELECT DISTINCT COUNT(user_id) as num FROM user_buddies WHERE buddy_id='$uid' AND approval !=1";
	$total_pages = mysql_fetch_array(mysql_query($sqly));
	$total_pages = $total_pages['num'];
	$pagination = new Pagination();
	$pagination->setLink("friends.php?page=%s$u2");
	$pagination->setPage($page);
	$pagination->setSize($size);
	$pagination->setTotalRecords($total_pages);
 	$pagy = $pagination->create_links();
    global $relative_path;
	$showuserfriend_members= "12";	         // -> change this
	$table_cols= "4";		// -> change this
	$wearefriends="";

$query="SELECT DISTINCT buddy_id,user_id,approval FROM user_buddies WHERE buddy_id='$uid' AND approval !=1 ORDER BY user_id ASC ". $pagination->getLimitSql();
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$myreturn='';
	
	if (mysql_num_rows($res)) {
	
	
		$i=1;
		while ($i < $rsrow=mysql_fetch_row($res)) {
                       
				   
		
                        $newuser_id = $rsrow[1];
						$zname = get_name($rsrow[1]);
						$plink =  _BASEURL_."/".get_profile_link($rsrow[1]);
     		  $query="SELECT user_id, user, city,us_state,country FROM users WHERE user_id='$newuser_id'";
                 	if (!($new=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
              	

						$rsnrow=mysql_fetch_row($new);                      
                        $picname = get_photo($rsnrow[0]);
			$gengen = get_gender($rsnrow[0]);

			if ($picname=="no-pict.gif") {
			$picname="no-pict$gengen.gif";
			}

			$prof = get_profession2($newuser_id);
                        $uname = $zname;
						$uname2 = remove_underscore($zname);
						
			if (($i%$table_cols)==1) {$wearefriends.="";}
			$wearefriends.="";
			$wearefriends.=" <li class=\"span3\"><div><a href=\"$plink\" title=\"$uname\"><img src=\"memberpictures/medthumbs/$picname\" width=\"100%\"  ><br />$uname2<br>$prof</a>";
			$wearefriends.="</div></li> ";
			if ($i%$table_cols==0) {$wearefriends.="";}
			$i++;
		}
		

		
		$wearefriends.="";
		

}
$name = get_name($uid);
$plink2 = _BASEURL_."/".get_profile_link($uid);



$howmanyfriendsyougot = get_total_friends($uid);

$tpl->set_var('friendcount',$howmanyfriendsyougot);

$tpl->set_file('middlecontent','friends/friends.html');

$tpl->set_var('pager',$pagy);

$tpl->set_var('friends',$wearefriends);
$tpl->set_var('name',$name);
$tpl->set_var('lang', $lang);
$tpl->set_var('plink',$plink2);

$middle_content=$tpl->process('out','middlecontent',0,true);

$title="Friends of $name";
include('blocks/block_main_frame.php');
?>
