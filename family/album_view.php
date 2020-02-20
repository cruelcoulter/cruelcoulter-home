<?php
session_start();

require 'functions.php';

require '../../db_config.php';

require "../../pdo.php";
/*
12/01/14 - Began. NOT done. Left off line 48
12/29/14 - Baseline release
12/30/14 - added js links for collapse to work
10/01/15 - add figure, figcaption and class=img-responsive
10/11/15 - escaped alttext
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
    <?PHP require 'include_fonts_css.php'; ?>
	
	
	<link rel="stylesheet" type="text/css" href="navbar-fixed-top.css">
<title>Family history - Photos</title>
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
 <?php
include "familynavbar_v4.php";

$album_id_temp = (isset($_GET["album_id"]) ? $_GET["album_id"] : "");
if ((ctype_digit($album_id_temp)) && (strlen($album_id_temp) < 10)) {
	$album_id = $album_id_temp;
	} else {
	$album_id = 1;
	}

if (isset($_SESSION['IsLoggedIn']) AND $_SESSION['IsLoggedIn'] == true)
{
$albumquery = "select * from album where album_id=:album_id";
$attquery = "select att.* from attachment att, junc_album_attachment jaa where
att.attachment_id = jaa.attachment_id
AND
jaa.album_id=:album_id";
}
else
{
$albumquery = "select * from album WHERE album_id=:album_id AND access_level='PUBLIC'";
$attquery ="select att.* from attachment att, junc_album_attachment jaa where
att.attachment_id = jaa.attachment_id
AND
jaa.album_id=:album_id AND access_level='PUBLIC'";
}

$statement = $link->prepare($albumquery);
$attstatement = $link->prepare($attquery);

$statement->execute(array(':album_id' => $album_id));
$attstatement->execute(array(':album_id' => $album_id));

$album = $statement->fetch();
$attachments = $attstatement->fetchAll();

if(!$album) {
	print_r($album->errorInfo());
}

if(!$attachments) {
	print_r($attachments->errorInfo());
}
?>

<div class="container">
<div class="row">


<?php echo "<h2>{$album['album_name']}</h2>\n"; ?>

<?php echo "<p>{$album['album_description']}</p>\n"; ?>
</div><!-- /row -->

<?php foreach ($attachments as $attachment) : ?>
<figure>
<div class="row">
<?php
	$escapedalttext = htmlspecialchars($attachment['alt_text'],ENT_QUOTES);

echo "<img class=\"img-responsive\" src=\"{$attachment['attachment_location']}\" alt=\"{$escapedalttext}\">\n"; ?>
</div>

<div class="row">
<?php
echo "<figcaption>{$attachment['attachment_title']}</figcaption>";
echo "<div>{$attachment['alt_text']}</div>\n";
    echo "<p><small>Date posted: {$attachment['date_posted']}</small></p>\n";
?>
</div>
</figure>
<?php endforeach; ?>

</div><!--/container-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo BOOTSTRAP_PATH; ?>dist/js/bootstrap.min.js"></script>
</body>
</html>
