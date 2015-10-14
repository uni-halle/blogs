<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">	
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
	<script>
		grunticon(["<?php echo get_template_directory_uri() . '/fonts/grunticon/icons.data.svg.css'; ?>",
			"<?php echo get_template_directory_uri() . '/fonts/grunticon/icons.data.png.css'; ?>",
			"<?php echo get_template_directory_uri() . '/fonts/grunticon/icons.fallback.css'; ?>"
		]);
	</script>
	<noscript><link href="<?php echo get_template_directory_uri() . '/fonts/grunticon/icons.fallback.css'; ?>" rel="stylesheet"></noscript>	
</head>

<body <?php body_class(); ?>>
<div id="site" class="hfeed">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'cleantraditional' ); ?></a>
	
	<header id="head" class="site-header row" role="banner">
		<div class="cell position-0 width-3">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<img src="<?php echo get_header_image(); ?>" 
					 title="<?php echo esc_attr( bloginfo( 'name' ) ); ?>"
					 alt="<?php echo esc_attr( bloginfo( 'name' ) ); ?>"/>
			</a>
		</div>
		<nav role="navigation" class="cell position-6 width-2">
			<ul class="navigation-header">
				<li><a href="<?php echo esc_url( site_url( '/wp-login.php' ) ); ?>">Login <span class="cleantraditional-icon icon-login"></span></a></li>
				<li><a href="<?php echo esc_url( site_url( '/suchen' ) ); ?>">Suchen <span class="cleantraditional-icon icon-search"></span></a></li>
				<li>
					<a class="button button-small" title="Schrift vergrößern"
						href="#" onclick="jQuery('body').changeFontsize('increase');">+</a>
					<a class="button button-small" title="Schrift verkleinern"
						href="#" onclick="jQuery('body').changeFontsize('decrease');">-</a>
					Schrift <span class="cleantraditional-icon icon-fontsize"></span>
				</li>
				<li><a href="<?php echo esc_url( site_url( '/kontakt' ) ); ?>">Kontakt <span class="cleantraditional-icon icon-contact"></span></a></li>
			</ul>
		</nav>
	</header>
	
	<div class="row"><div class="cell position-0 width-8 delimiter"></div></div>
	
	<div id="content" class="site-content row">
