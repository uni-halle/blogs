<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="shortcut icon" type="image/ico" href="<?php bloginfo('stylesheet_directory'); ?>/images/favicon.ico" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/scripts/basic.js" type="text/javascript"></script>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>

<body>

<div id="page_wrap">
	<div id="header">
		<div class="blog_title">
			<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
			<p class="description"><?php bloginfo('description'); ?></p>
		</div>
		
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>
		
		<div class="clear"></div>
	</div><!-- end header -->
	
	<div id="main_navi">
		<ul class="left">
			<li<?php if(is_home()&&!is_paged()) echo ' class="current_page_item"'; ?>><a href="<?php bloginfo('url'); ?>"><?php _e('HOME', 'pyrmont_v2'); ?><!--end--></a></li>
			<?php wp_list_pages('title_li=&depth=2'); ?>
    	</ul>
		
		<ul class="right">
			<!--<li class="twitter"><a href="http://twitter.com/your_user_name" title="<?php _e('Follow me on twitter', 'pyrmont_v2'); ?>">twitter</a></li>-->
			<li class="feed"><a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Subscribe', 'pyrmont_v2'); ?> <?php bloginfo('name'); ?>"> <?php _e('rss feed','pyrmont_v2');?></a></li>
		</ul>
	</div><!-- end main_navi -->
	<div class="clear"></div>