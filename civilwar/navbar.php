<?php include_once("../analyticstracking.php"); ?>
<?php include 'lettertext.php';?>
 <?php
 function getactivestatus($script_name, $thislink) {
   $SCRIPT_NAME = str_replace("/", "", $script_name);
   if ($SCRIPT_NAME == $thislink) {
     $thisclass = "active";
   }
   else {
     $thisclass = "";
   }
   return $thisclass;
 }
 ?>
     <!-- Fixed navbar -->
     <div class="navbar navbar-default navbar-fixed-top">
 <div class="navbar-header">
 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
</button>
          <a class="navbar-brand" href="index.php">Civil War Letters</a>
      </div><!-- /.navbar-header -->

       <div class="navbar-collapse collapse">
         <ul class="nav navbar-nav">
<li class="<?php echo getactivestatus($_SERVER['PHP_SELF'], "index.php");?>">
         <a href="index.php">Home</a></li>
<li class="<?php echo getactivestatus($_SERVER['PHP_SELF'], "Letter1.php");?>">
<a href="Letter1.php">21 May 1863</a></li>
<li class="<?php echo getactivestatus($_SERVER['PHP_SELF'], "Letter2.php");?>">
<a href="Letter2.php">19 Dec 1863</a></li>
<li class="<?php echo getactivestatus($_SERVER['PHP_SELF'], "Letter3.php");?>">
<a href="Letter3.php">21 Dec 1863</a></li>
<li class="<?php echo getactivestatus($_SERVER['PHP_SELF'], "Letter4.php");?>">
<a href="Letter4.php">21 Dec 1864</a></li>
<li class="<?php echo getactivestatus($_SERVER['PHP_SELF'], "Letter5.php");?>">
<a href="Letter5.php">15 Jun 1865</a></li>
<li class="<?php echo getactivestatus($_SERVER['PHP_SELF'], "alltextonly.php");?>">
<a href="alltextonly.php">All - text only</a></li>
</ul>
<ul class="nav navbar-nav navbar-right">
<li><a  href="https://cruelcoulter.com" target="_BLANK">CRUELCOULTER</a></li>
</ul>
</div><!--/.navbar-collapse -->
</div><!-- /navbar -->