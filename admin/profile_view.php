<?
session_start();
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
require("../includes/vars.inc.php");
$access_level=_ADMINLEVEL_;
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();

 $tpl = new phemplate(_TPLPATH_,'remove_nonjs');
$tpl->set_file('content','admin/profile_view.html');

if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
	$topass=$_SESSION['topass'];
	$_SESSION['topass']="";
}
$message=((isset($topass['message'])) ? ($topass['message']) : (""));

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
	$user_id=addslashes_mq($_GET['user_id']);
}

$delphoto="";
$query="SELECT firstname,lastname,user,gender,(YEAR(NOW())-YEAR(birthdate)),DAYOFYEAR(birthdate),ethnic,country,us_state,city,zip,addr,phone1,phone2,my_diz,work_interest,hairlength,hairtype,haircolor,hairpiece,eyeshape,eyecolor,eyebrows,eyelashes,eyewear,faceshape,bodytype,waist,chest,hips_inseam,height,weight,shoes,dress_shirt,membership,profession,status FROM users WHERE user_id='$user_id'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
if (mysql_num_rows($res)) {
list($firstname,$lastname,$name,$gender,$age,$zodiac,$ethnic,$country,$us_state,$city,$zip,$addr,$phone1,$phone2,$my_diz,$work_interest,$hairlength,$hairtype,$haircolor,$hairpiece,$eyeshape,$eyecolor,$eyebrows,$eyelashes,$eyewear,$faceshape,$bodytype,$waist,$chest,$hips_inseam,$height,$weight,$shoes,$dress_shirt,$membership,$profession,$status)=mysql_fetch_row($res);
	if (empty($photo)) {
		$photo='no-pict.gif';
	} else {
		$delphoto="<a href=\"do.php?act=delphoto&user_id=$user_id\">Delete photo</a>";
	}
	$photo=_IMAGESURL_."/".$photo;
	if ($country!=1) {
		$us_state="";
	}
	$active='';
	$notactive='';
	$suspended='';
	$registered='';
	$subscriber='';
	$admin='';
	if ($status==_STATUSACTIVE_) {
		$active='checked';
	} elseif ($status==_STATUSNOTACTIVE_) {
		$notactive='checked';
	} elseif ($status==_STATUSSUSPENDED_) {
		$suspended='checked';
	}
	if ($membership==_REGISTERLEVEL_) {
		$registered='checked';
	} elseif ($membership==_SUBSCRIBERLEVEL_) {
		$subscriber='checked';
	} elseif ($membership==_ADMINLEVEL_) {
		$admin='checked';
	}

	$pictures=get_album_pic2($user_id);
	$photos=array();
	$i=0;
	while (list($k,$v)=each($pictures)) {
		$photos[$i]['picture_name']=_IMAGESURL_."/$v";
		$photos[$i]['picture_number']=$k;
		$i++;
	}

	$titlename=$name."'s";

	$tpl->set_var('titlename',htmlentities(stripslashes($titlename)));
	$tpl->set_var('user_id',$user_id);
	$tpl->set_loop('photos',$photos);
	$tpl->set_var('name',htmlentities(stripslashes($name)));
	$tpl->set_var('photo',$photo);
	$tpl->set_var('delphoto',$delphoto);
	$tpl->set_var('gender',$accepted_genders[$gender]);
	$tpl->set_var('age',$accepted_ages[$age]);
	$tpl->set_var('ethnic',$accepted_ethnics[$ethnic]);
	$tpl->set_var('country',$accepted_countries[$country]);
	if ($fields['users.us_state'][0]) {
		$tpl->set_file('temp','bits/field_usstate_show.html');
		$tpl->set_var('us_state',((empty($us_state)) ? ("") : ($accepted_states[$us_state])));
		$field_state=$tpl->process('out','temp');
	} else {
		$field_state="";
	}
	if ($fields['users.city'][0]) {
		$tpl->set_file('temp','bits/field_city_show.html');
		$tpl->set_var('city',htmlentities(stripslashes($city)));
		$field_city=$tpl->process('out','temp');
	} else {
		$field_city="";
	}
	if ($fields['users.addr'][0]) {
		$tpl->set_file('temp','bits/field_addr_show.html');
		$tpl->set_var('addr',htmlentities(stripslashes($addr)));
		$field_addr=$tpl->process('out','temp');
	} else {
		$field_addr="";
	}
	if ($fields['users.zip'][0]) {
		$tpl->set_file('temp','bits/field_zip_show.html');
		$tpl->set_var('zip',htmlentities(stripslashes($zip)));
		$field_zip=$tpl->process('out','temp');
	} else {
		$field_zip="";
	}
	$tpl->set_var('profession',$accepted_professions[$profession]);
	$tpl->set_var('field_state',$field_state);
	$tpl->set_var('field_city',$field_city);
	$tpl->set_var('field_zip',$field_zip);
	$tpl->set_var('field_addr',$field_addr);
	$tpl->set_var('active',$active);
	$tpl->set_var('notactive',$notactive);
	$tpl->set_var('suspended',$suspended);
	$tpl->set_var('registered',$registered);
	$tpl->set_var('subscriber',$subscriber);
	$tpl->set_var('admin',$admin);

	if (!is_approved($user_id)) {
		$tpl->set_var('approval_links',"<a href=\"approval.php?action=2&user_id=$user_id\">Don't approve</a>&nbsp;&nbsp;&nbsp;<a href=\"approval.php?action=1&user_id=$user_id\">Approve</a>");
	}

	$content=$tpl->process('out','content',1);

	$topass=array('return2'=>"profile_view.php");
	$topass['redir_qparams']="user_id=$user_id";
	$_SESSION['topass']=$topass;

	$tpl->set_file('frame','admin/frame.html');
	$tpl->set_var('title','View members');
	$tpl->set_var('content',$content);
	$tpl->set_var('message',$message);
	$tpl->set_var('baseurl',_BASEURL_);
	$tpl->set_var('relative_path',$relative_path);

	print $tpl->process('out','frame',0,1);
} else {
?>
<script type="text/javascript">
	history.go(-1);
</script>
<?
}
?>
