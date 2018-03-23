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

	<body <?php if(!empty($_GET['dir'])){ $dir = $_GET['dir']; } else { $dir = 'fw'; } body_class("body " . $dir . " " . $post->post_name . " " . "sektion" . get_post_meta($post->ID, "sektion", true)); ?>>
		<div class="body__background">
			<div class="body__backgroundupper">
				<?php
					$thumbnail = "";
					$i = 1;
					//$pages = get_pagelist();
					$pages = get_pages(array( 'sort_order' => 'asc', 'sort_column' => 'menu_order' ));
					foreach($pages as $page){
						if (has_post_thumbnail($page->ID)) {
							$thumbnail = get_the_post_thumbnail_url( $page->ID, 'full', array('class' => 'pagelist__img') );
							$i = 1;
						} else {
							$i++;
						}
						if($page->ID == $post->ID){
							break;
						}
					}
					//echo "<div class='body__backgroundupperinner' count='" . $i . "' thumbnail='" . $thumbnail . "' style='background-image: url(" . $thumbnail . "); background-size: cover; background-position: center center; transform: scale(" . (1 + $i / 10) . ");'></div>";
					echo "<div class='body__backgroundupperinner' count='" . $i . "' thumbnail='" . $thumbnail . "' style='background-image: url(" . $thumbnail . "); background-size: cover; background-position: center center;'></div>";
				?>
			</div>
			<?php 
				$sektion = get_post_meta($post->ID, "sektion", true);
				if(get_post_meta($post->ID, "sektion", true) == 10) $raumfarbe = "#477286";
				if(get_post_meta($post->ID, "sektion", true) == 20) $raumfarbe = "#46686e";
				if(get_post_meta($post->ID, "sektion", true) == 30) $raumfarbe = "#505e4c";
				if(get_post_meta($post->ID, "sektion", true) == 40) $raumfarbe = "#4e797c";
				if(get_post_meta($post->ID, "sektion", true) == 41) $raumfarbe = "#4e797c";
				if(get_post_meta($post->ID, "sektion", true) == 50) $raumfarbe = "#8c3734";
				if(get_post_meta($post->ID, "sektion", true) == 51) $raumfarbe = "#8c3734";
				if(get_post_meta($post->ID, "sektion", true) == 60) $raumfarbe = "#8b3e64";
				if(get_post_meta($post->ID, "sektion", true) == 61) $raumfarbe = "#6f2a54";
				if(get_post_meta($post->ID, "sektion", true) == 62) $raumfarbe = "#4f1337";
				if(get_post_meta($post->ID, "sektion", true) == 70) $raumfarbe = "#5f6e88";
			?>
			<div class="body__backgroundlower" style="background: <?php echo $raumfarbe; ?>">
				<?php echo file_get_contents(get_template_directory_uri()."/img/grundriss_wma.svg"); ?>
			</div>
		</div>

		<div class="body__wrapper wrapper" id="top" name="top">

			<nav class="wrapper__section navlist">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'navlist__list', 'container' => 'ul') ); ?>
				<img class="navlist__icon navlist__icon--navicon" src="<?php echo get_template_directory_uri(); ?>/img/navicon.svg" />
				<?php
					$pages = get_pages(array( 'sort_order' => 'asc', 'sort_column' => 'menu_order' ));
					foreach($pages as $key => $page){
						if($page->ID == $post->ID){
							$counter = $key;
						}
					}
					if(!empty($pages[$counter + 1])){
						echo "<a class='navlist__link--next' href='" . get_permalink($pages[$counter + 1]->ID) . "?dir=fw'><img class='navlist__icon navlist__icon--down' src='" . get_template_directory_uri() . "/img/arrdown.svg' /></a>";
						//preload images
						echo "<img src='" . get_the_post_thumbnail_url( $pages[$counter + 1]->ID, 'full') . "' style='display: none;' />";
					}
					if(!empty($pages[$counter - 1])){
						echo "<a class='navlist__link--prev' href='" . get_permalink($pages[$counter - 1]->ID) . "?dir=rw'><img class='navlist__icon navlist__icon--up' src='" . get_template_directory_uri() . "/img/arrup.svg' /></a>";
						//preload images
						echo "<img src='" . get_the_post_thumbnail_url( $pages[$counter - 1]->ID, 'full') . "' style='display: none;' />";
					}
				?>
				<?php get_search_form(); ?>
			</nav>

			<div class="wrapper__section main">