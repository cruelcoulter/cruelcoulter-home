<?php
session_start();
require 'functions.php';
require '../../db_config.php';
require "../../pdo.php";
/*
01/2
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
    <?PHP require 'include_fonts_css.php'; ?>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" />
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#attachment_find').DataTable( {
	   "ajax": "attachment_json.php"
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
<link rel="shortcut icon" href="../includes/favicon.png">
</head>
<body>
 <?php
include "familynavbar_v4.php";
//TODO: distinguish between public and private
?>
<div class="container">
<div class="col-md-12">
<table id="attachment_find" class="display">
<thead>
<tr>
<th>Attachments</th>
</tr>
</thead>
</table>
</div><!-- /col-md-12 -->
        </div><!--/container-->
        <!--for social media icons-->
<script src="https://use.fontawesome.com/releases/v5.0.8/js/brands.js"></script>
<script src="https://use.fontawesome.com/releases/v5.0.8/js/solid.js"></script>
<script src="https://use.fontawesome.com/releases/v5.0.8/js/fontawesome.js"></script>

<?php 
// don't include 'include_js.php' it breaks the page
include 'footer_family.php'; ?>
</body>
</html>
