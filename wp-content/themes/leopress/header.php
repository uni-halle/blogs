<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php bloginfo('name'); ?><?php if ( is_home() ) { ?>- <?php bloginfo('description'); ?><?php } elseif ( is_single() ) { ?><?php } ?><?php wp_title(); ?>
</title>
<meta name="robots" content= "all" />
<meta name="revisit-after" content="1 day" />
<meta name="title" content="7 Graus - www.7graus.com" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
<meta http-equiv="imagetoolbar" content="no" />

<style type="text/css" media="screen">
<!--
@import url("<?php bloginfo('stylesheet_url'); ?>");
-->
</style>
<!--[if lt IE 7]><link href="<?php bloginfo('template_directory'); ?>/css/ie6.css" rel="stylesheet" type="text/css" /></script><![endif]-->
<!--[if IE 7]><link href="<?php bloginfo('template_directory'); ?>/css/ie7.css" rel="stylesheet" type="text/css" /><![endif]-->
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>

</head>

<body>
<div id="doc">
<div id="header">
<div id="header-content">
  <p <?php if (is_home()) { ?>id="homelink"<?php } ?> class="onhome"><a href="<?php bloginfo('home'); ?>" class="url" title="Home" rel="home">Home</a></p>
  <h1><?php bloginfo('name'); ?><?php if ( is_home() ) { ?> - <?php bloginfo('description'); ?><?php } else { ?> <?php wp_title(' &gt;'); ?><?php } ?></h1>

<?php include (TEMPLATEPATH . '/searchform.php'); ?>  
</div>
</div><!-- #header -->
<div id="content">