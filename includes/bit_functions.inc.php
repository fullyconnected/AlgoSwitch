<?php

function show_map_bit($cookie='1',$lat='',$lon=''){
           
           if($cookie==1){  
     $lat = $_COOKIE['posLat'];
     $lon = $_COOKIE['posLon'];
     $latlon = $lat.','.$lon;

$maput =  "<img border=\"0\" src=\"http://maps.google.com/maps/api/staticmap?center=$latlon&zoom=16&size=320x200&maptype=hybrid&markers=color:red|$latlon&sensor=false\" alt=\"\">";

}else{
  if(empty($lat)){
    $maput='';
    }else{
  $latlon = $lat.','.$lon;
  $maput =  "<img border=\"0\" src=\"http://maps.google.com/maps/api/staticmap?center=$latlon&zoom=16&size=640x280&scale=2&maptype=hybrid&markers=color:red|$latlon&sensor=false\" alt=\"\">";
}
}



return $maput;
}



function stats_bit(){
global $lang;
$totalusers = mysql_result(mysql_query("SELECT COUNT(*) FROM users where is_approved=1 AND status=2"), 0);

$myreturn = "<div align=\"left\">".$lang['STATS_BIT_MEMBERS'].": $totalusers<br /></div>";

return $myreturn;
	
}
function member_latest_photos($uid,$limit){  // used with taggedphotos_bit
$baseurl = _BASEURL_."/";

	$picno='';
	$userzoid='';
	$id='';
	$myreturn='';
	global $relative_path;
	
$query="SELECT fk_user_id, picture_number, picture_name, id, mainphoto, created_on, caption, views, adult, imgtitle FROM user_album2 WHERE fk_user_id=$uid ORDER BY created_on DESC LIMIT $limit";
	$result = mysql_query($query);
	
$i = 0;
while(($row = mysql_fetch_row($result)) !== false) {
	$picno = $row[1];
	$foton = $row[2]; 
	$id = $row[3]; 
    $userzoid = $uid;
	$date = nicetime($row[5],1);
	$i++;
	$myreturn .= "<div id=\"new_photos\"><a href=\"{$baseurl}picview.php?picno=$picno&user_id=$userzoid&id=$id\">
	<img src=\"${relative_path}memberpictures/thumbs/$foton\" border=\"0\" ><br />$date</div>";
	}

return $myreturn;
}


	
function escape( &$string, $default = false )
{
   return ( isset($string) ) ? htmlspecialchars( $string, ENT_QUOTES ) : $default ;
}

function relogin_form_bit() {
	global $lang;
	if (empty($_SESSION['user_id'])) {
	$loghtml = "bits/login.html";
	}else {
	$loghtml = "bits/empty.html";
	}
	
	if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
		$topass=$_SESSION['topass'];
		$_SESSION['topass']="";
	}
	$message=((isset($topass['message'])) ? ($topass['message']) : (""));
	$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
	$tpl->set_var('lang', $lang);
	
	


	$tpl->set_file('temp',$loghtml);
	global $relative_path;
	$tpl->set_var('relative_path',$relative_path);
	$tpl->set_var('message',$message);
	

	return $tpl->process('out','temp');
	
}



function copyright_bit() {
	 $tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
          $tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
	$tpl->set_file('temp','bits/copyright.html');

	global $relative_path;
	$tpl->set_var('relative_path',$relative_path);
	$tpl->set_var('sitename',_SITENAME_);
	return $tpl->process('out','temp',1);
}

function title_bit() {  // here for reference
	 $tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
	$tpl->set_file('temp','bits/title.html');

	global $relative_path;
	$tpl->set_var('relative_path',$relative_path);
	$tpl->set_var('sitename',_SITENAME_);
	return $tpl->process('out','temp',1);
}

function friendcount($user_id){
$query = "SELECT count(user_id) FROM user_buddies WHERE buddy_id='$user_id' AND approval !=1";
			if (!($countz=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
              	while ($rzrow=mysql_fetch_row($countz)) {
			
			     $howmany =  $rzrow[0];   
		}
return $howmany;
} 

function featureme_bit() { //  featured members aka VIP

	global $relative_path;
	$basegirl = _BASEURL_."/"; 
	$table_cols= 3;		// -> change this

	$query="SELECT feat_id,fk_user_id FROM featured ORDER BY RAND(".mt_rand().") LIMIT 3";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$myreturn='';
	if (mysql_num_rows($res)) {
		$myreturn="<table width=\"100%\">\n";
		$i=1;
		while ($rsrow=mysql_fetch_row($res)) {
			$photo=get_photo($rsrow[1]);
			$membernam = remove_underscore(get_name($rsrow[1]));
			if (!empty($photo) && file_exists(_THUMBSPATH_."/".$photo)) {
				$photo=$photo;
			}else {
				$photo="no-pict.gif";
			}
					$pprof=get_profession2($rsrow[1]);
					$plink=get_profile_link($rsrow[1]);
			
			
			if (($i%$table_cols)==1) {$myreturn.="<tr>\n";}
			$myreturn.="\t<td align=\"center\" align=\"center\">\n";
			$myreturn.="<a href=\"${basegirl}$plink\"><img src=\""._MEDTHUMBSURL_."/".$photo."\" width=\"100%\" class=\"mainimage\"  /></a><br><a href=\"${relative_path}$plink\"><strong>$membernam</strong></a><br><strong>$pprof</strong><br />";
			$myreturn.="</td>";
	
      if ($i%$table_cols<=3) {
        
      $myreturn.="</tr>";
    
    }
			
      $i++;
		}
		$rest=($i-1)%$table_cols;
		if ($rest!=0) {
			$colspan=$table_cols-$rest;
			$myreturn.="\t<td".(($colspan==1) ? ("") : (" colspan=\"$colspan\""))."></td>\n</tr>\n";
		}
		$myreturn.="</table>\n";
	} else {

		$myreturn="No photos to display";
	}
	return $myreturn;
}





?>
