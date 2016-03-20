<?php
//---------change below-----------------

define('_DBHOSTNAME_','localhost');  // Database hostname, usually localhost
define('_DBUSERNAME_','algswitchuser');       // MYSQL DATABASE USERNAME
define('_DBPASSWORD_','nytimes'); 	 // MYSQL DATABASE PASSWORD
define('_DBNAME_','algoswitch'); 	// MYSQL Database Name


define('_SITENAME_','Loopid');

define('_BASEURL_','http://localhost/AlgoSwitch');	//protocol required (http://) NOOO Trailing slash / <- no
define('_DOMAINURL_','http://localhost/AlgoSwitch/');	//protocol required (http://)
define('_PAYPAL_BIZNAME_','paypal@loooooopid.us'); // your PayPal business email 
define('_ADMINPROF_','Administrator');  //  Administrators profession 
define('_PLINK_','localhost/AlgoSwitch/'); // Profile link url NO (http://)
define('_BASEPATH_',$_SERVER['DOCUMENT_ROOT'].'/');	// full directory path of your server leave / unless using example.com/mysite/


define('_DEFAULT_RADIUS_','100');	 // default radius on around me. 
define('_DEFAULT_RADIUS_TYPE_','2');	// 1 = Miles 2 = Kilometres
define('_BIOLIMIT_','160');
define('_RESULTS_',20); // mailbox.php
define('_THUMBSONHOME_',8); // How many thumbnail pics to show on home page for the newest members joined who have uploaded pics

// GET YOUR RECAPTCHA KEYS HERE  https://www.google.com/recaptcha/admin/create

define('_PUBLIC_KEY_','6LfW6M0SAAAAAMB34vI8UqS5DT5DYIXKRATeM3m_');
define('_PRIVATE_KEY_','6LfW6M0SAAAAAKdbJ0FtP_fSXCZ4a-_kFfYJojvg');



define('_WATERMARK_',0); // Use image watermark? 1 = yes 0 = no // watermark.jpg is located in memberpictures/watermark/
define('_AGEALLOWED_',18); // Age allowed to signup
define('_TEMP_SELECT_',1);  // Show template selector on/off 1/0  

define('_MULTI_LANG_',1); // Use multiple language files on/off 1/0  
define('_MULTI_LANG_SELECTOR_',1); // Show multiple language selector on/off 1/0 
define('_DEFAULT_LANGUAGE_','en');   // ex en,sv,de,es
define('_DEFAULT_TEMPLATE_','default'); // default template

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
define('_DEFAULT_COUNTRY_CASTING_','223');
define('_DEFAULT_COUNTRY_SEARCH_','223');
define('_DEFAULT_COUNTRY_SIGNUP_','223');


define('_TPLPATH_',_BASEPATH_.'/templates/');// ending '/' is required
define('_TPLURL_',_BASEURL_.'/templates');// no trailing slash
define('_TEMPLATEDIR_',_BASEURL_.'/templates/'._DEFAULT_TEMPLATE_);



define('_EXPIRE_',20); // interval of inactivity in minutes after a user is considered offline.


define('_MAXPICSIZE_',8000000); // maximum size in bytes for photos members upload 

define('_THUMBSWIDTH_',80); // maximum thumbzoids

define('_IMAGESURL_',_BASEURL_.'/memberpictures');  // big images
define('_IMAGESPATH_',_BASEPATH_.'/memberpictures'); // real path for the url above

define('_THUMBSURL_',_IMAGESURL_.'/thumbs'); // thumbnails
define('_THUMBSPATH_',_IMAGESPATH_.'/thumbs'); // real path for the url above

define('_MEDTHUMBSURL_',_IMAGESURL_.'/medthumbs/'); // Medium thumbnails path
define('_MEDTHUMBSPATH_',_IMAGESPATH_.'/medthumbs/'); // Real path for medium thumbnails

define('_MEDTHUMBWIDTH_',300); 
define('_MEDTHUMBHEIGHT_',170); 

define('_THUMBWIDTH_',120); 
define('_THUMBHEIGHT_',120); 

define('_BIGWIDTH_',750); 
define('_BIGHEIGHT_',750); 



define('_BLOLIMIT_',1000);  // limit blog words   
define('_ENABLEBROWSERFILEEDIT_',1); 


// do not edit below unless you know what your doing. 
///////////////////////////////////////////////////////////////////////////////////////////////////////////

$accepted_upload_extensions=array('gif','jpg','jpeg'); // Bild
$accepted_audio_upload_extensions=array('mp3'); // Audio Upload  

//----end global access params---------
//---------change above-----------------
// Unset globally registered vars
function unset_vars(&$var) {
	while (list($var_name, $null) = @each($var)) {
		unset($GLOBALS[$var_name]);
	}
	return;
}



if (ini_get('register_globals') == '1' || strtolower(ini_get('register_globals')) == 'on') {
	$var_prefix = 'HTTP';
	$var_suffix = '_VARS';
	$test = array('_GET', '_POST', '_SERVER', '_COOKIE', '_ENV');
	foreach ($test as $var) {
		if (is_array(${$var_prefix . $var . $var_suffix})) {
			unset_vars(${$var_prefix . $var . $var_suffix});
			@reset(${$var_prefix . $var . $var_suffix});
		}
		if (is_array(${$var})) {
			unset_vars(${$var});
			@reset(${$var});
		}
	}
	if (is_array($_SESSION)) {
		unset_vars($_SESSION);
		@reset($_SESSION);
	}
	if (is_array($_REQUEST)) {
		unset_vars($_REQUEST);
		reset($_REQUEST);
	}
	if (is_array(${'_FILES'})) {
		unset_vars(${'_FILES'});
		@reset(${'_FILES'});
	}
	if (is_array(${'HTTP_POST_FILES'})) {
		unset_vars(${'HTTP_POST_FILES'});
		@reset(${'HTTP_POST_FILES'});
	}
}



if (isset($_GET['skin'])) {
	$_SESSION['my_template']=$_GET['skin'];
}

$PHP_SELF=$_SERVER['PHP_SELF'];
$relative_path=@str_repeat("../",substr_count($PHP_SELF,"/")-(substr_count(_BASEURL_,"/")-2)-1);



define('_ANY_',-1);
define('_CHOOSE_',-2);
define('_NDISCLOSED_',0);


$unaccepted_profile_names=array('admin','administrator','webmaster','box','web-master','web_master');

$accepted_contactus_types=array("1"=>'Activation Code Not Received',"2"=>'Problems Logging In',"3"=>'Registration Questions',"4"=>'Mailbox Issues',"5"=>'Casting Notices',"6"=>'Photo Uploading',"7"=>'Photographers',"8"=>'Search Errors',"9"=>'Other Problem');
$accepted_results_per_page=array("5"=>5,"10"=>10,"20"=>20,"30"=>30);
//-------------------------- don't change below !!! --------------------------
$zodiac_days=array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,1,1,1,1,1,1,1,1,1,1,1);
$zodiac_signs=array(""._ANY_=>"Any","1"=>"Capricorn","2"=>"Aquarius","3"=>"Pisces","4"=>"Aries","5"=>"Taurus","6"=>"Gemini","7"=>"Cancer","8"=>"Leo","9"=>"Virgo","10"=>"Libra","11"=>"Scorpio","12"=>"Sagitarius");
define('_ADMINLEVEL_',5);
define('_ADMINFORUM_',4);
define('_SUBSCRIBERLEVEL_',2);
define('_REGISTERLEVEL_',1);
define('_GUESTLEVEL_',0);
define('_STATUSACTIVE_',2);
define('_STATUSNOTACTIVE_',1);
define('_STATUSSUSPENDED_',0);
define('_PCONN_',0);	// persistent or non persistent connections. Use 1 for persistent.
define('_DEBUG_',0);	// 0-No,1-Yes. Used for debug info in error messages. Set to 0 for production
require_once('access_matrix.inc.php');

if (_MULTI_LANG_!=0){


$languages_possible = array(

    "en",

    "es",

    "sv",

);
if(isset($_GET['lang'])){
$lang = $_GET['lang'];
// register the session and set the cookie
$_SESSION['lang'] = $lang;
setcookie("lang", $lang, time() + (3600 * 24 * 30));
}
else if(isset($_SESSION['lang']))
{
$lang = $_SESSION['lang'];
}
else if(isset($_COOKIE['lang']))
{
$lang = $_COOKIE['lang'];
}
else
{
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

	if (!in_array($lang, array_keys($languages_possible))){
	$lang = _DEFAULT_LANGUAGE_;
 	}
}
}else{  //_MULTI_LANG_ toggle 
$lang = _DEFAULT_LANGUAGE_;
}
switch ($lang) {
  case 'en':
  $lang_file = 'lang.en.php';
  $_SESSION['lang'] = $lang;
  setcookie("lang", $lang, time() + (3600 * 24 * 30));
  break;

  case 'sv':
  $lang_file = 'lang.sv.php';
  $_SESSION['lang'] = $lang;
  setcookie("lang", $lang, time() + (3600 * 24 * 30));
  break;

  case 'es':
  $lang_file = 'lang.es.php';
  $_SESSION['lang'] = $lang;
  setcookie("lang", $lang, time() + (3600 * 24 * 30));
  break;

  default:
  $lang_file = 'lang.en.php';
}
include_once 'languages/'.$lang_file;
?>
