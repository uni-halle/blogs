<?php
/**
 * Catch Box Theme Options
 *
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 1.0
 */


/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 *
 * @since Catch Box 1.0
 *
 */
function catchbox_admin_enqueue_scripts( $hook_suffix ) {
	wp_register_script( 'jquery-cookie', get_template_directory_uri() . '/js/jquery.cookie.min.js', array( 'jquery' ), '1.0', true );

	wp_enqueue_style( 'catchbox-theme-options', get_template_directory_uri() . '/inc/theme-options.min.css', array( 'wp-color-picker' ), '2011-04-28' );

	wp_enqueue_script( 'catchbox-theme-options', get_template_directory_uri() . '/inc/theme-options.min.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-cookie', 'jquery-ui-sortable', 'jquery-ui-draggable', 'wp-color-picker'	), '2011-06-10' );

	wp_enqueue_media();

	wp_enqueue_script( 'catchbox_upload', get_template_directory_uri().'/inc/add_image_scripts.js', array( 'jquery') );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'catchbox_admin_enqueue_scripts' );


/**
 * Register the form setting for our catchbox_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, catchbox_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * We also use this function to add our theme option if it doesn't already exist.
 *
 * @since Catch Box 1.0
 */
function catchbox_theme_options_init() {
	register_setting(
		'catchbox_options',       // Options group, see settings_fields() call in catchbox_theme_options_render_page()
		'catchbox_theme_options', // Database option, see catchbox_get_theme_options()
		'catchbox_theme_options_validate' // The sanitization callback, see catchbox_theme_options_validate()
	);

	// Register our settings field group
	add_settings_section(
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything)
		'theme_options' // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
	);

	//@remove Remove if block when WordPress 4.8 is released
    if( !function_exists( 'has_site_icon' ) ) {
    	add_settings_field(
			'favicon', // Unique identifier for the field for this section
			__( 'Favicon URL', 'catch-box' ), // Setting field label
			'catchbox_settings_field_favicon', // Function that renders the settings field
			'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
			'general' // Settings section. Same as the first argument in the add_settings_section() above
		);

		// Register our individual settings fields
		add_settings_field(
			'webclip', // Unique identifier for the field for this section
			__( 'Web Clip Icon URL', 'catch-box' ), // Setting field label
			'catchbox_settings_field_webclip', // Function that renders the settings field
			'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
			'general' // Settings section. Same as the first argument in the add_settings_section() above
		);
    }

	// Register our individual settings fields
	add_settings_field(
		'color_scheme', // Unique identifier for the field for this section
		__( 'Color Scheme', 'catch-box' ), // Setting field label
		'catchbox_settings_field_color_scheme', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field(
		'link_color', // Unique identifier for the field for this section
		__( 'Link Color', 'catch-box' ), // Setting field label
		'catchbox_settings_field_link_color', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field(
		'layout', // Unique identifier for the field for this section
		__( 'Default Layout', 'catch-box' ), // Setting field label
		'catchbox_settings_field_layout', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field(
		'content_layout', // Unique identifier for the settings section
		__( 'Content layout', 'catch-box' ), // Setting field label
		'catchbox_settings_field_content_scheme', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field(
		'excerpt_length', // Unique identifier for the settings section
		__( 'Excerpt Length in Words', 'catch-box' ), // Setting field label
		'catchbox_settings_field_excerpt_length', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field(
		'feed_redirect', // Unique identifier for the settings section
		__( 'Feed Redirect URL', 'catch-box' ), // Setting field label
		'catchbox_settings_field_feed_redirect', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field(
		'site_title_above', // Unique identifier for the settings section
		__( 'Move Site Title and Tagline?', 'catch-box' ), // Setting field label
		'catchbox_settings_field_site_title_above', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field(
		'search_text', // Unique identifier for the settings section
		__( 'Default Display Text in Search', 'catch-box' ), // Setting field label
		'catchbox_settings_field_search_text', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field(
		'disable_header_search', // Unique identifier for the settings section
		__( 'Disable Search in Header?', 'catch-box' ), // Setting field label
		'catchbox_settings_field_disable_header_search', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field(
		'enable_menus', // Unique identifier for the settings section
		__( 'Enable Secondary & Footer Menu in Mobile Devices?', 'catch-box' ), // Setting field label
		'catchbox_settings_field_enable_menus', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field(
		'custom_css', // Unique identifier for the settings section
		__( 'Custom CSS Styles', 'catch-box' ), // Setting field label
		'catchbox_settings_field_custom_css', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field(
		'disable_scrollup', // Unique identifier for the settings section
		__( 'Scroll Up', 'catch-box' ), // Setting field label
		'catchbox_settings_field_scroll_up', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see catchbox_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);
}
add_action( 'admin_init', 'catchbox_theme_options_init' );

/**
 * Change the capability required to save the 'catchbox_options' options group.
 *
 * @see catchbox_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see catchbox_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * By default, the options groups for all registered settings require the manage_options capability.
 * This filter is required to change our theme options page to edit_theme_options instead.
 * By default, only administrators have either of these capabilities, but the desire here is
 * to allow for finer-grained control for roles and users.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function catchbox_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_catchbox_options', 'catchbox_option_page_capability' );


/**
 * Add our theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 *
 * @since Catch Box 1.0
 */
function catchbox_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'catch-box' ),   	// Name of page
		__( 'Theme Options', 'catch-box' ),   	// Label in menu
		'edit_theme_options',              		// Capability required
		'theme_options',                  		// Menu slug, used to uniquely identify the page
		'catchbox_theme_options_render_page' 	// Function that renders the options page
	);

	if ( ! $theme_page )
		return;

}
add_action( 'admin_menu', 'catchbox_theme_options_add_page' );


/**
 * Renders the favicon url setting field.
 *
 * @since Catch Box 1.6
 *
 * @remove Remove if block when WordPress 4.8 is released
 */
function catchbox_settings_field_favicon() {
	$options = catchbox_get_theme_options();
	?>
	<input class="upload-url" type="text" name="catchbox_theme_options[fav_icon]" id="fav-icon" size="65" value="<?php if ( isset( $options[ 'fav_icon' ] ) ) echo esc_attr( $options[ 'fav_icon'] ); ?>" />
    <input ref="<?php esc_attr_e( 'Insert as Favicon','catch-box' );?>" class="catchbox_upload_image button" type="button" value="<?php esc_attr_e( 'Add/Change Favicon','catch-box' );?>" />
	<?php
}


/**
 * Renders the favicon url setting field.
 *
 * @since Catch Box 2.0.3
 *
 * @remove Remove if block when WordPress 4.8 is released
 */
function catchbox_settings_field_webclip() {
	$options = catchbox_get_theme_options();
	?>
	<input class="upload-url" type="text" name="catchbox_theme_options[web_clip]" id="web-clip" size="65" value="<?php if ( isset( $options[ 'web_clip' ] ) ) echo esc_attr( $options[ 'web_clip'] ); ?>" />
    <input ref="<?php esc_attr_e( 'Insert as Web Clip Icon','catch-box' );?>" class="catchbox_upload_image button" type="button" value="<?php esc_attr_e( 'Add/Change Web Clip Icon','catch-box' );?>" />
	<?php
}


/**
 * Renders the Header Image setting field.
 *
 * @since Catch Box 4.4.6
 */
function catchbox_settings_field_header_image() {
	$options = catchbox_get_theme_options();

	foreach ( catchbox_color_schemes() as $scheme ) {
	?>
        <div class="layout image-radio-option color-scheme">
        <label class="description">
            <input type="radio" name="catchbox_theme_options[color_scheme]" value="<?php echo esc_attr( $scheme['value'] ); ?>" <?php checked( $options['color_scheme'], $scheme['value'] ); ?> />
            <input type="hidden" id="default-color-<?php echo esc_attr( $scheme['value'] ); ?>" value="<?php echo esc_attr( $scheme['default_link_color'] ); ?>" />
            <span>
                <img src="<?php echo esc_url( $scheme['thumbnail'] ); ?>" width="164" height="122" alt="" />
                <?php echo $scheme['label']; ?>
            </span>
        </label>
        </div>
	<?php
	}
}


/**
 * Renders the Color Scheme setting field.
 *
 * @since Catch Box 1.0
 */
function catchbox_settings_field_color_scheme() {
	$options = catchbox_get_theme_options();

	foreach ( catchbox_color_schemes() as $scheme ) {
	?>
        <div class="layout image-radio-option color-scheme">
        <label class="description">
            <input type="radio" name="catchbox_theme_options[color_scheme]" value="<?php echo esc_attr( $scheme['value'] ); ?>" <?php checked( $options['color_scheme'], $scheme['value'] ); ?> />
            <input type="hidden" id="default-color-<?php echo esc_attr( $scheme['value'] ); ?>" value="<?php echo esc_attr( $scheme['default_link_color'] ); ?>" />
            <span>
                <img src="<?php echo esc_url( $scheme['thumbnail'] ); ?>" width="164" height="122" alt="" />
                <?php echo $scheme['label']; ?>
            </span>
        </label>
        </div>
	<?php
	}
}


/**
 * Renders the Link Color setting field.
 *
 * @since Catch Box 1.0
 */
function catchbox_settings_field_link_color() {
	$options = catchbox_get_theme_options();

	?>
	<input type="text" name="catchbox_theme_options[link_color]" class="catchbox_color_picker" value="<?php echo esc_attr( $options['link_color'] ); ?>" data-default-color="<?php echo catchbox_get_default_link_color( $options['color_scheme'] )  ?>" />
	<?php
}


/**
 * Renders the excerpt length setting field.
 *
 * @since Catch Box 1.1.3
 */
function catchbox_settings_field_excerpt_length() {
	$options = catchbox_get_theme_options();
	if( empty( $options['excerpt_length'] ) )
		$options = catchbox_get_default_theme_options();
	?>

	<input type="text" name="catchbox_theme_options[excerpt_length]" id="excerpt-length" size="3" value="<?php if ( isset( $options[ 'excerpt_length' ] ) ) echo absint( $options[ 'excerpt_length'] ); ?>" /> <?php _e( 'Words', 'catch-box' ); ?>
	<?php
}


/**
 * Renders the feed redirect setting field.
 *
 * @since Catch Box 1.0
 */
function catchbox_settings_field_feed_redirect() {
	$options = catchbox_get_theme_options();
	?>
	<input type="text" name="catchbox_theme_options[feed_url]" id="feed-url" size="65" value="<?php if ( isset( $options[ 'feed_url' ] ) ) echo esc_attr( $options[ 'feed_url'] ); ?>" />
	<?php
}


/**
 * Move Site Title and Tagline above Header Image Checkbox
 *
 * @since Catch Box 2.5
 */
function catchbox_settings_field_site_title_above() {
	$options = catchbox_get_theme_options();
	if( empty( $options['site_title_above'] ) )
		$options = catchbox_get_default_theme_options();
	?>
    <input type="checkbox" id="disable-header-search" name="catchbox_theme_options[site_title_above]" value="1" <?php checked( '1', $options['site_title_above'] ); ?> /> <?php _e( 'Check to move above the Header/Logo Image', 'catch-box' ); ?>
	<?php
}


/**
 * Search Text
 *
 * @since Catch Box 2.8.1
 */
function catchbox_settings_field_search_text() {
	$options = catchbox_get_theme_options();
	if( empty( $options['search_display_text'] ) )
		$options = catchbox_get_default_theme_options();
	?>
    <input type="text" size="45" name="catchbox_theme_options[search_display_text]" value="<?php if ( isset( $options[ 'search_display_text' ] ) ) echo esc_attr( $options[ 'search_display_text' ] ); ?>" />
	<?php
}


/**
 * Disable Header Search Checkbox
 *
 * @since Catch Box 1.3.1
 */
function catchbox_settings_field_disable_header_search() {
	$options = catchbox_get_theme_options();
	if( empty( $options['disable_header_search'] ) )
		$options = catchbox_get_default_theme_options();
	?>
    <input type="checkbox" id="disable-header-search" name="catchbox_theme_options[disable_header_search]" value="1" <?php checked( '1', $options['disable_header_search'] ); ?> /> <?php _e( 'Check to Disable', 'catch-box' ); ?>
	<?php
}


/**
 * Enable Secondary and Footer Menu
 *
 * @since Catch Box 2.2.2
 */
function catchbox_settings_field_enable_menus() {
	$options = catchbox_get_theme_options();
	if( empty( $options['enable_menus'] ) )
		$options = catchbox_get_default_theme_options();
	?>
    <input type="checkbox" id="disable-header-search" name="catchbox_theme_options[enable_menus]" value="1" <?php checked( '1', $options['enable_menus'] ); ?> /> <?php _e( 'Check to Enable', 'catch-box' ); ?>
	<?php
}


/**
 * Renders the Custom CSS styles setting fields
 *
 * @since Catch Box 1.0
 */
function catchbox_settings_field_custom_css() {
	$options = catchbox_get_theme_options();
	?>
     <textarea id="custom-css" cols="90" rows="12" name="catchbox_theme_options[custom_css]"><?php if (!empty($options['custom_css'] ) )echo esc_attr($options['custom_css']); ?></textarea> <br />
     <?php _e('CSS Tutorial from W3Schools.', 'catch-box'); ?> <a class="button" href="<?php echo esc_url( __( 'http://www.w3schools.com/css/default.asp','catch-box' ) ); ?>" title="<?php esc_attr_e( 'CSS Tutorial', 'catch-box' ); ?>"><?php _e( 'Click Here to Read', 'catch-box' );?></a>

	<?php
}

/**
 * Renders the Scroll Up setting fields
 *
 * @since Catch Box 1.0
 */
function catchbox_settings_field_scroll_up() {
	$options = catchbox_get_theme_options();
	?>
     <input type='hidden' value='0' name='catchbox_theme_options[disable_scrollup]'>
    <input type="checkbox" id="scroll-up" name="catchbox_theme_options[disable_scrollup]" value="1" <?php checked( '1', $options['disable_scrollup'] ); ?> /> <?php _e('Check to disable', 'catch-box'); ?>

	<?php
}


/**
 * Renders the Layout setting field.
 *
 * @since Catch Box 1.0
 */
function catchbox_settings_field_layout() {
	$options = catchbox_get_theme_options();

	//Condition checks for backward compatibility
	if ( 'content-sidebar' == $options['theme_layout'] ) {
		$options['theme_layout'] = 'right-sidebar';
	}
	else if ( 'sidebar-content' == $options['theme_layout'] ) {
		$options['theme_layout'] = 'left-sidebar';
	}
	else if ( 'content-onecolumn' == $options['theme_layout'] ) {
		$options['theme_layout'] = 'no-sidebar-one-column';
	}

	foreach ( catchbox_layouts() as $layout ) {
		?>
		<div class="layout image-radio-option theme-layout">
		<label class="description">
			<input type="radio" name="catchbox_theme_options[theme_layout]" value="<?php echo esc_attr( $layout['value'] ); ?>" <?php checked( $options['theme_layout'], $layout['value'] ); ?> />
			<span>
				<img src="<?php echo esc_url( $layout['thumbnail'] ); ?>" width="136" height="122" alt="" />
				<?php echo $layout['label']; ?>
			</span>
		</label>
		</div>
		<?php
	}
}


/**
 * Renders the Content layout setting fields.
 *
 * @since Catch Box 1.0
 */
function catchbox_settings_field_content_scheme() {
	$options = catchbox_get_theme_options();
	foreach ( catchbox_content_layout() as $content ) {
		?>
		<div class="layout image-radio-option theme-layout">
            <label class="description">
                <input type="radio" name="catchbox_theme_options[content_layout]" value="<?php echo esc_attr( $content['value'] ); ?>" <?php checked( $options['content_layout'], $content['value'] ); ?> />
                <span>
                	<img src="<?php echo esc_url( $content['thumbnail'] ); ?>" width="164" height="163" alt="" />
                	<?php echo $content['label']; ?>
            	</span>
			</label>
		</div>
		<?php
	}
}


/**
 * Returns the options array for Catch Box.
 *
 * @since Catch Box 1.0
 */
function catchbox_theme_options_render_page() {
	?>
	<div id="catchthemes" class="wrap">

			<div id="theme-option-header">

                <div id="theme-option-title">
                    <h2 class="title"><?php _e( 'Theme Options By', 'catch-box' ); ?></h2>
                    <h2 class="logo">
                        <a href="<?php echo esc_url( __( 'https://catchthemes.com/', 'catch-box' ) ); ?>" title="<?php esc_attr_e( 'Catch Themes', 'catch-box' ); ?>">
                            <img src="<?php echo get_template_directory_uri().'/inc/images/catch-themes.png'; ?>" alt="<?php _e( 'Catch Themes', 'catch-box' ); ?>" />
                        </a>
                    </h2>
                </div><!-- #theme-option-title -->

                <div id="upgradepro">
                	<a class="button" href="<?php echo esc_url(__('https://catchthemes.com/themes/catch-box-pro/','catch-box')); ?>" title="<?php esc_attr_e('Upgrade to Catch Box Pro', 'catch-box'); ?>"><?php printf(__('Upgrade to Catch Box Pro','catch-box')); ?></a>
               	</div><!-- #upgradepro -->

                <div id="theme-support">
                    <ul>
                    	<li><a class="button" href="<?php echo esc_url(__('https://catchthemes.com/donate/','catch-box')); ?>" title="<?php esc_attr_e('Donate', 'catch-box'); ?>"><?php printf(__('Donate','catch-box')); ?></a></li>
                    	<li><a class="button" href="<?php echo esc_url(__('https://catchthemes.com/support/','catch-box')); ?>" title="<?php esc_attr_e('Support', 'catch-box'); ?>"><?php printf(__('Support','catch-box')); ?></a></li>
                        <li><a class="button" href="<?php echo esc_url(__('https://catchthemes.com/support-forum/forum/catch-box-public/','catch-box')); ?>" title="<?php esc_attr_e('Support Forum', 'catch-box'); ?>"><?php printf(__('Support Forum','catch-box')); ?></a></li>
                        <li><a class="button" href="<?php echo esc_url(__('https://catchthemes.com/theme-instructions/catch-box/','catch-box')); ?>" title="<?php esc_attr_e('Theme Instructions', 'catch-box'); ?>"><?php printf(__('Theme Instructions','catch-box')); ?></a></li>
                        <li><a class="button" href="<?php echo esc_url(__('https://www.facebook.com/catchthemes/','catch-box')); ?>" title="<?php esc_attr_e('Like Catch Themes on Facebook', 'catch-box'); ?>"><?php printf(__('Facebook','catch-box')); ?></a></li>
                        <li><a class="button" href="<?php echo esc_url(__('https://twitter.com/catchthemes/','catch-box')); ?>" title="<?php esc_attr_e('Follow Catch Themes on Twitter', 'catch-box'); ?>"><?php printf(__('Twitter','catch-box')); ?></a></li>
                        <li><a class="button" href="<?php echo esc_url(__('http://wordpress.org/support/view/theme-reviews/catch-box/','catch-box')); ?>" title="<?php esc_attr_e('Rate us 5 Star on WordPress', 'catch-box'); ?>"><?php printf(__('5 Star Rating','catch-box')); ?></a></li>
                   	</ul>
                </div><!-- #theme-support -->

                <div id="theme-option-header-notice">
                    <p class="notice">
                        <?php printf( _x( 'Theme Options Panel will be retired on future versions. Please use %1$s Customizer %2$s instead.','1: Customizer Link Start, 2: Customizer Link End' , 'catch-box' ) , '<a href="' . esc_url ( admin_url( 'customize.php' ) ) . '">', '</a>' ); ?>
                    </p>
                </div><!-- #theme-option-header -->

          	</div><!-- #theme-option-header -->


            <div id="catchbox_ad_tabs">
                <ul class="tabNavigation" id="mainNav">
                    <li><a href="#themeoptions"><?php _e( 'Theme Options', 'catch-box' );?></a></li>
                    <li><a href="#slidersettings"><?php _e( 'Featured Post Slider', 'catch-box' );?></a></li>
                    <li><a href="#sociallinks"><?php _e( 'Social Links', 'catch-box' );?></a></li>
                    <li><a href="#webmaster"><?php _e( 'Webmaster Tools', 'catch-box' );?></a></li>
                </ul><!-- .tabsNavigation #mainNav -->
               <form method="post" action="options.php">
                <!-- Option for Theme Options -->
                <div id="themeoptions">
                	<div class="option-container">
                        	<table class="form-table">
                                <tbody>
                                    <tr>
                                        <th scope="row"><?php _e( 'Header Image:', 'catch-box' ); ?></th>
                                        <td>
                                        <?php
                                        settings_fields( 'catchbox_options' );

                                        $options = catchbox_get_theme_options();

                                        $header_image = get_header_image();
                                        if ( !empty ( $header_image ) ) {
                                            echo '<a class="button" href="' . admin_url('themes.php?page=custom-header') . '" title="' .esc_attr__( 'Click here to change Header Image', 'catch-box' ). '">' . __( 'Click here to change Header Image', 'catch-box' ) . '</a>';
                                        } else {
                                            echo '<a class="button" href="' . admin_url('themes.php?page=custom-header') . '" title="' .esc_attr__( 'Click here to add Header Image', 'catch-box' ). '">' . __( 'Click here to add Header Image', 'catch-box' ) . '</a>';
                                        }  ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Header Image Location', 'catch-box' ); ?></label></th>
                                        <td>
                                            <select name="catchbox_theme_options[header_image_position]">
                                                <option value="above" <?php selected( 'above', $options[ 'header_image_position' ] ); ?>><?php echo __( 'Above Header Content', 'catch-box' ); ?></option>

                                                <option value="between" <?php selected( 'between', $options[ 'header_image_position' ] ); ?>><?php echo __( 'Between Site Title-Logo', 'catch-box' ); ?></option>

                                                <option value="below" <?php selected( 'below', $options[ 'header_image_position' ] ); ?>><?php echo __( 'Below Header Content', 'catch-box' ); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </div><!-- .option-container -->

                	<div class="option-container">

                            <?php
                                settings_fields( 'catchbox_options' );
                                do_settings_sections( 'theme_options' );
                                submit_button();
                            ?>

                  	</div><!-- .option-container -->
             	</div> <!-- #themeoptions -->

                <!-- Option for Featured Post Slider -->
                <div id="slidersettings">

						<?php
                            settings_fields( 'catchbox_options' );
                            $options = catchbox_get_theme_options();

                            if( is_array( $options ) && ( !array_key_exists( 'slider_qty', $options ) || !is_numeric( $options[ 'slider_qty' ] ) ) ) $options[ 'slider_qty' ] = 4;
                            elseif( !is_array( $options ) ) $options = array( 'slider_qty' => 4);
                        ?>
                        <div class="option-container">
                            <h3 class="option-toggle"><a href="#"><?php _e( 'Slider Options', 'catch-box' ); ?></a></h3>
                            <div class="option-content inside">
                                <table class="form-table">
                                    <tr>
                                        <th scope="row"><?php _e( 'Post Slider Tutorial', 'catch-box' ); ?></th>
                                        <td>
                                            <a class="button" href="<?php echo esc_url( __( 'https://catchthemes.com/blog/videos-blog/video-series-adding-featured-post-slider/','catch-box' ) ); ?>" title="<?php esc_attr_e( 'Post Slider Tutorial', 'catch-box' ); ?>"><?php _e( 'Click Here to Read Post Slider Tutorial', 'catch-box' );?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e( 'Exclude Slider post from Home page posts:', 'catch-box' ); ?></th>
                                        <input type='hidden' value='0' name='catchbox_theme_options[exclude_slider_post]'>
                                        <td><input type="checkbox" id="headerlogo" name="catchbox_theme_options[exclude_slider_post]" value="1" <?php isset($options['exclude_slider_post']) ? checked( '1', $options['exclude_slider_post'] ) : checked('0', '1'); ?> /> <?php _e( 'Check to disable', 'catch-box' ); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e( 'Number of Slides', 'catch-box' ); ?></th>
                                        <td><input type="text" name="catchbox_theme_options[slider_qty]" value="<?php if ( array_key_exists ( 'slider_qty', $options ) ) echo intval( $options[ 'slider_qty' ] ); ?>" /></td>
                                    </tr>
                                    <tbody class="sortable">
                                        <?php for ( $i = 1; $i <= $options[ 'slider_qty' ]; $i++ ): ?>
                                        <tr>
                                            <th scope="row"><label class="handle"><span class="count"><?php echo '#' . absint( $i ); ?></span> <?php _e( 'Featured Post ID', 'catch-box' ); ?></label></th>
                                            <td><input type="text" name="catchbox_theme_options[featured_slider][<?php echo absint( $i ); ?>]" value="<?php if( array_key_exists( 'featured_slider', $options ) && array_key_exists( $i, $options[ 'featured_slider' ] ) ) echo absint( $options[ 'featured_slider' ][ $i ] ); ?>" />
                                            <a href="<?php bloginfo ( 'url' );?>/wp-admin/post.php?post=<?php if( array_key_exists ( 'featured_slider', $options ) && array_key_exists ( $i, $options[ 'featured_slider' ] ) ) echo absint( $options[ 'featured_slider' ][ $i ] ); ?>&action=edit" class="button" title="<?php esc_attr_e( 'Click Here To Edit', 'catch-box' ); ?>"><?php _e( 'Click Here To Edit', 'catch-box' ); ?></a>
                                            </td>
                                        </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                                <p><?php _e( '<strong>Note</strong>: Here you add in Post IDs which displays on Homepage Featured Slider.', 'catch-box' ); ?> </p>
                                <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catch-box' ); ?>" /></p>
                            </div><!-- .option-content -->
                        </div><!-- .option-container -->
                        <div class="option-container">
                            <h3 class="option-toggle"><a href="#"><?php _e( 'Slider Effect Options', 'catch-box' ); ?></a></h3>
                            <div class="option-content inside">
                                <table class="form-table">
                                    <tr>
                                        <th>
                                        <label for="catchbox_cycle_style"><?php _e( 'Transition Effect:', 'catch-box' ); ?></label>
                                        </th>
                                        <?php if( empty( $options['transition_effect'] ) ) { $options['transition_effect'] = "fade"; } ?>
                                        <td>
                                            <select id="catchbox_cycle_style" name="catchbox_theme_options[transition_effect]">
                                                <option value="fade" <?php selected('fade', $options['transition_effect']); ?>><?php _e( 'fade', 'catch-box' ); ?></option>
                                                <option value="wipe" <?php selected('wipe', $options['transition_effect']); ?>><?php _e( 'wipe', 'catch-box' ); ?></option>
                                                <option value="scrollUp" <?php selected('scrollUp', $options['transition_effect']); ?>><?php _e( 'scrollUp', 'catch-box' ); ?></option>
                                                <option value="scrollDown" <?php selected('scrollDown', $options['transition_effect']); ?>><?php _e( 'scrollDown', 'catch-box' ); ?></option>
                                                <option value="scrollLeft" <?php selected('scrollLeft', $options['transition_effect']); ?>><?php _e( 'scrollLeft', 'catch-box' ); ?></option>
                                                <option value="scrollRight" <?php selected('scrollRight', $options['transition_effect']); ?>><?php _e( 'scrollRight', 'catch-box' ); ?></option>
                                                <option value="blindX" <?php selected('blindX', $options['transition_effect']); ?>><?php _e( 'blindX', 'catch-box' ); ?></option>
                                                <option value="blindY" <?php selected('blindY', $options['transition_effect']); ?>><?php _e( 'blindY', 'catch-box' ); ?></option>
                                                <option value="blindZ" <?php selected('blindZ', $options['transition_effect']); ?>><?php _e( 'blindZ', 'catch-box' ); ?></option>
                                                <option value="cover" <?php selected('cover', $options['transition_effect']); ?>><?php _e( 'cover', 'catch-box' ); ?></option>
                                                <option value="shuffle" <?php selected('shuffle', $options['transition_effect']); ?>><?php _e( 'shuffle', 'catch-box' ); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php if( empty( $options['transition_delay'] ) ) { $options['transition_delay'] = 4; } ?>
                                    <tr>
                                        <th scope="row"><?php _e( 'Transition Delay', 'catch-box' ); ?></th>
                                        <td>
                                            <input type="text" name="catchbox_theme_options[transition_delay]" value="<?php if( isset( $options [ 'transition_delay' ] ) ) echo $options[ 'transition_delay' ]; ?>" size="4" />
                                       <span class="description"><?php _e( 'second(s)', 'catch-box' ); ?></span>
                                        </td>
                                    </tr>

                                    <?php if( empty( $options['transition_duration'] ) ) { $options['transition_duration'] = 1; } ?>
                                    <tr>
                                        <th scope="row"><?php _e( 'Transition Length', 'catch-box' ); ?></th>
                                        <td>
                                            <input type="text" name="catchbox_theme_options[transition_duration]" value="<?php if( isset( $options [ 'transition_duration' ] ) ) echo $options[ 'transition_duration' ]; ?>" size="4" />
                                        <span class="description"><?php _e( 'second(s)', 'catch-box' ); ?></span>
                                        </td>
                                    </tr>
                                </table>
                                <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catch-box' ); ?>" /></p>
                            </div><!-- .option-content -->
                        </div><!-- .option-container -->


                </div> <!-- #slidersettings -->

                <!-- Option for Social Links -->
                <div id="sociallinks">
                	<div class="option-container">

                            <?php
                               settings_fields( 'catchbox_options' );
                            ?>
                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th scope="row"><label><?php _e( 'Facebook', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_facebook]" value="<?php if( isset( $options[ 'social_facebook' ] ) ) echo esc_url( $options[ 'social_facebook' ] ); ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label><?php _e( 'Twitter', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_twitter]" value="<?php if ( isset( $options[ 'social_twitter' ] ) ) echo esc_url( $options[ 'social_twitter'] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Google +', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_google]" value="<?php if ( isset( $options[ 'social_google' ] ) ) echo esc_url( $options[ 'social_google' ] ); ?>" />
                                        </td>
                                    </tr>

                                     <tr>
                                        <th scope="row"><label><?php _e( 'LinkedIn', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_linkedin]" value="<?php if ( isset( $options[ 'social_linkedin' ] ) ) echo esc_url( $options[ 'social_linkedin' ] ); ?>" />
                                        </td>
                                    </tr>

                                     <tr>
                                        <th scope="row"><label><?php _e( 'Pinterest', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_pinterest]" value="<?php if ( isset( $options[ 'social_pinterest' ] ) ) echo esc_url( $options[ 'social_pinterest' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Youtube', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_youtube]" value="<?php if ( isset( $options[ 'social_youtube' ] ) ) echo esc_url( $options[ 'social_youtube' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'RSS Feed', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_rss]" value="<?php if ( isset( $options[ 'social_rss' ] ) ) echo esc_url( $options[ 'social_rss' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Deviantart', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_deviantart]" value="<?php if ( isset( $options[ 'social_deviantart' ] ) ) echo esc_url( $options[ 'social_deviantart' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Tumblr', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_tumblr]" value="<?php if ( isset( $options[ 'social_tumblr' ] ) ) echo esc_url( $options[ 'social_tumblr' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Vimeo', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_viemo]" value="<?php if ( isset( $options[ 'social_viemo' ] ) ) echo esc_url( $options[ 'social_viemo' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Dribbble', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_dribbble]" value="<?php if ( isset( $options[ 'social_dribbble' ] ) ) echo esc_url( $options[ 'social_dribbble' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'MySpace', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_myspace]" value="<?php if ( isset( $options[ 'social_myspace' ] ) ) echo esc_url( $options[ 'social_myspace' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Aim', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_aim]" value="<?php if ( isset( $options[ 'social_aim' ] ) ) echo esc_url( $options[ 'social_aim' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Flickr', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_flickr]" value="<?php if ( isset( $options[ 'social_flickr' ] ) ) echo esc_url( $options[ 'social_flickr' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Slideshare', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_slideshare]" value="<?php if ( isset( $options[ 'social_slideshare' ] ) ) echo esc_url( $options[ 'social_slideshare' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Instagram', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_instagram]" value="<?php if ( isset( $options[ 'social_instagram' ] ) ) echo esc_url( $options[ 'social_instagram' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Skype', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_skype]" value="<?php if ( isset( $options[ 'social_skype' ] ) ) echo esc_attr( $options[ 'social_skype' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Soundcloud', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_soundcloud]" value="<?php if ( isset( $options[ 'social_soundcloud' ] ) ) echo esc_url( $options[ 'social_soundcloud' ] ); ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label><?php _e( 'Email', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_email]" value="<?php if ( isset( $options[ 'social_email' ] ) ) echo sanitize_email( $options[ 'social_email' ] ); ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label><?php _e( 'Xing', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_xing]" value="<?php if ( isset( $options[ 'social_xing' ] ) ) echo esc_url( $options[ 'social_xing' ] ); ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label><?php _e( 'Meetup', 'catch-box' ); ?></label></th>
                                        <td><input type="text" size="45" name="catchbox_theme_options[social_meetup]" value="<?php if( isset( $options[ 'social_meetup' ] ) ) echo esc_url( $options[ 'social_meetup' ] ); ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p><?php _e( '<strong>Note:</strong> Enter the url for correponding social networking website', 'catch-box' ); ?></p>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catch-box' ); ?>" /></p>

                  	</div><!-- .option-container -->
                </div> <!-- #sociallinks -->

                <!-- Option for Webmaster Tools -->
                <div id="webmaster">

                        <?php
                            settings_fields( 'catchbox_options' );
                        ?>
                        <div class="option-container">
                            <h3 class="option-toggle"><a href="#"><?php _e( 'Header and Footer Code', 'catch-box' ); ?></a></h3>
                           <div class="option-content inside">
                                <table class="form-table">
                                    <table class="form-table">
                                        <tr>
                                            <th scope="row"><label><?php _e('Code to display on Header', 'catch-box' ); ?></label></th>
                                            <td>
                                             <textarea name="catchbox_theme_options[tracker_header]" rows="7" cols="80" ><?php if ( isset( $options [ 'tracker_header' ] ) )  echo esc_attr( $options[ 'tracker_header' ] ); ?></textarea>

                                            </td>
                                        </tr>
                                        <tr>
                                        	<td></td><td><?php _e('Note: Here you can put scripts from Google, Facebook etc. which will load on Header', 'catch-box' ); ?></td>
                                    	</tr>

                                        <tr>
                                            <th scope="row"><label><?php _e('Code to display on Footer', 'catch-box' ); ?></label></th>
                                            <td>
                                             <textarea name="catchbox_theme_options[tracker_footer]" rows="7" cols="80" ><?php if ( isset( $options [ 'tracker_footer' ] ) )  echo esc_attr( $options[ 'tracker_footer' ] ); ?></textarea>

                                            </td>
                                        </tr>
                                        <tr>
                                        	<td></td><td><?php _e( 'Note: Here you can put scripts from Google, Facebook, Add This etc. which will load on footer', 'catch-box' ); ?></td>
                                   	 	</tr>
                                    </tbody>
                                </table>
                                <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catch-box' ); ?>" /></p>
                            </div> <!-- .option-content  -->
                        </div> <!-- .option-container  -->

                </div> <!-- #webmaster -->
                </form>
       		</div><!-- #catchbox_ad_tabs -->


	</div>
	<?php
}


/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see catchbox_theme_options_init()
 * @todo set up Reset Options action
 *
 * @since Catch Box 1.0
 */
function catchbox_theme_options_validate( $input ) {
	$options_validated = $defaults = catchbox_get_default_theme_options();

	// favicon url
	if ( isset( $input['fav_icon'] ) )
		$options_validated['fav_icon'] = esc_url_raw($input['fav_icon']);

	// web clicp icon url
	if ( isset( $input['web_clip'] ) )
		$options_validated['web_clip'] = esc_url_raw($input['web_clip']);

	// Validate Header Image Position
	if ( isset( $input['header_image_position'] ) )
		$options_validated['header_image_position'] = sanitize_key( $input['header_image_position'] );

	// Color scheme must be in our array of color scheme options
	if ( isset( $input['color_scheme'] ) && array_key_exists( $input['color_scheme'], catchbox_color_schemes() ) )
		$options_validated['color_scheme'] = $input['color_scheme'];

	// Our defaults for the link color may have changed, based on the color scheme.
	$options_validated['link_color'] = $defaults['link_color'] = catchbox_get_default_link_color( $options_validated['color_scheme'] );

	// Link color must be 3 or 6 hexadecimal characters
	if ( isset( $input['link_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['link_color'] ) )
		$options_validated['link_color'] = '#' . strtolower( ltrim( $input['link_color'], '#' ) );

	// Theme layout must be in our array of theme layout options
	if ( isset( $input['theme_layout'] ) ) {
		$options_validated['theme_layout'] = sanitize_key( $input['theme_layout'] );
	}

	// Theme layout must be in our array of theme layout options
	if ( isset( $input['content_layout'] ) && array_key_exists( $input['content_layout'], catchbox_content_layout() ) )
		$options_validated['content_layout'] = $input['content_layout'];

	// excerpt length
	if ( isset( $input['excerpt_length'] ) )
		$options_validated['excerpt_length'] = absint($input['excerpt_length']);

	// feed url
	if ( isset( $input['feed_url'] ) )
		$options_validated['feed_url'] = esc_url_raw($input['feed_url']);

	// Move Site Title
	if ( isset( $input['site_title_above'] ) )
		// Our checkbox value is either 0 or 1
		$options_validated[ 'site_title_above' ] = $input[ 'site_title_above' ];

	// data validation for Search Settings
	if ( isset( $input[ 'search_display_text' ] ) ) {
        $options_validated[ 'search_display_text' ] = sanitize_text_field( $input[ 'search_display_text' ] );
    }

	// Disable Header Search
	if ( isset( $input['disable_header_search'] ) )
		// Our checkbox value is either 0 or 1
		$options_validated[ 'disable_header_search' ] = $input[ 'disable_header_search' ];

	// Enable Seconday and Footer Menu
	if ( isset( $input['enable_menus'] ) )
		// Our checkbox value is either 0 or 1
		$options_validated[ 'enable_menus' ] = $input[ 'enable_menus' ];

	// Custom CSS
	if ( isset( $input['custom_css'] ) )
		$options_validated['custom_css'] = wp_kses_stripslashes($input['custom_css']);

	// data validation for scroll up
	if ( isset( $input['disable_scrollup'] ) ) {
		// Our checkbox value is either 0 or 1
		$options_validated[ 'disable_scrollup' ] = $input[ 'disable_scrollup' ];
	}

	if ( isset( $input[ 'slider_qty' ] ) ) {
		$options_validated[ 'slider_qty' ] = absint( $input[ 'slider_qty' ] ) ? $input [ 'slider_qty' ] : 4;
	}
	if ( isset( $input[ 'featured_slider' ] ) ) {
		$options_validated[ 'featured_slider' ] = array();
	}

 	if( isset( $input[ 'slider_qty' ] ) ) {
		for ( $i = 1; $i <= $input [ 'slider_qty' ]; $i++ ) {
			if ( !empty( $input[ 'featured_slider' ][ $i ] ) && intval( $input[ 'featured_slider' ][ $i ] ) ) {
				$options_validated[ 'featured_slider' ][ $i ] = absint( $input[ 'featured_slider' ][ $i ] );
			}
		}
	}

	if ( isset( $input['exclude_slider_post'] ) ) {
        // Our checkbox value is either 0 or 1
   		$options_validated[ 'exclude_slider_post' ] = $input[ 'exclude_slider_post' ];

    }

    if( !empty( $input[ 'transition_effect' ] ) ) {
        $options_validated['transition_effect'] = wp_filter_nohtml_kses( $input['transition_effect'] );
    }

    // data validation for transition delay
    if ( !empty( $input[ 'transition_delay' ] ) && is_numeric( $input[ 'transition_delay' ] ) ) {
        $options_validated[ 'transition_delay' ] = $input[ 'transition_delay' ];
    }

    // data validation for transition length
    if ( !empty( $input[ 'transition_duration' ] ) && is_numeric( $input[ 'transition_duration' ] ) ) {
        $options_validated[ 'transition_duration' ] = $input[ 'transition_duration' ];
    }

	//Facebook
	if( isset( $input[ 'social_facebook' ] ) )
		$options_validated[ 'social_facebook' ] = esc_url_raw( $input[ 'social_facebook' ] );
	//Twitter
	if( isset( $input[ 'social_twitter' ] ) )
		$options_validated[ 'social_twitter' ] = esc_url_raw( $input[ 'social_twitter' ] );
	//Youtube
	if( isset( $input[ 'social_youtube' ] ) )
		$options_validated[ 'social_youtube' ] = esc_url_raw( $input[ 'social_youtube' ] );
	//Google+
	if( isset( $input[ 'social_google' ] ) )
		$options_validated[ 'social_google' ] = esc_url_raw( $input[ 'social_google' ] );
	//RSS
	if( isset( $input[ 'social_rss' ] ) )
		$options_validated[ 'social_rss' ] = esc_url_raw( $input[ 'social_rss' ] );
	//Linkedin
	if( isset( $input[ 'social_linkedin' ] ) )
		$options_validated[ 'social_linkedin' ] = esc_url_raw( $input[ 'social_linkedin' ] );
	//Pinterest
	if( isset( $input[ 'social_pinterest' ] ) )
		$options_validated[ 'social_pinterest' ] = esc_url_raw( $input[ 'social_pinterest' ] );
	//Deviantart
	if( isset( $input[ 'social_deviantart' ] ) )
		$options_validated[ 'social_deviantart' ] = esc_url_raw( $input[ 'social_deviantart' ] );
	//Tumblr
	if( isset( $input[ 'social_tumblr' ] ) )
		$options_validated[ 'social_tumblr' ] = esc_url_raw( $input[ 'social_tumblr' ] );
	//Viemo
	if( isset( $input[ 'social_viemo' ] ) )
		$options_validated[ 'social_viemo' ] = esc_url_raw( $input[ 'social_viemo' ] );
	//Dribble
	if( isset( $input[ 'social_dribbble' ] ) )
		$options_validated[ 'social_dribbble' ] = esc_url_raw( $input[ 'social_dribbble' ] );
	//Myspace
	if( isset( $input[ 'social_myspace' ] ) )
		$options_validated[ 'social_myspace' ] = esc_url_raw( $input[ 'social_myspace' ] );
	//Aim
	if( isset( $input[ 'social_aim' ] ) )
		$options_validated[ 'social_aim' ] = esc_url_raw( $input[ 'social_aim' ] );
	//Flickr
	if( isset( $input[ 'social_flickr' ] ) )
		$options_validated[ 'social_flickr' ] = esc_url_raw( $input[ 'social_flickr' ] );
	//Slideshare
	if( isset( $input[ 'social_slideshare' ] ) )
		$options_validated[ 'social_slideshare' ] = esc_url_raw( $input[ 'social_slideshare' ] );
	//Instagram
	if( isset( $input[ 'social_instagram' ] ) )
		$options_validated[ 'social_instagram' ] = esc_url_raw( $input[ 'social_instagram' ] );
	//Skype
	if( isset( $input[ 'social_skype' ] ) )
		$options_validated[ 'social_skype' ] = sanitize_text_field( $input[ 'social_skype' ] );
	//Soundcloud
	if( isset( $input[ 'social_soundcloud' ] ) )
		$options_validated[ 'social_soundcloud' ] = esc_url_raw( $input[ 'social_soundcloud' ] );
	//Email
	if( isset( $input[ 'social_email' ] ) )
		$options_validated[ 'social_email' ] = sanitize_email( $input[ 'social_email' ] );
	//Xing
	if( isset( $input[ 'social_xing' ] ) )
		$options_validated[ 'social_xing' ] = esc_url_raw( $input[ 'social_xing' ] );
	//Meetup
	if( isset( $input[ 'social_meetup' ] ) )
		$options_validated[ 'social_meetup' ] = esc_url_raw( $input[ 'social_meetup' ] );

	// data validation for tracking code
	if( isset( $input[ 'tracker_header' ] ) )
		$options_validated[ 'tracker_header' ] = wp_kses_stripslashes( $input[ 'tracker_header' ] );
	if( isset( $input[ 'tracker_footer' ] ) )
		$options_validated[ 'tracker_footer' ] = wp_kses_stripslashes( $input[ 'tracker_footer' ] );

	//Clearing the theme option cache
	if( function_exists( 'catchbox_themeoption_invalidate_caches' ) )  { catchbox_themeoption_invalidate_caches(); }


	return $options_validated;
}