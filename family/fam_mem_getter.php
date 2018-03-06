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

	$query = "select fm.family_member_id,
    CONCAT(fm.first_name, ' ', f.family_name) as full_name
     from family_member fm,
     family f
     WHERE
     f.family_id = fm.family_id
     AND
     fm.family_member_id NOT IN
     (SELECT family_member_id from junc_family_member_link
     WHERE link_id = :choice)
     ORDER BY f.family_name";

	$execute = $link->prepare($query);

	$execute->execute(array(':choice' => $choice));

	$result = $execute->fetchAll();


	foreach ($result as $row) :
   		echo "<option value=\"{$row['family_member_id']}\">" . $row{'full_name'} . "</option>";
	endforeach;

?>