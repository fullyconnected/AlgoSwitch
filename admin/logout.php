<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();

$_SESSION=array();
session_destroy();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ". gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate",false);
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache",false);

header("Location: "._BASEURL_."/admin/index.php");
?>
