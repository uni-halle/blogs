<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<style type="text/css">
	#headimg {
background-image: url(<?php header_image(); ?>);
	background-repeat: no-repeat;
}
</style>
<!--[if IE 6]><link type="text/css" media="screen" rel="stylesheet" href="<?php get_template_directory_uri(); ?>/ie6.css" /><![endif]-->
<!--[if IE 7]><link type="text/css" media="screen" rel="stylesheet" href="<?php get_template_directory_uri(); ?>/ie7.css" /><![endif]-->
<!--[if IE 8]><link type="text/css" media="screen" rel="stylesheet" href="<?php get_template_directory_uri(); ?>/ie8.css" /><![endif]-->



 <!-- fixed fatal error with posts -->

<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?></head>

<body <?php body_class(); ?>>
	<div id="bgshadow"></div><div id="wrapper"><div id="container"> <div id="top"><div id="rssfeeds">
        <a href="<?php bloginfo_rss('rss2_url'); ?>"></a>      </div></div><div id="middle">
<div id="headimg"><div id="shadow"></div>
  <div id="blogtitle"><h2 align="right">
    <?php bloginfo('name'); ?></h2></div>
  <div id="tag">
<?php bloginfo('description'); ?></div><!-- end #tag -->

<!-- end #searchform --></div> 
 <!-- end #topg -->
				
				
				
<div id="menu">
					
	
					
			
			 
		


<div id="greendrop" class="backgreen"><?php wp_nav_menu();?></div>


		
							
					

				</div><!-- end #menug -->

				


			

