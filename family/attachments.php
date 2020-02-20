<?php

session_start();



require 'functions.php';



require '../../db_config.php';



require '../../pdo.php';



/* 

 	attachments.php

	purpose: display all attachments with ability to filter by family

10/08/13 - version 0
<?PHP require 'include_fonts_css.php'; ?>
10/21/13 - modify query to only get attachments with attachment_type of photo

11/23/13 - changed hardcoded bootstrap path to constant defined in db_config.php. Added google_script include.

12/02/13 - convert to pdo

12/30/14 - fix js and css

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

<link rel="shortcut icon" href="../includes/favicon.png">



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

        

$family_id_temp = (isset($_REQUEST["family_id"]) ? $_REQUEST["family_id"] : "");

if ((ctype_digit($family_id_temp)) && (strlen($family_id_temp) < 10)) {

	$family_id = $family_id_temp;

	} else {

	$family_id = "1"; 

	}

        

        

$query_text = "select 

att.alt_text,

att.attachment_location,

att.attachment_title

from

attachment att,

junc_family_attachment jfa

where

jfa.attachment_id = att.attachment_id 

and

    		att.attachment_type = 'photo'

    		and

jfa.family_id = :family_id";



$querytext = $link->prepare($query_text);



$querytext->bindParam(':family_id', $family_id, PDO::PARAM_INT);



$querytext->execute();



$result = $querytext->fetchAll();



$q2 = "select family_name from family where family_id = :family_id";



$q2text = $link->prepare($q2);



$q2text->execute(array(':family_id'=>$family_id));



$q2res = $q2text ->fetch();



if(!$result) {

	print_r($result->errorInfo());

}



if(!$q2res) {

	print_r($q2res->errorInfo());

}

?>



<div class="container"> 



<?php

include 'familynavbar.php';



$q2row = $q2res;



echo "<h2>The {$q2row["family_name"]} Family Gallery</h2>\n";



foreach ($result as $row) :

	echo "<div class=\"row\">\n";

	

	echo "<div class=\"col-md-9\">\n";

	

    echo "<img src=\"{$row['attachment_location']}\" alt=\"{$row['alt_text']}\">\n";

	

	echo "<div class=\"caption\">{$row["attachment_title"]}</div>\n";

	

	echo "</div><!-- /col-md-9 -->\n";

	

	echo "<div class=\"col-md-3\">\n";

	

	echo "<p>{$row['alt_text']}</p>\n";

	

	echo "</div><!-- /col-md-3 -->\n";

	

	echo "</div><!-- /row -->\n";



endforeach;

?>



</div><!-- /.container -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script src="<?php echo BOOTSTRAP_PATH; ?>dist/js/bootstrap.min.js"></script>



</body>

</html>