<?php
session_start();

require 'functions.php';

require '../../db_config.php';

$monthcount = 3;

/*
07/27/12 - initial version
08/28/12 - added getlastname query
09/17/13 - bootstrap 3.0 support
10/17/13 - added new query to display birth/death info
11/23/13 - changed hardcoded bootstrap path to constant defined in db_config.php. Added google_script include.
09/28/14 - added separate queries for public/private
10/04/14 - fixed query_text
10/13/14 - added call to GetNews.php. Causing errors. So much for learning OO.
12/29/14 - added album links
12/30/14 - added js links for collapse to work
01/05/15 - set the max # of family members to display on page to 5. After that a link to family page displays.
01/05/15 - limit the # of new attachments to 5
01/21/15 - added order by clause to get the newest
04/21/15 - Fix returnstring bug; add AmIAdmin test around admin page link
10/02/15 - fix delay in new attachments displaying by adding +1 to currdate()
TODO: change to pdo
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
    <?PHP require 'include_fonts_css.php'; ?>
		
	<!--<link rel="stylesheet" type="text/css" href="navbar-fixed-top.css">
  <script src="https://use.fontawesome.com/releases/v5.0.8/js/brands.js"></script>
  <script src="https://use.fontawesome.com/releases/v5.0.8/js/solid.js"></script>
  <script src="https://use.fontawesome.com/releases/v5.0.8/js/fontawesome.js"></script>-->
<title>Family history - Coulter, Kennedy, Pettibone, Raible</title>

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
<style type="text/css">
.bd-footer-links li {
    display: inline-block;
    padding-left: 5px;
}
</style>
</head>

<body>
 <?php include 'familynavbar_v4.php';?>

<div class="container">
<div class="jumbotron">
<h1 class="display-4">Family history - Coulter, Kennedy, Pettibone, Raible</h1>
<p class="lead">This is a genealogy of my ancestors: the Coulters of Wayne County, Ohio, the Kennedys of parts unknown, the Pettibones of Girard, Pennsylvania, and the Raibles of Erie, Pennsylvania. It is a work in progress. Please check back for updates. Information on living people is not included.</p>
<p>If you would like more information of any people on this site, please contact ron at cruelcoulter dot com. Also, please follow me on Twitter: @cruelcoulter</p>
</div>
<div class="row">
<div class="col-md-6">
<?php

$link = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD)
or die("<p>Error connecting: " . mysqli_error($link) . "</p>");

mysqli_select_db($link, DATABASE_NAME)
or die("<p>Error selecting: " . mysqli_error($link) . "</p>");


if (isset($_SESSION['IsLoggedIn']) AND $_SESSION['IsLoggedIn'] == true)
{
$query_text = "select family_member.family_member_id, family_member.family_member_slug, family_member.first_name, UPPER(family.family_name) as family_name, f2.family_name as married_name, (concat (coalesce(family_member.birthdate_est, year(family_member.birthdate), '?'), ' - ', coalesce(family_member.deathdate_est, year(family_member.deathdate), '?'))) as birthdeath, family.family_id from family_member LEFT JOIN family ON family_member.family_id = family.family_id LEFT JOIN family f2 ON family_member.married_name_id = f2.family_id order by family_member.family_id, birthdeath";

        $querystring = "select * from attachment where date_posted BETWEEN DATE_SUB(CURDATE(), INTERVAL ". $monthcount ." MONTH) AND CURDATE()+1 ORDER BY date_posted DESC LIMIT 5";
       $newsresult=mysqli_query($link, $querystring);

        $returnstring = "<h3>New attachments</h3>\n";
        while ($attachments = mysqli_fetch_array($newsresult)) {
        $returnstring .= "<p><a href=\"attachment.php?attachment_id=" . $attachments['attachment_id'] . "\">" .
        $attachments['attachment_title'] . "</a></p>\n";
        }
        echo $returnstring;

}
else
{
$query_text = "select family_member.family_member_id, family_member.family_member_slug, family_member.first_name, UPPER(family.family_name) as family_name, f2.family_name as married_name, (concat (coalesce(family_member.birthdate_est, year(family_member.birthdate), '?'), ' - ', coalesce(family_member.deathdate_est, year(family_member.deathdate), '?'))) as birthdeath, family.family_id from family_member LEFT JOIN family ON family_member.family_id = family.family_id LEFT JOIN family f2 ON family_member.married_name_id = f2.family_id WHERE family_member.access_level = 'PUBLIC' order by family_member.family_id, birthdeath";

       $querystring = "select * from attachment where access_level ='PUBLIC' and date_posted BETWEEN DATE_SUB(CURDATE(), INTERVAL ". $monthcount ." MONTH) AND CURDATE()+1 ORDER BY date_posted DESC LIMIT 5";
       $newsresult=mysqli_query($link, $querystring);

        $returnstring = "<h3>New attachments</h3>\n";
        while ($attachments = mysqli_fetch_array($newsresult)) {
        $returnstring .= "<p><a href=\"attachment.php?attachment_id=" . $attachments['attachment_id'] . "\">" .
        $attachments['attachment_title'] . "</a></p>\n";
        }
        echo $returnstring;

}


//new query
//select fm.first_name, (concat (coalesce(fm.birthdate_est, year(fm.birthdate), '?'), ' - ', coalesce(fm.deathdate_est, year(fm.deathdate), '?'))) as birthdeath, f.family_name from family_member fm, family f
//where fm.family_id = f.family_id
//order by family_id, birthdeath
/*
 *
 *
select fm.first_name, (concat (coalesce(fm.birthdate_est, year(fm.birthdate), '?'), ' - ', coalesce(fm.deathdate_est, year(fm.deathdate), '?'))) as birthdeath, f.family_name, f.family_id from family_member fm, family f
where fm.family_id = f.family_id
order by fm.family_id, birthdeath

select fm.first_name, (concat (coalesce(fm.birthdate_est, year(fm.birthdate), '?'), ' - ', coalesce(fm.deathdate_est, year(fm.deathdate), '?'))) as birthdeath, f.family_name, f2.family_name as married_name, f.family_id
from family_member fm, family f, family f2
where fm.family_id = f.family_id
and fm.married_name_id = f2.family_id
order by fm.family_id, birthdeath

 * This works:
select family_member.first_name, UPPER(family.family_name), f2.family_name as married_name, (concat (coalesce(family_member.birthdate_est, year(family_member.birthdate), '?'), ' - ', coalesce(family_member.deathdate_est, year(family_member.deathdate), '?'))) as birthdeath, family.family_id
from family_member
LEFT JOIN family ON family_member.family_id = family.family_id
LEFT JOIN family f2 ON family_member.married_name_id = f2.family_id
order by family_member.family_id, birthdeath
 *  *  *
 */

$result = mysqli_query($link, $query_text);

if(!$result) {
	die("<p>Query Error: " . mysqli_error($link) . "</p>");
}

$current_famid = null;

echo "<ul>\n";

while ($row = mysqli_fetch_array($result)) {

//test 5/3/13
	if ($row['family_id'] != $current_famid) {
		$current_famid=$row['family_id'];
		$family_name = getlastname($row['family_id'], $link);
        //reinitialize the famcount for each new family
        $current_famcount = 1;
		echo "<h3>{$family_name}</h3>\n";
      echo "<p><a href=\"family.php?family_id={$current_famid}\">Family info</a></p>";
	}

    if ($current_famcount < 5) {
	echo "<li><a href=\"family_member\\{$row['family_member_slug']}\">
	{$row['first_name']} {$row['family_name']} {$row['married_name']}</a> {$row['birthdeath']}</li>\n";
    } else {
      //Do nothing if a family has more than five members listed
    }
    $current_famcount++;

}

echo "</ul>";

if (isset($_SESSION['IsLoggedIn']) AND $_SESSION['IsLoggedIn'] == true)
{
$countattachqry = "select count(*) as countattach from attachment";
$countfamilymemberqry = "select count(*) as countfamilymember from family_member";
$albumquery = "select album_id, album_name from album order by album_id";
}
else
{
$countattachqry = "select count(*) as countattach from attachment WHERE access_level ='PUBLIC'";
$countfamilymemberqry = "select count(*) as countfamilymember from family_member WHERE access_level ='PUBLIC'";
$albumquery = "select album_id, album_name from album WHERE access_level = 'PUBLIC' order by album_id";
}

$countfamilyqry = "select count(*) as countfamily from family";

$albumqueryrun = mysqli_query($link, $albumquery);
$countattachres = mysqli_query($link, $countattachqry);
$countfamilyres = mysqli_query($link, $countfamilyqry);
$countfamilymemberres = mysqli_query($link, $countfamilymemberqry);

//$albumresult = mysqli_fetch_array($albumqueryrun);
$countattachrow = mysqli_fetch_assoc($countattachres);
$countfamilyrow = mysqli_fetch_assoc($countfamilyres);
$countfamilymemberrow = mysqli_fetch_assoc($countfamilymemberres);

echo "<h2>Photo collections</h2>\n";
while ($album = mysqli_fetch_array($albumqueryrun)) {
  echo "<p><a href=\"album_view.php?album_id={$album['album_id']}\">{$album['album_name']}</a></p>\n";
}
?>
<p><a href="attachments.php?family_id=4">Raible image gallery</a></p>
<p><a href="attachments.php?family_id=1">Coulter image gallery</a></p>
<p><a href="attachments.php?family_id=3">Pettibone image gallery</a></p>

<p>Number of attachments: <?php echo $countattachrow["countattach"]; ?></p>
<p>Number of family names: <?php echo $countfamilyrow["countfamily"]; ?></p>
<p>Number of individuals: <?php echo $countfamilymemberrow["countfamilymember"]; ?></p>
        </div><!-- /col-md-6 -->

        <?php
        mysqli_free_result($result);
		mysqli_free_result($countattachres);
		mysqli_free_result($countfamilyres);
		mysqli_free_result($countfamilymemberres);

mysqli_close($link);

        ?>
        <div class="col-md-6">
            <img class="img-responsive" src="attach/coulter3.png" alt="Sign from the Coulter home 231 Yacht Club Drive" width="168" height="580"/>
        </div><!-- /col-md-6 -->
        </div><!-- /row -->
        <div class="row">
                <?php
        if (AmILoggedIn() && AmIAdmin()) {
        	echo "<p><a href=\"adminpage.php\">Admin Page</a></p>";
        }

        ?>
        </div>
        </div><!--/container-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo BOOTSTRAP_PATH; ?>dist/js/bootstrap.min.js"></script>-->
<?php 
//include '../includes/footer.php'; 
include 'include_js.php';
include 'footer_family.php';
?>
</body>
</html>
