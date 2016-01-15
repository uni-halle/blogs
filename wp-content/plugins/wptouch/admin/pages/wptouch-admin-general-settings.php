<?php
if ( !defined( 'FOUNDATION_SETTING_DOMAIN' ) ) {
    define( 'FOUNDATION_SETTING_DOMAIN', 'foundation' );
}
if ( !wptouch_is_controlled_network() || ( wptouch_is_controlled_network() && is_network_admin() ) ) {
	add_filter( 'wptouch_admin_page_render_wptouch-admin-general-settings', 'wptouch_render_updates_page' );
}
if ( is_network_admin() ) {
	add_filter( 'wptouch_admin_page_render_wptouch-admin-general-settings', 'wptouch_render_multisite_page' );
}
add_filter( 'wptouch_admin_page_render_wptouch-admin-general-settings', 'wptouch_render_general_page' );
add_filter( 'wptouch_admin_page_render_wptouch-admin-general-settings', 'wptouch_render_compat_page' );
add_filter( 'wptouch_admin_page_render_wptouch-admin-general-settings', 'wptouch_render_devices_page' );
add_filter( 'wptouch_admin_page_render_wptouch-admin-general-settings', 'wptouch_render_menu_page' );
add_filter( 'wptouch_admin_page_render_wptouch-admin-general-settings', 'wptouch_render_themes' );
add_filter( 'wptouch_admin_page_render_wptouch-admin-general-settings', 'wptouch_render_theme_customize_page' );
add_filter( 'wptouch_admin_page_render_wptouch-admin-general-settings', 'wptouch_render_theme_gopro_page' );
add_filter( 'wptouch_admin_page_render_wptouch-admin-general-settings', 'wptouch_render_addons' );
add_filter( 'wptouch_admin_page_render_wptouch-admin-general-settings', 'wptouch_render_addon_settings' );

function wptouch_render_multisite_page( $page_options ) {
	global $wptouch_pro;

	$settings = wptouch_get_settings();

		wptouch_add_sub_page( WPTOUCH_ADMIN_MULTISITE_SETUP, 'multisite-network-admin', $page_options );

		wptouch_add_page_section(
			WPTOUCH_ADMIN_MULTISITE_SETUP,
			__( 'Multisite Setup', 'wptouch-pro' ),
			'mutisite-setup',
			array(
				wptouch_add_setting(
					'list',
					'force_network_locale',
					__( 'Admin Language', 'wptouch-pro' ),
					false,
					WPTOUCH_SETTING_BASIC,
					'3.0',
					wptouch_admin_get_languages(),
					'wptouch_pro'
				),
				wptouch_add_setting(
					'checkbox',
					'multisite_control',
					__( 'Control themes and extension downloads and updates', 'wptouch-pro' ),
					__( 'If enabled, sub-sites can only activate/deactivate themes and extensions that have been downloaded in the network admin.', 'wptouch-pro' ),
					WPTOUCH_SETTING_BASIC,
					'4.0',
					false,
					'bncid'
				)
			),
			$page_options
		);

	return $page_options;
}

function wptouch_render_updates_page( $page_options ) {
	global $wptouch_pro;

	if ( $wptouch_pro->theme_upgrades_available() || $wptouch_pro->extension_upgrades_available() ) {
		$settings = wptouch_get_settings();

		wptouch_add_sub_page( WPTOUCH_ADMIN_UPDATES_AVAILABLE, 'updates-available', $page_options );

		wptouch_add_page_section(
			WPTOUCH_ADMIN_UPDATES_AVAILABLE,
			__( 'Updates Available', 'wptouch-pro' ),
			'updates-available',
			array(
				wptouch_add_setting(
					'updates-available',
					'theme-extension-updates-available',
					false,
					false,
					WPTOUCH_SETTING_BASIC,
					'4.0'
				)
			),
			$page_options
		);
	}

	return $page_options;
}

function wptouch_render_general_page( $page_options ) {
	$settings = wptouch_get_settings();

	wptouch_add_sub_page( WPTOUCH_ADMIN_SETUP_GENERAL, 'setup-general-general', $page_options );

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Mobile Site Title', 'wptouch-pro' ),
		'mobile-site-title',
		array(
			wptouch_add_setting(
				'text',
				'site_title',
				wptouchize_it( __( 'WPtouch Pro site title', 'wptouch-pro' ) ),
				false,
				WPTOUCH_SETTING_BASIC,
				'3.0'
			),
		),
		$page_options,
		'wptouch_pro',
		false,
		false
	);

	if ( defined( 'WPTOUCH_IS_FREE' ) ){
		$display_text = wptouchize_it( __( 'If disabled WPtouch Pro will be off for visitors but can be configured.' , 'wptouch-pro' ) );
	} else {
		$display_text = wptouchize_it( __( 'If disabled WPtouch Pro will be off for visitors but can be configured in the Customizer.' , 'wptouch-pro' ) );
	}

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Display', 'wptouch-pro' ),
		'filtered-urls-compatibility',
		array(
			wptouch_add_setting(
				'checkbox',
				'new_display_mode',
				wptouchize_it( __( 'Display WPtouch Pro for mobile visitors', 'wptouch-pro' ) ),
				$display_text,
				WPTOUCH_SETTING_BASIC,
				'3.1'
			),
			wptouch_add_pro_setting(
				'list',
				'url_filter_behaviour',
				__( 'URL filtering' , 'wptouch-pro' ),
				false,
				WPTOUCH_SETTING_BASIC,
				'3.5.3',
				array(
					'disabled' => wptouchize_it( __( 'Show WPtouch Pro for all URLs' , 'wptouch-pro' ) ),
					'exclude_urls' => wptouchize_it( __( 'Exclude WPtouch Pro on these URLs', 'wptouch-pro' ) ),
					'exclusive_urls' => wptouchize_it( __( 'Only show WPtouch Pro on these URLs', 'wptouch-pro' ) )
				)
			),
			wptouch_add_pro_setting(
				'multiline-newline',
				'filtered_urls',
				__( 'Apply filter to these URLs/Pages', 'wptouch-pro' ),
				'e.g. "/about", "/products/store"',
				WPTOUCH_SETTING_BASIC,
				'3.5.3'
			),
			wptouch_add_pro_setting(
				'checkbox',
				'filtered_urls_exact',
				__( 'Require exact match', 'wptouch-pro' ),
				false,
				WPTOUCH_SETTING_BASIC,
				'3.5.3'
			)
		),
		$page_options
	);

	$landing_settings = array(
		wptouch_add_pro_setting(
			'list',
			'homepage_landing',
			__( 'Mobile front page', 'wptouch-pro' ),
			wptouchize_it( __( 'You can set a different front page for WPtouch Pro visitors.', 'wptouch-pro' ) ),
			WPTOUCH_SETTING_BASIC,
			'3.0',
			array(
				'none' => __( 'WordPress Reading Settings', 'wptouch-pro' ),
				'select' => __( 'Redirect to a page', 'wptouch-pro' ),
				'custom' => _x( 'Redirect to a custom URL', 'Refers to a custom landing page', 'wptouch-pro' )
			)
		),
		wptouch_add_pro_setting(
			'text',
			'homepage_redirect_custom_target',
			__( 'Custom Slug or URL', 'wptouch-pro' ),
			__( 'Enter a Slug (i.e. "/home") or a full URL path', 'wptouch-pro' ),
			WPTOUCH_SETTING_BASIC,
			'3.0'
		),
		wptouch_add_pro_setting(
			'redirect',
			'homepage_redirect_wp_target',
			false,
			false,
			WPTOUCH_SETTING_BASIC,
			'3.0'
		)
	);

	$landing_settings = apply_filters( 'foundation_settings_pages', $landing_settings );

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Landing Pages', 'wptouch-pro' ),
		'setup-landing-page',
		$landing_settings,
		$page_options,
		'wptouch_pro'
	);

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Desktop / Mobile Switching', 'wptouch-pro' ),
		'setup-general',
		array(
			wptouch_add_pro_setting(
				'checkbox',
				'show_switch_link',
				_x( 'Theme switch toggle', 'switches between desktop and mobile themes', 'wptouch-pro' ),
				false,
				WPTOUCH_SETTING_BASIC,
				'4.0'
			),
		),
		$page_options,
		'wptouch-pro',
		false,
		__( 'Shows a toggle in both the desktop mobile theme footers allowing users to switch between them.', 'wptouch-pro' )
	);

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Page Zoom', 'wptouch-pro' ),
		'foundation-zoom',
			array(
				wptouch_add_setting(
					'checkbox',
					'allow_zoom',
					__( 'Allow mobile browser zooming', 'wptouch-pro' ),
					wptouchize_it( __( 'By default WPtouch Pro disables browser zooming.' ) ),
					WPTOUCH_SETTING_BASIC,
					'2.0'
				)
			),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Smart App Banner', 'wptouch-pro' ),
		'foundation-smart-app-banner',
		array(
			wptouch_add_pro_setting(
				'text',
				'smart_app_banner',
				'App Store ID',
				false,
				WPTOUCH_SETTING_BASIC,
				'2.0'
			),
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN,
		false,
		sprintf( __( 'Find your ID from the %siTunes Link Maker%s.', 'wptouch-pro' ), '<a href="http://itunes.apple.com/linkmaker/" target="_blank">', '</a>' )
	);

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Analytics', 'wptouch-pro' ),
		'setup-custom-code',
		array(
			wptouch_add_pro_setting(
				'list',
				'analytics_embed_method',
				__( 'Analytics Code', 'wptouch-pro' ),
				false,
				WPTOUCH_SETTING_BASIC,
				'4.0',
				array(
					'disabled' => __( 'None', 'wptouch-pro' ),
					'simple' => 'Google Analytics',
					'custom' => __( 'Custom', 'wptouch-pro' )
				)
			),

			wptouch_add_pro_setting(
				'text',
				'analytics_google_id',
				__( 'Site ID', 'wptouch-pro' ),
				false,
				WPTOUCH_SETTING_BASIC,
				'3.0'
			),

			wptouch_add_pro_setting(
				'textarea',
				'custom_stats_code',
				false,
				false,
				WPTOUCH_SETTING_BASIC,
				'3.0'
			)
		),
		$page_options,
		'wptouch_pro'
	);

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		wptouchize_it( __( 'WPtouch Pro Love', 'wptouch-pro' ) ),
		'setup-powered-by',
		array(
			wptouch_add_setting(
				'checkbox',
				'show_wptouch_in_footer',
				wptouchize_it( __( 'Show powered by WPtouch Pro link in theme footer', 'wptouch-pro' ) ),
				false,
				WPTOUCH_SETTING_BASIC,
				'3.0'
			)
		),
		$page_options,
		'wptouch_pro'
	);

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Language', 'wptouch-pro' ),
		'setup-regionalization',
		array(
			wptouch_add_setting(
				'list',
				'force_locale',
				__( 'Theme Language', 'wptouch-pro' ),
				false,
				WPTOUCH_SETTING_BASIC,
				'3.0',
				wptouch_admin_get_languages()
			),
			wptouch_add_setting(
				'checkbox',
				'translate_admin',
				__( 'Also applies to admin', 'wptouch-pro' ),
				false,
				WPTOUCH_SETTING_BASIC,
				'3.0'
			)
		),
		$page_options,
		'wptouch_pro'
	);


	return $page_options;
}

function wptouch_render_compat_page( $page_options ) {
	wptouch_add_sub_page( WPTOUCH_ADMIN_SETUP_COMPAT, 'setup-compat', $page_options );

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_COMPAT,
		_x( 'Shortcodes', 'shortcodes are pieces of code [like_this] that users can drop into textareas that will convert to longer pieces of code', 'wptouch-pro' ),
		'shortcodes-compatibility',
		array(
			wptouch_add_pro_setting(
				'checkbox',
				'process_desktop_shortcodes',
				__( 'Process desktop theme shortcodes', 'wptouch-pro' ),
				false,
				WPTOUCH_SETTING_BASIC,
				'3.0'
			),
			wptouch_add_pro_setting(
				'multiline-comma',
				'remove_shortcodes',
				__( 'Filter out shortcodes', 'wptouch-pro' ),
				wptouchize_it( __( 'Filters out shortcodes from displaying when WPtouch Pro is active.', 'wptouch-pro' ) ),
				WPTOUCH_SETTING_BASIC,
				'3.0'
			)
		),
		$page_options,
		'wptouch_pro'
	);

	$page_options = apply_filters( 'wptouch_settings_compat', $page_options );

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_COMPAT,
		__( 'Active Plugins', 'wptouch-pro' ),
		'setup-general-plugin-compat',
		array(
			wptouch_add_pro_setting(
				'custom',
				'plugin-compat'
			)
		),
		$page_options,
		'wptouch_pro',
		false,
		wptouchize_it( __( 'Attempts to disable plugins for mobile visitors. Some plugins don‘t support this feature due to the way they load in WordPress.', 'wptouch-pro' ) )
	);

	return $page_options;
}

function wptouch_render_devices_page( $page_options ) {
	wptouch_add_sub_page( WPTOUCH_ADMIN_SETUP_DEVICES, 'setup-devices', $page_options );

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_DEVICES,
		__( 'Mobile Devices & Browsers', 'wptouch-pro' ),
		'admin_menu_homescreen_ipad_retina',
		array(
			wptouch_add_setting(
				'checkbox',
				'enable_ios_phone',
				'iOS (iPod, iPhone)',
				false,
				WPTOUCH_SETTING_BASIC,
				'4.0'
			),
			wptouch_add_setting(
				'checkbox',
				'enable_android_phone',
				'Android Stock & Chrome Browsers',
				false,
				WPTOUCH_SETTING_BASIC,
				'4.0'
			),
			wptouch_add_setting(
				'checkbox',
				'enable_blackberry_phone',
				'BlackBerry Browser',
				false,
				WPTOUCH_SETTING_BASIC,
				'4.0'
			),
			wptouch_add_setting(
				'checkbox',
				'enable_firefox_phone',
				'Firefox OS & Mobile Browser',
				false,
				WPTOUCH_SETTING_BASIC,
				'4.0'
			),
			wptouch_add_setting(
				'checkbox',
				'enable_windows_phone',
				'Windows Phone (IE)',
				false,
				WPTOUCH_SETTING_BASIC,
				'4.0'
			),
			wptouch_add_setting(
				'checkbox',
				'enable_opera_phone',
				'Opera Mini Browser',
				false,
				WPTOUCH_SETTING_BASIC,
				'4.0'
			),
		),
		$page_options,
		'wptouch_pro',
		false
	);

	if ( apply_filters( 'wptouch_allow_tablets', false ) ) {
		wptouch_add_page_section(
			WPTOUCH_ADMIN_SETUP_DEVICES,
			__( 'Tablets', 'wptouch-pro' ),
			'admin_menu_tablets',
			array(
				wptouch_add_pro_setting(
					'checkbox',
					'enable_ios_tablet',
					'iOS (iPad)',
					false,
					WPTOUCH_SETTING_BASIC,
					'4.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'enable_android_tablet',
					'Android',
					false,
					WPTOUCH_SETTING_BASIC,
					'4.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'enable_windows_tablet',
					'Windows Tablet',
					false,
					WPTOUCH_SETTING_BASIC,
					'4.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'enable_kindle_tablet',
					'Kindle',
					false,
					WPTOUCH_SETTING_BASIC,
					'4.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'enable_blackberry_tablet',
					'BlackBerry',
					false,
					WPTOUCH_SETTING_BASIC,
					'4.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'enable_webos_tablet',
					'WebOS',
					false,
					WPTOUCH_SETTING_BASIC,
					'4.0'
				),
			),
			$page_options,
			'wptouch_pro'
		);
	} else {
		wptouch_add_page_section(
			WPTOUCH_ADMIN_SETUP_DEVICES,
			__( 'Tablet Devices & Browsers', 'wptouch-pro' ),
			'admin_menu_tablets',
			array(
				wptouch_add_pro_setting(
					'custom',
					'no_tablet_support',
					false,
					false,
					WPTOUCH_SETTING_BASIC,
					'4.0'
				)
			),
			$page_options,
			'wptouch_pro',
			false,
			wptouchize_it( __( 'If your theme supports tablets, devices and browsers WPtouch Pro can be enabled for will be listed below.', 'wptouch-pro' ) )
		);
	}

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_DEVICES,
		__( 'Additional User Agents', 'wptouch-pro' ),
		'custom-user-agents',
		array(
			wptouch_add_setting(
				'multiline-newline',
				'custom_user_agents',
				__( 'User agents to add', 'wptouch-pro' ),
				__( 'You can enter partial i.e. "nokia" or full agent strings', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'3.0'
			)
		),
		$page_options,
		'wptouch_pro'
	);

	return $page_options;
}

function wptouch_render_menu_page( $page_options ) {
	wptouch_add_sub_page( WPTOUCH_ADMIN_MENU_MANAGE_ICON_SETS, 'menu-icons-manage-icon-sets', $page_options );

	wptouch_add_page_section(
		WPTOUCH_ADMIN_MENU_MANAGE_ICON_SETS,
		__( 'Menu Setup', 'wptouch-pro' ),
		'setup-menu-options',
		array(
			wptouch_add_setting(
				'custom',
				'menus_in_menus',
				false,
				false,
				WPTOUCH_SETTING_BASIC,
				'4.0'
			)
		),
		$page_options,
		'wptouch_pro'
	);

	wptouch_add_page_section(
		WPTOUCH_ADMIN_MENU_MANAGE_ICON_SETS,
		__( 'Menu Options', 'wptouch-pro' ),
		'setup-menu-parent-items',
		array(
			wptouch_add_setting(
				'checkbox',
				'enable_parent_items',
				__( 'Enable parent items as links', 'wptouch-pro' ),
				__( 'If disabled, parent menu items will only toggle child items.', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'3.0'
			),
			wptouch_add_setting(
				'checkbox',
				'enable_menu_icons',
				__( 'Use menu icons', 'wptouch-pro' ),
				__( 'Adds the ability to associate icons with menu items', 'wptouch-pro' ) . ' (in WordPress Appearance->Menus)',
				WPTOUCH_SETTING_BASIC,
				'3.0'
			)
		),
		$page_options,
		'wptouch_pro'
	);

	wptouch_add_page_section(
		WPTOUCH_ADMIN_MENU_MANAGE_ICON_SETS,
		__( 'Menu Icon Sets', 'wptouch-pro' ),
		'admin_menu_icon_sets',
		array(
			wptouch_add_setting(
				'custom',
				'installed_icon_sets'
			)
		),
		$page_options
	);

	wptouch_add_page_section(
		WPTOUCH_ADMIN_MENU_MANAGE_ICON_SETS,
		__( 'Custom Icons', 'wptouch-pro' ),
		'admin_menu_icon_upload',
		array(
			wptouch_add_setting(
				'custom',
				'custom_icon_upload'
			),
			wptouch_add_setting(
				'custom',
				'custom_icon_management'
			)
		),
		$page_options
	);

	return $page_options;
}

function wptouch_add_custom_menus( $menu_array ) {
	$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
	foreach( $menus as $key => $menu ) {
		$menu_array[ $menu->term_id ] = $menu->name;
	}

	return $menu_array;
}

function wptouch_get_custom_menu_list( $include_wp_pages = true, $include_none = false ) {
	$custom_menu = array();

	if ( $include_wp_pages ) {
		$custom_menu[ 'wp' ] = __( 'WordPress Pages', 'wptouch-pro' );
	}

	$custom_menu = wptouch_add_custom_menus( $custom_menu );

	if ( $include_none ) {
		$custom_menu[ 'none' ] = __( 'None', 'wptouch-pro' );
	}

	return $custom_menu;
}

function wptouch_render_theme_customize_page( $page_options ) {

	wptouch_add_sub_page( WPTOUCH_PRO_ADMIN_THEME_CUSTOMIZING, 'foundation-page-theme-customizer', $page_options );

	wptouch_add_page_section(
		WPTOUCH_PRO_ADMIN_THEME_CUSTOMIZING,
		__( 'Customize Theme', 'wptouch-pro' ),
		'handle-themes',
		array(
			wptouch_add_setting(
				'custom',
				'customizing_in_customizer'
			)
		),
		$page_options
	);

	return $page_options;
}

function wptouch_render_theme_gopro_page( $page_options ) {

	wptouch_add_sub_page( WPTOUCH_PRO_ADMIN_THEME_GOPRO, 'foundation-page-theme-gopro', $page_options );

	wptouch_add_page_section(
		WPTOUCH_PRO_ADMIN_THEME_GOPRO,
		__( 'Go Pro', 'wptouch-pro' ),
		'handle-gopro',
		array(
			wptouch_add_setting(
				'custom',
				'gopro'
			)
		),
		$page_options
	);

	return $page_options;
}

function wptouch_render_themes( $page_options ) {

	require_once( WPTOUCH_DIR . '/core/admin-themes.php' );

	wptouch_add_sub_page( WPTOUCH_PRO_ADMIN_THEMES, 'setup-themes-browser', $page_options );

	wptouch_add_page_section(
		WPTOUCH_PRO_ADMIN_THEMES,
		false,
		'handle-themes',
		array(
			wptouch_add_setting(
				'custom',
				'theme-browser'
			)
		),
		$page_options
	);

	return $page_options;
}

function wptouch_render_addons( $page_options ) {
	require_once( WPTOUCH_DIR . '/core/admin-extensions.php' );

	wptouch_add_sub_page( WPTOUCH_PRO_ADMIN_ADDONS, 'setup-addons-browser', $page_options );

	wptouch_add_page_section(
		WPTOUCH_PRO_ADMIN_ADDONS,
		false,
		'handle-addons',
		array(
			wptouch_add_setting(
				'custom',
				'extension-browser'
			)
		),
		$page_options
	);

	return $page_options;
}

function wptouch_render_addon_settings( $page_options ) {
	$page_options = apply_filters( 'wptouch_addon_options', $page_options );
	return $page_options;
}