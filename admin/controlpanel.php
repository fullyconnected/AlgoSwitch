<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();

if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));



if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$date=date("l F jS Y");
$time=date("g : i A T");

if(empty($message)){
$name=get_name($_SESSION['user_id']);
if(isset($_SESSION['llogged'])){
$llogged=nicetime($_SESSION['llogged']);}
else {$llogged=$date;}


$message="<table width=\"100%\" border=\"0\"><tr><td valign=\"top\"><font color=\"#000000\"><strong>Welcome $name!</strong></font> <br>You last logged in on: <b>$llogged</b><br><b>Current Date:</b> $date<br><b>Current Time</b> $time</td></tr></table>";}


$query="SELECT count(*) FROM users";
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
$totalcount=mysql_result($res,0,0);
$query="SELECT count(*) FROM users WHERE membership="._SUBSCRIBERLEVEL_;
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
$paying=mysql_result($res,0,0);
$query="SELECT count(*) FROM users WHERE status="._STATUSNOTACTIVE_;
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
$na_users=mysql_result($res,0,0);

$totals="There are currently <b>$totalcount</b> registered members. <b>$na_users</b> have not activated their membership.<br />";

$stats=array();
$i=0;
//genders
$query="SELECT gender,count(*) FROM users GROUP BY gender";
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
if (mysql_num_rows($res)) {
	while ($rsrow=mysql_fetch_row($res)) {
		$stats[$i]['type']=$accepted_genders[$rsrow[0]];
		$stats[$i]['count']=$rsrow[1];
		$stats[$i]['class']=(($i%2) ? ("trimpar") : ("trpar"));
		$i++;
	}
}


$data = array();
$tableSize = 300;


$query="SELECT count(user_id) FROM users WHERE joindate <= UNIX_TIMESTAMP(NOW()) AND joindate >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 DAY))";
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
$newtoday=mysql_result($res,0,0);
$query="SELECT count(user_id) FROM users WHERE joindate <= UNIX_TIMESTAMP(NOW()) AND joindate >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL -1 DAY))";
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
$newyesterday=mysql_result($res,0,0);
$query="SELECT count(user_id) FROM users WHERE joindate <= UNIX_TIMESTAMP(NOW()) AND joindate >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY))";
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
$newthismonth=mysql_result($res,0,0);
$query="SELECT count(user_id) FROM users WHERE joindate <= UNIX_TIMESTAMP(NOW()) AND joindate >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL -1 MONTH))";
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
$newlastmonth=mysql_result($res,0,0);
$query="SELECT count(user_id) FROM users WHERE joindate <= UNIX_TIMESTAMP(NOW()) AND joindate >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 YEAR))";
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
$newthisyear=mysql_result($res,0,0);
$query="SELECT count(user_id) FROM users WHERE joindate <= UNIX_TIMESTAMP(NOW()) AND joindate >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL -1 YEAR))";
if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
$newlastyear=mysql_result($res,0,0);

$data[0]['title'] = 'New Today';
$data[0]['value'] = $newtoday;
$data[1]['title'] = 'New Yesterday';
$data[1]['value'] = $newyesterday;
$data[2]['title'] = 'New This Month';
$data[2]['value'] = $newthismonth;
$data[3]['title'] = 'New Last Month';
$data[3]['value'] = $newlastmonth;
$data[4]['title'] = 'New This Year';
$data[4]['value'] = $newthisyear;
$data[5]['title'] = 'New Last Year';
$data[5]['value'] = $newlastyear;

$chartdata = drawChart($data);





global $relative_path;
$tpl = new phemplate(_TPLPATH_,'remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('chartdata',$chartdata);
$tpl->set_var('title',"CTRL");
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('relative_path',$relative_path);
$tpl->set_var('message',$message);

$tpl->set_var('content',"");
$tpl->set_var('relative_path',$relative_path);


print $tpl->process('out','frame', false,true);
?>