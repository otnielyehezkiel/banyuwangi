<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <!-- Title and other stuffs -->
  <title>Login </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">

  <!-- Stylesheets -->
  <link href="<?=base_url()?>static/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?=base_url()?>static/css/font-awesome.min.css">
  <link href="<?=base_url()?>static/css/style.css" rel="stylesheet">
  
  <script src="<?=base_url()?>static/js/respond.min.js"></script>
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Favicon -->
  
</head>

<body ng-app="app" ng-controller="PermissionsForm">

<!-- Form area -->
<div class="admin-form">
  <div class="container">
           <div class="row">
      <div class="col-md-12">
        <!-- Widget starts -->
            <div class="widget worange">
              <!-- Widget head -->
              <div class="widget-head" style="text-align: center">
                  Kesalahan
              </div>
              
                
              <div class="widget-content">
                <div class="padd">
                  <!-- Login form -->
                  <?= $pesan ?>
				</div>
                  
                </div>
              
                <div class="widget-foot">
                </div>
            </div>  
      </div>
    </div>
  
</div> 
</div>
	
		

<!-- JS -->
<script src="<?=base_url()?>static/js/jquery.js"></script>
<script src="<?=base_url()?>static/js/bootstrap.min.js"></script>
</body>
</html>