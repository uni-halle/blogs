<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/1">
	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats please -->
	<style type="text/css" media="screen">
		@import url( <?php bloginfo('stylesheet_url'); ?> );
	</style>
<link rel="shortcut icon" href="" type="image/vnd.microsoft.icon" />
<link rel="icon" href="" type="image/gif" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php wp_get_archives('type=monthly&format=link'); ?>
	<?php //comments_popup_script(); // off by default ?>
	<?php wp_head(); ?>
</head>
  <body>
<div id="header"></div>
<div id="menu">
	<div id="logo">
		<div id="h1"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></div>
		<div id="h2" class="description"><?php bloginfo('description'); ?></div>
	</div>
	<ul>
		<li <?php if(is_home()){echo 'class="current_page_item"';}?>><a href="<?php bloginfo('siteurl'); ?>" title="Home">Home</a></li>
		<?php wp_list_pages('title_li=&depth=1');?>
	</ul>
	<div class="header-strip"> </div>
</div>