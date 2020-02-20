<?php
/* 12/15/15 - add helpful labels and instructions*/
session_start();
require 'functions.php';

if ( ! AmILoggedIn()) {
	$thispage = substr($_SERVER['PHP_SELF'], 1);
	header("Location:login.php?backto=" . $thispage);
}

require '../../db_config.php';
require '../../pdo.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
/*$tagquery = "select tag.tag_text, tag.tag_id, junc_tag.ref_id, junc_tag.ref_type from tag, junc_tag
where tag.tag_id = junc_tag.tag_id order by tag.tag_id";*/

$tagquery = "select * from tag order by tag_text";

$tagexecute = $link->prepare($tagquery);

$tagexecute->execute();

$tags = $tagexecute->fetchAll();

} else {
//if post
	//add a new tag if needed
	if (!empty($_POST['tagnew']) && !empty($_POST['ref_id']) && !empty($_POST['ref_type'])) :
		$tag_textval = htmlspecialchars($_POST["tagnew"]);

	$instag = "insert into tag (tag_text) VALUES (:tag_textval)";
	$inspdo = $link->prepare($instag);
	$inspdo->execute(array(':tag_textval' => $tag_textval));

	$newid = $link->lastInsertId('tag_id');
	//source: PHP Solutions (2014) via books24x7
	$ref_id = (int) $_POST['ref_id'];

	$insjunc = "insert into junc_tag (tag_id, ref_id, ref_type) VALUES (:newid, :ref_id, :ref_type)";
	$insprep = $link->prepare($insjunc);
	$insprep->execute(array(':newid' => $newid, ':ref_id' => $_POST["ref_id"], ':ref_type' => $_POST["ref_type"]));

	 elseif (!empty($_POST['tag_id']) && !empty($_POST['ref_id']) && !empty($_POST['ref_type'])) :
	  $tag_id = (int) $_POST['tag_id'];
	  $ref_id = (int) $_POST['ref_id'];
			$insjunc = "insert into junc_tag (tag_id, ref_id, ref_type) VALUES (:tag_id, :ref_id, :ref_type)";
			$insprep = $link->prepare($insjunc);
			$insprep->execute(array(':tag_id' => $_POST["tag_id"], ':ref_id' => $_POST["ref_id"], ':ref_type' => $_POST["ref_type"]));
	 else :
	endif;//associate existing tag with a link, attachment or event

}
//end if post
?>
<!DOCTYPE HTML>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="utf-8" />
<!-- Bootstrap core CSS -->
<?PHP require 'include_fonts_css.php'; ?>


<title>Manage tags</title><!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
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


	<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

	<script>
		$(function() {

			$("#db-one").change(function() {
				$("#db-two").load("getter.php?choice=" + $("#db-one").val());
			});

		});
	</script>

</head>

<body>
<div class="container" style="padding-top:55px;">

<?php
        include 'navheader.php';
?>
<div class="row">
<form method="post">
<label for="tagnew">Add a new tag...</label>
<input type="text" name="tagnew" id="tagnew" />
<label for="tag_id">... OR select an existing tag</label>
<select id="tag_id" name="tag_id">
<option value=""></option>
<?php foreach ($tags as $tagrow) :

echo  "<option value=\"{$tagrow['tag_id']}\">{$tagrow['tag_text']}</option>";
endforeach;
?>
</select>
</div>

<div class="row">
<label for="db-one">Pick the type of item you are applying the tag to</label>
<select id="db-one" name="ref_type">
<option selected value="--select--">--select--</option>
<option value="attachment">attachment</option>
<option value="event">event</option>
<option value="link">link</option>
</select>
</div>

<div class="row">
<label for="db-two">Pick the item</label>
<select id="db-two" name="ref_id">
<option>Please choose</option>
</select>
</div>

<div class="row">
<input type="submit" value="submit">
</div>

</form>
</div>
</body>
</html>