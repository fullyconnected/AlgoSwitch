<?php
header("Content-Type: application/xml; charset=utf-8"); 
session_start();
require("includes/vars.inc.php");
require("includes/functions.inc.php");
require("includes/apt_functions.inc.php");
$access_level=_GUESTLEVEL_;

db_connect();
$date = ydate();
$user='';
$link='';
$city='';
$title='';
$img='';
$desc='';
$joindate='';
$query1 = "SELECT a.user_id,a.profilelink,b.picture_number,b.picture_name,city,a.my_diz, a.joindate,a.user,a.profession FROM users a, user_album2 b WHERE a.user_id=b.fk_user_id AND b.mainphoto =1 AND a.user_id!=1 ORDER BY joindate DESC LIMIT 10"; 
$result1 = mysql_query($query1);
$sitename = _SITENAME_;
$baseurl = _BASEURL_;

ECHO <<<END
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">

<channel>
<title>$sitename</title>
<link>$baseurl</link>
<language>en-us</language> 
<description>New Members</description>
<copyright>Copyright (c) $date $sitename All rights reserved.</copyright>

END;

// loop through the array pulling database fields for each item
for ($i = 0; $i < mysql_num_rows($result1); $i++) {
   @$row = mysql_fetch_array($result1);
   $title = cleanText($row['user']);
   $link = $baseurl."/".($row['profilelink']);
   $img =  $baseurl."/memberpictures/thumbs/".$row["picture_name"];
   $city = getCity($row['city']);
   $desc = strip_tags(limit_text($row['my_diz'],120));
   $joindate = date("r", $row['joindate']);
   $profession = get_profession2($row['user_id']);


ECHO <<<END
    
    <item>
<title>$title / $profession</title>
<description><![CDATA[<img src="$img" alt="$title" />]]><![CDATA[<p>


$desc

</p>]]></description>
<link>$link</link>
<guid>$link</guid>
<category>$profession</category>
<pubDate>$joindate</pubDate>

</item>
    
    
     

END;
}
ECHO <<<END
</channel>
</rss>

END;
?>
