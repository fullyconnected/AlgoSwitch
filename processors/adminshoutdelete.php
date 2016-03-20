<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();
	//Handle delete request
	
if (isset($_GET['action']) && !empty($_GET['action'])) {
	$action=addslashes_mq($_GET['action']);

$adnum = $_GET['adnum'];
$posterid = $_GET['poster_id'];
if ($action=='delete') {

		if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
			$theuserid=$_SESSION['user_id'];
		}
			
			if($theuserid == 1){
				$query="DELETE FROM shoutout WHERE fk_user_id='$posterid' AND adnum='$adnum'";
				if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
				$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['SHOUT_ADMIN_DELETE'] ."</font></div>";
				//$_SESSION['foundshoutouts']="";
				redirect2page("shoutout.php");
			} 
	}

}

?>