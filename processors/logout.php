<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_GUESTLEVEL_;

db_connect();

$reg=true;
if (isset($_SESSION['user_id'])) {
	$query="DELETE FROM online_status WHERE fk_user_id='".$_SESSION['user_id']."'";
	mysql_query($query);



	$query2="update users SET lastactivity=UNIX_TIMESTAMP(NOW()) WHERE user_id='".$_SESSION['user_id']."'";
	mysql_query($query2);

	$reg=false;


}


       
$_SESSION=array();
session_destroy();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ". gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate",false);
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache",false);

header("Location: "._BASEURL_."/index.php");

?>
