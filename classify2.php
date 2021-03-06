<?php
session_start();
include('functions.php');
sqlconnect();
include('auth.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0"> 
    <script src="js/stay_standalone.js" type="text/javascript"></script>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="apple-touch-icon" href="autoxicon.png" />
    <meta charset="UTF-8">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/add2home.css">
    <script type="application/javascript" src="js/add2home.js"></script>
    
     <style>
        table { cursor:pointer; }
        .table tbody tr.selected td{
            background-image: url('img/check.png');
            background-repeat:no-repeat;
            border: 1px solid #007c1e; 
            background-color: #d0e9c6;
        }
        body {
            background-image: url('img/satinweave.png');  /*thanks SubtlePatterns.com */
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }
      .bottombar {
	      border-width:1px; border-style: solid; border-color: black; padding: 5px; height: 25px;margin-left: auto; margin-right: auto; text-align: center; background-color: #d9edf7; color:#3a87ad; font-size: 19px;
      }
      .tablehead {
        background-color: #cccccc;
      }
      @media (max-width: 979px) {
        .navbar-fixed-top,
        .navbar-fixed-bottom {
            position: fixed;
            margin-left: 0px;
            margin-right: 0px;
      }

        }
        
        .twitter-typeahead .tt-query,
.twitter-typeahead .tt-hint {
  margin-bottom: 0;
}

.tt-dropdown-menu {
  min-width: 160px;
  margin-top: 2px;
  padding: 5px 0;
  background-color: #fff;
  border: 1px solid #ccc;
  border: 1px solid rgba(0,0,0,.2);
  *border-right-width: 2px;
  *border-bottom-width: 2px;
  -webkit-border-radius: 6px;
     -moz-border-radius: 6px;
          border-radius: 6px;
  -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
     -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
          box-shadow: 0 5px 10px rgba(0,0,0,.2);
  -webkit-background-clip: padding-box;
     -moz-background-clip: padding;
          background-clip: padding-box;
}

.tt-suggestion {
  display: block;
  padding: 3px 20px;
}

.tt-suggestion.tt-is-under-cursor {
  color: #fff;
  background-color: #0081c2;
  background-image: -moz-linear-gradient(top, #0088cc, #0077b3);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0077b3));
  background-image: -webkit-linear-gradient(top, #0088cc, #0077b3);
  background-image: -o-linear-gradient(top, #0088cc, #0077b3);
  background-image: linear-gradient(to bottom, #0088cc, #0077b3);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0077b3', GradientType=0)
}

.tt-suggestion.tt-is-under-cursor a {
  color: #fff;
}

.tt-suggestion p {
  margin: 0;
}
        
        
    </style>
    <title>Classify</title>
    
    
<!--     <link href="css/selectboxit.css" rel="stylesheet" media="screen"> -->
</head>
<body>
<script src="js/jquery191min.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<!-- <script src="js/selectboxit.js"></script> -->
<script src="js/typeahead.min.js"></script>
<?php include('navbar.html'); ?>
<div class="container">
    <div id="floatDiv"></div>
<?php
if (!$username){
	loginform();
}
$year    = $_POST[year];
$carid   = $_POST[carid];
$wheelid = $_POST[wheelid];
$suspvalue = 0;
if ($carid == "") {
	$_SESSION = array();
    echo "
    
    
    
    <form id='step1' action='$_SERVER[PHP_SELF]' method='post' name='year' class='form-horizontal' role='form'>
    <div class='form-group'>
    	
    	<label for='chooseyear' class='col-lg-2 control-label'>Have a BMW or Mini?</label>

		<div class='col-lg-4'>
			<SELECT name='year' id='chooseyear' class='form-control' onchange='this.form.submit()'>
				<option value=''>Select Year</option>";
    if ($year != "") {
        echo "<option selected='$year'>$year</option>";
    } //if year has already been slected, make it the default value
    $currentyear = date("Y");
    $currentyear++; //always show current year + 1 to account for the next MY
    for ($i = $currentyear; $i >= 1960; $i--) {
        echo "<option value='$i'>$i</option>";
    }
    echo "</SELECT>
    	</div>
    </div>
    </form>";
}
if (($carid == "") && ($year == "") && ($username != "")){
	    echo "<p>Don't have a BMW or Mini?  Type in the year, make, and model of your car:</p><form id='step1' action='calc.php?nonbmw=Y' method='post'> <input name='cardesc' class='input-large' id='xclassinput' placeholder='2000 Mazda Miata'>";
        if ($usergroup == "admin"){
            echo"<div class='nonbmwsubmit'>Admin: Save this classification in someone else's profile: <input type='text' class='input-large typeahead' name='alternateuser'  placeholder='Start typing a name...'></div>";
        }
        echo"<button type='submit' class='btn btn-primary nonbmwsubmit' style='display:inline;'>Submit</button></form><div id='nonbmwhelper' class='badge badge-important'></div>";

}
if ($year != "" && $carid == "") {
    sqlconnect();
    echo "<form id='step2' action='$_SERVER[PHP_SELF]' method='post' name='model'><input type='hidden' name='year' value='$year'>
    <div class='input-append'>
    <SELECT name='carid' class='span5' onchange='this.form.submit()'><option value=''>Select your car</option>";
    $result = mysql_query("SELECT * FROM `autox_cars` WHERE `year_start` <=$year AND `year_end` >=$year ORDER BY `autox_cars`.`car` ASC") or die("Error: " . mysql_error());
    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
        echo "<option value='$row[0]'>$row[1]</option>";
    }
    mysql_free_result($result);
    echo "</SELECT></div></form>";


}
if ($year != "" && $carid != "" && $wheelid == "") {
    sqlconnect();
    $result = mysql_query("SELECT * FROM `autox_optional_car_wheels` WHERE `car_id` = '$carid'") or die("Error: " . mysql_error());
    if (mysql_num_rows($result) == 0) {
        $url = "$_SERVER[PHP_SELF]?year=$year&carid=$carid&wheelid=0";
        echo "<form id='step2' action='$_SERVER[PHP_SELF]' method='post' name='model' onload='this.form.submit()'>
        <input type='hidden' name='year' value='$year'>
        <input type='hidden' name='carid' value='$carid'>
        <input type='hidden' name='wheelid' value='0'>
        </form>
        <script>
            document.model.submit();
        </script>
        ";
        //echo"There are no optional wheel/tire packages for this vehicle.  <a href='$url'>Click here to continue your classification</a>.";
    } else {
        echo "<form id='step2' action='$_SERVER[PHP_SELF]' method='post' name='model'><input type='hidden' name='year' value='$year'><input type='hidden' name='carid' value='$carid'>
    <div class='input-append'>
    <SELECT name='wheelid' class='span5' onchange='this.form.submit()'><option value='N'>Select your optional wheel/tire package</option>
    <option value='0'>None</option>";
        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            echo "<option value='$row[0]'>$row[2]</option>";
        }
        mysql_free_result($result);
        echo "</SELECT></div></form>";
    }
}
if ($year != "" && $carid != "" && $wheelid != "") {
    $readytoclassify="Y";
    sqlconnect();
    $result = mysql_query("SELECT * FROM `autox_cars` WHERE `car_id` = '$carid'") or die("Error: " . mysql_error());
    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
        $_SESSION['year'] = $year; //Push the year into a session cookie
        foreach ($row as $key => $value) { //Push the entire car information row into a session cookie
            $_SESSION[mysql_field_name($result, $key)] = $value;
        }
    }
    $wheelresult = mysql_query("SELECT * FROM `autox_optional_car_wheels` WHERE `car_id` = '$carid' AND `wheel_id` = '$wheelid'") or die("Error: " . mysql_error());
    if (mysql_num_rows($wheelresult) > 0) {
        while ($wheelrow = mysql_fetch_array($wheelresult, MYSQL_NUM)) {
            $_SESSION['opt_rear_wheel_width']  = $wheelrow[5];
            $_SESSION['opt_front_wheel_width'] = $wheelrow[9];
            $_SESSION['pkg_points']            = (($_SESSION['opt_rear_wheel_width'] - $_SESSION['rear_wheel_width']) / .5); //add 1/2 point for every addt'l 1" wheel width
            $_SESSION['pkg_points']            = $_SESSION['pkg_points'] + (($_SESSION['opt_front_wheel_width'] - $_SESSION['front_wheel_width']) / .5); //add 1/2 point for every addt'l 1" wheel width
            $_SESSION['opt_package_desc']	   = $wheelrow[2];
            $_SESSION['opt_package_name']      = $wheelrow[3];
            if ($_SESSION['opt_package_name'] != "") { $_SESSION['car'] = $_SESSION['car'] . " " . $_SESSION['opt_package_name']; } else { $_SESSION['car'] = $_SESSION['car'] . " " . $_SESSION['opt_package_desc'];  }

            $pkgcode = mysql_query("SELECT * FROM `autox_packages` WHERE `package_code` = '$wheelrow[3]'") or die("Error: " . mysql_error());
            while ($pkgcode = mysql_fetch_array($pkgcode, MYSQL_NUM)) {
            	$_SESSION['suspension_code'] = $pkgcode[3];
            }
        }
    } else {
        $_SESSION['opt_package_desc']	   = "";
        $_SESSION['opt_package_name']      = "";
        $_SESSION['opt_rear_wheel_width']  = ""; //destroy the cookie... otherwise if you go back, you'll have the wrong # of points!            
        $_SESSION['opt_front_wheel_width'] = ""; //destroy the cookie... otherwise if you go back, you'll have the wrong # of points!
    }
    echo "<h3>" . $year . " " . $_SESSION['car'] . "</h3>";
    if ($_SESSION[pkg_points]) {
	    echo "<h5>" . $_SESSION['points'] . " base points + " . $_SESSION['pkg_points'] . " point from larger wheels/tires included in package</h5>";
    } else {
	    echo "<h5>" . $_SESSION['points'] . " base points</h5>";
    }
    echo"<span class='label label-info'>Click on a modification to select/unselect</span><form id='options' action='calc.php' method='post'>";
    $result = mysql_query("SELECT * FROM `autox_mod_categories` ORDER BY `mandatory_selection` DESC") or die("Error: " . mysql_error());
    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
        echo "<h4>$row[5]";
        if ($row[2] == "Y") {
            echo "    <span class='badge badge-important'>Selection required</span>";
        }
        echo "</h4><h5>$row[6]</h5>";
        if ($row[0] == "11"){
	        if ($_SESSION['opt_front_wheel_width']) { echo"<h5>We think your front wheels are ". $_SESSION['opt_front_wheel_width'] . " inches wide. Calculate increased width from this value.</h5>"; } else { echo"<h5>We think your front wheels are ". $_SESSION['front_wheel_width'] . " inches wide. Calculate increased width from this value.</h5>"; }
        }

        if ($row[0] == "12"){
	        if ($_SESSION['opt_rear_wheel_width']) { echo"<h5>We think your rear wheels are ". $_SESSION['opt_rear_wheel_width'] . " inches wide. Calculate increased width from this value.</h5>"; } else { echo"<h5>We think your rear wheels are ". $_SESSION['rear_wheel_width'] . " inches wide. Calculate increased width from this value.</h5>"; }
        }
        $showlsd = "";
        if ($row[0] == "13"){
         
            if ($_SESSION['LSD_standard'] == "N") { 
                    echo "<h5>We don't think your car came from the factory with a limited slip differential.  If we are incorrect OR if you added an LSD, select this option.</h5>"; 
                    
            } else { 
                    echo "<h5>We think your car came with a limited slip differential.  These points are included in your base point calculation.</h5>"; 
                    $showlsd = "N";
                    
            }
        }
        

        if ($showlsd != "N") {
    
        $query = "SELECT * FROM `autox_modifications` WHERE `category_id` = '$row[0]'";
        $anotherresult = mysql_query($query) or die("Error: " . mysql_error());
        if ($row[7] == "Y") { //allow a multiple selections on certain categories
            echo "<table class='table table-condensed table-bordered modstablemulti'>";
        } else {
            echo "<table class='table table-condensed table-bordered modstable'>";
        }
        echo "<thead><tr class='tablehead'><th style='padding-left:30px;'>Name</th><th>Point value</th></tr></thead><tbody>";
        while ($anotherrow = mysql_fetch_array($anotherresult, MYSQL_NUM)) {
            if ($anotherrow[0] == "38") {
                $modpoints = $_SESSION['lsd_points'];
            } else {
                $modpoints = $anotherrow[4];
            } //for limited slip
            $default = $anotherrow[1];
            if  ($_SESSION['suspension_code'] == $anotherrow[5]) {
	            $default = "Y"; 
	            if ($anotherrow[5] != "B") {
			        $addtlinfo = "(<i>Auto selected due to car/package</i>)";
			    }
	            $suspvalue = $suspvalue + $anotherrow[4];
            }
            if ($default == "Y") { //Check to see if this needs to be default
                echo "<tr class='selected'><Td class='span6' style='padding-left:30px;'>$anotherrow[3] $addtlinfo</td><td class='span2 pointvalue' style='padding-left:30px;'>$modpoints</td><input type='hidden' name='mod_id[$anotherrow[0]]' value='true'></tr>";
            } else {
                echo "<tr><Td class='span6' style='padding-left:30px;'>$anotherrow[3]</td><td class='span2 pointvalue' style='padding-left:30px;'>$modpoints</td><input type='hidden' name='mod_id[$anotherrow[0]]' value='false'></tr>";
            }
        }
        echo "</tbody></table>";
 
        }


    }
    echo "<div id='enginemodificationsheader'><h4>Engine modifications</h4>
	      <h5>Below is a list of engine modifications as well as an average percent gain that modification provides.  Click your modifications OR enter a rear wheel (not flywheel) horsepower number below that you believe is true either from a dyno chart or modification manufacturer claims.</h5></div>
	      <center><p><button class='btn btn-info' id='showenginetable'>I wish to select my mods from a table and will assume GGC's engine gains are correct</button></p>
          <p><button class='btn btn-info' id='showrwhptable'>I wish to enter a rear wheel HP number</button></p>
          <p><button class='btn btn-info' id='hidethebuttons'>I have no engine modifications</button></p></center>
          <table class='table table-condensed table-bordered enginetablemulti'><thead><tr class='tablehead'><th style='padding-left:30px;'>Name</th><th style='padding-left:30px;'>% addt'l horsepower</th></tr></thead><tbody>";
    
    $result = mysql_query("SELECT * FROM `autox_mods_engine` ORDER BY `percent` ASC") or die("Error: " . mysql_error());
    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
        echo "<tr><Td class='span6' style='padding-left:30px;'>$row[2]</td><td class='span2 pointvalue' style='padding-left:30px;'>$row[3] </td><input type='hidden' name='engine_id[$row[0]]' value='false'></tr>";
    }
    echo"</table><table class='table table-condensed table-bordered enginetablemultiresults'>
        <Tr><td class='span6' style='padding-left:30px;'>Total additional hp</td><td class='span2' style='padding-left:30px;'><div class='percent' style='display: inline;'>0</div>%</td></tr>
        <Tr><td class='span6' style='padding-left:30px;'>Points from engine mods</td><Td class='span2' style='padding-left:30px;'><div class='enginemodpoints' style='display: inline;'>0</div></td></tr></table>";
    $rwhp = round($_SESSION[BHP] * .85);
    $total = round($rwhp + 10);
    echo "<table class='table table-condensed table-bordered' id='rwhptable'>
        <tr id='dynorow'><td class='span6' style='padding-left:30px;'>Your TOTAL rear wheel (not flywheel) horsepower based on dyno or modification manufacturer claims. We think your car has approx <B>$rwhp</b> rwhp, if you added a mod that adds a claimed 10hp, enter <B>$total</b> in this box.</td><td class='span2' style='padding-left:30px;'><input type='text' name='flywheelhp' id='dyno' class='input-small'>hp</td></tr>";
       if ($username) { echo "<tr id='claimrow'><td class='span6' style='padding-left:30px;'>If you entered a RWHP number based on manufacturers claims, please list your engine mods in this box. If you entered a RWHP number based on a dyno, simply type 'dyno' into this box.</td><Td class='span2' style='padding-left:30px;'><textarea name='hpclaim' id='explainhp'></textarea></td></tr>"; }

    echo"<Tr><td class='span6' style='padding-left:30px;'>Total additional hp</td><td class='span2' style='padding-left:30px;'><div class='percent' style='display: inline;'>0</div>%</td></tr>
         <Tr><td class='span6' style='padding-left:30px;'>Points from engine mods</td><Td class='span2' style='padding-left:30px;'><div class='enginemodpoints' style='display: inline;'>0</div></td></tr></table>";
    if ($username){
      echo"<div id='differentclass'><br><br><table class='table'><Tr><Td>Want to run your car in a higher or non-competitive class?  Select it here</td><Td><SELECT name='chosenclass' class='span2' id='chosenclass'></SELECT></td></tr></table>";
		if ($usergroup == "admin"){
			echo"<table class='table'><Tr><Td class='span8'>";
	   		echo"Admin: Save this classification in someone else's profile</td><td class='span4'><input type='text' class='input-large typeahead' name='alternateuser'  placeholder='Start typing a name...'>";
	   		echo"</td></tr></table>";
   		}
        echo "</div><button class='btn btn-success' type='submit' id='submitclassification'>Save this classification to my user profile</button>";
    }
    echo"</form>";
}
?>
<Br><Br><br>
</div>  <!--container-->
<div class="navbar navbar-fixed-bottom bottombar" id='finalpoints'></div>

<script>
function updatefloater(currentvalue,cumulativepoints)
{
    if (!cumulativepoints) {cumulativepoints = 0;}
    var carclass;
    var texttodisplay = currentvalue + cumulativepoints;
    <?php
sqlconnect();
$result = mysql_query("SELECT * FROM `autox_classes`") or die("Error: " . mysql_error());
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    echo "if (texttodisplay >= $row[1] && texttodisplay <= $row[2]) { carclass = '$row[0]'; } ";
}
?>
   var update = "Total points: " + texttodisplay + " - Class " + carclass;
    $("#finalpoints").text(update);
   if (carclass == 'C') { $("#chosenclass").html("<option value=''></option><option value='N'>N (Non-compete)</option><option value='B'>B</option><option value='A'>A</option><option value='AA'>AA</option><option value='AAA'>AAA</option><option value='Gonzo'>Gonzo</option>") ;} 
   if (carclass == 'B') { $("#chosenclass").html("<option value=''></option><option value='N'>N (Non-compete)</option><option value='A'>A</option><option value='AA'>AA</option><option value='AAA'>AAA</option><option value='Gonzo'>Gonzo</option>") ;} 
   if (carclass == 'A') { $('#chosenclass').html("<option value=''></option><option value='N'>N (Non-compete)</option><option value='AA'>AA</option><option value='AAA'>AAA</option><option value='Gonzo'>Gonzo</option>") ;} 
   if (carclass == 'AA') { $('#chosenclass').html("<option value=''></option><option value='N'>N (Non-compete)</option><option value='AAA'>AAA</option><option value='Gonzo'>Gonzo</option>") ;} 
   if (carclass == 'AAA') { $('#chosenclass').html("<option value=''></option><option value='N'>N (Non-compete)</option><option value='Gonzo'>Gonzo</option>") ;} 
   if (carclass == 'Gonzo') { $('#chosenclass').html("<option value=''></option><option value='N'>N (Non-compete)</option>") ;}     
}
<?php
echo "var enginemods = [";
$result = mysql_query("SELECT * FROM `autox_engine_levels` WHERE engine_level = \"" . $_SESSION['engine_level'] . "\" AND `lsd` = 'N'") or die("Error: " . mysql_error());
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	echo "['" . $row[0] . "',". $row[3] . "," . $row[4] . "," . $row[2] . "],";
}
echo "['Z',0,0,0]];
var enginemodslsd = [";
$result = mysql_query("SELECT * FROM `autox_engine_levels` WHERE engine_level = \"" . $_SESSION['engine_level'] . "\" AND `lsd` = 'Y'") or die("Error: " . mysql_error());
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	echo "['" . $row[0] . "',". $row[3] . "," . $row[4] . "," . $row[2] . "],";
}
echo "['Z',0,0,0]];";
?>
var cumulativepoints = 0;
var lsd = '<?php echo $_SESSION['LSD_standard'];?>';
var cumulativepercent = 0;
var suspvalue = <?php echo $suspvalue; ?>;
var pointvalue = 0;
pointvalue = pointvalue * 1;
var basepoints = <?php if ($_SESSION['points']) {echo $_SESSION['points'];} else { echo "0";} ?>;
var pkgpoints =  <?php if ($_SESSION['pkg_points']) {echo $_SESSION['pkg_points'];} else { echo "0";} ?>;
basepoints = basepoints * 1;
var currentvalue = 0;
currentvalue = currentvalue * 1;
currentvalue = currentvalue + basepoints + pkgpoints + suspvalue;
updatefloater(currentvalue);





$(document).ready(function(){

    	<?php if ($usergroup == "admin"){
  			echo"  	
    
            $('.typeahead').typeahead({
				name: 'alternateuser',
				local: [";	
			$result = mysql_query("SELECT * FROM `gy01d_users` WHERE `lastvisitdate` != '0000-00-00 00:00:00'") or die("Error: " . mysql_error());
            while ($row = mysql_fetch_array($result, MYSQL_NUM)) {  
                $escaped = str_replace("'", "", $row[1]);
                echo"'$escaped ($row[2])',";
            }
            echo"'Fake User (fakeuser)']});"; 
         }?>


  
    $('#xclassinput').keyup(function() {
        var nonbmwyear = $(this).val().substr(0,2);
        if ((nonbmwyear == '19') || (nonbmwyear == '20'))  {
           $('.nonbmwsubmit').show();
        }   else {
            if ($(this).val().length > 2) {
                $('#nonbmwhelper').text('4 digit year required');
            } else {
                $('#nonbmwhelper').text('');
            }

        }
    });
    $('.nonbmwsubmit').hide();
	$('.enginetablemulti').hide();
    $('.enginetablemultiresults').hide();
	$('#rwhptable').hide();
	$('#percenttable').hide();
	$('#submitclassification').hide();
	<?php if ($readytoclassify != "Y") { echo"$('#finalpoints').hide();"; }?>
/* 	$("select").selectBoxIt({}); */
    $('#differentclass').hide();
});




$('#showenginetable').on('click', function(event) {
	event.preventDefault();
	$('.enginetablemulti').show();
    $('.enginetablemultiresults').show();
	$('#showrwhptable').hide();
	$('#showenginetable').hide();
	$('#percenttable').show();
	$('#hidethebuttons').hide();
	$('#submitclassification').show();
    $('#differentclass').show();	
});      
$('#showrwhptable').on('click', function(event) {
	event.preventDefault();
	$('#rwhptable').show();
	$('#showenginetable').hide();
	$('#showrwhptable').hide();
	$('#percenttable').show();
	$('#hidethebuttons').hide();
	$('#submitclassification').show();	
    $('#differentclass').show();

}); 
$('#hidethebuttons').on('click', function(event) {
	event.preventDefault();
	$('#showenginetable').hide();
	$('#showrwhptable').hide();
	$('#hidethebuttons').hide();
	$('#enginemodificationsheader').hide();
	$('#submitclassification').show();	
    $('#differentclass').show();

});            
$('.modstable tbody tr').on('click', function(event) {
    if ($(this).hasClass('selected')) {
        $(this).find('input').attr('value','false');
        $(this).removeClass('selected');
        pointvalue = $(this).find(".pointvalue").html(); 
        pointvalue = pointvalue * 1;
        currentvalue = currentvalue - pointvalue;
        islsdselected = $(this).find('input').attr('name');
        if (islsdselected == 'mod_id[38]') { lsd = 'N';}
    } else {
        $(this).find('input').attr('value','true');           
         pointvalue = $(this).closest("table").find(".selected").find(".pointvalue").html(); 
        pointvalue = pointvalue * 1
        if (isNaN(pointvalue) === false) { currentvalue = currentvalue - pointvalue; }
        $(this).addClass('selected').siblings().removeClass('selected'); 
        $(this).addClass('selected').siblings().find('input').attr('value','false');  //testing this, worked!
        pointvalue = $(this).find(".pointvalue").html(); 
        pointvalue = pointvalue * 1
        currentvalue = currentvalue + pointvalue;
        islsdselected = $(this).find('input').attr('name');
        if (islsdselected == 'mod_id[38]') { lsd = 'Y';}        
    }

    updatefloater(currentvalue);
});
$('.modstablemulti tbody tr').on('click', function(event) {
    if ($(this).hasClass('selected')) {
        $(this).find('input').attr('value','false');
        $(this).removeClass('selected');
          pointvalue = $(this).find(".pointvalue").html(); 
        pointvalue = pointvalue * 1
        currentvalue = currentvalue - pointvalue;
    } else {
        $(this).find('input').attr('value','true');           
        $(this).addClass('selected');
        pointvalue = $(this).find(".pointvalue").html(); 
        pointvalue = pointvalue * 1
        currentvalue = currentvalue + pointvalue;

    }

    updatefloater(currentvalue);
});
$('.enginetablemulti tbody tr').on('click', function(event) {
    if ($(this).hasClass('selected')) {
        $(this).find('input').attr('value','false');
        $(this).removeClass('selected');
         pointvalue = $(this).find(".pointvalue").html(); 
        pointvalue = pointvalue * 1;
        cumulativepercent = cumulativepercent - pointvalue;
        if (lsd == 'N'){
		    for (var i=0;i<parseInt(enginemods.length);i++) {
		        if (cumulativepercent >= enginemods[i][1] && cumulativepercent <= enginemods[i][2]) { engineresult = enginemods[i][3]; }
		    }
	    } else {
		    for (var i=0;i<parseInt(enginemodslsd.length);i++) {
		        if (cumulativepercent >= enginemodslsd[i][1] && cumulativepercent <= enginemodslsd[i][2]) { engineresult = enginemodslsd[i][3]; }
		    }
	    }
	    cumulativepoints = cumulativepoints - (cumulativepoints - engineresult); 
    } else {
        $(this).find('input').attr('value','true');           
        $(this).addClass('selected');
        pointvalue = $(this).find(".pointvalue").html(); 
        pointvalue = pointvalue * 1
        cumulativepercent = cumulativepercent + pointvalue;
        if (lsd == 'N'){
		    for (var i=0;i<parseInt(enginemods.length);i++) {
		        if (cumulativepercent >= enginemods[i][1] && cumulativepercent <= enginemods[i][2]) { engineresult = enginemods[i][3]; }
		    }     
		 } else {
		    for (var i=0;i<parseInt(enginemodslsd.length);i++) {
		        if (cumulativepercent >= enginemodslsd[i][1] && cumulativepercent <= enginemodslsd[i][2]) { engineresult = enginemodslsd[i][3]; }
		    }     
		 }
	    cumulativepoints = (cumulativepoints + engineresult) - cumulativepoints;        
        
    }
    $('.percent').empty();
    $('.percent').append(cumulativepercent);
    $('.enginemodpoints').empty();
    $('.enginemodpoints').append(cumulativepoints);        
    updatefloater(currentvalue,cumulativepoints);
});
$("#dyno").keyup(function() {
	var hp= $("#dyno").val()
	<?php 
	$bhp = $_SESSION['BHP'];
	if (isset($_SESSION['BHP'])){ echo "var increase = Math.round((((hp/.85) / $bhp)-1) * 100);";} ?>

	if (increase > 0) {
		    for (var i=0;i<parseInt(enginemods.length);i++) {
	        	if (increase >= enginemods[i][1] && increase <= enginemods[i][2]) { 
	        		engineresult = enginemods[i][3];
	        	}
	        } 
	        $('.percent').empty();
	        $('.percent').append(increase);
	        $('.enginemodpoints').empty();
	        $('.enginemodpoints').append(engineresult);  
            if ($('#explainhp').val() == '') { 
                $('#explainhp').css('border', 'solid 1px red'); 
                $('#explainhp').attr("placeholder", "Required");
            } 
	        updatefloater(currentvalue,engineresult);	
            $('#dynorow').addClass('selected');      
	} else {
	        $('.percent').empty();
	        $('.percent').append("0");
	        $('.enginemodpoints').empty();
	        $('.enginemodpoints').append("0"); 
            $('#dynorow').removeClass('selected');  
            $('#explainhp').css('border', ''); 
            $('#explainhp').attr("placeholder", ""); 
	        updatefloater(currentvalue);	 
	}
});
</script>
</body></html>