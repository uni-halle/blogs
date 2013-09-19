<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
  <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
  <link rel="shortcut icon" href="http://vikiworks.com/favicon.png" type="image/png" />
  <title><?php if (is_home () ) { bloginfo('name'); } elseif ( is_category() ) { single_cat_title(); echo " - "; bloginfo('name'); } elseif (is_single() || is_page() ) { single_post_title(); } elseif (is_search() ) { bloginfo('name'); echo " search results: "; echo wp_specialchars($s); } else { wp_title('',true); } ?></title>
  
	<!--[if lte IE 6]>
	<style type="text/css">
		#main-wrapper, #left-sidebar, #right-sidebar, .rss  { behavior: url("<?php bloginfo('template_directory'); ?>/js/iepngfix.htc") }
	</style>
	<![endif]-->

  
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" title="vs" />
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/reset.css" type="text/css" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
    <script src="<?php bloginfo('template_directory'); ?>/js/smoothscroll.js" type="text/javascript"></script>

<?php wp_head(); ?>
</head>

<body>

<div id="header" class="fix">
	

	<ul class="topnav">
	<li><a href="<?php echo get_settings('home'); ?>/" title="Home">Startseite</a></li>
<?php wp_list_pages('sort_column=menu_order&title_li='.$page_sort)?>
	<li class="rss"><a href="http://yourFeedUrl" title="RSS abonieren">RSS</a></li>
	</ul>
<h1><a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
	</div>


<!-- main wrapper -->
<div id="main-wrapper">
