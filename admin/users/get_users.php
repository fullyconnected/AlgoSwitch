<?php
session_start();
require("../../includes/vars.inc.php");
require("../../includes/functions.inc.php");
require("../../includes/templates.inc.php");
require("../../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;
db_connect();
check_login_member();



	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;  
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
	$email = isset($_POST['email']) ? mysql_real_escape_string($_POST['email']) : '';  
	$user_id = isset($_POST['user_id']) ? mysql_real_escape_string($_POST['user_id']) : '';  
  
	$offset = ($page-1)*$rows;  
  
	$result = array();  

	$where = "email like '$email%' and user_id like '$user_id%'";  
	$rs = mysql_query("select count(*) from users where " . $where);  
	$row = mysql_fetch_row($rs);  
	$result["total"] = $row[0];  
  
	$rs = mysql_query("SELECT user_id,user,gender,email,profilelink,profession,is_approved,country,us_state,city,status,birthdate,lat,lon,FROM_UNIXTIME(joindate, '%m/%d/%Y') as joindate FROM users WHERE " . $where . " ORDER BY user_id DESC limit $offset,$rows"." ");

		
	$items = array();
	while($row = mysql_fetch_object($rs)){
	
	$row->gender2 =  $accepted_genders[$row->gender];
	$row->profession2 = $accepted_professions[$row->profession];
	$row->birthdate = date("m/d/Y", strtotime($row->birthdate));
		array_push($items, $row);
		
	}

	$result["rows"] = $items;

	
echo json_encode($result);

?>