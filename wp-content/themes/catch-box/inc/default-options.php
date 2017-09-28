<?php
/**
 * Catch Box Theme Options
 *
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 1.0
 */

/**
 * Returns the options array for Catch Box.
 *
 * @since Catch Box 1.0
 */
function catchbox_get_options() {
	return wp_parse_args( ( array ) get_theme_mod( 'catchbox_theme_options' ), catchbox_defaults() );
}


/**
 * Returns an array of color schemes registered for Catch Box.
 *
 * @since Catch Box 1.0
 */
function catchbox_color_schemes() {
	$color_scheme_options = array(
		'light' 					=> array(
			'value'					=> 'light',
			'label'					=> __( 'Light', 'catch-box' ),
			'default_link_color'	=> '#1b8be0',
		),
		'dark' 						=> array(
			'value'					=> 'dark',
			'label'					=> __( 'Dark', 'catch-box' ),
			'default_link_color'	=> '#e4741f',
		),
		'blue' 						=> array(
			'value'					=> 'blue',
			'label'					=> __( 'Blue', 'catch-box' ),
			'default_link_color'	=> '#326693',
		),
		'green' 						=> array(
			'value'					=> 'green',
			'label'					=> __( 'Green', 'catch-box' ),
			'default_link_color'	=> '#3e6107',
		),
		'red' 						=> array(
			'value'					=> 'red',
			'label'					=> __( 'Red', 'catch-box' ),
			'default_link_color'	=> '#a6201d',
		),
		'brown' 					=> array(
			'value'					=> 'brown',
			'label'					=> __( 'Brown', 'catch-box' ),
			'default_link_color'	=> '#5e3929',
		),
		'orange' 					=> array(
			'value'					=> 'orange',
			'label'					=> __( 'Orange', 'catch-box' ),
			'default_link_color'	=> '#802602',
		)
	);

	return apply_filters( 'catchbox_color_schemes', $color_scheme_options );
}


/**
 * Returns an array of layout options registered for Catch Box.
 *
 * @since Catch Box 1.0
 */
function catchbox_layouts() {
	$layout_options = array(
		'content-sidebar' 	=> array(
			'old_value'		=> 'content-sidebar',
			'value' 		=> 'right-sidebar',
			'label'			=> esc_html__( 'Content on left', 'catch-box' ),
		),
		'sidebar-content' 	=> array(
			'old_value'		=> 'sidebar-content',
			'value'			=> 'left-sidebar',
			'label'			=> esc_html__( 'Content on right', 'catch-box' ),
		),
		'content-onecolumn'	=> array(
			'old_value'		=> 'content-onecolumn',
			'value'			=> 'no-sidebar-one-column',
			'label'			=> esc_html__( 'One-column, no sidebar', 'catch-box' ),
		)
	);

	return apply_filters( 'catchbox_layouts', $layout_options );
}


/**
 * Returns an array of content layout options registered for Catch Box.
 *
 * @since Catch Box 1.0
 */
function catchbox_content_layout() {
	$content_options = array(
		'excerpt'			=> array(
			'value'			=> 'excerpt',
			'label'			=> esc_html__( 'Show excerpt', 'catch-box' ),
		),
		'full-content'		=> array(
			'value'			=> 'full-content',
			'label'			=> esc_html__( 'Show full content', 'catch-box' ),
		)
	);

	return apply_filters( 'catchbox_content_layouts', $content_options );
}


/**
 * Returns the default options for Catch Box.
 *
 * @since Catch Box 1.0
 */
function catchbox_defaults() {
	$default_theme_options = array(
		'excerpt_length'        => 40,
		'color_scheme'          => 'light',
		'header_image_position' => 'above',
		'link_color'            => catchbox_get_default_link_color( 'light' ),
		'theme_layout'          => 'right-sidebar',
		'content_layout'        => 'excerpt',
		'site_title_above'      => '0',
		'disable_header_search' => '0',
		'enable_sec_menu'       => '1',
		'enable_footer_menu'    => '0',
		'search_display_text'   => esc_html__( 'Search', 'catch-box' ),

		//Feature Slider
		'exclude_slider_post'   => '0',
		'slider_qty'            => 4,
		'transition_effect'     => 'fade',
		'transition_delay'      => 4,
		'transition_duration'   => 1,

		'disable_scrollup'      => '0',
		'custom_css'			=> ''
	);

	if ( is_rtl() )
 		$default_theme_options['theme_layout'] = 'left-sidebar';

	return apply_filters( 'catchbox_default_theme_options', $default_theme_options );
}


/**
 * Returns the default link color for Catch Box, based on color scheme.
 *
 * @since Catch Box 1.0
 *
 * @param $string $color_scheme Color scheme. Defaults to the active color scheme.
 * @return $string Color.
*/
function catchbox_get_default_link_color( $color_scheme = null ) {
	if ( null === $color_scheme ) {
		$options = catchbox_get_options();
		$color_scheme = $options['color_scheme'];
	}

	$color_schemes = catchbox_color_schemes();
	if ( ! isset( $color_schemes[ $color_scheme ] ) )
		return false;

	return $color_schemes[ $color_scheme ]['default_link_color'];
}


/**
 * Function to display the current year.
 *
 * @uses date() Gets the current year.
 * @return string
 */
function catchbox_the_year() {
    return esc_attr( date_i18n( __( 'Y', 'catch-box' ) ) );
}


/**
 * Function to display a link back to the site.
 *
 * @uses get_bloginfo() Gets the site link
 * @return string
 */
function catchbox_site_link() {
    return '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" ><span>' . get_bloginfo( 'name', 'display' ) . '</span></a>';
}


/**
 * Function to display a link to WordPress.org.
 *
 * @return string
 */
function catchbox_wp_link() {
    return '<a href="http://wordpress.org" title="' . esc_attr__( 'WordPress', 'catch-box' ) . '"><span>' . __( 'WordPress', 'catch-box' ) . '</span></a>';
}


/**
 * Function to display a link to Theme Link.
 *
 * @return string
 */
function catchbox_theme_name() {
    return '<span class="theme-name">' . __( 'Theme: Catch Box by ', 'catch-box' ) . '</span>';
}


/**
 * Function to display a link to Theme Link.
 *
 * @return string
 */
function catchbox_theme_author() {

    return '<span class="theme-author"><a href="' . esc_url( 'https://catchthemes.com/' ) . '" title="' . esc_attr__( 'Catch Themes', 'catch-box' ) . '">' . __( 'Catch Themes', 'catch-box' ) . '</a></span>';

}


/**
 * Function to display Catch Box assets
 *
 * @return string
 */
function catchbox_assets(){
    $catchbox_content = '<div class="copyright">'. esc_attr__( 'Copyright', 'catch-box' ) . ' &copy; '. catchbox_the_year() . ' ' . catchbox_site_link() . '. ' . esc_attr__( 'All Rights Reserved', 'catch-box' ) . '.</div><div class="powered">'. catchbox_theme_name() . catchbox_theme_author() . '</div>';
    return $catchbox_content;
}


/**
 * Function to migrate data from theme options to theme customizer
 *
 * @hooked after_setup_theme
 */
function catchbox_migrate_theme_options(){
    // Move Theme Options to theme mods
	if ( $catchbox_options = get_option( 'catchbox_theme_options' ) ) {
		set_theme_mod( 'catchbox_theme_options', $catchbox_options );
		delete_option( 'catchbox_theme_options' );
	}
}
add_action( 'after_setup_theme', 'catchbox_migrate_theme_options' );
