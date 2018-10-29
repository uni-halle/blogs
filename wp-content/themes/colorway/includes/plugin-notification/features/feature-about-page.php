<?php
/**
 * Lite Manager
 *
 * @package Colorway
 * @since 1.0.12
 */


/**
 * About page class
 */
require_once get_template_directory() . '/includes/plugin-notification/cw-notifications/cw-about-page/class-inkthemes-about-page.php';

/*
* About page instance
*/
$config = array(

	// Plugins array.
//	'recommended_plugins' => array(
//		'already_activated_message' => esc_html__( 'Already activated', 'colorway' ),
//		'version_label'             => esc_html__( 'Version: ', 'colorway' ),
//		'install_label'             => esc_html__( 'Install and Activate', 'colorway' ),
//		'activate_label'            => esc_html__( 'Activate', 'colorway' ),
//		'deactivate_label'          => esc_html__( 'Deactivate', 'colorway' ),
//		'content'                   => array(
//			array(
//				'slug' => 'elementor',
//			),
//			array(
//				'slug' => 'translatepress-multilingual',
//			),
//			array(
//				'slug' => 'beaver-builder-lite-version',
//			),
//			array(
//				'slug' => 'wp-product-review',
//			),
//			array(
//				'slug' => 'intergeo-maps',
//			),
//			array(
//				'slug' => 'visualizer',
//			),
//			array(
//				'slug' => 'adblock-notify-by-bweb',
//			),
//			array(
//				'slug' => 'nivo-slider-lite',
//			),
//		),
//	),
	// Required actions array.
	'recommended_actions' => array(
		'install_label'    => esc_html__( 'Install and Activate', 'colorway' ),
		'activate_label'   => esc_html__( 'Activate', 'colorway' ),
		'deactivate_label' => esc_html__( 'Deactivate', 'colorway' ),
		'content'          => array(
			'colorway-sites' => array(
				'title'       => 'Colorway Sites',
				'description' => __( 'It is highly recommended that you install the companion plugin to have access to the Frontpage features, Team and Testimonials sections.', 'colorway' ),
				//'check'       => defined( 'COLORWAY_COMPANION_VERSION' ),
				'plugin_slug' => 'colorway-sites',
				'id'          => 'colorway-sites',
			),
//			'pirate-forms'        => array(
//				'title'       => 'Pirate Forms',
//				'description' => __( 'Makes your Contact section more engaging by creating a good-looking contact form. Interaction with your visitors has never been easier.', 'colorway' ),
//				'check'       => defined( 'PIRATE_FORMS_VERSION' ),
//				'plugin_slug' => 'pirate-forms',
//				'id'          => 'pirate-forms',
//			),
//			'elementor'           => array(
//				'title'       => 'Elementor',
//				'description' => colorway_get_wporg_plugin_description( 'elementor' ),
//				'check'       => ( defined( 'ELEMENTOR_VERSION' ) || ! colorway_check_passed_time( MONTH_IN_SECONDS ) ),
//				'plugin_slug' => 'elementor',
//				'id'          => 'elementor',
//			),

		),
	),
);
Inkthemes_About_Page::init( apply_filters( 'colorway_about_page_array', $config ) );

/*
 * Notifications in customize
 */
require get_template_directory() . '/includes/plugin-notification/cw-notifications/cw-customizer-notify/class-inkthemes-customizer-notify.php';

$config_customizer = array(
	'recommended_plugins'       => array(
		'colorway-sites' => array(
			'recommended' => true,
			/* translators: s - Orbit Fox Companion */
			'description' => sprintf( esc_html__( 'If you want to take full advantage of the options this theme has to offer, please install and activate %s.', 'colorway' ), sprintf( '<strong>%s</strong>', 'Colorway Sites' ) ),
		),
	),
	'recommended_actions'       => array(),
	'recommended_actions_title' => esc_html__( 'Recommended Actions', 'colorway' ),
	'recommended_plugins_title' => esc_html__( 'Recommended Plugins', 'colorway' ),
	'install_button_label'      => esc_html__( 'Install and Activate', 'colorway' ),
	'activate_button_label'     => esc_html__( 'Activate', 'colorway' ),
	'deactivate_button_label'   => esc_html__( 'Deactivate', 'colorway' ),
);
Inkthemes_Customizer_Notify::init( apply_filters( 'colorway_customizer_notify_array', $config_customizer ) );
