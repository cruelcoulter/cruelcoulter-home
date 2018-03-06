<?php
session_start();

require 'functions.php';

require '../../db_config.php';

require "../../pdo.php";
/*
01/28/16 - initial version
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
    <link href="<?php echo BOOTSTRAP_PATH; ?>dist/css/bootstrap.css" rel="stylesheet">
	<link href='//fonts.googleapis.com/css?family=Source+Sans+Pro|Germania+One' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="family.css">
	<link rel="stylesheet" type="text/css" href="navbar-fixed-top.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" />
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#family_find').DataTable( {
	   "ajax": "family_find_json.php"
        } );
    } );
</script>
<title>Family members</title>
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
include "familynavbar.php";
//TODO: distinguish between public and private
?>

<div class="container">
<div class="row">

<div class="col-md-12">
<table id="family_find" class="display">
<thead>
<tr>
<th>slug</th>
<th>First Name</th>
<th>Last Name</th>
<th>Gender</th>
<th>Married Name</th>
<th>Birth City</th>
<th>Birth State</th>
<th>Birth Country</th>
<th>Birth Year</th>
<th>Death City</th>
<th>Death State</th>
<th>Death Country</th>
<th>Death Year</th>
</tr>
</thead>
</table>

</div><!-- /col-md-12 -->

        </div><!-- /row -->
        </div><!--/container-->
        <?php include '../includes/footer.php'; ?>
</body>
</html>
