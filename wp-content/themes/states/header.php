<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head profile="http://gmpg.org/xfn/11">
	
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :: '; } ?><?php bloginfo('name'); if(is_home()) { echo ' :: '; bloginfo('description'); } ?></title>	
		
		<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />       
		
		<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php bloginfo('template_directory'); ?>/reset-fonts-grids.css" /> 
		<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php bloginfo('stylesheet_url'); ?>" />
		<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php bloginfo('template_directory'); ?>/states.css" />
		
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		
		<?php wp_head(); ?>
	
	</head>
	
	<body>
					
	    <div id="doc2" class="yui-t6 <?php wp_states_state(); ?>">
   
	        <div id="hd"> 
				
				<div id="top" class="yui-g">
					<?php if ( is_front_page() ){ ?>

					    <div id="title" class="yui-u first">
					    	<h1><a href="<?php bloginfo('url'); ?>/" id="blog-title-link">the <span class="blog-title"><?php bloginfo('name'); ?></span> blog</a></h1>
					    </div>
							
					<?php } else { ?>

					    <div id="title" class="yui-u first">
					    	<div><a href="<?php bloginfo('url'); ?>/" id="blog-title-link">the <span class="blog-title"><?php bloginfo('name'); ?></span> blog</a></div>
					    </div>

					<?php } ?>
										
					<div id="tagline" class="yui-u">
				
						<form method="get" class="search_form cmxform" action="<?php bloginfo('home'); ?>/">
							<p>
								<input class="text_input" type="text" value="Search..." name="s" id="s" onfocus="if (this.value == 'Search...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search...';}" />
								<input type="submit" id="searchsubmit" value="Search" />
							</p>
						</form>
						
					</div><!-- end #tagline-->
					
					<div id="seal"><img src="<?php bloginfo('template_directory'); ?>/images/seals/<?php wp_states_state(); ?>-state-seal.png" alt="<?php wp_states_state(); ?>" height="75" width="75" /></div>
					<div id="emblem"></div>

				</div><!-- end .yui-g -->
				
				<div id="nav" class="primary">
						
					<ul>
					
						<li class="page_item page-item-1<?php if (is_front_page()) { ?>  current_page_item<?php } ?>"><a href="<?php bloginfo('url'); ?>/">Home</a></li>
			            <?php wp_list_pages('title_li=&depth=2'); ?>	
				
					</ul>
					<div class="clear"></div>				
				</div><!-- end #nav -->

				<div id="page-heading" class="yui-g">
					<div class="yui-u first">
						<?php breadcrumb_trail(); ?>
						<?php if ( function_exists('yoast_breadcrumb') ) {
							yoast_breadcrumb('<p id="breadcrumbs">','</p>');
						} ?>
					</div>
					<div class="yui-u alignright">
						<a class="rss" rel="nofollow" href="<?php bloginfo('rss2_url'); ?>">Subscribe to RSS Updates</a>				
					</div>
				</div><!-- end #page-heading -->
								        
	        </div><!--end #hd -->