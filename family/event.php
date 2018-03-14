<?php
session_start();

require 'functions.php';

require '../../db_config.php';

require "../../pdo.php";

/*
07/27/12 - initial version
08/28/12 - added getlastname query
09/17/13 - bootstrap 3.0 support
10/17/13 - added new query to display birth/death info
11/25/13 - changed hardcoded bootstrap path to constant defined in db_config.php. Added google_script include.
11/23/14 - changed path to pdo.php; fixed bootstrap path.
           Added logic to prevent unauthenticated users from seeing private events
           Add attachment link and changed query to left join
04/29/15 - fix bug in the query for logged in users
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
<title>Family history - Events</title>
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
include "familynavbar.php";

if (isset($_GET["event_id"]) && ctype_digit($_GET["event_id"]) && strlen($_GET["event_id"]) < 10) {
	$event_id = $_GET["event_id"];
} else {
	$event_id = 1;
}

if (isset($_SESSION['IsLoggedIn']) AND $_SESSION['IsLoggedIn'] == true)
{
$eventquery = "select A.attachment_location, A.attachment_title, A.alt_text,
E.event_detail, coalesce(E.event_year, year(E.event_date), '?') as event_year,
E.event_name from event E LEFT JOIN attachment A ON A.attachment_id = E.attachment_id
WHERE E.event_id = :event_id";
}
 else
{
  $eventquery = "select A.attachment_location, A.attachment_title, A.alt_text,
  E.event_detail, coalesce(E.event_year, year(E.event_date), '?') as event_year,
  E.event_name from event E LEFT JOIN attachment A ON E.attachment_id = A.attachment_id
  WHERE E.access_level='PUBLIC' and E.event_id = :event_id";
}
$statement = $link->prepare($eventquery);

$statement->bindValue(':event_id', $event_id);

$statement->execute();

$event = $statement->fetch();
?>

<div class="container" style="padding-top: 50px;">
<div class="row">


<p><?php echo $event['event_year'] . " " . $event['event_name'] . " " . $event['event_detail']; ?></p>
<?php echo "<img class=\"img-responsive\" src=\"{$event['attachment_location']}\" alt=\"{$event['alt_text']}\">\n"; ?>



        </div><!-- /row -->
        </div><!--/container-->
</body>
</html>
