<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<title><?php if (is_single() || is_page() || is_archive()) { wp_title('',true); } else { bloginfo('description'); } ?> &#8212; <?php bloginfo('name'); ?></title>

	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/custom.css" type="text/css" media="screen" />
	<!--[if lte IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/ie7.css" />
	<![endif]-->
	<!--[if lte IE 6]>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/ie6.css" />
	<![endif]-->
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php wp_head(); ?>
</head>
<body class="custom">

<div id="container">
<div id="page">
	<div id="masthead">
		<div id="rss_subscribe"><a href="<?php bloginfo('rss2_url'); ?>" rel="nofollow">Subscribe via RSS</a></div>
		<div id="logo"><a href="<?php bloginfo('url'); ?>"<?php if (is_home()) echo(' rel="nofollow"'); ?>><?php bloginfo('name'); ?></a></div>
		<?php if (is_home()) { ?><h1><?php bloginfo('description'); ?></h1><?php } else { ?><div id="tagline"><?php bloginfo('description'); ?></div><?php } ?>
		
	</div>
	<div id="rotating_image">
<?php include (TEMPLATEPATH . '/rotating_images.php'); ?>
	</div>