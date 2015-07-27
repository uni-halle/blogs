<?php
/**
 * Generate functions and definitions
 *
 * @package GeneratePress
 */
	
define( 'GENERATE_VERSION', '1.3.06');
define( 'GENERATE_URI', get_template_directory_uri() );
define( 'GENERATE_DIR', get_template_directory() );

add_action( 'after_setup_theme', 'generate_setup' );
if ( ! function_exists( 'generate_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function generate_setup() 
{

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Generate, use a find and replace
	 * to change 'generate' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'generate', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'generate' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'status' ) );
	
	/**
	 * Enable support for WooCommerce
	 */
	add_theme_support( 'woocommerce' );
	
	/**
	 * Enable support for <title> tag
	 */
	add_theme_support( 'title-tag' );
	
	/*
	 * Add HTML5 theme support
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );
	
	/**
	 * Set the content width to something large
	 * We set a more accurate width in generate_smart_content_width()
	 */
	global $content_width;
	if ( ! isset( $content_width ) )
		$content_width = 1200; /* pixels */
		
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 */
	add_editor_style( 'inc/css/editor-style.css' );

}
endif; // generate_setup

/**
 * Set default options
 */
function generate_get_defaults()
{
	$generate_defaults = array(
		'hide_title' => '',
		'hide_tagline' => '',
		'logo' => '',
		'container_width' => '1100',
		'header_layout_setting' => 'fluid-header',
		'nav_alignment_setting' => 'left',
		'header_alignment_setting' => 'left',
		'nav_layout_setting' => 'fluid-nav',
		'nav_position_setting' => 'nav-below-header',
		'nav_search' => 'disable',
		'content_layout_setting' => 'separate-containers',
		'layout_setting' => 'right-sidebar',
		'blog_layout_setting' => 'right-sidebar',
		'single_layout_setting' => 'right-sidebar',
		'post_content' => 'full',
		'footer_layout_setting' => 'fluid-footer',
		'footer_widget_setting' => '3',
		'background_color' => '#efefef',
		'text_color' => '#3a3a3a',
		'link_color' => '#1e73be',
		'link_color_hover' => '#000000',
		'link_color_visited' => '',
	);
	
	return apply_filters( 'generate_option_defaults', $generate_defaults );
}

/**
 * Register widgetized area and update sidebar with default widgets
 */
add_action( 'widgets_init', 'generate_widgets_init' );
function generate_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Right Sidebar', 'generate' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget inner-padding %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => apply_filters( 'generate_start_widget_title', '<h4 class="widget-title">' ),
		'after_title'   => apply_filters( 'generate_end_widget_title', '</h4>' ),
	) );
	register_sidebar( array(
		'name'          => __( 'Left Sidebar', 'generate' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget inner-padding %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => apply_filters( 'generate_start_widget_title', '<h4 class="widget-title">' ),
		'after_title'   => apply_filters( 'generate_end_widget_title', '</h4>' ),
	) );
	register_sidebar( array(
		'name'          => __( 'Header', 'generate' ),
		'id'            => 'header',
		'before_widget' => '<aside id="%1$s" class="widget inner-padding %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => apply_filters( 'generate_start_widget_title', '<h4 class="widget-title">' ),
		'after_title'   => apply_filters( 'generate_end_widget_title', '</h4>' ),
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget 1', 'generate' ),
		'id'            => 'footer-1',
		'before_widget' => '<aside id="%1$s" class="widget inner-padding %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => apply_filters( 'generate_start_widget_title', '<h4 class="widget-title">' ),
		'after_title'   => apply_filters( 'generate_end_widget_title', '</h4>' ),
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget 2', 'generate' ),
		'id'            => 'footer-2',
		'before_widget' => '<aside id="%1$s" class="widget inner-padding %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => apply_filters( 'generate_start_widget_title', '<h4 class="widget-title">' ),
		'after_title'   => apply_filters( 'generate_end_widget_title', '</h4>' ),
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget 3', 'generate' ),
		'id'            => 'footer-3',
		'before_widget' => '<aside id="%1$s" class="widget inner-padding %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => apply_filters( 'generate_start_widget_title', '<h4 class="widget-title">' ),
		'after_title'   => apply_filters( 'generate_end_widget_title', '</h4>' ),
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget 4', 'generate' ),
		'id'            => 'footer-4',
		'before_widget' => '<aside id="%1$s" class="widget inner-padding %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => apply_filters( 'generate_start_widget_title', '<h4 class="widget-title">' ),
		'after_title'   => apply_filters( 'generate_end_widget_title', '</h4>' ),
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget 5', 'generate' ),
		'id'            => 'footer-5',
		'before_widget' => '<aside id="%1$s" class="widget inner-padding %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => apply_filters( 'generate_start_widget_title', '<h4 class="widget-title">' ),
		'after_title'   => apply_filters( 'generate_end_widget_title', '</h4>' ),
	) );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Build the navigation
 */
require get_template_directory() . '/inc/navigation.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load element classes
 */
require get_template_directory() . '/inc/element-classes.php';

/**
 * Load metaboxes
 */
require get_template_directory() . '/inc/metaboxes.php';

/**
 * Load options
 */
require get_template_directory() . '/inc/options.php';

/**
 * Load add-on options
 */
require get_template_directory() . '/inc/add-ons.php';

/**
 * Enqueue scripts and styles
 */
add_action( 'wp_enqueue_scripts', 'generate_scripts' );
function generate_scripts() {

	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	// Stylesheets
	wp_enqueue_style( 'generate-style-grid', get_template_directory_uri() . '/css/unsemantic-grid.min.css', false, GENERATE_VERSION, 'all' );
	wp_enqueue_style( 'generate-style', get_template_directory_uri() . '/style.css', false, GENERATE_VERSION, 'all' );
	wp_enqueue_style( 'generate-mobile-style', get_template_directory_uri() . '/css/mobile.css', false, GENERATE_VERSION, 'all' );
	wp_add_inline_style( 'generate-style', generate_base_css() );
	if ( is_child_theme() ) :
		wp_enqueue_style( 'generate-child', get_stylesheet_uri(), true, filemtime( get_stylesheet_directory() . '/style.css' ), 'all' );
	endif;
	wp_enqueue_style( 'superfish', get_template_directory_uri() . '/css/superfish.css', false, GENERATE_VERSION, 'all' );
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css', false, '4.3.0', 'all' );

	// Scripts
	wp_enqueue_script( 'generate-navigation', get_template_directory_uri() . '/js/navigation.js', array(), GENERATE_VERSION, true );
	wp_enqueue_script( 'superfish', get_template_directory_uri() . '/js/superfish.min.js', array('jquery'), GENERATE_VERSION, true );
	wp_enqueue_script( 'hoverIntent', get_template_directory_uri() . '/js/hoverIntent.js', array('superfish'), GENERATE_VERSION, true );

	if ( 'enable' == $generate_settings['nav_search'] ) {
		wp_enqueue_script( 'generate-navigation-search', get_template_directory_uri() . '/js/navigation-search.js', array('jquery'), GENERATE_VERSION, true );
	}
	
	if ( 'nav-left-sidebar' == $generate_settings['nav_position_setting'] || 'nav-right-sidebar' == $generate_settings['nav_position_setting'] ) {
		wp_enqueue_script( 'generate-move-navigation', get_template_directory_uri() . '/js/move-navigation.js', array('jquery'), GENERATE_VERSION, true );
	}
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}

/**
 * Get the layout for the current page
 */
function generate_get_layout()
{
	// Get current post
	global $post;
	
	// Get Customizer options
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	// Set up the layout variable for pages
	$layout = $generate_settings['layout_setting'];
	
	// Get the individual page/post sidebar metabox value
	$layout_meta = ( isset( $post ) ) ? get_post_meta( $post->ID, '_generate-sidebar-layout-meta', true ) : '';
	
	// Set up BuddyPress variable
	$buddypress = false;
	if ( function_exists( 'is_buddypress' ) ) :
		$buddypress = ( is_buddypress() ) ? true : false;
	endif;

	// If we're on the single post page
	// And if we're not on a BuddyPress page - fixes a bug where BP thinks is_single() is true
	if ( is_single() && ! $buddypress ) :
		$layout = null;
		$layout = $generate_settings['single_layout_setting'];
	endif;

	// If the metabox is set, use it instead of the global settings
	if ( '' !== $layout_meta && false !== $layout_meta ) :
		$layout = $layout_meta;
	endif;
	
	// If we're on the blog, archive, attachment etc..
	if ( is_home() || is_archive() || is_search() || is_attachment() || is_tax() ) :
		$layout = null;
		$layout = $generate_settings['blog_layout_setting'];
	endif;
	
	// Finally, return the layout
	return apply_filters( 'generate_sidebar_layout', $layout );
}

/**
 * Get the footer widgets for the current page
 */
function generate_get_footer_widgets()
{
	// Get current post
	global $post;
	
	// Get Customizer options
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	// Set up the footer widget variable
	$widgets = $generate_settings['footer_widget_setting'];
	
	// Get the individual footer widget metabox value
	$widgets_meta = ( isset( $post ) ) ? get_post_meta( $post->ID, '_generate-footer-widget-meta', true ) : '';
	
	// If we're not on a single page or post, the metabox hasn't been set
	if ( ! is_singular() ) :
		$widgets_meta = '';
	endif;
	
	// If we have a metabox option set, use it
	if ( '' !== $widgets_meta && false !== $widgets_meta ) :
		$widgets = $widgets_meta;
	endif;
	
	// Finally, return the layout
	return apply_filters( 'generate_footer_widgets', $widgets );
}

/**
 * Construct the sidebars
 * @since 0.1
 */
add_action('generate_sidebars','generate_contruct_sidebars');
function generate_contruct_sidebars()
{
	// Get the layout
	$layout = generate_get_layout();
	
	// When to show the right sidebar
	$rs = array('right-sidebar','both-sidebars','both-right','both-left');

	// When to show the left sidebar
	$ls = array('left-sidebar','both-sidebars','both-right','both-left');
	
	// If left sidebar, show it
	if ( in_array( $layout, $ls ) ) :
		get_sidebar('left'); 
	endif;
	
	// If right sidebar, show it
	if ( in_array( $layout, $rs ) ) :
		get_sidebar(); 
	endif;
	
}

add_action('generate_credits','generate_add_footer_info');
function generate_add_footer_info()
{
	?>
	<span class="copyright"><?php _e('Copyright','generate');?> &copy; <?php echo date('Y'); ?></span> <?php do_action('generate_copyright_line');?>
	<?php
}

add_action('generate_copyright_line','generate_add_login_attribution');
function generate_add_login_attribution()
{
	?>
	&#x000B7; <a href="<?php echo esc_url('http://generatepress.com');?>" target="_blank" title="<?php _e('GeneratePress','generate');?>" itemprop="url"><?php _e('GeneratePress','generate');?></a> &#x000B7; <a href="http://wordpress.org" target="_blank" title="<?php _e('Proudly powered by WordPress','generate');?>"><?php _e('WordPress','generate');?></a>
	<?php
}

/**
 * Generate the CSS in the <head> section using the Theme Customizer
 * @since 0.1
 */
function generate_base_css()
{
	
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	// Start the magic
	$visual_css = array (
	
		// Body CSS
		'body'  => array(
			'background-color' => $generate_settings['background_color'],
			'color' => $generate_settings['text_color']
		),
		
		// Link CSS
		'a, a:visited' => array(
			'color'				=> $generate_settings['link_color'],
			'text-decoration' 	=> 'none'
		),
		
		// Visited link color if specified
		'a:visited' => array(
			'color' 			=> ( !empty( $generate_settings['link_color_visited'] ) ) ? $generate_settings['link_color_visited'] : null,
		),
		
		// Link hover
		'a:hover, a:focus, a:active' => array(
			'color' 			=> $generate_settings['link_color_hover'],
			'text-decoration' 	=> null
		),
		
		// Grid container
		'body .grid-container' => array(
			'max-width' => $generate_settings['container_width'] . 'px'
		)
		
	);
	
	// Output the above CSS
	$output = '';
	foreach($visual_css as $k => $properties) {
		if(!count($properties))
			continue;

		$temporary_output = $k . ' {';
		$elements_added = 0;

		foreach($properties as $p => $v) {
			if(empty($v))
				continue;

			$elements_added++;
			$temporary_output .= $p . ': ' . $v . '; ';
		}

		$temporary_output .= "}";

		if($elements_added > 0)
			$output .= $temporary_output;
	}

	$output = str_replace(array("\r", "\n"), '', $output);
	return $output;
}

/** 
 * Add viewport to wp_head
 * Decide whether mobile viewport should be added or fixed width viewport
 * @since 1.1.0
 */
add_action('wp_head','generate_add_viewport');
function generate_add_viewport()
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	if ( !defined( 'GENERATE_DISABLE_MOBILE' ) ) :
		echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
	else :
		echo '<meta name="viewport" content="width=' . $generate_settings['container_width'] . 'px">';
	endif;
}

/** 
 * Destroy mobile responsive functionality
 * Only run if GENERATE_DISABLE_MOBILE constant is defined
 * @since 1.1.0
 */
add_action( 'wp_enqueue_scripts', 'generate_dequeue_mobile_scripts', 100 );
function generate_dequeue_mobile_scripts() {

	if ( !defined( 'GENERATE_DISABLE_MOBILE' ) )
		return;
		
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);

	// Remove mobile stylesheets and scripts
	wp_dequeue_style( 'generate-mobile-style' );
	wp_dequeue_style( 'generate-style-grid' );
	wp_dequeue_script( 'generate-navigation' );
	
	// Add in mobile grid (no min-width on line 100)
	wp_enqueue_style( 'generate-style-grid-no-mobile', get_template_directory_uri() . '/css/unsemantic-grid-no-mobile.css', false, GENERATE_VERSION, 'all' );
  
   // Add necessary styles to kill mobile resposive features
	$styles = 'body .grid-container {width:' . $generate_settings['container_width'] . 'px;max-width:' . $generate_settings['container_width'] . 'px}';
	$styles .= '.menu-toggle {display:none;}';
	wp_add_inline_style( 'generate-style', $styles );
}

/** 
 * Add compatibility for IE8 and lower
 * @since 1.1.9
 */
add_action('wp_head','generate_ie_compatibility');
function generate_ie_compatibility()
{
?>
	<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri();?>/js/html5shiv.js"></script>
		<link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/css/ie.min.css" />
	<![endif]-->
<?php
}

if ( ! function_exists( 'generate_remove_caption_padding' ) ) :
/**
 * Remove WordPress's default padding on images with captions
 *
 * @param int $width Default WP .wp-caption width (image width + 10px)
 * @return int Updated width to remove 10px padding
 */
add_filter( 'img_caption_shortcode_width', 'generate_remove_caption_padding' );
function generate_remove_caption_padding( $width ) {
	return $width - 10;
}
endif;

if ( ! function_exists( 'generate_smart_content_width' ) ) :
/**
 * Set the $content_width depending on layout of current page
 * Hook into "wp" so we have the correct layout setting from generate_get_layout()
 * Hooking into "after_setup_theme" doesn't get the correct layout setting
 */
add_action( 'wp', 'generate_smart_content_width' );
function generate_smart_content_width()
{

	global $content_width, $post;
	
	// Get Customizer options
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	// Get sidebar widths
	$right_sidebar_width = apply_filters( 'generate_right_sidebar_width', '25' );
	$left_sidebar_width = apply_filters( 'generate_left_sidebar_width', '25' );
	
	// Get the layout
	$layout = generate_get_layout();
	
	// Find the real content width
	if ( 'left-sidebar' == $layout ) {
		// If left sidebar is present
		$content_width = $generate_settings['container_width'] * ( ( 100 - $left_sidebar_width ) / 100 );
	} elseif ( 'right-sidebar' == $layout ) {
		// If right sidebar is present
		$content_width = $generate_settings['container_width'] * ( ( 100 - $right_sidebar_width ) / 100 );
	} elseif ( 'no-sidebar' == $layout ) {
		// If no sidebars are present
		$content_width = $generate_settings['container_width'];
	} else {
		// If both sidebars are present
		$content_width = $generate_settings['container_width'] * ( ( 100 - ( $left_sidebar_width + $right_sidebar_width ) ) / 100 );	
	}
}
endif;