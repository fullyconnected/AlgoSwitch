<?php
session_start();
require("../../includes/vars.inc.php");
require("../../includes/functions.inc.php");
require("../../includes/templates.inc.php");
require("../../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
$user_id = intval($_REQUEST['user_id']);
$user = $_REQUEST['user'];
$email = $_REQUEST['email'];
$gender = intval($_REQUEST['gender']);
$phone ='';
$thedate = $_REQUEST['joindate'];
$a = strptime($thedate, '%m/%d/%Y');
$joindate = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);


$birthdate1 = $_REQUEST['birthdate'];
$birthdate = date("Y-m-d", strtotime($birthdate1));

if(!empty($_REQUEST['pass'])){
$passwordreq = md5($_REQUEST['pass']);
$password = ",pass='$passwordreq'";
}else{    
    $password = '';
}

$country= intval($_REQUEST['country']);
$region = intval($_REQUEST['us_state']);
$city = intval($_REQUEST['city']);
$lat =  mysql_real_escape_string($_REQUEST['lat']);
$lon =  mysql_real_escape_string($_REQUEST['lon']);
$status = intval($_REQUEST['status']);
if($user_id =='1'){
    $status = '2';
}

$profilelink = $_REQUEST['profilelink'];
$profiletype = $_REQUEST['profession'];

$sql = "update users set user='$user',phone1='$phone',gender='$gender',email='$email',joindate='$joindate'$password,profilelink='$profilelink',profession='$profiletype',country='$country',us_state='$region',city='$city',status='$status',birthdate='$birthdate',lat='$lat',lon='$lon' WHERE user_id='$user_id'";
@mysql_query($sql);
echo json_encode(array(
	'user_id' => $user_id,
	'user' => $user,
	'phone' => $phone,
        'gender'=> $gender,
	'email' => $email,
        'joindate' => $joindate,
        'pass',$password,
        'profilelink',$profilelink,
        'country',$country,
        'us_state',$region,
        'city',$city
));
?>