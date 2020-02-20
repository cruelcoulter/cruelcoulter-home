<?php

/*

09/06/12 - initial version

05/03/13 - added image display

09/28/14 - separate queries for public and private . update doctype declaration

01/02/15 - begin applying bootstrap

01/03/15 - added jumbotron, family member info

04/10/15 - add amiloggedin function call

TODO: get links on here

*/

session_start();

require '../../db_config.php';



include_once 'functions.php';



$link = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD)

or die("<p>Error connecting: " . mysqli_error($link) . "</p>");



mysqli_select_db($link, DATABASE_NAME)

or die("<p>Error selecting: " . mysqli_error($link) . "</p>");



$family_id_temp = $_GET["family_id"];



if ((ctype_digit($family_id_temp)) && (strlen($family_id_temp) < 10)) {

	$family_id = $family_id_temp;

	} else {

	$family_id = 1;

	}





$query_text = "select * from family where family_id = " . $family_id;



if (isset($_SESSION['IsLoggedIn']) AND $_SESSION['IsLoggedIn'] == true)

{

/* left off  - add family id to where clause*/

$family_query_text = "select family_member.family_member_id, family_member.first_name, UPPER(family.family_name) as family_name, f2.family_name as married_name, (concat (coalesce(family_member.birthdate_est, year(family_member.birthdate), '?'), ' - ', coalesce(family_member.deathdate_est, year(family_member.deathdate), '?'))) as birthdeath, family.family_id from family_member LEFT JOIN family ON family_member.family_id = family.family_id LEFT JOIN family f2 ON family_member.married_name_id = f2.family_id WHERE family.family_id = " .$family_id. "  order by family_member.family_id, birthdeath";



$attach_query = "select * from attachment where attachment_id in (select attachment_id from

junc_family_attachment where family_id = " . $family_id . ")";



$image_query = "select * from attachment where attachment_id in (select attachment_id from

junc_family_attachment where family_id = " . $family_id . " and isimage = 1)";



} else

{
<?PHP require 'include_fonts_css.php'; ?>
/* left off  - add family id to where clause*/

$family_query_text = "select family_member.family_member_id, family_member.first_name, UPPER(family.family_name) as family_name, f2.family_name as married_name, (concat (coalesce(family_member.birthdate_est, year(family_member.birthdate), '?'), ' - ', coalesce(family_member.deathdate_est, year(family_member.deathdate), '?'))) as birthdeath, family.family_id from family_member LEFT JOIN family ON family_member.family_id = family.family_id LEFT JOIN family f2 ON family_member.married_name_id = f2.family_id WHERE family_member.access_level = 'PUBLIC' AND family.family_id = " .$family_id. " order by family_member.family_id, birthdeath";



$attach_query = "select * from attachment where access_level = 'PUBLIC' and attachment_id in (select attachment_id from

junc_family_attachment where family_id = " . $family_id . ")";



$image_query = "select * from attachment where access_level = 'PUBLIC' and attachment_id in (select attachment_id from

junc_family_attachment where family_id = " . $family_id . " and isimage = 1)";

}



$family_result = mysqli_query($link, $family_query_text);



$result = mysqli_query($link, $query_text);



$attach_result = mysqli_query($link, $attach_query);



$image_result = mysqli_query($link, $image_query);



if(!$result) {

	die("<p>Query Error: " . mysqli_error($link) . "</p>");

}



if(!$family_result) {

	die("<p>Query Error: " . mysqli_error($link) . "</p>");

}



if(!$attach_result) {

	die("<p>Query Error: " . mysqli_error($link) . "</p>");

}



if(!$image_result) {

	die("<p>Query Error: " . mysqli_error($link) . "</p>");

}



$family_row = mysqli_fetch_array($result,MYSQLI_ASSOC);



?>

<!DOCTYPE html>

<html lang="en">



<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="utf-8" />

    <!-- Bootstrap core CSS -->

    <link href="<?php echo BOOTSTRAP_PATH; ?>dist/css/bootstrap.css" rel="stylesheet">

	

	

	<link rel="stylesheet" type="text/css" href="navbar-fixed-top.css">

<title><?php echo "{$family_row['family_name']} "   ?>Family Page</title>

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

<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-144-precomposed.png" >

<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-114-precomposed.png">

<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-72-precomposed.png">

<link rel="apple-touch-icon-precomposed" href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-57-precomposed.png">

<link rel="shortcut icon" href="../includes/favicon.png">



<style type="text/css" media="all">

.familytable {

display: table;

}

.familyrow {

display: table-row;

}

.familycell {

display: table-cell;

}

.familylabel {

font-weight:bold;

}

.familydata {

}



#imagearea {

float: left;

margin-left: 20px;

margin-top: 50px;

}



#textarea {

float: left;

margin-left: 30px;

margin-top: 50px;

}



#linkarea {

clear: both;

}

</style>

</head>



<body>

<?php include "familynavbar_v4.php"; ?>

<div class="container">

<div class="jumbotron">

<?php echo "<h1>{$family_row['family_name']}</h1>" ?>

<?php echo "<p>{$family_row['family_notes']}</p>" ?>

</div>

<div class="row">

<?php

echo "<ul>\n";



while ($row = mysqli_fetch_array($family_result)) {



	echo "<li><a href=\"family_detail.php?family_member_id={$row['family_member_id']}\">

	{$row['first_name']} {$row['family_name']} {$row['married_name']}</a> {$row['birthdeath']}&nbsp;";

    if (AmILoggedIn()) {

      echo "<a href=\"family_member_manage.php?family_member_id={$row['family_member_id']}\">Edit</a></li>\n";

    } else {

    echo "</li>\n";

    }

}

echo "</ul>\n";

?>

</div>

<!--<div id="textarea">-->

<div class="row">

<?php

//added 5/3/13

//mysqli_data_seek($result, 0);

while ($image_row = mysqli_fetch_array($image_result)) {

echo "<img src = \"" . $image_row['attachment_location'] . "\" alt=\"" . $image_row['alt_text'] . "\"><br />\n";

echo "<p>{$image_row['attachment_title']}</p>\n";

}



?>



</div><!-- /row -->



<!--</div> /textarea-->



<div class="row">

<?php

//mysqli_data_seek($result, 0);

//while ($row = mysqli_fetch_array($result)) {

//echo "<p>{$row['family_notes']}</p>\n";

//}



//while ($attach_row = mysqli_fetch_array($attach_result)) {

//echo "<a href=\"attachment.php?attachment_id={$attach_row['attachment_id']}&family_id="

//. $family_id . "\">{$attach_row['attachment_title']}</a><br>\n";

//}



//while ($link_row = mysqli_fetch_array($link_result)) {

//echo "<a href=\"" . $link_row['link_url'] . "\">" . $link_row['link_title'] . "</a>";

//}

?>

</div>

</div><!-- /.container -->

<?php

mysqli_free_result($result);



mysqli_free_result($attach_result);



mysqli_free_result($image_result);



mysqli_close($link);

?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script src="<?php echo BOOTSTRAP_PATH; ?>dist/js/bootstrap.min.js"></script>



</body>

</html>

