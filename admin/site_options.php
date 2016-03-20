<?php
session_start();
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
require("../includes/vars.inc.php");
$access_level=_ADMINLEVEL_;
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();

 $tpl = new phemplate(_TPLPATH_,'remove_nonjs');
$tpl->set_file('content','admin/site_options.html');

$message='';


if ($_SERVER['REQUEST_METHOD']=='POST') {
if (isset($_POST['use_ratings'])) {
		$use_ratings=1;
	} else {
		$use_ratings=0;
	}
	
	
	
	if (isset($_POST['filter_emails'])) {
		$filter_emails=1;
	} else {
		$filter_emails=0;
	}
	if (isset($_POST['filter_urls'])) {
		$filter_urls=1;
	} else {
		$filter_urls=0;
	}
	if (isset($_POST['filter_words'])) {
		$filter_words=1;
	} else {
		$filter_words=0;
	}
	if (isset($_POST['signup_alerts'])) {
		$signup_alerts=1;
	} else {
		$signup_alerts=0;
	}
	if (isset($_POST['auto_approve'])) {
		$auto_approve=1;
	} else {
		$auto_approve=0;
	}
	if (isset($_POST['use_comments_portfolios'])) {
			$use_comments_portfolios=1;
		} else {
			$use_comments_portfolios=0;
	}
	if (isset($_POST['use_comments_photos'])) {
				$use_comments_photos=1;
			} else {
				$use_comments_photos=0;
	}
	if (isset($_POST['auto_expire_ads'])) {
					$auto_expire_ads=1;
				} else {
					$auto_expire_ads=0;
	}
	if (isset($_POST['email_confirmation'])) {
					$email_confirmation=1;
				} else {
					$email_confirmation=0;
	}
	
	
	//$email_confirmation=intval($_POST['email_confirmation']);
	
	$unused_interval=intval($_POST['unused_interval']);
	$inactive_interval=intval($_POST['inactive_interval']);
	
	$max_messages=intval($_POST['max_messages']);
	$mailfrom=sanitize($_POST['mailfrom']);
	$mailcontactus=sanitize($_POST['mailcontactus']);
	$max_user_pics=intval($_POST['max_user_pics']);
	$max_ads=intval($_POST['max_ads']);
	$max_headlines=intval($_POST['max_headlines']);
	
	

	$query="UPDATE site_options SET use_ratings='$use_ratings',filter_urls='$filter_urls',filter_emails='$filter_emails',filter_words='$filter_words',unused_interval='$unused_interval',inactive_interval='$inactive_interval',max_messages='$max_messages',signup_alerts='$signup_alerts',mailfrom='$mailfrom',mailcontactus='$mailcontactus',auto_approve='$auto_approve',max_user_pics='$max_user_pics',max_ads='$max_ads',max_headlines='$max_headlines',use_comments_portfolios='$use_comments_portfolios',use_comments_photos='$use_comments_photos',auto_expire_ads='$auto_expire_ads',email_confirmation='$email_confirmation'";
	if (!($res=@mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
	$message="Site options updated.";
}

$query="SELECT use_ratings,filter_urls,filter_emails,filter_words,unused_interval,inactive_interval,max_messages,signup_alerts,mailfrom,mailcontactus,auto_approve,max_user_pics,max_ads,max_headlines,use_comments_portfolios,use_comments_photos,auto_expire_ads,email_confirmation FROM site_options";
if (!($res=@mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
list($use_ratings,$filter_urls,$filter_emails,$filter_words,$unused_interval,$inactive_interval,$max_messages,$signup_alerts,$mailfrom,$mailcontactus,$auto_approve,$max_user_pics,$max_ads,$max_headlines,$use_comments_portfolios,$use_comments_photos,$auto_expire_ads,$email_confirmation)=mysql_fetch_row($res);
if ($use_ratings==1) {
	$use_ratings="checked";
} else {
	$use_ratings="";
}
if ($filter_urls==1) {
	$filter_urls="checked";
} else {
	$filter_urls="";
}
if ($filter_emails==1) {
	$filter_emails="checked";
} else {
	$filter_emails="";
}
if ($filter_words==1) {
	$filter_words="checked";
} else {
	$filter_words="";
}
if ($signup_alerts==1) {
	$signup_alerts="checked";
} else {
	$signup_alerts="";
}
if ($auto_approve==1) {
	$auto_approve="checked";
} else {
	$auto_approve="";
}
if ($use_comments_portfolios==1) {
	$use_comments_portfolios="checked";
} else {
	$use_comments_portfolios="";
}
if ($use_comments_photos==1) {
	$use_comments_photos="checked";
} else {
	$use_comments_photos="";
}
if ($auto_expire_ads==1) {
	$auto_expire_ads="checked";
} else {
	$auto_expire_ads="";
}
if ($email_confirmation==1) {
	$email_confirmation="checked";
} else {
	$email_confirmation="";
}



$tpl->set_var('use_ratings',$use_ratings);

$tpl->set_var('filter_emails',$filter_emails);
$tpl->set_var('filter_urls',$filter_urls);
$tpl->set_var('filter_words',$filter_words);
$tpl->set_var('unused_interval',$unused_interval);
$tpl->set_var('inactive_interval',$inactive_interval);
$tpl->set_var('max_messages',$max_messages);
$tpl->set_var('signup_alerts',$signup_alerts);
$tpl->set_var('mailfrom',$mailfrom);
$tpl->set_var('mailcontactus',$mailcontactus);
$tpl->set_var('auto_approve',$auto_approve);
$tpl->set_var('max_user_pics',$max_user_pics);
$tpl->set_var('max_ads',$max_ads);
$tpl->set_var('max_headlines',$max_headlines);
$tpl->set_var('use_comments_portfolios',$use_comments_portfolios);
$tpl->set_var('use_comments_photos',$use_comments_photos);
$tpl->set_var('auto_expire_ads',$auto_expire_ads);
$tpl->set_var('email_confirmation',$email_confirmation);

$content=$tpl->process('','content');

$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','Change some global options');
$tpl->set_var('content',$content);
$tpl->set_var('relative_path',$relative_path);
$tpl->set_var('message',$message);
$tpl->set_var('baseurl',_BASEURL_);

print $tpl->process('out','frame',0,1);
?>