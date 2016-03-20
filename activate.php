<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
db_connect();
	$topass=array();
	$code=intval($_GET['act_code']);
	$user_id=intval($_GET['user_id']);
                $error=false;
                if(!isset($user_id)){
                        $error=true;
                        $message="There was an error! Please login to activate your account!";
                }
                if ($code!=get_key($user_id)) {
                        $error=true;
                        $message="Invalid activation code. Please login to activate your account!";
                }
                $topass=array();
                if (!$error) {
                        $query="SELECT status,user,membership FROM users WHERE user_id='$user_id'";
                        $res=mysql_query($query);
                        if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
                        $myreturn='';
                        $rsrow=mysql_fetch_row($res);
                        $reg_status = $rsrow[0];
                        $membership = $rsrow[2];
                        $name = $rsrow[1];

                    if(($reg_status == 0) OR ($reg_status == 2)){
                        $topass['message']="Your account is already active.  Please login!";
                        $nextstep="login.php";

                        }else{

                        $query="UPDATE users SET status='"._STATUSACTIVE_."' WHERE user_id='$user_id'";
                        if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}

                        $query="INSERT INTO user_buddies SET user_id='1', buddy_id=$user_id, approval='0'";
                        if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
                        $query="INSERT INTO user_buddies SET user_id=$user_id, buddy_id='1', approval='0'";
                        if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}


                        $_SESSION['user_id']=$user_id;
                        $_SESSION['name']=$name;
                        $_SESSION['membership']=$membership;
                        $query="UPDATE users SET last_visit=UNIX_TIMESTAMP(NOW()) WHERE user_id='$user_id'";
                        if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}

                        $nextstep="mailbox.php";
                        $topass['message']=$lang['REGISTRATION_COMPLETE'];


                   }

                } else {
                        $topass['message']=$message;
                        $nextstep="login.php";
                }
                redirect2page($nextstep,$topass);

?>
