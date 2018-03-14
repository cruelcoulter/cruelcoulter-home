<?php
session_start();
// created 4/22/15.   NOT DONE *******
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

	$query = "select fm.family_member_id,
    CONCAT(fm.first_name, ' ', f.family_name) as full_name
     from family_member fm,
     family f
     WHERE
     f.family_id = fm.family_id
     ORDER BY f.family_name";

	$execute = $link->prepare($query);

	$execute->execute();

	$result = $execute->fetchAll();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

echo $_POST['new_link_title'];
echo $_POST['new_link_url'];
echo $_POST['family_member_id'];

	//add a new link
	if (!empty($_POST['new_link_title']) && !empty($_POST['new_link_url']) && !empty($_POST['family_member_id']) ){
		$link_titleval = htmlspecialchars($_POST["new_link_title"]);

	$inslink = "insert into link (link_title, link_url) VALUES (:link_titleval, :new_link_url)";
	$inspdo = $link->prepare($inslink);

    echo $inslink;

    if (!$inslink) {
      print_r($link->errorInfo());
    }
	$inspdo->execute(array(':link_titleval' => $link_titleval, ':new_link_url' => $_POST['new_link_url']));

	$newid = $link->lastInsertId('link_id');

	$insjunc = "insert into junc_family_member_link (link_id, family_member_id) VALUES (:newid, :family_member_id)";

    echo $insjunc;

    $insprep = $link->prepare($insjunc);
    if (!$insprep) {
      print_r($link->errorInfo());
    }
	$insprep->execute(array(':newid' => $newid, ':family_member_id' => $_POST["family_member_id"]));

}

}
//end if post
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
    <link href="<?php echo BOOTSTRAP_PATH; ?>dist/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo BOOTSTRAP_PATH; ?>dist/css/bootstrap-theme.css" rel="stylesheet">
	<link href='//fonts.googleapis.com/css?family=Source+Sans+Pro|Germania+One' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="family.css">
	<link rel="stylesheet" type="text/css" href="navbar-fixed-top.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage links</title>

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
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>


</head>

<body>
<div class="container">
<div class="row">
<form method="post">

<label for="new_link_title">Add a new link title</label>
<input type="text" name="new_link_title" id="new_link_title" />

<label for="new_link_url">Add a new link URL</label>
<input type="url" name="new_link_url" id="new_link_url" />


<select id="family_member_id" name="family_member_id">
<?php
foreach ($result as $row):
echo "<option value=\"{$row['family_member_id']}\">{$row['full_name']}</option>";
endforeach;
?>
</select>
<!--<input type="submit" value="submit">-->
<button type="submit" class="btn btn-default">Submit</button>
</form>
        </div><!-- /row -->
        </div><!--/container-->



</body>
</html>