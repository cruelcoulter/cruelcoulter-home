<?php

session_start();



require 'functions.php';



require '../../db_config.php';



require "../../pdo.php";

/*

01/02/15 - began with album_view as basis
<?PHP require 'include_fonts_css.php'; ?>
http://stackoverflow.com/questions/12314800/twiiter-bootstrap-carousel-making-dynamic-with-php

 */

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="utf-8" />

    <!-- Bootstrap core CSS -->

    <link href="<?php echo BOOTSTRAP_PATH; ?>dist/css/bootstrap.css" rel="stylesheet">

	

	

	<link rel="stylesheet" type="text/css" href="navbar-fixed-top.css">

<style type="text/css">

/* CUSTOMIZE THE CAROUSEL

-------------------------------------------------- */

/*http://getbootstrap.com/examples/carousel/carousel.css*/

/* Carousel base class */

.carousel {

  height: 500px;

  margin-bottom: 60px;

}

/* Since positioning the image, we need to help out the caption */

.carousel-caption {

  z-index: 10;

}



/* Declare heights because of positioning of img element */

.carousel .item {

  height: 500px;

  background-color: #777;

}

.carousel-inner > .item > img {

  position: absolute;

  top: 0;

  left: 0;

  /*min-width: 100%;*/

  height: 500px;

}



</style>

  <script>

  //not sure i need this..

  $(document).ready(function(){

    $('.carousel').carousel();

  });

</script>

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



<div class="carousel" id="myCarousel" data-ride="carousel">



      <!-- Indicators -->

      <ol class="carousel-indicators">

<?php $idx = 0; ?>

<?php foreach ($attachments as $attachment) : ?>

<?php $idx_class = ($idx == 0) ? 'active' : ''; ?>

<?php echo '<li data-target="#myCarousel" data-slide-to="'.$idx.'" class="'.$idx_class.'"></li>' ?>

<?php $idx++; ?>

<?php endforeach; ?>

      </ol>





<div class="carousel-inner">

<?php $i = 1; ?>

<?php foreach ($attachments as $attachment) : ?>

<?php $item_class = ($i == 1) ? 'item active' : 'item'; ?>

   <div class="<?php echo $item_class; ?>">

<?php echo "<img src=\"{$attachment['attachment_location']}\" alt=\"{$attachment['alt_text']}\">\n"; ?>

    <div class="carousel-caption">

<?php echo "{$attachment['attachment_title']}\n"; ?>

    </div>

  </div>

<?php $i++; ?>

<?php endforeach; ?>



</div>



  <!-- Controls -->

  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">

    <span class="glyphicon glyphicon-chevron-left"></span>

  </a>

  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">

    <span class="glyphicon glyphicon-chevron-right"></span>

  </a>



</div><!-- /.carousel -->



</div><!--/container-->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script src="<?php echo BOOTSTRAP_PATH; ?>dist/js/bootstrap.min.js"></script>

</body>

</html>

