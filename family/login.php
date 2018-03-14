<?php
session_start();

error_reporting(E_ERROR | E_PARSE);

require 'functions.php';

/* login.php
10/25/13 - first release
11/21/13 - converting to pdo. 
11/23/13 - changed hardcoded bootstrap path to constant defined in db_config.php. Added google_script include.
09/22/14 - added user_role functionality
01/01/15 - fix css
 */

require '../../db_config.php';

//trying pdo
require "pdo.php";

/* 
 * try {
	$pdolink = new PDO('mysql:host='.DATABASE_HOST.';dbname='.DATABASE_NAME, DATABASE_USERNAME,DATABASE_PASSWORD);
}
catch (PDOException $e)
{
	echo $e->getMessage();
}
 */
if (isset($_POST['username']) && isset($_POST['mypassword'])) {

	$username = $_POST['username'];

	$mypassword = $_POST['mypassword'];

	$userpdo = $link->prepare('SELECT user_id, email, passwd, salt, user_role FROM user WHERE username = :username');
	
	$userpdo->bindParam(':username', $username);
	
	$userpdo->execute();
	
	$users = $userpdo->fetch();
	
	//print_r($users);
	
	//$result = mysqli_query($query);

	if( ! $users) {
		header('Location:login.php?msg=User%20Not%20Found');
		die();
	}
	//$userData = mysqli_fetch_array($result, MYSQL_ASSOC);
	$hash = hash('sha256', $users['salt']) . hash('sha256', $mypassword);
	
	//echo $hash;
	
//The thing is that in php, it parses variables inside a string if you use double quotes and doesn't if you use single quotes
//http://stackoverflow.com/questions/13035992/dynamic-location-header-php	
	if($hash != $users['passwd']) {
		header('Location:login.php?msg=Incorrect+password');
		die();
	} else {
		$_SESSION["IsLoggedIn"] = true;
		$_SESSION["User_Role"] = $users['user_role'];
		if (isset($_POST['backto'])) {
			$backto = $_POST['backto'];
			header("Location: $backto");
		} else {
			header("Location: index.php?msg=logged+in");
		}
		die();
	}

}

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
	<link rel="stylesheet" type="text/css" href="navbar-fixed-top.css">
<title>Family history - Coulter, Kennedy, Pettibone, Raible</title>

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
<!--<style type="text/css">
.container {
//padding-top: 30px;
//margin-top: 30px;
}
</style>
--></head>

<body>
<?php

if (AmILoggedIn()) {
	echo "<p>You are already logged in. <a href=\"index.php\">Home</a></p>";
	die();
} else {
	include 'familynavbar.php';
}
?>
 
<div class="container">
<div class="row">

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="form-horizontal">

<div class = "form-group">
<label for="username" class="col-lg-2 control-label">User Name</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="username" id="username" maxlength="50" placeholder="Your assigned username">
</div>
</div>

<div class="form-group">
<label for="mypassword" class="col-lg-2 control-label">Password</label>
<div class="col-lg-10">
<input type="password" class="form-control" name="mypassword" id="mypassword" maxlength="50" placeholder="Your assigned password">
</div>
</div>

<div class="form-group">
<div class="col-lg-offset-2 col-lg-10">
<?php 
if (isset($_GET['backto'])) {
echo "<input type=\"hidden\" name=\"backto\" value=\"" . $_GET['backto'] . "\">";
}
?>

<button type="submit" class="btn btn-default">Sign in</button>
</div>
</div>


</form>
        </div><!-- /row -->
        </div><!--/container-->
</body>
</html>
