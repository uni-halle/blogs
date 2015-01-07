<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package WordPress
 * @subpackage Choco
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="page">
<?php do_action( 'before' ); ?>
	<header id="header" class="clear-fix">
		<div id="logo">
			<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'h3'; ?>
			<<?php echo $heading_tag; ?> id="site-title">
				<span>
					<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</span>
			</<?php echo $heading_tag; ?>>
			<div class="description"><?php bloginfo( 'description' ); ?></div>

		</div><!-- #logo -->

		<div id="nav">
			<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary') ); ?>
		</div><!-- #nav -->

		<a href="#" class="open-sidebar">
			<span></span>
		</a>

	</header><!-- #header -->

	<div id="main">
		<a href="<?php bloginfo( 'rss_url' ); ?>" id="rss-link">RSS</a>
		<div id="main-top">
			<div id="main-middle">
				<div id="main-bot" class="clear-fix">
					<div id="content">
						<div class="content-inner">