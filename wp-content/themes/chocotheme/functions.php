<?php
/**
 * @package WordPress
 * @subpackage Choco
 *
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 645;

/** Tell WordPress to run choco_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'choco_setup' );

if ( ! function_exists( 'choco_setup' ) ):

function choco_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'choco', get_template_directory() . '/languages' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'choco' ),
	) );

	// This theme allows users to set a custom background
	add_theme_support( 'custom-background' );

	//Add support for Post Formats
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
}
endif;

/**
 * Enqueue scripts and styles
 */
function choco_scripts() {
	wp_enqueue_style( 'choco', get_stylesheet_uri() );

	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-touchSwipe', esc_url( get_stylesheet_directory_uri() ) . '/js/jquery.touchSwipe.min.js');
	wp_enqueue_script('functions', esc_url( get_stylesheet_directory_uri() ) . '/js/functions.js');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'choco_scripts' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 */
function choco_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'choco_page_menu_args' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 */
function choco_remove_recent_comments_style() {
	add_filter( 'show_recent_comments_widget_style', '__return_false' );
}
add_action( 'widgets_init', 'choco_remove_recent_comments_style' );
/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme's style.css. This is just
 * a simple filter call that tells WordPress to not use the default styles.
 *
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Deprecated way to remove inline styles printed when the gallery shortcode is used.
 *
 * This function is no longer needed or used. Use the use_default_gallery_style
 * filter instead, as seen above.
 *
 */
function choco_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'choco_remove_gallery_css' );

register_sidebar( array (
		'name'			=> 'Sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</li>',
		'before_title'	=> '<h4 class="widgettitle">',
		'after_title'	=> '</h4>',
) );

/**
 *Changed wp_page_menu structure to get rid of the wrapped div and add menu_class arguments to <ul>
 */
function choco_add_menu_class ($page_markup) {
	preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
	$divclass = $matches[1];
	$toreplace = array('<div class="'.$divclass.'">', '</div>');
	$new_markup = str_replace($toreplace, '', $page_markup);
	$new_markup = preg_replace('/^<ul>/i', '<ul class="'.$divclass.'">', $new_markup);
	return $new_markup;
}
add_filter('wp_page_menu', 'choco_add_menu_class');

function choco_print_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class( 'comment' ); ?>>
		<div class="comment-body" id="comment-<?php comment_ID(); ?>">
			<?php echo get_avatar( $comment, 70 ); ?>
			<p class="author">
				<?php comment_author_link(); ?>
			</p>
			<p class="comment-meta">
				<?php comment_date(); ?> at <?php comment_time(); ?>
			</p>
			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'choco' ); ?></em><br />
				<?php endif; ?>

				<?php comment_text(); ?>
				<div class="alignleft"><?php edit_comment_link(__( 'Edit', 'choco' ),'  ','' ); ?></div>

			</div>
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
			<div class="cl">&nbsp;</div>
		</div>
	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li <?php comment_class( 'comment' ); ?>>
		<div class="comment-body" id="comment-<?php comment_ID(); ?>">
			<?php _e( 'Pingback:', 'choco' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'choco' ), ' ' ); ?></div>
	<?php
			break;
	endswitch;

}

// Add the default Choco gravatar
function choco_avatar ( $avatar_defaults ) {
		$myavatar = get_stylesheet_directory_uri() . '/images/avatar.gif';

		$avatar_defaults[$myavatar] = "Choco";

		return $avatar_defaults;
}
add_filter( 'avatar_defaults', 'choco_avatar' );

// Load up the theme options
require( get_template_directory() . '/inc/theme-options.php' );

/**
 *  Get choco options
 */
function choco_get_options() {
	$defaults = array(
		'color_scheme' => 'default',
	);
	$options = get_option( 'choco_theme_options', $defaults );
	return $options;
}

/**
 * Register our color schemes and add them to the style queue
 */
function choco_color_registrar() {
	$options = choco_get_options();
	$color_scheme = $options['color_scheme'];

	if ( ! empty( $color_scheme ) ) {
		wp_register_style( $color_scheme, get_template_directory_uri() . '/colors/' . $color_scheme . '/style.css', null, null );
		wp_register_style( $color_scheme . '_rtl' , get_template_directory_uri() . '/colors/' . $color_scheme . '/rtl.css', null, null );
		wp_enqueue_style( $color_scheme );

		if ( 'rtl' == get_option( 'text_direction' ) ) {
			wp_enqueue_style( $color_scheme . '_rtl' );
		}

	}
}
add_action( 'wp_enqueue_scripts', 'choco_color_registrar' );

/**
 * Adds custom classes to the array of body classes.
 */
function choco_body_classes( $classes ) {
	$options = choco_get_options();
	$color_scheme = $options['color_scheme'];
	switch ( $color_scheme ) {
		case 'darkgray' :
			$classes[] = 'color-darkgray';
			break;
		case 'red' :
			$classes[] = 'color-red';
			break;
		default :
			$classes[] = 'color-default';
			break;
	}
	return $classes;
}
add_filter( 'body_class', 'choco_body_classes' );

/**
 * Appends post title to Aside and Quote posts
 *
 * @param string $content
 * @return string
 */
function choco_conditional_title( $content ) {

	if ( has_post_format( 'aside' ) || has_post_format( 'quote' ) ) {
		if ( ! is_singular() )
			$content .= the_title( '<h2 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>', false );
		else
			$content .= the_title( '<h2 class="post-title">', '</h2>', false );
	}

	return $content;
}
add_filter( 'the_content', 'choco_conditional_title', 0 );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since Choco 0.1
 */
function choco_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'choco' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'choco_wp_title', 10, 2 );

/**
 * Load Jetpack compatibility file.
 */
if ( file_exists( get_template_directory() . '/inc/jetpack.php' ) )
	require get_template_directory() . '/inc/jetpack.php';