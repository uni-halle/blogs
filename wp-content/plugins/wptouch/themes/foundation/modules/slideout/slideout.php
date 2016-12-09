<?php

add_action( 'foundation_module_init_mobile', 'foundation_slideout_init' );

function foundation_slideout_init() {

	// Slideout CSS
	wp_register_style( 'foundation_slideout', foundation_get_base_module_url() . '/slideout/slideout.css' );

	wp_enqueue_style(
		'foundation_slideout',
		foundation_get_base_module_url() . '/slideout/slideout.css',
		'',
		md5( FOUNDATION_VERSION )
	);

	// Slideout JS
	wp_enqueue_script(
		'foundation_slideout',
		foundation_get_base_module_url() . '/slideout/slideout.js',
		array( 'jquery' ),
		md5( FOUNDATION_VERSION ),
		true
	);

    // Slideout Helper JS
    wp_enqueue_script(
        'foundation_slideout_helper',
        foundation_get_base_module_url() . '/slideout/slideout-helper.js',
        array( 'foundation_slideout' ),
        md5( FOUNDATION_VERSION ),
        true
    );
}
