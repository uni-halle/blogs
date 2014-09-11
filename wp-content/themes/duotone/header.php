<?php
/**
 * @package Duotone
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />

<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!--[if lte IE 8]>
<style type="text/css">
	body { font-size:12px; }
</style>
<![endif]-->

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="wrap">

	<div id="menu">
		<?php wp_nav_menu( array(
			'container'      => false,
			'theme_location' => 'primary',
			'fallback_cb'    => 'duotone_page_menu'
		) ); ?>
	</div>

	<div id="page">

		<div id="header">
			<h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<div class="description"><?php bloginfo( 'description' ); ?></div>
		</div>

		<div id="content">
			<div class="sleeve">