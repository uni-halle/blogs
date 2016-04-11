<?php
/**
 * Catch Box functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, catchbox_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'catchbox_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 1.0
 */


if ( ! function_exists( 'catchbox_content_width' ) ) :
/**
 * Change the content width based on the Theme Settings and Page/Post Settings
 */
function catchbox_content_width() {

	$layout = catchbox_get_theme_layout();

	$content_width = 530;

	if ( is_page_template( 'page-fullwidth.php' ) ) {
		$content_width = 880; /* pixels */
	}
	else if ( is_page_template( 'page-disable-sidebar.php' ) ) {
		$content_width = 660; /* pixels */
	}
	else if ( $layout == 'content-onecolumn' || is_page_template( 'page-onecolumn.php' ) ) {
		$content_width = 620; /* pixels */
	}
}
endif; // catchbox_content_width
add_action( 'after_setup_theme', 'catchbox_content_width', 0 );


/**
 * Tell WordPress to run catchbox_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'catchbox_setup' );

if ( ! function_exists( 'catchbox_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override catchbox_setup() in a child theme, add your own catchbox_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,custom headers and backgrounds.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Catch Box 1.0
 */
function catchbox_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Catch Box, use a find and replace
	 * to change 'twentysixteen' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'catch-box', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// The next four constants set how Catch Box supports custom headers.

	// The default header text color
	define( 'HEADER_TEXTCOLOR', '000' );

	// By leaving empty, we allow for random image rotation.
	define( 'HEADER_IMAGE', '' );

	// The height and width of your custom header used for site logo.
	// Add a filter to catchbox_header_image_width and catchbox_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'catchbox_header_image_width', 300 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'catchbox_header_image_height', 125 ) );

	// We'll be using post thumbnails for custom header images for logos.
	// We want them to be the size of the header image that we just defined
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Add Catch Box's custom image sizes
	add_image_size( 'featured-header', HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true ); // Used for logo (header) images

	//disable old image size for featued posts add_image_size( 'featured-slider', 560, 270, true );
	add_image_size( 'featured-slider', 644, 320, true ); // Used for featured posts if a large-feature doesn't exist

	//@remove Remove if block when WordPress 4.8 is released
	if( !function_exists( 'has_custom_logo' ) ) {
		// Add support for custom header
		add_theme_support( 'custom-header', array(
			// Header image random rotation default
			'random-default'			=> false,
			// Header image flex width
			'flex-width'      			=> true,
			// Header image flex height
			'flex-height'				=> true,
			// Template header style callback
			'wp-head-callback'			=> 'catchbox_header_style',
			// Admin header style callback
			'admin-head-callback'		=> 'catchbox_admin_header_style',
			// Admin preview style callback
			'admin-preview-callback'	=> 'catchbox_admin_header_image'
		) );

		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		register_default_headers( array(
			'wheel' => array(
				'url' => '%s/images/headers/garden.jpg',
				'thumbnail_url' => '%s/images/headers/garden-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Garden', 'catch-box' )
			),
			'shore' => array(
				'url' => '%s/images/headers/flower.jpg',
				'thumbnail_url' => '%s/images/headers/flower-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Flower', 'catch-box' )
			),
			'trolley' => array(
				'url' => '%s/images/headers/mountain.jpg',
				'thumbnail_url' => '%s/images/headers/mountain-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Mountain', 'catch-box' )
			),
		) );
	}

	// Add support for custom backgrounds
	add_theme_support( 'custom-background' );

	// This theme uses wp_nav_menu() in three locations.
	register_nav_menus(array(
		'primary' 	=> __( 'Primary Menu', 'catch-box' ),
	   	'secondary'	=> __( 'Secondary Menu', 'catch-box' ),
		'footer'	=> __( 'Footer Menu', 'catch-box' )
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style();




	/**
     * This feature enables Jetpack plugin Infinite Scroll
     */
    add_theme_support( 'infinite-scroll', array(
		'type'           => 'click',
        'container'      => 'content',
        'footer_widgets' => array( 'sidebar-2', 'sidebar-3', 'sidebar-4' ),
        'footer'         => 'page',
    ) );

    //@remove Remove check when WordPress 4.8 is released
	if ( function_exists( 'has_custom_logo' ) ) {
		/**
		* Setup Custom Logo Support for theme
		* Supported from WordPress version 4.5 onwards
		* More Info: https://make.wordpress.org/core/2016/03/10/custom-logo/
		*/
		add_theme_support( 'custom-logo',
			array(
		   		'height'      => 300,
	 			'width'       => 125,
	 			'flex-height' => true,
			)
		);
	}

}
endif; // catchbox_setup


if ( ! function_exists( 'catchbox_get_theme_layout' ) ) :
	/**
	 * Returns Theme Layout prioritizing the meta box layouts
	 *
	 * @uses  get_options
	 *
	 * @action wp_head
	 *
	 * @since Catch Box 4.6
	 */
	function catchbox_get_theme_layout() {
		$id = '';

		global $post, $wp_query;

	    // Front page displays in Reading Settings
		$page_on_front  = get_option('page_on_front') ;
		$page_for_posts = get_option('page_for_posts');

		// Get Page ID outside Loop
		$page_id = $wp_query->get_queried_object_id();

		// Blog Page or Front Page setting in Reading Settings
		if ( $page_id == $page_for_posts || $page_id == $page_on_front ) {
	        $id = $page_id;
	    }
	    else if ( is_singular() ) {
	 		if ( is_attachment() ) {
				$id = $post->post_parent;
			}
			else {
				$id = $post->ID;
			}
		}

		//Get appropriate metabox value of layout
		if ( '' != $id ) {
			$layout = get_post_meta( $id, 'catchbox-sidebarlayout', true );
		}
		else {
			$layout = 'default';
		}

		//Load options data
		$options = catchbox_get_theme_options();

		//check empty and load default
		if ( empty( $layout ) || 'default' == $layout ) {
			$layout = $options['theme_layout'];
		}

		//Condition checks for backward compatibility
		if ( 'content-sidebar' ==$layout ) {
			$layout = 'right-sidebar';
		}
		else if ( 'sidebar-content' ==$layout ) {
			$layout = 'left-sidebar';
		}
		else if ( 'content-onecolumn' ==$layout ) {
			$layout = 'no-sidebar-one-column';
		}

		return $layout;
	}
endif; //catchbox_get_theme_layout


/**
 * Migrate Logo to New WordPress core Custom Logo
 *
 *
 * Runs if version number saved in theme_mod "logo_version" doesn't match current theme version.
 */
function catchbox_logo_migrate() {
	$ver = get_theme_mod( 'logo_version', false );

	// Return if update has already been run
	if ( version_compare( $ver, '3.6' ) >= 0 ) {
		return;
	}

	// If a logo has been set previously, update to use logo feature introduced in WordPress 4.5
	if ( function_exists( 'the_custom_logo' ) ) {
		$header_image = get_header_image();

		if ( ! empty( $header_image ) ) {
			// Since previous logo was stored a URL, convert it to an attachment ID
			$logo = attachment_url_to_postid( $header_image );

			if ( is_int( $logo ) ) {
				set_theme_mod( 'custom_logo', $logo );
			}
		}

  		// Update to match logo_version so that script is not executed continously
		set_theme_mod( 'logo_version', '3.6' );
	}
}
add_action( 'after_setup_theme', 'catchbox_logo_migrate' );


/**
 * Migrate Custom Favicon to WordPress core Site Icon
 *
 * Runs if version number saved in theme_mod "site_icon_version" doesn't match current theme version.
 */
function catchbox_site_icon_migrate() {
	$ver = get_theme_mod( 'site_icon_version', false );

	//Return if update has already been run
	if ( version_compare( $ver, '3.6' ) >= 0 ) {
		return;
	}

	/**
	 * Get Theme Options Values
	 */
	$options = catchbox_get_theme_options();

   	// If a logo has been set previously, update to use logo feature introduced in WordPress 4.5
	if ( function_exists( 'has_site_icon' ) ) {
		if( isset( $options['fav_icon'] ) && '' != $options['fav_icon'] ) {
			// Since previous logo was stored a URL, convert it to an attachment ID
			$site_icon = attachment_url_to_postid( $options['fav_icon'] );

			if ( is_int( $site_icon ) ) {
				update_option( 'site_icon', $site_icon );
			}
		}

	  	// Update to match site_icon_version so that script is not executed continously
		set_theme_mod( 'site_icon_version', '3.6' );
	}
}
add_action( 'after_setup_theme', 'catchbox_site_icon_migrate' );


// Load up our theme options page and related code.
require( get_template_directory() . '/inc/theme-options.php' );

// Grab Catch Box's Adspace Widget.
require( get_template_directory() . '/inc/widgets.php' );

/**
 * Custom Menus
 */
require get_template_directory() . '/inc/catchbox-menus.php';


/**
 * Custom Metabox
 */
require get_template_directory() . '/inc/catchbox-metabox.php';


if ( ! function_exists( 'catchbox_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @since Catch Box 1.0
 */
function catchbox_header_style() {

	$text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail.
	if ( $text_color == HEADER_TEXTCOLOR )
		return;

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $text_color ) :
	?>
		#site-title,
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php

		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // catchbox_header_style


if ( ! function_exists( 'catchbox_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @since Catch Box 1.0
 */
function catchbox_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
		font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
	}
	#headimg h1 {
		margin: 0;
	}
	#headimg h1 a {
		font-size: 32px;
		line-height: 36px;
		text-decoration: none;
	}
	#desc {
		font-size: 14px;
		line-height: 23px;
		padding: 0 0 3em;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>

	#headimg img {
		height: auto;
		max-width: 100%;
	}
	</style>
<?php
}
endif; // catchbox_admin_header_style


if ( ! function_exists( 'catchbox_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @since Catch Box 1.0
 */
function catchbox_admin_header_image() { ?>
	<div id="headimg">
		<?php
		$color = get_header_textcolor();
		$image = get_header_image();
		if ( $color && $color != 'blank' )
			$style = ' style="color:#' . $color . '"';
		else
			$style = ' style="display:none"';
		?>

		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // catchbox_admin_header_image


/**
 * Sets the post excerpt length.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function catchbox_excerpt_length( $length ) {
	$options = catchbox_get_theme_options();
	if( empty( $options['excerpt_length'] ) )
		$options = catchbox_get_default_theme_options();

	$length = $options['excerpt_length'];
	return $length;
}
add_filter( 'excerpt_length', 'catchbox_excerpt_length' );


if ( ! function_exists( 'catchbox_continue_reading_link' ) ) :
/**
 * Returns a "Continue Reading" link for excerpts
 */
function catchbox_continue_reading_link() {
	return ' <a class="more-link" href="'. esc_url( get_permalink() ) . '">' . sprintf(
					__( 'Continue reading %s', 'catch-box' ),
					'<span class="screen-reader-text">  '.get_the_title().'</span>'
				).
				'<span class="meta-nav">&rarr;</span></a>';
}
endif;


/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and catchbox_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function catchbox_auto_excerpt_more( $more ) {
	return catchbox_continue_reading_link();
}
add_filter( 'excerpt_more', 'catchbox_auto_excerpt_more' );


/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function catchbox_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= catchbox_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'catchbox_custom_excerpt_more' );


if ( ! function_exists( 'catchbox_widgets_init' ) ):
/**
 * Register our sidebars and widgetized areas.
 *
 * @since Catch Box 1.0
 */
function catchbox_widgets_init() {

	register_widget( 'catchbox_adwidget' );

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'catch-box' ),
		'id' => 'sidebar-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => "</section>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'catch-box' ),
		'id' => 'sidebar-2',
		'description' => __( 'An optional widget area for your site footer', 'catch-box' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'catch-box' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'catch-box' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Three', 'catch-box' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'catch-box' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
endif; // catchbox_widgets_init

add_action( 'widgets_init', 'catchbox_widgets_init' );


if ( ! function_exists( 'catchbox_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function catchbox_content_nav( $nav_id ) {
	global $wp_query;

	/**
	 * Check Jetpack Infinite Scroll
	 * if it's active then disable pagination
	 */
	if ( class_exists( 'Jetpack', false ) ) {
		$jetpack_active_modules = get_option('jetpack_active_modules');
		if ( $jetpack_active_modules && in_array( 'infinite-scroll', $jetpack_active_modules ) ) {
			return false;
		}
	}

	if ( $wp_query->max_num_pages > 1 ) {  ?>
		<nav id="<?php echo $nav_id; ?>">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'catch-box' ); ?></h3>
			<?php if ( function_exists('wp_pagenavi' ) )  {
				wp_pagenavi();
			}
			elseif ( function_exists('wp_page_numbers' ) ) {
				wp_page_numbers();
			}
			else { ?>
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'catch-box' ) ); ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'catch-box' ) ); ?></div>
			<?php
			} ?>
		</nav><!-- #nav -->
		<?php
	}

}
endif; // catchbox_content_nav


if ( ! function_exists( 'catchbox_content_query_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function catchbox_content_query_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) { ?>
		<nav id="<?php echo $nav_id; ?>">
        	<h3 class="assistive-text"><?php _e( 'Post navigation', 'catch-box' ); ?></h3>
			<?php if ( function_exists('wp_pagenavi' ) )  {
                wp_pagenavi();
            }
            elseif ( function_exists('wp_page_numbers' ) ) {
                wp_page_numbers();
            }
            else { ?>
            	<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'catch-box' ) ); ?></div>
                <div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'catch-box' ) ); ?></div>
            <?php
            } ?>
		</nav><!-- #nav -->
	<?php
	}
}
endif; // catchbox_content_nav


/**
 * Return the URL for the first link found in the post content.
 *
 * @since Catch Box 1.0
 * @return string|bool URL or false when no link is present.
 */
function catchbox_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}


if ( ! function_exists( 'catchbox_footer_sidebar_class' ) ):
/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function catchbox_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-2' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}
endif; // catchbox_footer_sidebar_class


if ( ! function_exists( 'catchbox_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own catchbox_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Catch Box 1.0
 */
function catchbox_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'catch-box' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'catch-box' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'catch-box' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'catch-box' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'catch-box' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'catch-box' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'catch-box' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // catchbox_comment


if ( ! function_exists( 'catchbox_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own catchbox_posted_on to override in a child theme
 *
 * @since Catch Box 1.0
 */
function catchbox_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date updated" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'catch-box' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'catch-box' ), get_the_author() ) ),
		get_the_author()
	);
}
endif; // catchbox_posted_on


if ( ! function_exists( 'catchbox_body_classes' ) ) :
/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 * @since Catch Box 1.0
 */
function catchbox_body_classes( $classes ) {
	$options = catchbox_get_theme_options();
	$layout = $options['theme_layout'];
	if ( function_exists( 'is_multi_author' ) && !is_multi_author() ) {
		$classes[] = 'single-author';
	}

	$layout = catchbox_get_theme_layout();

	if ( $layout == 'right-sidebar' && !is_page_template( 'page-disable-sidebar.php' ) && !is_page_template( 'page-fullwidth.php' )  && !is_page_template( 'page-onecolumn.php' ) ) {
		$classes[] = 'right-sidebar';
	}
	elseif ( $layout == 'left-sidebar' && !is_page_template( 'page-disable-sidebar.php' ) && !is_page_template( 'page-fullwidth.php' )  && !is_page_template( 'page-onecolumn.php' ) ) {
		$classes[] = 'left-sidebar';
	}
	elseif ( $layout == 'no-sidebar-one-column' || is_page_template( 'page-onecolumn.php' ) && !is_page_template( 'page-disable-sidebar.php' ) && !is_page_template( 'page-fullwidth.php' ) ) {
		$classes[] = 'no-sidebar one-column';
	}
	elseif ( is_page_template( 'page-disable-sidebar.php' ) || is_attachment() ) {
		$classes[] = 'no-sidebar';
	}
	elseif ( is_page_template( 'page-fullwidth.php' ) || is_attachment() ) {
		$classes[] = 'no-sidebar full-width';
	}

	if ( empty ( $options ['enable_menus'] ) ) {
		$classes[] = 'one-menu';
	}

	return $classes;
}
endif; // catchbox_body_classes

add_filter( 'body_class', 'catchbox_body_classes' );


/**
 * Adds in post ID when viewing lists of posts
 * This will help the admin to add the post ID in featured slider
 *
 * @param mixed $post_columns
 * @return post columns
 */
function catchbox_post_id_column( $post_columns ) {
	$beginning = array_slice( $post_columns, 0 ,1 );
	$beginning[ 'postid' ] = __( 'ID', 'catch-box'  );
	$ending = array_slice( $post_columns, 1 );
	$post_columns = array_merge( $beginning, $ending );
	return $post_columns;
}
add_filter( 'manage_posts_columns', 'catchbox_post_id_column' );

function catchbox_posts_id_column( $col, $val ) {
	if( $col == 'postid' ) echo $val;
}
add_action( 'manage_posts_custom_column', 'catchbox_posts_id_column', 10, 2 );

function catchbox_posts_id_column_css() {
	echo '<style type="text/css">#postid { width: 50px; }</style>';
}
add_action( 'admin_head-edit.php', 'catchbox_posts_id_column_css' );


/**
 * Function to pass the variables for php to js file.
 * This funcition passes the slider effect variables.
 */
function catchbox_pass_slider_value() {
	$options = catchbox_get_theme_options();
	if( !isset( $options[ 'transition_effect' ] ) ) {
		$options[ 'transition_effect' ] = "fade";
	}
	if( !isset( $options[ 'transition_delay' ] ) ) {
		$options[ 'transition_delay' ] = 4;
	}
	if( !isset( $options[ 'transition_duration' ] ) ) {
		$options[ 'transition_duration' ] = 1;
	}
	$transition_effect = $options[ 'transition_effect' ];
	$transition_delay = $options[ 'transition_delay' ] * 1000;
	$transition_duration = $options[ 'transition_duration' ] * 1000;
	wp_localize_script(
		'catchbox_slider',
		'js_value',
		array(
			'transition_effect' => $transition_effect,
			'transition_delay' => $transition_delay,
			'transition_duration' => $transition_duration
		)
	);
}//catchbox_pass_slider_value


if ( ! function_exists( 'catchbox_sliders' ) ) :
/**
 * This function to display featured posts on index.php
 *
 * @get the data value from theme options
 * @displays on the index
 *
 * @useage Featured Image, Title and Content of Post
 *
 * @uses set_transient and delete_transient
 */
function catchbox_sliders() {
	global $post;

	//delete_transient( 'catchbox_sliders' );

	// get data value from catchbox_options_slider through theme options
	$options = catchbox_get_theme_options();
	// get slider_qty from theme options
	if( !isset( $options['slider_qty'] ) || !is_numeric( $options['slider_qty'] ) ) {
		$options[ 'slider_qty' ] = 4;
	}

	$postperpage = $options[ 'slider_qty' ];
	if ( isset( $options[ 'featured_slider' ] ) ) {
		//In customizer, all values are returned but with empty, this rectifies the issue in customizer
		$slider_array = array_filter( $options[ 'featured_slider' ] );

		if( ( !$catchbox_sliders = get_transient( 'catchbox_sliders' ) ) && !empty( $slider_array ) ) {
			echo '<!-- refreshing cache -->';

			$catchbox_sliders = '
			<div id="slider">
				<section id="slider-wrap">';
				$get_featured_posts = new WP_Query( array(
					'posts_per_page' => $postperpage,
					'post__in'		 => $options[ 'featured_slider' ],
					'orderby' 		 => 'post__in',
					'ignore_sticky_posts' => 1 // ignore sticky posts
				));

				$i=0; while ( $get_featured_posts->have_posts()) : $get_featured_posts->the_post(); $i++;
					$title_attribute = esc_attr( apply_filters( 'the_title', get_the_title( $post->ID ) ) );

					if ( $i == 1 ) { $classes = "slides displayblock"; } else { $classes = "slides displaynone"; }
					$catchbox_sliders .= '
					<div class="'.$classes.'">
						<a href="'. esc_url ( get_permalink() ).'" title="'.sprintf( esc_attr__( 'Permalink to %s', 'catch-box' ), the_title_attribute( 'echo=0' ) ).'" rel="bookmark">
								'.get_the_post_thumbnail( $post->ID, 'featured-slider', array( 'title' => $title_attribute, 'alt' => $title_attribute, 'class'	=> 'pngfix' ) ).'
						</a>
						<div class="featured-text">'
							.the_title( '<span class="slider-title">','</span>', false ).' <span class="sep">:</span>
							<span class="slider-excerpt">'.get_the_excerpt().'</span>
						</div><!-- .featured-text -->
					</div> <!-- .slides -->';
				endwhile; wp_reset_query();
			$catchbox_sliders .= '
				</section> <!-- .slider-wrap -->
				<nav id="nav-slider">
					<div class="nav-previous"><img class="pngfix" src="'.get_template_directory_uri().'/images/previous.png" alt="next slide"></div>
					<div class="nav-next"><img class="pngfix" src="'.get_template_directory_uri().'/images/next.png" alt="next slide"></div>
				</nav>
			</div> <!-- #featured-slider -->';
			set_transient( 'catchbox_sliders', $catchbox_sliders, 86940 );
		}
		echo $catchbox_sliders;
	}
}
endif;  // catchbox_sliders


if ( ! function_exists( 'catchbox_scripts_method' ) ):
/**
 * Register jquery scripts
 *
 * @register jquery cycle and custom-script
 * hooks action wp_enqueue_scripts
 */
function catchbox_scripts_method() {
	global $post;

	$options = get_option( 'catchbox_theme_options' );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );

	/**
	 * Loads up Responsive Menu
	 */
	wp_enqueue_script( 'catchbox-sidr', get_template_directory_uri() . '/js/jquery.sidr.min.js', array( 'jquery' ), '2.1.1.1', false );

	//Register JQuery circle all and JQuery set up as dependent on Jquery-cycle
	wp_register_script( 'jquery-cycle', get_template_directory_uri() . '/js/jquery.cycle.all.min.js', array( 'jquery' ), '2.9999.5', true );

	//Enqueue Slider Script only in Front Page
	if ( is_front_page() || is_home() ) {
		wp_enqueue_script( 'catchbox_slider', get_template_directory_uri() . '/js/catchbox_slider.js', array( 'jquery-cycle' ), '1.0', true );
	}

	wp_enqueue_script( 'catchbox-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151112', true );

	/**
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Loads our main stylesheet.
	wp_enqueue_style( 'catchbox-style', get_stylesheet_uri() );

	// Adds JavaScript for handling the navigation menu hide-and-show behavior.
	wp_enqueue_script( 'catchbox-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '20152512', true );

	/**
	 * Loads up Scroll Up script
	 */
	if ( empty( $options['disable_scrollup'] ) ) {
		wp_enqueue_script( 'catchbox-scrollup', get_template_directory_uri() . '/js/catchbox-scrollup.min.js', array( 'jquery' ), '20072014', true  );
	}

	// Load the html5 shiv.
	wp_enqueue_script( 'catchbox-html5', get_template_directory_uri() . '/js/html5.min.js', array(), '3.7.3' );

}
endif; // catchbox_scripts_method

add_action( 'wp_enqueue_scripts', 'catchbox_scripts_method' );


if ( ! function_exists( 'catchbox_alter_home' ) ):
/**
 * Alter the query for the main loop in home page
 * @uses pre_get_posts hook
 */
function catchbox_alter_home( $query ) {
	$options = catchbox_get_theme_options();
	if( !isset( $options[ 'exclude_slider_post' ] ) ) {
 		$options[ 'exclude_slider_post' ] = "0";
 	}
    if ( $options[ 'exclude_slider_post'] != "0" && !empty( $options[ 'featured_slider' ] ) ) {
		if( $query->is_main_query() && $query->is_home() ) {
			$query->query_vars['post__not_in'] = $options[ 'featured_slider' ];

		}
	}
}
endif; // catchbox_alter_home
add_action( 'pre_get_posts','catchbox_alter_home' );


if ( ! function_exists( 'catchbox_comment_form_fields' ) ) :
/**
 * Altering Comment Form Fields
 * @uses comment_form_default_fields filter
 */
function catchbox_comment_form_fields( $fields ) {
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$commenter = wp_get_current_commenter();
    $fields['author'] = '<p class="comment-form-author"><label for="author">' . esc_attr__( 'Name', 'catch-box' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
    $fields['email'] = '<p class="comment-form-email"><label for="email">' . esc_attr__( 'Email', 'catch-box' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
        '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
    return $fields;
}
endif; // catchbox_comment_form_fields

add_filter( 'comment_form_default_fields', 'catchbox_comment_form_fields' );


/**
 * Get the favicon Image from theme options
 *
 * @uses favicon
 * @get the data value of image from theme options
 * @display favicon
 *
 * @uses set_transient and delete_transient
 *
 * @remove Remove this function when WordPress 4.8 is released
 */
function catchbox_favicon() {
	if ( function_exists( 'has_site_icon' ) ) {
		//Bail Early if Core Site Icon Feature is Present
		return;
	}

	if ( !$catchbox_favicon = get_transient( 'catchbox_favicon' ) ) {

		$options = catchbox_get_theme_options();

		if ( !empty( $options['fav_icon'] ) )  {
			$catchbox_favicon = '<link rel="shortcut icon" href="'.esc_url( $options[ 'fav_icon' ] ).'" type="image/x-icon" />';
		}

		set_transient( 'catchbox_favicon', $catchbox_favicon, 86940 );

	}

	echo $catchbox_favicon ;

}

//Load Favicon in Header Section
//add_action('wp_head', 'catchbox_favicon');

//Load Favicon in Admin Section
//add_action( 'admin_head', 'catchbox_favicon' );


/**
 * Get the Web Click Icon from theme options
 *
 * @uses web clip
 * @get the data value of image from theme options
 * @display web clip
 *
 * @uses set_transient and delete_transient
 *
 * @remove Remove this function when WordPress 4.8 is released
 */
function catchbox_webclip() {
	if ( function_exists( 'has_site_icon' ) ) {
		//Bail Early if Core Site Icon Feature is Present
		return;
	}

	if ( !$catchbox_webclip = get_transient( 'catchbox_webclip' ) ) {

		$options = catchbox_get_theme_options();

		if ( !empty( $options['web_clip'] ) )  {
			$catchbox_webclip = '<link rel="apple-touch-icon-precomposed" href="'.esc_url( $options[ 'web_clip' ] ).'" />';
		}

		set_transient( 'catchbox_webclip', $catchbox_webclip, 86940 );

	}

	echo $catchbox_webclip ;

}

//Load webclip in Header Section
add_action('wp_head', 'catchbox_webclip');


/**
 * Redirect WordPress Feeds To FeedBurner
 */
function catchbox_rss_redirect() {
	$options = catchbox_get_theme_options();
	if ( !empty( $options['feed_url'] ) ) {
		$url = 'Location: '.$options['feed_url'];
		if ( is_feed() && !preg_match('/feedburner|feedvalidator/i', $_SERVER['HTTP_USER_AGENT']))
		{
			header($url);
			header('HTTP/1.1 302 Temporary Redirect');
		}
	}
}
add_action('template_redirect', 'catchbox_rss_redirect');


if ( ! function_exists( 'catchbox_socialprofile' ) ):
/**
 * Social Profles
 *
 * @since Catch Box 1.0
 */
function catchbox_socialprofile() {

	//delete_transient( 'catchbox_socialprofile' );

    $options = catchbox_get_theme_options();
	$flag = 0;
	if( !empty( $options ) ) {
		foreach( $options as $option ) {
			if( $option ) {
				$flag = 1;
			}
			else {
				$flag = 0;
			}
			if( $flag == 1) {
				break;
			}
		}
	}

	if( ( !$catchbox_socialprofile = get_transient( 'catchbox_socialprofile' ) ) && ($flag == 1) ) {
		echo '<!-- refreshing cache -->';

		$catchbox_socialprofile = '
			<nav class="social-profile" role="navigation" aria-label="' . esc_attr__( 'Footer Social Links Menu', 'catch-box' ) . '">
 		 		<ul>';
					//Facebook
					if ( !empty( $options['social_facebook'] ) ) {
						$catchbox_socialprofile .= '<li class="facebook"><a href="'. esc_url( $options['social_facebook'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Facebook', 'catch-box' ) .'</span></a></li>';
					}

					//Twitter
					if ( !empty( $options['social_twitter'] ) ) {
						$catchbox_socialprofile .= '<li class="twitter"><a href="'. esc_url( $options['social_twitter'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Twitter', 'catch-box' ) .'</span></a></li>';
					}

					//Google+
					if ( !empty( $options['social_google'] ) ) {
						$catchbox_socialprofile .= '<li class="google-plus"><a href="'. esc_url( $options['social_google'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Google Plus', 'catch-box' ) .'</span></a></li>';
					}

					//Linkedin
					if ( !empty( $options['social_linkedin'] ) ) {
						$catchbox_socialprofile .= '<li class="linkedin"><a href="'. esc_url( $options['social_linkedin'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Linkedin', 'catch-box' ) .'</span></a></li>';
					}

					//Pinterest
					if ( !empty( $options['social_pinterest'] ) ) {
						$catchbox_socialprofile .= '<li class="pinterest"><a href="'. esc_url( $options['social_pinterest'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Pinterest', 'catch-box' ) .'</span></a></li>';
					}

					//Youtube
					if ( !empty( $options['social_youtube'] ) ) {
						$catchbox_socialprofile .= '<li class="you-tube"><a href="'. esc_url( $options['social_youtube'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'YouTube', 'catch-box' ) .'</span></a></li>';
					}

					//RSS Feed
					if ( !empty( $options['social_rss'] ) ) {
						$catchbox_socialprofile .= '<li class="rss"><a href="'. esc_url( $options['social_rss'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'RSS Feed', 'catch-box' ) .'</span></a></li>';
					}

					//Deviantart
					if ( !empty( $options['social_deviantart'] ) ) {
						$catchbox_socialprofile .= '<li class="deviantart"><a href="'. esc_url( $options['social_deviantart'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Deviantart', 'catch-box' ) .'</span></a></li>';
					}

					//Tumblr
					if ( !empty( $options['social_tumblr'] ) ) {
						$catchbox_socialprofile .= '<li class="tumblr"><a href="'. esc_url( $options['social_tumblr'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Tumblr', 'catch-box' ) .'</span></a></li>';
					}

					//Vimeo
					if ( !empty( $options['social_viemo'] ) ) {
						$catchbox_socialprofile .= '<li class="viemo"><a href="'. esc_url( $options['social_viemo'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Vimeo', 'catch-box' ) .'</span></a></li>';
					}

					//Dribbble
					if ( !empty( $options['social_dribbble'] ) ) {
						$catchbox_socialprofile .= '<li class="dribbble"><a href="'. esc_url( $options['social_dribbble'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Dribbble', 'catch-box' ) .'</span></a></li>';
					}

					//MySpace
					if ( !empty( $options['social_myspace'] ) ) {
						$catchbox_socialprofile .= '<li class="my-space"><a href="'. esc_url( $options['social_myspace'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'MySpace', 'catch-box' ) .'</span></a></li>';
					}

					//Aim
					if ( !empty( $options['social_aim'] ) ) {
						$catchbox_socialprofile .= '<li class="aim"><a href="'. esc_url( $options['social_aim'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'AIM', 'catch-box' ) .'</span></a></li>';
					}

					//Flickr
					if ( !empty( $options[ 'social_flickr'] ) ) {
						$catchbox_socialprofile .= '<li class="flickr"><a href="'. esc_url( $options['social_flickr'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Flickr', 'catch-box' ) .'</span></a></li>';
					}

					//Slideshare
					if ( !empty( $options[ 'social_slideshare' ] ) ) {
						$catchbox_socialprofile .= '<li class="slideshare"><a href="'. esc_url( $options[ 'social_slideshare' ] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Slideshare', 'catch-box' ) .'</span></a></li>';
					}

					//Instagram
					if ( !empty( $options[ 'social_instagram' ] ) ) {
						$catchbox_socialprofile .= '<li class="instagram"><a href="'. esc_url( $options[ 'social_instagram' ] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Instagram', 'catch-box' ) .'</span></a></li>';
					}

					//skype
					if ( !empty( $options[ 'social_skype' ] ) ) {
						$catchbox_socialprofile .= '<li class="skype"><a href="'. esc_attr( $options[ 'social_skype' ] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Skype', 'catch-box' ) .'</span></a></li>';
					}

					//Soundcloud
					if ( !empty( $options[ 'social_soundcloud' ] ) ) {
						$catchbox_socialprofile .= '<li class="soundcloud"><a href="'. esc_url( $options[ 'social_soundcloud' ] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Soundcloud', 'catch-box' ) .'</span></a></li>';
					}

					//Email
					if ( !empty( $options[ 'social_email' ] )  && is_email( $options[ 'social_email' ] ) ) {
						$catchbox_socialprofile .= '<li class="email"><a href="mailto:'. sanitize_email( $options[ 'social_email' ] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Email', 'catch-box' ) .'</span></a></li>';
					}

					//Xing
					if ( !empty( $options[ 'social_xing' ] ) ) {
						$catchbox_socialprofile .= '<li class="xing"><a href="'. esc_url( $options[ 'social_xing' ] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Xing', 'catch-box' ) .'</span></a></li>';
					}

					//Meetup
					if ( !empty( $options[ 'social_meetup' ] ) ) {
						$catchbox_socialprofile .= '<li class="meetup"><a href="'. esc_url( $options[ 'social_meetup' ] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Meetup', 'catch-box' ) .'</span></a></li>';
					}

					$catchbox_socialprofile .= '
				</ul>
			</nav><!-- .social-profile -->';
		set_transient( 'catchbox_socialprofile', $catchbox_socialprofile, 604800 );
	}
	echo $catchbox_socialprofile;
}
endif; // catchbox_socialprofile

// Load Social Profile catchbox_site_generator hook
add_action('catchbox_site_generator', 'catchbox_socialprofile', 10 );


if ( ! function_exists( 'catchbox_slider_display' ) ) :
/**
 * Display slider
 */
function catchbox_slider_display() {
	global $post, $wp_query;

	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts');

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	if ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) {
		if ( function_exists( 'catchbox_pass_slider_value' ) ) { catchbox_pass_slider_value(); }
		if ( function_exists( 'catchbox_sliders' ) ) { catchbox_sliders(); }
	}
}
endif; //catchbox_slider_display

// Load slider in  catchbox_content hook
add_action('catchbox_content', 'catchbox_slider_display', 10);


if ( ! function_exists( 'catchbox_header_image' ) ) :
/**
 * Template for Header Image
 *
 * To override this in a child theme
 * simply create your own catchbox_header_image(), and that function will be used instead.
 *
 * @since Catch Box 2.5
 */
function catchbox_header_image() {
	// Check to see if the header image has been removed
	$header_image = get_header_image();

	// Check Logo
	if ( function_exists( 'has_custom_logo' ) ) {
		if ( has_custom_logo() ) {
			echo '<div id="site-logo">'. get_custom_logo() . '</div><!-- #site-logo -->';
		}
	}
	else if ( ! empty( $header_image ) ) : ?>

    	<div id="site-logo">
        	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
            </a>
      	</div>

	<?php endif; // end check for removed header image
}
endif;


if ( ! function_exists( 'catchbox_header_details' ) ) :
/**
 * Template for Header Details
 *
 * @since Catch Box 2.5
 */
function catchbox_header_details() {

	// Check to see if the header image has been removed
	$header_image = get_header_image();
	// Check Logo
	if ( function_exists( 'has_custom_logo' ) ) {
		if ( has_custom_logo() ) {
			echo '<div id="hgroup" class="site-details with-logo">';
		}
		else {
    		echo '<div id="hgroup" class="site-details">';
    	}
	}
	else if ( ! empty( $header_image ) ) :
     	echo '<div id="hgroup" class="site-details with-logo">';
	else :
    	echo '<div id="hgroup" class="site-details">';
	endif; // end check for removed header image  ?>

   		<h1 id="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
       	<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
   	</div><!-- #hgroup -->

<?php
}
endif;


if ( ! function_exists( 'catchbox_headerdetails' ) ) :
/**
 * Header Details including Site Logo, Title and Description
 *
 * @since Catch Box 2.5
 */
function catchbox_headerdetails() {

	// Getting data from Theme Options
	$options = catchbox_get_theme_options();
	$sitedetails = $options['site_title_above'];

	echo '<div class="logo-wrap clearfix">';

	if ( $sitedetails == '0' ) {
		echo catchbox_header_image();
		echo catchbox_header_details();
	} else {
		echo catchbox_header_details();
		echo catchbox_header_image();
	}

	echo '</div><!-- .logo-wrap -->';

}
endif; //catchbox_headerdetails

// Loads Header Details in catchbox_headercontent hook
add_action( 'catchbox_headercontent', 'catchbox_headerdetails', 10 );


if ( ! function_exists( 'catchbox_header_search' ) ) :
/**
 * Header Search Box
 *
 * @since Catch Box 2.5
 */
function catchbox_header_search() {

	// Getting data from Theme Options
	$options = catchbox_get_theme_options();

	if ( $options ['disable_header_search'] == 0 ) :
    	get_search_form();
    endif;

}
endif; //catchbox_header_search

// Loads Header Search in catchbox_headercontent hook
add_action( 'catchbox_headercontent', 'catchbox_header_search', 15 );


if ( ! function_exists( 'catchbox_footer_content' ) ) :
/**
 * shows footer content
 *
 * @since Catch Box 2.5
 */
function catchbox_footer_content() {
	//delete_transient( 'catchbox_footer_content' );

	if ( ( !$catchbox_footer_content = get_transient( 'catchbox_footer_content' ) ) ) {
		echo '<!-- refreshing cache -->';

		$catchbox_footer_content = catchbox_assets();

    	set_transient( 'catchbox_footer_content', $catchbox_footer_content, 86940 );
    }
	echo $catchbox_footer_content;
}
endif; //catchbox_footer_content

// Load footer content in  catchbox_site_generator hook
add_action( 'catchbox_site_generator', 'catchbox_footer_content', 15 );


/**
 * This function loads Scroll Up Navigation
 *
 * @uses catchbox_after action
 */
function catchbox_scrollup() {

	echo '<a href="#branding" id="scrollup"><span class="screen-reader-text">Go to Header Section</span></a>';

}
add_action( 'catchbox_after', 'catchbox_scrollup', 10 );


if ( ! function_exists( 'catchbox_breadcrumb_display' ) ) :
	/**
	 * Display breadcrumb on header
	 */
	function catchbox_breadcrumb_display() {
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			$wpseo_internallinks = get_option( 'wpseo_internallinks' );
			if ( $wpseo_internallinks['breadcrumbs-enable'] == 1 ) {
				echo '<div class="breadcrumbs wpseoyoast">';
					yoast_breadcrumb();
				echo '</div><!-- .wpseoyoast -->';
			}
		}
		elseif ( function_exists( 'bcn_display' ) ) {
			echo '<div class="breadcrumbs breadcrumbnavxt" xmlns:v="http://rdf.data-vocabulary.org/#">';
					bcn_display();
			echo '</div><!-- .breadcrumbnavxt -->';
		}
	}
endif; // catchbox_breadcrumb_display
add_action( 'catchbox_content', 'catchbox_breadcrumb_display', 20 );


if ( ! function_exists( 'catchbox_skiptocontain' ) ) :
	/**
	 * Display Skip to Contain Link
	 */
	function catchbox_skiptocontain() {
		echo '<a href="#main" class="skip-link screen-reader-text">Skip to content</a>';
	}
endif; // catchbox_breadcrumb_display
add_action( 'catchbox_before_header', 'catchbox_skiptocontain', 10 );


/**
 * Customizer Options
 */
require( get_template_directory() . '/inc/customizer/customizer.php' );
