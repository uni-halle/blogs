<?php

/**
 * Enqueues front-end CSS for color scheme.
 *
 * @see wp_add_inline_style()
 */
function coni_custom_css() {
	$heroColor = get_theme_mod( 'coni_hero_color', '#00b09a' );
	$text_color = get_theme_mod( 'coni_text_color', '#999999' );
	$link_color = get_theme_mod( 'coni_link_color', '#00b09a' );

	$colors = array(
		'heroColor'      => $heroColor,
		'text_color'     => $text_color,
		'link_color'     => $link_color,
	);

	$custom_css = coni_get_custom_css( $colors );

	wp_add_inline_style( 'coni-pro-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'coni_custom_css' );



/**
 * Returns CSS for the color schemes.
 *
 * @param array $colors colors.
 * @return string CSS.
 */
function coni_get_custom_css( $colors ) {

	//Default colors
	$colors = wp_parse_args( $colors, array(
		'heroColor'            => '',
		'text_color'            => '',
		'link_color'            => '',
	) );

	$css = <<<CSS


CSS;

	return $css;
}