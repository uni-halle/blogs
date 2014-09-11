<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<title><?php wp_title(' '); ?><?php if(wp_title(' ', false)) { ?> at <?php } ?><?php bloginfo('name'); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
<meta name="description" content="<?php bloginfo('description'); ?>" />
<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="shortcut icon" type="image/x-png" href="<?php bloginfo('template_url'); ?>/favicon.png" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
</head>
<body>
<div id="wrap" class="group">
<div id="header">
<h1><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a> &nbsp; <?php bloginfo('description'); ?></h1>
</div>
<div id="menu" class="group">
<div class="corner-TL"></div> <div class="corner-TR"></div>
	<ul>
	<li><a href="<?php bloginfo('url'); ?>/">Home</a></li>
	<?php wp_list_pages('title_li=&depth=1'); ?>
	</ul>
    <div id="feed"><a href="<?php bloginfo('rss2_url'); ?>">RSS Feed</a></div>
<div class="corner-BL"></div> <div class="corner-BR"></div>
</div>