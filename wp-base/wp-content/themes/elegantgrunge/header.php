<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title>
		<?php if ( is_home() ) { ?><?php bloginfo('description'); ?> | <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_search() ) { ?><?php echo $s; ?> | <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_single() ) { ?><?php wp_title(''); ?> | <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_page() ) { ?><?php wp_title(''); ?> | <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_category() ) { ?><?php _e('Archive', 'elegant-grunge') ?> <?php single_cat_title(); ?> | <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_month() ) { ?><?php _e('Archive', 'elegant-grunge') ?> <?php the_time('F'); ?> | <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_tag() ) { ?><?php single_tag_title();?> | <?php bloginfo('name'); ?><?php } ?>
</title>

<?php elegant_grunge_the_favicon() ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<?php if ( get_option('header_image') ) : ?>
<style type="text/css">
#header div {
	background: url(<?php echo get_option('header_image') ?>) no-repeat center top;
	width: 100%;
	height: 100%;
	display: block;
}
#header * {
	display: none;
}
</style>
<?php endif; ?>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/ie.css" />
<style type="text/css">
#footer #subscribe a {
	background:none;
	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_url')?>/images/rss.png');
}
<?php if ( get_option('header_image') ) : ?>
#header div {
	background: none;
	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo get_option('header_image')?>');
}
<?php endif; ?>
</style>
<![endif]-->

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php echo get_option("extra_header") ?>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>

</head>

<?php
if ( !defined('EG_BODY_CLASS') && get_option('page_setup') != 'right-sidebar' )
	define('EG_BODY_CLASS', get_option('page_setup'));
?>

<body <?php if ( defined('EG_BODY_CLASS') ) echo 'class="'.EG_BODY_CLASS.'"'; ?>>

<div id="page">

<div id="menu">
	<ul>
		<li class="page_item <?php if ( is_home() ) { ?>current_page_item<?php } ?>"><a href="<?php bloginfo('url'); ?>"><?php _e('Home', 'elegant-grunge') ?></a></li>
		<?php wp_list_pages('title_li=&depth=1'); ?>
	</ul>
	<div class="clear"></div>
</div>

<div id="header-wrap">
<div id="header">
	<div>
		<h1><a href="<?php bloginfo('home') ?>"><?php bloginfo('name'); ?></a></h1>
		<span id="blog-description"><?php bloginfo('description'); ?></span>
	</div>
</div>
</div>

<!-- end header -->