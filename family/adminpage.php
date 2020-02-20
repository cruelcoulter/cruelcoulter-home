<?php
session_start();

require 'functions.php';

if ( ! AmILoggedIn() ) {
	$thispage = substr($_SERVER['PHP_SELF'], 1);
	header("Location:login.php?backto=" . $thispage);
}

if (! AmIAdmin() ) {
	echo "You don't have access to administrative functions";
	die();
}

require '../../db_config.php';

/*
07/27/12 - version 0
09/02/12 - added logic to process family_member_id and family_member as get params
09/18/13 - bootstrap 3.0 support; html5
10/20/13 - added caption
11/23/13 - changed hardcoded bootstrap path to constant defined in db_config.php. Added google_script include.
11/25/13 - added ternary operator test for $_GET values
09/22/14 - add top padding and link to manage_users.php
04/21/15 - add link to tag_manage.php; add amIAdmin test; include navheader instead of family navbar
10/11/15 - remove "(doesn't work well)" from link to add attach page
02/08/18 - added phpinfo
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
    <?PHP require 'include_fonts_css.php'; ?>
	
<title>Family History - Admin</title>

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
$link = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD)
or die("<p>Error connecting: " . mysqli_error($link) . "</p>");

mysqli_select_db($link, DATABASE_NAME)
or die("<p>Error selecting: " . mysqli_error($link) . "</p>");

?>


<?php
include 'navheader.php';
?>

<div class="container" style="padding-top:50px;">

	<p><a href="manage_users.php">Manage users</a></p>

<p><a href="family_member_manage.php">Manage family members</a></p>

<p><a href="file_upload.php">Manage attachments</a></p>

<p><a href="location_manage.php">Manage locations</a></p>

<p><a href="event_manage.php">Manage events</a></p>

<p><a href="tag_manage.php">Manage tags</a></p>

<p><a href="link_add.php">Add link</a></p>

<p><a href="link_manage.php">Associate a link with a person</a></p>

<p><a href="add_attachments_to_album.php">Add attachment to album</a></p>

</div><!-- /.container -->
<?php
echo $_SERVER['SERVER_NAME'];
phpinfo();
 ?>
<?php include 'include_js.php'; ?>
</body>
</html>
