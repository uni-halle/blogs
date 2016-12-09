<?php

add_action( 'foundation_module_init_mobile', 'foundation_flickity_init' );

function foundation_flickity_init() {

	wp_enqueue_style(
		'foundation_flickity_css',
		foundation_get_base_module_url() . '/flickity/flickity.min.css',
		'',
		md5( FOUNDATION_VERSION ),
		'screen'
	);

	wp_enqueue_script(
		'foundation_flickity',
		foundation_get_base_module_url() . '/flickity/flickity.pkgd.min.js',
		array( 'jquery' ),
		md5( FOUNDATION_VERSION ),
		true
	);

	wp_enqueue_script(
		'foundation_flickity_wptouch',
		foundation_get_base_module_url() . '/flickity/flickity-wptouch.js',
		array( 'foundation_flickity' ),
		md5( FOUNDATION_VERSION ),
		true
	);

}
