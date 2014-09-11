<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">

<title>
    <?php if ( is_home() ) { ?><?php bloginfo('description'); ?>&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>
    <?php if ( is_search() ) { ?>Search Results&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>
    <?php if ( is_author() ) { ?>Author Archives&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>
    <?php if ( is_single() ) { ?><?php wp_title(''); ?><?php } ?>
    <?php if ( is_page() ) { ?><?php wp_title(''); ?><?php } ?>
    <?php if ( is_category() ) { ?><?php single_cat_title(); ?>&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>
    <?php if ( is_month() ) { ?><?php the_time('F'); ?>&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>
    <?php if (function_exists('is_tag')) { if ( is_tag() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Tag Archive&nbsp;|&nbsp;<?php single_tag_title("", true); } } ?>
</title>

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<?php if (is_home()) { ?>

    <?php if ( get_option('bizzthemes_meta_description') <> "" ) { ?><meta name="description" content="<?php echo stripslashes(get_option('bizzthemes_meta_description')); ?>" /><?php } ?>
    <?php if ( get_option('bizzthemes_meta_keywords') <> "" ) { ?><meta name="keywords" content="<?php echo stripslashes(get_option('bizzthemes_meta_keywords')); ?>" /><?php } ?>
    <?php if ( get_option('bizzthemes_meta_author') <> "" ) { ?><meta name="author" content="<?php echo stripslashes(get_option('bizzthemes_meta_author')); ?>" /><?php } ?>

<?php } ?>

<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />

    <?php if ( get_option('bizzthemes_customcss') ) { ?><link href="<?php bloginfo('template_directory'); ?>/custom/custom.css" rel="stylesheet" type="text/css"><?php } ?>

<?php if ( get_option('bizzthemes_favicon') <> "" ) { ?><link rel="icon" type="image/png" href="<?php echo get_option('bizzthemes_favicon'); ?>" /><?php } ?>

<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( get_option('bizzthemes_feedburner_url') <> "" ) { echo get_option('bizzthemes_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE7.js" type="text/javascript"></script>
<![endif]-->

<?php if ( get_option('bizzthemes_scripts_header') <> "" ) { echo stripslashes(get_option('bizzthemes_scripts_header')); } ?>
<?php wp_enqueue_script('jquery'); ?> 

<?php wp_head(); ?>

</head>

<body>

	<div class="wrapper">
	
	<div class="top_menu">
	
	    <div class="fl">
	
            <ul id="pagenav" class="page-menu">
			
			    <li <?php if ( is_home() ) { ?> class="current_page_item" <?php } ?>><a href="<?php echo get_option('home'); ?>/">Home</a></li>
					            
			    <?php wp_list_pages('title_li=&depth=0&exclude=' . get_inc_pages("pag_exclude_") .'&sort_column=menu_order'); ?>
			        
		    </ul> 
	   
	    </div>

	    <div class="fr">
		    
			<div class="subscribe">
			
			    <?php if ( get_option('bizzthemes_feedburner_url') <> "" ) { ?> 
			
			        <a href="<?php echo get_option('bizzthemes_feedburner_url'); ?>"><span class="rss-button">RSS</span></a>
					
					&nbsp;&rarr; <a href="<?php echo get_option('bizzthemes_feedburner_url'); ?>"><?php echo get_option('bizzthemes_subscribe_name'); ?></a>
			
			    <?php } else { ?>
			
			        <a href="<?php echo get_bloginfo_rss('rss2_url'); ?>"><span class="rss-button">RSS</span></a>
					
					&nbsp;&rarr; <a href="<?php echo get_bloginfo_rss('rss2_url'); ?>"><?php echo get_option('bizzthemes_subscribe_name'); ?></a>
				 
			    <?php } ?>
				
				<?php

	                $feed_count = stripslashes(get_option('bizzthemes_feedcount'));
					$feed_count = preg_replace('/width=\"\d\d\"/i', 'width="88"', $feed_count);
					$feed_count = preg_replace('/height=\"\d\d\"/i', 'height="26"', $feed_count);
					$feed_count = preg_replace('/bg=\w\w\w\w\w\w/i', 'bg=ffffff', $feed_count);
					$feed_count = preg_replace('/fg=\w\w\w\w\w\w/i', 'fg=444444', $feed_count);
					$feed_count = preg_replace('/<p>/i', '<div class="chicklet">', $feed_count);
					$feed_count = preg_replace('/<\/p>/i', '</div>', $feed_count);
					
                    if ( get_option('bizzthemes_feedcount') <> "" ) { echo $feed_count; } 
					
				?>
			
			</div>
			
	    </div>
	
	    </div>
		
		<!-- AdSense Top Single: START -->
	
	        <?php if (get_option('bizzthemes_header_adsense') <> "") { ?>
					
			    <div class="adsense-728">
		
		            <?php echo stripslashes(get_option('bizzthemes_header_adsense')); ?>
		
		        </div>
												
		    <?php } ?>	
		
        <!-- AdSense Top Single: END -->
		
		<div class="page">

			<div class="content <?php if ( !get_option('bizzthemes_right_sidebar') ) { echo 'content_right'; } else { echo 'content_left'; } ?>">

                <div id="header">
				
				    <?php if ( get_option('bizzthemes_show_blog_title') ) { ?>
			
			            <div class="blog-title"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></div>
			
		                <div class="blog-description"><?php bloginfo('description'); ?></div>
			
		            <?php } else { ?>
				
                        <h1 class="logo">
			
			                <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>">
			
			                    <img src="<?php if ( get_option('bizzthemes_logo_url') <> "" ) { echo get_option('bizzthemes_logo_url'); } else { echo get_bloginfo('template_directory').'/images/logo-trans.png'; } ?>" alt="<?php bloginfo('name'); ?>" />
				    
					        </a>
				
			            </h1><!--/logo-->

	                <?php } ?>	
					
				</div>
				