<?php
session_start();

require 'functions.php';

require '../../db_config.php';

require "../../pdo.php";
/*
10/01/15 - initial release
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
<title>Family history - Events</title>
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
include "familynavbar.php";

if (isset($_SESSION['IsLoggedIn']) AND $_SESSION['IsLoggedIn'] == true)
{
$eventquery = "select attachment_id, attachment_title from attachment order by attachment_title";
}
else
{
$eventquery = "select attachment_id, attachment_title from attachment WHERE access_level='PUBLIC' order by attachment_title";
}
$statement = $link->prepare($eventquery);

$statement->execute();

$events = $statement->fetchAll();
?> 

<div class="container">
<div class="row">

<ul>
<?php foreach ($events as $event) : ?>

<li><?php echo "<a href=\"attachment.php?attachment_id=" . $event['attachment_id'] . "\">" . $event['attachment_title'] . "</a>"; ?></li>

<?php endforeach;?>
</ul>


        </div><!-- /row -->
        </div><!--/container-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo BOOTSTRAP_PATH; ?>dist/js/bootstrap.min.js"></script>
</body>
</html>
