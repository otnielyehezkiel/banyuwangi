
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
                  Login
              </div>
              
                
              <div class="widget-content">
                <div class="padd">
                  <!-- Login form -->
                  <form class="form-horizontal" action="<?=site_url()?>/login/do_login"  method="post">
                    <!-- Email -->
                    <div class="form-group">
                      <label class="control-label col-lg-3"for="inputUsername">Username</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control"  name="username"  id="inputEmail" placeholder="Masukkan Username Anda....">
                      </div>
                    </div>
                    <!-- Password -->
                    <div class="form-group">
                      <label class="control-label col-lg-3" for="inputPassword">Password</label>
                      <div class="col-lg-9">
                        <input type="password" class="form-control" name="password" id="inputPassword" placeholder="Masukkan Password Anda...">
                      </div>
                    </div>
                    <!-- Remember me checkbox and sign in button -->
                  
                        <div class="col-lg-9 col-lg-offset-3">
			<button type="submit" class="btn btn-info btn-sm">Log in</button>
                    </div>
                    <br />
                  </form>
				  
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