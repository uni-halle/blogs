<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!-- Favicon -->
<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" type="image/x-icon" />
<link rel="icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" type="image/x-icon" />

<!-- iOS Homescreen Icons -->
<link rel="apple-touch-icon" href="<?php bloginfo('stylesheet_directory'); ?>/images/Icon.png" /> <!-- iPhone -->
<link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo('stylesheet_directory'); ?>/images/Icon-72.png" /> <!-- iPad -->
<link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('stylesheet_directory'); ?>/images/Icon@2x.png" /> <!-- iPhone 4 -->


<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
		
	wp_head();
?>
<!-- Google +1 Schaltflaeche -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
  {lang: 'de'}
</script>
</head>

<body <?php body_class(); ?>>
<div id="wrapper" class="hfeed">
	<div id="header">
		<div id="masthead">
			<div id="branding" role="banner">
				<div id="site-description"><a href="http://www.onlineradiomaster.de" title="Zur ONLINE RADIO MASTER Website"><?php bloginfo( 'description' ); ?></a></div>
				<!-- Header Link, aber nicht auf Startseite -->
				<?php if ( !is_home() ) { ?>
				
				<a href="/" title="Zur Blog Startseite">
				
				<?php }
					// Check if this is a post or page, if it has a thumbnail, and if it's a big one
					if ( is_singular() &&
							has_post_thumbnail( $post->ID ) &&
							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) &&
							$image[1] >= HEADER_IMAGE_WIDTH ) :
						// Houston, we have a new header image!
						echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
					else : ?>
						
						<!-- HARDCODED HEADER IMAGE MIT HTTP -->
						<img src="/files/2010/12/ormablog_v2_jpeg2000.jpg" width="940" height="198" alt="ONLINE RADIO MASTER Banner">
						<!-- END HARDCODED HEADER IMAGE -->
						
						<!-- <img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" /> -->
					<?php endif; ?>
				
				<!-- Header Link Ende, aber nicht auf Startseite -->
				<?php if ( !is_home() ) { ?>
				
				</a>
				
				<?php } ?>
				
			</div><!-- #branding -->

			<div id="access" role="navigation">
			  <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
				<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentyten' ); ?>"><?php _e( 'Skip to content', 'twentyten' ); ?></a></div>
				<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
				<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
			</div><!-- #access -->
		</div><!-- #masthead -->
	</div><!-- #header -->

	<div id="main">
