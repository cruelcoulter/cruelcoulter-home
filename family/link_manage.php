<?php
session_start();
// created 4/22/15.   
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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

$linkquery = "select * from link order by link_title";


$linkexecute = $link->prepare($linkquery);

$linkexecute->execute();

$links = $linkexecute->fetchAll();

} else {
//if post

	 if (!empty($_POST['link_id']) && !empty($_POST['family_member_id']) ) {
	$insjunc = "insert into junc_family_member_link (link_id, family_member_id) VALUES (:link_id, :family_member_id)";
	$insprep = $link->prepare($insjunc);
	$insprep->execute(array(':link_id' => $_POST['link_id'], ':family_member_id' => $_POST['family_member_id']));

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

	<script>
		$(function() {

			$("#link_id").change(function() {
				$("#family_member_id").load("fam_mem_getter.php?choice=" + $("#link_id").val());
			});

		});
	</script>

</head>

<body>
<div class="container">
<div class="row">
<form method="post">


<label for="link_id">Choose an existing link</label>
<select id="link_id" name="link_id">
<option selected value="--select--">--select--</option>
<?php foreach ($links as $linkrow) :
echo  "<option value=\"{$linkrow['link_id']}\">{$linkrow['link_title']}</option>";
endforeach;
?>
</select>

<!-- This will return the family members that aren't already associated with the selected link -->
<select id="family_member_id" name="family_member_id">
<option>Please choose</option>
</select>
<!--<input type="submit" value="submit">-->
<button type="submit" class="btn btn-default">Submit</button>
</form>
        </div><!-- /row -->
        </div><!--/container-->



</body>
</html>