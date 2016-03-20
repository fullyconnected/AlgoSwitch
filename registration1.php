<?PHP
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
require_once("includes/recaptchalib.php");


$access_level=_GUESTLEVEL_;
db_connect();
$countriesx = '';
$citiesx = '';
$country = '';
$statesx = '';
$stateid='';
$choosen_country_code='';
$choosen_state_code='';
$choosen_city_code='';
if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];
}else{
$myuser_id = "";}
global $relative_path;

if(isset($_SESSION['user_id'])){
$query="SELECT profession FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession)=mysql_fetch_row($res);
}


$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());

if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";

}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));
$plink=((isset($topass['plink'])) ? ($topass['plink']) : (""));
$name=((isset($topass['name'])) ? ($topass['name']) : (""));
$password=((isset($topass['password'])) ? ($topass['password']) : (""));
$password2=((isset($topass['password2'])) ? ($topass['password2']) : (""));
$email=((isset($topass['email'])) ? ($topass['email']) : (""));
$email2=((isset($topass['email2'])) ? ($topass['email2']) : (""));
$gender=((isset($topass['gender'])) ? ($topass['gender']) : (_CHOOSE_));
$profession=((isset($topass['profession'])) ? ($topass['profession']) : (1));


$zip=((isset($topass['zip'])) ? ($topass['zip']) : (""));
$addr=((isset($topass['addr'])) ? ($topass['addr']) : (""));
$birthday=((isset($topass['birthday'])) ? ($topass['birthday']) : (""));
$birthmonth=((isset($topass['birthmonth'])) ? ($topass['birthmonth']) : (""));
$birthyear=((isset($topass['birthyear'])) ? ($topass['birthyear']) : (""));
$phone1=((isset($topass['phone1'])) ? ($topass['phone1']) : (""));
$phone2=((isset($topass['phone2'])) ? ($topass['phone2']) : (""));
$website=((isset($topass['website'])) ? ($topass['website']) : (""));



$state=((isset($topass['state'])) ? ($topass['state']) : (""));
$city=((isset($topass['city'])) ? ($topass['city']) : (""));
$country=((isset($topass['country'])) ? ($topass['country']) : (_DEFAULT_COUNTRY_SIGNUP_));




if(isset($_SESSION['user_id'])){
$file="error.html";
$message="You are already registered";
}
else {
$file="register1.html";
}

$tpl->set_file('middlecontent',$file);
$tpl->set_var('message',$message);
$tpl->set_var('plink',htmlentities(stripslashes($plink)));
$tpl->set_var('name',htmlentities(stripslashes($name)));
$tpl->set_var('password',htmlentities(stripslashes($password)));
$tpl->set_var('password2',htmlentities(stripslashes($password2)));
$tpl->set_var('email',htmlentities(stripslashes($email)));
$tpl->set_var('email2',htmlentities(stripslashes($email2)));
$tpl->set_var('gender_options',vector2options($accepted_genders,$gender,array(_ANY_,_NDISCLOSED_)));




$tpl->set_var('profession_options',vector2options($accepted_professions,$profession,array(_ANY_,_NDISCLOSED_,_CHOOSE_)));

$tpl->set_var('phone1',htmlentities(stripslashes($phone1)));
$tpl->set_var('phone2',htmlentities(stripslashes($phone2)));
$tpl->set_var('addr',htmlentities(stripslashes($addr)));

$tpl->set_var('birthday_options',vector2options($days,$birthday));
$tpl->set_var('birthmonth_options',vector2options($accepted_months,$birthmonth));
$tpl->set_var('birthyear_options',vector2options($accepted_birthdate_years,$birthyear));

$tpl->set_var('website',htmlentities(stripslashes($website)));



$tpl->set_var('scode',recaptcha_get_html(_PUBLIC_KEY_));

$plink2 = _PLINK_;
$tpl->set_var('plink2',$plink2);
$tpl->set_var('lang', $lang);

// country city region 

$qs_11="SELECT con_id,name FROM geo_countries ORDER BY name";
$qr_11=mysql_query($qs_11);
while ($myrow = mysql_fetch_array($qr_11))
    {
if (isset($topass['country'])){
	$country = intval($topass['country']);
}else{
$country=_DEFAULT_COUNTRY_SEARCH_;	
}
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
$qs_12="SELECT name,sta_id FROM geo_states WHERE con_id='$choosen_country_code' ORDER by name ";
$qr_12=mysql_query($qs_12);
while ($myrow = mysql_fetch_array($qr_12))
{
	if (isset($topass['state'])){
	$stateid = intval($topass['state']);
}		
			
    if($myrow['sta_id']==$stateid) {
	$selected = "selected='selected'";
	$choosen_state_code=$myrow["sta_id"];
	} else {
	$selected ="";}
    if($myrow["name"]!="")$statesx.="<option $selected value='".$myrow["sta_id"]."'>".$myrow["name"]."</option>";
	$tpl->set_var('statesx',$statesx);
        }
    }
	
	$choosen_city_code=$city;
    if ($choosen_state_code)
    {
        $qs_13="SELECT cty_id, sta_id,name FROM geo_cities WHERE sta_id='$choosen_state_code'";
        $qr_13=mysql_query($qs_13);
        while ($myrow = mysql_fetch_array($qr_13))
        {
		if (isset($topass['city'])){
	  	$cityid = intval($topass['city']);
}		
	    if($myrow['cty_id']==$cityid) {
		
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
$middle_content=$tpl->process('out','middlecontent',0,1);
$sitename=_SITENAME_;
$title=$lang['REGISTRATION_TITLE'].' '.$sitename;
include('blocks/block_reg.php');
?>
