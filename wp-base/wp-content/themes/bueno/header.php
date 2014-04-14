<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package bueno
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="format-detection" content="telephone=no" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if( '' != of_get_option('favicon') ){ ?>
<link rel="icon" href="<?php echo esc_url( of_get_option('favicon', "" ) ); ?>" type="image/x-icon" />
<?php } ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<!--[if (gt IE 9)|!(IE)]>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.mobile.customized.min.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="page-wrapper">
	<?php do_action( 'before' ); ?>
	<header id="header" role="banner">
		<div class="container clearfix">

			<!-- Header Top Row template part (top menu and slogan) -->
			<?php get_template_part( 'templates/header-top-row' ) ?>

			<!-- Logo template part (logo and header banner) -->
			<?php get_template_part( 'templates/header-logo-row' ) ?>

			<!-- Navigation template part (navigation and top search box) -->
			<?php get_template_part( 'templates/header-nav' ) ?>

		</div>
	</header><!-- #masthead -->
	
	<div id="main" class="site-main">
		<div class="container">
			<?php if ( of_get_option('g_breadcrumbs_id') != 'no') { ?>
				<?php bueno_breadcrumb(); ?>
			<?php } ?>
			<div class="row">