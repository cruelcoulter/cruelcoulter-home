<?php
session_start();
require 'functions.php';
require '../../db_config.php';
require "../../pdo.php";
/*
12/10/16 - removed http from google font api link 
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
    <?PHP require 'include_fonts_css.php'; ?>
	<link href='//fonts.googleapis.com/css?family=Source+Sans+Pro|Germania+One' rel='stylesheet' type='text/css'>
	
	<link rel="stylesheet" type="text/css" href="navbar-fixed-top.css">
<title>Family history - Tags</title>
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
$tagquery = "select * from tag order by tag_text";
$statement = $link->prepare($tagquery);
$statement->execute();
$tags = $statement->fetchAll();
?>
<div class="container">
<div class="row">
<ul>
<?php foreach ($tags as $tag) : ?>
<li><?php echo " <a href=\"tag.php?tag_id=" . $tag['tag_id'] . "\">" . $tag['tag_text'] . "</a>"; ?></li>
<?php endforeach;?>
</ul>

        </div><!-- /row -->
        </div><!--/container-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo BOOTSTRAP_PATH; ?>dist/js/bootstrap.min.js"></script>
</body>
</html>
