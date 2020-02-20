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
11/30/14 - create
12/10/14 - working baseline.
10/06/15 - add tinymce
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
			selector: "#album_description"
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
<title>Manage album</title>

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

	$album_description = (isset($_POST['album_description']) ? $_POST['album_description'] : "");

	$album_name = (isset($_POST['album_name']) ? $_POST['album_name'] : "");

	$access_level = (isset($_POST['access_level']) ? $_POST['access_level'] : "");

if (!isset($_GET['album_id'])) {

$albumpdo = $link->prepare('insert into album (album_description, album_name, access_level)
 VALUES (:album_description, :album_name, :access_level)');
$albumpdo->bindParam(':album_description', $album_description);
$albumpdo->bindParam(':album_name', $album_name);
$albumpdo->bindParam(':access_level', $access_level);
$albumpdo->execute();

if ($albumpdo) {
	echo 'Album added';
}
else
{
	print_r($albumpdo->errorInfo());
}

} //TODO: add logic to handle updates


	$link = null;
	} //if ($_SERVER['REQUEST_METHOD'] === 'POST')
		?>
	<div class="container">
		<form action="album_manage.php" method="post">
			<div class="form-group">
				<label for="album_name">Album Name</label> <input type="text"
					class="form-control" id="album_name" placeholder="album name"
					name="album_name">
			</div>

			<div class="form-group">
				<label for="album_description">Album Description</label> <input type="text"
					class="form-control" id="album_description" placeholder="Description" name="album_description">
			</div>

<?php
echo "<div class=\"form-group\">\n";

echo "<label for=\"access_level\">Public or Private?</label>";

echo "<select name=\"access_level\" id=\"access_level\">
    <option value=\"PUBLIC\">PUBLIC</option>
    <option value=\"PRIVATE\">PRIVATE</option>";

echo "</select>\n";

echo "</div>\n";
?>


			<button type="submit" class="btn btn-default">Submit</button>
		</form>

	</div>
</body>
</html>
