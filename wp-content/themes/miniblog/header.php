<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head profile="http://gmpg.org/xfn/11">
		<!--WP Head-->
			<?php wp_head(); ?>
		<!--End of WP Head-->
	
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_url');?>/style.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_url');?>/pagenavi-css.css" />
		<link rel="shortcut icon" type="image/png" href="<?php bloginfo('template_url');?>/images/favicon.png" />
		<?php include (TEMPLATEPATH . '/new-style.php'); ?>

		<!--For Theme Options-->
			<?php
				global $options;
				foreach ($options as $value) {
				if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
				}
			?>
		<!--End of For Theme Options-->
	
		<!--IE Fixer Stylesheet-->
			<!--[if lte IE 7]>
				<style type="text/css">
					#sidebar-recent-posts,
					#sidebar-popular-posts,
					#sidebar-recent-comments,
					#sidebar-search,
					#sidebar-twitter,
					#sidebar-categories{
						display: none;
					}
				</style>
			<![endif]-->
		<!--End of IE Fixer Stylesheet-->
		
		<!--For IEPNGFix-->
			<!--[if lte IE 6]>
				 <style type="text/css">
					div, h2, span, a{
						behavior: url("<?php bloginfo('template_url');?>/iepngfix/iepngfix.htc") 
					}
				</style>
				<script type="text/javascript" src="<?php bloginfo('template_url');?>/iepngfix/iepngfix_tilebg.js"></script>
			<![endif]-->
		<!--End of For IEPNGFix-->
		
		<!--For Input Fields-->
			<script type="text/javascript">
				function clickclear(thisfield, defaulttext) {
				if (thisfield.value == defaulttext) {
				thisfield.value = "";
				}
				}
				 function clickrecall(thisfield, defaulttext) {
				if (thisfield.value == "") {
				thisfield.value = defaulttext;
				}
				}
			</script>
		<!--End of For Input Fields-->
		
		<!--For StereoTabs-->
			<script type="text/javascript" src="<?php bloginfo('template_url');?>/js/prototype.js"></script>
			<script type="text/javascript" src="<?php bloginfo('template_url');?>/js/effects.js"></script>
			<script type="text/javascript" src="<?php bloginfo('template_url');?>/js/stereotabs.js"></script>
			<script type="text/javascript">
				//<![CDATA[
				Event.observe(window, 'load', loadTabs, false);
				function loadTabs() {
					var tabs = new tabset('tab-container'); // name of div to crawl for tabs and panels
					tabs.autoActivate($('tab_first')); // name of tab to auto-select if none exists in the url
				}
				//]]>
			</script>		
		<!--End of For StereoTabs-->

		<title>
			<?php if (is_home () ) { bloginfo('name'); echo ' - ' ; bloginfo('description');}
			elseif ( is_category() ) { single_cat_title(); echo ' - ' ; bloginfo('name');}
			elseif (is_single() ) { single_post_title(); echo ' - ' ; bloginfo('name');}
			elseif (is_page() ) { single_post_title(); echo ' - ' ; bloginfo('name');}
			else { wp_title('',true); } ?>
		</title>
		
	</head>
	<body>
		<div id="navbar">
			<div id="navbar-container">
				<ul>
					<?php if (is_home()) { ?>
						<li class="current_page_item">
							<a href="<?php echo get_settings('home'); ?>" title="Home">Home</a>
						</li>
					<?php }else{ ?>
						<li class="page_item">
							<a href="<?php echo get_settings('home'); ?>" title="Home">Home</a>
						</li>
					<?php } ?>
					<?php wp_list_pages('sort_column=menu_order&depth=1&title_li=');?>
					<?php if($mb_show_rss == "true") { ?>
					<li class="rss-feed">
						<a href="<?php bloginfo('rss2_url'); ?>"><span class="rss-icon">My RSS Feed</span></a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<div id="header">
			<div id="header-container">
				<div id="bubble-container">
					<div id="bubble-left"></div>
					<div id="bubble-middle">
						<h1><a href="<?php echo get_settings('home'); ?>"><span class="first-color"><?php echo $mb_title_first;?></span><span class="second-color"><?php echo $mb_title_second;?></span></a></h1>
						<span id="site-slogan"><?php echo stripslashes($mb_slogan);?></span>
					</div>
					<div id="bubble-right"></div>
				</div>
				<div id="bubble-bottom"></div>
			</div>
		</div>
		<div id="container-top"></div>
		<div id="container">
			<div id="main">
<!--End of Header.php-->