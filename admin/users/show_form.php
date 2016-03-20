<?php
session_start();
require("../../includes/vars.inc.php");
require("../../includes/functions.inc.php");
require("../../includes/templates.inc.php");
require("../../includes/apt_functions.inc.php");
db_connect();
check_login_member();
$access_level=_ADMINLEVEL_;
$uid = intval($_REQUEST['uid']);

$gender = get_gender($uid);

$theprofession = get_profession($uid);
//echo $_REQUEST['index'];
//echo $gender;

$accepted_status=array("1"=>"No","2"=>"Yes");

$gengen = vector2options($accepted_genders,$gender,array(_ANY_,_NDISCLOSED_));
$professions = vector2options($accepted_professions,$theprofession,array(_ANY_,_NDISCLOSED_));

$query="SELECT user,pass,email,gender,DAYOFMONTH(birthdate),MONTH(birthdate),YEAR(birthdate),ethnic,country,us_state,city,zip,addr,phone1,my_diz,work_interest,hairlength,hairtype,haircolor,hairpiece,eyeshape,eyecolor,eyebrows,eyelashes,eyewear,faceshape,bodytype,waist,chest,hips_inseam,height,weight,shoes,dress_shirt,website,membership,profilelink,language,status,lat,lon FROM users WHERE user_id='".$uid."'";
	
if (!($res=mysql_query($query))) {error(mysql_error(),__LINE__,__FILE__);}
list($name,$pass,$email,$gender,$birthday,$birthmonth,$birthyear,$ethnic,$country,$us_state,$city,$zip,$addr,$phone1,$my_diz,$work_interest,$hairlength,$hairtype,$haircolor,$hairpiece,$eyeshape,$eyecolor,$eyebrows,$eyelashes,$eyewear,$faceshape,$bodytype,$waist,$chest,$hips_inseam,$height,$weight,$shoes,$dress_shirt,$website,$membership,$plink,$language,$status,$lat,$lon)=mysql_fetch_row($res);

$thestatus  = vector2options($accepted_status,$status,array(_ANY_,_NDISCLOSED_));



/// country city region 

$countriesx = '';
$statesx = '';
$citiesx='';

$qs_11="SELECT con_id,name FROM geo_countries ORDER BY name";
$qr_11=mysql_query($qs_11);

while ($myrow = mysql_fetch_array($qr_11))
    {
   if($country==$myrow["con_id"]) {
   $selected = "selected='selected'";
   
   $choosen_country_code=$myrow["con_id"];
   }else {

	$selected ="";

	}

$countriesx.="<option $selected value='".$myrow["con_id"]."'>".$myrow["name"]."</option>";
	
	}
	
	
    if ($choosen_country_code) 
    {
        $qs_12="SELECT DISTINCT name,sta_id FROM geo_states WHERE con_id='$choosen_country_code' ORDER BY name";
        $qr_12=mysql_query($qs_12);
        while ($myrow = mysql_fetch_array($qr_12))
        {
			
		
        if($myrow['sta_id']==$us_state) {
		$selected = "selected='selected'";
		$choosen_state_code=$myrow["sta_id"];
		} else {
		$selected ="";
		}
        if($myrow["name"]!="")$statesx.="<option $selected value='".$myrow["sta_id"]."'>".$myrow["name"]."</option>";
	
	
        }
    }
	
	$choosen_city_code=$city;
    if ($choosen_state_code)
    {
        $qs_13="SELECT cty_id, sta_id,name FROM geo_cities WHERE sta_id='$choosen_state_code' ORDER BY name";
        $qr_13=mysql_query($qs_13);
        while ($myrow = mysql_fetch_array($qr_13))
        {
        if($myrow['cty_id']==$city) {
		
		$selected = "selected='selected'";
		$choosen_city_code=$city;
		}else {
		$selected ="";
		
		}
        if($myrow['name']!="")
		
		$citiesx.="<option $selected value='".$myrow["cty_id"]."'>".$myrow["name"]."</option>";
		
		
		
        }
    }








?>

<form method="post">
	<table class="dv-table" style="width:100%;background:#fafafa;padding:5px;margin-top:5px;">
		<tr>
			<td>Name</td>
			<td><input name="user" class="easyui-validatebox" required="required"></input></td>
			<td>Gender</td>
			<td>
				
			<select id="dd" class="combobox" name="gender" required="required" ><?php echo $gengen ?></select>
			
			
			
			</td>
	
		
		
		
		
		<tr>
			<td>Email</td>
			<td><input name="email" class="easyui-validatebox" validType="email" required="required"></input></td>
			
			
			<td>Join Date</td>
			<td>
			<input name="joindate" id="dd" type="text" class="easyui-datebox" required="required"></input>  
			</td>
			
			
			
			
		</tr>
		
		
		<tr>
			
			
			<td>Password</td>
			<td><input name="pass" class="easyui-validatebox" validType="password" ></input></td>
			
			
			<td>Profile Link</td>
			<td><input name="profilelink" class="easyui-validatebox" validType="profilelink" required="required"></input></td>
		
		
		
		
		</tr>
		
	
		<tr>
			
			<td>Profile Type</td>
			<td><select class="combobox" name="profession" required="required"><?php echo $professions ?></select></td>
		
			<td>Activated</td>
			<td>
			<select id="dd" class="combobox" name="status" required="required" ><?php echo $thestatus ?></select>
			
				
	
			
			
			</td>
			

		</tr>
		
	
		<tr>
		<td>Birth Date</td>
			<td><input name="birthdate" id="dd2" type="text" class="easyui-datebox" required="required"></input></td>
			
		
		</tr>
		
		
		
<tr>
<td>Country</td>
<td>

<select class="combobox"  name="country" id="id_country" onchange="xrefresh_state();" style="width: 161px;">
<option value="country">Country</option>
<?php echo $countriesx; ?>
</select></td></tr><tr>




<td>Region</td>

<td align="left">
<select class="combobox" name="us_state" id="id_state" onchange="xrefresh_city();">
<option value="">Region</option>
<?php echo $statesx; ?>
</select></td></tr><tr>

<td>City</td>

<td align="left">
<select class="combobox" name="city" id="id_city">
<option value="">City</option>

<?php echo $citiesx ?>
</select>


</td>



		
<tr>
			
	<td>Lat</td>
			
	<td><input name="lat" ></input></td>
	
	
	
			
</tr>
	
<tr>
			
	<td>Lon</td>
			

	<td><input name="lon" ></input></td>


	
	
			
</tr>









</tr>	
		
		
	</table>
	<div style="padding:5px 0;text-align:right;padding-right:30px">
		<a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="saveItem(<?php echo $_REQUEST['index'];?>)">Save</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelItem(<?php echo $_REQUEST['index'];?>)">Cancel</a>
	</div>
</form>






<script type="text/javascript">


function xrefresh_state()
{
    $("#id_state").attr("disabled", true);$("#id_state").html('');
    var qs = $("#id_country").val();
    if(qs == '') {
        alert('You must specify a country !');
    }
    else {
        $("#id_state").append(new Option('Please Wait ...'));
        var state_options= new Array();
        $.get("location-ajax.php?action=refresh_state&country_id=" + qs, function(data){
            eval(data); 
                if(state_options.length > 0) {
                    addOptions(state_options,document.getElementById('id_state'));
        }  }  ); } 
} 
function xrefresh_city()
{
    $("#id_city").attr("disabled", true);$("#id_city").html('');
    var qs = $("#id_state").val();
    if(qs == '') {
        alert('You must specify a state !');
    }
    else {
        $("#id_city").append(new Option('Please Wait ...'));
        var city_options= new Array();
        $.get("location-ajax.php?action=refresh_city&state_id=" + qs, function(data){
            eval(data); 
                if(city_options.length > 0) {
                    addOptions(city_options,document.getElementById('id_city'));
        }  }  ); } 
}         
function addOptions(cl,select_id) 
{
        if(select_id)
        {  
            $(select_id).removeAttr("disabled");$(select_id).html('');
            for(var i = 0; i < cl.length; i++) {
            select_id.options[i] = new Option(cl[i].text, cl[i].value);
            }
        }
} 


function update() {
  $("#notice_div").html('Loading..'); 
  $.ajax({
    type: 'GET',
    url: 'location-ajax.php?action=refresh_city&state_id=',
    timeout: 2000,
    success: function(data) {
      $("#some_div").html(data);
      $("#notice_div").html(''); 
      window.setTimeout(update, 10000);
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      $("#notice_div").html('Timeout contacting server..');
      window.setTimeout(update, 60000);
    }




});






}

       
</script>
