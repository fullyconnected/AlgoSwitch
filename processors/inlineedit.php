<?php // www.rocky.nu
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0

require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");

db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();


$user_id = intval($_SESSION['user_id']);
$fieldname = intval($_GET['fieldname']);



$content = clean_stringfor_title(mysql_real_escape_string($_GET['content']));

$content = preg_replace("/[^a-zA-Z0-9.\s]/", "", $content);

$query="UPDATE user_album_cat set album_name = '$content' WHERE fk_user_id ='$user_id' AND id = '$fieldname'";
		if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}



//THIS WRITES CONTENT TO A TEXT FILE   :)
//$handle = fopen($_GET['fieldname'].".txt", "w+");
//fwrite($handle, stripslashes($_GET['content']));
//fclose($handle);

$content = addslashes_mq($content);


echo stripslashes(substr($content, 0, 26));
?> 
