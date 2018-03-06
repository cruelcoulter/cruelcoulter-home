<?php
header("Content-type: application/json");
/*
03/10/16 - initial version
 */

$json_data = array();

require '../../db_config.php';

require "../../pdo.php";

if (isset($_SESSION['IsLoggedIn']) AND $_SESSION['IsLoggedIn'] == true)
{
$attach_query = "select
concat('<a href=\"attachment.php?attachment_id=',attachment_id,'\">',attachment_title,'</a>')
 from attachment order by attachment_title";
}
else
{
$attach_query = "select
concat('<a href=\"attachment.php?attachment_id=',attachment_id,'\">',attachment_title,'</a>')
 from attachment WHERE access_level='PUBLIC' order by attachment_title";
}

$fistatement = $link->prepare($attach_query);

$fistatement->execute();

$attach_find = $fistatement->fetchAll(PDO::FETCH_NUM);

$json_data = array('aaData' => $attach_find);

echo json_encode($json_data);

?>