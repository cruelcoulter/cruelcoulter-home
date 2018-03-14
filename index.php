<?php
session_start();
/*
03/06/16 - new home page for root
08/29/16 - add random quote feature
02/03/18 - upgrade to bootstrap4
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="google-site-verification" content="UWXOMmTbTtAAQWB25sS0TRWseBwnCySSF_eEc2EF8hM" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8" />
    <!--for social media icons-->
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/brands.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/solid.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/fontawesome.js"></script>
    <!-- Bootstrap core CSS
    <link href="/civilwar/bootstrap-3.0.0/dist/css/bootstrap.css" rel="stylesheet">-->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link href='//fonts.googleapis.com/css?family=Source+Sans+Pro|Germania+One' rel='stylesheet' type='text/css'>
<style type="text/css">
.marketing .col-lg-4 {
text-align: center;
}
.carousel-item {
text-align: center;
}
blockquote {
padding-top: 20px;
}
.footer {
  bottom: 0;
  width: 100%;
  height: 60px; /* Set the fixed height of the footer here */
  line-height: 60px; /* Vertically center the text there */
  background-color: #f5f5f5;
}
.bd-footer-links li {
    display: inline-block;
    padding-left: 5px;
}
</style>
<title>cruelcoulter.com home page</title>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="/civilwar/bootstrap-3.0.0/assets/js/html5shiv.js"></script>
      <script src="/civilwar/bootstrap-3.0.0/assets/js/respond.min.js"></script>
    <![endif]-->
    <!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/civilwar/bootstrap-3.0.0/assets/ico/apple-touch-icon-144-precomposed.png" >
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/civilwar/bootstrap-3.0.0/assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/civilwar/bootstrap-3.0.0/assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="/civilwar/bootstrap-3.0.0/assets/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="includes/favicon.png">

</head>
<body>
<?php
require '../db_config.php';
//require '../quotes_config.php';
//pdo stores the connection in the variable $link
require "../pdo.php";
include_once("analyticstracking.php");
$quotequery = "SELECT * FROM quotes ORDER BY RAND() LIMIT 1";
$statement = $quoteslink->prepare($quotequery);
$statement->execute();
$quotes = $statement->fetchAll();
?>
<div class="container first">
<div class="row">
<div class="col-lg-12">
<blockquote>
<?php foreach ($quotes as $quote) : ?>
<?php echo $quote['quote_text']; ?>
<?php echo "<div>" . $quote['quote_author'] . " - <cite>" . $quote['quote_source'] .
"</cite></div>"; ?>
<?php endforeach; ?>
</blockquote>
</div><!--/col-->
</div><!--/row-->
</div><!--/container-->

<!--
copied from https://getbootstrap.com/docs/4.0/examples/carousel/
Is a carousel a carousel if it only has one item?
-->

  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img class="first-slide" src="family/attach/cheewink_rocky_river.gif" alt="First slide">
      </div>
    </div>
  </div>

  <div class="container marketing">
    <!-- Three columns of text below the carousel -->
    <div class="row">
      <div class="col-lg-4">
        <img class="rounded-circle" src="images/pettibone_140x140_flat.png" alt="Ed and Rose Pettibone" width="140" height="140">
        <h2>Family History</h2>
        <p>My family genealogy going back to the late 1700s in eastern Pennsylvania</p>
        <p><a class="btn btn-secondary" href="/family" role="button">View details &raquo;</a></p>
      </div><!-- /.col-lg-4 -->
      <div class="col-lg-4">
        <img class="rounded-circle" src="images/140x140_letter_flat.png" alt="letter" width="140" height="140">
        <h2>Civil War Letters</h2>
        <p>Letters written by my great-great uncle while serving in the 105th Ohio Volunteer Infantry.</p>
        <p><a class="btn btn-secondary" href="/civilwar" role="button">View details &raquo;</a></p>
      </div><!-- /.col-lg-4 -->
      <div class="col-lg-4">
        <img class="rounded-circle" src="images/140x140_button_flat.png" alt="button" width="140" height="140">
        <h2>Blog</h2>
        <p>Thoughts in vain</p>
        <p><a class="btn btn-secondary" href="/blog" role="button">View details &raquo;</a></p>
      </div><!-- /.col-lg-4 -->
    </div><!-- /.row -->
  </div><!-- /.container -->

<script src="//code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<?php include (dirname(__FILE__) . '/includes/footer.php'); ?>
</body>
</html>
