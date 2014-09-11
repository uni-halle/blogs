<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head>
  <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<title><?php wp_title(':', true, 'right'); ?> <?php bloginfo('name'); ?></title>
	
<!--[if gte IE 7]><!-->
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" charset="utf-8" />
<!-- <![endif]-->

<!--[if IE 7]>
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style_ie.css" type="text/css" media="screen" charset="utf-8" />
<![endif]-->

<!--[if IE 6]>
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_directory'); ?>/styles_ie6.css" />
<![endif]-->

  <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
  <link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  <?php wp_head(); ?>
</head>

<body>


<div id="siteWrapper">

  <h1 class="vcard author"><a href="<?php echo get_option('home'); ?>/" title="Home" class="fn"><?php  bloginfo('name');?></a></h1> 

  <div id="mainNav">
    <ul>
      <?php wp_list_pages('title_li=&depth=1'); ?>
    </ul>
  </div>
  
  <div id="siteDescription">
    <?php  bloginfo('description'); ?>
  </div>
