<?php
function db_connect() {
	if (_PCONN_) {
		if (!(mysql_pconnect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_))) {error(mysql_error(),__LINE__,__FILE__);}
	} else {
		if (!(mysql_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_))) {error(mysql_error(),__LINE__,__FILE__);}

  }
	
	mysql_select_db(_DBNAME_);
	mysql_query("SET CHARACTER SET utf8");

}
// strip invalid xml char
function cleanInput($input) {
	$search = array(
	'@<script[^>]*?>.*?</script>@si',   /* strip out javascript */
	'@<[\/\!]*?[^<>]*?>@si',            /* strip out HTML tags */
	'@<style[^>]*?>.*?</style>@siU',    /* strip style tags properly */
	'@<![\s\S]*?--[ \t\n\r]*>@'         /* strip multi-line comments */
	);

	$output = preg_replace($search, '', $input);
	return $output;
}

function strip_invalid_xml_chars2($in)
{
$out = "";
$length = strlen($in);
for ( $i = 0; $i < $length; $i++)
{
$current = ord($in{$i});
if ( ($current == 0x9) || ($current == 0xA) || ($current == 0xD) || (($current >= 0x20) && ($current <= 0xD7FF)) || (($current >= 0xE000) && ($current <= 0xFFFD)) || (($current >= 0x10000) && ($current <= 0x10FFFF)))

{

        $out .= chr($current);

}else{

        $out .= " ";

		}

	}
return $out;
}

function cleanText($intext) {

    return strip_invalid_xml_chars2(utf8_encode(stripslashes(htmlentities(strip_tags($intext)))));
}

function ydate() {  //copy right date

 $date = date('Y');
 return $date;
}

function vector2table($vector) {
    $afis="<table>\n";
    $i=1;
    $afis.="<tr>\n\t<td class=title colspan=2>Table</td>\n</tr>\n";
    while (list($k,$v) = each($vector)) {
        $afis.="<tr class=".(($i%2) ? "trpar" : "trimpar").">\n\t<td>".htmlentities($k)."</td>\n\t<td>".htmlentities($v)."</td>\n</tr>\n";
        $i++;
    }
    $afis.="</table>\n";
    return $afis;
}


function vector2biditable($myarray,$rows,$cols) {
	$myreturn="<table>\n";
	for ($r=0;$r<$rows;$r++) {
		$myreturn.="<tr>\n";
		for ($c=0;$c<$cols;$c++) {
			$myreturn.="\t<td>".$myarray[$r*$cols+$c]."</td>\n";
		}
		$myreturn.="</tr>\n";
	}
	$myreturn.="</table>\n";
	return $myreturn;
}


function vector2options($show_vector,$selected_map_val,$exclusion_vector=array()) {
	$myreturn='';
	while (list($k,$v)=each($show_vector)) {
		if (!in_array($k,$exclusion_vector)) {
			$myreturn.="<option value=\"".$k."\"";
			if ($k==$selected_map_val) {
				$myreturn.=" selected=\"selected\"";
			}
			$myreturn.=">".$v."</option>\n";
		}
	}
	return $myreturn;
}

function vector2options_2($show_vector,$selected_map_val,$exclusion_vector=array()) {
	$myreturn='';
	while (list($k,$v)=each($show_vector)) {
		if (!in_array($k,$exclusion_vector)) {
			$myreturn.="<option value=\"".$k."\"";
			if ($k==$selected_map_val) {
				$myreturn.=" selected=\"selected\"";
			}
			$myreturn.=">".$v."</option>\n";
		}
	}
	return $myreturn;
}


function vector2options_selected_array($show_vector,$selected_map_val,$exclusion_vector=array()) {
	$myreturn='';
	while (list($k,$v)=each($show_vector)) {
		if (!in_array($k,$exclusion_vector)) {
			$myreturn.="<option value=\"".$k."\"";
			
		/*	if ($k==$selected_map_val) {
				$myreturn.=" selected";
			}*/
			
			if(!empty($selected_map_val)){
			
			@$myreturn.= (in_array($k, $selected_map_val)) ? ' selected="selected"' : '' ;  
			}
			
			$myreturn.=">".$v."</option>\n";
		}
	}
	return $myreturn;
}




function vector2checkboxes($show_vector,$excluded_keys_vector,$checkname,$binvalue,$table_cols=1,$showlabel=true) {
	$myreturn='<table>';
	$i=0;
	$row=0;
	$myvector=array_flip(array_diff(array_flip($show_vector),$excluded_keys_vector));
	$total_vals=count($myvector);
	$i=1;
	while (list($k,$v)=each($myvector)) {
		if (($i%$table_cols)==1) {$myreturn.="<tr>\n";}
		$myreturn.="\t<td>\n";
		$myreturn.="\t\t<input type=\"checkbox\" name=\"".$checkname."[$k]\"";
		if (isset($binvalue) && ($binvalue>0) && (($binvalue>>$k)%2)) {
//print "binvalue=$binvalue k=$k<br>";
			$myreturn.=" checked";
		}
		$myreturn.=">";
		if ($showlabel) {
			$myreturn.=$v;
		}
		$myreturn.="\n";
		$myreturn.="\t</td>\n";
		if ($i%$table_cols==0) {$myreturn.="</tr>\n";}
		$i++;
	}
	$rest=($i-1)%$table_cols;
	if ($rest!=0) {
		$colspan=$table_cols-$rest;
		$myreturn.="\t<td".(($colspan==1) ? ("") : (" colspan=\"$colspan\""))."></td>\n</tr>\n";
	}
	$myreturn.="</table>\n";
	return $myreturn;
}

function vector2binvalues($myarray) {
	$myreturn=0;
	while (list($k,$v)=each($myarray)) {
		$myreturn+=(1<<$k);
	}
	return $myreturn;
}


function binvalue2index($binvalue) {
	$myarray=array();
	$i=0;
	while ($binvalue>0) {
		if ($binvalue & 1) {
			$myarray[]=$i;
		}
		$binvalue>>=1;
		$i++;
	}
	return $myarray;
}


function array2string($myarray,$binvalue) {
	$myreturn='';
	while (list($k,$v)=each($myarray)) {
		if (isset($binvalue) && ($binvalue>0) && (($binvalue>>$k)%2)) {
			$myreturn.=$v.', ';
		}
	}
	$myreturn=substr($myreturn,0,-2);
	return $myreturn;
}


function del_keys($myarray,$keys) {
	$myreturn=array();
	while (list($k,$v)=each($myarray)) {
		if (!in_array($k,$keys)) {
			$myreturn[$k]=$v;
		}
	}
	return $myreturn;
}


function del_empty_vals($myarray) {
	$myreturn=array();
	while (list($k,$v)=each($myarray)) {
		if (!empty($v)) {
			$myreturn[$k]=$v;
		}
	}
	return $myreturn;
}


function stripslashes_mq($value,$trim=false,$stripsql=false) {
	if (is_array($value)) {
		$return=array();
		while (list($k,$v)=each($value)) {
			$return[stripslashes_mq($k)]=stripslashes_mq($v);
		}
	} else {
		if ($trim) {
			$value=trim($value);
		}
		if(get_magic_quotes_gpc() == 0) {
			$return=$value;
		} else {
			$return=stripslashes($value);
		}
		if ($stripsql) {
			$return=str_replace('\%','%',$return);
			$return=str_replace('\_','_',$return);
		}
	}
	return $return;
}


function addslashes_mq($value,$trim=false,$stripsql=false) {
	
	
	if (is_array($value)) {
		$return=array();
		while (list($k,$v)=each($value)) {
			$return[addslashes_mq($k)]=addslashes_mq($v);
		}
	} else {
		if ($trim) {
			$value=trim($value);
		}
		if(get_magic_quotes_gpc() == 0) {
			$return=addslashes($value);
		} else {
			$return=$value;
		}
		if ($stripsql) {
			$return=str_replace('%','\%',$return);
			$return=str_replace('_','\_',$return);
		}
	}
	return $return;
}
function redirect2page($pagename,$topass=array(),$qstring="",$full_url=false) {
	if (!empty($pagename)) {
		if (!$full_url) {
			$redirect=_BASEURL_."/".$pagename;
			$separator="?";
			if (SID!="") {
				$redirect.=$separator.SID;
				$separator="&amp;";
			}
			if (!empty($qstring)) {
				$redirect.=$separator.$qstring;
				$separator="&amp;";
			}
		} else {
			$redirect=$pagename;
		}
		if (isset($topass) && !empty($topass)) {
			$_SESSION['topass']=$topass;
		}
		header("Status: 303 See Other",true);
		header("Location: $redirect",true);
	} else {
		error("No page specified for redirect",__LINE__,__FILE__);
	}
	exit;
}
function unix2dos($mystring) {
	$mystring=preg_replace("/\n/m","\r\n",$mystring);
	return $mystring;
}

function send_email($from,$to,$subject,$message) {
	$sentok=mail($to,$subject,$message,"From: $from\r\n");
	return $sentok;
}

function str_highlight($text, $needle, $options = null, $highlight = null){  // currently used for blogs
    // Default highlighting
    if ($highlight === null) {
        $highlight = '<strong>\1</strong>';
    }
 
    // Select pattern to use
    if ($options & 'STR_HIGHLIGHT_SIMPLE') {
        $pattern = '#(%s)#';
        $sl_pattern = '#(%s)#';
    } else {
        $pattern = '#(?!<.*?)(%s)(?![^<>]*?>)#';
        $sl_pattern = '#<a\s(?:.*?)>(%s)</a>#';
    }
 
    // Case sensitivity
    if (!($options & 'STR_HIGHLIGHT_CASESENS')) {
        $pattern .= 'i';
        $sl_pattern .= 'i';
    }
 
    $needle = (array) $needle;
    foreach ($needle as $needle_s) {
        $needle_s = preg_quote($needle_s);
 
        // Escape needle with optional whole word check
        if ($options & 'STR_HIGHLIGHT_WHOLEWD') {
            $needle_s = '\b' . $needle_s . '\b';
        }
 
        // Strip links
        if ($options & 'STR_HIGHLIGHT_STRIPLINKS') {
            $sl_regex = sprintf($sl_pattern, $needle_s);
            $text = preg_replace($sl_regex, '\1', $text);
        }
 
        $regex = sprintf($pattern, $needle_s);
        $text = preg_replace($regex, $highlight, $text);
    }
 
    return $text;
}
function format_bbcodes($text)
{
  global $lang;
  $search = array(
  '[b]','[/b]','[B]','[/B]','[i]','[/i]','[I]','[/I]','[u]','[/u]','[U]','[/U]');
  $replace = array(
  '<strong>','</strong>','<strong>','</strong>','<em>','</em>','<em>','</em>','<u>','</u>','<u>','</u>');
  
  $text = str_replace($search, $replace, $text);

  $search = array(
  '#\[img\](http|https|ftp)://(.*?)\[/img\]#i',
  '#\[email\](.*?)\[/email\]#i',
  '#\[email=(.*?)\](.*?)\[/email\]#i',  
  '#\[url=(http|https|ftp)://(.+?)\](.+?)\[/url\]#ie',
  '#\[url\](http|https|ftp)://(.+?)\[/url\]#ie',
  '#\[code\]#i',
  '#\[/code\]#i',
  '#\[quote\]#i',
  '#\[quote=(.*?)\]#i',
  '#\[/quote\]#i'
  );
  $replace = array(
  '<img src="\\1://\\2" alt="\\1://\\2">',
  '<a href="mailto:\\1">\\1</a>',
  '<a href="mailto:\\1">\\2</a>',
  'trunc_url(\'\\1://\\2\',\'\\3\')',

  'trunc_url(\'\\1://\\2\',\'\\1://\\2\')',
  '<div class="code"><b>'.$lang['code'].': </b><br><br>',
  '</div>',
  '<div class="quote"><b>'.$lang['quote'].': </b><br>',
  '<div class="quote"><b>'.$lang['quoting'].' \\1</b><br>',
  '</div>'
  );
  
  return preg_replace($search, $replace, $text);
}
function get_relative_path($baseurl,$myurl) {
	
	$myreturn="";
	$baseinfo=parse_url(substr($baseurl,0,-1));
	$myinfo=parse_url($myurl);
	$basepath=$baseinfo['path'];
	$mypath=$myinfo['path'];
	$count=substr_count($mypath,"/")-substr_count($basepath,"/");
	for ($i=1;$i<$count;$i++) {
		$myreturn.="../";
	}
	return $myreturn;
}


function general_error($message,$line='unset',$file='unset') {
	?>
	<center>
	There has been an error:<br />
	<font class="alert"><b><?=$message?></b></font>
	<br />
	<?=((_DEBUG_) ? ("Line: $line") : (""))?>
	<br />
	<?=((_DEBUG_) ? ("File: $file") : (""))?>
	</center>
	<?
	exit;
}

function array2qs($myarray) {
	$myreturn="";
	while (list($k,$v)=each($myarray)) {
		$myreturn.="$k=$v&";
	}
	$myreturn=substr($myreturn,0,-1);
	return $myreturn;
}


function create_pager($from,$where,$offset,$results) {
	$myreturn='';
	mt_srand(make_seed());
	$radius=5;
	global $PHP_SELF;
	global $accepted_results_per_page;
	$params=array();
	$params=array_merge($_GET,$_POST);
	unset($params['offset'],$params['results'],$params['PHPSESSID']);
	$query="SELECT 1 FROM $from WHERE $where";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$totalrows=mysql_num_rows($res);
	@$total_pages=ceil($totalrows/$results); // error if empty

$myreturn.="<div class=\"paginationlinks\"><div class=\"pagination\"><ul>";


if($offset >= $results){
	
	$prev = $offset - $results;
	$myreturn.="<li class=\"nextpage\"><a href=\"$PHP_SELF?offset=0&results=$results&".array2qs($params,array('PHPSESSID'))."\">&#171;</a></li>";
	
	$myreturn.="<li class=\"nextpage\"><a href=\"$PHP_SELF?offset=".$prev."&results=$results&".array2qs($params,array('PHPSESSID'))."\">Prev</a></li>";
	
	
	}
	$dotsbefore=false;
	$dotsafter=false;
	for ($i=1;$i<=$total_pages;$i++) {
	
		if (((($i-1)*$results)<=$offset) && ($offset<$i*$results)) {
		if ($total_pages > 1){
		
			$myreturn.="<li class=\"currentpage\">$i</li>";
		}	
		} elseif (($i-1+$radius)*$results<$offset) {
			if (!$dotsbefore) {
				//$myreturn.="";
				$dotsbefore=true;	
			}
		} elseif (($i-1-$radius)*$results>$offset) {
			if (!$dotsafter) {
				//$myreturn.="";
				$dotsafter=true;
			}
		} else {
			
			$myreturn.="<li><a href=\"$PHP_SELF?offset=".(($i-1)*$results)."&results=$results&".array2qs($params,array('PHPSESSID'))."\">$i</a></li>";
			
			
		}
	}	
	if ($total_pages!=0){
	

		if($offset == 0){
		$next = $results;
		if ($total_pages > 1){
			$myreturn.="<li class=\"nextpage\"><a href=\"$PHP_SELF?offset=".$next."&results=$results&".array2qs($params,array('PHPSESSID'))."\">Next</a></li>";
$myreturn.="<li class=\"nextpage\"><a href=\"$PHP_SELF?offset=".(($total_pages-1)*$results)."&results=$results&".array2qs($params,array('PHPSESSID'))."\">&#187;</a></li>";
}
		}elseif ($offset == (($total_pages-1)*$results)){
		$next='';
		$nexttext = '';
		}else{
		$next=$offset+$results;
		
		
		
		
		$myreturn.="<li class=\"nextpage\"><a href=\"$PHP_SELF?offset=".$next."&results=$results&".array2qs($params,array('PHPSESSID'))."\">Next</a></li>";
$myreturn.="<li class=\"nextpage\"><a href=\"$PHP_SELF?offset=".(($total_pages-1)*$results)."&results=$results&".array2qs($params,array('PHPSESSID'))."\">&#187;</a></li>";


		}
		
	}
	
		

	
	$myreturn.="</ul>";
	$myreturn.="</div>";
	$myreturn.="</div>";	
	return $myreturn;

}

function upload_file($destdir,$actual_field_name,$desired_filename='',$required=false) {
	$error=false;
	$filename="";
	if (isset($_FILES[$actual_field_name]['tmp_name']) && is_uploaded_file($_FILES[$actual_field_name]['tmp_name'])) {
		$filename=addslashes_mq($_FILES[$actual_field_name]['name']);
		$ext=strtolower(substr(strrchr($_FILES[$actual_field_name]['name'],"."),1));
		if ($_FILES[$actual_field_name]['size']==0) {
			$error=true;
			$message="Picture upload error";
		} else {
			if (!empty($desired_filename)) {
				$filename="$desired_filename.$ext";
			}
			if (!move_uploaded_file($_FILES[$actual_field_name]['tmp_name'],$destdir.'/'.$filename)) {
				$error=true;
				$message="Cannot move picture to the destination directory.";
				$filename='';
			} else {
				@chmod($destdir.'/'.$filename,0644);
			}
		}
	} elseif ($required) {
		$error=true;
	}
	if ($error) {
		$myreturn=false;
		trigger_error($message,E_USER_ERROR);
	} else {
		$myreturn=$filename;
	}
	return $myreturn;
}
function limit_text($text, $limit) {
   // $text = strip_tags($text);
      $words = str_word_count($text, 2);
      $pos = array_keys($words);
      if (count($words) > $limit) {
          $text = substr($text, 0, $pos[$limit]) . ' ...';
      }
    return $text;
}

function remove_underscore($bye){  // sooon to be renamed to remove_dash
$myreturn = str_replace("-", " ", trim($bye));
return $myreturn;
}
function add_underscore($hello){// sooon to be renamed to add_dash
$myreturn = str_replace(" ", "-", trim($hello));
return $myreturn;
} 

function remove_underscore2($bye){  // sooon to be renamed to remove_dash
$myreturn = str_replace("_", " ", trim($bye));
return $myreturn;
}
function add_underscore2($hello){// sooon to be renamed to add_dash
$myreturn = str_replace(" ", "_", trim($hello));
return $myreturn;
} 

function create_pager2($from,$where,$offset,$results) {
	mt_srand(make_seed());
	$radius=5;
	global $PHP_SELF;
	global $accepted_results_per_page;
	$params=array();
	$params=array_merge($_GET,$_POST);
	unset($params['offset'],$params['results'],$params['PHPSESSID']);
	$myrand=mt_rand(1000,2000);
	$myreturn="<form id=\"pagerform$myrand\" name=\"pagerform$myrand\" action=\"$PHP_SELF\" method=\"get\">\n";
	$myreturn.="<table>\n";
	$myreturn.="<tr>\n";
	$myreturn.="\t<td>\n";
	$query="SELECT 1 FROM $from WHERE $where";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$totalrows=mysql_num_rows($res);
	$total_pages=ceil($totalrows/$results);
	$myreturn.="\t\t<a href=\"$PHP_SELF?offset=0&results=$results&".array2qs($params,array('PHPSESSID'))."\"><b>&lt;&lt;</b></a>&nbsp;&nbsp;";
	$dotsbefore=false;
	$dotsafter=false;
	for ($i=1;$i<=$total_pages;$i++) {
		if (((($i-1)*$results)<=$offset) && ($offset<$i*$results)) {
			$myreturn.="<b>[</b>$i<b>]</b>&nbsp;&nbsp;";
		} elseif (($i-1+$radius)*$results<$offset) {
			if (!$dotsbefore) {
				$myreturn.="";
				$dotsbefore=true;
			}
		} elseif (($i-1-$radius)*$results>$offset) {
			if (!$dotsafter) {
				$myreturn.="";
				$dotsafter=true;
			}
		} else {
			$myreturn.="<a href=\"$PHP_SELF?offset=".(($i-1)*$results)."&results=$results&".array2qs($params,array('PHPSESSID'))."\">$i</a>&nbsp;&nbsp;";
		}
	}
	$myreturn.="<a href=\"$PHP_SELF?offset=".(($total_pages-1)*$results)."&results=$results&".array2qs($params,array('PHPSESSID'))."\"><b>&gt;&gt;</b></a>&nbsp;&nbsp;\n";
	$myreturn.="\t</td>\n";
	$myreturn.="\t<td>\n";
	$myreturn.="\t\t<input type=\"hidden\" name=\"offset\" value=\"$offset\" />\n";
	while (list($k,$v)=each($params)) {
		if (is_array($v)) {
			while (list($subk,$subv)=each($v)) {
				$myreturn.="\t\t<input type=\"hidden\" name=\"${k}[$subk]\" value=\"$subv\" />\n";
			}
		} else {
			$myreturn.="\t\t<input type=\"hidden\" name=\"$k\" value=\"$v\" />\n";
		}
	}
	$myreturn.="\t\t<select name=\"results\" onchange=\"document.pagerform$myrand.submit()\">\n";
	$myreturn.=vector2options($accepted_results_per_page,$results);
	$myreturn.="\t\t</select>\n";
	$myreturn.="\t</td>\n";
	$myreturn.="</tr>\n";
	$myreturn.="</table>\n";
	$myreturn.="</form>\n";
	return $myreturn;
}
function removeEvilAttributes($tagSource){
$stripAttrib = "' (class¦javascript:¦onclick¦ondblclick¦onmousedown¦onmouseup¦onmouseover¦onmousemove¦onmouseout¦onkeypress¦onkeydown¦onkeyup¦oncontextmenu)=\"(.*?)\"'i";
$tagSource = stripslashes($tagSource);
$tagSource = preg_replace($stripAttrib, '', $tagSource);
return $tagSource;
}

function removeEvilTags($source) {
$allowedTags = '<img><h1><h2><h3><h4><h5><h6><br><b><p><u><i><a><ol><ul><li><pre><hr><blockquote><table><tr><td><th><span><div><strong><tbody><sup><font><strong>';
$source = strip_tags($source, $allowedTags);
return preg_replace('/<(.*?)>/ie', "'<'.removeEvilAttributes('\\1').'>'", $source);
}

function reformatDate($datetime) {
// put date in universal format, no seconds
list($year, $month, $day, $hour, $min, $sec) = split( '[: -]',
$datetime);
return "$year-$month-$day at $hour:$min";
}

function mysqlDatetimeToUnixTimestamp($datetime){
        $val = explode(" ",$datetime);
        $date = explode("-",$val[0]);
        $time = explode(":",$val[1]);
        return mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]);
    }
function dateDiff($dt1, $dt2, $split='yw') {
    $date1 = (strtotime($dt1) != -1) ? strtotime($dt1) : $dt1;
    $date2 = (strtotime($dt2) != -1) ? strtotime($dt2) : $dt2;
    $dtDiff = $date1 - $date2;
    $totalDays = intval($dtDiff/(24*60*60));
    $totalSecs = $dtDiff-($totalDays*24*60*60);
    $dif['h'] = $h = intval($totalSecs/(60*60));
    $dif['m'] = $m = intval(($totalSecs-($h*60*60))/60);
    $dif['s'] = $totalSecs-($h*60*60)-($m*60);
    // set up array as necessary
    switch($split) {
    case 'yw': # split years-weeks-days
      $dif['y'] = $y = intval($totalDays/365);
      $dif['w'] = $w = intval(($totalDays-($y*365))/7);
      $dif['d'] = $totalDays-($y*365)-($w*7);
      break;
    case 'y': # split years-days
      $dif['y'] = $y = intval($totalDays/365);
      $dif['d'] = $totalDays-($y*365);
      break;
    case 'w': # split weeks-days
      $dif['w'] = $w = intval($totalDays/7);
      $dif['d'] = $totalDays-($w*7);
      break;
    case 'd': # don't split -- total days
      $dif['d'] = $totalDays;
      break;
    default:
      die("Error in dateDiff(). Unrecognized \$split parameter. Valid values are 'yw', 'y', 'w', 'd'. Default is 'yw'.");
    }
    return $dif;
  } // ratings start here doofball
  
  



function clean_stringforurl($string) // cleans the urls for the video
    {
        // Define the maximum number of characters allowed as part of the URL
        
        $currentMaximumURLLength = 101;
        
        $string = strtolower($string);
        
        // Any non valid characters will be treated as _, also remove duplicate _
        
        $string = preg_replace('/[^a-z0-9_]/i', '_', $string);
        $string = preg_replace('/_[_]*/i', '_', $string);
        
        // Cut at a specified length
        
        if (strlen($string) > $currentMaximumURLLength)
        {
            $string = substr($string, 0, $currentMaximumURLLength);
        }
        
        // Remove beggining and ending signs
        
        $string = preg_replace('/_$/i', '', $string);
        $string = preg_replace('/^_/i', '', $string);
        
        return $string;
    }
function drawChart($chartData){
   global $tableSize;
   $maxValue = 0;

   // First get the max value from the array
   foreach ($chartData as $item) {
      if ($item['value'] > $maxValue) $maxValue = $item['value'];
   }

   // Now set the theoretical maximum value depending on the maxValue
   $maxBar = 1;
   while ($maxBar < $maxValue) $maxBar = $maxBar * 10;

   // Calculate 1px value as the table is 300px
   $pxValue = ceil($maxBar/$tableSize);

   // Now display the table with bars
   $myreturn = '<table><tr><th width="166" align=left>Title</th><th colspan="2" align = left>Value</th></tr>';
   foreach ($chartData as $item) {
      $width = ceil($item['value']/$pxValue);
   	$myreturn .= '<tr><td class="ztd" width="100">'.$item['title'].'</td>';
   	 	$myreturn .= '<td class="ztd" width="166">
   	     <img src="../templates/admin/images/barbg.gif" alt="'.$item['title'].'" width="'.$width.'" height="15" /></td>';
   	 	$myreturn .= '<td class="ztd">'.$item['value'].'</td></tr>';
   }
   	$myreturn .= '</table>';
return $myreturn;
}
function clean_stringfor_title($string,$size='30') // clean title
    {
        // Define the maximum number of characters allowed as part of the URL
        
        $currentMaximumURLLength = $size;
        
   	  //   $string = strtolower($string);
        
        // Any non valid characters will be treated as _, also remove duplicate _
        
       // $string = preg_replace('/[^a-z0-9_]/i', ' ', $string);
        $string = preg_replace('/_[_]*/i', ' ', $string);
        
        // Cut at a specified length
        
        if (strlen($string) > $currentMaximumURLLength)
        {
            $string = substr($string, 0, $currentMaximumURLLength);
        }
        
        // Remove beggining and ending signs
        
        $string = preg_replace('/_$/i', ' ', $string);
        $string = preg_replace('/^_/i', ' ', $string);
        if(empty($string) && !isset($string)){
	
	return 'Untitled';
	}else{
        return $string;
    }
}
function make_keywords($text, $limit = 15) {
      $keywords = '';
      $num = 0;
      $text = preg_replace(array("`[[:punct:]]+`", "`[\s]+`"), array(" ", " "), strip_tags($text) );
      $text = explode(" ", $text);
      // We take the most used words first
      $text = array_count_values($text);
      foreach ($text as $word => $count) {
         if ( strlen($word) >= 4 ) {
            $keywords .= ($keywords == '') ? $word : ',' . $word;
            $num++;
            if ( $limit > 0 && $num >= $limit ) {
               break;
            }
         }   
      }
      return $keywords;
  }

function nicetime($date,$unixdate=0)
{
    if(empty($date)) {
        return "No date provided";
    }
global $lang;
$periods = array(    $lang['PERIODS_SECOND'], $lang['PERIODS_MINUTE'] , $lang['PERIODS_HOUR'], $lang['PERIODS_DAY'], $lang['PERIODS_WEEK'], $lang['PERIODS_MONTH'], $lang['PERIODS_YEAR'] , $lang['PERIODS_DECADE']);
    $lengths         = array("60","60","24","7","4.35","12","10");
   
    $now             = time();
    if($unixdate==1){
    $unix_date = $date;
    }else{
    $unix_date = strtotime($date);
   
    }
   
       // check validity of date
    if(empty($unix_date)) {   
        return "Bad date";
    }

    // is it future date or past date
    if($now > $unix_date) {   
        $difference     = $now - $unix_date;
        $tense        =  $lang['PERIODS_AGO']; 	   
    } else {
        $difference     = $unix_date - $now;
        $tense         = $lang['PERIODS_FROM_NOW']; 	
	
	}
   
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
   
    $difference = round($difference);
 
    if(($difference != 1)&&($_SESSION['lang']=='en')) {
        $periods[$j].=  $lang['PERIODS_S']; ;
    }
   
    return "$difference $periods[$j] {$tense}";
}

function get_my_template($set=1) {
	 if ($set==1){
	if (isset($_SESSION['my_template']) && !empty($_SESSION['my_template'])) {
		$myreturn=$_SESSION['my_template'];
	} elseif (defined('_DEFAULT_TEMPLATE_')) {
		$myreturn=_DEFAULT_TEMPLATE_;
	} else {
		$myreturn="default";
	}
	 }else{
		$myreturn=$set;
	 }
	return $myreturn;
}
function choose_skin_bit() {
	global $relative_path;
	$temptoggle = _TEMP_SELECT_;
	$myreturn='';
	$myskins=array();
	if ($dh=opendir(_TPLPATH_)) {
		while (($file=readdir($dh))!==false ) {
			if (is_dir(_TPLPATH_."/$file") && $file!='.' && $file!='..' && $file!='admin' && $file!='emails') {
				$myskins[$file]=$file;
			}
		}
		closedir($dh);
	}
	if (count($myskins)>1) {
		$tpl=new phemplate(_TPLPATH_.get_my_template().'/','remove_nonjs');
		$tpl->set_file('content','bits/choose_skin.html');
		$tpl->set_var('relative_path',$relative_path);
		$tpl->set_var('skin_options',vector2options($myskins,get_my_template()));
		$myreturn=$tpl->process('','content',TPL_FINISH);
	}if ($temptoggle == 1){
	
	return $myreturn;
	
	}
}
function language_selector(){
	$html='';
	if(_MULTI_LANG_SELECTOR_!=0){
	global $lang;
	$baseurl=_BASEURL_;
	
	$html="<div id=\"country_selector\"><strong>".$lang['LANG_SELECTOR_TXT'].":</strong><br />
	<a href=\"$baseurl/setlang.php?lang=en\"><img src=\"$baseurl/images/en.png\" border=\"0\" /></a>
<a href=\"$baseurl/setlang.php?lang=sv\"><img src=\"$baseurl/images/sv.png\" border=\"0\" /></a>
<a href=\"$baseurl/setlang.php?lang=es\"><img src=\"$baseurl/images/es.png\" border=\"0\" /></a>
</div>
";}
	
return $html;	
}


function toAscii($str) {
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", '-', $clean);

	return $clean;
}
function sanitize($data)  
  {  
   // remove whitespaces (not a must though)  
   $data = trim($data);   
   
 // apply stripslashes if magic_quotes_gpc is enabled  
 if(get_magic_quotes_gpc())  
 {  
 $data = stripslashes($data);  
 }  
   
 // a mySQL connection is required before using this function  
 $data = mysql_real_escape_string($data);  
   
return $data;  
 }

function cleanString($string, $separator = '-'){
	$accents = array('Š' => 'S', 'š' => 's', 'Ð' => 'Dj','Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss','à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'ƒ' => 'f');
	$string = strtr($string, $accents);
	$string = strtolower($string);
	$string = preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
	$string = preg_replace('{ +}', ' ', $string);
	$string = trim($string);
	$string = str_replace(' ', $separator, $string);
 	
	return $string;
}
function base_path() {  // dynamic base path with folder.

$folder = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
$base = "<base href=\"$folder/\" />";

return $base;
}


function mysql_status($A=null) {
    $status = explode('  ', mysql_stat());
    foreach($status as $k=>$v) { $v=explode(':',$v,2); $status[$v[0]]=$v[1]; }
    return (isset($status[$A])?$status[$A]:$status);
}
function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}
function curPageURL() {
 $pageURL = 'http';
 //if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
function parse_to_date_and_string($parse) {
$exclude = array('i', 'at');
$parse_array = explode(" ", $parse);
$date = array();
$data['text'] = array();
$word_count = count($parse_array);
for($i = 0; $i < $word_count; $i++) {
if (strtotime($parse_array[$i]) > 0 && !in_array($parse_array[$i], $exclude)) {
$date[] = $parse_array[$i];
} else {
$data['text'][] = $parse_array[$i];
}
}
$data['date'] = strtotime(implode(" ", $date));
return $data;
}

function lat_lon($id,$condition){
	// 1 = country   2 = region  3 = city
switch ($condition) 	{
    case 1:
	 $somewhere = " geo_countries WHERE con_id='".$id."'";
       break;
    case 2:
	 $somewhere = " geo_states WHERE sta_id='".$id."'";
	   break;
    case 3:
      $somewhere = " geo_cities WHERE cty_id='".$id."'";
        break;
	
	}
	$query="SELECT latitude,longitude FROM $somewhere";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$latlon = mysql_fetch_array($res);
	
	return $latlon;
}

function member_lat_lon($memberID){
	//  memberID
  
 

	$query="SELECT  lat,lon FROM users where user_id = $memberID";
	if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
	$latlon = mysql_fetch_array($res);
	
	return $latlon;
}


function generatePassword ($length = 8){
 // start with a blank password
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
  
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
      $length = $maxlength;
    }
	
    // set up a counter for how many characters are in the password so far
    $i = 0; 
    
    // add random characters to $password until $length is reached
    while ($i < $length) { 
	  // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);      
      // have we already used this character in $password?
      if (!strstr($password, $char)) { 
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }

    }
    // donedid!
    return $password;

  }
 
function logout_button(){
 global $lang;
 $basep = _BASEURL_;

 if (_FBCONNECT_ ==1 ){
	
 
  if (isset($_SESSION['fbloggedin'])){
  $myreturn = "<a id=\"logout\" href=\"$basep/processors/logout.php\" onclick=\"FB.logout(function(response) { window.location = '$basep/processors/logout.php' }); return false;\" title=\"$lang[MENU_LOGOUT]\">$lang[MENU_LOGOUT]</a>";
  }else{
  $myreturn = "<a href=\"$basep/processors/logout.php\">$lang[MENU_LOGOUT]</a>";
  }
 }else{
	
	 $myreturn = "<a href=\"$basep/processors/logout.php\">$lang[MENU_LOGOUT]</a>";
	
 }
 return $myreturn;
}
function facebook_login_button(){
  if (_FBCONNECT_ ==1){
  global $lang;
  
  
 $myreturn = "<div class=\"fb-login-button\" scope=\"email,user_birthday,user_checkins\">$lang[FORM_FACEBOOK_LOGIN]</div>";
   }else{
   $myreturn = '';

}
return $myreturn;
}

function facebook_JS_header(){
$appID = _FB_APPID_;	
$basep = _BASEURL_;

if (_FBCONNECT_ ==1){
$myreturn = " <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '$appID',
          status     : true, 
          cookie     : true,
          xfbml      : true,
          oauth      : true,
        });

        FB.Event.subscribe('auth.login', function(response) {
          window.location=\"$basep/fbconnect.php\";
        });
      };

      (function(d){
         var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
         js = d.createElement('script'); js.id = id; js.async = true;
         js.src = \"//connect.facebook.net/en_US/all.js\";
         d.getElementsByTagName('head')[0].appendChild(js);
       }(document));
    </script>";
    
}else{
	$myreturn = '';
}
return $myreturn;
	
}

?>
