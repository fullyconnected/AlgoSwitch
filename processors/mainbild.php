<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");

$access_level=$access_matrix['myprofile_album'][0];
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();




$message=((isset($topass['message'])) ? ($topass['message']) : (""));
$picture = addslashes_mq($_GET['main']);
$picture = str_replace('\\"', '', $picture);





$query="UPDATE user_album2 SET mainphoto = 1  WHERE picture_name='".$picture."'";
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
$query="UPDATE user_album2 SET mainphoto = 0  WHERE fk_user_id='".$_SESSION['user_id']."' AND picture_name != '".$picture."'";
	if (!($res=@mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}

?>

