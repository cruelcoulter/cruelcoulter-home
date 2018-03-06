<?php
session_start();

require 'functions.php';

if ( ! AmILoggedIn()) {
	$thispage = substr($_SERVER['PHP_SELF'], 1);
	header("Location:login.php?backto=" . $thispage);
}

require '../../db_config.php';

//pdo stores the connection object in the variable $link
require '../../pdo.php';

/*
 	add_attachments_to_album.php
	purpose: display all attachments and albums to associate attachments with album
12/11/14 - create based on attachments.php
12/28/14 - functional baseline
10/11/15 - don't display the image, only title
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
<title>Associate Attachments with Album</title>

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

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	  //code here
      //var_dump($_POST['attachment_id']);
      $album_id = $_POST['album_id'];
      if(!empty($_POST['attachment_id'])){
     foreach($_POST['attachment_id'] as $attachment_id):
     $albumquery = $link->prepare('insert into junc_album_attachment (album_id, attachment_id)
     values (:album_id, :attachment_id)');
     $albumquery->bindParam(':album_id', $album_id);
    $albumquery->bindParam(':attachment_id', $attachment_id);
    $albumquery->execute();

     //echo "Album: $album_id<br>\n";
        //echo "$attachment_id was checked!<br>\n ";
     endforeach;
     exit;
   }
      }//end POST

$querytext = "select * from attachment order by attachment_id";

$querytext = $link->prepare($querytext);

$querytext->execute();

$result = $querytext->fetchAll();

$q2 = "select * from album ORDER BY album_name";

$q2text = $link->prepare($q2);

$q2text->execute();

$q2res = $q2text ->fetchAll();

if(!$result) {
	print_r($result->errorInfo());
}

if(!$q2res) {
	print_r($q2res->errorInfo());
}
include 'familynavbar.php';
?>

<div class="container" style="padding-top: 50px;">

<form method="post">


<?php

echo "<div class=\"form-group\">\n";

echo "<select name=\"album_id\" id=\"album_id\">";
foreach ($q2res as $row2) :
echo "<option value=\"{$row2['album_id']}\">{$row2['album_name']}</option>";
endforeach;
echo "</select>\n";

echo "</div>\n";



foreach ($result as $row) :
	echo "<div class=\"row\">\n";

	echo "<div class=\"col-md-12\">\n";

    echo "<input type=\"checkbox\" name=\"attachment_id[]\" value=\"{$row['attachment_id']}\" />";

//    echo "<img src=\"{$row['attachment_location']}\" alt=\"{$row['alt_text']}\">\n";

//	echo "<div class=\"caption\">{$row['attachment_title']}</div>\n";

    echo "{$row['attachment_title']}<br>\n";

	echo "</div><!-- /col-md-12 -->\n";

//	echo "<div class=\"col-md-3\">\n";

//	echo "<p>{$row['album_name']}</p>\n";

//	echo "</div><!-- /col-md-3 -->\n";

	echo "</div><!-- /row -->\n";

endforeach;

?>


			<button type="submit" class="btn btn-default">Submit</button>

</form>

</div><!-- /.container -->



</body>
</html>