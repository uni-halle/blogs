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
	$options = catchbox_get_theme_options();

	$defaults = catchbox_get_default_theme_options();

	//print_r($options);

	//Custom Controls
	require get_template_directory() . '/inc/customizer/customizer-custom-controls.php';

	$theme_slug = 'catchbox_theme_';

	$settings_page_tabs = array(
		'theme_options' => array(
			'id' 			=> 'theme_options',
			'title' 		=> __( 'Theme Options', 'catch-box' ),
			'description' 	=> __( 'Basic theme Options', 'catch-box' ),
			'sections' 		=> array(
				'favicon' => array(
					'id' 			=> 'favicon',
					'title' 		=> __( 'Favicon', 'catch-box' ),
					'description' 	=> '',
				),
				'web_clip_icon_options' => array(
					'id' 			=> 'web_clip_icon_options',
					'title' 		=> __( 'Webclip Icon Options', 'catch-box' ),
					'description' 	=> __( 'Web Clip Icon for Apple devices. Recommended Size - Width 144px and Height 144px height, which will support High Resolution Devices like iPad Retina', 'catch-box' )
				),
				'color_scheme' => array(
					'id' 			=> 'color_scheme',
					'title' 		=> __( 'Color Scheme', 'catch-box' ),
					'description' 	=> '',
				),
				'link_color' => array(
					'id' 			=> 'link_color',
					'title' 		=> __( 'Link Color', 'catch-box' ),
					'description' 	=> '',
				),
				'theme_layout' => array(
					'id' 			=> 'theme_layout',
					'title' 		=> __( 'Default Layout', 'catch-box' ),
					'description' 	=> '',
				),
				'content_layout' => array(
					'id' 			=> 'content_layout',
					'title' 		=> __( 'Content Layout', 'catch-box' ),
					'description' 	=> '',
				),
				'excerpt_length' => array(
					'id' 			=> 'excerpt_length',
					'title' 		=> __( 'Excerpt Length in Words', 'catch-box' ),
					'description' 	=> '',
				),
				'feed_url' => array(
					'id' 			=> 'feed_url',
					'title' 		=> __( 'Feed Redirect URL', 'catch-box' ),
					'description' 	=> '',
				),
				'site_title_above' => array(
					'id' 			=> 'site_title_above',
					'title' 		=> __( 'Move Site Title and Tagline?', 'catch-box' ),
					'description' 	=> '',
				),
				'search_display_text' => array(
					'id' 			=> 'search_display_text',
					'title' 		=> __( 'Default Display Text in Search', 'catch-box' ),
					'description' 	=> '',
				),
				'disable_header_search' => array(
					'id' 			=> 'disable_header_search',
					'title' 		=> __( 'Disable Search in Header?', 'catch-box' ),
					'description' 	=> '',
				),
				'enable_menus' => array(
					'id' 			=> 'enable_menus',
					'title' 		=> __( 'Enable Secondary & Footer Menu in Mobile Devices?', 'catch-box' ),
					'description' 	=> '',
				),
				'custom_css' => array(
					'id' 			=> 'custom_css',
					'title' 		=> __( 'Custom CSS Styles', 'catch-box' ),
					'description' 	=> '',
				),
				'scrollup' => array(
					'id' 			=> 'scrollup',
					'title' 		=> __( 'Scroll Up Options', 'catch-box' ),
					'description' 	=> '',
				),
			),
		),

		'featured_slider' => array(
			'id' 			=> 'featured_slider',
			'title' 		=> __( 'Featured Slider', 'catch-box' ),
			'description' 	=> __( 'Featured Slider', 'catch-box' ),
			'sections' 		=> array(
				'slider_options' => array(
					'id' 			=> 'slider_options',
					'title' 		=> __( 'Slider Options', 'catch-box' ),
					'description' 	=> '',
				),
				'slider_effect_options' => array(
					'id' 			=> 'slider_effect_options',
					'title' 		=> __( 'Slider Effect Options', 'catch-box' ),
					'description' 	=> '',
				),
			)
		),

		'social_links' => array(
			'id' 			=> 'social_links',
			'title' 		=> __( 'Social Links', 'catch-box' ),
			'description' 	=> __( 'Add your social links here', 'catch-box' ),
			'sections' 		=> array(
				'predefined_social_icons' => array(
					'id' 			=> 'predefined_social_icons',
					'title' 		=> __( 'Predefined Social Icons', 'catch-box' ),
					'description' 	=> '',
				),
			),
		),
		'webmaster_tools' => array(
			'id' 			=> 'webmaster_tools',
			'title' 		=> __( 'Webmaster Tools', 'catch-box' ),
			'description' 	=>  sprintf( __( 'Webmaster Tools falls under Plugins Territory according to Theme Review Guidelines in WordPress.org. This feature will be depreciated in future versions from Catch Box free version. If you want this feature, then you can add <a href="%s">Catch Web Tools</a>  plugin.', 'catch-box' ), esc_url( 'https://wordpress.org/plugins/catch-web-tools/' ) ),
			'sections' 		=> array(
				'webmaster_tools' => array(
					'id' 			=> 'webmaster_tools',
					'title' 		=> __( 'Webmaster Tools', 'catch-box' ),
					'description' 	=>  sprintf( __( 'Webmaster Tools falls under Plugins Territory according to Theme Review Guidelines in WordPress.org. This feature will be depreciated in future versions from Catch Box free version. If you want this feature, then you can add <a href="%s">Catch Web Tools</a>  plugin.', 'catch-box' ), esc_url( 'https://wordpress.org/plugins/catch-web-tools/' ) ),
				),
			),
		),
	);

	//Add Panels and sections
	foreach ( $settings_page_tabs as $panel ) {
		$wp_customize->add_panel(
			$theme_slug . $panel['id'],
			array(
				'priority' 		=> 200,
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

	$settings_parameters = array(
		//Color Scheme
		'color_scheme' => array(
			'id' 			=> 'color_scheme',
			'title' 		=> __( 'Color Scheme', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'radio',
			'sanitize' 		=> 'catchbox_sanitize_select',
			'panel' 		=> 'theme_options',
			'section' 		=> 'color_scheme',
			'default' 		=> $defaults['color_scheme'],
			'choices'		=> $catchbox_color_schemes,
		),
		'link_color' => array(
			'id' 			=> 'link_color',
			'title' 		=> __( 'Link Color', 'catch-box' ),
			'description' 	=> '',
			'field_type' 	=> 'color',
			'sanitize' 		=> 'sanitize_hex_color',
			'panel' 		=> 'theme_options',
			'section' 		=> 'link_color',
			'default' 		=> $defaults['link_color']
		),
		'theme_layout' => array(
			'id' 			=> 'theme_layout',
			'title' 		=> __( 'Default Layout', 'catch-box' ),
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
			'title' 		=> __( 'Content Layout', 'catch-box' ),
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
			'title' 		=> __( 'Excerpt Length in Words', 'catch-box' ),
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
			'title' 			=> __( 'Feed Redirect url', 'catch-box' ),
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
			'title' 		=> __( 'Check to move above the Header/Logo Image', 'catch-box' ),
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchbox_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'site_title_above',
			'default' 		=> $defaults['site_title_above']
		),
		'search_display_text' => array(
			'id' 			=> 'search_display_text',
			'title' 		=> __( 'Default Display Text in Search', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'text',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'theme_options',
			'section' 		=> 'search_display_text',
			'default' 		=> $defaults['search_display_text']
		),
		'disable_header_search' => array(
			'id' 			=> 'disable_header_search',
			'title' 		=> __( 'Check to Disable', 'catch-box' ),
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchbox_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'disable_header_search',
			'default' 		=> $defaults['disable_header_search']
		),
		'enable_menus' => array(
			'id' 			=> 'enable_menus',
			'title' 		=> __( 'Check to Enable', 'catch-box' ),
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchbox_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'enable_menus',
			'default' 		=> $defaults['enable_menus']
		),
		'custom_css' => array(
			'id' 			=> 'custom_css',
			'title' 		=> __( 'Custom CSS Styles', 'catch-box' ),
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
			'title' 		=> __( 'Check to disable scroll up', 'catch-box' ),
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
			'title' 			=> __( 'Check to Exclude Slider posts from Homepage posts', 'catch-box' ),
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'catchbox_sanitize_checkbox',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['exclude_slider_post'],
		),
		'slider_qty' => array(
			'id' 				=> 'slider_qty',
			'title' 			=> __( 'Number of Slides', 'catch-box' ),
			'description'		=> __( 'Customizer page needs to be refreshed after saving if number of slides is changed', 'catch-box' ),
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
			'title' 			=> __( 'Transition Effect', 'catch-box' ),
			'description'		=> '',
			'field_type' 		=> 'select',
			'sanitize' 			=> 'catchbox_sanitize_select',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_effect_options',
			'default' 			=> $defaults['transition_effect'],
			'choices'			=> array(
										'fade'			=> __( 'fade', 'catch-box' ),
										'wipe'			=> __( 'wipe', 'catch-box' ),
										'scrollUp'		=> __( 'scrollUp', 'catch-box' ),
										'scrollDown'	=> __( 'scrollDown', 'catch-box' ),
										'scrollLeft'	=> __( 'scrollLeft', 'catch-box' ),
										'scrollRight'	=> __( 'scrollRight', 'catch-box' ),
										'blindX'		=> __( 'blindX', 'catch-box' ),
										'blindY'		=> __( 'blindY', 'catch-box' ),
										'blindZ'		=> __( 'blindZ', 'catch-box' ),
										'cover'			=> __( 'cover', 'catch-box' ),
										'shuffle'		=> __( 'shuffle', 'catch-box' ),
									)
		),
		'transition_delay' => array(
			'id' 				=> 'transition_delay',
			'title' 			=> __( 'Transition Delay (in seconds)', 'catch-box' ),
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
			'id' 				=> 'transition_duration (in seconds)',
			'title' 			=> __( 'Transition Length', 'catch-box' ),
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
			'title' 		=> __( 'Facebook', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_twitter' => array(
			'id' 			=> 'social_twitter',
			'title' 		=> __( 'Twitter', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_google' => array(
			'id' 			=> 'social_google',
			'title' 		=> __( 'Google+', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_linkedin' => array(
			'id' 			=> 'social_linkedin',
			'title' 		=> __( 'LinkedIn', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_pinterest' => array(
			'id' 			=> 'social_pinterest',
			'title' 		=> __( 'Pinterest', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_youtube' => array(
			'id' 			=> 'social_youtube',
			'title' 		=> __( 'Youtube', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_rss' => array(
			'id' 			=> 'social_rss',
			'title' 		=> __( 'RSS', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_deviantart' => array(
			'id' 			=> 'social_deviantart',
			'title' 		=> __( 'deviantART', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_tumblr' => array(
			'id' 			=> 'social_tumblr',
			'title' 		=> __( 'Tumblr', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_viemo' => array(
			'id' 			=> 'social_viemo',
			'title' 		=> __( 'Vimeo', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_dribbble' => array(
			'id' 			=> 'social_dribbble',
			'title' 		=> __( 'Dribbble', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_myspace' => array(
			'id' 			=> 'social_myspace',
			'title' 		=> __( 'MySpace', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_aim' => array(
			'id' 			=> 'social_aim',
			'title' 		=> __( 'Aim', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_flickr' => array(
			'id' 			=> 'social_flickr',
			'title' 		=> __( 'Flickr', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_slideshare' => array(
			'id' 			=> 'social_slideshare',
			'title' 		=> __( 'Slideshare', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_instagram' => array(
			'id' 			=> 'social_instagram',
			'title' 		=> __( 'Instagram', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_skype' => array(
			'id' 			=> 'social_skype',
			'title' 		=> __( 'Skype', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_soundcloud' => array(
			'id' 			=> 'social_soundcloud',
			'title' 		=> __( 'Soundcloud', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_email' => array(
			'id' 			=> 'social_email',
			'title' 		=> __( 'Email', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'sanitize_email',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_xing' => array(
			'id' 			=> 'social_xing',
			'title' 		=> __( 'Xing', 'catch-box' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> ''
		),
		'social_meetup' => array(
			'id' 			=> 'social_meetup',
			'title' 		=> __( 'Meetup', 'catch-box' ),
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
			'title' 			=> __( 'Code to display on Header', 'catch-box' ),
			'description' 		=> __( 'Here you can put scripts from Google, Facebook etc. which will load on Header', 'catch-box' ),
			'field_type' 		=> 'textarea',
			'sanitize' 			=> 'wp_kses_stripslashes',
			'panel' 			=> 'webmaster_tools',
			'section' 			=> 'webmaster_tools',
			'active_callback'	=> 'catchbox_is_header_code_present',
			'default' 			=> ''
		),
		'tracker_footer' => array(
			'id' 				=> 'tracker_footer',
			'title' 			=> __( 'Code to display on Footer', 'catch-box' ),
			'description' 		=> __( 'Here you can put scripts from Google, Facebook etc. which will load on Footer', 'catch-box' ),
			'field_type' 		=> 'textarea',
			'sanitize' 			=> 'wp_kses_stripslashes',
			'panel' 			=> 'webmaster_tools',
			'section' 			=> 'webmaster_tools',
			'active_callback'	=> 'catchbox_is_footer_code_present',
			'default' 		=> ''
		),
	);

	//@remove Remove if block when WordPress 4.8 is released
	if( !function_exists( 'has_site_icon' ) ) {
		$settings_favicon = array(
			//Favicon
			'fav_icon' => array(
				'id' 				=> 'fav_icon',
				'title' 			=> __( 'Fav Icon', 'catch-box' ),
				'description'		=> '',
				'field_type' 		=> 'image',
				'sanitize' 			=> 'catchbox_sanitize_image',
				'panel' 			=> 'theme_options',
				'section' 			=> 'favicon',
				'default' 			=> '',
				'active_callback'	=> 'catchbox_is_site_icon_active',
			),
			//Web Clip Icon
			'web_clip' => array(
				'id' 				=> 'web_clip',
				'title' 			=> __( 'Web Clip Icon', 'catch-box' ),
				'description'		=> '',
				'field_type' 		=> 'image',
				'sanitize' 			=> 'catchbox_sanitize_image',
				'panel' 			=> 'theme_options',
				'section' 			=> 'web_clip_icon_options',
				'default' 			=> '',
				'active_callback'	=> 'catchbox_is_site_icon_active',
			),
		);

		$settings_parameters = array_merge( $settings_parameters, $settings_favicon);
	}

	foreach ( $settings_parameters as $option ) {
		if( 'image' == $option['field_type'] ) {
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'type'				=> 'option',
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
		else if ('checkbox' == $option['field_type'] ) {
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize'],
					'default'			=> $option['default'],				)
			);

			$params = array(
						'label'		=> $option['title'],
						'settings'  => $theme_slug . 'options[' . $option['id'] . ']',
						'name'  	=> $theme_slug . 'options[' . $option['id'] . ']',
						'section'	=> $theme_slug . $option['section']
					);

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
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
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize']
				)
			);

			// Add setting control
			$params = array(
					'label'			=> $option['title'],
					'settings'		=> $theme_slug . 'options[' . $option['id'] . ']',
					'type'			=> $option['field_type'],
					'description'   => $option['description'],
					'section'	=> $theme_slug . $option['section']
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

			$wp_customize->add_control(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				$params
			);
		}
	}

	if( !isset( $options['slider_qty'] ) || !is_numeric( $options['slider_qty'] ) ) {
		$options[ 'slider_qty' ] = 4;
	}

    //Add featured post elements with respect to no of featured sliders
	for ( $i = 1; $i <= $options[ 'slider_qty' ]; $i++ ) {
		$wp_customize->add_setting(
			// $id
			$theme_slug . 'options[featured_slider][' . $i . ']',
			// parameters array
			array(
				'type'				=> 'option',
				'sanitize_callback'	=> 'catchbox_sanitize_post_id'
			)
		);

		$wp_customize->add_control(
			$theme_slug . 'options[featured_slider][' . $i . ']',
			array(
				'label'		=> sprintf( __( '#%s Featured Post ID', 'catch-box' ), $i ),
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
		'description'	=> __( 'Caution: Reset all settings to default. Refresh the page after save to view full effects.', 'catch-box' ),
		'priority' 		=> 700,
		'title'    		=> __( 'Reset all settings', 'catch-box' ),
	) );

	$wp_customize->add_setting( 'catchbox_theme_options[reset_all_settings]', array(
		'capability'		=> 'edit_theme_options',
		'sanitize_callback' => 'catchbox_reset_all_settings',
		'transport'			=> 'postMessage',
	) );

	$wp_customize->add_control( 'catchbox_theme_options[reset_all_settings]', array(
		'label'    => __( 'Check to reset all settings to default', 'catch-box' ),
		'section'  => 'catchbox_reset_all_settings',
		'settings' => 'catchbox_theme_options[reset_all_settings]',
		'type'     => 'checkbox'
	) );
	// Reset all settings to default end

	//Important Links
	$wp_customize->add_section( 'important_links', array(
		'priority' 		=> 999,
		'title'   	 	=> __( 'Important Links', 'catch-box' ),
	) );

	/**
	 * Has dummy Sanitizaition function as it contains no value to be sanitized
	 */
	$wp_customize->add_setting( 'important_links', array(
		'sanitize_callback'	=> 'catchbox_sanitize_important_link',
	) );

	$wp_customize->add_control( new CatchBox_Important_Links( $wp_customize, 'important_links', array(
        'label'   	=> __( 'Important Links', 'catch-box' ),
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
	catchbox_themeoption_invalidate_caches();
}
add_action( 'customize_preview_init', 'catchbox_customize_preview' );
add_action( 'customize_save', 'catchbox_customize_preview' );


/**
 * Custom scripts and styles on Customizer for Catch Box
 *
 * @since Catch Box 3.3
 */
function catchbox_customize_scripts() {
    wp_register_script( 'catchbox_customizer_custom', get_template_directory_uri() . '/inc/customizer-custom-scripts.js', array( 'jquery' ), '20140108', true );

    $catchbox_misc_links = array(
                            'upgrade_link' 				=> esc_url( 'http://catchthemes.com/themes/catch-box-pro/' ),
                            'upgrade_text'              => __( 'Upgrade To Pro', 'catch-box' ),
                            );

    //Add More Theme Options Button
    wp_localize_script( 'catchbox_customizer_custom', 'catchbox_misc_links', $catchbox_misc_links );

    wp_enqueue_script( 'catchbox_customizer_custom' );

    wp_enqueue_style( 'catchbox_customizer_custom', get_template_directory_uri() . '/inc/catchbox-customizer.css');
}
add_action( 'customize_controls_enqueue_scripts', 'catchbox_customize_scripts');


//Active callbacks for customizer
require get_template_directory() . '/inc/customizer/customizer-active-callbacks.php';

//Sanitize functions for customizer
require get_template_directory() . '/inc/customizer/customizer-sanitize-functions.php';
