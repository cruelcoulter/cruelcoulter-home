<?php
session_start();

require 'functions.php';

if ( ! AmILoggedIn()) {
	$thispage = substr($_SERVER['PHP_SELF'], 1);
	header("Location:login.php?backto=" . $thispage);
}

require '../../db_config.php';
//pdo stores the connection in the variable $link
require "../../pdo.php";
/*
09/19/13 - fix formatting
10/06/13 - added navheader include
11/27/13 - moved to family folder. Format to match other files.
10/03/14 - added access_level column; applied htmlspecialchars to alt_text
12/10/14 - added closing select tag
10/02/15 - added tinymce
10/06/15 - remark out the htmlspechars. changed the query
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="utf-8" />
        <!-- added tinymce link 9/29/15 -->
        <script type="text/javascript" src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
		tinymce.init({
			plugins: ["link"],
			selector: "#alt_text"
		});
		</script>
<!-- Bootstrap core CSS -->
<link
	href="<?php echo BOOTSTRAP_PATH; ?>dist/css/bootstrap.css"
	rel="stylesheet">
<link
	href='http://fonts.googleapis.com/css?family=Source+Sans+Pro|Germania+One'
	rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="family.css">
<title>Upload files</title>

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
	href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/favicon.png">

</head>

<body>
<?php
        include 'navheader.php';
?>
<div class="container" style="padding-top:60px;">
<?php
        //include 'navheader.php';

        function convertfeature($featurepic) {
            /*if (strlen($featurepic))*/
						if (isset($_POST["feature_on_detail_page"])) {
                return 1;
            }
//					else {return "NULL";}
					else {return 0;}


        }

$linkmysqli = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD)
or die("<p>Error connecting: " . mysqli_error($linkmysqli) . "</p>");

mysqli_select_db($linkmysqli, DATABASE_NAME)
or die("<p>Error selecting: " . mysqli_error($linkmysqli) . "</p>");

$qry_family = "SELECT family_id, family_name from family order by family_name";

$res_family = mysqli_query($linkmysqli, $qry_family);

$qry_fam_mem = "SELECT family_member_id, concat_ws(' ', first_name, family_name) as full_name FROM family_member, family WHERE family.family_id = family_member.family_id";

$res_fam_mem = mysqli_query($linkmysqli, $qry_fam_mem);

//$alt_text = htmlspecialchars($_POST["alt_text"],ENT_QUOTES);

if (isset($_POST["submit"])){
    if (strlen($_POST["family_member_id"])){
        $family_member_id = $_POST["family_member_id"];
    } else {
        $family_member_id = '';
    }

    if (strlen($_POST["family_id"])){
        $family_id = $_POST["family_id"];
    } else {
        $family_id = '';
    }

    if (isset ($_POST["feature_on_detail_page"])) {
			$featurepic = 1;
		} else {
			$featurepic = 0;
		}
        //do something
if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br />";
  }
else
  {
//include 'navheader.php';
  echo "Upload: " . $_FILES["file"]["name"] . "<br />";
  echo "Type: " . $_FILES["file"]["type"] . "<br />";
  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
  echo "Stored in: " . $_FILES["file"]["tmp_name"];

       move_uploaded_file($_FILES["file"]["tmp_name"],
      "../family/attach/" . $_FILES["file"]["name"]);
      echo "Now Stored in: " . "attach/" . $_FILES["file"]["name"] . "<br>";

     /* $ins_attach_qry = "insert into attachment(attachment_location,
          attachment_title, alt_text, access_level) values ('attach/" .  $_FILES["file"]["name"]
              . "', '" . $_POST["attachment_title"] . "', '" . $alt_text . "', '" . $_POST["access_level"] . "')";
*/

      $stringattachpath =  "attach/" .  $_FILES["file"]["name"];

$ins_attach_qry = $link->prepare('insert into attachment(attachment_location,
          attachment_title, alt_text, access_level)
          values  (:stringattachpath
              ,:attachment_title, :alt_text, :access_level)');
              $ins_attach_qry->bindparam(':stringattachpath', $stringattachpath);
              $ins_attach_qry->bindparam(':attachment_title', $_POST["attachment_title"]);
              $ins_attach_qry->bindparam(':alt_text', $_POST["alt_text"]);
              $ins_attach_qry->bindparam(':access_level', $_POST["access_level"]);
              $ins_attach_qry->execute();
      //echo $ins_attach_qry . "<br>";

$attachment_id = $link->lastinsertid();

      //$res_attach = mysqli_query($linkmysqli, $ins_attach_qry);

//attachment gets associated with family OR family member
//9/20/13 changed $maxvalid to mysqli_insert_id()
//10/4/13 added $linkmysqli parameter to mysqli_insert_id()

      if (strlen($family_member_id)) {
/*      $insjuncqry = "insert into junc_family_member_attachment (family_member_id,
          attachment_id, feature_on_detail_page) values ( " . $family_member_id
          . ", " . mysqli_insert_id($linkmysqli) . ", " . convertfeature($featurepic) . ")";
*/
$insjuncqry = $link->prepare('insert into junc_family_member_attachment
(family_member_id, attachment_id, feature_on_detail_page)
          values (:family_member_id, :attachment_id, :feature_on_detail_page)');
          $insjuncqry->bindparam(':family_member_id', $family_member_id);
          $insjuncqry->bindparam(':attachment_id', $attachment_id);
          $insjuncqry->bindparam(':feature_on_detail_page', convertfeature($featurepic));
          $insjuncqry->execute();
} elseif (strlen($family_id)) {
     /* $insjuncqry = "insert into junc_family_attachment (family_id,
          attachment_id) values ( " . $family_id
          . ", " . mysqli_insert_id($linkmysqli) . ")";*/
          $insjuncqry = $link->prepare('insert into junc_family_attachment
          (family_id, attachment_id)
          values (:family_id, :attachment_id)');
          $insjuncqry->bindparam(':family_id',$family_id);
          $insjuncqry->bindparam(':attachment_id',$attachment_id);
          $insjuncqry->execute();
      }

  //echo $insjuncqry . "<br>";

  //$resjunc = mysqli_query($linkmysqli, $insjuncqry);

  mysqli_close($linkmysqli);
  }

    } else {
			echo "<p>Hey dum dum, don't forget you can only load images.</p>";

echo "<form action=\"file_upload.php\"
		name=\"uploadform\"
		method=\"post\"
		enctype=\"multipart/form-data\"
		enablecab=\"Yes\">";


//File Upload
echo "<div class=\"form-group\">";

echo "<label for=\"file\">File Upload</label>";

echo     "<input type=\"file\" name=\"file\" id=\"file\" /><br>";

echo "</div>";

//Family Member dropdown
echo "<div class=\"form-group\">";

echo "<label for=\"family_member_id\">Select a Family Member...</label>";

echo     "<select name=\"family_member_id\">
     <option value=\"\"></option>";


     while ($row = mysqli_fetch_array($res_fam_mem)) {
        echo "<option value=\"{$row['family_member_id']}\">
                    {$row['full_name']}</option>\n";
                    }

echo "</select>";

echo "</div>";

//Family Dropdown
echo "<div class=\"form-group\">";

echo "<label for=\"family_id\">... OR select a Family</label>";

echo "<select name=\"family_id\">
    <option value=\"\"></option>";

while ($famrow = mysqli_fetch_array($res_family)) {
    echo "<option value=\"{$famrow['family_id']}\">
        {$famrow['family_name']}</option>\n";
}

echo "</select>";

echo "</div>";


//attachment title
echo "<div class=\"form-group\">";

echo "<label for=\"attachment_title\">Title:</label> <input type=\"text\" name=\"attachment_title\" maxlength=\"150\" class=\"form-control\">";

echo "</div>";

//attachment alt
?>
<div class="form-group">

<label for="alt_text">Alt text:</label>
<textarea name="alt_text" id="alt_text" class="form-control" rows="3"></textarea>

</div>
<?php
//access level added 10/3/14
echo "<div class=\"form-group\">";

echo "<label for=\"access_level\">Public or Private?</label>";

echo "<select name=\"access_level\" id=\"access_level\">
    <option value=\"PUBLIC\">PUBLIC</option><option value=\"PRIVATE\">PRIVATE</option>";

echo "</select>\n";

echo "</div>\n";


echo          		"<div class=\"checkbox\">
    <label><input type=\"checkbox\" name=\"feature_on_detail_page\">Display on main page? </label>
          		</div>
    <input type=\"submit\" value=\"submit\" name=\"submit\" class=\"btn btn-default\">
        </form>\n";
    }
?>
    </div>
    </body>
</html>
