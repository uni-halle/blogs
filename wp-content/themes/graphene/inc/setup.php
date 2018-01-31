<?php
/**
 * Run the database updater if necessary
*/
function graphene_db_init(){
	global $graphene_settings, $graphene_defaults;
	
	/* Run DB updater if needed */
	include( GRAPHENE_ROOTDIR . '/admin/db-updater.php' );
	graphene_update_db();
}
add_action( 'init', 'graphene_db_init' );


/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
function graphene_get_content_width(){
	global $graphene_settings;
	$width = graphene_grid_width( 0, 12, $graphene_settings['column_width']['two_col']['content'], $graphene_settings['column_width']['three_col']['content'] );
	return apply_filters( 'graphene_content_width', $width );
}


function graphene_set_content_width(){
	global $content_width;
	$content_width = graphene_get_content_width();
}
add_action( 'template_redirect', 'graphene_set_content_width' );


if ( ! function_exists( 'graphene_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function graphene_setup() {
	global $graphene_settings, $graphene_defaults, $content_width;
	graphene_init_settings();
	
	$content_width = graphene_get_content_width();
		
	// Add custom image sizes selectively
	$height = ( $graphene_settings['slider_height']) ? $graphene_settings['slider_height'] : 370;
	$frontpage_id = ( get_option( 'show_on_front' ) == 'posts' ) ? NULL : get_option( 'page_on_front' );
	
	if ( $graphene_settings['slider_full_width'] ) {
		$slider_width = graphene_grid_width( $graphene_settings['gutter_width']*2, 12 );
	} else {
		$column_mode = graphene_column_mode( $frontpage_id );
		
		if ( strpos( $column_mode, 'two_col' ) === 0 ) $column_mode = 'two_col';
		elseif ( strpos( $column_mode, 'three_col' ) === 0 ) $column_mode = 'three_col';
		else $column_mode = NULL;
		
		if ( $column_mode )	$slider_width = graphene_grid_width( '', $graphene_settings['column_width'][$column_mode]['content'] );
		else $slider_width = graphene_grid_width( '', 12 );
	}

	add_image_size( 'graphene_slider', apply_filters( 'graphene_slider_image_width', $slider_width ), $height, true );


	if ( get_option( 'show_on_front' ) == 'page' && !$graphene_settings['disable_homepage_panes']) {
		$pane_width = floor( $content_width / 2 );
		add_image_size( 'graphene-homepage-pane', apply_filters( 'graphene_homepage_pane_image_width', $pane_width ), apply_filters( 'graphene_homepage_pane_image_height', floor( $pane_width * 0.5 ) ), true);
	}
	
	// Add support for editor syling
	if ( ! $graphene_settings['disable_editor_style'] ){
		add_editor_style( array( 
			GRAPHENE_ROOTURI . '/bootstrap/css/bootstrap.min.css',
			'editor-style.css'
		) );
	}


	add_theme_support( 'title-tag' );
	
	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
	
	// Add support for post thumbnail / featured image
	add_theme_support( 'post-thumbnails' );

	// Make theme available for translation
	load_theme_textdomain( 'graphene', get_template_directory() . '/languages' );
	
	// Register the custom menu locations
	register_nav_menus( array( 
		'Header Menu' 		=> __( 'Header Menu', 'graphene' ),
		'secondary-menu' 	=> __( 'Secondary Menu', 'graphene' ),
		'footer-menu' 		=> __( 'Footer Menu', 'graphene' ),
	) );

	// Add support for custom background
	global $wp_version;
	$args = array(
		'default-color' 		=> 'FBFBFB',
		'default-image' 		=> GRAPHENE_ROOTURI . '/images/bg.jpg',
		'default-repeat'		=> 'no-repeat',
		'default-position-x'	=> 'center',
		'default-size'			=> 'contain'
	);
	if ( $graphene_settings['container_style'] == 'boxed' ) add_theme_support( 'custom-background', $args ); 

	/* Add support for custom header */
	define( 'HEADER_TEXTCOLOR',    apply_filters( 'graphene_header_textcolor', 'ffffff' ) );
	define( 'HEADER_IMAGE',        apply_filters( 'graphene_default_header_image', GRAPHENE_ROOTURI . '/images/headers/forest.jpg' ) );
	define( 'HEADER_IMAGE_WIDTH',  apply_filters( 'graphene_header_image_width', graphene_grid_width( $graphene_settings['gutter_width'] * 2, 12 ) ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'graphene_header_image_height', $graphene_settings['header_img_height'] ) );
	define( 'NO_HEADER_TEXT', ! apply_filters( 'graphene_header_text', true ) );

	$args = array(
		'width'               => HEADER_IMAGE_WIDTH,
		'height'              => HEADER_IMAGE_HEIGHT,
		'default-image'       => HEADER_IMAGE,
		'header-text'		  => ! NO_HEADER_TEXT,
		'default-text-color'  => HEADER_TEXTCOLOR,
		'wp-head-callback'    => '',
		'admin-head-callback' => 'graphene_admin_header_style',
	);
	$args = apply_filters( 'graphene_custom_header_args', $args );
	add_theme_support( 'custom-header', $args );
	
	set_post_thumbnail_size( $args['width'], $args['height'], true );

	// Register default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( graphene_get_default_headers() );
        
    do_action( 'graphene_setup' );
}
endif;
add_action( 'after_setup_theme', 'graphene_setup' );


if ( ! function_exists( 'graphene_get_default_headers' ) ) {
	function graphene_get_default_headers() {
		$headers = array( 
			'Forest' => array( 
				'url' 			=> '%s/images/headers/forest.jpg',
				'thumbnail_url' => '%s/images/headers/forest-thumb.jpg',
				'description' 	=> __( 'Forest', 'graphene' ), 
			),
			'Mountains' => array( 
				'url' 			=> '%s/images/headers/mountains.jpg',
				'thumbnail_url' => '%s/images/headers/mountains-thumb.jpg',
				'description' 	=> __( 'Mountains', 'graphene' ), 
			),
			'Road' => array( 
				'url' 			=> '%s/images/headers/road.jpg',
				'thumbnail_url' => '%s/images/headers/road-thumb.jpg',
				'description' 	=> __( 'Road', 'graphene' ), 
			),
			'Schematic' => array( 'url' => '%s/images/headers/schematic.jpg',
				'thumbnail_url' => '%s/images/headers/schematic-thumb.jpg',
				'description' => __( 'Header image by Syahir Hakim', 'graphene' ) ),
			
			'Flow' => array( 'url' => '%s/images/headers/flow.jpg',
				'thumbnail_url' => '%s/images/headers/flow-thumb.jpg',
				'description' => __( 'This is the default Graphene theme header image, cropped from image by Quantin Houyoux at sxc.hu', 'graphene' ) ),
			
			'Fluid' => array( 'url' => '%s/images/headers/fluid.jpg',
				'thumbnail_url' => '%s/images/headers/fluid-thumb.jpg',
				'description' => __( 'Header image cropped from image by Ilco at sxc.hu', 'graphene' ) ),
			
			'Techno' => array( 'url' => '%s/images/headers/techno.jpg',
				'thumbnail_url' => '%s/images/headers/techno-thumb.jpg',
				'description' => __( 'Header image cropped from image by Ilco at sxc.hu', 'graphene' ) ),
			
			'Fireworks' => array( 'url' => '%s/images/headers/fireworks.jpg',
				'thumbnail_url' => '%s/images/headers/fireworks-thumb.jpg',
				'description' => __( 'Header image cropped from image by Ilco at sxc.hu', 'graphene' ) ),
			
			'Nebula' => array( 'url' => '%s/images/headers/nebula.jpg',
				'thumbnail_url' => '%s/images/headers/nebula-thumb.jpg',
				'description' => __( 'Header image cropped from image by Ilco at sxc.hu', 'graphene' ) ),
			
			'Sparkle' => array( 'url' => '%s/images/headers/sparkle.jpg',
				'thumbnail_url' => '%s/images/headers/sparkle-thumb.jpg',
				'description' => __( 'Header image cropped from image by Ilco at sxc.hu', 'graphene' ) ),
		);
		
		return apply_filters( 'graphene_default_header_images', $headers );
	}
}


/**
 * Register widgetized areas
 *
 * To override graphene_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Graphene 1.0
 * @uses register_sidebar
 */
function graphene_widgets_init() {
	if (function_exists( 'register_sidebar' ) ) {
		global $graphene_settings, $graphene_defaults;
		
		register_sidebar(array( 'name' => __( 'Graphene - Sidebar One', 'graphene' ),
			'id' 			=> 'sidebar-widget-area',
			'description' 	=> __( 'The first sidebar widget area (will always be displayed on the right hand side).', 'graphene' ),
			'before_widget' => '<div id="%1$s" class="sidebar-wrap clearfix %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> "<h3>",
			'after_title' 	=> "</h3>",
		) );
                
		register_sidebar(array( 'name' => __( 'Graphene - Sidebar Two', 'graphene' ),
			'id' 			=> 'sidebar-two-widget-area',
			'description' 	=> __( 'The second sidebar widget area (will always be displayed on the left hand side).', 'graphene' ),
			'before_widget' => '<div id="%1$s" class="sidebar-wrap clearfix %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> "<h3>",
			'after_title' 	=> "</h3>",
		) );
		

		/* Get the column settings for footer widget area */
		if ( is_front_page() && $graphene_settings['alt_home_footerwidget'] ) $columns = $graphene_settings['alt_footerwidget_column'];
		else $columns = $graphene_settings['footerwidget_column'];

		if ( ! $columns ) $columns = $graphene_defaults['footerwidget_column'];

		if ( $columns == 6 ) $cols = 'col-md-2 col-sm-4';
		else $cols = 'col-sm-' . round( 12 / $columns );

		/* Register the footer widget area */
		register_sidebar(array( 'name' => __( 'Graphene - Footer', 'graphene' ),
			'id' 			=> 'footer-widget-area',
			'description' 	=> __( "The footer widget area. Leave empty to disable. Set the number of columns to display at the theme's Display Options page.", 'graphene' ),
			'before_widget' => '<div id="%1$s" class="sidebar-wrap clearfix %2$s ' . $cols . '">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> "<h3>",
			'after_title' 	=> "</h3>",
		) );
		
		/**
		 * Register alternate widget areas to be displayed on the front page, if enabled
		 *
		 * @package Graphene
		 * @subpackage Graphene
		 * @since Graphene 1.0.8
		*/
		if ( $graphene_settings['alt_home_sidebar']) {
			register_sidebar( array( 'name' => __( 'Graphene - Sidebar One (Front Page)', 'graphene' ),
				'id' => 'home-sidebar-widget-area',
				'description' => __( 'The first sidebar widget area that will only be displayed on the front page.', 'graphene' ),
				'before_widget' => '<div id="%1$s" class="sidebar-wrap clearfix %2$s">',
				'after_widget' => '</div>',
				'before_title' => "<h3>",
				'after_title' => "</h3>",
			) );
			
			register_sidebar(array( 'name' => __( 'Graphene - Sidebar Two (Front Page)', 'graphene' ),
				'id' => 'home-sidebar-two-widget-area',
				'description' => __( 'The second sidebar widget area that will only be displayed on the front page.', 'graphene' ),
				'before_widget' => '<div id="%1$s" class="sidebar-wrap clearfix %2$s">',
				'after_widget' => '</div>',
				'before_title' => "<h3>",
				'after_title' => "</h3>",
			) );
		}
		
		if ( $graphene_settings['alt_home_footerwidget']) {
			register_sidebar(array( 'name' => __( 'Graphene - Footer (Front Page)', 'graphene' ),
				'id' => 'home-footer-widget-area',
				'description' => __( "The footer widget area that will only be displayed on the front page. Leave empty to disable. Set the number of columns to display at the theme's Display Options page.", 'graphene' ),
				'before_widget' => '<div id="%1$s" class="sidebar-wrap clearfix %2$s ' . $cols . '">',
				'after_widget' => '</div>',
				'before_title' => "<h3>",
				'after_title' => "</h3>",
			) );
		}
		
		/* Header widget area */
		if ( $graphene_settings['enable_header_widget']) :
			register_sidebar(array( 'name' => __( 'Graphene - Header', 'graphene' ),
				'id' => 'header-widget-area',
				'description' => __("The header widget area.", 'graphene' ),
				'before_widget' => '<div id="%1$s" class="sidebar-wrap clearfix %2$s">',
				'after_widget' => '</div>',
				'before_title' => "<h3>",
				'after_title' => "</h3>",
			) );
		endif;
                
		/* Action hooks widget areas */
		if ( count( $graphene_settings['widget_hooks'] ) > 0 ) {
			$available_hooks = graphene_get_action_hooks( true );
			
			foreach ($graphene_settings['widget_hooks'] as $hook) {
				if (in_array($hook, $available_hooks)) {
					register_sidebar(array(
						'name' => ucwords( str_replace('_', ' ', $hook) ),
						'id' => $hook,
						'description' => sprintf( __("Dynamically added widget area. This widget area is attached to the %s action hook.", 'graphene'), "'$hook'" ),
						'before_widget' => '<div id="%1$s" class="sidebar-wrap clearfix %2$s">',
						'after_widget' => '</div>',
						'before_title' => "<h3>",
						'after_title' => "</h3>",
					));
					// to display the widget dynamically attach the dynamic method
					add_action( $hook, 'graphene_display_dynamic_widget_hooks' );
				}
				
			}                    
		}
	}
	
	do_action( 'graphene_widgets_init' );
}
add_action( 'widgets_init', 'graphene_widgets_init' );


/**
 * Display a dynamic widget area, this is hooked to the user selected do_action() hooks available in Graphene.
 * @global array $graphene_settings 
 */
function graphene_display_dynamic_widget_hooks(){
    global $graphene_settings;
	
    // to find the current action
    $actionhook_id = current_filter();
    if ( in_array( $actionhook_id, $graphene_settings['widget_hooks'])  && is_active_sidebar( $actionhook_id ) ) : ?>
    <div class="graphene-dynamic-widget" id="graphene-dynamic-widget-<?php echo $actionhook_id; ?>">
        <?php dynamic_sidebar( $actionhook_id ); ?>
    </div>
    <?php endif;
}