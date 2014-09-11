<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<!--[if IE]><link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie.css" type="text/css" media="screen" /><![endif]-->
<!--[if lt IE 8]>
<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta)/IE8.js" type="text/javascript"></script>
<![endif]-->

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_head(); ?>

</head>

<body>

<div id="wrap">

    <div id="header">
    
    	<!-- image randomizer -->
    	<a href="<?php bloginfo('wpurl'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/polaroid/bg-polaroid.php" alt="Header" /></a>

    	<div id="title">           
            <h1><a href="<?php bloginfo('wpurl'); ?>"><?php bloginfo('name'); ?></a></h1>
            <p class="description"><?php bloginfo('description'); ?></p>           
        </div>
        
    </div>
    
    <div id="top-right"></div>
    
	<span id="rss-big"><a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>" ></a></span>
     
    <div id="contentwrap">
    
        <div id="menu">
            <ul>
                <li><a href="<?php bloginfo('wpurl'); ?>" <?php if (is_home()) { ?>class="current"<?php } ?>>Home</a></li>
                <?php wp_list_pages('title_li=&depth=1'); ?> 
            </ul>
        </div>
    