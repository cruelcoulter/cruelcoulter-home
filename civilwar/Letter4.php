<!DOCTYPE html>
<html lang="en">
<!-- updated for Bootstrap 3 -->
  <head>
    <title>Civil War letters</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <!-- Bootstrap core CSS -->
    <link href="bootstrap-3.0.0/dist/css/bootstrap.css" rel="stylesheet">
    <link href="css/mymodal.css" rel="stylesheet">
    <!--for social media icons-->
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/brands.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/solid.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/fontawesome.js"></script>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="bootstrap-3.0.0/assets/js/html5shiv.js"></script>
      <script src="bootstrap-3.0.0/assets/js/respond.min.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="bootstrap-3.0.0/assets/ico/apple-touch-icon-144-precomposed.png" >
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="bootstrap-3.0.0/assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="bootstrap-3.0.0/assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="bootstrap-3.0.0/assets/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="bootstrap-3.0.0/assets/ico/favicon.png">
<style type="text/css">
.bd-footer-links li {
    display: inline-block;
    padding-left: 5px;
}
</style>


  </head>
  <body>
<?php include 'navbar.php';
include '../../db_config.php';
?> 
            <div class="container">
            <div class="row">

            <div class="col-md-6"> 
                    <img src="images/72ppi/letter4side1.png" alt="<?php echo $letter4side1; ?>" class="img-responsive imageletter">
                    <p><a href="#letter4side1" data-toggle="modal" data-target="#letter4side1">View full size with text</a></p>
                </div><!-- /.col-md-3 -->

            <div class="col-md-6"> 
                    <img src="images/72ppi/letter4side2.png" alt="<?php echo $letter4side2; ?>" class="img-responsive imageletter">
                    <p><a href="#letter4side2" data-toggle="modal" data-target="#letter4side2">View full size with text</a></p>
                </div><!-- /.col-md-3 -->
                
            </div><!-- /.row -->
            
            <div class="row">

            <div class="col-md-6"> 
                    <img src="images/72ppi/letter4side3.png" alt="<?php echo $letter4side3; ?>" class="img-responsive imageletter">
                    <p><a href="#letter4side3" data-toggle="modal" data-target="#letter4side3">View full size with text</a></p>
                </div><!-- /.col-md-3 -->

               <div class="col-md-6"> 
                    <img src="images/72ppi/letter4side4.png" alt="<?php echo $letter4side4; ?>" class="img-responsive imageletter">
                    <p><a href="#letter4side4" data-toggle="modal" data-target="#letter4side4">View full size with text</a></p>
                </div><!-- /.col-md-3 -->
                

            </div><!-- /row -->
            </div><!-- /.container -->
            
            
            <!-- Modal -->
  <div class="modal fade" id="letter4side1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog wide-modal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">December 21 1864 Side 1</h4>
        </div>
        <div class="modal-body">
        <div class="container">
        <div class="row">
        <div class="col-md-6">
        <img src="images/150ppi/letter4side1.png" alt="" class="img-responsive">
        </div>
        <div class="col-md-6">
        <p class="letterbody"><?php echo $letter4side1; ?></p>
        </div>
        </div><!-- /.row -->
        </div><!-- /.container -->
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
            
            <!-- Modal -->
  <div class="modal fade" id="letter4side2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog wide-modal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">December 21 1864 Side 2</h4>
        </div>
        <div class="modal-body">
        <div class="container">
        <div class="row">
        <div class="col-md-6">
        <img src="images/150ppi/letter4side2.png" alt="" class="img-responsive">
        </div>
        <div class="col-md-6">
        <p class="letterbody">
        <?php echo $letter4side2; ?>
        </p>
        </div>
        </div><!-- /.row -->
        </div><!-- /.container -->
        </div><!-- /.modal-body -->
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
                        
            
            <!-- Modal -->
  <div class="modal fade" id="letter4side3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog wide-modal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">December 21 1864 Side 3</h4>
        </div>
        <div class="modal-body">
        <div class="container">
        <div class="row">
        <div class="col-md-6">
        <img src="images/150ppi/letter4side3.png" alt="" class="img-responsive">
        </div>
        <div class="col-md-6">
        <p class="letterbody"><?php echo $letter4side3; ?></p>
        </div>
        </div><!-- /.row -->
        </div><!-- /.container -->
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
            
            <!-- Modal -->
  <div class="modal fade" id="letter4side4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog wide-modal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">December 21 1864 Side 4</h4>
        </div>
        <div class="modal-body">
        <div class="container">
        <div class="row">
        <div class="col-md-6">
        <img src="images/150ppi/letter4side4.png" alt="" class="img-responsive">
        </div>
        <div class="col-md-6">
        <p class="letterbody">
        <?php echo $letter4side4; ?>
        </p>
        </div>
        </div><!-- /.row -->
        </div><!-- /.container -->
        </div><!-- /.modal-body -->
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

          <?php include '../includes/footer.php';?> 
          
  
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap-3.0.0/assets/js/jquery.js"></script>
    <script src="bootstrap-3.0.0/assets/js/modal.js"></script>
    <script src="bootstrap-3.0.0/dist/js/bootstrap.min.js"></script>
    <script src="bootstrap-3.0.0/js/collapse.js"></script>
  
</body>
</html>
