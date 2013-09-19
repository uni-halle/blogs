<!DOCTYPE html>

<html lang="en-gb">

	<head>

		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		
		<title><?php if (is_single() || is_home() || is_page() || is_archive()) { ?><?php bloginfo('name'); ?> <?php } ?><?php wp_title('&mdash;',true); ?></title>
		
		<meta name="description" content="<?php bloginfo('name'); ?>, <?php bloginfo('description'); ?>" />
		
		<meta name="keywords" content="design, web, site, accessibility, readability, usability, simplicity, minimalism" />
		
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
		
		<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
		
		<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />

		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

		<?php wp_head(); ?>
		
	</head>
	
	<body>
	
	<div id="page">
	
		<a class="skip_nav" href="#content">Skip to content</a>
	
		<div id="main_search">
		
			<form id="searchform" class="blog-search" method="get" action="<?php bloginfo('home') ?>">
						
				<div>
		
					<input id="s" name="s" type="text" class="text" value="<?php the_search_query() ?>" size="10" tabindex="1" />
					<input type="submit" class="button" value="Search" tabindex="2" />
				</div>
					
			</form>
			
		</div>
	
		<div id="header">
	
			<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
			
		</div>
		
		<p class="tag"><?php bloginfo('description'); ?></p>
		
		<div id="main"> <!-- for IE's rubbish clearing of elements -->
		
			<ul class="nav">
			
				<?php

				if (is_front_page()) {
			
					echo "<li class=\"current_page_item\">Home</li>";
				
				}
			
				else {
	              
					echo "<li><a href=\""; bloginfo('url'); echo "\">Home</a></li>";
	        
				}
			
				?>
				
					<?php wp_list_pages('title_li=&depth=1&sort_column=menu_order'); ?>
					
					<li><a href="<?php bloginfo('rss2_url'); ?>">Subscribe to RSS feed</a></li>
					
			</ul>
