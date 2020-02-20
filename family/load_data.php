<?php  
session_start();

require 'functions.php';

if ( ! AmILoggedIn()) {
	$thispage = substr($_SERVER['PHP_SELF'], 1);
	header("Location:login.php?backto=" . $thispage);
}

if (! AmIAdmin() ) {
	echo "You don't have access to administrative functions";
	die();
}

require '../../db_config.php';

require '../../pdo.php';
/* 
02/05/20 - create based on youtube tutorial
*/
$jsonoutput = [];
$output = '';
//temporarily changed post to get for debugging. Don't know why this page doesn't work.
if(isset($_POST["family_member_id"])) {
    if($_POST["family_member_id"] != '')
    {
        $family_member_id = $_POST["family_member_id"];
        $sql = "select attachment_id, attachment_title from attachment where attachment_id NOT IN 
        (select attachment_id from junc_family_member_attachment where family_member_id = :family_member_id)";
    }
    else
    {
        $sql = "select attachment_id, attachment_title from attachment";
    }
    $qryprep = $link->prepare($sql);
			
    $qryprep->bindParam(':family_member_id', $family_member_id, PDO::PARAM_INT);
    
    $qryprep->execute();
	$attachment_result = $qryprep->fetchAll();
	foreach ($attachment_result as $attrow):
    	$output .= "<option value=\"{$attrow['attachment_id']}\">{$attrow['attachment_title']}</option>\n";
    //$jsonoutput[$attrow['attachment_id']] = $attrow['attachment_title'];
	endforeach;
    echo $output;
//return $output;
//echo json_encode($jsonoutput);
}