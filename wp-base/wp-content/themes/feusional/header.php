<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php if ( is_home() ) { ?><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?><?php } else { ?><?php wp_title($sep = ''); ?> - <?php bloginfo('name'); ?><?php } ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo TEMPLATEPATH.'/pagenavi-css.css';?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 1.0" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_get_archives('type=monthly&format=link'); ?>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<!--[if lte IE 6]>
<link rel="stylesheet" media="all" type="text/css" href="<?php bloginfo('template_url'); ?>/ie.css" />
<![endif]-->
<?php wp_head(); ?>
</head>
<body>
	<div id="menu">
		<ul>
			<li<?php if ( is_home() ) : ?> class="current_page_item"<?php endif; ?>><a href="<?php echo get_option('home'); ?>/">Home</a></li>
			<?php wp_list_pages("depth=1&title_li=") ;?>
			<li class="feed fright"><a href="<?php bloginfo('rss2_url'); ?>">Subscribe</a></li>
		</ul> 
	</div><!--end of #menu -->	 
	<div id="wrapper">
		<div id="header">
			<h1 id="logo"><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
			<div class="clear"></div>
		</div><!--end of #header -->