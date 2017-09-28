<?php
/**
 * Catch Kathmandu Customizer/Theme Options
 *
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 3.6.7
 */

/**
 * Implements Catch Kathmandu theme options into Theme Customizer.
 *
 * @param $wp_customize Theme Customizer object
 * @return void
 *
 * @since Catch Box 3.6.7
 */
function catchbox_customize_register( $wp_customize ) {
	$options = catchbox_get_options();

	$defaults = catchbox_defaults();

	//Custom Controls
	require trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-custom-controls.php';

	$theme_slug = 'catchbox_theme_';

	$settings_page_tabs = array(
		'theme_options' => array(
			'id' 			=> 'theme_options',
			'title' 		=> esc_html__( 'Theme Options', 'catch-box' ),
			'description' 	=> esc_html__( 'Basic theme Options', 'catch-box' ),
			'sections' 		=> array(
				'theme_layout' => array(
					'id' 			=> 'theme_layout',
					'title' 		=> esc_html__( 'Default Layout', 'catch-box' ),
					'description' 	=> '',
				),
				'content_layout' => array(
					'id' 			=> 'content_layout',
					'title' 		=> esc_html__( 'Content Layout', 'catch-box' ),
					'description' 	=> '',
				),
				'excerpt_length' => array(
					'id' 			=> 'excerpt_length',
					'title' 		=> esc_html__( 'Excerpt Length in Words', 'catch-box' ),
					'description' 	=> '',
				),
				'feed_url' => array(
					'id' 			=> 'feed_url',
					'title' 		=> esc_html__( 'Feed Redirect URL', 'catch-box' ),
					'description' 	=> '',
				),
				'search_display_text' => array(
					'id' 			=> 'search_display_text',
					'title' 		=> esc_html__( 'Search Text Settings', 'catch-box' ),
					'description' 	=> '',
				),
				'disable_header_search' => array(
					'id' 			=> 'disable_header_search',
					'title' 		=> esc_html__( 'Disable Search in Header', 'catch-box' ),
					'description' 	=> '',
				),
				'enable_menus' => array(
					'id' 			=> 'enable_menus',
					'title' 		=> esc_html__( 'Mobile Menu Options', 'catch-box' ),
					'description' 	=> '',
				),
				'custom_css' => array(
					'id' 			=> 'custom_css',
					'title' 		=> esc_html__( 'Custom CSS Styles', 'catch-box' ),
					'description' 	=> '',
				),
				'scrollup' => array(
					'id' 			=> 'scrollup',
					'title' 		=> esc_html__( 'Scroll Up Options', 'catch-box' ),
					'description' 	=> '',
				),
			),
		),

		'featured_slider' => array(
			'id' 			=> 'featured_slider',
			'title' 		=> esc_html__( 'Featured Slider', 'catch-box' ),
			'description' 	=> esc_html__( 'Featured Slider', 'catch-box' ),
			'sections' 		=> array(
				'slider_options' => array(
					'id' 			=> 'slider_options',
					'title' 		=> esc_html__( 'Slider Options', 'catch-box' ),
					'description' 	=> '',
				),
				'slider_effect_options' => array(
					'id' 			=> 'slider_effect_options',
					'title' 		=> esc_html__( 'Slider Effect Options', 'catch-box' ),
					'description' 	=> '',
				),
			)
		),

		'colors' => array(
			'id' 			=> 'colors',
			'title' 		=> esc_html__( 'Colors', 'catch-box' ),
			'description' 	=> '',
			'priority'		=> '10',
			'sections' 		=> array(
				'basic_colors' => array(
					'id' 			=> 'basic_colors',
					'title' 		=> esc_html__( 'Basic Colors', 'catch-box' ),
					'description' 	=> '',
				),
				'custom_colors' => array(
					'id' 			=> 'custom_colors',
					'title' 		=> esc_html__( 'Custom Color Options', 'catch-box' ),
					'description' 	=> '',
				),
			),
		),

		'social_links' => array(
			'id' 			=> 'social_links',
			'title' 		=> esc_html__( 'Social Links', 'catch-box' ),
			'description' 	=> esc_html__( 'Add your social links here', 'catch-box' ),
			'sections' 		=> array(
				'predefined_social_icons' => array(
					'id' 			=> 'predefined_social_icons',
					'title' 		=> esc_html__( 'Predefined Social Icons', 'catch-box' ),
					'description' 	=> '',
				),
			),
		),
		'webmaster_tools' => array(
			'id' 			=> 'webmaster_tools',
			'title' 		=> esc_html__( 'Webmaster Tools', 'catch-box' ),
			'description' 	=>  sprintf( esc_html__( 'Webmaster Tools falls under Plugins Territory according to Theme Review Guidelines in WordPress.org. This feature will be depreciated in future versions from Catch Box free version. If you want this feature, then you can add <a href="%s">Catch Web Tools</a>  plugin.', 'catch-box' ), esc_url( 'https://wordpress.org/plugins/catch-web-tools/' ) ),
			'sections' 		=> array(
				'webmaster_tools' => array(
					'id' 			=> 'webmaster_tools',
					'title' 		=> esc_html__( 'Webmaster Tools', 'catch-box' ),
					'description' 	=>  sprintf( esc_html__( 'Webmaster Tools falls under Plugins Territory according to Theme Review Guidelines in WordPress.org. This feature will be depreciated in future versions from Catch Box free version. If you want this feature, then you can add <a href="%s">Catch Web Tools</a>  plugin.', 'catch-box' ), esc_url( 'https://wordpress.org/plugins/catch-web-tools/' ) ),
				),
			),
		),
	);

	//Add Panels and sections
	foreach ( $settings_page_tabs as $panel ) {
		$panel_priority = 200;

		if ( 'colors' === $panel['id'] ) {
			$panel_priority = 30;
		}

		$wp_customize->add_panel(
			$theme_slug . $panel['id'],
			array(
				'priority' 		=> $panel_priority,
				'capability' 	=> 'edit_theme_options',
				'title' 		=> $panel['title'],
				'description' 	=> $panel['description'],
			)
		);

		// Loop through tabs for sections
		foreach ( $panel['sections'] as $section ) {
			$params = array(
								'title'			=> $section['title'],
								'description'	=> $section['description'],
								'panel'			=> $theme_slug . $panel['id']
							);

			if ( isset( $section['active_callback'] ) ) {
				$params['active_callback'] = $section['active_callback'];
			}

			$wp_customize->add_section(
				// $id
				$theme_slug . $section['id'],
				// parameters
				$params

			);
		}
	}

	foreach( catchbox_color_schemes() as $option ) {
		$catchbox_color_schemes[ $option['value'] ] =  $option['label'];
	}

	foreach( catchbox_layouts() as $option ) {
		$catchbox_theme_layout[ $option['value'] ] =  $option['label'];
	}

	foreach( catchbox_content_layout() as $option ) {
		$catchbox_content_layout[ $option['value'] ] =  $option['label'];
	}

	//Move default header color and background color under custom section basic_colors
	$wp_customize->get_control( 'header_textcolor' )->section = $theme_slug . 'basic_colors' ;
	$wp_customize->get_control( 'header_textcolor' )->priority = 20 ;

	$wp_customize->get_control( 'background_color' )->section = $theme_slug . 'basic_colors' ;
	$wp_customize->get_control( 'background_color' )->priority = 25 ;

	$settings_parameters = array(
		//Color Scheme
		'color_scheme' => array(
			'id' 			=> 'color_scheme',
			'title' 		=> esc_html__( 'Color Scheme', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'radio',
			'sanitize' 		=> 'catchbox_sanitize_select',
			'panel' 		=> 'theme_options',
			'section' 		=> 'basic_colors',
			'default' 		=> $defaults['color_scheme'],
			'choices'		=> $catchbox_color_schemes,
		),
		'link_color' => array(
			'id' 			=> 'link_color',
			'title' 		=> esc_html__( 'Link Color', 'catch-box' ),
			'description' 	=> '',
			'field_type' 	=> 'color',
			'sanitize' 		=> 'sanitize_hex_color',
			'panel' 		=> 'theme_options',
			'section' 		=> 'custom_colors',
			'default' 		=> $defaults['link_color']
		),
		'theme_layout' => array(
			'id' 			=> 'theme_layout',
			'title' 		=> esc_html__( 'Default Layout', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'radio',
			'sanitize' 		=> 'catchbox_sanitize_select',
			'panel' 		=> 'theme_options',
			'section' 		=> 'theme_layout',
			'default' 		=> $defaults['theme_layout'],
			'choices'		=> $catchbox_theme_layout,
		),
		'content_layout' => array(
			'id' 			=> 'content_layout',
			'title' 		=> esc_html__( 'Content Layout', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'radio',
			'sanitize' 		=> 'catchbox_sanitize_select',
			'panel' 		=> 'theme_options',
			'section' 		=> 'content_layout',
			'default' 		=> $defaults['content_layout'],
			'choices'		=> $catchbox_content_layout,
		),
		//Excerpt length
		'excerpt_length' => array(
			'id' 			=> 'excerpt_length',
			'title' 		=> esc_html__( 'Excerpt Length in Words', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'number',
			'sanitize' 		=> 'catchbox_sanitize_number_range',
			'panel' 		=> 'theme_options',
			'section' 		=> 'excerpt_length',
			'default' 		=> $defaults['excerpt_length'],
			'input_attrs' 	=> array(
					            'style' => 'width: 45px;',
					            'min'   => 0,
					            'max'   => 999999,
					            'step'  => 1,
					        	)
		),
		'feed_url' => array(
			'id' 				=> 'feed_url',
			'title' 			=> esc_html__( 'Feed Redirect url', 'catch-box' ),
			'description' 		=> '',
			'field_type' 		=> 'url',
			'sanitize' 			=> 'esc_url_raw',
			'panel' 			=> 'social_links',
			'section' 			=> 'feed_url',
			'default' 			=> '',
			'active_callback'	=> 'catchbox_is_feed_url_present',
		),
		'site_title_above' => array(
			'id' 			=> 'site_title_above',
			'title' 		=> esc_html__( 'Check to move above the Header/Logo Image', 'catch-box' ),
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchbox_sanitize_checkbox',
			'section' 		=> 'title_tagline',
			'default' 		=> $defaults['site_title_above']
		),
		'search_display_text' => array(
			'id' 			=> 'search_display_text',
			'title' 		=> esc_html__( 'Default Display Text in Search', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'text',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'theme_options',
			'section' 		=> 'search_display_text',
			'default' 		=> $defaults['search_display_text']
		),
		'disable_header_search' => array(
			'id' 			=> 'disable_header_search',
			'title' 		=> esc_html__( 'Check to Disable', 'catch-box' ),
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchbox_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'disable_header_search',
			'default' 		=> $defaults['disable_header_search']
		),
		'enable_sec_menu' => array(
			'id' 			=> 'enable_sec_menu',
			'title' 		=> esc_html__( 'Check to enable secondary mobile menu', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchbox_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'enable_menus',
			'default' 		=> $defaults['enable_sec_menu']
		),
		'enable_footer_menu' => array(
			'id' 			=> 'enable_footer_menu',
			'title' 				=> esc_html__( 'Check to enable footer mobile menu', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchbox_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'enable_menus',
			'default' 		=> $defaults['enable_footer_menu']
		),
		'custom_css' => array(
			'id' 			=> 'custom_css',
			'title' 		=> esc_html__( 'Custom CSS Styles', 'catch-box' ),
			'description' 	=> '',
			'field_type' 	=> 'textarea',
			'sanitize' 		=> 'catchbox_sanitize_custom_css',
			'panel' 		=> 'theme_options',
			'section' 		=> 'custom_css',
			'default' 		=> ''
		),

		//Scroll Up Options
		'disable_scrollup' => array(
			'id' 			=> 'disable_scrollup',
			'title' 		=> esc_html__( 'Check to disable scroll up', 'catch-box' ),
			'description' 	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchbox_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'scrollup',
			'default' 		=> $defaults['disable_scrollup'],
		),

		//Slider Options
		'exclude_slider_post' => array(
			'id' 				=> 'exclude_slider_post',
			'title' 			=> esc_html__( 'Check to Exclude Slider posts from Homepage posts', 'catch-box' ),
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'catchbox_sanitize_checkbox',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['exclude_slider_post'],
		),
		'slider_qty' => array(
			'id' 				=> 'slider_qty',
			'title' 			=> esc_html__( 'Number of Slides', 'catch-box' ),
			'description'		=> esc_html__( 'Customizer page needs to be refreshed after saving if number of slides is changed', 'catch-box' ),
			'field_type' 		=> 'number',
			'sanitize' 			=> 'catchbox_sanitize_number_range',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['slider_qty'],
			'input_attrs' 		=> array(
						            'style' => 'width: 45px;',
						            'min'   => 0,
						            'max'   => 20,
						            'step'  => 1,
						        	)
		),
		'transition_effect' => array(
			'id' 				=> 'transition_effect',
			'title' 			=> esc_html__( 'Transition Effect', 'catch-box' ),
			'description'		=> '',
			'field_type' 		=> 'select',
			'sanitize' 			=> 'catchbox_sanitize_select',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_effect_options',
			'default' 			=> $defaults['transition_effect'],
			'choices'			=> array(
										'fade'			=> esc_html__( 'fade', 'catch-box' ),
										'wipe'			=> esc_html__( 'wipe', 'catch-box' ),
										'scrollUp'		=> esc_html__( 'scrollUp', 'catch-box' ),
										'scrollDown'	=> esc_html__( 'scrollDown', 'catch-box' ),
										'scrollLeft'	=> esc_html__( 'scrollLeft', 'catch-box' ),
										'scrollRight'	=> esc_html__( 'scrollRight', 'catch-box' ),
										'blindX'		=> esc_html__( 'blindX', 'catch-box' ),
										'blindY'		=> esc_html__( 'blindY', 'catch-box' ),
										'blindZ'		=> esc_html__( 'blindZ', 'catch-box' ),
										'cover'			=> esc_html__( 'cover', 'catch-box' ),
										'shuffle'		=> esc_html__( 'shuffle', 'catch-box' ),
									)
		),
		'transition_delay' => array(
			'id' 				=> 'transition_delay',
			'title' 			=> esc_html__( 'Transition Delay (in seconds)', 'catch-box' ),
			'description'		=> '',
			'field_type' 		=> 'number',
			'sanitize' 			=> 'catchbox_sanitize_number_range',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_effect_options',
			'default' 			=> $defaults['transition_delay'],
			'input_attrs' 		=> array(
						            'style' => 'width: 45px;',
						            'min'   => 0,
						            'max'   => 999999999,
						            'step'  => 1,
						        	)
		),
		'transition_duration' => array(
			'id' 				=> 'transition_duration',
			'title' 			=> esc_html__( 'Transition Length', 'catch-box' ),
			'description'		=> '',
			'field_type' 		=> 'number',
			'sanitize' 			=> 'catchbox_sanitize_number_range',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_effect_options',
			'default' 			=> $defaults['transition_duration'],
			'input_attrs' 		=> array(
						            'style' => 'width: 45px;',
						            'min'   => 0,
						            'max'   => 999999999,
						            'step'  => 1,
						        	)
		),

		//Social Links
		'social_facebook' => array(
			'id' 			=> 'social_facebook',
			'title' 		=> esc_html__( 'Facebook', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_twitter' => array(
			'id' 			=> 'social_twitter',
			'title' 		=> esc_html__( 'Twitter', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_google' => array(
			'id' 			=> 'social_google',
			'title' 		=> esc_html__( 'Google+', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_linkedin' => array(
			'id' 			=> 'social_linkedin',
			'title' 		=> esc_html__( 'LinkedIn', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_pinterest' => array(
			'id' 			=> 'social_pinterest',
			'title' 		=> esc_html__( 'Pinterest', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_youtube' => array(
			'id' 			=> 'social_youtube',
			'title' 		=> esc_html__( 'Youtube', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_rss' => array(
			'id' 			=> 'social_rss',
			'title' 		=> esc_html__( 'RSS', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_deviantart' => array(
			'id' 			=> 'social_deviantart',
			'title' 		=> esc_html__( 'deviantART', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_tumblr' => array(
			'id' 			=> 'social_tumblr',
			'title' 		=> esc_html__( 'Tumblr', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_viemo' => array(
			'id' 			=> 'social_viemo',
			'title' 		=> esc_html__( 'Vimeo', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_dribbble' => array(
			'id' 			=> 'social_dribbble',
			'title' 		=> esc_html__( 'Dribbble', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_myspace' => array(
			'id' 			=> 'social_myspace',
			'title' 		=> esc_html__( 'MySpace', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_aim' => array(
			'id' 			=> 'social_aim',
			'title' 		=> esc_html__( 'Aim', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_flickr' => array(
			'id' 			=> 'social_flickr',
			'title' 		=> esc_html__( 'Flickr', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_slideshare' => array(
			'id' 			=> 'social_slideshare',
			'title' 		=> esc_html__( 'Slideshare', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_instagram' => array(
			'id' 			=> 'social_instagram',
			'title' 		=> esc_html__( 'Instagram', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_skype' => array(
			'id' 			=> 'social_skype',
			'title' 		=> esc_html__( 'Skype', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_soundcloud' => array(
			'id' 			=> 'social_soundcloud',
			'title' 		=> esc_html__( 'Soundcloud', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_email' => array(
			'id' 			=> 'social_email',
			'title' 		=> esc_html__( 'Email', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'sanitize_email',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_xing' => array(
			'id' 			=> 'social_xing',
			'title' 		=> esc_html__( 'Xing', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_meetup' => array(
			'id' 			=> 'social_meetup',
			'title' 		=> esc_html__( 'Meetup', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_goodreads' => array(
			'id' 			=> 'social_goodreads',
			'title' 		=> esc_html__( 'Goodreads', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_github' => array(
			'id' 			=> 'social_github',
			'title' 		=> esc_html__( 'Github', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_vk' => array(
			'id' 			=> 'social_vk',
			'title' 		=> esc_html__( 'VK', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),						

		//Webmaster Tools
		'tracker_header' => array(
			'id' 				=> 'tracker_header',
			'title' 			=> esc_html__( 'Code to display on Header', 'catch-box' ),
			'description' 		=> esc_html__( 'Here you can put scripts from Google, Facebook etc. which will load on Header', 'catch-box' ),
			'field_type' 		=> 'textarea',
			'sanitize' 			=> 'wp_kses_stripslashes',
			'panel' 			=> 'webmaster_tools',
			'section' 			=> 'webmaster_tools',
			'active_callback'	=> 'catchbox_is_header_code_present',
			'default' 			=> ''
		),
		'tracker_footer' => array(
			'id' 				=> 'tracker_footer',
			'title' 			=> esc_html__( 'Code to display on Footer', 'catch-box' ),
			'description' 		=> esc_html__( 'Here you can put scripts from Google, Facebook etc. which will load on Footer', 'catch-box' ),
			'field_type' 		=> 'textarea',
			'sanitize' 			=> 'wp_kses_stripslashes',
			'panel' 			=> 'webmaster_tools',
			'section' 			=> 'webmaster_tools',
			'active_callback'	=> 'catchbox_is_footer_code_present',
			'default' 		=> ''
		),
	);


	if ( function_exists( 'has_custom_logo' ) ) {
		$settings_header_image = array(
			//Favicon
			'header_image_position' => array(
				'id'          => 'header_image_position',
				'title'       => esc_html__( 'Header Image Location', 'catch-box' ),
				'description' => '',
				'field_type'  => 'select',
				'sanitize'    => 'catchbox_sanitize_select',
				'section'     => 'header_image',
				'default'     => $defaults['header_image_position'],
				'choices'     => array(
					'above'   => esc_html__( 'Above Header Content', 'catch-box' ),
					'between' => esc_html__( 'Between Site Title-Logo', 'catch-box' ),
					'below'   => esc_html__( 'Below Header Content', 'catch-box' )
				)
			)
		);

		$settings_parameters = array_merge( $settings_parameters, $settings_header_image);
	}

	//@remove Remove if block and custom_css from $settings_paramater when WordPress 5.0 is released
	if( function_exists( 'wp_update_custom_css_post' ) ) {
		unset( $settings_parameters['custom_css'] );
	}

	foreach ( $settings_parameters as $option ) {
		if ( 'image' == $option['field_type'] ) {
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'sanitize_callback'	=> $option['sanitize'],
					'default'			=> $option['default'],
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,$theme_slug . 'options[' . $option['id'] . ']',
					array(
						'label'				=> $option['title'],
						'section'   		=> $theme_slug . $option['section'],
						'settings'  		=> $theme_slug . 'options[' . $option['id'] . ']',
						'active_callback'	=> $option['active_callback'],
					)
				)
			);
		}
		elseif ('checkbox' == $option['field_type'] ) {
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'sanitize_callback'	=> $option['sanitize'],
					'default'			=> $option['default'],				)
			);

			$params = array(
						'label'		=> $option['title'],
						'settings'  => $theme_slug . 'options[' . $option['id'] . ']',
						'name'  	=> $theme_slug . 'options[' . $option['id'] . ']',
					);

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			if ( 'title_tagline' == $option['section'] ) {
				$params['section'] = $option['section'];
			}
			else {
				$params['section']	= $theme_slug . $option['section'];
			}

			$wp_customize->add_control(
				new CatchBox_Customize_Checkbox(
					$wp_customize,$theme_slug . 'options[' . $option['id'] . ']',
					$params
				)
			);
		}
		else {
			//Normal Loop
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'default'			=> $option['default'],
					'sanitize_callback'	=> $option['sanitize']
				)
			);

			// Add setting control
			$params = array(
					'label'			=> $option['title'],
					'settings'		=> $theme_slug . 'options[' . $option['id'] . ']',
					'type'			=> $option['field_type'],
					'description'   => $option['description'],
				);

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			if ( isset( $option['choices']  ) ){
				$params['choices'] = $option['choices'];
			}

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			if ( isset( $option['input_attrs']  ) ){
				$params['input_attrs'] = $option['input_attrs'];
			}

			if ( 'header_image' == $option['section'] ) {
				$params['section'] = $option['section'];
			}
			else {
				$params['section']	= $theme_slug . $option['section'];
			}

			$wp_customize->add_control(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				$params
			);
		}
	}

	if ( !isset( $options['slider_qty'] ) || !is_numeric( $options['slider_qty'] ) ) {
		$options['slider_qty'] = 4;
	}

    //Add featured post elements with respect to no of featured sliders
	for ( $i = 1; $i <= $options['slider_qty']; $i++ ) {
		$wp_customize->add_setting(
			// $id
			$theme_slug . 'options[featured_slider][' . $i . ']',
			// parameters array
			array(
				'sanitize_callback'	=> 'catchbox_sanitize_post_id'
			)
		);

		$wp_customize->add_control(
			$theme_slug . 'options[featured_slider][' . $i . ']',
			array(
				'label'		=> sprintf( esc_html__( '#%s Featured Post ID', 'catch-box' ), $i ),
				'section'   => $theme_slug .'slider_options',
				'settings'  => $theme_slug . 'options[featured_slider][' . $i . ']',
				'type'		=> 'text',
					'input_attrs' => array(
	        		'style' => 'width: 100px;'
	    		),
				//'active_callback' 	=> 'catchbox_is_post_slider_active'
			)
		);
	}


	// Reset all settings to default
	$wp_customize->add_section( 'catchbox_reset_all_settings', array(
		'description'	=> esc_html__( 'Caution: Reset all settings to default. Refresh the page after save to view full effects.', 'catch-box' ),
		'priority' 		=> 700,
		'title'    		=> esc_html__( 'Reset all settings', 'catch-box' ),
	) );

	$wp_customize->add_setting( 'catchbox_theme_options[reset_all_settings]', array(
		'capability'		=> 'edit_theme_options',
		'sanitize_callback' => 'catchbox_sanitize_checkbox',
		'transport'			=> 'postMessage',
	) );

	$wp_customize->add_control( 'catchbox_theme_options[reset_all_settings]', array(
		'label'    => esc_html__( 'Check to reset all settings to default', 'catch-box' ),
		'section'  => 'catchbox_reset_all_settings',
		'settings' => 'catchbox_theme_options[reset_all_settings]',
		'type'     => 'checkbox'
	) );
	// Reset all settings to default end

	//Important Links
	$wp_customize->add_section( 'important_links', array(
		'priority' 		=> 999,
		'title'   	 	=> esc_html__( 'Important Links', 'catch-box' ),
	) );

	/**
	 * Has dummy Sanitizaition function as it contains no value to be sanitized
	 */
	$wp_customize->add_setting( 'important_links', array(
		'sanitize_callback'	=> 'sanitize_text_field',
	) );

	$wp_customize->add_control( new CatchBox_Important_Links( $wp_customize, 'important_links', array(
        'label'   	=> esc_html__( 'Important Links', 'catch-box' ),
        'section'  	=> 'important_links',
        'settings' 	=> 'important_links',
        'type'     	=> 'important_links',
    ) ) );
    //Important Links End
}
add_action( 'customize_register', 'catchbox_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously for adventurous.
 * And flushes out all transient data on preview
 *
 * @since Catch Kathmandu 3.4
 */
function catchbox_customize_preview() {
	//Remove transients on preview
	catchbox_flush_transients();
}
add_action( 'customize_preview_init', 'catchbox_customize_preview' );
add_action( 'customize_save', 'catchbox_customize_preview' );


/**
 * Custom scripts and styles on Customizer for Catch Box
 *
 * @since Catch Box 3.3
 */
function catchbox_customize_scripts() {
    wp_enqueue_script( 'catchbox_customizer_custom', get_template_directory_uri() . '/js/customizer-custom-scripts.js', array( 'jquery' ), '20140108', true );

    $catchbox_misc_links = array(
		'reset_message' => esc_html__( 'Refresh the customizer page after saving to view reset effects', 'catch-box' ),
	);

	// Send reset message as object to custom customizer js
    wp_localize_script( 'catchbox_customizer_custom', 'catchbox_misc_links', $catchbox_misc_links );
}
add_action( 'customize_controls_enqueue_scripts', 'catchbox_customize_scripts');


/**
 * Function to reset date with respect to condition
 *
 * @since Catch Box Pro 4.9
 */
function catchbox_reset_data() {
	$options  = catchbox_get_options();

    if ( $options['reset_all_settings'] ) {
    	remove_theme_mods();

    	// Flush out all transients	on reset
        catchbox_flush_transients();

        return;
    }
}
//add_action( 'customize_save_after', 'catchbox_reset_data' );


//Active callbacks for customizer
require trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-active-callbacks.php';

//Sanitize functions for customizer
require trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-sanitize-functions.php';

// Add Upgrade to Pro Button.
require trailingslashit( get_template_directory() ) . 'inc/customizer/upgrade-button/class-customize.php';