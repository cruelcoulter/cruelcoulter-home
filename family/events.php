<?php
session_start();
require 'functions.php';
require '../../db_config.php';
require "../../pdo.php";
/*
07/27/12 - initial version
08/28/12 - added getlastname query
09/17/13 - bootstrap 3.0 support
10/17/13 - added new query to display birth/death info
11/25/13 - changed hardcoded bootstrap path to constant defined in db_config.php. Added google_script include.
12/01/13 - fix pdo.php location
09/28/14 - added separate queries for public/private
12/30/14 - css and js links
05/07/15 - add filters
11/04/15 - tweak filters and layout
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
    <?PHP require 'include_fonts_css.php'; ?>
<title>Family history - Events</title>
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
$family_id_query = "select family_id, family_name from family order by family_name";
$fistatement = $link->prepare($family_id_query);
$fistatement->execute();
$family_id_filter = $fistatement->fetchAll();
$event_type_query = "select * from event_type order by event_type_name";
$etstatement = $link->prepare($event_type_query);
$etstatement->execute();
$event_type_id_filter = $etstatement->fetchAll();
/*
this query works (maybe):
select event_id, 
coalesce(event_year, year(event_date), '?') as event_year, 
event_name,
coalesce (e.family_id, (select fm.family_id from family_member fm where fm.family_member_id = e.family_member_id)) as family_id
 from mydb.event e;

this also works (maybe)
select event_id, coalesce(event_year, year(event_date), '?') as event_year, event_name from event WHERE 1=1 AND (family_id = 1 OR family_member_id IN (select family_member_id from family_member WHERE family_id = 1))
*/
	if (isset($_POST['family_id_filter']) && (!empty($_POST['family_id_filter']))) {
		//doesn't work. $pre_fifl = filter_var($_POST['family_id_filter'],FILTER_SANITIZE_MAGIC_QUOTES);
		$family_id_filter_list = implode(', ', $_POST['family_id_filter']);
	} else {
		$family_id_filter_list = NULL;
	}
	
	if (isset($_POST['event_type_filter']) && (!empty($_POST['event_type_filter'])) && (ctype_digit($_POST['event_type_filter'])) && (strlen($_POST['event_type_filter']) < 3)) {
		$event_type_filter = $_POST['event_type_filter'];
	}
	else {
		$event_type_filter = NULL;
	}
if (isset($_SESSION['IsLoggedIn']) AND $_SESSION['IsLoggedIn'] == true)
  {
  $eventquery = "select event_id, coalesce(event_year, year(event_date), '?') as event_year, event_name from event WHERE 1=1 ";
	if (!empty($family_id_filter_list)) {
		$eventquery .= " AND family_id IN (" . $family_id_filter_list . ")";
	}
	if (!empty($event_type_filter)) {
		$eventquery .= " AND event_type_id = " . $event_type_filter;
	}
  
   $eventquery .= " order by event_year";
  }
else
  {
  $eventquery = "select event_id, coalesce(event_year, year(event_date), '?') as event_year, event_name from event WHERE access_level='PUBLIC' ";
	if (!empty($family_id_filter_list)) {
		$eventquery .= " AND family_id IN (" . $family_id_filter_list . ")";
	}
	if (!empty($event_type_filter)) {
		$eventquery .= " AND event_type_id = " . $event_type_filter;
	}
  
   $eventquery .= " order by event_year";
  }
$statement = $link->prepare($eventquery);
$statement->execute();
$events = $statement->fetchAll();
?> 
<div class="container">
<div class="row">
<div class="col-md-4">
    <form method="post" class="form-inline">
    <div class="form-group">
    <label for="family_id_filter">Family</label>
    <!-- make the select entity an array by adding brackets per http://stackoverflow.com/questions/13110758/getting-all-post-from-multiple-select-value-->
    <select id="family_id_filter" name="family_id_filter[]" class="form-control" multiple="multiple" size="15">
    <?php foreach ($family_id_filter as $family_item) : ?>
    <?php echo "<option value=\"{$family_item['family_id']}\">{$family_item['family_name']}</option>\n"; ?>
    <?php endforeach; ?>
    </select>
    </div>
<!-- someday, but not yet
    <div class="form-group">
    <label for="start_year_filter">Start year</label>
    <input type="number" maxlength="4" id="start_year_filter" name="start_year_filter" class="form-control" width="4">
    </div>
    <div class="form-group">
    <label for="end_year_filter">End year</label>
    <input type="number" maxlength="4" id="end_year_filter" name="end_year_filter" class="form-control" width="4">
    </div>
-->
    <div class="form-group">
    <label for="event_type_filter">Event type</label>
    <select id="event_type_filter" name="event_type_filter" class="form-control">
    <option value="">-- please select --</option>
    <?php foreach ($event_type_id_filter as $event_type_item) : ?>
    <?php echo "<option value=\"{$event_type_item['event_type_id']}\">{$event_type_item['event_type_name']}</option>\n"; ?>
    <?php endforeach; ?>
    </select>
    </div>
    <br />
    <button type="submit" class="btn btn-default">Apply filter</button>
    </form>
</div><!-- /col-md-4 -->
<div class="col-md-8">
<ul>
<?php /*var_dump($_POST['family_id_filter'])."<br>";*/ ?>
<?php foreach ($events as $event) : ?>
<li><?php echo $event['event_year'] . " <a href=\"event.php?event_id=" . $event['event_id'] . "\">" . $event['event_name'] . "</a>"; ?></li>
<?php endforeach;?>
</ul>
</div><!-- /col-md-8 -->
        </div><!-- /row -->
        </div><!--/container-->
<?php 
include 'include_js.php';
include 'footer_family.php';
 ?>
</body>
</html>
