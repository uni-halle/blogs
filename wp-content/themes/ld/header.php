<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php bloginfo('name'); ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/template/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/template/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/template/dist/css/skins/skin-blue.min.css">
  <style>
  	dt {margin-top: 8px;}
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
  <div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

      <!-- Logo -->
      <a href="<?php bloginfo('url') ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>st</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">Stuff</span>
      </a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Navigation umschalten</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
              <?php $current_user = wp_get_current_user(); ?>
              <li class="dropdown user user-menu">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $current_user->user_firstname . ' ' . $current_user->user_lastname; ?></a>
                  <ul class="dropdown-menu">
                      <li class="user-body">
                        <div class="text-center">
                          <?php echo $current_user->user_firstname . ' ' . $current_user->user_lastname; ?> ist gerade angemeldet.
                        </div>
                      </li>
                      <li class="user-footer">
                          <div class="pull-left"><a href="/wp-admin/edit.php" class="btn btn-default btn-flat">Dashboard</a></div>
                          <div class="pull-right"><a href="<?php echo wp_logout_url( home_url() ); ?>" class="btn btn-default btn-flat">Abmelden</a></div>
                      </li>
                  </ul>
              </li>
          </ul>
        </div>
      </nav>

    </header>
    <aside class="main-sidebar">
      <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
          <li id="schnellstart"><a href="/schnellstart"><i class="fa fa-rocket"></i><span>Schnellstart</span></a></li>
          <li id="katalog"><a href="/katalog"><i class="fa fa-bookmark"></i><span>Katalog</span></a></li>
          <li id="meine"><a href="/meine"><i class="fa fa-user"></i><span>Meine Artikel</span></a></li>
          <li id="checkout"><a href="/checkout"><i class="fa fa-paper-plane"></i><span>Ausleihe</span></a></li>
          <li id="checkin"><a href="/checkin"><i class="fa fa-archive"></i><span>Rücknahme</span></a></li>
          <li class="header">VERWALTUNG</li>
          <li id="label"><a href="/label"><i class="fa fa-paint-brush"></i><span>Label drucken</span></a></li>
          <li><a href="/wp-admin/post-new.php" target="backend"><i class="fa fa-plus"></i><span>Neuer Artikel</span></a></li>
        </ul>
        <!-- /.sidebar-menu -->
      </section>
      <!-- /.sidebar -->
    </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
