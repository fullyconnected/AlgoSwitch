<?php
header ("content-type: text/xml");
require("includes/vars.inc.php");
require("includes/functions.inc.php");

require("includes/apt_functions.inc.php");
$access_level=_GUESTLEVEL_;

db_connect();


$base = _BASEURL_;




$memberid = htmlspecialchars(intval($_GET['uid']));
if(empty($memberid)){
$memberid = '1';
}



$query1 = "SELECT * FROM user_audio WHERE user_id=$memberid";
$result1 = mysql_query($query1);


// display RSS 2.0 channel information

ECHO <<<END
	<playlist version="1">

	<trackList>
    
END;

// loop through the array pulling database fields for each item
for ($i = 0; $i < mysql_num_rows($result1); $i++) {
   @$row = mysql_fetch_array($result1);
   $uid = cleanText($row["user_id"]);
   $filename = $row["file_name"];
   $title = remove_underscore2($row["audio_title"]);
   $artist = remove_underscore2($row["artist_name"]);
   $artist = toAscii($artist);

ECHO <<<END
<track>
<title>$title</title>
<creator>$artist</creator>
<location>$base/audio/$filename</location>
<image></image>
<meta rel="type">mp3</meta>
<info>
</info>
<identifier></identifier>
</track>


END;



}
ECHO <<<END
  </trackList>
</playlist>

END;
?>
