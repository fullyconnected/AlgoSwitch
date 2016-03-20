<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
require("../includes/class.inputfilter.php");
$access_level=_REGISTERLEVEL_;
db_connect();
check_login_member();


if($_GET["action"]=="refresh_state")
{
    $xqs_12="SELECT DISTINCT name,sta_id FROM geo_states WHERE con_id='".$_GET["country_id"]."' ORDER BY name" or die(mysql_error());  
    $xqr_12=mysql_query($xqs_12) or die(mysql_error());
    @$output .= "state_options.push(new Option('Choose State', ''));\n";
    while ($myrow = mysql_fetch_array($xqr_12))
    {
        $output .= "state_options.push(new Option('".addslashes($myrow["name"])."', '".$myrow["sta_id"]."'));\n";
    }  
}

if($_GET["action"]=="refresh_city")
{
    
    $xqs_12="SELECT cty_id,name FROM geo_cities WHERE sta_id='".$_GET["state_id"]."' ORDER BY name" or die(mysql_error());  
    $xqr_12=mysql_query($xqs_12) or die(mysql_error());
    @$output .= "city_options.push(new Option('Choose City', ''));\n";
    while ($myrow = mysql_fetch_array($xqr_12))
    {
        $output .= "city_options.push(new Option('".addslashes($myrow["name"])."', '".$myrow["cty_id"]."'));\n";
    }  
}
echo $output;
?>
