<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>

		<meta name="robots" content="index, follow" />
		<meta charset="<?php bloginfo('charset') ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/img/favicon/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/img/favicon/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/img/favicon/manifest.json">
		<link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="theme-color" content="#ffffff">

		<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" media="screen" type="text/css" />

		<link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700" rel="stylesheet">

		<?php wp_head(); ?>

	</head>

	<body <?php body_class("body"); ?> >
		<div class="body__background"><?php //maybe enable here gallery for addgallery-plugin: get_template_part('/templates/parts/addgallery-slideshow', ''); ?></div>

		<div class="body__wrapper wrapper">

			<header class="wrapper__section header">
				<img class="header__brand" src="<?php echo get_template_directory_uri()."/img/brand.svg";?>" />
				<a class="header__homelink" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
			</header>

			<nav class="wrapper__section navbar">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => '', 'container' => 'ul' ) ); ?>
			</nav>

			<nav class="wrapper__section navlist">
				<img class="navlist__icon" src="<?php echo get_template_directory_uri(); ?>/img/navicon.svg" />
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'navlist__list', 'container' => 'ul' ) ); ?>
			</nav>

			<div class="wrapper__section main">