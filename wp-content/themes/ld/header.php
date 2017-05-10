<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php bloginfo('name'); ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/dist/css/skins/skin-blue.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>
	dt {margin-top: 8px;}
</style>
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
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
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
          <!-- <li class="header">ARTIKEL</li> -->
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
