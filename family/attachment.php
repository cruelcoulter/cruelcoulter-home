<?php
session_start();

require 'functions.php';

require '../../db_config.php';

require '../../pdo.php';

/*
07/27/12 - version 0
09/02/12 - added logic to process family_member_id and family_member as get params
09/18/13 - bootstrap 3.0 support; html5
10/20/13 - added caption
11/23/13 - changed hardcoded bootstrap path to constant defined in db_config.php. Added google_script include.
11/25/13 - added ternary operator test for $_GET values
12/01/13 - convert to pdo
12/30/14 - css and js links
04/13/15 - add tag query, date posted
04/22/15 - add PDF handling
09/27/15 - add alt text display
09/29/15 - add img-responsive class, figure and figcaption
10/07/15 - escaped the alt text
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
    <link href="<?php echo BOOTSTRAP_PATH; ?>dist/css/bootstrap.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro|Germania+One' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="family.css">
	<link rel="stylesheet" type="text/css" href="navbar-fixed-top.css">
<title>Family History - Attachments</title>

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
<link rel="shortcut icon" href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/favicon.png">

</head>

<body>

<?php

$attachment_id_temp = (isset($_GET["attachment_id"]) ? $_GET["attachment_id"] : "");
if ((ctype_digit($attachment_id_temp)) && (strlen($attachment_id_temp) < 10)) {
	$attachment_id = $attachment_id_temp;
	} else {
	$attachment_id = 1;
	}

$family_member_id_temp = (isset($_GET["family_member_id"]) ? $_GET["family_member_id"] : "");
if ((ctype_digit($family_member_id_temp)) && (strlen($family_member_id_temp) < 10)) {
	$family_member_id = $family_member_id_temp;
	} else {
	$family_member_id = "";
	}

$family_id_temp = (isset($_GET["family_id"]) ? $_GET["family_id"] : "");
if ((ctype_digit($family_id_temp)) && (strlen($family_id_temp) < 10)) {
	$family_id = $family_id_temp;
	} else {
	$family_id = "";
	}

$sql = "select * from attachment where attachment_id = :attachment_id";

$querytext = $link->prepare($sql);

$querytext->execute(array(':attachment_id' => $attachment_id));

$result = $querytext->fetchAll();

if(!$result) {
	print_r($result->errorInfo());
}

$tagtext = "select tag_text, tag_id from tag where tag_id IN (select tag_id from junc_tag where ref_id = :attachment_id and ref_type = 'attachment')";
$tagquery = $link->prepare($tagtext);
$tagquery->execute(array(':attachment_id' => $attachment_id));
$tagresult = $tagquery->fetchAll();


?>

<?php include 'familynavbar.php';?>

<div class="container">
<div id="textarea">
<div class="familytable">


<?php foreach ($result as $row) : ?>
<?php if ($row['attachment_type'] == "PDF") {
    echo "<a href=\"http://family.cruelcoulter.com/{$row['attachment_location']}\">{$row['attachment_title']}</a>";
    echo "<div>{$row['alt_text']}</div>";
    echo "<p><small>Date posted: {$row['date_posted']}</small></p>\n";
} else {
	$escapedalttext = htmlspecialchars($row['alt_text'],ENT_QUOTES);
  echo "<figure>";
    echo "<img class=\"img-responsive\" src=\"{$row['attachment_location']}\" alt=\"{$escapedalttext}\">\n";
    echo "<figcaption>{$row['attachment_title']}</figcaption>\n";
    echo "</figure>";
    echo "<div>{$row['alt_text']}</div>";
    echo "<p><small>Date posted: {$row['date_posted']}</small></p>\n";
        } ?>
<?php endforeach; ?>

<?php
function createtaglink($tag_id, $tag_text) {
	$taglink = "<a href=\"tag.php?tag_id=" . $tag_id . "\">" . $tag_text . "</a>";
	return $taglink;
}
$tagstring = "<p>Tagged: ";
foreach ($tagresult as $tagrow) :
$tagstring .= createtaglink($tagrow['tag_id'],$tagrow['tag_text']) . ", ";
endforeach;
$tagstring = rtrim($tagstring, ", ");
$tagstring .= "</p>";
echo $tagstring;
?>

</div><!-- /familytable -->

</div> <!--/textarea-->

</div><!-- /.container -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo BOOTSTRAP_PATH; ?>dist/js/bootstrap.min.js"></script>

</body>
</html>
