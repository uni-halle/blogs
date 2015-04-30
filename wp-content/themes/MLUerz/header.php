<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<script>(function(){document.documentElement.className='js'})();</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="page_margins">
<div id="page" class="hold_floats hfeed site">
	<!--<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'twentyfifteen' ); ?></a>-->
<div id="header">
<header id="masthead" class="site-header" role="banner">
<!--suche-->
<form action="<?php echo home_url( '/' ); ?>" method="get" id="suche" role="search">
		<input type="text" name="s" id="searchbox-sword" value="<?php the_search_query(); ?>" placeholder="Suchwort + [ENTER]" />
</form>
<!-- /suche -->
<?php echo qtranxf_generateLanguageSelectCode('text'); ?><!-- lang menu -->
						<h1 class="site-title"><img src="<?php header_image(); ?>" alt="<?php bloginfo( 'name' ); ?>" style="max-width:100%;height:auto" class="print"/><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					
					<?php 
					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) : ?>
						<small class="site-description"><?php echo $description; ?></small>
					<?php endif;
				?></h1>
		</header></div><!-- .site-header -->

<div id="main">
<!-- #col1: Erste Float-Spalte des Inhaltsbereiches --><a id="content" class="site-content"></a>
    <div id="col1"><div id="col1_content" class="clearfix">
