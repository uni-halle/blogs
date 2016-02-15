<?php
	//Bootstrap =======================================================
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '3.1', 'all');
	//=================================================================

	//Flickity =======================================================
	wp_enqueue_style( 'flickity', get_template_directory_uri() . '/css/flickity.css', array(), '1.1.1', 'all');
	//=================================================================

	//Google Font =======================================================
	wp_enqueue_style( 'coni_google-font', '//fonts.googleapis.com/css?family=Lato:300,400,700', array(), '1.0', 'all');
	//=================================================================


	wp_enqueue_style( 'coni_style', get_stylesheet_uri() );

?>