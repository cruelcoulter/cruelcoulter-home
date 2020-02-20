<?php
session_start();
require 'functions.php';
if ( ! AmILoggedIn()) {
	$thispage = substr($_SERVER['PHP_SELF'], 1);
	header("Location:login.php?backto=" . $thispage);
}
require '../../db_config.php';
require '../../pdo.php';
	$choice = htmlspecialchars($_GET['choice']);
	switch ($choice) {
		case 'attachment':
	$query = "SELECT attachment_id as id_num, attachment_title as dd_val FROM attachment";
	break;
	case 'event':
	$query = "select event_id as id_num, event_name as dd_val FROM event";
	break;
	case 'link':
	$query = "select link_id as id_num, link_title as dd_val from link";
	break;
	}
	$execute = $link->prepare($query);
	$execute->execute();
	$result = $execute->fetchAll();

	foreach ($result as $row) :
   		echo "<option value=\"{$row['id_num']}\">" . $row{'dd_val'} . "</option>";
	endforeach;
?>