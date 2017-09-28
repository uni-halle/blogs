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
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	function catchbox_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'catchbase_content_width', 530 );
	}
	endif; // catchbox_content_width
add_action( 'after_setup_theme', 'catchbox_content_width', 0 );


if ( ! function_exists( 'catchbox_template_redirect' ) ) :
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet for different value other than the default one
	 *
	 * @global int $content_width
	 */
	function catchbox_template_redirect() {
	   	$layout = catchbox_get_theme_layout();

		if ( is_page_template( 'page-fullwidth.php' ) ) {
			$GLOBALS['content_width'] = 880; /* pixels */
		}
		elseif ( is_page_template( 'page-disable-sidebar.php' ) ) {
			$GLOBALS['content_width'] = 660; /* pixels */
		}
		elseif ( 'content-onecolumn' == $layout || is_page_template( 'page-onecolumn.php' ) ) {
			$GLOBALS['content_width'] = 620; /* pixels */
		}
	}
endif;
add_action( 'template_redirect', 'catchbox_template_redirect' );


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

	//featued posts
	add_image_size( 'featured-slider-larger', 980, 400, true );

	add_image_size( 'featured-slider', 640, 318, true ); // Used for featured posts if a large-feature doesn't exist

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
				'height'      => 125,
				'width'       => 300,
				'flex-height' => true,
				'flex-width'  => true,
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
	    elseif ( is_singular() ) {
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
		$options = catchbox_get_options();

		//check empty and load default
		if ( empty( $layout ) || 'default' == $layout ) {
			$layout = $options['theme_layout'];
		}

		//Condition checks for backward compatibility
		if ( 'content-sidebar' ==$layout ) {
			$layout = 'right-sidebar';
		}
		elseif ( 'sidebar-content' ==$layout ) {
			$layout = 'left-sidebar';
		}
		elseif ( 'content-onecolumn' ==$layout ) {
			$layout = 'no-sidebar-one-column';
		}

		return $layout;
	}
endif; //catchbox_get_theme_layout


/**
 * Sets the post excerpt length.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function catchbox_excerpt_length( $length ) {
	$options = catchbox_get_options();
	if ( empty( $options['excerpt_length'] ) )
		$options = catchbox_defaults();

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
			<h3 class="screen-reader-text"><?php _e( 'Post navigation', 'catch-box' ); ?></h3>
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
        	<h3 class="screen-reader-text"><?php _e( 'Post navigation', 'catch-box' ); ?></h3>
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
	$options = catchbox_get_options();
	$layout = $options['theme_layout'];
	if ( function_exists( 'is_multi_author' ) && !is_multi_author() ) {
		$classes[] = 'single-author';
	}

	$layout = catchbox_get_theme_layout();

	if ( 'right-sidebar' == $layout && !is_page_template( 'page-disable-sidebar.php' ) && !is_page_template( 'page-fullwidth.php' )  && !is_page_template( 'page-onecolumn.php' ) ) {
		$classes[] = 'right-sidebar';
	}
	elseif ( 'left-sidebar' == $layout && !is_page_template( 'page-disable-sidebar.php' ) && !is_page_template( 'page-fullwidth.php' )  && !is_page_template( 'page-onecolumn.php' ) ) {
		$classes[] = 'left-sidebar';
	}
	elseif ( 'no-sidebar-one-column' == $layout || is_page_template( 'page-onecolumn.php' ) && !is_page_template( 'page-disable-sidebar.php' ) && !is_page_template( 'page-fullwidth.php' ) ) {
		$classes[] = 'no-sidebar one-column';
	}
	elseif ( is_page_template( 'page-disable-sidebar.php' ) || is_attachment() ) {
		$classes[] = 'no-sidebar';
	}
	elseif ( is_page_template( 'page-fullwidth.php' ) || is_attachment() ) {
		$classes[] = 'no-sidebar full-width';
	}

	if ( ! ( ! empty ( $options['enable_sec_menu'] ) && has_nav_menu( 'secondary' ) ) ) {
		$classes[] = 'one-menu';
	}

	$position = isset( $options['header_image_position'] ) ? $options['header_image_position'] : 'above';


	if ( 'above' == $position ) {
		$classes[] = 'header-image-top';
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
	if ( 'postid' == $col ) echo $val;
}
add_action( 'manage_posts_custom_column', 'catchbox_posts_id_column', 10, 2 );

function catchbox_posts_id_column_css() {
	echo '
	<style type="text/css">
	    #postid { width: 80px; }
	    @media screen and (max-width: 782px) {
	        .wp-list-table #postid, .wp-list-table #the-list .postid { display: none; }
	        .wp-list-table #the-list .is-expanded .postid {
	            padding-left: 30px;
	        }
	    }
    </style>';
}
add_action( 'admin_head-edit.php', 'catchbox_posts_id_column_css' );


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
	wp_enqueue_script( 'catchbox-menu', get_template_directory_uri() . '/js/menu.min.js', array( 'jquery' ), '2.1.1.1', false );

	wp_localize_script( 'catchbox-menu', 'screenReaderText', array(
		'expand'   => esc_html__( 'expand child menu', 'catch-box' ),
		'collapse' => esc_html__( 'collapse child menu', 'catch-box' ),
	) );

	wp_enqueue_script( 'jquery-fitvids', get_template_directory_uri() . '/js/catchbox-fitvids.min.js', array( 'jquery' ), '20140315', true );

	//Register JQuery circle all and JQuery set up as dependent on Jquery-cycle
	wp_register_script( 'jquery-cycle', get_template_directory_uri() . '/js/jquery.cycle.all.min.js', array( 'jquery' ), '2.9999.5', true );

	//Enqueue Slider Script only in Front Page
	if ( is_front_page() || is_home() ) {
		wp_enqueue_script( 'catchbox-slider', get_template_directory_uri() . '/js/catchbox-slider.js', array( 'jquery-cycle' ), '1.0', true );
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
	$options = catchbox_get_options();
	if ( !isset( $options['exclude_slider_post'] ) ) {
 		$options['exclude_slider_post'] = "0";
 	}
    if ( $options[ 'exclude_slider_post'] != "0" && !empty( $options['featured_slider'] ) ) {
		if ( $query->is_main_query() && $query->is_home() ) {
			$query->query_vars['post__not_in'] = $options['featured_slider'];

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
 * Redirect WordPress Feeds To FeedBurner
 */
function catchbox_rss_redirect() {
	$options = catchbox_get_options();
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

    $options = catchbox_get_options();
	$flag = 0;
	if ( !empty( $options ) ) {
		foreach( $options as $option ) {
			if ( $option ) {
				$flag = 1;
			}
			else {
				$flag = 0;
			}
			if ( $flag == 1) {
				break;
			}
		}
	}

	if ( ( !$catchbox_socialprofile = get_transient( 'catchbox_socialprofile' ) ) && ($flag == 1) ) {
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
					if ( !empty( $options['social_slideshare'] ) ) {
						$catchbox_socialprofile .= '<li class="slideshare"><a href="'. esc_url( $options['social_slideshare'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Slideshare', 'catch-box' ) .'</span></a></li>';
					}

					//Instagram
					if ( !empty( $options['social_instagram'] ) ) {
						$catchbox_socialprofile .= '<li class="instagram"><a href="'. esc_url( $options['social_instagram'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Instagram', 'catch-box' ) .'</span></a></li>';
					}

					//skype
					if ( !empty( $options['social_skype'] ) ) {
						$catchbox_socialprofile .= '<li class="skype"><a href="'. esc_attr( $options['social_skype'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Skype', 'catch-box' ) .'</span></a></li>';
					}

					//Soundcloud
					if ( !empty( $options['social_soundcloud'] ) ) {
						$catchbox_socialprofile .= '<li class="soundcloud"><a href="'. esc_url( $options['social_soundcloud'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Soundcloud', 'catch-box' ) .'</span></a></li>';
					}

					//Email
					if ( !empty( $options['social_email'] )  && is_email( $options['social_email'] ) ) {
						$catchbox_socialprofile .= '<li class="email"><a href="mailto:'. sanitize_email( $options['social_email'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Email', 'catch-box' ) .'</span></a></li>';
					}

					//Xing
					if ( !empty( $options['social_xing'] ) ) {
						$catchbox_socialprofile .= '<li class="xing"><a href="'. esc_url( $options['social_xing'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Xing', 'catch-box' ) .'</span></a></li>';
					}

					//Meetup
					if ( !empty( $options['social_meetup'] ) ) {
						$catchbox_socialprofile .= '<li class="meetup"><a href="'. esc_url( $options['social_meetup'] ) .'"><span class="screen-reader-text">'. esc_attr__( 'Meetup', 'catch-box' ) .'</span></a></li>';
					}

					//Goodreads
					if ( !empty( $options['social_goodreads'] ) ) {
						$catchbox_socialprofile .=
							'<li class="goodreads"><a href="'.esc_url( $options['social_goodreads'] ).'" title="'. esc_attr__( 'Goodreads', 'catch-box' ) .'" target="_blank">'. esc_attr__( 'Goodreads', 'catch-box' ) .'</a></li>';
					}

					//Github
					if ( !empty( $options['social_github'] ) ) {
						$catchbox_socialprofile .=
							'<li class="github"><a href="'.esc_url( $options['social_github'] ).'" title="'. esc_attr__( 'Github', 'catch-box' ) .'" target="_blank">'. esc_attr__( 'Github', 'catch-box' ) .'</a></li>';
					}

					//VK
					if ( !empty( $options['social_vk'] ) ) {
						$catchbox_socialprofile .=
							'<li class="vk"><a href="'.esc_url( $options['social_vk'] ).'" title="'. esc_attr__( 'VK', 'catch-box' ) .'" target="_blank">'. esc_attr__( 'VK', 'catch-box' ) .'</a></li>';
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


// Loads Header Details in catchbox_headercontent hook
add_action( 'catchbox_headercontent', 'catchbox_headerdetails', 10 );


// Loads Header Search in catchbox_headercontent hook
add_action( 'catchbox_headercontent', 'catchbox_header_search', 20 );


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
	$options = catchbox_get_options();
	if ( ! $options['disable_scrollup'] ) {
		echo '<a href="#branding" id="scrollup"><span class="screen-reader-text">' . esc_html__( 'Scroll Up', 'catch-box' ) . '</span></a>';
	}
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
		echo '<a href="#main" class="skip-link screen-reader-text">' . esc_html__( 'Skip to content', 'catch-box' ) . '</a>';
	}
endif; // catchbox_breadcrumb_display
add_action( 'catchbox_before_header', 'catchbox_skiptocontain', 10 );

/**
 * Enqueue the styles for the current color scheme.
 *
 * @since Catch Box 1.0
 */
function catchbox_enqueue_color_scheme() {
	$options = catchbox_get_options();
	$color_scheme = $options['color_scheme'];

	$enqueue_schemes = array( 'dark', 'blue', 'green', 'red', 'brown', 'orange' );

	if ( in_array( $color_scheme, $enqueue_schemes ) ) {
		wp_enqueue_style( $color_scheme, get_template_directory_uri() . '/colors/' . $color_scheme . '.css', array( 'catchbox-style' ), null );
	}

	do_action( 'catchbox_enqueue_color_scheme', $color_scheme );
}
add_action( 'wp_enqueue_scripts', 'catchbox_enqueue_color_scheme' );

/**
 * Hooks the css to head section
 *
 * @since Catch Box 1.0
 *
 * @remove when WordPress version 5.0 is released
 */
function catchbox_inline_css() {
	/**
      * Bail if WP version >=4.7 as we have migrated this option to core
	*/
	if ( function_exists( 'wp_update_custom_css_post' ) ) {
		return;
    }

    $options = catchbox_get_options();
	if ( !empty( $options['custom_css'] ) ) {
		echo '<!-- '.get_bloginfo('name').' Custom CSS Styles -->' . "\n";
        echo '<style type="text/css" media="screen">' . "\n";
		echo $options['custom_css'] . "\n";
		echo '</style>' . "\n";
	}
}
add_action('wp_head', 'catchbox_inline_css');

/**
 * Site Verification codes are hooked to wp_head if any value exists
 */

function catchbox_verification() {
    $options = catchbox_get_options();
	//google
    if (!empty( $options['google_verification'] ) ) {
		echo '<meta name="google-site-verification" content="' . $options['google_verification'] . '" />' . "\n";
	}

	//bing
	if (!empty( $options['bing_verification'] ) ) {
        echo '<meta name="msvalidate.01" content="' . $options['bing_verification'] . '" />' . "\n";
	}

	//yahoo
	if (!empty( $options['yahoo_verification'] ) ) {
        echo '<meta name="y_key" content="' . $options['yahoo_verification'] . '" />' . "\n";
	}

	//site stats, analytics code
	if (!empty( $options['tracker_header'] ) ) {
        echo $options['tracker_header'];
	}
}

add_action('wp_head', 'catchbox_verification');

/**
 * Analytic, site stat code hooked in footer
 * @uses wp_footer
 */
function catchbox_site_stats() {
    $options = catchbox_get_options();
    if (!empty( $options['tracker_footer'] ) ) {
        echo $options['tracker_footer'];
	}
}

add_action('wp_footer', 'catchbox_site_stats');

/**
 * Add a style block to the theme for the current link color.
 *
 * This function is attached to the wp_head action hook.
 *
 * @since Catch Box 1.0
 */
function catchbox_print_link_color_style() {
	$options = catchbox_get_options();
	$link_color = $options['link_color'];

	$default_options = catchbox_defaults();

	// Don't do anything if the current link color is the default.
	if ( $default_options['link_color'] == $link_color )
		return;
?>
	<style>
		/* Link color */
		a,
		#site-title a:focus,
		#site-title a:hover,
		#site-title a:active,
		.entry-title a:hover,
		.entry-title a:focus,
		.entry-title a:active,
		.widget_catchbox_ephemera .comments-link a:hover,
		section.recent-posts .other-recent-posts a[rel="bookmark"]:hover,
		section.recent-posts .other-recent-posts .comments-link a:hover,
		.format-image footer.entry-meta a:hover,
		#site-generator a:hover {
			color: <?php echo $link_color; ?>;
		}
		section.recent-posts .other-recent-posts .comments-link a:hover {
			border-color: <?php echo $link_color; ?>;
		}
	</style>
<?php
}
add_action( 'wp_head', 'catchbox_print_link_color_style' );





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

		//Remove header image previously set as logo
		set_theme_mod( 'header_image', '' );

		set_theme_mod( 'header_image_data', array() );

  		// Update to match logo_version so that script is not executed continously
		set_theme_mod( 'logo_version', '3.6' );
	}
}
add_action( 'after_setup_theme', 'catchbox_logo_migrate' );


/**
 * Migrate Custom CSS to WordPress core Custom CSS
 *
 * Runs if version number saved in theme_mod "custom_css_version" doesn't match current theme version.
 */
function catchbox_custom_css_migrate(){
	$ver = get_theme_mod( 'custom_css_version', false );

	// Return if update has already been run
	if ( version_compare( $ver, '4.7' ) >= 0 ) {
		return;
	}

	if ( function_exists( 'wp_update_custom_css_post' ) ) {
	    // Migrate any existing theme CSS to the core option added in WordPress 4.7.

	    /**
		 * Get Theme Options Values
		 */
		$options = catchbox_get_options();

	    if ( isset( $options['custom_css'] ) && '' != $options['custom_css'] ) {
			$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
			$return   = wp_update_custom_css_post( $core_css . $options['custom_css'] );

	        if ( ! is_wp_error( $return ) ) {
	            // Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
	            unset( $options['custom_css'] );
	            update_option( 'catchbox_theme_options', $options );

	            // Update to match custom_css_version so that script is not executed continously
				set_theme_mod( 'custom_css_version', '4.7' );
	        }
	    }
	}
}
add_action( 'after_setup_theme', 'catchbox_custom_css_migrate' );

// Load up our theme default options and related codes.
require trailingslashit( get_template_directory() ) . 'inc/default-options.php';

// Load transients/cache fliushing functions
require trailingslashit( get_template_directory() ) . 'inc/invalidate-caches.php';

//Custom Header
require trailingslashit( get_template_directory() ) . 'inc/custom-header.php';

//Custom Menus
require trailingslashit( get_template_directory() ) . 'inc/catchbox-menus.php';

//Custom Metabox
require trailingslashit( get_template_directory() ) . 'inc/catchbox-metabox.php';

//Customizer Options
require trailingslashit( get_template_directory() ) . 'inc/customizer/customizer.php' ;

// Load up Widgets and Sidebars
require trailingslashit( get_template_directory() ) . 'inc/widgets.php';

// Load Catch Box Sliders
require trailingslashit( get_template_directory() ) . 'inc/catchbox-sliders.php';
