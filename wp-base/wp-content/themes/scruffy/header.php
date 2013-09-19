<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

	<title>
		<?php if ( is_home() ) { ?><?php bloginfo('name'); ?> &raquo; <?php bloginfo('description'); ?><?php } ?>
		<?php if ( is_search() ) { ?>Search Results &raquo; <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_author() ) { global $wp_query; $curauth = $wp_query->get_queried_object(); ?>Author Archives &raquo; <?php echo $curauth->nickname; ?> &raquo; <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_single() ) { ?><?php wp_title(''); ?> &raquo; <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_page() ) { ?><?php wp_title(''); ?> &raquo; <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_category() ) { ?><?php single_cat_title(); ?> &raquo; Archive &raquo; <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_year() ) { ?><?php the_time('Y'); ?> &raquo; Archive &raquo; <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_month() ) { ?><?php the_time('F Y'); ?> &raquo; Archive &raquo; <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_day() ) { ?><?php the_time('d F Y'); ?> &raquo; Archive &raquo; <?php bloginfo('name'); ?><?php } ?>
		<?php if (function_exists('is_tag')) { if (is_tag()) { ?><?php single_tag_title("", true); ?> &raquo; Tag Archive &raquo; <?php bloginfo('name'); ?><?php } } ?>
	</title>

	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="description" content="<?php bloginfo('description') ?>" />

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/960gs/960.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1); ?> Comments RSS feed" />
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
	<!--[if lte IE 6]>
	<script defer type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/pngfix.js"></script>
	<![endif]-->
	
	<?php wp_get_archives('type=monthly&format=link'); ?>
	<?php wp_head(); ?>
	
</head>

<body <?php if(is_page()): echo 'class="altbg"'; endif;?>>

<div id="container">

	<div id="head">
		
		<h1><a href="<?php bloginfo('home'); ?>" title="Home"><?php bloginfo('name'); ?></a></h1>
		
		<div id="nav">
			<ul>
				<li class="first <?php if (is_home()){ ?>current_page_item<?php ;} ?>"><a href="<?php bloginfo('home'); ?>" title="Home">Home</a></li>
				<?php wp_list_pages('title_li=&depth=1'); ?>
			</ul>
		</div><!-- /nav -->
		
	</div><!-- /head -->


	<div id="main" <?php if (!is_home()): echo 'class="alt"'; endif; ?>>