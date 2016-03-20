<?php
session_start();
require("../../includes/vars.inc.php");
require("../../includes/functions.inc.php");
require("../../includes/templates.inc.php");
require("../../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;
db_connect();
check_login_member();

$user_id = intval($_REQUEST['user_id']);

$user = mysql_real_escape_string(addslashes_mq(removeEvilTags($_REQUEST['user'])));
$gender = intval($_REQUEST['gender']);
$email = $_REQUEST['email'];

$thedate = $_REQUEST['joindate'];
$a = strptime($thedate, '%m/%d/%Y');
$joindate = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);

$birthdate1 = $_REQUEST['birthdate'];
$birthday = date("Y-m-d", strtotime($birthdate1));

$country= intval($_REQUEST['country']);
$region = intval($_REQUEST['us_state']);
$city = intval($_REQUEST['city']);

$lat =  mysql_real_escape_string($_REQUEST['lat']);
$lon =  mysql_real_escape_string($_REQUEST['lon']);

$profiletype = intval($_REQUEST['profession']);
$profilelink = mysql_real_escape_string(cleanString($_REQUEST['profilelink']));
$status = intval($_REQUEST['status']);

$password = md5($_REQUEST['pass']);

$key = time();

$query="INSERT INTO users SET profilelink='$profilelink',membership=1,joindate='$joindate',
access_key='$key',user='$user',pass='$password',email='$email',gender='$gender',country='$country',city='$city',
us_state='$region',birthdate='$birthday',profession='$profiletype',status='$status',is_approved='1',last_visit=UNIX_TIMESTAMP(NOW()),lat='$lan',lon='$lon'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}

$u_id=mysql_insert_id();

$query="INSERT INTO user_preferences SET fk_user_id='$u_id',email_send_news=1,email_send_alerts=1,email_format=0,allow_portfolio_comments=1,allow_photo_comments=1,allow_ratings=1,recent_visits=1";
    if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
$query="INSERT INTO user_workhistory SET fk_user_id='$u_id'";
    if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
$query="INSERT INTO profile_extended SET fk_user_id='$u_id'";
    if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}

    if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
$query="INSERT INTO user_buddies SET user_id='1', buddy_id=$u_id, approval='0'";
    if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);} 
$query="INSERT INTO user_buddies SET user_id=$u_id, buddy_id='1', approval='0'";
    if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);} 
$message = 'available';
$sql = ("insert into mbchat_status (userid,status) values ($u_id,'".mysql_real_escape_string($message)."') on duplicate key update status = '".mysql_real_escape_string($message)."'");
	$query = mysql_query($sql);


echo json_encode(array(
        'id' => mysql_insert_id(),
   
        'profilelink' => $profilelink,
         'access_key' => $key,
          'joindate' => $joindate,
           'user' => $user,
            'pass' => $password,
             'email' => $email,
             'gender' => $gender,
              'country' => $country,
               'city' => $city,
                'us_state' => $region,
                'birthdate' => $birthday,
                'profession' => $profiletype,
                'status' => $status
               
       
));
?>