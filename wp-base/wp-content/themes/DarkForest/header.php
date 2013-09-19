<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="all" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/print.css" type="text/css" media="print" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_head(); ?>
</head>
<body>
<div id="page">
<center>
<div class="toplink">
	<div class="toplinkT"><a href="<?php bloginfo('rss2_url'); ?>" class="rss">Entries RSS</a> <a href="<?php bloginfo('comments_rss2_url'); ?>" class="rss">Comments RSS</a></div>
</div>
  <div id="header">
    <div id="headerimg">
	  <h1><a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
	  <div class="description"><?php bloginfo('description'); ?></div>
	</div>

  
    <div class="mainMenu">
	<ul id="nav">
	  <li class="page_item"><a href="<?php echo get_settings('home'); ?>/" title="Home">Home</a></li>
	  <?php wp_list_pages('sort_column=menu_order&depth=1&title_li=');?>
	</ul>
    
    <div>
          <?php include (TEMPLATEPATH . '/searchform.php'); ?>
    </div>
    </div>
    </div>
  <!--/header -->

<div id="wrapper">