<?php
session_start();

require 'functions.php';

if ( ! AmILoggedIn()) {
	$thispage = substr($_SERVER['PHP_SELF'], 1);
	header("Location:login.php?backto=" . $thispage);
}

require '../../db_config.php';
/* 
10/06/13 - added navheader include
10/21/13 - converted to twitter bootstrap
10/24/13 - added jquery link (failed attempt)
11/02/13 - added status column
files.
10/04/14 - Renamed status -> access_level. capitalized public and private
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="utf-8" />
<!-- Bootstrap core CSS -->
<link
	href="<?php echo BOOTSTRAP_PATH; ?>dist/css/bootstrap.css"
	rel="stylesheet">
<link
	href='http://fonts.googleapis.com/css?family=Source+Sans+Pro|Germania+One'
	rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="family.css">
<title>Manage family member</title>

<?php 
if (ENVIRON == "PROD") {
	include 'google_script.php';
}
?>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo BOOTSTRAP_PATH; ?>assets/js/html5shiv.js"></script>
      <script src="<?php echo BOOTSTRAP_PATH; ?>assets/js/respond.min.js"></script>
    <![endif]-->

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144"
	href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114"
	href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72"
	href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed"
	href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon"
	href="../includes/favicon.png">
<!-- <script type="text/javascript" src="//code.jquery.com/jquery-latest.min.js"></script> -->
<!--       <script type="text/javascript">
               $(document).ready(function(){

                        function showState(){
                            $.ajax({
                                type:"post",
                                url:"family_member_manage.php",
                                data:"action=showstate",
                                success:function(data){
                                    $("#birth_state_id").html(data);
                                }
                            });
                        }

                        showState();

                        $("#statesubmit").click(function(){
                            
                          var name=$("#state").val();
 
                          $.ajax({
                              type:"post",
                              url:"family_member_manage.php",
                              data:"state="+state+"&action=addstate",
                              success:function(data){
                            	  showState();
                              }
 
                          });
 
                    });
               });
       </script> -->
</head>

<body>
<div class="container">
<?php
        
        include 'navheader.php';

$link = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD)
or die("<p>Error connecting: " . mysqli_error($link) . "</p>");

mysqli_select_db($link, DATABASE_NAME)
or die("<p>Error selecting: " . mysqli_error($link) . "</p>");

/* try {
	$pdolink = new PDO('mysql:host=localhost;dbname=cruelcou_family', DATABASE_USERNAME,DATABASE_PASSWORD);
}
catch (PDOException $e)
{
	echo $e->getMessage();
}
 */

$query_text = "select * from family order by family_name";
$result = mysqli_query($link, $query_text);

$qry_city_twp = "select * from city_twp order by city_name";

$res_city_twp = mysqli_query($link, $qry_city_twp);

$qry_state = "select * from state order by state";

$res_state = mysqli_query($link, $qry_state);

$qry_county = "select * from county order by county";

$res_county = mysqli_query($link, $qry_county);

$qry_country = "select * from country order by country";

$res_country = mysqli_query($link, $qry_country);

$qry_mother = "SELECT family_member_id, concat_ws(' ', first_name, family_name) as full_name FROM family_member, family WHERE family.family_id = family_member.family_id";

$res_mother = mysqli_query($link, $qry_mother);

$qry_father = "SELECT family_member_id, concat_ws(' ', first_name, family_name) as full_name FROM family_member, family WHERE family.family_id = family_member.family_id";

$res_father = mysqli_query($link, $qry_father);

if (isset($_GET["family_member_id"])) {
$fmquery_text = "select * from family_member where family_member_id = " .
		htmlspecialchars($_GET["family_member_id"]);

$fmresult = mysqli_query($link, $fmquery_text);
$fmrow = mysqli_fetch_array($fmresult);
$to_edit = true;
$editing=false;
$adding=false;
}

if (isset($_POST["family_member_id"])) {
    $editing = true;
	$to_edit = false;
    $adding = false;

} elseif (($_SERVER['REQUEST_METHOD'] === 'POST') && 
(!isset($_POST["family_member_id"])))  {
$editing = false;
$adding = true;
$to_edit = false;
}


if(!$result) {
	die("<p>Query Error: " . mysqli_error($link) . "</p>");
}

/* 
// test submit the stateform
$state = $_POST['state'];
if (strlen($state)){

	$statepdo = $pdolink->prepare("insert into state (state) VALUES (:state)");
	$statepdo->bindParam(':state', $state);
	$statepdo->execute();

	if ($statepdo) {
		echo 'State added';
	}
	else
	{
		print_r($statepdo->errorInfo());
	}
}
 */

/* 
 * //source: http://phpseason.wordpress.com/2013/02/15/ajax-add-retrieve-mysql-records-using-jquery-php/
$action=$_POST["action"];

if($action=="showstate") {
	$statepdo = $pdolink->prepare('select * from state order by state');
	$statepdo->execute();
	
	while($row = $statepdo->fetch(PDO::FETCH_ASSOC)) {
		echo "<option value=\"{$row["state_id"]}\">{$row["state"]}</option>\n";
	}
}
elseif ($action=="addstate"){
	$state = $_POST["state"];
	$statepdo = $pdolink->prepare("insert into state (state) VALUES (:state)");
	$statepdo->bindParam(':state', $state);
	$statepdo->execute();
	
	if ($statepdo) {
		echo 'State added';
	}
	else
	{
		print_r($statepdo->errorInfo());
	}
	
}
 */
function returnff($ff, $to_edit, $fmrow)
{
    if ($to_edit === true && strlen($fmrow[$ff])) 
        { echo "value=\"{$fmrow[$ff]}\""; }
}

/*
 * 
 * $idvalue: the value of the id
 * $displayvalue: the string to be displayed
 * $row: the row in the query populating the dropdown
 * $frmrow: the row in the family member query
 * 
 */
function checklistmatch($idvalue, $idname, $displayvalue, $displayname, $fmrow) {
if ($idvalue == $fmrow) {
echo "<option value=\"{$idvalue}\" selected>{$displayvalue}</option>\n";	
} else {
echo "<option value=\"{$idvalue}\">{$displayvalue}</option>\n";
}
}


if ($editing == true || $adding == true) {
$first_name = (isset($_POST["first_name"]) ? $_POST["first_name"] : "");
$family_id = (isset($_POST["family_id"]) ? $_POST["family_id"] : "");
$last_name = (isset($_POST["last_name"]) ? $_POST["last_name"] : "");
$gender = (isset($_POST["gender"]) ? $_POST["gender"] : "");
$married_name = (isset($_POST["married_name"]) ? $_POST["married_name"] : "");
$married_name_id = (isset($_POST["married_name_id"]) ? $_POST["married_name_id"] : "");
$birthdate_est = (isset($_POST["birthdate_est"]) ? $_POST["birthdate_est"] : "");
$birthdate = (isset($_POST["birthdate"]) ? $_POST["birthdate"] : "");
$birth_city_twp_id = (isset($_POST["birth_city_twp_id"]) ? $_POST["birth_city_twp_id"] : "");
$birth_state_id = (isset($_POST["birth_state_id"]) ? $_POST["birth_state_id"] : "");
$birth_county_id = (isset($_POST["birth_county_id"]) ? $_POST["birth_county_id"] : "");
$birth_country_id = (isset($_POST["birth_country_id"]) ? $_POST["birth_country_id"] : "");
$deathdate_est = (isset($_POST["deathdate_est"]) ? $_POST["deathdate_est"] : "");
$deathdate = (isset($_POST["deathdate"]) ? $_POST["deathdate"] : "");
$death_city_twp_id = (isset($_POST["death_city_twp_id"]) ? $_POST["death_city_twp_id"] : "");
$death_state_id = (isset($_POST["death_state_id"]) ? $_POST["death_state_id"] : "");
$death_county_id = (isset($_POST["death_county_id"]) ? $_POST["death_county_id"] : "");
$death_country_id = (isset($_POST["death_country_id"]) ? $_POST["death_country_id"] : "");
$mother_member_id = (isset($_POST["mother_member_id"]) ? $_POST["mother_member_id"] : "");
$father_member_id = (isset($_POST["father_member_id"]) ? $_POST["father_member_id"] : "");
$family_member_notes = (isset($_POST["family_member_notes"]) ? $_POST["family_member_notes"] : "");
$access_level = (isset($_POST["access_level"]) ? $_POST["access_level"] : "");

$family_member_id = (isset($_POST["family_member_id"]) ? $_POST["family_member_id"] : "");
}
// 4/25/13 - added quotes around birthdate and deathdate in the insert and update queries
if ($adding == true) {
    
    $ins_qry_text = "insert into family_member (";
       
    
    if (strlen($first_name)){
    $ins_qry_text .= "first_name";
    }

    if (strlen($family_id)){
    $ins_qry_text .= ", family_id";
    }

    if (strlen($last_name)){
    $ins_qry_text .= ", last_name";
    }

    if (strlen($gender)){
    $ins_qry_text .= ", gender";
    }

    if (strlen($married_name)){
    $ins_qry_text .= ", married_name";
    }

    if (strlen($married_name_id)){
    $ins_qry_text .= ", married_name_id";
    }

    if (strlen($birthdate_est)){
    $ins_qry_text .= ", birthdate_est";
    }

    if (strlen($birthdate)){
    $ins_qry_text .= ", birthdate";
    }
	
	if (strlen($birth_city_twp_id)){
    $ins_qry_text .= ", birth_city_twp_id";
    }

	if (strlen($birth_state_id)){
    $ins_qry_text .= ", birth_state_id";
    }
	
	if (strlen($birth_county_id)){
    $ins_qry_text .= ", birth_county_id";
    }

	if (strlen($birth_country_id)){
    $ins_qry_text .= ", birth_country_id";
    }
	
	if (strlen($deathdate_est)){
    $ins_qry_text .= ", deathdate_est";
    }
	
	if (strlen($deathdate)){
    $ins_qry_text .= ", deathdate";
    }
	
	if (strlen($death_city_twp_id)){
    $ins_qry_text .= ", death_city_twp_id";
    }
	
	if (strlen($death_state_id)){
    $ins_qry_text .= ", death_state_id";
    }
	
	if (strlen($death_county_id)){
    $ins_qry_text .= ", death_county_id";
    }
	
	if (strlen($death_country_id)){
    $ins_qry_text .= ", death_country_id";
    }
	
	if (strlen($mother_member_id)){
    $ins_qry_text .= ", mother_member_id";
    }
	
	if (strlen($father_member_id)){
    $ins_qry_text .= ", father_member_id";
    }
    if (strlen($family_member_notes)){
        $ins_qry_text .= ", family_member_notes";
    }
    $ins_qry_text .= ", access_level";

    
    $ins_qry_text .= ") VALUES (";

    
    if (strlen($first_name)){
        $ins_qry_text .= "'" . $first_name . "'";
    }

    if (strlen($family_id)){
        $ins_qry_text .= ", " . $family_id;
    }

    if (strlen($last_name)){
        $ins_qry_text .= ", '" . $last_name . "'";
    }

    if (strlen($gender)){
        $ins_qry_text .= ", '" . $gender . "'";
    }

    if (strlen($married_name)){
        $ins_qry_text .= ", '" . $married_name . "'";
    }

    if (strlen($married_name_id)){
        $ins_qry_text .= ", " . $married_name_id;
    }

    if (strlen($birthdate_est)){
        $ins_qry_text .= ", '" . $birthdate_est . "'";
    }

    if (strlen($birthdate)){
        $ins_qry_text .= ", '" . $birthdate . "'";
    }

    if (strlen($birth_city_twp_id)){
        $ins_qry_text .= ", " . $birth_city_twp_id;
    }

    if (strlen($birth_state_id)){
        $ins_qry_text .= ", " . $birth_state_id;
    }

    if (strlen($birth_county_id)){
        $ins_qry_text .= ", " . $birth_county_id;
    }

    if (strlen($birth_country_id)){
        $ins_qry_text .= ", " . $birth_country_id;
    }

    if (strlen($deathdate_est)){
        $ins_qry_text .= ", '" . $deathdate_est . "'";
    }

    if (strlen($deathdate)){
        $ins_qry_text .= ", '" . $deathdate . "'";
    }

    if (strlen($death_city_twp_id)){
        $ins_qry_text .= ", " . $death_city_twp_id;
    }

    if (strlen($death_state_id)){
        $ins_qry_text .= ", " . $death_state_id;
    }

    if (strlen($death_county_id)){
        $ins_qry_text .= ", " . $death_county_id;
    }

    if (strlen($death_country_id)){
        $ins_qry_text .= ", " . $death_country_id;
    }

    if (strlen($mother_member_id)){
        $ins_qry_text .= ", " . $mother_member_id;
    }

    if (strlen($father_member_id)){
        $ins_qry_text .= ", " . $father_member_id;
    }
    if (strlen($family_member_notes)){
        $ins_qry_text .= ", '" . $family_member_notes . "'";
    }
    $ins_qry_text .= ", '" . $access_level . "'";

$ins_qry_text .= ")";

echo $ins_qry_text;

$res_qry_text = mysqli_query($link, $ins_qry_text);

mysqli_close($link);
}

////////
//EDITS
///////

if ($editing == true) {
	$edit_qry_text = "update family_member set ";

	if (strlen($first_name)){
		$edit_qry_text .= " first_name='" . $first_name . "'";
	} else {
		$edit_qry_text .= " first_name=NULL";
		}
	
	if (strlen($family_id)){
		$edit_qry_text .= ", family_id=" . $family_id;
	}
	
	if (strlen($last_name)){
		$edit_qry_text .= ", last_name='" . $last_name . "'";
	}
	
	if (strlen($gender)){
		$edit_qry_text .= ", gender='" . $gender . "'";
	}
	
	if (strlen($married_name)){
		$edit_qry_text .= ", married_name='" . $married_name . "'";
	}
	
	if (strlen($married_name_id)){
		$edit_qry_text .= ", married_name_id=" . $married_name_id;
	}
	
	if (strlen($birthdate_est)){
		$edit_qry_text .= ", birthdate_est='" . $birthdate_est . "'";
	}
	
	if (strlen($birthdate)){
		$edit_qry_text .= ", birthdate='" . $birthdate . "'";
	}
	
	if (strlen($birth_city_twp_id)){
		$edit_qry_text .= ", birth_city_twp_id=" . $birth_city_twp_id;
	}
	
	if (strlen($birth_state_id)){
		$edit_qry_text .= ", birth_state_id=" . $birth_state_id;
	}
	
	if (strlen($birth_county_id)){
		$edit_qry_text .= ", birth_county_id=" . $birth_county_id;
	}
	
	if (strlen($birth_country_id)){
		$edit_qry_text .= ", birth_country_id=" . $birth_country_id;
	}
	
	if (strlen($deathdate_est)){
		$edit_qry_text .= ", deathdate_est='" . $deathdate_est . "'";
	}
	
	if (strlen($deathdate)){
		$edit_qry_text .= ", deathdate='" . $deathdate . "'";
	}
	
	if (strlen($death_city_twp_id)){
		$edit_qry_text .= ", death_city_twp_id=" . $death_city_twp_id;
	}
	
	if (strlen($death_state_id)){
		$edit_qry_text .= ", death_state_id=" . $death_state_id;
	}
	
	if (strlen($death_county_id)){
		$edit_qry_text .= ", death_county_id=" . $death_county_id;
	}
	
	if (strlen($death_country_id)){
		$edit_qry_text .= ", death_country_id=" . $death_country_id;
	}
	
	if (strlen($mother_member_id)){
		$edit_qry_text .= ", mother_member_id=" . $mother_member_id;
	}
	
	if (strlen($father_member_id)){
		$edit_qry_text .= ", father_member_id=" . $father_member_id;
	}
	
	if (strlen($family_member_notes)){
		$edit_qry_text .= ", family_member_notes='" . $family_member_notes . "'";
	} 
	$edit_qry_text .= ", access_level='" . $access_level . "'";
	
    $edit_qry_text .= " where family_member_id = " . $_POST["family_member_id"];
    
    echo $edit_qry_text;

    $res_qry_text = mysqli_query($link, $edit_qry_text);

mysqli_close($link);
}


//
//end php
//begin form

//don't display form if posting
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//	exit();
//}

?>
    <h3>Format for dates is YYYY-MM-DD</h3>
        <form action="family_member_manage.php" method="post">

            <label for="first_name">First Name</label>
            <div class="fieldinput">
            <input name="first_name" id="first_name" type="text" maxlength="20"
                   <?php returnff("first_name", $to_edit, $fmrow) ?> />
            </div>

            <label for="family_id">Last Name</label>
            <div class="fieldinput">
                <select name="family_id" id="family_id">
                    <option value=""></option>
                    <?php 
                    while ($row = mysqli_fetch_array($result)) {
/*                echo "<option value=\"{$row['family_id']}\">
                    {$row['family_name']}</option>\n";
 */
checklistmatch($row["family_id"], "family_id", $row["family_name"], 
"family_name", $fmrow["family_id"]);
                    }
?>
                </select>
            </div>

            <label for="gender">Gender</label>
            <div class="fieldinput">
                <select name="gender" id="gender">
                <option value=""></option>
                <?php
                if ($fmrow["gender"] == "male") { 
                echo "<option value=\"male\" selected>male</option>";
                } else {
				echo "<option value=\"male\">male</option>";
				}
				
				if ($fmrow["gender"] == "female") {
					echo "<option value=\"female\" selected>female</option>";
				} else {
					echo "<option value=\"female\">female</option>";
				}
                ?>
                </select>
            </div>

            <label for="married_name_id">Married Name</label>
            <div class="fieldinput">
                <select name="married_name_id" id="married_name_id">
                    <option value=""></option>
                    <?php
		mysqli_data_seek($result,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($result)) {
//                echo "<option value=\"{$row['family_id']}\">
//                   {$row['family_name']}</option>\n";
checklistmatch($row["family_id"], "family_id", $row["family_name"], 
"family_name", $fmrow["married_name_id"]);
                    }
                    ?>
                </select>
            </div>

            <label for="birthdate_est">Est Birth Date</label>
            <div class="fieldinput">
            <input name="birthdate_est" id="birthdate_est" type="text" maxlength="25" 
                   <?php returnff("birthdate_est", $to_edit, $fmrow) ?>/>
            </div>

            <label for="birthdate">Birth Date</label>
            <div class="fieldinput">
            <input name="birthdate" id="birthdate" type="text" maxlength="25" 
                 <?php returnff("birthdate", $to_edit, $fmrow) ?>  />
            </div>

            <label for="birth_city_twp_id">Birthplace (City/Township)</label>
            <div class="fieldinput">
                <select name="birth_city_twp_id" id="birth_city_twp_id">
                    <option value=""></option>
                    <?php
		//mysqli_data_seek($result,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_city_twp)) {
//                echo "<option value=\"{$row['city_twp_id']}\">
//                    {$row['city_name']} {$row['city_twp']}</option>\n";
checklistmatch($row["city_twp_id"], "city_twp_id", $row["city_name"], 
"city_name", $fmrow["birth_city_twp_id"]);
                    }
                    ?>
                </select>
            </div>

            <label for="birth_state_id">Birthplace (State)</label>
            <div class="fieldinput">
                <select name="birth_state_id" id="birth_state_id">
                    <option value=""></option>
                    <?php
		//mysqli_data_seek($result,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_state)) {
                //echo "<option value=\"{$row['state_id']}\">{$row['state']}</option>\n";
checklistmatch($row["state_id"], "state_id", $row["state"], "state", $fmrow["birth_state_id"]);
}
                    ?>
                </select>
                
                
               <!--  <p><a href="#stateform" data-toggle="modal" data-target="#stateform">Add state</a></p> -->
                
                
                
            </div>

            <label for="birth_county_id">Birthplace (County)</label>
            <div class="fieldinput">
                <select name="birth_county_id" id="birth_county_id">
                    <option value=""></option>
                    <?php
		//mysqli_data_seek($result,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_county)) {
//                echo "<option value=\"{$row['county_id']}\">{$row['county']}</option>\n";
checklistmatch($row["county_id"], "county_id", $row["county"], "county", $fmrow["birth_county_id"]);
                    }
                    ?>
                </select>
            </div>

            <label for="birth_country_id">Birthplace (Country)</label>
            <div class="fieldinput">
                <select name="birth_country_id" id="birth_country_id">
                    <option value=""></option>
                    <?php
		//mysqli_data_seek($result,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_country)) {
//                echo "<option value=\"{$row['country_id']}\">{$row['country']}</option>\n";
checklistmatch($row["country_id"], "country_id", $row["country"], "country", $fmrow["birth_country_id"]);
                    }
                    ?>
                </select>
            </div>


            <label for="deathdate_est">Est Death Date</label>
            <div class="fieldinput">
            <input name="deathdate_est" id="deathdate_est" type="text" maxlength="25" 
                   <?php returnff("deathdate_est", $to_edit, $fmrow) ?>/>
            </div>

            <label for="deathdate">Death Date</label>
            <div class="fieldinput">
            <input name="deathdate" id="deathdate" type="text" maxlength="25" 
                 <?php returnff("deathdate", $to_edit, $fmrow) ?>  />
            </div>

            <label for="death_city_twp_id">Deathplace (City/Township)</label>
            <div class="fieldinput">
                <select name="death_city_twp_id" id="death_city_twp_id">
                    <option value=""></option>
                    <?php
		mysqli_data_seek($res_city_twp,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_city_twp)) {
//                echo "<option value=\"{$row['city_twp_id']}\">
//                    {$row['city_name']} {$row['city_twp']}</option>\n";
checklistmatch($row["city_twp_id"], "city_twp_id", $row["city_name"], 
"city_name", $fmrow["death_city_twp_id"]);
                    }
                    ?>
                </select>
            </div>

            <label for="death_state_id">Deathplace (State)</label>
            <div class="fieldinput">
                <select name="death_state_id" id="death_state_id">
                    <option value=""></option>
                    <?php
		mysqli_data_seek($res_state,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_state)) {
//                echo "<option value=\"{$row['state_id']}\">{$row['state']}</option>\n";
checklistmatch($row["state_id"], "state_id", $row["state"],
"state", $fmrow["death_state_id"]);
                    }
                    ?>
                </select>
            </div>

            <label for="death_county_id">Deathplace (County)</label>
            <div class="fieldinput">
                <select name="death_county_id" id="death_county_id">
                    <option value=""></option>
                    <?php
		mysqli_data_seek($res_county,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_county)) {
//                echo "<option value=\"{$row['county_id']}\">{$row['county']}</option>\n";
checklistmatch($row["county_id"], "county_id", $row["county"], "county", $fmrow["death_county_id"]);
                    }
                    ?>
                </select>
            </div>

            <label for="death_country_id">Deathplace (Country)</label>
            <div class="fieldinput">
                <select name="death_country_id" id="death_country_id">
                    <option value=""></option>
                    <?php
		mysqli_data_seek($res_country,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_country)) {
//                echo "<option value=\"{$row['country_id']}\">{$row['country']}</option>\n";
checklistmatch($row["country_id"], "country_id", $row["country"], "country", $fmrow["death_country_id"]);
                    }
                    ?>
                </select>
            </div>

            <label for="mother_member_id">Mother</label>
            <div class="fieldinput">
                <select name="mother_member_id" id="mother_member_id">
                    <option value=""></option>
                    <?php
		//mysqli_data_seek($res_country,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_mother)) {
//                echo "<option value=\"{$row['family_member_id']}\">{$row['full_name']}</option>\n";
checklistmatch($row['family_member_id'], "family_member_id", $row['full_name'], "full_name", $fmrow["mother_member_id"]);
                    }
                    ?>
                </select>
            </div>

            <label for="father_member_id">Father</label>
            <div class="fieldinput">
                <select name="father_member_id" id="father_member_id">
                    <option value=""></option>
                    <?php
		//mysqli_data_seek($res_country,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_father)) {
//                echo "<option value=\"{$row['family_member_id']}\">{$row['full_name']}</option>\n";
checklistmatch($row['family_member_id'], "family_member_id", $row['full_name'], "full_name", $fmrow["father_member_id"]);
                    }
                    ?>
                </select>
            </div>
            
            <label for="family_member_notes">Notes</label>
            <div class="fieldinput">
            <textarea name="family_member_notes" id="family_member_notes"><?php echo $fmrow["family_member_notes"]?></textarea>
			</div>
            
            <label for="access_level">Access Level</label>
            <div class="fieldinput">
            <select name="access_level" id="access_level">
            <option value="PUBLIC">Public</option>
            <option value="PRIVATE">Private</option>
            </select>
            </div>
            
            <?php 
            if ($to_edit == true) {
            	echo "<input type=\"hidden\" name=\"family_member_id\" value=" . 
              	$_GET["family_member_id"] . ">";
            }
            
            ?>
            
            <input type="submit" value="submit" name="submit" class="btn btn-default"/>


</form>
    </div><!-- /.container -->
    
    
                <!-- Modal -->
<!--  <div class="modal fade" id="stateform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog wide-modal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Add State</h4>
        </div>
        <div class="modal-body">
        <div class="container">
        <div class="row">
        <div class="col-md-12">
        		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        	<div class="form-group">
				<label for="state">Add state</label>
				<input type="text" class="form-control" id="state" placeholder="State" name="state">
			</div>
        		<button type="submit" class="btn btn-default" id="statesubmit">Submit</button>
        		
        		</form>
        
        </div>
        </div>
        </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
    <script src="<?php echo BOOTSTRAP_PATH; ?>assets/js/modal.js"></script>
-->  
    </body>
</html>