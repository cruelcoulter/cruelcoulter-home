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
09/18/13 - create
10/06/13 - added navheader include
10/10/13 - added event name
11/27/13 - moved to family folder. Format to match other files.
09/21/14 - added access level form; converted the date field to use the html5 date input
04/28/15 - add admin test; convert to pdo. 
09/29/15 - added tinymce. Don't add htmlspecialchars() around event_detail. just makes things weird.
 */
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8" />
        <!-- added tinymce link 9/29/15 -->
        <script type="text/javascript" src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
		tinymce.init({
			plugins: ["link"],
			selector: "#event_detail"
		});
		</script>
		<!-- Bootstrap core CSS -->
		<?php include 'include_fonts_css.php'; ?>
		
<style type="text/css">
.container {
padding-top: 30px;
margin-top: 30px;
}
</style>
		<title>Manage event</title>

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

/*$attachment_id = (isset($_POST['attachment_id']) ? $_POST['attachment_id'] : "");

$event_date = (isset($_POST['event_date']) ? $_POST['event_date'] : "");

$event_detail = (isset($_POST['event_detail']) ? $_POST['event_detail'] : "");
		
$event_name = (isset($_POST['event_name']) ? $_POST['event_name'] : "");

$event_type_id = (isset($_POST['event_type_id']) ? $_POST['event_type_id'] : "");

$event_year = (isset($_POST['event_year']) ? $_POST['event_year'] : "");

$family_id = (isset($_POST['family_id']) ? $_POST['family_id'] : "");

$family_member_id = (isset($_POST['family_member_id']) ? $_POST['family_member_id'] : "");

$family_member_id_other = (isset($_POST['family_member_id_other']) ? $_POST['family_member_id_other'] : "");

$access_level = (isset($_POST['access_level']) ? $_POST['access_level'] : "");
*/


		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$attachment_id =  $_POST['attachment_id'];
			$event_date =  $_POST['event_date'];
			$event_detail =  $_POST['event_detail'];
			$event_name =  $_POST['event_name'];
			$event_type_id =  $_POST['event_type_id'];
			$event_year =  $_POST['event_year'];
			$family_id =  $_POST['family_id'] ;
			$family_member_id =  $_POST['family_member_id'];
			
			if(!isset($attachment_id) || empty($attachment_id)) {$attachment_id = null; }
			if(!isset($event_date) || empty($event_date)) {$event_date = null; }
			if(!isset($event_detail) || empty($event_detail)) {$event_detail = null; }
			if(!isset($event_name) || empty($event_name)) {$event_name = null; }
			if(!isset($event_type_id) || empty($event_type_id)) {$event_type_id = null; }
			if(!isset($event_year) || empty($event_year)) {$event_year = null; }
			if(!isset($family_id) || empty($family_id)) {$family_id = null; }
			if(!isset($family_member_id) || empty($family_member_id)) {$family_member_id = null; }
			
			//$family_member_id_other =  $_POST['family_member_id_other'];

$access_level =  $_POST['access_level'];
		
echo $attachment_id . ", " .$event_date . ", " . $event_detail . ", " . $event_name . ", " . $event_type_id . ", " . $event_year . ", " . $family_id . ", " . $family_member_id . ", " .  $access_level ;

			$event_qry = "insert into event (attachment_id, event_date, event_name,
		event_detail, event_year, family_id, family_member_id,
		family_member_id_other, access_level, event_type_id) VALUES (
				:attachment_id,
				:event_date,
				:event_name,
				:event_detail,
				:event_year,
				:family_id,
				:family_member_id,
				:family_member_id_other,
				:access_level,
				:event_type_id
			)";

			
			$eventprep = $link->prepare($event_qry);
			
			$eventprep->bindParam(':attachment_id', $attachment_id, PDO::PARAM_INT);
			$eventprep->bindParam(':event_date', $event_date, PDO::PARAM_STR);
			$eventprep->bindParam(':event_name', $event_name, PDO::PARAM_STR);
			$eventprep->bindParam(':event_detail', $event_detail, PDO::PARAM_STR);
			$eventprep->bindParam(':event_year', $event_year, PDO::PARAM_STR);
			$eventprep->bindParam(':family_id', $family_id, PDO::PARAM_INT);
			$eventprep->bindParam(':family_member_id', $family_member_id, PDO::PARAM_INT);
			$eventprep->bindParam(':family_member_id_other', $family_member_id_other, PDO::PARAM_INT);
			$eventprep->bindParam(':access_level', $access_level, PDO::PARAM_STR);
			$eventprep->bindParam(':event_type_id', $event_type_id, PDO::PARAM_INT);
			
			$eventprep->execute();
			
/*			$eventprep->execute(
			array(
			  ':attachment_id' => isset($attachment_id) ? $attachment_id : null,
			  ':event_date' => isset($event_date) ? $event_date : null,
			  ':event_name' => isset($event_name) ? $event_name : null,
			  ':event_detail' => isset($event_detail) ? $event_detail : null,
			  ':event_year' => isset($event_year) ? $event_year : null,
			  ':family_id' => isset($event_year) ? $event_year : null,
			  ':family_member_id' => isset($family_member_id) ? $family_member_id : null,
			  ':family_member_id_other' => isset($family_member_id_other) ? $family_member_id_other : null,
			  ':access_level' => isset($access_level) ? $access_level : null,
			  ':event_type_id' => isset($event_type_id) ? $event_type_id : null
			  )
			);
*/

/*			$eventprep->execute(
			array(
			  ':attachment_id' => $attachment_id,
			  ':event_date' => $event_date,
			  ':event_name' => $event_name,
			  ':event_detail' => $event_detail,
			  ':event_year' => $event_year,
			  ':family_id' => $event_year,
			  ':family_member_id' => $family_member_id,
			  ':family_member_id_other' => $family_member_id_other,
			  ':access_level' => $access_level,
			  ':event_type_id' => $event_type_id
			  )
			);
*/			
				print_r($eventprep->errorInfo());
			/*printf("%d Row inserted.\n", $eventprep->affected_rows);*/

		}

		//end if server request_metho is post
		$event_type_qry = "select * from event_type order by event_type_name";

		$event_type_execute = $link->prepare($event_type_qry);
	
		$event_type_execute->execute();
	
		$event_type_result = $event_type_execute->fetchAll();


		$family_id_qry = "select family_id, family_name from family order by family_name";

		$family_id_execute = $link->prepare($family_id_qry);
	
		$family_id_execute->execute();
	
		$family_id_result = $family_id_execute->fetchAll();


		$family_member_id_qry = "select family_member_id, first_name, family_id from family_member order by family_id";

		$family_member_id_execute = $link->prepare($family_member_id_qry);
	
		$family_member_id_execute->execute();
	
		$family_member_id_result = $family_member_id_execute->fetchAll();

		
		$attachment_qry = "select attachment_id, attachment_location, attachment_title from attachment";

		$attachment_execute = $link->prepare($attachment_qry);
	
		$attachment_execute->execute();
	
		$attachment_result = $attachment_execute->fetchAll();
		
		?>
			<form action="event_manage.php" method="post">
				<div class="form-group">
					<label for="event_type_id">Select Event (required)</label>
					<select name="event_type_id" class="form-control" id="event_type_id">
						<?php
						foreach ($event_type_result as $etrow):
							echo "<option value=\"{$etrow['event_type_id']}\">{$etrow['event_type_name']}</option>\n";
						endforeach;
						?>
					</select>
				</div>

				<div class="form-group">
					<label for="event_year">Add year</label>
					<input type="text"
					class="form-control" id="event_year" placeholder="YYYY" name="event_year">
				</div>

				<div class="form-group">
					<label for="event_date">Add date</label>
					<input type="datetime"
					class="form-control" id="event_date" placeholder="YYYY-MM-DD" name="event_date">
				</div>

				<div class="form-group">
					<label for="event_name">Add name of event</label>
					<input type="text"
					class="form-control" id="event_name" placeholder="1880 census" name="event_name">
				</div>

				<div class="form-group">
					<label for="event_detail">Add detail (max 500)</label>
					<textarea name="event_detail" id="event_detail" class="form-control"></textarea>
                    
				</div>

				<div class="form-group">
					<label for="family_id">Select family</label>
					<select name="family_id" class="form-control" id="family_id">
						<option value=""></option>
						<?php
						foreach ($family_id_result as $firow):
							echo "<option value=\"{$firow['family_id']}\">{$firow['family_name']}</option>\n";
						endforeach;
						?>
					</select>
				</div>

				<div class="form-group">
					<label for="family_member_id">Select family member</label>
					<select name="family_member_id" class="form-control" id="family_member_id">
						<option value=""></option>
						<?php
						foreach ($family_member_id_result as $fmrow):
							$family_name = getlastnamepdo($fmrow['family_id'], $link);
							echo "<option value=\"{$fmrow['family_member_id']}\">{$fmrow['first_name']} {$family_name}</option>\n";
						endforeach;
						?>
					</select>
				</div>

				<div class="form-group">
					<label for="attachment_id">Select attachment</label>
					<select name="attachment_id" class="form-control" id="attachment_id">
						<option value=""></option>
						<?php
						foreach ($attachment_result as $attrow):
							echo "<option value=\"{$attrow['attachment_id']}\">{$attrow['attachment_location']} {$attrow['attachment_title']}</option>\n";
						endforeach;
						?>
					</select>
				</div>

				<div class="form-group">
					<label for="access_level">Access Level</label>
					<select name="access_level" class="form-control" id="access_level">
						<option value="PUBLIC">PUBLIC</option>
						<option value="PRIVATE">PRIVATE</option>
					</select>
				</div>

				<!-- need to add family_member_id_other eventually -->

				<button type="submit" class="btn btn-default">
					Submit
				</button>
			</form>

		</div>
	</body>
</html>
