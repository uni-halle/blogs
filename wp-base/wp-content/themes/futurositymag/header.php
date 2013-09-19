<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php sandbox_blog_lang(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<title><?php bloginfo('name'); if ( is_404() ) : _e(' &raquo; ', 'sandbox'); _e('Not Found', 'sandbox'); elseif ( is_home() ) : _e(' &raquo; ', 'sandbox'); bloginfo('description'); else : wp_title(); endif; ?></title>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
	<meta name="description" content="<?php bloginfo('description') ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version') ?>" /><!-- Please leave for stats -->
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Posts RSS feed', 'sandbox'); ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Comments RSS feed', 'sandbox'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
	
	<!-- needed for apple style search box -->
	<link rel="stylesheet" type="text/css" href="default.css" id="default"  />
	<!-- dummy stylesheet - href to be swapped -->
	<link rel="stylesheet" type="text/css" href="dummy.css" id="dummy_css"  />
	<script type="text/javascript" src="applesearch.js"></script>
	<script type="text/javascript">
	//<![CDATA[
		window.onload = function () { applesearch.init(); }
	//]]>
	</script>
	<!-- #apple style search -->


<?php wp_head() ?>
</head>

<body class="<?php sandbox_body_class() ?>">

<div id="wrapper" class="hfeed">

	<div id="header">
	<ul id="pages">
<?php wp_list_pages('title_li=&sort_column=post_title&sort_order=desc&depth=1' ) ?>
				</ul>

<h1><a class="blog-title" href="<?php echo get_settings('home') ?>/" title="<?php bloginfo('name') ?>" rel="home"><?php bloginfo('name') ?></a><span> | <?php bloginfo('description') ?></span></h1>
	<div id="blog-description"></div>
	<?php include (TEMPLATEPATH . '/searchform.php'); ?>
	</div><!--  #header -->

	<div id="access">
		<div class="skip-link"><a href="#content" title="<?php _e('Skip navigation to the content', 'sandbox'); ?>"><?php _e('Skip to content', 'sandbox'); ?></a></div>
	</div><!-- #access -->