<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");

db_connect();

function makeRandomPassword() { 
          $salt = "abchefghjkmnpqrstuvwxyz0123456789"; 
          srand((double)microtime()*1000000);  
          $i = 0;
	  $pass = '';
          while ($i <= 7) { 
                $num = rand() % 33; 
                $tmp = substr($salt, $num, 1); 
                $pass = $pass . $tmp; 
                $i++; 
          } 
          return $pass; 
 }   
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	

	$email=addslashes_mq($_POST['email'],true);

	

	$error=false;
	if (empty($email)) {
	
		$error=true;
		$message="<div class=\"alert\">".$lang['FORGOT_PASSWORD_ERROR_1']."</div>";
	}
	
	 if(isset($_SESSION['security_code']) != $_POST['security_code']){
		   $error=true;
		   $message="<div class=\"alert\">".$lang['FORGOT_PASSWORD_ERROR_2']."</div>";
            unset($_SESSION['security_code']);
            }


	if (!$error) {
	$new_pwd = makeRandomPassword();
	$new_pwdmd = md5($new_pwd);
	$rs_activ = mysql_query("update users set pass='$new_pwdmd' WHERE email='$email'") or die(mysql_error());
	
	$query="SELECT user,email,access_key,user,pass FROM users WHERE email='$email'";
	
		
		if (!($res=mysql_query($query))) {error(mysql_error());}
		if (mysql_num_rows($res)) {
			$rsrow=mysql_fetch_row($res);
			send_password($rsrow[0],$rsrow[1],$rsrow[2],$rsrow[3],$new_pwd);
			$message="<div class=\"alert\">".$lang['FORGOT_PASSWORD_MESS_1']."</div>";
		} else {
			$message="<div class=\"alert\">".$lang['FORGOT_PASSWORD_ERROR_3']."</div>";
		}
	}
}

$topass['message']=$message;
redirect2page('forgot_pass.php',$topass);
?>
