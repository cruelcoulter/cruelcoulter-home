<?php
/*
11/23/14 - add events link
12/30/14 - fix the collapse functionality
10/01/15 - add documents link
12/15/15 - display admin home link to authenticated users
 */
 $currpage = basename($_SERVER['PHP_SELF']);
?>
    <nav class="navbar navbar-default navbar-fixed-top">

    <div class="container">

        <div class="navbar-header">

          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

          </button>

          <a class="navbar-brand" href="index.php">Family History</a>

          </div><!-- /.navbar-header -->

          <div class="navbar-collapse collapse" id="navbar">

            <ul class="nav navbar-nav">

              <li<?php echo checkpage($currpage, "index.php"); ?>><a href="index.php">Home</a></li>

              <li<?php echo checkpage($currpage, "family_member_find.php"); ?>><a href="family_member_find.php">People</a></li>

              <li<?php echo checkpage($currpage, "events.php"); ?>><a href="events.php">Events</a></li>

              <li<?php echo checkpage($currpage, "tags.php"); ?>><a href="tags.php">Tags</a></li>

              <li<?php echo checkpage($currpage, "attachment_find.php"); ?>><a href="attachment_find.php">Documents</a></li>

              <li<?php echo checkpage($currpage, "album_list.php"); ?>><a href="album_list.php">Albums</a></li>
 <?php
 if (isset($_GET["family_member_id"])) {
 	$family_member_id = $_GET["family_member_id"];
 } else {
 	$family_member_id = "";
 }
 
 if (strlen($family_member_id)) {
 	echo " <li>
 	 	<a href=\"family_detail.php?family_member_id=" . $family_member_id . "\">
 	Back to this person's page</a></li>";
 } ?>

              </ul>

              <ul class="nav navbar-nav navbar-right">
              
              <?php 
              if (AmILoggedIn()) {
				  echo "<li><a href=\"adminpage.php\">Admin Home</a></li>";
              	echo "<li><a href=\"logout.php\">Logout</a></li>";
              } else {
              	echo "<li><a href=\"login.php\">Log In</a></li>";
              }
              ?>
              <li><?php if (ENVIRON == "DEV") {echo basename($_SERVER['PHP_SELF']);} ?></li>
              
              </ul>

          </div><!--/.navbar-collapse -->
      </div><!--/.container -->
    </nav><!-- /navbar -->

