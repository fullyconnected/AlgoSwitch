<?php 
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
require_once("../includes/recaptchalib.php");

if (isset($_POST['step']) && !empty($_POST['step'])) {
	$step=intval($_POST['step']);
	$error=false;
	db_connect();
	
	$topass=array();
	if ($step==1) {
	$plink=mysql_real_escape_string(addslashes_mq(removeEvilTags($_POST['plink'])));
	$plink=mysql_real_escape_string(cleanString($_POST['plink']));
	$name=mysql_real_escape_string(addslashes_mq(removeEvilTags($_POST['name'])));
	$password=mysql_real_escape_string(addslashes_mq($_POST['password']));
	$password2=mysql_real_escape_string(addslashes_mq($_POST['password2']));
	$email=mysql_real_escape_string(addslashes_mq($_POST['email']));
	$gender=mysql_real_escape_string(addslashes_mq($_POST['gender']));
	$profession=mysql_real_escape_string(addslashes_mq($_POST['profession']));  // aka profile type
	$country=mysql_real_escape_string(intval($_POST['country'])); 
	$us_state=mysql_real_escape_string(intval($_POST['state'])); 
	$city=mysql_real_escape_string(intval($_POST['city'])); 
	$birthmonth=mysql_real_escape_string(addslashes_mq($_POST['birthmonth']));
	$birthday=mysql_real_escape_string(addslashes_mq($_POST['birthday']));
	$birthyear=mysql_real_escape_string(addslashes_mq($_POST['birthyear']));
	$birthdate=$birthyear."-".$birthmonth."-".$birthday;
	$birthdate2=$birthyear.$birthmonth.$birthday;
	
	

		
		if (!empty($plink) && (strlen($plink)>=3) && !strstr($plink,'%') && !strstr($plink,'?') && !strstr($plink,'&') && !strstr($plink,'\'') && !strstr($plink,'"') && !strstr($plink,' ') && !strstr($plink,'\\') && !strstr($plink,'/')) {
			$query="SELECT user_id FROM users WHERE profilelink='$plink'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			
			if (mysql_num_rows($res)) {
				$error=true;
				$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_1']."</font></div>";
			}
		} else {
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_2']."</font></div>";
		}
		
		
		
		  global $unaccepted_profile_names;

		if (in_array($plink, $unaccepted_profile_names)) {
		    $error=true;
        	 $message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_24']."</font></div>";
		}
		
		
  
		
		
		if (!empty($name) && (strlen($name)>=3)) 
                
        
        {
			$query="SELECT user_id FROM users WHERE user='$name'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			if (mysql_num_rows($res)) {
				$error=true;
				$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_3']."</font></div>";
			}
		} else {
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_4']."</font></div>";
		}
	
	
		if (empty($password) || (strlen($password)<3)) {
		$error=true;
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_5']."</font></div>";
		}
		
		
	
		
		
		
		
		
		
		
		
	
		
		if ($password2!=$password) {
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_6']."</font></div>";
		}
		
	
		if (empty($email)) {
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_7']."</font></div>";
		}
		
		if (!empty($email)){
		$query="SELECT email FROM users WHERE email='$email'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			if (mysql_num_rows($res)) {
				$error=true;
				$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_8']."</font></div>";
					}
				}
	
	
	
	
	
	

	
// recaptcha
	
$resp = recaptcha_check_answer (_PRIVATE_KEY_,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
   // die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." ."(reCAPTCHA said: " . $resp->error . ")");
	 $error=true; 
	 $message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$resp->error."</font></div>";	 
		 
		 
		 
  }


  	








		if ($birthmonth == _CHOOSE_) {
	$error=true;
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_11']."</font></div>";
		
		}
		
		if ($birthday == _CHOOSE_) {
	$error=true;
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_12']."</font></div>";
		
		}
		
		
	if ($birthyear == _CHOOSE_) {
	$error=true;
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_13']."</font></div>";
		
		}
	$realage = determine_age($birthdate);
	$ageallowed = _AGEALLOWED_;
	if($ageallowed > $realage){
	$error=true;
	$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_14']." $ageallowed ".$lang['REGISTRATION_ERROR_15']."</font></div>";
		}
	$yearnow=date("Y");
	if($birthyear > $yearnow){
	$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_16']."$yearnow ".$lang['REGISTRATION_ERROR_17']." $birthyear ".$lang['REGISTRATION_ERROR_18']."</font></div>";
		}
		
		/*	if ($city==0) {
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_21']."</font></div>";
		}
	*/
   /*
		if ($us_state==0) {
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_22']."</font></div>";
		}
	*/	
		if ($country==0) {
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_23']."</font></div>";
		}
		

	
	/*	
		
		if(!empty($country) && empty($us_state) && empty($city)){
	
		$latlon =  lat_lon($country,1);
		
}elseif(!empty($country) && !empty($us_state) && empty($city)){
	
		$latlon =  lat_lon($us_state,2);
		
}elseif(!empty($country) && !empty($us_state) && !empty($city)){
	
		$latlon =  lat_lon($city,3);
}else{
	
	$latlon = '';
}

$lat = $latlon['latitude'];


$lon = $latlon['longitude'];
		
	*/
	
	$lat = $_COOKIE['posLat'];
	$lon = $_COOKIE['posLon'];
	
		$topass=array();
		if (!$error) {
			$passencrypt = md5($password);
			$key=time();
			
			$language = $_SESSION['lang'];

$query="INSERT INTO users SET profilelink='$plink',status="._STATUSNOTACTIVE_.",membership=1,joindate=UNIX_TIMESTAMP(NOW()),access_key='$key',user='$name',pass='$passencrypt',email='$email',gender='$gender',country='$country',us_state='$us_state',city='$city',birthdate='$birthdate',profession='$profession',language='$language',lat='$lat',lon='$lon'";
           
			if (get_site_option('auto_approve')) {
				$query.=",is_approved=1";
			} else {
				$query.=",is_approved=0";
			}
			

			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			$reg_id=mysql_insert_id();
			$_SESSION['reg_id']=$reg_id;
			$_SESSION['reg_name']=$name;
			$_SESSION['reg_email']=$email;
			$_SESSION['reg_username']=$email;
			$_SESSION['reg_password']=$password;
			$query="INSERT INTO user_preferences SET fk_user_id='$reg_id',email_send_news=1,email_send_alerts=1,email_format=0,allow_portfolio_comments=1,allow_photo_comments=1,allow_ratings=1,recent_visits=1";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			
					
			 $confirmation = get_site_option('email_confirmation');

			if ($confirmation =='1') {  // check 
			if (get_site_option('signup_alerts')) {
				send_newsignup_alert($name,$email,$accepted_genders[$gender]);
			}

			$query="UPDATE users SET status='"._STATUSACTIVE_."' WHERE user_id='$reg_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			
			$query="INSERT INTO user_buddies SET user_id='1', buddy_id=$reg_id, approval='0'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);} 
            
			$query="INSERT INTO user_buddies SET user_id=$reg_id, buddy_id='1', approval='0'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);} 
		
			$approv = get_site_option('auto_approve');
		
			if ($approv =='0') {
		
			$query="UPDATE users SET is_approved='0' WHERE user_id=$reg_id";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);} 		
			}



			$_SESSION['user_id']=$reg_id;
			
			$query="UPDATE users SET last_visit=UNIX_TIMESTAMP(NOW()) WHERE user_id='$reg_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}		
			$nextstep="members.php";
			$topass['message']=$lang['REGISTRATION_COMPLETE'];
			
	
		redirect2page($nextstep,$topass);


				}
			$code=get_key($_SESSION['reg_id']);
			if (!send_activation_code($_SESSION['reg_id'],$_SESSION['reg_name'],$_SESSION['reg_email'],$code,$_SESSION['reg_username'],$_SESSION['reg_password'],$_SESSION['lang'])) {
				$topass['message']="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_19']."</font></div>";
			}

			$nextstep="registration2.php";

			if (get_site_option('signup_alerts')) {
				send_newsignup_alert($name,$email,$accepted_genders[$gender]);
			}
		}

		if ($error) {
			$topass['message']=$message;
			$topass['plink']=$plink;
			$topass['name']=$name;
			$topass['password']=$password;
			$topass['password2']=$password2;
			$topass['email']=$email;
			$topass['email2']=$email2;
			$topass['gender']=$gender;
			$topass['profession']=$profession;
			$topass['password']=$password;
			$topass['password2']=$password2;
			$topass['city']=$city;
			$topass['state']=$us_state;
			$topass['country']=$country;
			$topass['birthday']=$birthday;
			$topass['birthmonth']=$birthmonth;
			$topass['birthyear']=$birthyear;
		
			$nextstep="registration1.php";
		}
		redirect2page($nextstep,$topass);


	}elseif ($step==2) {
		$code=addslashes_mq($_POST['act_code'],true);
		$user_id=addslashes_mq($_POST['user_id'],true);
		$error=false;
		if ($code!=get_key($user_id)) {
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">".$lang['REGISTRATION_ERROR_20']."</font></div>";
		}
		$topass=array();
		if (!$error) {
			$query="UPDATE users SET status='"._STATUSACTIVE_."' WHERE user_id='$user_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			
			$query="INSERT INTO user_buddies SET user_id='1', buddy_id=$user_id, approval='0'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);} 
            
			$query="INSERT INTO user_buddies SET user_id=$user_id, buddy_id='1', approval='0'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);} 
		

			$_SESSION['user_id']=$user_id;
			
			$query="UPDATE users SET last_visit=UNIX_TIMESTAMP(NOW()) WHERE user_id='$user_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}		
			$nextstep="mailbox.php";
			$topass['message']=$lang['REGISTRATION_COMPLETE'];
			
		} else {
			$topass['message']=$message;
			$nextstep="registration2.php";
		}
		redirect2page($nextstep,$topass);
	}
}

?>
