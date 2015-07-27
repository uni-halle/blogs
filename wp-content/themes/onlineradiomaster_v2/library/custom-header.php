<?php

// The Header-image.
	$defaults = array(

	'default-image'   => '',
	'width'           => 1400,
	'height'          => 150,
	'flex-width'      => true,
	'flex-height'     => true,
	'uploads'         => true,
	'random-default'  => false,
	'header-text'     => false,
	//'default-text-color'  => '',
	//'wp-head'             => '',
	//'admin-head-callback' => '',
	//'admin-preview-callback' => '',
);

	add_theme_support( 'custom-header', $defaults );

	?>
