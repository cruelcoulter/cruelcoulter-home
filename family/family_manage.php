<?php
session_start();

require 'functions.php';

if ( ! AmILoggedIn()) {
	$thispage = substr($_SERVER['PHP_SELF'], 1);
	header("Location:login.php?backto=" . $thispage);
}

require '../../db_config.php';

//pdo stores the connection in the variable $link
require "../../pdo.php";

/*
04/05/20 - create as a copy of album_manage.php

 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="utf-8" />
        <!-- added tinymce link 9/29/15 -->
        <script type="text/javascript" src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
		tinymce.init({
			plugins: ["link"],
			selector: "#family_notes"
		});
		</script>
<!-- Bootstrap core CSS -->
<?PHP require 'include_fonts_css.php'; ?>


<style type="text/css">
.container {
padding-top: 30px;
margin-top: 30px;
}
</style>
<title>Manage family</title>

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
<link rel="apple-touch-icon-precomposed" sizes="144x144"
	href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114"
	href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72"
	href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed"
	href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon"
	href="../includes/favicon.png">

</head>

<body>
	<?php
	include 'navheader.php';

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$family_notes = (isset($_POST['family_notes']) ? $_POST['family_notes'] : "");
	$family_name = (isset($_POST['family_name']) ? $_POST['family_name'] : "");

if (!isset($_GET['family_id'])) {

$familypdo = $link->prepare('insert into family (family_notes, family_name)
 VALUES (:family_notes, :family_name)');
$familypdo->bindParam(':family_notes', $family_notes);
$familypdo->bindParam(':family_name', $family_name);
$familypdo->execute();

if ($familypdo) {
	echo 'family added';
}
else
{
	print_r($familypdo->errorInfo());
}

} //TODO: add logic to handle updates


	$link = null;
	} //if ($_SERVER['REQUEST_METHOD'] === 'POST')
		?>
	<div class="container">
		<form action="family_manage.php" method="post">
			<div class="form-group">
				<label for="family_name">Family Name</label> <input type="text"
					class="form-control" id="family_name" placeholder="family name"
					name="family_name">
			</div>

			<div class="form-group">
				<label for="family_notes">Family notes</label> <input type="text"
					class="form-control" id="family_notes" placeholder="notes" name="family_notes">
			</div>

			<button type="submit" class="btn btn-default">Submit</button>
		</form>

	</div>
</body>
</html>
