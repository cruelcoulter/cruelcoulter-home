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
<link rel="shortcut icon" href="../includes/favicon.png">
</head>
<body>
 <?php
include "familynavbar_v4.php";
if (isset($_SESSION['IsLoggedIn']) AND $_SESSION['IsLoggedIn'] == true)
{
$eventquery = "select album_id, album_name from album order by album_name";
}
else
{
$eventquery = "select album_id, album_name from album WHERE access_level='PUBLIC' order by album_name";
}
$statement = $link->prepare($eventquery);
$statement->execute();
$events = $statement->fetchAll();
?> 
<div class="container">
<div class="row">
<ul>
<?php foreach ($events as $event) : ?>
<li><?php echo "<a href=\"album_view.php?album_id=" . $event['album_id'] . "\">" . $event['album_name'] . "</a>"; ?></li>
<?php endforeach;?>
</ul>

        </div><!-- /row -->
        </div><!--/container-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo BOOTSTRAP_PATH; ?>dist/js/bootstrap.min.js"></script>
</body>
</html>
