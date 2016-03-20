<?php

session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();

$tpl = new phemplate(_TPLPATH_,'remove_nonjs');



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
$country=((isset($topass['country'])) ? ($topass['country']) : (1));
$us_state=((isset($topass['us_state'])) ? ($topass['us_state']) : (1));
$city=((isset($topass['city'])) ? ($topass['city']) : (""));
$zip=((isset($topass['zip'])) ? ($topass['zip']) : (""));
$addr=((isset($topass['addr'])) ? ($topass['addr']) : (""));
$birthday=((isset($topass['birthday'])) ? ($topass['birthday']) : (""));
$birthmonth=((isset($topass['birthmonth'])) ? ($topass['birthmonth']) : (""));
$birthyear=((isset($topass['birthyear'])) ? ($topass['birthyear']) : (""));
$phone1=((isset($topass['phone1'])) ? ($topass['phone1']) : (""));
$phone2=((isset($topass['phone2'])) ? ($topass['phone2']) : (""));
$my_diz=((isset($topass['my_diz'])) ? ($topass['my_diz']) : (""));

$tpl->set_file('content','admin/addmember.html');
$tpl->set_var('message',$message);
$tpl->set_var('plink',htmlentities(stripslashes($plink)));

$tpl->set_var('name',htmlentities(stripslashes($name)));
$tpl->set_var('password',htmlentities(stripslashes($password)));
$tpl->set_var('password2',htmlentities(stripslashes($password2)));
$tpl->set_var('email',htmlentities(stripslashes($email)));
$tpl->set_var('email2',htmlentities(stripslashes($email2)));
$tpl->set_var('gender_options',vector2options($accepted_genders,$gender,array(_ANY_,_NDISCLOSED_)));

$tpl->set_var('profession_options',get_profile_pulldown(0,0,0));


$tpl->set_var('country_options',vector2options($accepted_countries,$country,array(_ANY_)));

$tpl->set_var('phone1',htmlentities(stripslashes($phone1)));
$tpl->set_var('phone2',htmlentities(stripslashes($phone2)));
$tpl->set_var('addr',htmlentities(stripslashes($addr)));
$tpl->set_var('zip',htmlentities(stripslashes($zip)));
$tpl->set_var('city',htmlentities(stripslashes($city)));
$tpl->set_var('usstate_options',vector2options($accepted_states,$us_state,array(_ANY_)));
$tpl->set_var('birthday_options',vector2options($days,$birthday));
$tpl->set_var('birthmonth_options',vector2options($accepted_months,$birthmonth));
$tpl->set_var('birthyear_options',vector2options($accepted_birthdate_years,$birthyear));




$plink2 = _PLINK_;
$tpl->set_var('plink2',$plink2);

$content=$tpl->process('out','content',1);
$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','Add New Member');

$tpl->set_var('content',$content);
$tpl->set_var('message',$message);
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('relative_path',$relative_path);

//
print $tpl->process('out','frame',0,1);
?>
