<!DOCTYPE 
 html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<?php /* 
       * Mootools has some problems with xhtml 1.1 and the correct 
       * xml-mimetype, so we use xhtml 1.0 strict. Buh, though. 
       */ ?>
<head profile="http://gmpg.org/xfn/11">
  <title><?php bloginfo('name'); ?><?php wp_title(' &raquo; '); ?></title>
  <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
  <meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
  <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/script/mootools.js"></script>
  <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/script/script.js.php"></script>
  <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/script/slimbox.js"></script>
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/slimbox.css" type="text/css" media="screen" />
  <?php wp_head(); ?>
  <!--[if lte IE 6]>
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/styleIE6.css" type="text/css" media="screen" />
  <![endif]-->
</head>

<body>
<div id="hometop"></div>
<div id="container">
  <div id="header">
    <h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
  </div>
  <div id="maincontainer">
    <div id="contentcontainer">
      <div id="contenttop"></div>
      <div id="content">
        <div id="buttons">
          <div id="buttonsleft">
            <?php next_posts_link('<img src="' . get_bloginfo('stylesheet_directory') . '/imgs/arrow_left.png" class="button" alt="&Auml;ltere Beitr&auml;ge" title="&Auml;ltere Beitr&auml;ge" />') ?>
            <?php previous_posts_link('<img src="' . get_bloginfo('stylesheet_directory') . '/imgs/arrow_right.png" class="button" alt="Neuere Beitr&auml;ge" title="Neuere Beitr&auml;ge" />') ?>
          </div>
          <?php /* You can add more Buttons here, just be sure to do it exactly the 
                 * way it is done here, and provide the imagefiles in grava/imgs/
                 * named button.png and button_hover.png. Else it wont work.
                 * You can do this in the WP-Backend maybe in a future release
                 */ ?>
          <div id="buttonsright">
            <a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/imgs/rss.png" class="button" title="RSS Feed abonnieren" alt="RSS" /></a>
            <a href="<?php bloginfo('comments_rss2_url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/imgs/rss_comments.png" class="button" title="RSS Feed (Kommentare) abonnieren" alt="RSS (Kommentare)"/></a>
            <img src="<?php bloginfo('stylesheet_directory'); ?>/imgs/sidebar.png" class="button" id="sidebartoggler" title="Sidebar einblenden / ausblenden" alt="Sidebar einblenden / ausblenden" />
          </div>
        </div>