<?php 
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
require("../includes/class.inputfilter.php");

$access_level=_ADMINLEVEL_;
$filterObj = new InputFilter(NULL, NULL, 1, 1, 1);
if (isset($_POST['step']) && !empty($_POST['step'])) {
	$step=addslashes_mq($_POST['step']);
	$error=false;
	db_connect();
	$topass=array();
	if ($step==1) {
		$plink=addslashes_mq(removeEvilTags($_POST['plink']));
		$plink=addslashes_mq($_POST['plink']);
       		$plink = addslashes_mq(toAscii($plink));
		$name=addslashes_mq(removeEvilTags($_POST['name']));
		$password=addslashes_mq($_POST['password']);
		$email=addslashes_mq($_POST['email']);
		$gender=addslashes_mq($_POST['gender']);
		$profession=addslashes_mq($_POST['profession']);  // aka profile type
		$country=addslashes_mq($_POST['country']);
		$my_diz=addslashes_mq($_POST['my_diz']);
		$us_state=addslashes_mq($_POST['us_state']);
		$city=addslashes_mq($_POST['city']);
		$zip=addslashes_mq($_POST['zip']);
		$phone1=addslashes_mq(removeEvilTags($_POST['phone']));
		$birthmonth=addslashes_mq($_POST['birthmonth']);
		$birthday=addslashes_mq($_POST['birthday']);
		$birthyear=addslashes_mq($_POST['birthyear']);
		$birthdate=$birthyear."-".$birthmonth."-".$birthday;
		$birthdate2=$birthyear.$birthmonth.$birthday;
		
		if (!empty($plink) && (strlen($plink)>=3) && !strstr($plink,'%') && !strstr($plink,'?') && !strstr($plink,'&') && !strstr($plink,'\'') && !strstr($plink,'"') && !strstr($plink,' ') && !strstr($plink,'\\') && !strstr($plink,'/')) {
		$query="SELECT user_id FROM users WHERE profilelink='$plink'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			if (mysql_num_rows($res)) {
				$error=true;
				$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">Profile link name already taken. Please choose another.</font></div>";
			}
		} else {
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">Invalid profile link name entered. Too short or not in an acceptable format.</font></div>";
		}
		
		
		
		if (!empty($name) && (strlen($name)>=3)) 
                
        
        {
		$query="SELECT user_id FROM users WHERE user='$name'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			if (mysql_num_rows($res)) {
				$error=true;
				$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">Name already taken. Please choose another.</font></div>";
			}
		} else {
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">Invalid name entered. Name is too short or not in an acceptable format.</font></div>";
		}
	
	
		if (empty($password) || (strlen($password)<3)) {
		$error=true;
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">Invalid password entered.  Password is too short or not in an acceptable format. No spaces allowed in password.</font></div>";
		}
		
		if (empty($email)) {
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">Invalid email entered.</font></div>";
		}
	
        	
		if (!empty($email)){
		$query="SELECT email FROM users WHERE email='$email'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			if (mysql_num_rows($res)) {
				$error=true;
				$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">Email address is already in use.</font></div>";
			}
			
			}

			
		if ($birthmonth == _CHOOSE_) {
	$error=true;
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">Please enter the birth month!</font></div>";
		
		}
		
		if ($birthday == _CHOOSE_) {
	$error=true;
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">Please enter the birth day!</font></div>";
		
		}
		
		
	if ($birthyear == _CHOOSE_) {
	$error=true;
		$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">Please enter the birth year!</font></div>";
		
		}
	



		$realage = determine_age($birthdate);
		$ageallowed = _AGEALLOWED_;
		if($ageallowed > $realage){
		$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">You have age set to $ageallowed to join your site. (to change edit includes/vars.inc.php)</font></div>";
		}


		$yearnow=date("Y");
		if($birthyear > $yearnow){
	
			$error=true;
			$message="<div class=\"dotz\" align=\"center\"><font class=\"alert\">The current year is $yearnow but you have selected $birthyear as your year of birth. Please correct your error.</font></div>";
		}
		
		$my_diz = $filterObj->process($_POST['my_diz']);

		$topass=array();
		if (!$error) {
			$key=time();
			$thepassword = md5($password);
			$query="INSERT INTO users SET profilelink='$plink',status="._STATUSACTIVE_.",membership=1,joindate=UNIX_TIMESTAMP(NOW()),access_key='$key',user='$name',pass='$thepassword',email='$email',gender='$gender',country='$country',us_state='$us_state',city='$city',birthdate='$birthdate',zip='$zip',profession='$profession',website='$website',is_approved='1',my_diz='$my_diz',phone1='$phone1',last_visit=UNIX_TIMESTAMP(NOW())";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
		 
			
			$reg_id=mysql_insert_id();
			$_SESSION['reg_id']=$reg_id;
			$_SESSION['reg_name']=$name;
			$_SESSION['reg_email']=$email;
			$_SESSION['reg_username']=$email;
			$_SESSION['reg_password']=$password;
			$query="INSERT INTO user_preferences SET fk_user_id='$reg_id',email_send_news=1,email_send_alerts=1,email_format=0,allow_portfolio_comments=1,allow_photo_comments=1,allow_ratings=1,recent_visits=1";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			$query="INSERT INTO user_workhistory SET fk_user_id='$reg_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			$query="INSERT INTO profile_extended SET fk_user_id='$reg_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}



			$query="UPDATE users SET status='"._STATUSACTIVE_."' WHERE user_id='$reg_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			
			$query="INSERT INTO user_buddies SET user_id='1', buddy_id=$reg_id, approval='0'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);} 
            
			$query="INSERT INTO user_buddies SET user_id=$reg_id, buddy_id='1', approval='0'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);} 
			
			$query="UPDATE users SET last_visit=UNIX_TIMESTAMP(NOW()) WHERE user_id='$reg_id'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}		
		
			
		}
		if ($error) {
			$topass['message']=$message;
		
			$topass['name']=$name;
			$topass['password']=$password;
			$topass['password2']=$password2;
			$topass['email']=$email;
			$topass['email2']=$email2;
			$topass['gender']=$gender;
			$topass['profession']=$profession;
			$topass['country']=$country;
			$topass['us_state']=$us_state;
			$topass['city']=$city;
			$topass['zip']=$zip;
			$topass['phone1']=$phone1;
			$topass['my_diz']=$my_diz;
			$topass['birthday']=$birthday;
			$topass['birthmonth']=$birthmonth;
			$topass['birthyear']=$birthyear;
			$nextstep="admin/addmember.php";
			
			}else{
			
			$nextstep="admin/manage_member.php?user_id=$reg_id";
		}
		redirect2page($nextstep,$topass);


	}
				
}

?>
