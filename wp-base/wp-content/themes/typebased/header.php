<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
<meta name="description" content="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>" />
<meta name="keywords" content="" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<title><?php bloginfo('name'); wp_title(); ?></title>

<?php wp_head(); ?>
</head>

<body>

<div id="container">

	<div id="header">
		<h1><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('description'); ?>"><img src="<?php if ( get_option('woo_logo') <> "" ) { echo get_option('woo_logo'); ?>" style="margin-top:0px;"<?php } else { ?><?php bloginfo('template_directory'); ?>/<?php woo_style_path(); ?>/logo.jpg<?php } ?>" alt="<?php bloginfo('name'); ?>" /></a></h1>
	</div>
	
	<div id="menu">
		<ul class="wrap">
				<?php if (is_page()) { $highlight = "page_item"; } else {$highlight = "page_item current_page_item"; } ?>
				<li class="<?php echo $highlight; ?> first"><a href="<?php bloginfo('url'); ?>"><span>Home</span></a></li>
                <?php woo_show_pagemenu( get_option('woo_menupages') ); ?>
		</ul>
	</div>