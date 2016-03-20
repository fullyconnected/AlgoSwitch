<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=$access_matrix['mydetails'][0];


db_connect();
check_login_member();

$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$hairlength=addslashes_mq($_POST['hairlength']);
	$haircolor=addslashes_mq($_POST['haircolor']);
	$hairtype=addslashes_mq($_POST['hairtype']);
	$hairlength=addslashes_mq($_POST['hairlength']);
	if (isset($_POST['hairpieces']) && !empty($_POST['hairpieces'])) {
			     $hairpieces=vector2binvalues(addslashes_mq($_POST['hairpieces']));
			} else {
			     $hairpieces=0;
		}
	$eyeshape=addslashes_mq($_POST['eyeshape']);
	$eyecolor=addslashes_mq($_POST['eyecolor']);
	$eyebrows=addslashes_mq($_POST['eyebrows']);
	$eyelashes=addslashes_mq($_POST['eyelashes']);
if (isset($_POST['eyewear']) && !empty($_POST['eyewear'])) {
			     $eyewear=vector2binvalues(addslashes_mq($_POST['eyewear']));
			} else {
			     $eyewear=0;
		}

if (isset($_POST['ethnicmakeup']) && !empty($_POST['ethnicmakeup'])) {
			     $ethnicmakeup=vector2binvalues(addslashes_mq($_POST['ethnicmakeup']));
			} else {
			     $ethnicmakeup=0;
		}

	$faceshape=addslashes_mq($_POST['faceshape']);
	$hips_inseam=addslashes_mq($_POST['hips_inseam']);
	$height=addslashes_mq($_POST['height']);
	$weight=addslashes_mq($_POST['weight']);
	$waist=addslashes_mq($_POST['waist']);
	$shoes=addslashes_mq($_POST['shoes']);
	$bodytype=addslashes_mq($_POST['bodytype']);
	$dress_shirt=addslashes_mq($_POST['dress_shirt']);
	$chest=addslashes_mq($_POST['chest']);



	$query="UPDATE users SET hairlength='$hairlength',hairtype='$hairtype',haircolor='$haircolor',hairpiece='$hairpieces',eyeshape='$eyeshape',eyecolor='$eyecolor',eyebrows='$eyebrows',eyelashes='$eyelashes',eyewear='$eyewear',faceshape='$faceshape',bodytype='$bodytype',chest='$chest',height='$height',weight='$weight',waist='$waist',chest='$chest',shoes='$shoes',hips_inseam='$hips_inseam',dress_shirt='$dress_shirt',ethnic='$ethnicmakeup' WHERE user_id='".$_SESSION['user_id']."'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	
	$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['MY_DETAILS_UPDATED']."</font></div>";
}
redirect2page("mydetails.php",$topass);
?>