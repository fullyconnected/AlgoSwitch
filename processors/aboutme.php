<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
require("../includes/class.inputfilter.php");
$access_level=$access_matrix['mydetails'][0];


db_connect();
check_login_member();
$filterObj = new InputFilter(NULL, NULL, 1, 1, 1);
$topass=array();
$error=false;
if ($_SERVER['REQUEST_METHOD']=='POST') {
	
	//$my_diz = $filterObj->process(mysql_real_escape_string($_POST['my_diz']));

	$my_diz = mysql_real_escape_string($_POST['my_diz']);
	
	
	$search_arr = array("<a href");
	$replace_arr = array("<a rel=\"nofollow\" target=\"_blank\" href");
		$my_diz = str_replace($search_arr,$replace_arr,$my_diz);
	
	/*if(preg_match("/\\w{40,}/", $my_diz)){
	$error=true;
	$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">Cannot accept words that have 40 or more letters.</font></div>";
	$topass['my_diz']=$my_diz;
	redirect2page("aboutme.php",$topass);
	}*/

	$biolimit=_BIOLIMIT_;
	$mydiz=preg_replace("/\s+/"," ",$my_diz);
	$cntmydiz=split(' ',$mydiz);


	$worddif=(count($cntmydiz) - $biolimit);
	$yours=count($cntmydiz);

	if (count($cntmydiz) > $biolimit){
	$error=true;
	$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">Your bio cannot contain more than ($biolimit) words. You have tried to submit ($yours) words. Please edit your bio by ($worddif) words.</font></div>";
	$topass['my_diz']=$my_diz;
	redirect2page("aboutme.php",$topass);
	}


	$query="UPDATE users SET my_diz='$my_diz' WHERE user_id='".$_SESSION['user_id']."'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	
	$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['ABOUT_UPDATED']."</font></div>";
	$topass['message']=$message;
}
redirect2page("aboutme.php",$topass);
