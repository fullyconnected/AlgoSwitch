<?PHP
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/templates.inc.php");
require("includes/apt_functions.inc.php");
$access_level=$access_matrix['changemyemail'][0];

db_connect();
check_login_member();


if(isset($_SESSION['user_id'])){
$myuser_id = $_SESSION['user_id'];}

else {$myuser_id = "";}
global $relative_path;

if(isset($_SESSION['user_id'])){
$query="SELECT profession,membership FROM users WHERE user_id='".$_SESSION['user_id']."'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($profession,$mymembership)=mysql_fetch_row($res);
}


$tpl = new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());


if (isset($_SESSION['topass']) && is_array($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']='';
	unset($_SESSION['topass']);
}


$query="SELECT email FROM users WHERE user_id='".$_SESSION['user_id']."'";
			if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
			list($curemail)=mysql_fetch_row($res);

$message=((isset($topass['message'])) ? ($topass['message']) : (""));
$newemail=((isset($topass['newemail'])) ? ($topass['newemail']) : (""));
$tpl->set_file('middlecontent','changemyemail.html');
$tpl->set_var('message',$message);
$tpl->set_var('curem',$curemail);
$tpl->set_var('newem',$newemail);
$tpl->set_var('lang', $lang);
$middle_content=$tpl->process('out','middlecontent',0,1);

$title="Change my email";
include('blocks/block_main_frame.php');
?>