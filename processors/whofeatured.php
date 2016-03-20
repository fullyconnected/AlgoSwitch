<?PHP
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=$access_matrix['change_userpass'][0];


db_connect();
check_login_member();

$topass=array();
$error=false;
$max_user_vids=get_site_option('max_user_pics');
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$video_link=addslashes_mq($_POST['video_link']);
	$video_name=addslashes_mq($_POST['video_name']);
	//$screen_name=addslashes_mq($_POST['name']);

$curr_videos=get_video_vid2($_SESSION['user_id']);
			$vidno=1;
			$curr_numbers=array_keys($curr_videos);
			for ($i=1;$i<=$max_user_vids;$i++) {
				$vidno=$i;
				if (!in_array($vidno,$curr_numbers)) {
					break;
				}
			}
	


if (!$error) {
$query="INSERT INTO user_videos SET fk_user_id='".$_SESSION['user_id']."',video_number='$vidno',video_link='$video_link',video_name='$video_name'";
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$message.="Photo uploaded.";
            

		}


} else {
	if (isset($_GET['delete']) && !empty($_GET['delete']) && ($_GET['delete']>=1) && ($_GET['delete']<=$max_user_vids)) {
		$del=addslashes_mq($_GET['delete']);
		$query="SELECT video_link, video_name FROM user_videos WHERE fk_user_id='".$_SESSION['user_id']."' AND video_number='$del'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$vid=mysql_result($res,0,0);



		$query="DELETE FROM user_videos WHERE fk_user_id='".$_SESSION['user_id']."' AND video_number='$del'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		
	
				
		$message="video deleted";
	} else {
		$message="Invalid video selected to delete.";
	}
}
$topass=array('message'=>$message);
redirect2page("myvideos.php",$topass);
?>