<?php
session_start();
require 'functions.php';
if ( ! AmILoggedIn()) {
	$thispage = substr($_SERVER['PHP_SELF'], 1);
	header("Location:login.php?backto=" . $thispage);
}
require '../../db_config.php';
require '../../pdo.php';
/* 
10/06/13 - added navheader include
10/21/13 - converted to twitter bootstrap
10/24/13 - added jquery link (failed attempt)
11/02/13 - added status column
10/04/14 - Renamed status -> access_level. capitalized public and private
Feb 2020 - Converted to Bootstrap. Added tinymce. Added family_member_slug
Mar 2020 - I don't know whether values will come in via GET or POST. Change all to REQUEST???
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
<meta charset="utf-8" />
        <!-- added tinymce link 9/29/15 -->
        <script type="text/javascript" src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
		tinymce.init({
			plugins: ["link"],
			selector: "#family_member_notes"
		});
		</script>

<!-- Bootstrap core CSS -->
<?PHP require 'include_fonts_css.php'; ?>
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
<?php include 'navheader.php'; ?>
<div class="container">
<?php
$mysqlilink = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD)
or die("<p>Error connecting: " . mysqli_error($mysqlilink) . "</p>");
mysqli_select_db($mysqlilink, DATABASE_NAME)
or die("<p>Error selecting: " . mysqli_error($mysqlilink) . "</p>");
/* try {
	$pdolink = new PDO('mysql:host=localhost;dbname=cruelcou_family', DATABASE_USERNAME,DATABASE_PASSWORD);
}
catch (PDOException $e)
{
	echo $e->getMessage();
}
 */
$query_text = "select * from family order by family_name";
$result = mysqli_query($mysqlilink, $query_text);
$qry_city_twp = "select * from city_twp order by city_name";
$res_city_twp = mysqli_query($mysqlilink, $qry_city_twp);
$qry_state = "select * from state order by state";
$res_state = mysqli_query($mysqlilink, $qry_state);
$qry_county = "select * from county order by county";
$res_county = mysqli_query($mysqlilink, $qry_county);
$qry_country = "select * from country order by country";
$res_country = mysqli_query($mysqlilink, $qry_country);
//$qry_mother = "SELECT family_member_id, concat_ws(' ', first_name, family_name) as full_name FROM family_member, family WHERE family.family_id = family_member.family_id";
$qry_mother = "SELECT family_member_id, family_member_slug FROM family_member, family WHERE family.family_id = family_member.family_id and gender='female' order by last_name, first_name";
$res_mother = mysqli_query($mysqlilink, $qry_mother);
//$qry_father = "SELECT family_member_id, concat_ws(' ', first_name, family_name) as full_name FROM family_member, family WHERE family.family_id = family_member.family_id";
$qry_father = "SELECT family_member_id, family_member_slug  FROM family_member, family WHERE family.family_id = family_member.family_id and gender='male' order by last_name, first_name";
$res_father = mysqli_query($mysqlilink, $qry_father);

//check logic here
//if the fmi is defined and it's a GET, you've clicked a link and are getting ready to edit an existing family membeer
if (!empty($_GET["family_member_id"])) {
  $fmquery_text = "select * from family_member where family_member_id = " .
		htmlspecialchars($_GET["family_member_id"]);
  $fmresult = mysqli_query($mysqlilink, $fmquery_text);
  $fmrow = mysqli_fetch_array($fmresult);
  $adding=false;
  $editing=false;
  $to_add = false;
  $to_edit = true;
  $family_member_id=htmlspecialchars($_GET["family_member_id"]);
}
//if the fmi is defined and it's a post, you are editing an existing family member. the form should not display
if (isset($_POST["family_member_id"])) {
    $adding = false;
    $editing = true;
    $to_add = false;
    $to_edit = false;
} 
//if fmi is not defined and it's a post, you are adding a family member. The form should not display.
if (($_SERVER['REQUEST_METHOD'] === 'POST') &&  (!isset($_POST["family_member_id"])))  {
  $adding = true;
  $editing = false;
  $to_add = false;
  $to_edit = false;
}
//if fmi is not defined and it's a get, you are getting ready to add a family member. The form should display.
if (!isset($_REQUEST["family_member_id"]) && ($_SERVER['REQUEST_METHOD'] === 'GET')){
  $adding = false;
  $editing = false;
  $to_add = true;
  $to_edit = false;

}


if(!$result) {
	die("<p>Query Error: " . mysqli_error($mysqlilink) . "</p>");
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
    if (strlen($fmrow[$ff])) 
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
$family_member_slug = (isset($_POST["family_member_slug"]) ? $_POST["family_member_slug"] : "");
}
// 4/25/13 - added quotes around birthdate and deathdate in the insert and update queries
if ($adding == true) {
require_once 'family_member_manage_insert_query_mysqli.php';    
echo $ins_qry_text;
$res_qry_text = mysqli_query($mysqlilink, $ins_qry_text);
mysqli_close($mysqlilink);
//redirect to reload
//header("Location: family_member_manage.php");
}
////////
//EDITS
///////
if ($editing == true) {
//include 'family_member_manage_edit_query_pdo.php';
// not sure if I got the right syntax for married name id
$data = [	
 'first_name'=>$first_name,
 'family_id'=>$family_id,
 'last_name'=>$last_name,
 'gender'=>$gender,
 'married_name'=>$married_name,
 'married_name_id'=> !empty($married_name_id) ? $married_name_id : null,
 'birthdate_est'=>$birthdate_est,
 'birthdate'=>$birthdate,
 'birth_city_twp_id'=> !empty($birth_city_twp_id) ? $birth_city_twp_id : null,
 'birth_state_id'=> !empty($birth_state_id) ? $birth_state_id : null,
 'birth_county_id'=> !empty($birth_county_id) ? $birth_county_id : null,
 'birth_country_id'=> !empty($birth_country_id) ? $birth_country_id : null,
 'deathdate_est'=>$deathdate_est,
 'deathdate'=>$deathdate,
 'death_city_twp_id'=> !empty($death_city_twp_id) ? $death_city_twp_id : null ,
 'death_state_id'=> !empty($death_state_id) ? $death_state_id : null,
 'death_county_id'=> !empty($death_county_id) ? $death_county_id: null,
 'death_country_id'=> !empty($death_country_id) ? $death_country_id : null,
 'mother_member_id'=> !empty($mother_member_id) ? $mother_member_id: null,
 'father_member_id'=> !empty($father_member_id) ? $father_member_id: null,
 'family_member_notes'=>$family_member_notes,
 'family_member_slug'=>$family_member_slug,
 'family_member_id'=>$family_member_id,
 'access_level'=>$access_level,
 ];
$sql = "update family_member set
 first_name=:first_name,
 family_id=:family_id,
 last_name=:last_name,
 gender=:gender,
 married_name=:married_name,
 married_name_id=:married_name_id,
 birthdate_est=:birthdate_est,
 birthdate=:birthdate,
 birth_city_twp_id=:birth_city_twp_id,
 birth_state_id=:birth_state_id,
 birth_county_id=:birth_county_id,
 birth_country_id=:birth_country_id,
 deathdate_est=:deathdate_est,
 deathdate=:deathdate,
 death_city_twp_id=:death_city_twp_id,
 death_state_id=:death_state_id,
 death_county_id=:death_county_id,
 death_country_id=:death_country_id,
 mother_member_id=:mother_member_id,
 father_member_id=:father_member_id,
 family_member_notes=:family_member_notes,
 family_member_slug=:family_member_slug,
 access_level=:access_level
where family_member_id =:family_member_id";
$stmt = $link->prepare($sql);
$stmt -> execute($data);
$errorInfo = $stmt->errorInfo();
if (isset($errorInfo[2])) {
    $error = $errorInfo[2];
echo $error;
}
echo "Editing: ", $editing;
echo " Adding: ", $adding;
echo " To edit: ", $to_edit;
echo " To add: ", $to_add , "<br />";

//redirect to reload
//header("Location: family_member_manage.php");
} //if edditing true
if ($editing == true || $adding == true ){
     include 'include_js.php';
echo "<script src=\"build_slug_ajax.js\"></script>";
echo "</body>";
echo "</html>";
    exit();
}
?>
  <h3>Format for dates is YYYY-MM-DD</h3>
  <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
  <div class="form-row"> <!-- row 1 -->
  <div class="form-group col-md-4">
      <label for="first_name">First Name</label>
      
      <input class="form-control" name="first_name" id="first_name" type="text" maxlength="20"
             <?php if ($to_edit === true) {returnff("first_name", $to_edit, $fmrow);} ?> />
  </div>

  <div class="form-group col-md-4">
      <label for="family_id">Last Name</label>
          <select class="form-control" name="family_id" id="family_id">
              <option value=""></option>
              <?php 
              while ($row = mysqli_fetch_array($result)) {
                  checklistmatch($row["family_id"], "family_id", $row["family_name"], "family_name", $fmrow["family_id"]);
              }
              ?>
          </select>
      </div>

      <div class="form-group col-md-2">
      <label for="gender">Gender</label>
          <select class="form-control" name="gender" id="gender">
          <option value=""></option>
          <?php
          if (!empty($fmrow["gender"]) && $fmrow["gender"] == "male") { 
              echo "<option value=\"male\" selected>male</option>";
          } else {
      echo "<option value=\"male\">male</option>";
  }
  
  if (!empty($fmrow["gender"]) && $fmrow["gender"] == "female") {
    echo "<option value=\"female\" selected>female</option>";
  } else {
    echo "<option value=\"female\">female</option>";
  }
          ?>
          </select>
      </div>

      <div class="form-group col-md-2">
      <label for="married_name_id">Married Name</label>
          <select class="form-control" name="married_name_id" id="married_name_id">
              <option value=""></option>
              <?php
              mysqli_data_seek($result,0); //have to restart at the beginning
              while ($row = mysqli_fetch_array($result)) {
              checklistmatch($row["family_id"], "family_id", $row["family_name"], "family_name", $fmrow["married_name_id"]);
              }
              ?>
          </select>
      </div>

      </div> <!-- //row 1 -->

      <div class="form-row"> <!-- row 2 -->

      <div class="form-group col-md-2">
      <label for="birthdate_est">Est Birth Date</label>
      <input class="form-control" name="birthdate_est" id="birthdate_est" type="text" maxlength="4" placeholder="YYYY (if known)" 
             <?php if ($to_edit === true) {returnff("birthdate_est", $to_edit, $fmrow);} ?>/>
      </div>

      <div class="form-group col-md-2">
      <label for="birthdate">Birth Date</label>
      <input class="form-control" name="birthdate" id="birthdate" type="text" maxlength="25" placeholder="YYYY-MM-DD" 
           <?php if ($to_edit === true) {returnff("birthdate", $to_edit, $fmrow);} ?>  />
      </div>

      <div class="form-group col-md-2">
      <label for="birth_city_twp_id">Birthplace (city/twp)</label>
          <select class="form-control" name="birth_city_twp_id" id="birth_city_twp_id">
              <option value=""></option>
              <?php
              while ($row = mysqli_fetch_array($res_city_twp)) {
                  checklistmatch($row["city_twp_id"], "city_twp_id", $row["city_name"], "city_name", $fmrow["birth_city_twp_id"]);
              }
              ?>
          </select>
      </div>

      <div class="form-group col-md-2">
      <label for="birth_state_id">Birthplace (State)</label>
          <select class="form-control" name="birth_state_id" id="birth_state_id">
              <option value=""></option>
              <?php
              while ($row = mysqli_fetch_array($res_state)) {
                  checklistmatch($row["state_id"], "state_id", $row["state"], "state", $fmrow["birth_state_id"]);
                  }
              ?>
          </select>
      </div>

      <div class="form-group col-md-2">
      <label for="birth_county_id">Birthplace (County)</label>
          <select class="form-control" name="birth_county_id" id="birth_county_id">
              <option value=""></option>
              <?php
              while ($row = mysqli_fetch_array($res_county)) {
                  checklistmatch($row["county_id"], "county_id", $row["county"], "county", $fmrow["birth_county_id"]);
              }
              ?>
          </select>
      </div>

      <div class="form-group col-md-2">
      <label for="birth_country_id">Birthplace (Country)</label>
          <select class="form-control" name="birth_country_id" id="birth_country_id">
              <option value=""></option>
              <?php
              while ($row = mysqli_fetch_array($res_country)) {
                  checklistmatch($row["country_id"], "country_id", $row["country"], "country", $fmrow["birth_country_id"]);
              }
              ?>
          </select>
      </div>

      </div> <!-- //row 2 -->


      <div class="form-row"> <!-- row 3 -->

      <div class="form-group col-md-2">
      <label for="deathdate_est">Est Death Date</label>
      <input class="form-control" name="deathdate_est" id="deathdate_est" type="text" maxlength="4" placeholder="YYYY (if known)" 
             <?php if ($to_edit === true) {returnff("deathdate_est", $to_edit, $fmrow);} ?>/>
      </div>

      <div class="form-group col-md-2">
      <label for="deathdate">Death Date</label>
      <input class="form-control" name="deathdate" id="deathdate" type="text" maxlength="25" placeholder="YYYY-MM-DD" 
           <?php if ($to_edit === true) {returnff("deathdate", $to_edit, $fmrow);} ?>  />
      </div>

      <div class="form-group col-md-2">
      <label for="death_city_twp_id">Deathplace (city/twp)</label>
          <select class="form-control" name="death_city_twp_id" id="death_city_twp_id">
              <option value=""></option>
              <?php
mysqli_data_seek($res_city_twp,0); //have to restart at the beginning
              while ($row = mysqli_fetch_array($res_city_twp)) {
                  checklistmatch($row["city_twp_id"], "city_twp_id", $row["city_name"], "city_name", $fmrow["death_city_twp_id"]);
              }
              ?>
          </select>
      </div>

      <div class="form-group col-md-2">
      <label for="death_state_id">Deathplace (State)</label>
          <select class="form-control" name="death_state_id" id="death_state_id">
              <option value=""></option>
              <?php
mysqli_data_seek($res_state,0); //have to restart at the beginning
              while ($row = mysqli_fetch_array($res_state)) {
              checklistmatch($row["state_id"], "state_id", $row["state"], "state", $fmrow["death_state_id"]);
              }
              ?>
          </select>
      </div>

      <div class="form-group col-md-2">
      <label for="death_county_id">Deathplace (County)</label>
          <select class="form-control" name="death_county_id" id="death_county_id">
              <option value=""></option>
              <?php
mysqli_data_seek($res_county,0); //have to restart at the beginning
              while ($row = mysqli_fetch_array($res_county)) {
              checklistmatch($row["county_id"], "county_id", $row["county"], "county", $fmrow["death_county_id"]);
              }
              ?>
          </select>
      </div>

      <div class="form-group col-md-2">
      <label for="death_country_id">Deathplace (Country)</label>
          <select class="form-control" name="death_country_id" id="death_country_id">
              <option value=""></option>
              <?php
          mysqli_data_seek($res_country,0); //have to restart at the beginning
              while ($row = mysqli_fetch_array($res_country)) {
              checklistmatch($row["country_id"], "country_id", $row["country"], "country", $fmrow["death_country_id"]);
              }
              ?>
          </select>
      </div>

      </div> <!-- //row 3 -->

      <div class="form-row"> <!-- row 4 -->

      <div class="form-group col-md-3">
      <label for="mother_member_id">Mother</label>
          <select class="form-control" name="mother_member_id" id="mother_member_id">
              <option value=""></option>
              <?php
              while ($row = mysqli_fetch_array($res_mother)) {
                if(!empty($fmrow["mother_member_id"])) {
                    checklistmatch($row['family_member_id'], "family_member_id", $row['family_member_slug'], "family_member_slug", $fmrow["mother_member_id"]);
                } else {
                    echo "<option value=\"" . $row['family_member_id'] . "\">" . $row['family_member_slug'] . "</option>\n";
                }
              }
              ?>
          </select>
      </div>

      <div class="form-group col-md-3">
      <label for="father_member_id">Father</label>
          <select class="form-control" name="father_member_id" id="father_member_id">
              <option value=""></option>
              <?php
              while ($row = mysqli_fetch_array($res_father)) {
                if(!empty($fmrow["father_member_id"])) {
                  checklistmatch($row['family_member_id'], "family_member_id", $row['family_member_slug'], "family_member_slug", $fmrow["father_member_id"]);
                } else {
                    echo "<option value=\"" . $row['family_member_id'] . "\">" . $row['family_member_slug'] . "</option>\n";
                }
              }
              ?>
          </select>
      </div>
      
      <div class="form-group col-md-6">
      <label for="family_member_notes">Notes</label>
      <textarea class="form-control" name="family_member_notes" id="family_member_notes"><?php if ($to_edit === true) {echo $fmrow["family_member_notes"];}?></textarea>
</div>
      
      </div> <!-- //row 4 -->

      <div class="form-row"> <!-- row 5 -->
      <div class="form-group col-md-6">
      <label for="family_member_slug">Slug</label>
      <input type="text" class="form-control" id="family_member_slug" name="family_member_slug" value="<?php if ($to_edit === true) {echo $fmrow["family_member_slug"];} ?>" />
      <button type="button" value="build-slug" name="build-slug" class="btn" onclick="buildslug()">Build Slug</button>
      </div>

      
      <div class="form-group col-md-6">
          <label for="access_level">Access Level</label>
          <select class="form-control" name="access_level" id="access_level">
          <option value="PUBLIC">Public</option>
          <option value="PRIVATE">Private</option>
          </select>
          </div>
      </div>    <!-- //row 5 -->
      
      <?php 
      if ($to_edit == true) {
        echo "<input type=\"hidden\" name=\"family_member_id\" value=" . $family_member_id . ">";
      }
      
      ?>
      
      <button type="submit" value="submit" name="submit" class="btn btn-primary">Submit</button>


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
<?php include 'include_js.php'; ?>
		<script src="build_slug_ajax.js"></script>
    </body>
</html>