<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");

db_connect();


// Number of records to show per page
$recordsPerPage = 2; 

// default startup page
$pageNum = 1;

if(isset($_GET['p'])) {
  $pageNum = $_GET['p'];
  settype($pageNum, 'integer');
}

$offset = ($pageNum - 1) * $recordsPerPage;

$query="SELECT DISTINCT a.user_id,a.profilelink,a.profession,a.country,a.us_state,a.city,a.joindate,b.fk_user_id,b.picture_name,b.picture_number FROM users a, user_album2 b WHERE a.user_id=b.fk_user_id  AND b.mainphoto ='1' AND a.user_id!='1' ORDER BY a.joindate DESC LIMIT $offset, $recordsPerPage"; 
$result = mysql_query($query) or die('Mysql Err. 1');

$data='';
$state = '';
while($row = mysql_fetch_assoc($result)) {
                        
  $plink =  _BASEURL_."/".get_profile_link($row['user_id']);
  $prof = get_profession2($row['user_id']);
  $membername = get_name($row['user_id']);
  $picname = get_photo($row['user_id']);
  $joindate =nicetime($row['joindate'],1);
  $country=getCountry($row['country'],'abbrev2');
  $state = getRegion($row['us_state']);
  $city = getCity($row['city']);             

                        if ($picname=="") {
			    $picname="no-pict.gif";
			}
		$from = "$city $state, $country";
			
		if(empty($state)) {
		  
		  $from = "$country";
		}

		//<img  src=\"img.php?src=/memberpictures/thumbs/$picname&h=86&w=86&zc=1\" border=\"0\"  alt=\"$membername\" />
$data.="



<div class=\"media\">




<a class=\"pull-left\" href=\"$plink\"><img src=\"img.php?src=/memberpictures/$picname&h=86&w=86&zc=1\"border=\"0\" width=\"100%\" alt=\"$membername\" /></a>


<div>
 <div class=\"media-body\">
 
 <a class=\"\" href=\"$plink\"><h4>$membername  / $prof</h4></a>
 
 



<strong>$from</strong><br>



<strong class=\"\">$joindate</strong>";
			




$data.="  </div>";

}


$query ="SELECT DISTINCT a.profilelink,a.profession,a.my_diz,a.joindate, b.fk_user_id,b.picture_name,b.picture_number FROM users a, user_album2 b WHERE a.user_id=b.fk_user_id AND b.mainphoto ='1' AND a.user_id!=1 limit 0,20"; 


  $result  = mysql_query($query) or die('Mysql Err. 2');
  $row     = mysql_fetch_assoc($result);
  
	
  $row = mysql_fetch_row(mysql_query("SELECT FOUND_ROWS()"));

  $numrows = $row[0];  # 4

  $maxPage = ceil($numrows/$recordsPerPage);

  $nav = '';

  for($page = 1; $page <= $maxPage; $page++) {
  if ($page == $pageNum)     {
     $nav .= "<li class=\"currentpage\">$page</li>";
  }
  else
  {
    
      $nav .= "<li><a href=\"javascript:htmlData('processors/new_members_ajax.php','p=$page')\">$page</a></li>";
  }
}

if ($pageNum > 1) {

  $page = $pageNum - 1;
  $prev = "<a href=\"javascript:htmlData('processors/new_members_ajax.php','p=$page')\"><strong>Prev</strong></a></li>";

  $first = "<a href=\"javascript:htmlData('processors/new_members_ajax.php','p=1')\"><strong>&#171;</strong></a></li>";
}
else {
  $prev = '';
  $first = '';
}

if ($pageNum < $maxPage) {
  $page = $pageNum + 1;
  $next = "<a href=\"javascript:htmlData('processors/new_members_ajax.php','p=$page')\"><strong>Next</strong></a>";

  $last = "<a href=\"javascript:htmlData('processors/new_members_ajax.php','p=$maxPage')\"><strong>&#187;</strong></a>";
}
else {
  $next = '';
  $last = '';
}



echo $data;


$paginglinks ="<div class=\"paginationlinks\">

<div class=\"pager\"><ul>
      <li class=\"nextpage\"></li>
      <li class=\"nextpage\">$prev</li>
    
      <li class=\"nextpage\">$next</li>
      <li class=\"nextpage\"> </li></ul></div></div>";



if ($maxPage >1){
    echo $paginglinks;
}


?>
