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
$optiont='';
$editor='';
global $relative_path;


$tpl = new phemplate(_TPLPATH_,'remove_nonjs');

$filedir = _TPLPATH_.get_my_template();

$valid_ext[1] = "css";
$valid_ext[2] = "html";

if ($_SERVER['REQUEST_METHOD'] != 'POST'){

	if (is_readable($filedir)) {
function directoryToArray($directory, $recursive) {
	$me = basename($_SERVER['PHP_SELF']);	
	$array_items = array();
		if ($handle = opendir($directory)) {
	  	while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && $file != $me && substr($file,0,1) != '.') {
	        if (is_dir($directory. "/" . $file)) {
						if($recursive) {
							$array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
							 }						 
					}
					else {
	            $file = $directory . "/" . $file;
	            $array_items[] = preg_replace("/\/\//si", "/", $file);
					}
	      }
	    }
	    closedir($handle);
			asort($array_items);
	  }
	  return $array_items;
	}
	


$filelist = directoryToArray($filedir, true);

$optiont .= "<form action=\"\" method=\"post\">\n";
$optiont .="<select name=\"the_file\">\n";
	
	foreach ($filelist as $file) {
		$ext = substr(strrchr($file, '.'), 1);
			if (in_array($ext,$valid_ext)) {
			$optiont .= "<option value=\"$file\">$file</option>\n";
		
			}			
	}

	$optiont .="</select>\n";
	$optiont .="<input type=\"submit\" name=\"open\" value=\"Open\" class=\"log2\" />\n	";
	$optiont .="</form>";
		
	$tpl->set_var('option',$optiont);	
}
	else {	
	$message = "<h3>ERROR!</h3><p>Could not open directory! <br />Check your CHMOD settings in case it is a file/folder permissions problem.   <a href=\"\"><h1>GO BACK</h1></a></p>";
	}

}
else if (isset($_POST['open'])){
		if (is_writable($_POST["the_file"])) {


$zfile = $_POST["the_file"];
$editor .= "<h3>File Opened</h3>";
$editor .= "<form action=\"\" method=\"post\">";	
$editor .= "<input type=\"hidden\" name=\"the_file2\" value=\"$zfile\" />";
$editor .= "<p><strong>Currently open: ". $zfile ."</strong></p>";
$editor .= "<textarea rows=\"30\" id=\"editor\" name=\"updatedfile\">";
	
$file2open = fopen($_POST["the_file"], "r");
	
$current_data = @fread($file2open, filesize($_POST["the_file"]));
$current_data = preg_replace("#</textarea>#", "<END-TA-DO-NOT-EDIT>", $current_data);
$editor .= htmlspecialchars($current_data);
	
		fclose($file2open);
	
$editor .="</textarea>	<br/>";
$editor .="	<input type=\"button\" value=\"Cancel\" onClick=\"javascript:location.href='edit_templates.php'\" />";
$editor .="	<input type=\"reset\" value=\"Reset\" class=\"log2\">";
$editor .="	<input type=\"submit\" name=\"save\" value=\"Save Changes\" class=\"log2\" />	</form>";
		
	$tpl->set_var('editorbox',$editor);
		
		
		}
		else {
		$message= "<h3>ERROR!</h3><p>Could not open file! <br /> Check your CHMOD settings in case it is a file/folder permissions problem.   <a href=\"\"><h1>GO BACK</h1></a></p>";
		}
	}
	
	///////////////////////////////////////////////////////
	//If save button has been pushed....
	//////////////////////////////////////////////////////
	else if (isset($_POST['save'])){
		if (is_writable($_POST["the_file2"])) {

		$file2ed = fopen($_POST["the_file2"], "w+");
		
		$data_to_save = $_POST["updatedfile"];
		$data_to_save = preg_replace("#<END-TA-DO-NOT-EDIT>#", "</textarea>", $data_to_save);
		$data_to_save = stripslashes($data_to_save);

			if (fwrite($file2ed,$data_to_save)) { 
				$message = "<h3>File Saved</h3><p><a href=\"\">Click here to go back.</a></p>";	
				fclose($file2ed);
			}
			else {	
				$message ="<h3>ERROR!</h3><p>File NOT saved! <br /> Check your CHMOD settings in case it is a file/folder permissions problem. <br />  <a href=\"\"><h1>GO BACK</h1></a></p>";
				fclose($file2ed);
			}
		}
		else {
			$message = "<h3>ERROR!</h3><p>File NOT saved! <br />Check your CHMOD settings in case it is a file/folder permissions problem.  <a href=\"\"><h1>GO BACK</h1></a></p>";
		}
	}
	





$tpl->set_file('content','admin/edit_templates.html');
$content=$tpl->process('out','content');

$tpl->set_file('frame','admin/frame.html');
$tpl->set_var('title','Change user password');
$tpl->set_var('message',$message);


$tpl->set_var('content',$content);
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('relative_path',$relative_path);

print $tpl->process('out','frame',0,1);
?>