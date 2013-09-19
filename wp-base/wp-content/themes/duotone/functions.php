<?php
/**
 * @package Duotone
 */

if ( ! isset( $content_width ) )
	$content_width = 315;

if ( ! defined( 'MIN_WIDTH' ) )
	define( 'MIN_WIDTH', 560 );

if ( ! defined( 'MAX_WIDTH' ) )
	define( 'MAX_WIDTH', 840 );

if ( ! function_exists( 'duotone_setup' ) ) {
	function duotone_setup() {
		require_once( get_template_directory() . '/inc/duotone.php' );
		Duotone::init();

		add_theme_support( 'automatic-feed-links' );
		register_sidebar( array( 'name' => __( 'Sidebar', 'duotone' ) ) );

		add_image_size( 'duotone_archive', 75, 75, true );
		add_image_size( 'duotone_singular', 840, 0, true );

		register_nav_menus( array(
			'primary' => __( 'Primary Navigation', 'duotone' )
		) );

		add_theme_support( 'print-style' );
	}
}
add_action( 'after_setup_theme', 'duotone_setup' );

/**
 * Setup the WordPress core custom background feature.
 *
 * Use add_theme_support to register support for WordPress 3.4+
 * as well as provide backward compatibility for previous versions.
 * Use feature detection of wp_get_theme() which was introduced
 * in WordPress 3.4.
 *
 * Hooks into the after_setup_theme action.
 */
function duotone_custom_background() {
	$args = array(
		'default-color' => '',
		'default-image' => '',
	);

	$args = apply_filters( 'duotone_custom_background_args', $args );
	add_theme_support( 'custom-background', $args );
}
add_action( 'after_setup_theme', 'duotone_custom_background' );

/**
 * Enqueue scripts and styles
 */
function duotone_scripts() {
	wp_enqueue_style( 'duotone', get_stylesheet_uri() );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'duotone_scripts' );

/**
 * Fallback for primary navigation menu.
 */
function duotone_page_menu() {
	$recent = get_posts( array(
		'numberposts' => 1,
	) );

	$year = date( 'Y' );

	if ( is_array( $recent ) ) {
		$last = array_shift( $recent );
		if ( isset( $last->post_date ) )
			$year = substr( $last->post_date, 0, 4 );
	}
?>
	<ul>
		<li><a href="<?php echo esc_url( get_year_link( $year ) ); ?>"><?php _e( 'archive', 'duotone' ); ?></a></li>
		<?php wp_list_pages( array(
			'title_li' => '',
		) ); ?>
	</ul>
<?php
}

function duotone_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract( $args, EXTR_SKIP );
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID(); ?>">
	<div id="div-comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
		<div class="gravatar"><?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?></div>
		<div class="comment-meta commentmetadata metadata">
			<a href="#comment-<?php comment_ID(); ?>" title=""><?php comment_date( 'j M Y' ); ?> at <?php comment_time(); ?></a>
			<cite class="fn"><?php comment_author_link(); ?></cite>
			<?php edit_comment_link( __( 'edit', 'duotone' ), '<br />', '' ); ?>
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => 'reply', 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
		</div>
		</div>
		<div class="content">

			<?php if ($comment->comment_approved == '0') : ?>
			<p><em><?php _e( 'Your comment is awaiting moderation.', 'duotone' ); ?></em></p>
			<?php endif; ?>
			<?php comment_text(); ?>
		</div>
		<div class="clear"></div>
	</div>
<?php
}

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since Duotone 2.1
 */
function duotone_wp_title( $title, $sep ) {
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
		$title .= " $sep " . sprintf( __( 'Page %s', 'duotone' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'duotone_wp_title', 10, 2 );
