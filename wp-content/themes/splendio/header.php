<?php
/**
 * The Header for our theme.
 */
?><!DOCTYPE html>
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title>
<?php global $page, $paged;	wp_title( '|', true, 'right' );	bloginfo( 'name' );	$site_description = get_bloginfo( 'description', 'display' );	if ( $site_description && ( is_home() || is_front_page() ) ) echo " | $site_description";	if ( $paged >= 2 || $page >= 2 ) echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );	?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url'); ?>/style-print.css"  />
<link  href="http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold" rel="stylesheet" type="text/css" >
<link rel="shortcut icon" href="<?php bloginfo( 'template_url'); ?>/images/favicon.ico" type="image/x-icon" />
<script type='text/javascript' src="<?php bloginfo('template_url'); ?>/js/jquery-1.4.1.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.color.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        if(!$.browser.msie) $("ul li").hover(function() {$(this).siblings().stop().fadeTo(400,0.4);}, function() { $(this).siblings().stop().fadeTo(400,1); });
    });
</script>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
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
?>
</head>
<body <?php body_class(); ?>>
<!-- Start - Wrapper -->
<div id="wrapper">
<!-- Start header -->
<div id="header">    
 <div class="header-left">
   <div class="logo">
  <?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>				
  <<?php echo $heading_tag; ?> class="site-title">
  <span><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span>
  </<?php echo $heading_tag; ?>>
  <div class="site-desc"><?php bloginfo( 'description' ); ?></div>  
  </div>    
  <!-- Start access -->
  <div id="access" role="navigation">
  <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
  <div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentyten' ); ?>"><?php _e( 'Skip to content', 'twentyten' ); ?></a></div>
  <?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
  <?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
  </div>
 <!-- End access -->   
</div> 
<div class="header-right">
 <div class="search">
  <form method="get" action="#">
   <fieldset>
   <p class="search-title">Search</p>
   <input type="text" value="" name="s" /><button type="submit">GO</button>
   </fieldset>
  </form>
 </div>
 <div class="syndicate">
  <ul>
   <li><a class="s1" href="#" title="RSS"><em>RSS Feed</em></a></li>
   <li><a class="s2" href="#" title="Twitter"><em>Twitter</em></a></li>
   <li><a class="s3" href="#" title="FaceBook"><em>FaceBook</em></a></li>
   <li><a class="s4" href="#" title="Share"><em>Share</em></a></li>
  </ul>
 </div>
</div>    
<div class="header-image" role="banner">
 <img src="<?php header_image(); ?>" alt="" />
</div>             
</div>
<!-- End header -->
<!-- Start Container -->
<div id="container">