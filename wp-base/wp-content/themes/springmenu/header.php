<?php load_theme_textdomain('avenue'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?>
<?php if ( is_home() ) { ?>&nbsp;&raquo;&nbsp;<?php bloginfo('description'); ?><?php } ?>
<?php if ( is_single() ) { ?><?php } ?><?php wp_title(); ?>&nbsp;&laquo;</title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
<meta name="robots" content="index,follow"/>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<link rel="shortcut icon" type="image/x-icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico"/>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_get_archives('type=monthly&format=link'); ?>
<?php wp_head(); ?></head>
<body><a name="top"></a>

<div id="page">
<div id="frame">
<div id="header">

<div class="mid">
<h1 class="blogtitle">
<a href="<?php echo get_settings('home'); ?>" title="<?php bloginfo('name'); ?> Home"><?php bloginfo('name'); ?></a></h1>

<h2 class="subtitle"><?php bloginfo('description'); ?></h2>
</div><!-- End Mid -->
</div><!-- End Header -->

<div id="menu">
<ul><?php wp_list_pages('title_li=&depth=1&'.$page_sort.'&'.$pages_to_exclude)?></ul>
</div><!-- End Menu -->

<div id="visual">
<div class="home"><a href="<?php echo get_settings('home'); ?>" title="<?php bloginfo('name'); ?> Home">
<img src="<?php bloginfo('template_directory'); ?>/images/home.jpg" alt="Home" /></a></div><!-- End Home -->
</div><!-- End Visual -->

</div><!-- End Frame -->