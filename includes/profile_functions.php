<?php


function profile_visit($profile_user_id,$usession)  //last who visited profile
{

	
$time = time();
$query="SELECT profile_user_id,viewer_user_id,viewer_user_counter,viewer_visit_time  FROM profile_views WHERE profile_user_id = '$profile_user_id' AND viewer_user_id = '$usession'";
		if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			if (mysql_num_rows($res)) {
		list($profile_user_id2,$viewer_user_id,$viewer_user_counter,$viewer_visit_time)=mysql_fetch_row($res);
	}
		if (isset($_SESSION['user_id']) && empty($viewer_visit_time) && check_browse_anonymous($usession)){
		

	$addinto = "INSERT INTO profile_views values ('$profile_user_id','$usession','1','$time')";	
	mysql_query($addinto); 
	}else{
		if (isset($_SESSION['user_id']) && !empty($viewer_visit_time) && check_browse_anonymous($usession)){
$viewer_user_counter = $viewer_user_counter + 1;
$sql = "UPDATE profile_views SET viewer_user_counter = '$viewer_user_counter' , viewer_visit_time = '$time' WHERE viewer_user_id = '$usession' AND profile_user_id = '$profile_user_id'";
mysql_query($sql); 
		}
		
	}
}	


function show_last_visted($profile_user_id,$limit=5) { // recent visitors per profile
	global $lang;
	global $relative_path;
	$myreturn='';
	$query="SELECT viewer_visit_time FROM profile_views WHERE profile_user_id = '$profile_user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	if (mysql_num_rows($res)) {
	list($checkifviewed)=mysql_fetch_row($res);
	}
	
	$query="SELECT profile_user_id,viewer_user_id,viewer_user_counter,viewer_visit_time FROM profile_views WHERE profile_user_id = '$profile_user_id' ORDER BY viewer_visit_time DESC LIMIT $limit ";
	
		if (!($res=mysql_query($query))){error(mysql_error(),__LINE__,__FILE__);}
			//echo $res[3];
		
		$i=1;
		$myreturn .= "<table border='0' cellpadding='0' cellspacing='0' width='216'>";
		$myreturn .= "<tr>";
		$myreturn .= "<td align='left' colspan='2'><h1>".$lang['PROFILE_RECENT_VISITORS']."</h1><div class='head_zoid'></div></td>";
		$myreturn .= "</tr><tr>";
		$myreturn .= "<td align='left' class='small' width='216'>";
		$myreturn .="<ul style=\"list-style:none;margin:0;padding: 0;\">\n";
		while ($rsrow=mysql_fetch_row($res)) {
			
		$name = get_name($rsrow[1]);
		$getprlink = get_profile_link($rsrow[1]);
		$getproff = get_profession2($rsrow[1]);
		
		$myreturn.="<li><a href=\"${relative_path}".$getprlink."\">$name</a><font class=\"recent_visitors\"> / $getproff</font></li>";
		
	
	}
			
	    $myreturn.="</ul>\n";
		$myreturn .= "</td></tr></table>";
		
	
			$i++;

	if(!empty($checkifviewed)){

	return $myreturn;
	}
}
function check_browse_anonymous($user_id) {
	$alphc=false;
	$query="SELECT recent_visits FROM user_preferences WHERE fk_user_id='$user_id'";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res) && mysql_result($res,0,0)) {
			$alphc=true;
		}
		return $alphc;
}

?>
