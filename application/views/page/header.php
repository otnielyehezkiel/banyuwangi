<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <!-- Title and other stuffs -->
  <title>SDA & LH Banyuwangi |<?php echo (isset($title) ? '  ' . $title : ''); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">


  <!-- Stylesheets -->
<link href="<?= base_url() ?>static/css/bootstrap.min.css" rel="stylesheet">
<!-- Font awesome icon -->
<link rel="stylesheet" href="<?= base_url() ?>static/css/font-awesome.min.css"> 
<!-- jQuery UI -->
<link rel="stylesheet" href="<?= base_url() ?>static/css/jquery-ui.css"> 
<!-- Calendar -->
<link rel="stylesheet" href="<?= base_url() ?>static/css/fullcalendar.css">
<!-- prettyPhoto -->
<link rel="stylesheet" href="<?= base_url() ?>static/css/prettyPhoto.css">  
<!-- Star rating -->
<link rel="stylesheet" href="<?= base_url() ?>static/css/rateit.css">
<!-- Date picker -->
<link rel="stylesheet" href="<?= base_url() ?>static/css/bootstrap-datetimepicker.min.css">
<!-- CLEditor -->
<link rel="stylesheet" href="<?= base_url() ?>static/css/jquery.cleditor.css">
<!-- Data tables -->
<link rel="stylesheet" href="<?= base_url() ?>static/css/jquery.dataTables.css">
<!-- Bootstrap toggle -->
<link rel="stylesheet" href="<?= base_url() ?>static/css/jquery.onoff.css">
<!-- Main stylesheet -->
<link href="<?= base_url() ?>static/css/style.css" rel="stylesheet">
<!--<link href="<?= base_url() ?>assets/default/css/style_detailProject.css" rel="stylesheet">-->
<!-- Widgets stylesheet -->
<link href="<?= base_url() ?>static/css/widgets.css" rel="stylesheet">   

<script src="<?= base_url() ?>static/js/respond.min.js"></script>
<!--[if lt IE 9]>
<script src="<?= base_url() ?>assets/default/js/html5shiv.js"></script>
<![endif]-->

<!--advance form -->
<!--<link href="<?= base_url() ?>assets/default/bs3/css/bootstrap.min.css" rel="stylesheet">-->
<!--<link href="<?= base_url() ?>assets/default/css/bootstrap-reset.css" rel="stylesheet">-->
<!-- <link href="<?= base_url() ?>assets/default/font-awesome/css/font-awesome.css" rel="stylesheet" /> -->

<!--<link rel="stylesheet" href="<?= base_url() ?>assets/default/css/bootstrap-switch.css" />-->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/css/multi-select.css" />
<!--<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/default/js/jquery-tags-input/jquery.tagsinput.css" />-->

<link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/js/select2/select2.css" />
<script src="<?= base_url() ?>static/js/jquery-2.1.3.min.js"></script> <!-- jQuery -->
<script src="<?= base_url() ?>static/js/bootstrap.js"></script> <!-- Bootstrap -->
<script src="<?= base_url() ?>static/js/jquery-ui.min.js"></script> <!-- jQuery UI -->
<script>
    Number.prototype.formatMoney = function(c, d, t){
        var n = this, 
            c = isNaN(c = Math.abs(c)) ? 2 : c, 
            d = d == undefined ? "." : d, 
            t = t == undefined ? "," : t, 
            s = n < 0 ? "-" : "", 
            i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
            j = (j = i.length) > 3 ? j % 3 : 0;
       return '<span class="currency">Rp</span><span class="number">' + s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "") + '</span>';
    };
     
</script>

<!-- Custom styles for this template -->
<!--<link href="<?= base_url() ?>assets/default/css/style2.css" rel="stylesheet">-->
<!--<link href="<?= base_url() ?>assets/default/css/style-responsive.css" rel="stylesheet" />-->
<!-- end advance form -->
</head>

<body ng-app="app" ng-controller="PermissionsForm">

<!-- Header starts -->
<style>
    .v-separator
    {
        content: "";
        display: inline-block;
        height: 55px;
        border-right: 1px solid #fafafa;
        border-left: 1px solid #b4b4b4;
        padding: 0;
    }
</style>
<div class="navbar navbar-fixed-top bs-docs-nav pull-left" role="banner">

    <div class="container">
        <!-- Menu button for smallar screens -->
        <div class="navbar-header">
            <button class="navbar-toggle btn-navbar" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                <span>Menu</span>
            </button>
            <!-- Site name for smallar screens -->
            <a href="<?= base_url() ?>" class="navbar-brand hidden-lg">Data PBI Jombang</a>
        </div>



        <!-- Navigation starts -->
        <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">         

            <?php
            $session=$this->session->userdata('jombang_session');
            //$session=$session[0];
            ?>


            <!-- Links -->
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown pull-right">            
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="fa fa-users"></i> <?= $session['PENGGUNA_NAMA'] ?> <b class="caret"></b>
                    </a>
                    <!-- Dropdown menu -->
                    <ul class="dropdown-menu">
                        <li><a href="<?= site_url() ?>/pengguna/profile"><i class="fa fa-user"></i> Profil</a></li>
                        <li><a href="<?= site_url() ?>/login/logout"><i class="fa fa-sign-out"></i> Logout</a></li>
                    </ul>
                </li>

            </ul>
        </nav>

    </div>
</div>
<div class="clearfix"></div>


<!-- Header starts -->
<header>
    <div class="container">
        <div class="row">

            <!-- Logo section -->
            <div class="col-md-4">
                <!-- Logo. -->
                <div class="logo">
                  <!--<h1><a href="#">SI<span class="bold">Project</span></a></h1>
                  <p class="meta">Sistem Manajemen dan Monitoring Proyek</p> -->
                    <img src="<?= base_url() ?>/static/img/LOGO.png" height="70px" alt="Logo" title="Jombang PBI" class="pull-left"/>
                </div>
                <!-- Logo ends -->
            </div>

            <!-- Button section -->
            <div class="col-md-6">

                <!-- Buttons -->

            </div>

            <!-- Data section -->

            

        </div>
    </div>
</header>
<!-- Header ends -->

<!-- Main content starts -->

<div class="content">

    <!-- Sidebar -->
    
    <div class="sidebar">
    <div class="sidebar-dropdown"><a href="#">Navigation</a></div>

    <!--- Sidebar navigation -->
    <!-- If the main navigation has sub navigation, then add the class "has_sub" to "li" of main navigation. -->
    <ul id="nav">
        <!-- Main menu with font awesome icon -->
        
        <li class="has_sub ">
            <a><i class="fa fa-table"></i>Upload <span class="pull-right"><i class="fa fa-chevron-right"></i></span></a>
            <ul>
                <li><a href="<?= site_url() ?>/cobaupload"><i class="fa fa-table"></i> Upload</a></li>
            </ul>
            
        </li>

        <li class="has_sub ">
            <a><i class="fa fa-table"></i>Management <span class="pull-right"><i class="fa fa-chevron-right"></i></span></a>
            <ul>
                <li><a href="<?= site_url() ?>/pengguna/manage"><i class="fa fa-table"></i> Pengguna</a></li>
            </ul>

        </li>

        <li class="has_sub ">
            <a><i class="fa fa-table"></i>Data <span class="pull-right"><i class="fa fa-chevron-right"></i></span></a>
            <ul>
                <li><a href=""><b>Produksi</b></a></li>
            </ul>
            <ul>
                <li><a href="<?= site_url() ?>/data/bahan_makanan"><i class="fa fa-table"></i> Bahan Makanan</a></li>
            </ul>
            <ul>
                <li><a href="<?= site_url() ?>/data/viewdata/buah_buahan"><i class="fa fa-table"></i> Buah-buahan</a></li>
            </ul>
            <ul>
                <li><a href="<?= site_url() ?>/data/viewdata/sayur_sayuran"><i class="fa fa-table"></i> Sayur-sayuran</a></li>
            </ul>
            <ul>
                <li><a href="<?= site_url() ?>/data/viewdata/tanaman_perkebunan"><i class="fa fa-table"></i> Tanaman Perkebunan</a></li>
            </ul>
            <ul>
                <li><a href="<?= site_url() ?>/data/konsumsi"><b>Konsumsi</b></a></li>
            </ul>
            <ul>
                <li><a href="<?= site_url() ?>/data/konsumsi"><i class="fa fa-table"></i>Konsumsi</a></li>
            </ul>
            <ul>
                <li><a href=""><b>Harga<b> </a></li>
            </ul>
            <ul>
                <li><a href="<?= site_url() ?>/data/hargabp"><i class="fa fa-table"></i> Bahan Pokok</a></li>
            </ul>
        </li>
       
    </ul>
</div>
    <!-- Sidebar ends -->

    <!-- Main bar -->
<div class="mainbar">
