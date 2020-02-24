<?php
session_start();
require 'functions.php';
require '../../db_config.php';
require "../../pdo.php";
/*
02/23/20 - Created as a copy of tags.php 
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
    <?PHP require 'include_fonts_css.php'; ?>
<title>Family history - Links</title>
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
$lquery = "select * from link order by link_title";
$statement = $link->prepare($lquery);
$statement->execute();
$cruellinks = $statement->fetchAll();
?>
<div class="container">
<ul>
<?php foreach ($cruellinks as $cruellink) : ?>
<li><?php echo " <a target=\"_blank\" href=\"" . $cruellink['link_url'] . "\">" . $cruellink['link_title'] . "</a>"; ?></li>
<?php endforeach;?>
</ul>

</div><!--/container-->
<?php
include 'include_js.php';
include 'footer_family.php'; 
?>
</body>
</html>
