<?php
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
$access_level=_REGISTERLEVEL_;

// define
$choosen_state_code ='';
$choosen_country_code='';
$countriesx='';
$citiesx='';
$statesx='';

db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();
if (isset($_SESSION['user_id']))
{
	$myuser_id = $_SESSION['user_id'];
}

else
{
	$myuser_id = "";
}
global $relative_path;

if (isset($_SESSION['user_id']))
{
	$query="SELECT profession,membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
	if (!($res=mysql_query($query))) {
		error(mysql_error(),__LINE__,__FILE__);
	}
	list($profession,$mymembership)=mysql_fetch_row($res);
}


$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());

$tpl->set_var('relative_path', $relative_path);
	
	
$message = '';
if (isset($_SESSION['topass']) && !empty($_SESSION['topass']))
{
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";
	$language=$topass['language'];
	$message=$topass['message'];
	$country=$topass['country'];
	$us_state=$topass['us_state'];
	$zip=$topass['zip'];
	$addr=$topass['addr'];
	$plink=$topass['plink'];
	$name=$topass['name'];
	$city=$topass['city'];
	$us_state=$topass['us_state'];
	$gender=$topass['gender'];
	$phone1=$topass['phone1'];
	$birthday=$topass['birthday'];
	$birthyear=$topass['birthyear'];
	$birthmonth=$topass['birthmonth'];
	$website=$topass['website'];
}

else
{

	$query="SELECT user,pass,email,gender,DAYOFMONTH(birthdate),MONTH(birthdate),YEAR(birthdate),ethnic,country,us_state,city,zip,addr,phone1,my_diz,work_interest,hairlength,hairtype,haircolor,hairpiece,eyeshape,eyecolor,eyebrows,eyelashes,eyewear,faceshape,bodytype,waist,chest,hips_inseam,height,weight,shoes,dress_shirt,website,membership,profilelink,language FROM users WHERE user_id='".$_SESSION['user_id']."'";
	
	
	
	if (!($res=mysql_query($query))) {
		error(mysql_error(),__LINE__,__FILE__);
	}
	list($name,$pass,$email,$gender,$birthday,$birthmonth,$birthyear,$ethnic,$country,$us_state,$city,$zip,$addr,$phone1,$my_diz,$work_interest,$hairlength,$hairtype,$haircolor,$hairpiece,$eyeshape,$eyecolor,$eyebrows,$eyelashes,$eyewear,$faceshape,$bodytype,$waist,$chest,$hips_inseam,$height,$weight,$shoes,$dress_shirt,$website,$membership,$plink,$language)=mysql_fetch_row($res);

}



/// country city region 
$qs_11="SELECT con_id,name FROM geo_countries ORDER BY name";
$qr_11=mysql_query($qs_11);


while ($myrow = mysql_fetch_array($qr_11))
    {


   if($country==$myrow["con_id"]) {
	 $selected = "selected='selected'";
   
   $choosen_country_code=$myrow["con_id"];
   }else {
	$selected ="";
	
	}

	$countriesx.="<option $selected value='".$myrow["con_id"]."'>".$myrow["name"]."</option>";
	
	}
	
	
    if ($choosen_country_code) 
    {
        $qs_12="SELECT DISTINCT name,sta_id FROM geo_states WHERE con_id='$choosen_country_code' ORDER BY name";
        $qr_12=mysql_query($qs_12);
        while ($myrow = mysql_fetch_array($qr_12))
        {
			
			
        if($myrow['sta_id']==$us_state) {
		$selected = "selected='selected'";
		$choosen_state_code=$myrow["sta_id"];
		} else {
		$selected ="";
		}
        if($myrow["name"]!="")$statesx.="<option $selected value='".$myrow["sta_id"]."'>".$myrow["name"]."</option>";
		$tpl->set_var('statesx',$statesx);
        }
    }
	
	$choosen_city_code=$city;
    if ($choosen_state_code)
    {
        $qs_13="SELECT cty_id, sta_id,name FROM geo_cities WHERE sta_id='$choosen_state_code' ORDER BY name";
        $qr_13=mysql_query($qs_13);
        while ($myrow = mysql_fetch_array($qr_13))
        {
        if($myrow['cty_id']==$city) {
		
		$selected = "selected='selected'";
		$choosen_city_code=$city;
		}else {
		$selected ="";
		
		}
        if($myrow['name']!="")
		
		$citiesx.="<option $selected value='".$myrow["cty_id"]."'>".$myrow["name"]."</option>";
		$tpl->set_var('citiesx',$citiesx);
		
		
        }
    }

$tpl->set_var('countriesx',$countriesx);

$password2='';
$email2='';
$sitelink=_PLINK_."/";
$tpl->set_file('middlecontent','accountinfo.html');
$tpl->set_var('message',$message);
$tpl->set_var('sitelink',$sitelink);
$tpl->set_var('plink',$plink);
$tpl->set_var('name',htmlentities(stripslashes($name)));
$tpl->set_var('website',htmlentities(stripslashes($website)));
$tpl->set_var('email2',htmlentities(stripslashes($email2)));
$tpl->set_var('gender_options',vector2options($accepted_genders,$gender,array(_ANY_,_NDISCLOSED_)));
$tpl->set_var('language_options',vector2options($accepted_languages,$language));
$tpl->set_var('phone1',htmlentities(stripslashes($phone1)));
$tpl->set_var('addr',htmlentities(stripslashes($addr)));
$tpl->set_var('zip',htmlentities(stripslashes($zip)));
$tpl->set_var('city',htmlentities(stripslashes($city)));
$tpl->set_var('birthday_options',vector2options($days,$birthday));
$tpl->set_var('birthmonth_options',vector2options($accepted_months,$birthmonth));
$tpl->set_var('birthyear_options',vector2options($accepted_birthdate_years,$birthyear));
$tpl->set_var('lang', $lang);

$middle_content=$tpl->process('out','middlecontent',0,1);

$title="Account Information";

include('blocks/block_main_frame.php');
?>
