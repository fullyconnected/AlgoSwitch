<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;
$howmanyfans = '';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_,_PCONN_);
check_login_member();

$tpl = new phemplate(_TPLPATH_,'remove_nonjs');

$tpl->set_file('content','admin/approval.html');

$message='';
$howmany= "12";	         // -> change this
$table_cols= "4";		// -> change this
global $relative_path;
$offset = "0";
$set    = "12";
if(@$_GET['offset'] == ""){
	$offset = "0";         
        }
        if(isset($_GET['offset'])){
       $offset  = $_GET['offset'];
       $offset2 = $offset;
        }
$query="SELECT user_id,user FROM users WHERE is_approved='0' ORDER BY user_id ASC LIMIT $howmany OFFSET $offset";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$myreturn='';
	
	if (mysql_num_rows($res)) {
	
		$myreturn="<table width=\"100%\" cellpadding=\"0\" cellspacing=\"1\" align=\"center\" valign=\"top\">";
		$i=1;
		while ($i < $rsrow=mysql_fetch_row($res)) {
                       
            $newuser_id = $rsrow[0];
			$natty = get_name($rsrow[0]);
            
  			$picname = get_photo($rsrow[0]);
			$prof = get_profession2($rsrow[0]);
			$uname = remove_underscore($rsrow[1]);
			if (($i%$table_cols)==1) {$myreturn.="<tr>\n";}
			
			
			
			$myreturn.="\t<td align=\"center\" valign=\"top\">\n";
			$myreturn.="<br><a class=\"link-newest\" href=\"manage_member.php?user_id=$rsrow[0]\" title=\"$uname\"><img src=\"../memberpictures/thumbs/$picname\" class=\"mainimage\" border=\"0\" alt=\"$uname\"></A><br /><b>$uname</b><br>$prof";
			$myreturn.="\t</td>\n";
			if ($i%$table_cols==0) {$myreturn.="</tr><tr><td>&nbsp;</td></tr>\n";}
			$i++;
		}
		$rest=($i-1)%$table_cols;
		if ($rest!=0) {
			$colspan=$table_cols-$rest;
			$myreturn.="\t<td".(($colspan==1) ? ("") : (" colspan=\"$colspan\""))."></td>\n</tr>\n";
		}

		
		$myreturn.="</table>\n ";
		
		
		$preva = "<B>PREV</B>";
		$nesta = "<B>NEXT</B>";
		$query = "SELECT count(user_id) FROM users WHERE is_approved='0'";
			if (!($howmuch=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
              	$rzrow=mysql_fetch_row($howmuch);
			     $howmanyfans =  $rzrow[0];      
			
		
	  if($offset >= $set){

                $offset  = $offset + $set;

                $offset2 = $offset2 - $set;
					if ($offset>$howmanyfans) {
				$nesta = "";
				}
				
                $myreturn.="<br><br><a href=\"approveme.php?user_id=$user_id&amp;offset=$offset2\">$preva</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"approveme.php?user_id=$user_id&amp;offset=$offset\">$nesta</A>\n";

                }else{

                $offset=$set;
				if ($offset>0) {
				$preva = "";
				}
			if ($offset>$howmanyfans) {
				$nesta = "";
				}
				
                $myreturn.="<br><br>$preva&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"approveme.php?user_id=$user_id&amp;offset=$offset\">$nesta</A>\n";

                }

	}
$app =  $myreturn;
$isapoff = get_site_option('auto_approve');

if ($howmanyfans==0 && $isapoff ==0){
$mess = 'There are no new members for approval <br /><br />';
}elseif($howmanyfans !=0  && $isapoff ==1){
$mess = "You have auto approve turned on. Members below have to be approved manually";
}elseif($howmanyfans ==0  && $isapoff ==1){
$mess = "You have auto approve turned on.<br /><br />";
}else{
$mess = "There are $howmanyfans new members to approve ";
}

$tpl->set_var('mess',$mess);	

$tpl->set_var('list',$app);
$content=$tpl->process('out','content',1);
 $tpl = new phemplate(_TPLPATH_,'remove_nonjs');
$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','New Member Approval');
$tpl->set_var('content',$content);

$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('relative_path',$relative_path);

print $tpl->process('out','frame');
?>