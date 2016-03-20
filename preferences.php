<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
$access_level=$access_matrix['preferences'][0];

db_connect();
check_login_member();

if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}
global $relative_path;

if(isset($_SESSION['user_id'])){
$query="SELECT profession,membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession,$mymembership)=mysql_fetch_row($res);
}


$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
 $tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));
$query="SELECT email_send_news,email_send_alerts,email_format,allow_portfolio_comments,allow_photo_comments,allow_ratings,recent_visits,private_profile FROM user_preferences WHERE fk_user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list ($email_send_news,$email_send_alerts,$email_format,$allow_portfolio_comments,$allow_photo_comments,$allow_ratings,$recent_visits,$private_profile)=mysql_fetch_row($res);
if ($email_send_news) {
	$news="checked";
} else {
	$news='';
}
if ($email_send_alerts) {
	$sendalert="checked";
} else {
	$sendalert='';
}
if ($email_format) {
	$eformat1="";
	$eformat2="checked";
} else {
	$eformat1="checked";
	$eformat2="";
}

if ($allow_portfolio_comments) {
	$allow_portfolio_comments="checked";
} else {
	$allow_portfolio_comments='';
}
if ($allow_photo_comments) {
	$allow_photo_comments="checked";
} else {
	$allow_photo_comments='';
}
if ($allow_ratings) {
	$allow_ratings="checked";
} else {
	$allow_ratings='';
}
if ($recent_visits) {
	$recent_visits="checked";
} else {
	$recent_visits='';
}


if (get_member_site_option('allow_portfolio_comments')) {
	$cell_portfolio_comments="<td><input type=\"checkbox\" name=\"allow_portfolio_comments\" value=\"1\" ${allow_portfolio_comments}>".$lang['PREFERENCES_YES_POST_COMMENTS_PORTFOLIO']."</td></tr><tr><td height=\"20\"></td></tr><tr>";
}else{$cell_portfolio_comments="<td><input type=\"checkbox\" name=\"allow_portfolio_comments\" value=\"1\" ${allow_portfolio_comments}>".$lang['PREFERENCES_YES_POST_COMMENTS_PORTFOLIO']."</td></tr><tr><td height=\"20\"></td></tr><tr>";}

if (get_member_site_option('allow_photo_comments')) {
	$cell_photo_comments="<td><input type=\"checkbox\" name=\"allow_photo_comments\" value=\"1\" ${allow_photo_comments}>".$lang['PREFERENCES_YES_POST_COMMENTS_PHOTOS']."</td></tr><tr><td height=\"20\"></td></tr><tr>";
}else{$cell_photo_comments="<td><input type=\"checkbox\" name=\"allow_photo_comments\" value=\"1\" ${allow_photo_comments}>".$lang['PREFERENCES_YES_POST_COMMENTS_PHOTOS']."</td></tr><tr><td height=\"20\"></td></tr><tr>";}

if (get_member_site_option('allow_ratings')) {
	$cell_use_ratings="<td></td></tr><tr><td><input type=\"checkbox\" name=\"allow_ratings\" value=\"1\" ${allow_ratings}>".$lang['PREFERENCES_YES_RATE_MY_PORTFOLIO']."</td></tr><tr><td height=\"20\"></td></tr><tr>";
}else{$cell_use_ratings="<td></td></tr><tr><td><input type=\"checkbox\" name=\"allow_ratings\" value=\"1\" ${allow_ratings}>".$lang['PREFERENCES_YES_RATE_MY_PORTFOLIO']."</td></tr><tr><td height=\"20\"></td></tr><tr>";
}
if (get_member_site_option('recent_visits')) {
	$cell_recent_visits="<td></td></tr><tr><td><input type=\"checkbox\" name=\"recent_visits\" value=\"1\" ${recent_visits}>".$lang['PREFERENCES_YES_SHOW_IN_RECENT_VISITS']."</td></tr><tr><td height=\"20\"></td></tr><tr>";
}else{$cell_recent_visits="<td></td></tr><tr><td><input type=\"checkbox\" name=\"recent_visits\" value=\"1\" ${recent_visits}>".$lang['PREFERENCES_YES_SHOW_IN_RECENT_VISITS']."</td></tr><tr><td height=\"20\"></td></tr><tr>";
}
$tpl->set_var('lang', $lang);

$tpl->set_file('middlecontent','preferences.html');
$tpl->set_var('private_profile',vector2options($options_private_profile,$private_profile,array(_ANY_)));
$tpl->set_var('message',$message);
$tpl->set_var('news',$news);
$tpl->set_var('sendalert',$sendalert);
$tpl->set_var('eformat1',$eformat1);
$tpl->set_var('eformat2',$eformat2);
$tpl->set_var('recent_visits',$cell_recent_visits);
$tpl->set_var('allow_ratings',$cell_use_ratings);
$tpl->set_var('allow_photo_comments',$cell_portfolio_comments);
$tpl->set_var('allow_portfolio_comments',$cell_photo_comments);
$middle_content=$tpl->process('out','middlecontent',0,1);

$title="My Preferences";
include('blocks/block_main_frame.php');
?>
