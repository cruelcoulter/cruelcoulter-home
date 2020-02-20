<?php
/*
02/12/18 - copy of familynavbar created to handle bootstrap4
 */
 $currpage = basename($_SERVER['PHP_SELF']);
?>
    <nav class="navbar navbar-expand-lg navbar-light bg-faded">
      <a class="navbar-brand" href="<?php echo FAMILY_URL_ROOT; ?>index.php">Family History</a>
      <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar">
            <span class="navbar-toggler-icon"></span>
      </button>
          <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav mr-auto">
              <li<?php echo checkpage($currpage, "index.php"); ?>><a class="nav-link" href="<?php echo FAMILY_URL_ROOT; ?>index.php">Home</a></li>
              <li<?php echo checkpage($currpage, "family_member_find.php"); ?>><a class="nav-link" href="<?php echo FAMILY_URL_ROOT; ?>family_member_find.php">People</a></li>
              <li<?php echo checkpage($currpage, "events.php"); ?>><a class="nav-link" href="<?php echo FAMILY_URL_ROOT; ?>events.php">Events</a></li>
              <li<?php echo checkpage($currpage, "tags.php"); ?>><a class="nav-link" href="<?php echo FAMILY_URL_ROOT; ?>tags.php">Tags</a></li>
              <li<?php echo checkpage($currpage, "attachment_find.php"); ?>><a class="nav-link" href="<?php echo FAMILY_URL_ROOT; ?>attachment_find.php">Documents</a></li>
              <li<?php echo checkpage($currpage, "album_list.php"); ?>><a class="nav-link" href="<?php echo FAMILY_URL_ROOT; ?>album_list.php">Albums</a></li>
 <?php
 if (isset($_GET["family_member_id"])) {
 	$family_member_id = $_GET["family_member_id"];
 } else {
 	$family_member_id = "";
 }
 
 if (strlen($family_member_id)) {
 	echo " <li>
 	 	<a class=\"nav-link\" href=\"" . FAMILY_URL_ROOT . "family_member/\"" . $family_member_id . "\">
 	Back to this person's page</a></li>";
 } ?>
  </ul>
  <ul class="navbar-nav">
          <?php 
              if (AmILoggedIn()) {
				  echo "<li><a class=\"nav-link\" href=\"" . FAMILY_URL_ROOT . "adminpage.php\">Admin Home</a></li>";
              	echo "<li><a class=\"nav-link\" href=\"" . FAMILY_URL_ROOT . "logout.php\">Logout</a></li>";
              } else {
              	echo "<li><a class=\"nav-link\" href=\"" . FAMILY_URL_ROOT . "login.php\">Log In</a></li>";
              }
              ?>
              <li><?php if (ENVIRON == "DEV") {echo basename($_SERVER['PHP_SELF']);} ?></li>
              
              </ul>
          </div><!--/.navbar-collapse -->
    </nav><!-- /navbar -->
