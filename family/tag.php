<?php
session_start();
require 'functions.php';
require '../../db_config.php';
require "../../pdo.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
    <?PHP require 'include_fonts_css.php'; ?>	
	
<title>Family history - Tag</title>
<?php
if (ENVIRON == "PROD") {
	include 'google_script.php';
}
?>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo BOOTSTRAP_PATH; ?>bootstrap-3.0.0/assets/js/html5shiv.js"></script>
      <script src="<?php echo BOOTSTRAP_PATH; ?>bootstrap-3.0.0/assets/js/respond.min.js"></script>
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
if (isset($_GET["tag_id"]) && ctype_digit($_GET["tag_id"]) && strlen($_GET["tag_id"]) < 10) {
	$tag_id = $_GET["tag_id"];
} else {
	$tag_id = 1;
}
$tagtextquery = "select tag_text from tag where tag_id = :tag_id";
$statement = $link->prepare($tagtextquery);
$statement->bindValue(':tag_id', $tag_id);
$statement->execute();
//returns the first column of the first row
$tagtext = $statement->fetchColumn();
$tageventquery = "select E.event_id, E.event_detail, coalesce(E.event_year, year(E.event_date), '?') as event_year,
E.event_name from event E, junc_tag JT WHERE JT.ref_type = 'event' AND JT.ref_id = E.event_id AND JT.tag_id = :tag_id";
$eventresult = $link->prepare($tageventquery);
$eventresult->bindValue(':tag_id', $tag_id);
$eventresult->execute();

$taglinkquery = "select L.link_id, L.link_url, L.link_title from link L, junc_tag JT WHERE JT.ref_type='link' AND JT.ref_id = L.link_id AND JT.tag_id = :tag_id";
$linkstatement = $link->prepare($taglinkquery);
$linkstatement->bindValue(':tag_id', $tag_id);
$linkstatement->execute();

$tagattquery = "select A.attachment_id, A.attachment_title from attachment A, junc_tag JT WHERE JT.ref_type='attachment' AND JT.ref_id = A.attachment_id AND JT.tag_id = :tag_id";
$attstatement = $link->prepare($tagattquery);
$attstatement->bindValue(':tag_id', $tag_id);
$attstatement->execute();
?>
<div class="container" style="padding-top: 50px;">
<h1><?php echo $tagtext ?></h1>
<?php
if ($eventresult->rowCount() > 0) {
$tageventresult = $eventresult->fetchAll();
  echo "<div class=\"well well-lg\">";
echo "<h3>Events</h3>";
foreach ($tageventresult as $event):
echo $event['event_year'] . " " . $event['event_name'] . " " . $event['event_detail'];
endforeach;
  echo "</div><!-- /.well-->";
}
if ($linkstatement->rowCount() > 0) {
$taglinkresult = $linkstatement->fetchAll();
  echo "<div class=\"well well-lg\">";
echo "<h3>Links</h3>";
echo "<ul>";
foreach ($taglinkresult as $linkitem):
echo "<li><a href=\"{$linkitem['link_url']}\">{$linkitem['link_title']}</a></li>";
endforeach;
echo "</ul>";
  echo "</div><!-- /.well-->";
}
if ($attstatement->rowCount() > 0) {
$tagattresult = $attstatement->fetchAll();
  echo "<div class=\"well well-lg\">";
echo "<h3>Attachments</h3>";
echo "<ul>";
foreach ($tagattresult as $att):
echo "<li><a href=\"attachment.php?attachment_id={$att['attachment_id']}\">{$att['attachment_title']}</a></li>";
endforeach;
echo "</ul>";
  echo "</div><!-- /.well-->";
}
?>
<?php /*echo "<img class=\"img-responsive\" src=\"{$tag['attachment_location']}\" alt=\"{$tag['alt_text']}\">\n";*/ ?>

        </div><!--/container-->
<?php
include 'include_js.php';
include 'footer_family.php'; 
?>
</body>
</html>