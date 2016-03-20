<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();



$message='';
 $tpl = new phemplate(_TPLPATH_,'remove_nonjs');
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$error=false;
	while (list($k,$v)=each($access_matrix)) {
		if (isset($_POST[$k])) {
			$levels[$k]=addslashes_mq($_POST[$k]);
		} else {
			$error=true;
			break;
		}
	}
	if (!$error) {
		$towrite="<?\n";
		while (list($k,$v)=each($levels)) {
			$towrite.="\$access_matrix['$k'][0]=".(($levels[$k]==_SUBSCRIBERLEVEL_) ? ("_SUBSCRIBERLEVEL_") : (($levels[$k]==_REGISTERLEVEL_) ? ("_REGISTERLEVEL_") : ("_GUESTLEVEL_"))).";\n";
			$towrite.="\$access_matrix['$k'][1]=\"".$access_matrix[$k][1]."\";\n";
		}
		$towrite.="?>";
		$fp=fopen("../includes/access_matrix.inc.php",'w');
		if ($fp) {
			if (!fwrite($fp,$towrite)) {
				$message="Cannot write to access definition file. Please check that you have write permissions on that file.";
			} else {
				$message="Access rights updated.";
			}
			fclose($fp);

		} else {
			$message="Cannot open access definition file. Please check that you have write permissions on that file.";
		}
	} else {
		$message='There has been an error processing your query. Please inform the admin about that.';
	}
	$content="";
} else {
	$levels=array();
	$i=0;
	while (list($k,$v)=each($access_matrix)) {
		$levels[$i]['key']=$k;
		$levels[$i]['diz']=$access_matrix[$k][1];
		if ($access_matrix[$k][0]==_GUESTLEVEL_) {
			$levels[$i]['checkguest']='checked';
			$levels[$i]['checkregistered']='';
			$levels[$i]['checksubscriber']='';
		} elseif ($access_matrix[$k][0]==_REGISTERLEVEL_) {
			$levels[$i]['checkguest']='';
			$levels[$i]['checkregistered']='checked';
			$levels[$i]['checksubscriber']='';
		} elseif ($access_matrix[$k][0]==_SUBSCRIBERLEVEL_) {
			$levels[$i]['checkguest']='';
			$levels[$i]['checkregistered']='';
			$levels[$i]['checksubscriber']='checked';
		}
		$levels[$i]['myclass']=(($i%2) ? ('trimpar') : ('trpar'));
		$i++;
	}
	$tpl->set_file('temp','admin/acclevels.html');
	$tpl->set_loop('levels',$levels);
	$tpl->set_var('relative_path',$relative_path);
	$content=$tpl->process('out','temp',1);
}

$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title',"Change page's access level");
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('message',$message);
$tpl->set_var('content',$content);
$tpl->set_var('relative_path',$relative_path);


print $tpl->process('out','frame',0,1);
?>