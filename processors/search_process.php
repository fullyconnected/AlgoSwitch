<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");


db_connect();


$topass=array();
if ($_SERVER['REQUEST_METHOD']=='GET') {
	$from="users a";
	$where="1";
	$searchtype=addslashes_mq($_GET['searchtype']);
	$error=false;


	if ($searchtype=='name') {
		if (isset($_GET['screenname']) & !empty($_GET['screenname'])) {
			$name=addslashes_mq($_GET['screenname']);
		} else {
			$error=true;
       
			$message="No search string specified<br>";
			
			$topass['message']=$message;
		
				redirect2page("search_results.php",$topass);
		}
        if (is_numeric($name)) {
        $name = get_name($name);
        }
        
        
		
		if (strstr($name,'?') || strstr($name,'\'') || strstr($name,'"')) {
			$error=true;
			$message='Invalid characters in search string. Please use only letters, digits and the percent char (%).';
			$topass['message']=$message;
								redirect2page("search_results.php",$topass);
		}
		if (isset($name) && !empty($name) &&!$error) {
			$where.=" AND a.profilelink LIKE '%$name%'";
		}
	} elseif ($searchtype=='quicker') {
			$access_level=_GUESTLEVEL_;
//			check_login_member();

			$gender=addslashes_mq($_GET['gender']);
			if (($gender!=_ANY_) && ($gender!=_NDISCLOSED_)) {
 				$where.=" AND (a.gender='$gender' OR a.gender='"._ANY_."' OR a.gender='"._NDISCLOSED_."')";
			}
			$agemin=addslashes_mq($_GET['agemin']);
			$agemax=addslashes_mq($_GET['agemax']);
			$where.=" AND a.birthdate>=(now()-INTERVAL $agemax YEAR) AND a.birthdate<=(now()-INTERVAL $agemin YEAR)";
			$ethnicity=addslashes_mq($_GET['ethnicity']);
			if (($ethnicity!=_ANY_) && ($ethnicity!=_NDISCLOSED_)) {

			$where.=" AND a.ethnic!='' AND ((MOD(a.ethnic>>$ethnicity,2)=1) OR a.ethnic='"._NDISCLOSED_."')";
			}$where.=" AND a.profession='1'";

		}elseif ($searchtype=='state') {
			$access_level=_GUESTLEVEL_;
//			check_login_member();

			$state=addslashes_mq($_GET['whichstate']);
			$where.=" AND a.us_state='$state' AND a.profession='1'";

		}elseif ($searchtype=='advanced') {
	$access_level=$access_matrix['advancedsearch'][0];
	check_login_member();
	global $relative_path;
	$topass['$backto']="<a href=\"${relative_path}advancedsearch.php\">Advanced Search</a>";

		$gender=addslashes_mq($_GET['gender']);
		if (($gender!=_ANY_) && ($gender!=_NDISCLOSED_)) {
			$where.=" AND a.gender='$gender'";
		}

		$country=addslashes_mq($_GET['country']);
				if (($country!=_ANY_) && ($country!=_NDISCLOSED_)) {
					$where.=" AND a.country='$country'";
				}


					$us_state=addslashes_mq($_GET['us_state']);
					if (($country==1) && ($us_state!=_ANY_) && ($us_state!=_NDISCLOSED_)) {
						$where.=" AND a.us_state='$us_state'";
					}

					if (isset($_GET['city']) && !empty($_GET['city'])) {
						$city=addslashes_mq($_GET['city'],true);
						$where.=" AND a.city='$city'";
					}

		$agemin=addslashes_mq($_GET['agemin']);
		$agemax=addslashes_mq($_GET['agemax']);
		$where.=" AND a.birthdate>=(now()-INTERVAL $agemax YEAR) AND a.birthdate<=(now()-INTERVAL $agemin YEAR)";

			$heightmin=addslashes_mq($_GET['heightmin']);
			$heightmax=addslashes_mq($_GET['heightmax']);
			if ($heightmin!=_ANY_) {
				$where.=" AND a.height>='$heightmin' AND a.height<='$heightmax'";
			}

			$weightmin=addslashes_mq($_GET['weightmin']);
			$weightmax=addslashes_mq($_GET['weightmax']);
			if ($weightmin!=_ANY_) {
				$where.=" AND a.weight>='$weightmin' AND a.weight<='$weightmax'";
			}


			$ethnic=addslashes_mq($_GET['ethnicity']);
			if (($ethnic!=_ANY_) && ($ethnic!=_NDISCLOSED_)) {
			$where.=" AND a.ethnic='$ethnic'";
			}

			$profession=addslashes_mq($_GET['profession']);
						if (($profession!=_ANY_) && ($profession!=_NDISCLOSED_)) {
						$where.=" AND a.profession='$profession'";
			}

			$body=addslashes_mq($_GET['body']);
			if (($body!=_ANY_) && ($body!=_NDISCLOSED_)) {
			$where.=" AND a.bodytype='$body'";
			}


			$haircolor=addslashes_mq($_GET['haircolor']);
			if (($haircolor!=_ANY_) && ($haircolor!=_NDISCLOSED_)) {
			$where.=" AND a.haircolor='$haircolor'";
			}

			$hairlength=addslashes_mq($_GET['hairlength']);
			if (($hairlength!=_ANY_) && ($hairlength!=_NDISCLOSED_)) {
			$where.=" AND a.hairlength='$hairlength'";
			}

			$hairtype=addslashes_mq($_GET['hairtype']);
			if (($hairtype!=_ANY_) && ($hairtype!=_NDISCLOSED_)) {
			$where.=" AND a.hairtype='$hairtype'";
			}

			$hairpiece=addslashes_mq($_GET['hairpiece']);
			if (($hairpiece!=_ANY_) && ($hairpiece!=_NDISCLOSED_)) {
			$where.=" AND a.hairtype='$hairpiece'";
			}

			$eyecolor=addslashes_mq($_GET['eyecolor']);
			if (($eyecolor!=_ANY_) && ($eyecolor!=_NDISCLOSED_)) {
			$where.=" AND a.eyecolor='$eyecolor'";
			}

			$eyeshape=addslashes_mq($_GET['eyeshape']);
			if (($eyeshape!=_ANY_) && ($eyeshape!=_NDISCLOSED_)) {
			$where.=" AND a.eyeshape='$eyeshape'";
			}

			$eyebrows=addslashes_mq($_GET['eyebrows']);
			if (($eyebrows!=_ANY_) && ($eyebrows!=_NDISCLOSED_)) {
			$where.=" AND a.eyebrows='$eyebrows'";
			}


			$eyelashes=addslashes_mq($_GET['eyelashes']);
			if (($eyelashes!=_ANY_) && ($eyelashes!=_NDISCLOSED_)) {
			$where.=" AND a.eyelashes='$eyelashes'";
			}

			$eyewear=addslashes_mq($_GET['eyewear']);
			if (($eyewear!=_ANY_) && ($eyewear!=_NDISCLOSED_)) {
			$where.=" AND a.eyewear='$eyewear'";
			}

			$faceshape=addslashes_mq($_GET['faceshape']);
			if (($faceshape!=_ANY_) && ($faceshape!=_NDISCLOSED_)) {
			$where.=" AND a.faceshape='$faceshape'";
			}

			$waist=addslashes_mq($_GET['waist']);
			if (($waist!=_ANY_) && ($waist!=_NDISCLOSED_)) {
			$where.=" AND a.waist='$waist'";
			}

			$chest=addslashes_mq($_GET['chest']);
			if (($chest!=_ANY_) && ($chest!=_NDISCLOSED_)) {
			$where.=" AND a.chest='$chest'";
			}

			$dress_shirt=addslashes_mq($_GET['dress_shirt']);
			if (($dress_shirt!=_ANY_) && ($dress_shirt!=_NDISCLOSED_)) {
			$where.=" AND a.dress_shirt='$dress_shirt'";
			}


			$hips_inseam=addslashes_mq($_GET['hips_inseam']);
			if (($hips_inseam!=_ANY_) && ($hips_inseam!=_NDISCLOSED_)) {
			$where.=" AND a.hips_inseam='$hips_inseam'";
			}

			$shoes=addslashes_mq($_GET['shoes']);
			if (($shoes!=_ANY_) && ($shoes!=_NDISCLOSED_)) {
			$where.=" AND a.shoes='$shoes'";
			}

		if (isset($_GET['have_photo']) && !empty($_GET['have_photo'])) {
	                $where.=" AND a.user_id=b.fk_user_id AND b.picture_number ='1'";
        	        $from.=",user_album2 b";
		}
	}
	$query="SELECT a.user_id,a.user,a.pass,a.firstname,a.lastname,a.profilelink,a.gender,(YEAR(NOW())-YEAR(a.birthdate)),DAYOFYEAR(a.birthdate),a.ethnic,a.country,a.us_state,a.city,a.zip,a.ADDr,a.phone1,a.phone2,a.my_diz,a.work_interest,a.hairlength,a.hairtype,a.haircolor,a.hairpiece,a.eyeshape,a.eyecolor,a.eyebrows,a.eyelashes,a.eyewear,a.faceshape,a.bodytype,a.waist,a.chest,a.hips_inseam,a.height,a.weight,a.shoes,a.dress_shirt,a.membership,a.email,a.profession FROM $from WHERE $where";
//die($query);
	if (!$error) {
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res)) {
			$foundmembers=array();
			$i=0;
			while ($rsrow=mysql_fetch_row($res)) {
			$query="SELECT picture_name FROM user_album2 WHERE fk_user_id=$rsrow[0] AND picture_number='1'";
						if (!($res2=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
						list($picture)=mysql_fetch_row($res2);

			list($user_id,$user,$pass,$firstname,$lastname,$name,$gender,$age,$zsign,$ethnic,$country,$us_state,$city,$zip,$ADDr,$phone1,$phone2,$my_diz,$work_interest,$hairlength,$hairtype,$haircolor,$hairpiece,$eyeshape,$eyecolor,$eyebrows,$eyelashes,$eyewear,$faceshape,$bodytype,$waist,$chest,$hips_inseam,$height,$weight,$shoes,$dress_shirt,$membership,$email,$profession)=$rsrow;


				if (empty($picture)) {
									$picture='no-pict.gif';
								}
								$foundmembers[$i]['user_id']=$user_id;
								$foundmembers[$i]['name']=$name;
								$foundmembers[$i]['picture']=$picture;
								$foundmembers[$i]['age']=$age;
								$foundmembers[$i]['backto']=$backto;
								$foundmembers[$i]['gender']=$accepted_genders[$gender];
								$allcountry="";
								if (!empty($city)) {
									$allcountry.=$city." / ";
								}
								if (($country==1) && ($state!=_ANY_) && ($state!=_NDISCLOSED_)) {
									$allcountry.=$accepted_states[$state]." / ";
								}
								$allcountry.=$accepted_countries[$country];
								$foundmembers[$i]['country']=$allcountry;
								if (is_online($user_id)) {
									$foundmembers[$i]['online']="<img src=\"images/online.gif\" border=\"0\" alt=\"Online now!\" />";
								} else {
									$foundmembers[$i]['online']="<img src=\"images/notonline.gif\" border=\"0\" alt=\"Not online\" />";
								}
								if (defined('_LASTACTIVITY_ADDON_')) {
									$foundmembers[$i]['last_activity']="<i>Last activity:</i> ".time_since_last_activity($user_id);
								} else {
									$foundmembers[$i]['last_activity']='';
								}
								$i++;
							}
			$_SESSION['foundmembers']=$foundmembers;

					redirect2page("search_results.php",$topass);
		} else {
			$topass['message']="No members were found matching your criteria";
if ($searchtype!='advanced') {
				redirect2page("search_results.php",$topass);
			} else {
				redirect2page("advancedsearch.php",$topass);
			}
		}
	} else {

		$topass['message']=$message;
		redirect2page("search_results.php",$topass);
	}
}

redirect2page("search_results.php",$topass);
?>