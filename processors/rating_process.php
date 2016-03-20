<?php

session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_GUESTLEVEL_;

db_connect();

header("Cache-Control: no-cache");
header("Pragma: nocache");


// IF JAVASCRIPT IS ENABLED

if($_POST){

	
	$id = intval($_POST['id']);
	$rating = intval($_POST['rating']);
	
	// If you want people to be able to vote more than once, comment the entire if/else block block and uncomment the code below it.
	
	
	if(@mysql_fetch_assoc(mysql_query("SELECT id FROM profile_rating WHERE IP = '".$_SERVER['REMOTE_ADDR']."' AND rating_id = '$id'"))){
	
		echo 'already_voted';
		
	} else {
		
		mysql_query("INSERT INTO profile_rating (rating_id,rating_num,IP) VALUES ('$id','$rating','".$_SERVER['REMOTE_ADDR']."')") or die(mysql_error());
		
		$total = 0;
		$rows = 0;
		
		$sel = mysql_query("SELECT rating_num FROM profile_rating WHERE rating_id = '$id'");
		while($data = mysql_fetch_assoc($sel)){
		
			$total = $total + $data['rating_num'];
			$rows++;
		}
		
		$perc = ($total/$rows) * 20;
		
		echo round($perc,2);
		//echo round($perc/5)*5;
		
	}

}

// IF JAVASCRIPT IS DISABLED

if($_GET){

	$id = (int) $_GET['id'];
	$rating = (int) $_GET['rating'];
	
	// If you want people to be able to vote more than once, comment the entire if/else block block and uncomment the code below it.
	
	
	if(@mysql_fetch_assoc(mysql_query("SELECT id FROM profile_rating WHERE IP = '".$_SERVER['REMOTE_ADDR']."' AND rating_id = '$id'"))){
	
		//echo 'already_voted';
		
	} else {
		
		mysql_query("INSERT INTO profile_rating (rating_id,rating_num,IP) VALUES ('$id','$rating','".$_SERVER['REMOTE_ADDR']."')") or die(mysql_error());
		
	}
	
	header("Location:".$_SERVER['HTTP_REFERER']."");
	die;

}

?>
