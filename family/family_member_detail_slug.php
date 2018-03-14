<?php
session_start();

require 'functions.php';

require '../../db_config.php';

require '../../pdo.php';

/*
family_member_detail_slug.php. Reworking of family_member_detail.php to use slug instead of id
IGNORE This page it's junk
TO DO: fix the family_member_id_other issue
02/18/18 - initial version
@@@@@@@@@@@@@@@@@@@@@@@@@@

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%555


This page is not used!!





*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
    <link href="<?php echo BOOTSTRAP_PATH; ?>dist/css/bootstrap.css" rel="stylesheet">
    <!-- Add this link to all pages -->
    <link href="<?php echo BOOTSTRAP_PATH; ?>dist/css/bootstrap-theme.css" rel="stylesheet">
	<link href='//fonts.googleapis.com/css?family=Source+Sans+Pro|Germania+One' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="family.css">
	<link rel="stylesheet" type="text/css" href="navbar-fixed-top.css">
<title>Coulter/Kennedy/Raible/Pettibone Family History</title>

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
</head>

<body>

<?php include 'familynavbar.php';?>

<?php

$family_member_slug_temp = (isset($_GET["family_member_slug"]) ? $_GET["family_member_slug"] : "");
//do a regex to make sure the slug only contains [-A-Za-z0-9]
if (strlen($family_member_slug_temp) < 200) {
	$family_member_slug = $family_member_slug_temp;
	} else {
	$family_member_slug = 'carl-coulter-1891-1974';
	}

if (isset($_SESSION['IsLoggedIn']) AND $_SESSION['IsLoggedIn'] == true)
{
$query_text = "select * from family_member where family_member_slug = :family_member_slug";
}
else
{
$query_text = "select * from family_member where family_member_slug = :family_member_slug AND access_level ='PUBLIC'";
}
$querytext = $link->prepare($query_text);
$querytext->execute(array(':family_member_slug' => $family_member_slug));

$result = $querytext->fetchAll();

if (isset($_SESSION['IsLoggedIn']) AND $_SESSION['IsLoggedIn'] == true)
{
$attach_query = "select * from attachment where attachment_id in (select attachment_id from
junc_family_member_attachment where family_member_slug = :family_member_slug and feature_on_detail_page IS NULL)";
}
else
{
  $attach_query = "select * from attachment where access_level = 'PUBLIC' AND attachment_id in (select attachment_id from
junc_family_member_attachment where family_member_slug = :family_member_slug and feature_on_detail_page IS NULL)";
}
$attachquery = $link->prepare($attach_query);
$attachquery->execute(array(':family_member_slug' => $family_member_slug));
$attachresult = $attachquery->fetchAll();

$link_query = "select link.*, junc_family_member_link.family_member_id  from link,
    junc_family_member_link where junc_family_member_link.link_id = link.link_id and
    junc_family_member_link.family_member_slug = :family_member_slug";

$linkquery = $link->prepare($link_query);
$linkquery->execute(array(':family_member_slug' => $family_member_slug));
$link_result = $linkquery->fetchAll();

//event query. Added 4/8/15
if (isset($_SESSION['IsLoggedIn']) AND $_SESSION['IsLoggedIn'] == true)
{
$event_query = "select * from event where family_member_slug = :family_member_slug OR
family_member_id_other = :family_member_slug ORDER BY event_year";
}
else
{
$event_query = "select * from event where access_level = 'PUBLIC' AND
(family_member_id = :family_member_id OR family_member_id_other = :family_member_slug) ORDER BY event_year";
}
$eventquery = $link->prepare($event_query);
$eventquery->execute(array(':family_member_id' => $family_member_id));
$eventresult = $eventquery->fetchAll();


?>
<!--
***************************
temp. don't move to production
***************************
 -->
<?php echo basename($_SERVER['PHP_SELF']); ?>

<div class="bodyarea">
<div class="container">

<div class="row">
<div class="col-md-6">
<div class="familytable">
 <!-- wrap elements in a p tag for a better look -->

<?php
//while ($row = mysqli_fetch_array($result)) {
foreach ($result as $row) :
	echo "<div class=\"row\">\n";
	echo "<div class=\"col-md-4\">\n";
	echo "Name:\n";
	echo "</div>\n";
	echo "<div class=\"col-md-8\">\n";
	//added marriednamepdo function call 4/7/15. Be sure to move functions.php to server
    echo "<p>{$row['first_name']}" . getmarriednamepdo($row['married_name_id'], $link) . getlastnamepdo($row['family_id'], $link) . "</p>\n";
	echo "</div>\n";
	echo "</div>\n";

	echo "<div class=\"row\">\n";
	echo "<div class=\"col-md-4\">\n";
	echo "Place of Birth:\n";
	echo "</div>\n";
	echo "<div class=\"col-md-8\">\n";
	echo buildplacenamepdo($row['birth_city_twp_id'], $row['birth_county_id'], $row['birth_state_id'], $row['birth_country_id'], $link);
	echo "</div>\n";
	echo "</div>\n";

	echo "<div class=\"row\">\n";
	echo "<div class=\"col-md-4\">\n";
	echo "Place of Death:\n";
	echo "</div>\n";
	echo "<div class=\"col-md-8\">\n";
	echo buildplacenamepdo($row['death_city_twp_id'], $row['death_county_id'], $row['death_state_id'], $row['death_country_id'], $link);
	echo "</div>\n";
	echo "</div>\n";

	echo "<div class=\"row\">\n";
	echo "<div class=\"col-md-4\">\n";
	echo "Father:\n";
	echo "</div>\n";
	echo "<div class=\"col-md-8\">\n";

	echo getfatherpdoslug($row['father_member_id'], $row['family_id'], $link);

	echo "</div>\n";
	echo "</div>\n";

	echo "<div class=\"row\">\n";
	echo "<div class=\"col-md-4\">\n";
	echo "Mother:\n";
	echo "</div>\n";
	echo "<div class=\"col-md-8\">\n";
	echo getmotherpdo($row['mother_member_id'], $row['family_id'], $link);
	echo "</div>\n";
	echo "</div>\n";


	echo "<div class=\"row\">\n";
	echo "<div class=\"col-md-4\">\n";
	echo "Notes:\n";
	echo "</div>\n";
	echo "<div class=\"col-md-8\">\n";
	echo "<p>{$row['family_member_notes']}</p>\n";
	echo "</div>\n";
	echo "</div>\n";

endforeach;
?>
</div><!-- /familytable -->
</div><!-- /.col-md-6 -->


<div class="col-md-6">
<div id="imagearea">
<?php

//echo "familymemberid: " . $family_member_id;

$picture_query = "select a.*, fma.feature_on_detail_page from attachment a, junc_family_member_attachment fma where a.attachment_id =
fma.attachment_id and family_member_slug = :family_member_slug and feature_on_detail_page = 1";

$picturequery = $link->prepare($picture_query);
$picturequery->execute(array(':family_member_id' => $family_member_id));

if($picturequery) {
$picture_result = $picturequery->fetchAll();
	foreach ($picture_result as $picture_row) :
	echo "<img class=\"img-responsive\" src=\"{$picture_row['attachment_location']}\" alt=\"{$picture_row['alt_text']}\">\n";
	endforeach;
} else {
	echo "&nbsp;";
}

?>
</div><!-- /#imagearea -->
</div><!-- /.col-md-6  -->
</div><!-- /.row -->

<div class="row">
<div class="col-md-12">
<div id="linkarea">
<?php
if ($attachresult) {
	foreach ($attachresult as $attach_row) :
	echo "<a href=\"attachment.php?attachment_id={$attach_row['attachment_id']}&family_member_id="
	. $family_member_id . "\">{$attach_row['attachment_title']}</a><br>\n";
	endforeach;
} else {
	echo "&nbsp;";
	}


if (!$link_result) {
	echo "&nbsp;";
} else {
	foreach ($link_result as $link_row) :
	echo "<p><a href=\"" . $link_row['link_url'] . "\">" . $link_row['link_title'] . "</a></p>\n";
	endforeach;
}

if ($eventresult) {
  echo "<h3>Events</h3>";
  foreach ($eventresult as $eventrow) :
       echo "<h4>" . $eventrow['event_name'] . "</h4>\n";
       echo "<p>" . $eventrow['event_detail'] . "</p>\n";

       if ($eventrow['event_date']) {
         echo "<p>" . $eventrow['event_date'] . "</p>\n";
       } else {
         if ($eventrow['event_year']) {
           echo "<p>" . $eventrow['event_year'] . "</p>\n";
         }
       }

  endforeach;
}

unset($attachquery);
unset($attachresult);
unset($link_query);
unset($link_result);
unset($picture_query);
unset($picture_result);
unset($picturequery);
unset($query_text);
unset($querytext);
unset($result);
unset($eventresult);
?>
</div><!-- /#linkarea -->
</div><!-- /.col-md-12 -->
</div> <!-- /.row -->


</div><!-- /.container -->
</div><!-- /.bodyarea -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo BOOTSTRAP_PATH; ?>dist/js/bootstrap.min.js"></script>
<?php include './includes/footer.php'; ?>

</body>
</html>
