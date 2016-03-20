<?php
session_start();
require("../includes/vars.inc.php");
require("../includes/functions.inc.php");
require("../includes/templates.inc.php");
require("../includes/apt_functions.inc.php");
$access_level=_ADMINLEVEL_;

db_connect();
check_login_member();
$mstatus = _STATUSNOTACTIVE_;
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$from="users a";
	$where="1";
	$profilepage="";
	$age="";
	$gender="";
	$user="";
	$age="";
	$profession="";
	$pass="";
	$email="";
	$country="";
	$name="";
	
	

	$searchtype=addslashes_mq($_POST['searchtype']);
	$error=false;
	if ($searchtype==1) {
		if (isset($_POST['user'])) {
			$user=addslashes_mq(add_underscore($_POST['user']));
		} else {
			$error=true;
			$message="No search string specified";
		}
		if (strlen($user)<3) {
			$error=true;
			$message='You are not allowed to search for names less than 3 chars long';
		}
		if (strstr($user,'?') || strstr($user,'\'') || strstr($user,'"')) {
			$error=true;
			$message='Invalid characters in search string. Please use only letters, digits and the percent char (%).';
		}
		if (isset($user) && !empty($user) &&!$error) {
			$where.=" AND a.user LIKE '%$user%'";
		}
	} elseif ($searchtype==2) {
		if (isset($_POST['name'])) {
			$name=addslashes_mq(add_underscore($_POST['name']));
		} else {
			$error=true;
			$message="No search string specified";
		}
		if (strlen($name)<3) {
			$error=true;
			$message='You are not allowed to search for names less than 3 chars long';
		}
		if (strstr($name,'?') || strstr($name,'\'') || strstr($name,'"')) {
			$error=true;
			$message='Invalid characters in search string. Please use only letters, digits and the percent char (%).';
		}
		if (isset($name) && !empty($name) &&!$error) {
			$where.=" AND a.profilelink LIKE '%$name%'";
		}
	} elseif ($searchtype==3) {
		$gender=addslashes_mq($_POST['gender']);
		if (($gender!=_ANY_) && ($gender!=_NDISCLOSED_)) {
			$where.=" AND a.gender='$gender'";
		}

		$country=addslashes_mq($_POST['country']);
				if (($country!=_ANY_) && ($country!=_NDISCLOSED_)) {
					$where.=" AND a.country='$country'";
				}


					$us_state=addslashes_mq($_POST['us_state']);
					if (($country==1) && ($us_state!=_ANY_) && ($us_state!=_NDISCLOSED_)) {
						$where.=" AND a.us_state='$us_state'";
					}

					if (isset($_POST['city']) && !empty($_POST['city'])) {
						$city=addslashes_mq($_POST['city'],true);
						$where.=" AND a.city='$city'";
					}

		$agemin=addslashes_mq($_POST['agemin']);
		$agemax=addslashes_mq($_POST['agemax']);
		$where.=" AND a.birthdate>=(now()-INTERVAL $agemax YEAR) AND a.birthdate<=(now()-INTERVAL $agemin YEAR)";

			$heightmin=addslashes_mq($_POST['heightmin']);
			$heightmax=addslashes_mq($_POST['heightmax']);
			if ($heightmin!=_ANY_) {
				$where.=" AND a.height>='$heightmin' AND a.height<='$heightmax'";
			}

			$weightmin=addslashes_mq($_POST['weightmin']);
			$weightmax=addslashes_mq($_POST['weightmax']);
			if ($weightmin!=_ANY_) {
				$where.=" AND a.weight>='$weightmin' AND a.weight<='$weightmax'";
			}


			$ethnic=addslashes_mq($_POST['ethnicity']);
			if (($ethnic!=_ANY_) && ($ethnic!=_NDISCLOSED_)) {
			$where.=" AND a.ethnic='$ethnic'";
			}

			$profession=addslashes_mq($_POST['profession']);
						if (($profession!=_ANY_) && ($profession!=_NDISCLOSED_)) {
						$where.=" AND a.profession='$profession'";
			}

			$body=addslashes_mq($_POST['body']);
			if (($body!=_ANY_) && ($body!=_NDISCLOSED_)) {
			$where.=" AND a.bodytype='$body'";
			}


			$haircolor=addslashes_mq($_POST['haircolor']);
			if (($haircolor!=_ANY_) && ($haircolor!=_NDISCLOSED_)) {
			$where.=" AND a.haircolor='$haircolor'";
			}

			$hairlength=addslashes_mq($_POST['hairlength']);
			if (($hairlength!=_ANY_) && ($hairlength!=_NDISCLOSED_)) {
			$where.=" AND a.hairlength='$hairlength'";
			}

			$hairtype=addslashes_mq($_POST['hairtype']);
			if (($hairtype!=_ANY_) && ($hairtype!=_NDISCLOSED_)) {
			$where.=" AND a.hairtype='$hairtype'";
			}

			$hairpiece=addslashes_mq($_POST['hairpiece']);
			if (($hairpiece!=_ANY_) && ($hairpiece!=_NDISCLOSED_)) {
			$where.=" AND a.hairtype='$hairpiece'";
			}

			$eyecolor=addslashes_mq($_POST['eyecolor']);
			if (($eyecolor!=_ANY_) && ($eyecolor!=_NDISCLOSED_)) {
			$where.=" AND a.eyecolor='$eyecolor'";
			}

			$eyeshape=addslashes_mq($_POST['eyeshape']);
			if (($eyeshape!=_ANY_) && ($eyeshape!=_NDISCLOSED_)) {
			$where.=" AND a.eyeshape='$eyeshape'";
			}

			$eyebrows=addslashes_mq($_POST['eyebrows']);
			if (($eyebrows!=_ANY_) && ($eyebrows!=_NDISCLOSED_)) {
			$where.=" AND a.eyebrows='$eyebrows'";
			}


			$eyelashes=addslashes_mq($_POST['eyelashes']);
			if (($eyelashes!=_ANY_) && ($eyelashes!=_NDISCLOSED_)) {
			$where.=" AND a.eyelashes='$eyelashes'";
			}

			$eyewear=addslashes_mq($_POST['eyewear']);
			if (($eyewear!=_ANY_) && ($eyewear!=_NDISCLOSED_)) {
			$where.=" AND a.eyewear='$eyewear'";
			}

			$faceshape=addslashes_mq($_POST['faceshape']);
			if (($faceshape!=_ANY_) && ($faceshape!=_NDISCLOSED_)) {
			$where.=" AND a.faceshape='$faceshape'";
			}

			$waist=addslashes_mq($_POST['waist']);
			if (($waist!=_ANY_) && ($waist!=_NDISCLOSED_)) {
			$where.=" AND a.waist='$waist'";
			}

			$chest=addslashes_mq($_POST['chest']);
			if (($chest!=_ANY_) && ($chest!=_NDISCLOSED_)) {
			$where.=" AND a.chest='$chest'";
			}

			$dress_shirt=addslashes_mq($_POST['dress_shirt']);
			if (($dress_shirt!=_ANY_) && ($dress_shirt!=_NDISCLOSED_)) {
			$where.=" AND a.dress_shirt='$dress_shirt'";
			}


			$hips_inseam=addslashes_mq($_POST['hips_inseam']);
			if (($hips_inseam!=_ANY_) && ($hips_inseam!=_NDISCLOSED_)) {
			$where.=" AND a.hips_inseam='$hips_inseam'";
			}

			$shoes=addslashes_mq($_POST['shoes']);
			if (($shoes!=_ANY_) && ($shoes!=_NDISCLOSED_)) {
			$where.=" AND a.shoes='$shoes'";
			}

		if (isset($_POST['have_photo']) && !empty($_POST['have_photo'])) {
	                $where.=" AND a.user_id=b.fk_user_id";
        	        $from.=",user_album2 b";
		}

	} elseif ($searchtype==4) {
		if (isset($_POST['user_id'])) {
			$user_id=addslashes_mq($_POST['user_id']);
		} else {
			$error=true;
			$message="No search string specified";
		}
		if (strstr($user_id,'%') || strstr($user_id,'?') || strstr($user_id,'\'') || strstr($user_id,'"')) {
			$error=true;
			$message='Invalid characters in search string. Please use only digits.';
		}
		if (isset($user_id) && !empty($user_id) &&!$error) {
			$where.=" AND a.user_id='$user_id'";
		}
	} elseif ($searchtype==5) {
		if (isset($_POST['gender'])) {
			$gender=addslashes_mq($_POST['gender']);
		} else {
			$error=true;
			$message="No gender specified in search";
		}
		if (isset($_POST['acc_level'])) {
			$acc_level=addslashes_mq($_POST['acc_level']);
		} else {
			$error=true;
			$message="No access level specified in search";
		}
		if (!$error) {
			if ($gender!=_ANY_) {
				$where.=" AND a.gender='$gender'";
			}
			if ($acc_level!=_ANY_) {
				$where.=" AND a.membership='$acc_level'";
			}
			if(($gender = _ANY_) && ($acc_level="_ANY_")){
			$where.=" AND a.gender!='' AND a.membership != '5'";
			}
		}
	} elseif ($searchtype==6) {
		if (isset($_POST['email'])) {
			$email=addslashes_mq($_POST['email']);
		} else {
			$error=true;
			$message="No email address specified in search";
		}
		if (!$error) {
			$where.=" AND a.email='$email'";
		}
	} elseif ($searchtype==7) {
		$where.=" AND a.status=$mstatus";
	}
	$query="SELECT a.user_id,a.user,a.pass,a.profilelink,a.gender,(YEAR(NOW())-YEAR(a.birthdate)),DAYOFYEAR(a.birthdate),a.ethnic,a.country,a.us_state,a.city,a.zip,a.addr,a.phone1,a.phone2,a.my_diz,a.work_interest,a.hairlength,a.hairtype,a.haircolor,a.hairpiece,a.eyeshape,a.eyecolor,a.eyebrows,a.eyelashes,a.eyewear,a.faceshape,a.bodytype,a.waist,a.chest,a.hips_inseam,a.height,a.weight,a.shoes,a.dress_shirt,a.membership,a.email,a.profession FROM $from WHERE $where";
//die($query);
	if (!$error) {
		if (!($res=mysql_query($query))) {general_error(mysql_error(),__LINE__,__FILE__);}
		if (mysql_num_rows($res)) {
			$foundmembers=array();
			$i=0;
			while ($rsrow=mysql_fetch_row($res)) {
				list($user_id,$user,$pass,$name,$gender,$age,$zsign,$ethnic,$country,$us_state,$city,$zip,$addr,$phone1,$phone2,$my_diz,$work_interest,$hairlength,$hairtype,$haircolor,$hairpiece,$eyeshape,$eyecolor,$eyebrows,$eyelashes,$eyewear,$faceshape,$bodytype,$waist,$chest,$hips_inseam,$height,$weight,$shoes,$dress_shirt,$membership,$email,$profession)=$rsrow;


				$wpro=get_profession2($user_id);
				if($wpro == 1){
				$profilepage="profile_view.php";}
				elseif($wpro == 2){
				$profilepage="album_view.php";}
				else {
				$profilepage="general_view.php";}
				$foundmembers[$i]['profilepage']=$profilepage;
				$foundmembers[$i]['user_id']=$user_id;
				$foundmembers[$i]['name']=$name;
				$foundmembers[$i]['gender']=$accepted_genders[$gender];
				$foundmembers[$i]['user']=$user;
				if(!isset($country) || empty($country)||($country == 0)){
				$foundmembers[$i]['country']="";
				}	
				else {
				$foundmembers[$i]['country']=getCountry($country);
				}
				if(!isset($profession) || empty($profession)||($profession == 0)){
				$foundmembers[$i]['profession']="";
				}
				else {
				$foundmembers[$i]['profession']=get_profile_type($profession);
				}
				$foundmembers[$i]['age']=$age;
				$foundmembers[$i]['pass']=$pass;
				$foundmembers[$i]['email']=$email;
				$foundmembers[$i]['myclass']=(($i%2) ? ("trimpar") : ("trpar"));
				$i++;
			}
			$_SESSION['foundmembers']=$foundmembers;
			redirect2page("admin/display_results.php",$topass);
		} else {
			$topass['message']="No members were found matching your criteria";
			redirect2page("admin/member_qsearch.php",$topass);
		}
	} else {
		$topass['message']=$message;
		redirect2page("admin/controlpanel.php",$topass);
	}
}
redirect2page("admin/member_qsearch.php",$topass);
?>
