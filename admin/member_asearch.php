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
$eyeshape="";
$eyecolor="";
$eyelashes="";
$eyebrows="";
$hairtype="";
$haircolor="";
$hairlength="";
$hairpiece="";
$eyewear="";
$faceshape="";
$bodytype="";
$waist="";
$chest="";
$shoes="";
$hips_inseam="";
$dress_shirt="";
$ethnic="";
$profession="";

$tpl->set_file('content','admin/member_asearch_index.html');
$tpl->set_var('message',$message);
$tpl->set_var('gender_options',vector2options($accepted_genders,_ANY_,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('agemin_options',vector2options($accepted_ages,01,array(_CHOOSE_)));
$tpl->set_var('agemax_options',vector2options($accepted_ages,73,array(_CHOOSE_)));
$tpl->set_var('country_options',vector2options($accepted_countries,_ANY_,array(_CHOOSE_)));
$tpl->set_var('state_options',vector2options($accepted_states,_ANY_,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('heightmin_options',vector2options($accepted_heights,_ANY_,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('heightmax_options',vector2options($accepted_heights,18,array(_CHOOSE_,_ANY_,_NDISCLOSED_)));
$tpl->set_var('weightmin_options',vector2options($accepted_weights,_ANY_,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('weightmax_options',vector2options($accepted_weights,8,array(_CHOOSE_,_ANY_,_NDISCLOSED_)));
$tpl->set_var('eyeshape_options',vector2options($accepted_eyeshapes,$eyeshape,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('eyecolor_options',vector2options($accepted_eyes,$eyecolor,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('eyelashes_options',vector2options($accepted_eyelashes,$eyelashes,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('eyebrows_options',vector2options($accepted_eyebrows,$eyebrows,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('hairtype_options',vector2options($accepted_hairtypes,$hairtype,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('haircolor_options',vector2options($accepted_hair,$haircolor,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('hairlength_options',vector2options($accepted_hairlength,$hairlength,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('hairpiece_options',vector2options($accepted_hairpieces,$hairpiece,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('eyewear_options',vector2options($accepted_eyewear,$eyewear,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('faceshape_options',vector2options($accepted_faceshapes,$faceshape,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('bodytype_options',vector2options($accepted_bodies,$bodytype,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('waist_options',vector2options($accepted_waists,$waist,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('chest_options',vector2options($accepted_chests,$chest,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('shoes_options',vector2options($accepted_shoes,$shoes,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('hips_inseam_options',vector2options($accepted_hips_inseam,$hips_inseam,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('dress_shirt_options',vector2options($accepted_dress_shirt,$dress_shirt,array(_CHOOSE_,_NDISCLOSED_)));
$tpl->set_var('ethnic_background_options',vector2options($accepted_ethnics,$ethnic,array(_CHOOSE_,_NDISCLOSED_)));

$tpl->set_var('profession_options',get_profile_pulldown(0,$profession,1));



$content=$tpl->process('out','content');
$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','View members');
$tpl->set_var('content',$content);
$tpl->set_var('message',$message);
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('relative_path',$relative_path);

//
print $tpl->process('out','frame',0,1);
?>
