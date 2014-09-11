<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
	<title><?php bloginfo('name'); if ( is_404() ) : _e(' » '); _e('Not Found'); elseif ( is_home() ) : _e(' » '); bloginfo('description'); else : wp_title(); endif; ?>:</title>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
<!--	<meta name="description" content="<?php bloginfo('description') ?>" /> -->
	<meta name="generator" content="WordPress <?php bloginfo('version') ?>" /><!-- Please leave for stats -->
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Posts RSS feed'); ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Comments RSS feed'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />



<meta name="verify-v1" content="IBtNiMo4vNVXvlwJxbrVIbFaVZ1NBh59InQkrh/Fwfg=" />

<?php wp_head() ?>

</head>

<body>
<div id="outerWrap">
<div id="wrapper">

	<div id="container">
		<div id="content">

			<div id="header">
				<h1><a href="<?php echo get_option('home') ?>/" title="<?php bloginfo('name') ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
				<h2><?php bloginfo('description'); ?></h2>

		
			</div><!--  #header -->
