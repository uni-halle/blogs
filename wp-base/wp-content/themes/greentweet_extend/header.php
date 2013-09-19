<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<!--[if lte IE 7]><style media="screen,projection" type="text/css">@import "<?php bloginfo('stylesheet_directory'); ?>/style-ie.css";</style><![endif]-->
	<!--[if IE 6]>
		<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/DD_belatedPNG_0.0.7a-min.js"></script>
		<script type="text/javascript">
		  DD_belatedPNG.fix('#wrapper, #header, #header img, #footer, .body');
		</script>
	<![endif]-->
	<script type="text/javascript" src="<?php echo get_option('home'); ?>/wp-includes/js/jquery/jquery.js"></script>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jqueryslidemenu/jqueryslidemenu.js"></script>
	<!-- /Main Menu -->
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/carousel/stepcarousel.js"></script>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>
</head>

<body <?php if(is_home()) : ?>id="indexpage"<?php endif; ?>>
<div id="wrapper">
<div id="page">

<div id="header">
	<div id="logo">
        <a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a><br /><span><?php bloginfo('description'); ?></span>
    </div>
	<div id="mainmenu">
		<ul>
			<li>
				<a href="#">Navigation</a>
				<ul>
					<li class="<? if(is_home()) echo 'current_page_item'; ?>"><a href="<?php echo get_option('home'); ?>/">Home</a></li>
					<?php wp_list_pages('title_li=&sort_column=menu_order&depth=0&exclude='); ?>
				</ul>
			</li>
		</ul>
	</div>
	<ul id="header_links">
		<li><a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/button_rss.png" alt="Entries (RSS)" /></a></li>
		<?php $twitter_id = obwp_get_meta(SHORTNAME.'_twitter_id'); ?>
		<li><a href="http://www.twitter.com/<?php echo $twitter_id; ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/button_twitter.png" alt="Twitter" /></a></li>
	</ul>
</div>
<div class="body">