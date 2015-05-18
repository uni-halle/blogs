<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <main id="main">
 *
 * @package Generate
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<?php if ( ! function_exists( '_wp_render_title_tag' ) ) : ?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
	<?php endif; ?>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php 
	wp_head();
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	?>
</head>

<body itemtype="http://schema.org/WebPage" itemscope="itemscope" <?php body_class(); ?>>
	<?php do_action( 'generate_before_header' ); ?>
	<header itemtype="http://schema.org/WPHeader" itemscope="itemscope" id="masthead" role="banner" <?php generate_header_class(); ?>>
		<div <?php generate_inside_header_class(); ?>>
			<?php do_action( 'generate_before_header_content'); ?>
			
			<?php if ( is_active_sidebar('header') ) : ?>
				<div class="header-widget">
					<?php dynamic_sidebar( 'header' ); ?>
				</div>
			<?php endif; // end sidebar widget area ?>
		
			<?php if ( empty( $generate_settings['hide_title'] ) || empty( $generate_settings['hide_tagline'] ) ) : ?>
				<div class="site-branding">
				<?php if ( empty( $generate_settings['hide_title'] ) ) : ?>
					<p class="main-title" itemprop="headline"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php endif;
				
				if ( empty( $generate_settings['hide_tagline'] ) ) : ?>
					<p class="site-description"><?php bloginfo( 'description' ); ?></p>
				<?php endif; ?>
				</div>
			<?php endif;
			
			if ( !empty( $generate_settings['logo'] ) ) : ?>
				<div class="site-logo">
					<a href="<?php echo apply_filters( 'generate_logo_href' , esc_url( home_url( '/' ) ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img class="header-image" src="<?php echo $generate_settings['logo']; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" /></a>
				</div>
			<?php endif; ?>
			<?php do_action( 'generate_after_header_content'); ?>
		</div><!-- .inside-header -->
	</header><!-- #masthead -->
	<?php do_action( 'generate_after_header' ); ?>
	
	<div id="page" class="hfeed site grid-container container grid-parent">
		<div id="content" class="site-content">
			<?php do_action('generate_inside_container'); ?>