<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>

		<meta name="robots" content="index, follow" />
		<meta charset="<?php bloginfo('charset') ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/img/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/img/favicon/favicon-16x16.png">
		<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/img/favicon/site.webmanifest">
		<link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="theme-color" content="#ffffff">

		<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" media="screen" type="text/css" />

		<link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700" rel="stylesheet">

		<?php wp_head(); ?>

	</head>

	<body <?php body_class("body"); ?>>
		<div class="body__background" style="background-image: url(<?php echo get_the_post_thumbnail_url( $post->ID, 'full' ); ?>); background-size: cover; background-position: center center;"></div>

		<div class="body__wrapper wrapper" id="top" name="top">

			<nav class="wrapper__section navlist">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'navlist__list', 'container' => 'ul') ); ?>
				<img class="navlist__icon navlist__icon--navicon" src="<?php echo get_template_directory_uri(); ?>/img/navicon.svg" />
				<?php get_search_form(); ?>
			</nav>

			<div class="wrapper__section main">