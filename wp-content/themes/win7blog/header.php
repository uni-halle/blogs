<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">

<title>
		<?php if ( is_home() ) { ?><?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_search() ) { ?><?php echo $s; ?> | <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_single() ) { ?><?php wp_title(''); ?> | <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_page() ) { ?><?php wp_title(''); ?> | <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_category() ) { ?><?php single_cat_title(); ?> | <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_month() ) { ?><?php the_time('F'); ?> | <?php bloginfo('name'); ?><?php } ?>
		<?php if ( is_tag() ) { ?><?php single_tag_title();?> | <?php bloginfo('name'); ?><?php } ?>
</title>

	<?php global $win7blog_options;
	if (is_home()){
		if ($win7blog_options["homepage_desc"] != "") {
			$description = $win7blog_options["homepage_desc"]; ?>
			<meta name="description" content="<?php echo $description ?>" />
	<?php }
		if ($win7blog_options["homepage_keywords"] != "") {
			$keywords = $win7blog_options["homepage_keywords"]; ?>
			<meta name="keywords" content="<?php echo $keywords ?>" />
	<?php }
	} elseif (is_single()){
	    if ($post->post_excerpt) {
	        $description = $post->post_excerpt;
	    } else {
	        $description = win7blog_substr(strip_tags($post->post_content), 220);
	    }

	    $keywords = "";
	    $tags = wp_get_post_tags($post->ID);
	    foreach ($tags as $tag ) {
	        $keywords = $keywords . $tag->name . ", ";
	    }
	}

	if ($win7blog_options["stickypost_style"] == "Simple") { ?>
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory') ?>/patterns/sticky-simple.css" />
	<?php
	} elseif ($win7blog_options["stickypost_style"] == "Rich") { ?>
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory') ?>/patterns/sticky-rich.css" />
	<?php
	}
	?>

	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url') ?>" />
	<link rel="shortcut icon" href="<?php bloginfo('template_directory') ?>/images/favicon.ico" />
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head() // For plugins ?>
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="<?php printf( __( '%s latest posts', 'win7blog' ), wp_specialchars( get_bloginfo('name'), 1 ) ) ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'win7blog' ), wp_specialchars( get_bloginfo('name'), 1 ) ) ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
</head>

<body <?php if (is_home() && !is_paged()): ?>id="home"<?php endif ?>>
<div id="wrapper" class="hfeed">

	<div id="header">

		<div id="title">
			<h1><a href="<?php bloginfo('home') ?>/"><?php bloginfo('name'); ?></a>
				<div id="blog-description"><?php bloginfo('description') ?></div>
			</h1>
 		</div>

		<?php win7blog_globalnav() ?>
		<div id="win7blog_search">
			<form id="searchform" class="blog-search" method="get" action="<?php bloginfo('home') ?>">
				<div>
					<input id="win7blog_s" name="s" type="text" class="text" value="<?php the_search_query() ?>" size="18" tabindex="1" />
					<input id="w7b_search_btn1" type="submit" class="button" value="<?php _e('Search','win7blog'); ?>" tabindex="2" />
				</div>
			</form>
		</div>
	</div>



