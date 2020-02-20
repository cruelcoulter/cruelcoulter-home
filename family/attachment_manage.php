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
02/04/20 - create as copy of event_manage.php
*/

function fill_attachment($link)
{
	$output = '';
	$attachment_qry = "select attachment_id, attachment_title from attachment order by attachment_title";
	$attachment_execute = $link->prepare($attachment_qry);
	$attachment_execute->execute();
	$attachment_result = $attachment_execute->fetchAll();
	foreach ($attachment_result as $attrow):
		$output .= "<option value=\"{$attrow['attachment_id']}\">{$attrow['attachment_title']}</option>\n";
	endforeach;
return $output;

}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8" />
		<!-- Bootstrap core CSS -->
		<?PHP require 'include_fonts_css.php'; ?>
		
<style type="text/css">
.container {
padding-top: 30px;
margin-top: 30px;
}
</style>
		<title>Manage attachment</title>

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
		<link rel="apple-touch-icon-precomposed" sizes="144x144"
		href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114"
		href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72"
		href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed"
		href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-57-precomposed.png">
		<link rel="shortcut icon"
		href="../includes/favicon.png">

		</head>

	<body>
		<div class="container">
<?php

include 'navheader.php';

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$attachment_id =  $_POST['attachment_id'];
			$feature_on_detail_page =  $_POST['feature_on_detail_page'] ;
			$family_member_id =  $_POST['family_member_id'];
			
			if(!isset($attachment_id) || empty($attachment_id)) {$attachment_id = null; }
			if(!isset($feature_on_detail_page) || empty($feature_on_detail_page)) {$feature_on_detail_page = 0; }
			if(!isset($family_member_id) || empty($family_member_id)) {$family_member_id = null; }
			

//$access_level =  $_POST['access_level'];
		
			$event_qry = "insert into junc_family_member_attachment (attachment_id, family_member_id, feature_on_detail_page) VALUES (
				:attachment_id,
                :family_member_id,
                :feature_on_detail_page
			)";

			
			$eventprep = $link->prepare($event_qry);
			
			$eventprep->bindParam(':attachment_id', $attachment_id, PDO::PARAM_INT);
			$eventprep->bindParam(':feature_on_detail_page', $feature_on_detail_page, PDO::PARAM_INT);
			$eventprep->bindParam(':family_member_id', $family_member_id, PDO::PARAM_INT);
			
			$eventprep->execute();

			if ($eventprep) {
				echo("\n\n\nSuccess!");
			} else {
					print_r($eventprep->errorInfo());
				}
		}

		//end if server request_method is post
/*		$event_type_qry = "select * from event_type order by event_type_name";
		$event_type_execute = $link->prepare($event_type_qry);
		$event_type_execute->execute();
		$event_type_result = $event_type_execute->fetchAll();

 		$family_id_qry = "select family_id, family_name from family order by family_name";
		$family_id_execute = $link->prepare($family_id_qry);
		$family_id_execute->execute();
		$family_id_result = $family_id_execute->fetchAll();
 */

//		$family_member_id_qry = "select family_member_id, first_name, family_id from family_member order by family_id";
		$family_member_id_qry = "select family_member_id, family_member_slug, family_id from family_member order by family_id, family_member_slug";
		$family_member_id_execute = $link->prepare($family_member_id_qry);
		$family_member_id_execute->execute();
		$family_member_id_result = $family_member_id_execute->fetchAll();

		
		$attachment_qry = "select attachment_id, attachment_location, attachment_title from attachment";
		$attachment_execute = $link->prepare($attachment_qry);
		$attachment_execute->execute();
		$attachment_result = $attachment_execute->fetchAll();
		
		?>
			<form action="attachment_manage.php" method="post">

				<div class="form-group">
					<label for="family_member_id">Select family member</label>
					<select name="family_member_id" class="form-control" id="family_member_id">
						<option value=""></option>
						<?php
						foreach ($family_member_id_result as $fmrow):
							//$family_name = getlastnamepdo($fmrow['family_id'], $link);
							echo "<option value=\"{$fmrow['family_member_id']}\">{$fmrow['family_member_slug']}</option>\n";
						endforeach;
						?>
					</select>
				</div>

				<div class="form-group">
					<label for="attachment_id">Select attachment</label>
					<select name="attachment_id" class="form-control" id="attachment_id">
					</select>
				</div>

				<div class="form-group">
					<label for="feature_on_detail_page">Feature on detail page?</label>
					<select name="feature_on_detail_page" class="form-control" id="feature_on_detail_page">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>

				<button type="submit" class="btn btn-default">
					Submit
				</button>
			</form>

		</div>
		<!--<script src="https://code.jquery.com/jquery-2.2.4.min.js">-->
		<!--https://www.codexworld.com/dynamic-dependent-select-box-using-jquery-ajax-php/-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="attach_ajax.js"></script>
	</body>
</html>
