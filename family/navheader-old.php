<?php
/* 12/15/15 - added admin home link */
?>
    <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
         <div class="navbar-header">

          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

 
          </div><!-- /.navbar-header -->
          <div class="navbar-collapse collapse">
    <ul class="nav navbar-nav">
  <li><a href="index.php">Home</a></li>
  <li><a href="family_member_manage.php">Manage Family Members</a></li>
  <li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Attachment <span class="caret"></span></a>
          <ul class="dropdown-menu">
          <li><a href="attachment_manage.php">Associate existing attachment</a></li>
          <li><a href="file_upload.php">Upload File</a></li>
          </ul>
  </li>
  <li><a href="location_manage.php">Manage Locations</a></li>
  <li><a href="event_manage.php">Manage Events</a></li>
  <li><a href="manage_users.php">Manage Users</a></li>
  <li><a href="album_manage.php">Manage Albums</a></li>
  <li><a href="tag_manage.php">Manage Tags</a></li>
  </ul>
                <ul class="nav navbar-nav navbar-right">
                <li><a href="adminpage.php">Admin home</a></li>
              <li>
<a href="logout.php">Logout</a>
              </li>
              </ul>
</div>
</div> <!-- /container-fluid -->
              </nav>
              <script src="<?php echo BOOTSTRAP_PATH; ?>dist/js/bootstrap.min.js"></script>