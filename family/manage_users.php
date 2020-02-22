<?php 
// 09/22/14 - add AmIAdmin call and user_role
session_start();
require 'functions.php';
if ( ! AmILoggedIn()) {
	$thispage = substr($_SERVER['PHP_SELF'], 1);
	header("Location:login.php?backto=" . $thispage);
}
if ( ! AmIAdmin()) {
	die("Sorry, you must be an administrator to do this");
}
require '../../db_config.php';

require '../../pdo.php';
/* 
 * manage_users.php
 * 11/20/13 - first release
 * 11/25/13 - make consist with family files. Add salt
 * 2/21/20 - fix css and js links for bootstrap 4 
 *  */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
	<?php require 'include_fonts_css.php'; ?>
	
	
<title>Manage user</title>
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
<style type="text/css">
.container {
padding-top: 30px;
margin-top: 30px;
}
</style>
</head>
<body>
<?php  include 'navheader.php';?> 
<div class="container">
<?php


 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//header('Location: manage_users.php');
	if
	(isset($_POST['username'])
			&& isset($_POST['email'])
			&& isset($_POST['mypassword'])
			&& isset($_POST['mypassword2'])
			&& $_POST['mypassword'] === $_POST['mypassword2']
			)
	{
		//per http://tinsology.net/2009/06/creating-a-secure-login-system-the-right-way/
		$hash1 = hash('sha256', $_POST['mypassword']);
		//creates a 3 character sequence
		function createSalt()
		{
			$string = md5(uniqid(rand(), true));
			return substr($string, 0, 3);
		}
		$salt = createSalt();
		$hash = hash('sha256', $salt) . $hash1;
		
		
		$query = $pdolink->prepare('insert into user (username, email, user_role, passwd, salt) VALUES (:username, :email, :user_role, :passwd, :salt)');
		$array = array(
				":username" => $_POST['username'],
				":email" => $_POST['email'],
				":user_role" => $_POST['user_role'],
				":passwd" => $hash,
				":salt" => $salt
				);
		$result = $query->execute($array);
	if ($result) {
	echo 'User added';
		} else {
	print_r($result->errorInfo());
		}
	} else {
		echo "You have left off a required field, or your passwords don't match";
	}
	
}//server request_method
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="form-horizontal">
<div class = "form-group">
<label for="username" class="col-lg-2 control-label">User Name</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="username" id="username" maxlength="50" placeholder="Your assigned username">
</div>
</div>
<div class="form-group">
<label for="email" class="col-lg-2 control-label">Email</label>
<div class="col-lg-10">
<input type="email" class="form-control" name="email" id="email" maxlength="50" placeholder="Your email">
</div>
</div>
<div class="form-group">
<label for="user_role" class="col-lg-2 control-label">User Role</label>
<div class="col-lg-10">
	<select name="user_role">
	<option value="USER">USER</option>
	<option value="ADMIN">ADMIN</option>
	</select>
</div>
</div>
<div class="form-group">
<label for="mypassword" class="col-lg-2 control-label">Password</label>
<div class="col-lg-10">
<input type="password" class="form-control" name="mypassword" id="mypassword" maxlength="50" placeholder="Your desired password">
</div>
</div>

<div class="form-group">
<label for="mypassword2" class="col-lg-2 control-label">Password (again)</label>
<div class="col-lg-10">
<input type="password" class="form-control" name="mypassword2" id="mypassword2" maxlength="50" placeholder="Re-type your desired password">
</div>
</div>
<div class="form-group">
<div class="col-lg-offset-2 col-lg-10">
<button type="submit" class="btn btn-default">Add user</button>
</div>
</div>
</form>
        </div><!--/container-->
		<?php include 'include_js.php'; ?>
</body>
</html>
