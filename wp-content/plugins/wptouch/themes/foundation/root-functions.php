<?php
define( 'FOUNDATION_VERSION', '4.0' );

define( 'FOUNDATION_DIR', WPTOUCH_DIR . '/themes/foundation' );
define( 'FOUNDATION_URL', WPTOUCH_URL . '/themes/foundation' );

define( 'FOUNDATION_SETTING_DOMAIN', 'foundation' );

define( 'FOUNDATION_PAGE_GENERAL', __( 'Theme Settings', 'wptouch-pro' ) );
define( 'FOUNDATION_PAGE_BRANDING', __( 'Branding', 'wptouch-pro' ) );
define( 'FOUNDATION_PAGE_MEDIA', __( 'Media Handling', 'wptouch-pro' ) );
define( 'FOUNDATION_PAGE_HOMESCREEN_ICONS', __( 'Bookmark Icons', 'wptouch-pro' ) );
define( 'FOUNDATION_PAGE_CUSTOM', __( 'Custom Content', 'wptouch-pro' ) );
define( 'FOUNDATION_MAX_LOGO_SIZE', 1136 );

add_filter( 'wptouch_registered_setting_domains', 'foundation_setting_domain' );
add_filter( 'wptouch_setting_defaults_foundation', 'foundation_setting_defaults' );
add_filter( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_render_theme_settings' );
add_filter( 'wptouch_setting_version_compare', 'foundation_setting_version_compare', 10, 2 );
add_filter( 'wptouch_body_classes', 'foundation_body_classes' );
add_filter( 'wptouch_the_content', 'foundation_insert_multipage_links' );
add_filter( 'wptouch_update_settings', 'foundation_sanitize_wptouch_settings' );

// fix for JetPack
add_filter( 'jetpack_check_mobile', 'foundation_override_jetpack_check_mobile' );

add_action( 'wptouch_post_head', 'foundation_setup_smart_app_banner' );
add_action( 'wptouch_post_head', 'foundation_setup_viewport' );
add_action( 'wptouch_post_head', 'foundation_setup_homescreen_icons' );
add_action( 'wptouch_post_head', 'foundation_inline_styles' );
add_action( 'wptouch_post_head', 'foundation_add_meta_theme_color' );

add_action( 'pre_get_posts', 'foundation_posts_per_page' );
add_filter( 'pre_get_posts', 'foundation_exclude_categories_tags' );

add_action( 'wptouch_pre_footer', 'foundation_handle_footer' );
add_action( 'wptouch_post_footer', 'foundation_enqueue_color_data' );
add_action( 'wptouch_post_footer', 'foundation_handle_custom_css_declarations' );
add_action( 'wptouch_post_process_image_file', 'foundation_process_image_file', 10, 2 );

add_action( 'wptouch_language_insert', 'foundation_add_wpml_lang_switcher', 20 );

function foundation_add_wpml_lang_switcher() {
	$settings = wptouch_get_settings();

	// Check admin panel setting
	if ( $settings->show_wpml_lang_switcher ) {
		if ( function_exists( 'icl_get_languages' ) ) {
			$data = icl_get_languages( 'skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str' );
			if ( $data ) {
				echo '<div id="wpml-language-chooser-wrap"><div id="wpml-language-chooser">';
				echo '<strong>' . __( 'Language: ', 'wptouch-pro' ) . '</strong>';
				echo '<select>';
				foreach ( $data as $lang => $item ) {
					echo '<option value="' . esc_url( $item['url'] ) . '"';
					if ( $item["active"] ) {
						echo " selected";
					}
					echo '>' . esc_html( $item['native_name'] ) . '</option>';
				}
				echo '</select>';
				echo '</div></div>';
			}
		}
	}

}

function foundation_setting_domain( $domains ) {
	$domains[] = FOUNDATION_SETTING_DOMAIN;

	return $domains;
}

function foundation_setting_version_compare( $version, $domain ) {
	if ( $domain == FOUNDATION_SETTING_DOMAIN ) {
		return FOUNDATION_VERSION;
	}

	return $version;
}

function foundation_process_image_file( $file_name, $setting_name ) {
	if ( $setting_name->domain == FOUNDATION_SETTING_DOMAIN && $setting_name->name == 'logo_image' ) {
		// Need to make sure this isn't too big
		if ( function_exists( 'getimagesize' ) && function_exists( 'imagecreatefrompng' ) && function_exists( 'imagecopyresampled' ) && function_exists( 'imagepng' ) ) {

			$size = getimagesize( $file_name );
			if ( $size ) {
				$width  = $size[0];
				$height = $size[1];

				if ( $size['mime'] == 'image/png' ) {
					if ( $width > FOUNDATION_MAX_LOGO_SIZE ) {
						$new_width  = FOUNDATION_MAX_LOGO_SIZE;
						$new_height = $height * $new_width / $width;

						$src_image = imagecreatefrompng( $file_name );

						$saved_image = imagecreatetruecolor( $new_width, $new_height );

						// Preserve Transparency
						imagecolortransparent( $saved_image, imagecolorallocate( $saved_image, 0, 0, 0 ) );

						imagecopyresampled( $saved_image, $src_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

						// Get rid of the old file
						unlink( $file_name );

						// New image, compression level 5 (make it a bit smaller)
						imagepng( $saved_image, $file_name, 5 );
					}
				}
			}
		}
	}
}

function foundation_prepare_uploaded_file_url( $uploaded_file ) {
	if ( ! strstr( $uploaded_file, 'http' ) && ! strstr( $uploaded_file, 'wp-content' ) ) {
		$uploaded_file = WPTOUCH_BASE_CONTENT_URL . $uploaded_file;
	} else {
		$uploaded_file = wptouch_check_url_ssl( $uploaded_file );
	}

	return $uploaded_file;
}

function foundation_setting_defaults( $settings ) {

	// Depreciated and removed in 4.0
	$settings->allow_nested_comment_replies = false;
	$settings->twitter_account              = false;

	// Landing Pages
	$settings->latest_posts_page = 'none';

	// Compatibility
	$settings->new_video_handling = true;

	// Theme Settings
	$settings->allow_zoom              = false;
	$settings->smart_app_banner        = '';
	$settings->custom_footer_message   = '';
	$settings->custom_css_declarations = '';

	// Misc
	$settings->logo_image = '';

	// Blog
	$settings->posts_per_page      = '5';
	$settings->excluded_categories = '';
	$settings->excluded_tags       = '';

	// Branding
	$settings->typography_sets = 'default';

	// Homescreen Icons
	$settings->iphone_icon_retina  = false;
	$settings->android_others_icon = false;
	$settings->ipad_icon_retina    = false;

	// Web App Mode
	$settings->webapp_enable_persistence = false;
	$settings->webapp_show_notice        = true;
	$settings->webapp_ignore_urls        = '';
	$settings->webapp_notice_expiry_days = 30;

	// Statrup Screens
	$settings->startup_screen_iphone_2g_3g        = false;
	$settings->startup_screen_iphone_4_4s         = false;
	$settings->startup_screen_iphone_5            = false;
	$settings->startup_screen_iphone_6            = false;
	$settings->startup_screen_iphone_6plus        = false;
	$settings->startup_screen_ipad_1_portrait     = false;
	$settings->startup_screen_ipad_1_landscape    = false;
	$settings->startup_screen_ipad_3_portrait     = false;
	$settings->startup_screen_ipad_3_landscape    = false;
	$settings->startup_screen_full_res            = false;
	$settings->startup_screen_ipad_full_portrait  = false;
	$settings->startup_screen_ipad_full_landscape = false;

	// Advertising
	$settings->advertising_type          = 'none';
	$settings->advertising_location      = 'header';
	$settings->advertising_blog_listings = true;
	$settings->advertising_single        = true;
	$settings->advertising_pages         = false;
	$settings->advertising_taxonomy      = true;
	$settings->advertising_search        = true;
	$settings->google_adsense_id         = '';
	$settings->google_slot_id            = '';
	$settings->google_code_type          = 'sync';
	$settings->custom_advertising_mobile = '';

	// Sharing
	$settings->show_share          = true;
	$settings->share_on_pages      = false;
	$settings->share_location      = 'top';
	$settings->share_colour_scheme = 'default';

	// Social Links
	$settings->social_facebook_url  = '';
	$settings->social_twitter_url   = '';
	$settings->social_google_url    = '';
	$settings->social_instagram_url = '';
	$settings->social_tumblr_url    = '';
	$settings->social_pinterest_url = '';
	$settings->social_vimeo_url     = '';
	$settings->social_youtube_url   = '';
	$settings->social_linkedin_url  = '';
	$settings->social_yelp_url      = '';
	$settings->social_email_url     = '';
	$settings->social_rss_url       = '';

	// Featured Slider
	$settings->featured_enabled             = true;
	$settings->featured_blog                = true;
	$settings->featured_homepage            = true;
	$settings->featured_style               = 'enhanced';
	$settings->featured_autoslide           = false;
	$settings->featured_continuous          = false;
	$settings->featured_grayscale           = false;
	$settings->featured_comments            = false;
	$settings->featured_type                = 'latest';
	$settings->featured_tag                 = '';
	$settings->featured_category            = '';
	$settings->featured_post_type           = '';
	$settings->featured_post_ids            = '';
	$settings->featured_speed               = 'normal';
	$settings->featured_max_number_of_posts = '5';
	$settings->featured_filter_posts        = true;

	return $settings;
}

function foundation_has_logo_image() {
	$settings = foundation_get_settings();

	return ( $settings->logo_image != '' );
}

function foundation_the_logo_image() {
	$settings = foundation_get_settings();

	echo esc_url( foundation_prepare_uploaded_file_url( $settings->logo_image ) );
}

function foundation_enqueue_color_data() {
	$colors = foundation_get_theme_colors();
	if ( is_array( $colors ) && count( $colors ) ) {
		$inline_color_data = '';

		foreach ( $colors as $color ) {
			$settings = wptouch_get_settings( $color->domain );

			$setting_name = $color->setting;

			$output_color = $settings->$setting_name;

			if ( $color->fg_selectors ) {
				$inline_color_data .= $color->fg_selectors . " { color: " . $output_color . "; }\n";
			}

			if ( $color->bg_selectors ) {
				$inline_color_data .= $color->bg_selectors . " { background-color: " . $output_color . "; }\n";
			}
		}

		echo '<style>';
		echo $inline_color_data;
		echo '</style>';
	}
}

function foundation_handle_footer() {
	$settings = foundation_get_settings();

	if ( $settings->custom_footer_message ) {

		/**
		 * Filters the custom footer message obtained from the custom footer section.
		 *
		 * @param string $settings ->custom_footer_message The footer message.
		 */
		$message = apply_filters( 'foundation_footer_message', $settings->custom_footer_message );

		if ( strip_tags( $message ) == $message ) {
			$output_message = '<p>' . $message . '</p>';
		} elseif ( 1 === preg_match( '/<script[\s\S]*?>[\s\S]*?<\/script>/', $message, $matches ) ) {
			$output_message = preg_replace( array( '/\\\"/', "/\\\'/" ), array( "'", '"' ), $message );
		} else {
			$output_message = $message;
		}


		$allowed_tags                           = wp_kses_allowed_html( 'post' );
		$allowed_tags['form']['autocomplete']   = true;
		$allowed_tags['form']['novalidate']     = true;
		$allowed_tags['form']['autocapitalize'] = true;
		$allowed_tags['input']                  = array(
			'class'           => true,
			'id'              => true,
			'name'            => true,
			'value'           => true,
			'type'            => true,
			'placeholder'     => true,
			'required'        => true,
			'accept'          => true,
			'align'           => true,
			'alt'             => true,
			'autocomplete '   => true,
			'autofocus'       => true,
			'checked'         => true,
			'disabled'        => true,
			'form'            => true,
			'formaction'      => true,
			'formenctype'     => true,
			'formmethod '     => true,
			'formnovalidate ' => true,
			'formtarget'      => true,
			'formtarget'      => true,
			'height'          => true,
			'list'            => true,
			'max'             => true,
			'maxlength'       => true,
			'min'             => true,
			'multiple'        => true,
			'pattern'         => true,
			'readonly'        => true,
			'size'            => true,
			'step'            => true,
			'width'           => true,
		);
		$allowed_tags['script']                 = array(
			'async'       => true,
			'crossorigin' => true,
			'defer'       => true,
			'integrity'   => true,
			'nomodule'    => true,
			'src'         => true,
			'text'        => true,
			'type'        => true,
		);

		/**
		 * Filters the custom footer message obtained from the custom footer section.
		 *
		 * @param string $output_message The footer message for output.
		 * @param array  $allowed_tags   The allowed tags for the sanitization function.
		 */
		echo wp_kses( apply_filters( 'foundation_footer_message_output', $output_message ), $allowed_tags );
	}
}

function foundation_handle_custom_css_declarations() {
	$settings = foundation_get_settings();
	if ( $settings->custom_css_declarations ) {
		/**
		 * Filters the custom css declarations from the customizer settings.
		 *
		 * @param string $settings ->custom_css_declarations The css declarations.
		 */
		$styles         = apply_filters( 'foundation_custom_css_declarations', $settings->custom_css_declarations );
		$trimmed_styles = trim( $styles );

		if ( strip_tags( $trimmed_styles ) == $trimmed_styles ) {
			$output_code = '<style>' . $trimmed_styles . '</style>';
		} else {
			$output_code = $trimmed_styles;
		}

		/**
		 * Filters the custom css declarations from the customizer settings before ouput.
		 *
		 * @param string $output_code The css declarations code before output.
		 */
		echo apply_filters( 'foundation_footer_css_declarations_output', $output_code );
	}
}

function foundation_get_settings() {
	return wptouch_get_settings( FOUNDATION_SETTING_DOMAIN );
}

function foundation_posts_per_page( $query ) {
	if ( wptouch_is_showing_mobile_theme_on_mobile_device() && $query->is_main_query() && ( $query->is_home() || is_archive() ) ) {
		$settings = foundation_get_settings();

		set_query_var( 'posts_per_page', $settings->posts_per_page );
	}
}

function foundation_is_theme_using_module( $module_name ) {
	$theme_data = foundation_get_theme_data();

	return in_array( $module_name, $theme_data->theme_support );
}

function foundation_get_tag_list() {
	$all_tags = array();

	$tags = get_tags(
		array(
			'number'  => 50,
			'orderby' => 'count',
		)
	);

	foreach ( $tags as $tag ) {
		$all_tags[ $tag->slug ] = $tag->name;
	}

	return $all_tags;
}

function foundation_get_category_list() {
	$all_cats = array();

	$categories = get_categories(
		array(
			'number'  => 50,
			'orderby' => 'count',
		)
	);

	foreach ( $categories as $cat ) {
		$all_cats[ $cat->slug ] = $cat->name;
	}

	return $all_cats;
}

function foundation_setup_viewport() {
	$settings  = foundation_get_settings();
	$zoomState = 'no';
	if ( $settings->allow_zoom == true ) {
		$zoomState = 'yes';
	}
	echo '<meta name="viewport" content="initial-scale=1.0, maximum-scale=3.0, user-scalable=' . esc_attr( $zoomState ) . ', width=device-width" />';
}

function foundation_render_theme_settings( $page_options ) {
	wptouch_add_sub_page( FOUNDATION_PAGE_GENERAL, 'foundation-page-theme-settings', $page_options );

	if ( ! wptouch_admin_use_customizer() ) {
		if ( foundation_has_theme_colors() ) {
			$color_settings = array();

			$colors = foundation_get_theme_colors();

			foreach ( $colors as $name => $color ) {
				$color_settings[] = wptouch_add_setting(
					'color',
					$color->setting,
					$color->desc,
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0',
					'',
					$color->domain
				);
			}

			wptouch_add_page_section(
				FOUNDATION_PAGE_BRANDING,
				__( 'Theme Colors', 'wptouch-pro' ),
				'foundation-colors',
				$color_settings,
				$page_options,
				FOUNDATION_SETTING_DOMAIN,
				false,
				false,
				20
			);
		}
	}

	$foundation_blog_settings = array(
		wptouch_add_pro_setting(
			'range',
			'posts_per_page',
			__( 'Number of posts in post listings', 'wptouch-pro' ),
			__( 'Overrides the WordPress Reading settings for "Blog pages show at most"', 'wptouch-pro' ),
			WPTOUCH_SETTING_BASIC,
			'1.0',
			array(
				'min'  => 1,
				'max'  => 15,
				'step' => 1,
			)
		),
		wptouch_add_pro_setting(
			'text',
			'excluded_categories',
			__( 'Excluded categories', 'wptouch-pro' ),
			__( 'Comma separated by category name', 'wptouch-pro' ),
			WPTOUCH_SETTING_BASIC,
			'1.0'
		),
		wptouch_add_pro_setting(
			'text',
			'excluded_tags',
			__( 'Excluded tags', 'wptouch-pro' ),
			__( 'Comma separated by tag name', 'wptouch-pro' ),
			WPTOUCH_SETTING_BASIC,
			'1.0'
		),
	);

	/**
	 * Filters the customizer settings for the blog section.
	 *
	 * @param array $foundation_blog_settings The blog settings.
	 */
	$foundation_blog_settings = apply_filters( 'foundation_settings_blog', $foundation_blog_settings );

	/**
	 * Filters the customizer settings for the page section.
	 *
	 * @param array Empty array.
	 */
	$foundation_page_settings = apply_filters( 'foundation_settings_page', array() );

	wptouch_add_page_section(
		FOUNDATION_PAGE_GENERAL,
		__( 'Blog', 'wptouch-pro' ),
		'foundation-web-theme-settings',
		$foundation_blog_settings,
		$page_options,
		FOUNDATION_SETTING_DOMAIN,
		true
	);

	wptouch_add_page_section(
		FOUNDATION_PAGE_GENERAL,
		__( 'Pages', 'wptouch-pro' ),
		'foundation-page-settings',
		$foundation_page_settings,
		$page_options,
		FOUNDATION_SETTING_DOMAIN,
		true
	);

	if ( ! function_exists( 'has_site_icon' ) ) {
		wptouch_add_page_section(
			FOUNDATION_PAGE_GENERAL,
			__( 'Site Icon', 'wptouch-pro' ),
			'admin_menu_homescreen_android',
			array(
				wptouch_add_setting(
					'image-upload',
					'iphone_icon_retina',
					sprintf( __( '%d by %d pixels (PNG)', 'wptouch-pro' ), 192, 192 ),
					false,
					WPTOUCH_SETTING_BASIC,
					'2.0'
				),
			),
			$page_options,
			FOUNDATION_SETTING_DOMAIN,
			true,
			false,
			30
		);
	}

	$foundation_header_settings = array(
		wptouch_add_setting(
			'image-upload',
			'logo_image',
			__( 'Site Logo', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			false,
			'1.0'
		),
	);

	/**
	 * Filters the customizer settings for the header section.
	 *
	 * @param array $foundation_header_settings The header settings.
	 */
	$foundation_header_settings = apply_filters( 'foundation_settings_header', $foundation_header_settings );

	wptouch_add_page_section(
		FOUNDATION_PAGE_BRANDING,
		__( 'Header', 'wptouch-pro' ),
		'foundation-header',
		$foundation_header_settings,
		$page_options,
		FOUNDATION_SETTING_DOMAIN,
		true,
		false,
		10
	);

	$foundation_footer_settings = array(
		wptouch_add_setting(
			'textarea',
			'custom_footer_message',
			__( 'Custom footer content', 'wptouch-pro' ),
			__( 'HTML is allowed', 'wptouch-pro' ),
			WPTOUCH_SETTING_BASIC,
			'1.0'
		),
	);

	/**
	 * Filters the customizer settings for the footer section.
	 *
	 * @since 4.3.17
	 *
	 * @param array $foundation_footer_settings The footer settings.
	 */
	$foundation_footer_settings = apply_filters( 'foundation_settings_footer', $foundation_footer_settings );

	wptouch_add_page_section(
		FOUNDATION_PAGE_BRANDING,
		__( 'Footer', 'wptouch-pro' ),
		'foundation-custom-content',
		$foundation_footer_settings,
		$page_options,
		FOUNDATION_SETTING_DOMAIN,
		true,
		false,
		70
	);

	wptouch_add_page_section(
		FOUNDATION_PAGE_BRANDING,
		__( 'Custom CSS', 'wptouch-pro' ),
		'foundation-custom-css-declarations',
		array(
			wptouch_add_setting(
				'textarea',
				'custom_css_declarations',
				__( 'Custom CSS Declarations', 'wptouch-pro' ),
				false,
				WPTOUCH_SETTING_BASIC,
				'4.0'
			),
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN,
		true,
		false,
		80
	);

	return $page_options;
}

add_action( 'wptouch_customizer_start_setup', 'foundation_recover_images' );
function foundation_recover_images() {
	wptouch_customizer_port_image( 'wptouch_iphone_icon_retina', 'iphone_icon_retina' );
	wptouch_customizer_port_image( 'wptouch_logo_image', 'logo_image' );
}

function foundation_maybe_output_homescreen_icon( $image, $width, $height, $pixel_ratio = 1 ) {
	$settings = foundation_get_settings();

	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		$use_wordpress_icon = true;
	} else {
		$use_wordpress_icon = false;
	}

	if ( $image && ! $use_wordpress_icon ) {
		if ( $width != 57 ) {
			$size_string = ' sizes="' . $width . 'x' . $height . '"';
		} else {
			$size_string = '';
		}

		echo '<link rel="apple-touch-icon-precomposed" ' . esc_html( $size_string ) . ' href="' . esc_url( foundation_prepare_uploaded_file_url( $image ) ) . '" />' . "\n";
	}
}

function foundation_setup_homescreen_icons() {
	$settings = foundation_get_settings();
	$has_icon = $settings->ipad_icon_retina;

	if ( wptouch_is_device_real_ipad() ) {
		// iPad home screen icons
		foundation_maybe_output_homescreen_icon( $settings->ipad_icon_retina, 152, 152, 2 );
		foundation_maybe_output_homescreen_icon( $settings->ipad_icon_retina, 144, 144, 2 );
		foundation_maybe_output_homescreen_icon( $settings->ipad_icon_retina, 57, 57, 1 );

		// Default (if no icon added in admin, or icon isn't formatted correctly, and as a catch-all)
		echo '<link rel="apple-touch-icon-precomposed" href="' . esc_url( WPTOUCH_DEFAULT_HOMESCREEN_ICON ) . '" />' . "\n";
	} else {
		// iPhone / Android home screen icons
		foundation_maybe_output_homescreen_icon( $settings->iphone_icon_retina, 192, 192, 2 );
		foundation_maybe_output_homescreen_icon( $settings->iphone_icon_retina, 180, 180, 2 );
		foundation_maybe_output_homescreen_icon( $settings->iphone_icon_retina, 120, 120, 2 );
		foundation_maybe_output_homescreen_icon( $settings->iphone_icon_retina, 114, 114, 2 );
		foundation_maybe_output_homescreen_icon( $settings->iphone_icon_retina, 57, 57, 1 );

		// Default (if no icon added in admin, or icon isn't formatted correctly, and as a catch-all)
		if ( ! $has_icon ) {
			echo '<link rel="apple-touch-icon-precomposed" href="' . esc_url( WPTOUCH_DEFAULT_HOMESCREEN_ICON ) . '" />' . "\n";
		}
	}
}

function foundation_setup_smart_app_banner() {
	$settings = foundation_get_settings();
	$appID    = $settings->smart_app_banner;
	if ( $appID ) {
		echo '<meta name="apple-itunes-app" content="app-id=' . esc_attr( $appID ) . '" />' . "\n";
	}
}

// Child Theme Functions

global $foundation_data;

add_action( 'init', 'foundation_signal_module_init' );
add_action( 'wptouch_root_functions_loaded', 'foundation_theme_init' );

function foundation_signal_module_init() {
	// Themes will tie into this to add theme support
	do_action( 'foundation_module_init' );
	do_action( 'foundation_enqueue_scripts' );

	if ( wptouch_is_showing_mobile_theme_on_mobile_device() ) {
		do_action( 'foundation_module_init_mobile' );
		do_action( 'foundation_enqueue_scripts_mobile' );
		do_action( 'foundation_enqueue_color_scripts' );
	}
}

function foundation_init_data() {
	global $foundation_data;

	$foundation_data = new stdClass;

	// The base module is always loaded; don't change this or horrible things will happen!
	$foundation_data->theme_support = array( 'base' );
}

function foundation_get_theme_data() {
	global $foundation_data;

	return $foundation_data;
}

function foundation_set_theme_data( $theme_data ) {
	global $foundation_data;

	$foundation_data = $theme_data;
}

function foundation_load_theme_modules() {
	$settings = foundation_get_settings();

	$theme_data = foundation_get_theme_data();

	/**
	 * Filters the theme support setting.
	 *
	 * @param array $theme_data ->theme_support The theme support data.
	 */
	$theme_data->theme_support = apply_filters( 'wptouch_theme_support', $theme_data->theme_support );

	if ( count( $theme_data->theme_support ) ) {
		foreach ( $theme_data->theme_support as $module ) {

			/**
			 * Dynamically filters the allowed module support based on the $module name.
			 *
			 * @param bool Whether the module is allowed.
			 */
			$allow_module_load = apply_filters( 'wptouch_allow_module_' . $module, true );
			if ( ! $allow_module_load ) {
				continue;
			}

			$bootstrap_file = dirname( __FILE__ ) . '/modules/' . $module . '/' . $module . '.php';
			$defined_name   = 'WPTOUCH_MODULE_' . str_replace( '-', '_', strtoupper( $module ) ) . '_INSTALLED';

			if ( file_exists( $bootstrap_file ) ) {
				// Load the main bootstrap file
				require_once( $bootstrap_file );

				define( $defined_name, '1' );
			}

			if ( ! defined( 'WPTOUCH_IS_FREE' ) ) {
				// Pro version
				$alternate_location = WPTOUCH_DIR . '/pro/modules/' . $module . '/' . $module . '.php';

				if ( file_exists( $alternate_location ) ) {
					require_once( $alternate_location );

					if ( ! defined( $defined_name ) ) {
						define( $defined_name, '1' );
					}
				}
			}
		}

		// Force settings to be reloaded
		global $wptouch_pro;
		$wptouch_pro->invalidate_settings();
	}
}

function foundation_theme_init() {
	foundation_init_data();

	do_action( 'foundation_init' );

	foundation_load_theme_modules();

	// Actions that happen immediately after the modules are loaded
	do_action( 'foundation_modules_loaded' );
	if ( wptouch_is_showing_mobile_theme_on_mobile_device() ) {
		do_action( 'foundation_modules_loaded_mobile' );
	}
}

function foundation_add_theme_support( $theme_support ) {
	$theme_data = foundation_get_theme_data();

	if ( is_array( $theme_support ) ) {
		foreach ( $theme_support as $module ) {
			if ( ! in_array( $module, $theme_data->theme_support ) ) {
				$theme_data->theme_support[] = $module;
			}
		}
	} else {
		if ( ! in_array( $theme_support, $theme_data->theme_support ) ) {
			$theme_data->theme_support[] = $theme_support;
		}
	}
}

function foundation_body_classes( $classes ) {
	global $wptouch_pro;
	$global_settings = $wptouch_pro->get_settings();

	$settings = foundation_get_settings();

	if ( $settings->new_video_handling != false ) {
		$classes[] = 'css-videos';
	}

	if ( $settings->typography_sets != 'default' ) {
		$classes[] = 'body-font';
	}

	if ( isset( $_COOKIE['wptouch-device-type'] ) ) {
		if ( $_COOKIE['wptouch-device-type'] == 'smartphone' ) {
			$classes[] = 'smartphone';
		} else if ( $_COOKIE['wptouch-device-type'] == 'tablet' ) {
			$classes[] = 'tablet';
		}
	}

	if ( isset( $_COOKIE['wptouch-device-orientation'] ) ) {
		if ( $_COOKIE['wptouch-device-orientation'] == 'portrait' ) {
			$classes[] = 'portrait';
		} else if ( $_COOKIE['wptouch-device-orientation'] == 'landscape' ) {
			$classes[] = 'landscape';
		}
	}

	// iOS Device
	if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'iPhone' ) || strpos( $_SERVER['HTTP_USER_AGENT'], 'iPod' ) || strpos( $_SERVER['HTTP_USER_AGENT'], 'iPad' ) ) {
		$classes[] = 'ios';
	}

	// Android Device
	if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Android' ) ) {
		$classes[] = 'android';
	}

	if ( wptouch_should_load_rtl() ) {
		$classes[] = 'rtl';
	}

	// iOS 7 or higher now
	$classes[] = 'ios7';

	$classes[] = 'theme-' . $global_settings->current_theme_name;

	return $classes;
}

function foundation_get_base_url() {
	return WPTOUCH_URL . '/themes/foundation';
}

function foundation_get_base_module_url() {
	return WPTOUCH_URL . '/themes/foundation/modules';
}

global $foundation_registered_colors;
$foundation_registered_colors = array();

function foundation_register_theme_color( $setting_name, $desc, $fg_selectors, $bg_selectors, $domain = FOUNDATION_SETTING_DOMAIN, $live_preview = false, $luma_threshold = false, $luma_class = false ) {
	$theme_color = new stdClass;

	$theme_color->setting        = $setting_name;
	$theme_color->desc           = $desc;
	$theme_color->fg_selectors   = $fg_selectors;
	$theme_color->bg_selectors   = $bg_selectors;
	$theme_color->domain         = $domain;
	$theme_color->luma_threshold = $luma_threshold;
	$theme_color->luma_class     = $luma_class;
	$theme_color->live_preview   = $live_preview;

	global $foundation_registered_colors;
	$foundation_registered_colors[ $setting_name ] = $theme_color;
}

function foundation_has_theme_colors() {
	global $foundation_registered_colors;

	return count( $foundation_registered_colors );
}

function foundation_get_theme_colors() {
	global $foundation_registered_colors;

	return $foundation_registered_colors;
}

/////* Foundation Functions (can be used by all child themes) */////

/* If there are more comments than the pagination setting, we know we should show the pagination links */
function wptouch_fdn_comments_pagination() {
	if ( get_option( 'comments_per_page' ) < wptouch_get_comment_count() ) {
		return true;
	} else {
		return false;
	}
}

/* Previous + Next Post Functions For Single Post Pages */
function wptouch_fdn_get_previous_post_link() {
	$excluded  = wptouch_fdn_convert_catname_to_id();
	$prev_post = get_adjacent_post( false, $excluded, true );
	echo esc_url( get_permalink( $prev_post->ID ) );
}

function wptouch_fdn_get_next_post_link() {
	$excluded  = wptouch_fdn_convert_catname_to_id();
	$next_post = get_adjacent_post( false, $excluded, false );
	echo esc_url( get_permalink( $next_post->ID ) );
}

function wptouch_fdn_get_previous_post_link_w_title() {
	$excluded  = wptouch_fdn_convert_catname_to_id();
	$prev_post = get_adjacent_post( false, $excluded, true );
	echo '<a class="prev-post" href="' . esc_url( get_permalink( $prev_post->ID ) ) . '">' . $prev_post->post_title . '</a>';
}

function wptouch_fdn_get_next_post_link_w_title() {
	$excluded  = wptouch_fdn_convert_catname_to_id();
	$next_post = get_adjacent_post( false, $excluded, false );
	echo '<a class="next-post" href="' . esc_url( get_permalink( $next_post->ID ) ) . '">' . $next_post->post_title . '</a>';
}

function wptouch_fdn_if_next_post_link() {
	$excluded  = wptouch_fdn_convert_catname_to_id();
	$next_post = get_adjacent_post( false, $excluded, false );

	if ( $next_post ) {
		return true;
	} else {
		return false;
	}
}

function wptouch_fdn_if_previous_post_link() {
	$excluded  = wptouch_fdn_convert_catname_to_id();
	$prev_post = get_adjacent_post( false, $excluded, true );

	if ( $prev_post ) {
		return true;
	} else {
		return false;
	}
}

// Dynamic archives heading text for archive result pages, and search
function wptouch_fdn_archive_title_text() {
	global $wp_query;
	$total_results = $wp_query->found_posts;

	if ( ! ( is_home() || is_single() ) ) {
		echo '<div class="archive-text">';
	}
	if ( is_search() ) {
		echo intval( $total_results ) . '&nbsp;';
		echo sprintf( __( "search results for '%s'", "wptouch-pro" ), get_search_query() );
	}
	if ( is_category() ) {
		echo sprintf( __( "%sCategories &rsaquo;%s %s", "wptouch-pro" ), '<span class="type">', '</span>', esc_html( single_cat_title( "", false ) ) );
	} elseif ( is_tag() ) {
		echo sprintf( __( "Tags &rsaquo; %s", "wptouch-pro" ), esc_html( single_tag_title( " ", false ) ) );
	} elseif ( is_day() ) {
		echo sprintf( __( "Archives &rsaquo; %s", "wptouch-pro" ), esc_html( get_the_time( 'F jS, Y' ) ) );
	} elseif ( is_month() ) {
		echo sprintf( __( "Archives &rsaquo; %s", "wptouch-pro" ), esc_html( get_the_time( 'F, Y' ) ) );
	} elseif ( is_year() ) {
		echo sprintf( __( "Archives &rsaquo; %s", "wptouch-pro" ), esc_html( get_the_time( 'Y' ) ) );
	} elseif ( get_post_type() ) {
	}
	if ( ! ( is_home() || is_single() ) ) {
		echo '</div>';
	}
}

// Dynamic archives Load More text
function wptouch_fdn_archive_load_more_text() {
	global $wp_query;
	$total_results = $wp_query->found_posts;

	if ( is_category() ) {
		echo( __( 'Load more from this category', 'wptouch-pro' ) );
	} elseif ( is_tag() ) {
		echo( __( 'Load more tagged like this', 'wptouch-pro' ) );
	} elseif ( is_day() ) {
		echo( __( 'Load more from this day', 'wptouch-pro' ) );
	} elseif ( is_month() ) {
		echo( __( 'Load more from this month', 'wptouch-pro' ) );
	} elseif ( is_year() ) {
		echo( __( 'Load more from this year', 'wptouch-pro' ) );
	} elseif ( get_post_type() == 1 ) {
		echo( __( 'Load more in this section', 'wptouch-pro' ) );
	} else {
		echo( __( 'Load more entries', 'wptouch-pro' ) );
	}
}

function wptouch_fdn_ordered_cat_list( $num, $include_count = true, $taxonomy = 'category', $opening_tag = '<ul>', $closing_tag = '</ul>' ) {
	global $wpdb;

	$settings = wptouch_get_settings( 'foundation' );

	$excluded_cats = 0;

	if ( $settings->excluded_categories ) {
		$new_cats = _foundation_explode_and_trim_taxonomy( $settings->excluded_categories, 'category' );

		if ( is_array( $new_cats ) && count( $new_cats ) ) {
			$excluded_cats = implode( ',', $new_cats );
		}
	}


	echo $opening_tag;
	$sql = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}term_taxonomy INNER JOIN {$wpdb->prefix}terms ON {$wpdb->prefix}term_taxonomy.term_id = {$wpdb->prefix}terms.term_id WHERE taxonomy = '{$taxonomy}' AND {$wpdb->prefix}term_taxonomy.term_id NOT IN ($excluded_cats) AND count >= 1 ORDER BY count DESC LIMIT 0, $num" );

	if ( $sql ) {

		/**
		 * Filters the SQL to allow categories list.
		 *
		 * @param array $sql The $sql results.
		 */
		$sql = apply_filters( 'wptouch_ordered_cat_list_categories', $sql );

		foreach ( $sql as $result ) {
			if ( $result ) {
				$link = get_term_link( (int) $result->term_id, $taxonomy );

				if ( is_wp_error( $link ) ) {
					continue;
				}

				echo "<li><a href=\"" . esc_url( $link ) . "\">" . $result->name;

				if ( $include_count ) {
					echo " <span>(" . intval( $result->count ) . ")</span></a>";
				}

				echo '</a>';
				echo '</li>';
			}
		}
	}
	echo $closing_tag;
}

function wptouch_fdn_hierarchical_cat_list( $num, $include_count = true, $taxonomy = 'category', $opening_tag = '<ul>', $closing_tag = '</ul>' ) {
	$walker   = new WPtouchProMainNavMenuWalker;
	$defaults = array(
		'number'             => $num,
		'show_option_all'    => false,
		'show_option_none'   => false,
		'orderby'            => 'name',
		'order'              => 'ASC',
		'style'              => 'list',
		'show_count'         => $include_count,
		'hide_empty'         => 1,
		'use_desc_for_title' => 1,
		'child_of'           => 0,
		'feed'               => '',
		'feed_type'          => '',
		'feed_image'         => '',
		'exclude'            => '',
		'exclude_tree'       => '',
		'current_category'   => 0,
		'hierarchical'       => true,
		'title_li'           => false,
		'echo'               => 1,
		'depth'              => 0,
		'taxonomy'           => $taxonomy,
		'walker'             => new WPtouchProCategoryWalker,
	);

	if ( isset( $args ) ) {
		$r = wp_parse_args( $args, $defaults );
	} else {
		$r = $defaults;
	}

	if ( ! isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] ) {
		$r['pad_counts'] = true;
	}

	if ( true == $r['hierarchical'] ) {
		$r['exclude_tree'] = $r['exclude'];
		$r['exclude']      = '';
	}

	if ( ! isset( $r['class'] ) ) {
		$r['class'] = ( 'category' == $r['taxonomy'] ) ? 'categories' : $r['taxonomy'];
	}

	if ( ! taxonomy_exists( $r['taxonomy'] ) ) {
		return false;
	}

	$show_option_all  = $r['show_option_all'];
	$show_option_none = $r['show_option_none'];

	$categories = get_categories( $r );

	$output = '';

	if ( empty( $categories ) ) {
		if ( ! empty( $show_option_none ) ) {
			if ( 'list' == $r['style'] ) {
				$output .= '<li class="cat-item-none">' . $show_option_none . '</li>';
			} else {
				$output .= $show_option_none;
			}
		}
	} else {
		$output = $opening_tag;

		if ( ! empty( $show_option_all ) ) {
			$posts_page = ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );
			$posts_page = esc_url( $posts_page );
			if ( 'list' == $r['style'] ) {
				$output .= '<li class="cat-item-all"><a href="$posts_page">$show_option_all</a></li>';
			} else {
				$output .= "<a href='$posts_page'>$show_option_all</a>";
			}
		}

		if ( empty( $r['current_category'] ) && ( is_category() || is_tax() || is_tag() ) ) {
			$current_term_object = get_queried_object();
			if ( $current_term_object && $r['taxonomy'] === $current_term_object->taxonomy ) {
				$r['current_category'] = get_queried_object_id();
			}
		}

		if ( $r['hierarchical'] ) {
			$depth = $r['depth'];
		} else {
			$depth = - 1; // Flat.
		}
		$output .= walk_category_tree( $categories, $depth, $r );

		$output .= '</ul>';
	}

	/**
	 * Filter the HTML output of a taxonomy list.
	 *
	 * @since 2.1.0
	 *
	 * @param string $output HTML output.
	 * @param array  $args   An array of taxonomy-listing arguments.
	 */
	$html = apply_filters( 'wp_list_categories', $output, $r );

	if ( $r['echo'] ) {
		echo wp_kses_post( $html );
	} else {
		return $html;
	}
}

function wptouch_fdn_ordered_tag_list( $num ) {
	global $wpdb;

	$settings = wptouch_get_settings( 'foundation' );

	$excluded_tags = 0;

	if ( $settings->excluded_tags ) {
		$new_tags = _foundation_explode_and_trim_taxonomy( $settings->excluded_tags, 'post_tag' );

		if ( is_array( $new_tags ) && count( $new_tags ) ) {
			$excluded_tags = implode( ',', $new_tags );
		}
	}

	echo '<ul>';

	$sql = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}term_taxonomy INNER JOIN {$wpdb->prefix}terms ON {$wpdb->prefix}term_taxonomy.term_id = {$wpdb->prefix}terms.term_id WHERE taxonomy = 'post_tag' AND {$wpdb->prefix}term_taxonomy.term_id NOT IN ($excluded_tags) AND count >= 1 ORDER BY count DESC LIMIT 0, $num" );

	if ( $sql ) {
		foreach ( $sql as $result ) {
			if ( $result ) {
				echo "<li><a href=\"" . esc_url( get_tag_link( $result->term_id ) ) . "\">" . esc_url( $result->name ) . " <span>(" . intval( $result->count ) . ")</span></a></li>";
			}
		}
	}
	echo '</ul>';
}

function wptouch_fdn_display_comment( $comment, $args, $depth ) {
	$GLOBALS['comment']       = $comment;
	$GLOBALS['comment_args']  = $args;
	$GLOBALS['comment_depth'] = $depth;
	extract( $args, EXTR_SKIP );

	locate_template( 'one-comment.php', true, false );
}

function wptouch_fdn_get_search_post_types() {

	/**
	 * Filters the theme's search post types.
	 *
	 * @param array The searchable post types.
	 */
	return apply_filters( 'foundation_search_post_types', array( 'post', 'page' ) );
}

function wptouch_fdn_convert_catname_to_id() {
	$settings = foundation_get_settings();
	$cats     = $settings->excluded_categories;

	if ( $cats ) {
		$cat_ids        = explode( ',', $cats );
		$new_cats_by_id = array();

		foreach ( $cat_ids as $cat ) {
			$trimmed_cat      = trim( $cat );
			$new_cats_by_id[] = get_cat_ID( $trimmed_cat );
		}

		$new_cats_by_id_list = implode( ',', $new_cats_by_id );

		return $new_cats_by_id_list;
	} else {
		return false;
	}
}

function wptouch_fdn_convert_tagname_to_id() {
	$settings = foundation_get_settings();
	$tags     = $settings->excluded_tags;

	if ( $tags ) {
		$tag_ids        = explode( ',', $tags );
		$new_tags_by_id = array();

		foreach ( $tag_ids as $tag ) {
			$trimmed_tag      = trim( $tag );
			$tagname          = get_term_by( 'name', $trimmed_tag, 'post_tag' );
			$tagid            = $tagname->term_id;
			$new_tags_by_id[] = $tagid;
		}

		$new_tags_by_id_list = implode( ',', $new_tags_by_id );

		return $new_tags_by_id_list;
	} else {
		return false;
	}
}

function wptouch_fdn_get_search_post_type() {
	global $search_post_type;

	switch ( $search_post_type ) {
		case 'post':
			return __( 'Post', 'wptouch-pro' );
		case 'page':
			return __( 'Page', 'wptouch-pro' );
		default:
			/**
			 * Filters the theme search post type name.
			 *
			 * @param string $search_post_type The search post type.
			 */
			return apply_filters( 'wptouch_foundation_search_post_type_text', $search_post_type );
	}
}

function _foundation_explode_and_trim_taxonomy( $tax, $tax_type ) {
	$cats     = explode( ',', $tax );
	$new_cats = array();

	foreach ( $cats as $cat ) {
		$trimmed_cat = trim( $cat );
		if ( is_numeric( $trimmed_cat ) ) {
			$new_cats[] = $trimmed_cat;
		} else {
			$term_data = get_term_by( 'name', $trimmed_cat, $tax_type );
			if ( $term_data ) {
				$new_cats[] = $term_data->term_id;
			}
		}
	}

	return $new_cats;
}

function foundation_exclude_categories_tags( $query ) {
	if ( wptouch_is_mobile_theme_showing() ) {
		$settings = foundation_get_settings();

		if ( $settings->excluded_categories ) {
			$new_cats = _foundation_explode_and_trim_taxonomy( $settings->excluded_categories, 'category' );

			if ( ! $query->is_single() ) {
				$query->set( 'category__not_in', $new_cats );
			}
		}

		if ( $settings->excluded_tags ) {
			$new_tags = _foundation_explode_and_trim_taxonomy( $settings->excluded_tags, 'post_tag' );

			if ( ! $query->is_single() ) {
				$query->set( 'tag__not_in', $new_tags );
			}
		}
	}

	return $query;
}

function foundation_insert_multipage_links( $content ) {
	$multipage_links = wp_link_pages( 'before=<div class="wp-page-nav">' . __( 'Pages', 'wptouch-pro' ) . ':&after=</div>&echo=0' );
	if ( ! is_feed() && ! is_home() ) {
		return $content . $multipage_links;
	} else {
		return $content;
	}
}

function foundation_number_of_posts_to_show() {
	$settings  = wptouch_get_settings( 'foundation' );
	$num_posts = $settings->posts_per_page;

	return $num_posts;
}


function foundation_get_base_module_dir() {
	return WPTOUCH_DIR . '/themes/foundation/modules';
}

function foundation_inline_styles() {
	/**
	 * Filters the theme's inline style.
	 *
	 * @param string Empty string.
	 */
	$style_data = apply_filters( 'foundation_inline_style', '' );
	if ( strlen( $style_data ) ) {
		echo "\n<!-- Foundation Styles -->\n";
		echo "<style type='text/css'>\n" . $style_data . "</style>\n";
	}
}

function foundation_custom_die_handler( $function ) {
	$error_template = FOUNDATION_DIR . '/default/formerror.php';

	if ( ! is_admin() && file_exists( $error_template ) ) {
		require_once( $error_template );
		die();
	}

	return $function;
}

function foundation_add_meta_theme_color() {

	/**
	 * Filters the theme color.
	 *
	 * @param string Empty string.
	 */
	$header_color = apply_filters( 'wptouch_theme_color', '' );

	if ( strlen( $header_color ) ) {
		echo '<meta name="theme-color" content="' . esc_html( $header_color ) . '">';
	}

}

function foundation_override_jetpack_check_mobile() {
	return false;
}

/**
 * Sanitize settings data for XSS before DB save/update.
 *
 * @param object $settings The WPtouch settings.
 *
 * @return object
 */
function foundation_sanitize_wptouch_settings( $settings ) {
	if ( ! empty( $settings->homepage_redirect_custom_target ) ) {
		$settings->homepage_redirect_custom_target = esc_url_raw( $settings->homepage_redirect_custom_target );
	}

	if ( ! empty( $settings->site_title ) ) {
		$settings->site_title = sanitize_text_field( $settings->site_title );
	}

	return $settings;
}
