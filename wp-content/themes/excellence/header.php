<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/tabber.js"></script>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/tab.css" type="text/css" media="screen" />
<?php wp_head(); ?>
</head>
<body>
<div id="wrapper">
<div id="header">

<div id="logo">
<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
<h2><?php bloginfo('description'); ?></h2>
</div><!--logo-->

<div id="logo_right">
<?php include (TEMPLATEPATH . '/searchform.php'); ?>
</div><!--logo_right-->
<br clear="all" />

<a href="<?php bloginfo('rss2_url'); ?>" class="rss"><img src="<?php bloginfo('template_directory'); ?>/images/rss.gif" border="0" alt="RSS" /></a>
</div><!--header-->

<div id="nav_left"><!-- --></div>
<div id="navigation">
<ul id="menu">
<li class="<?php if (((is_home()) && !(is_paged())) or (is_archive()) or (is_single()) or (is_paged()) or (is_search())) { ?>current_page_item<?php } else { ?>page_item<?php } ?>"><a href="<?php echo get_settings('home'); ?>">Home<?php echo $langblog;?></a></li>
<?php wp_list_pages('sort_column=menu_order&depth=1&title_li='); ?>
</ul>
</div><!-- end of navigation -->
<div id="nav_right"><!-- --></div>
<br clear="all" />