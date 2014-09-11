<?php
/**
 * @package Esquire
 */

/**
 * Load Esquire scripts
 */
function esquire_scripts() {
	wp_enqueue_style( 'esquire', get_stylesheet_uri() );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_enqueue_script( 'esquire', get_template_directory_uri() .'/js/esquire.js', array( 'jquery'), '2011-07-29' );
}
add_action( 'wp_enqueue_scripts', 'esquire_scripts' );

/**
 * Audio helper script.
 *
 * If an audio shortcode exists we will enqueue javascript
 * that replaces all non-supported audio player instances
 * with text links.
 *
 * @uses shortcode_exists();
 */
function esquire_audio_script() {
	if ( shortcode_exists( 'audio' ) )
		return;

	if ( ! is_singular() || has_post_format( 'audio' ) )
		wp_enqueue_script( 'esquire-audio', get_template_directory_uri() . '/js/audio.js', array(), '20130520' );
}
add_action( 'wp_enqueue_scripts', 'esquire_audio_script' );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 560;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Esquire 1.2
 */
function esquire_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 */
	load_theme_textdomain( 'esquire', get_template_directory_uri() . '/languages' );

	/**
	 * Add feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Main Menu', 'esquire' ),
	) );

	// Add post thumbnail support for audio album art
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'audio', 207, 207, false );

	/**
	 * Enable Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'image', 'quote', 'link', 'audio', 'video' ) );

	/**
	 * Load Jetpack compatibility file.
	 */
	require( get_template_directory() . '/inc/jetpack.compat.php' );

	/**
	 * Load up our functions for grabbing content from posts
	 */
	require( get_template_directory() . '/content-grabbers.php' );
}
add_action( 'after_setup_theme', 'esquire_setup' );

/**
 * Sniff out the number of categories in use and return the number of categories
 */
function esquire_category_counter() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	return $all_the_cool_cats;
}

/**
 * Flush out the transients used in esquire_category_counter
 */
function esquire_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'esquire_category_transient_flusher' );
add_action( 'save_post', 'esquire_category_transient_flusher' );

/**
 * Add a class to the Older Posts link
 */
function esquire_next_posts_link_attributes( $attr ) {
	$attr = 'rel="prev"';

	return $attr;
}
add_filter( 'next_posts_link_attributes', 'esquire_next_posts_link_attributes' );

/**
 * Add a class to the Newer Posts link
 */
function esquire_previous_posts_link_attributes( $attr ) {
	$attr = 'rel="next"';

	return $attr;
}
add_filter( 'previous_posts_link_attributes', 'esquire_previous_posts_link_attributes' );

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function esquire_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'esquire_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function esquire_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '"><em>' .__( 'Continue&nbsp;reading&nbsp;<span class="meta-nav">&rarr;</span>', 'esquire' ) . '</em></a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and esquire_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function esquire_auto_excerpt_more( $more ) {
	return ' &hellip;' . esquire_continue_reading_link();
}
add_filter( 'excerpt_more', 'esquire_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function esquire_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= esquire_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'esquire_custom_excerpt_more' );

/**
 * Register our footer widget area
 *
 * @since Esquire 1.0
 */
function esquire_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Footer', 'esquire' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'esquire_widgets_init' );

/**
 * Template for comments and pingbacks.
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Esquire 1.0
 */
function esquire_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'esquire' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'esquire' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 16;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'esquire' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'esquire' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'esquire' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'esquire' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'esquire' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}

/**
 * Filter the_content for post formats, and add extra presentational markup as needed.
 *
 * @param string $the_content
 * @return string Updated content with extra markup.
 */
function esquire_the_content( $content ) {
	if ( has_post_format( 'image' ) ) {
		$first_image = esquire_image_grabber( get_the_ID(), $content, '<div class="frame"><div class="wrapper">', '</div></div>' );
		if ( $first_image && defined( 'WPCOM_THEMES_IMAGE_REPLACE_REGEX' ) )
			$content = preg_replace( WPCOM_THEMES_IMAGE_REPLACE_REGEX, $first_image, $content, 1 );
	}

	return $content;
}
if ( ! is_admin() )
	add_filter( 'the_content', 'esquire_the_content', 11 );

/**
 * Add extra markup to VideoPress embeds.
 *
 * @param string $html Video content from VideoPress plugin.
 * @return string Updated content with extra markup.
 */
function esquire_video_embed_html( $html ) {
	return sprintf( '<div class="frame"><div class="player">%s</div></div>', $html );
}

/**
 * Add extra markup to auto-embedded videos.
 *
 * @param string $html Content from the auto-embed plugin.
 * @param string $url Link embedded in the post, used to determine if this is a video we want to filter.
 * @return string Updated content with extra markup.
 */
function esquire_check_video_embeds( $html, $url ) {
	if ( false !== ( strstr( $url, 'youtube' ) ) || false !== ( strstr( $url, 'vimeo' ) ) )
		$html = esquire_video_embed_html( $html );
	return $html;
}

/**
 * Get a short-form mime type for an audio file to display as a class attribute.
 *
 * @param int ID of an attachment
 * @return string A short representation of the file's mime type.
 */
function esquire_post_classes( $classes ) {
	if ( has_post_format( 'audio' ) ) {
		$audio = esquire_audio_grabber( get_the_ID() );

		if ( ! empty( $audio ) && is_object( $audio ) ) {
			$mime = str_replace( 'audio/', '', get_post_mime_type( $audio->ID ) );
			if ( in_array( $mime, array( 'mp3', 'ogg', 'wav', ) ) )
				$classes[] = $mime;
		}
	}
	return $classes;
}
add_filter( 'post_class', 'esquire_post_classes' );

if ( ! function_exists( 'the_post_format_audio' ) ) :
/**
 * Shiv for the_post_format_audio().
 *
 * the_post_format_audio() was introduced to WordPress in version 3.6. To
 * provide backward compatibility with previous versions, we will define our
 * own version of this function.
 *
 * @todo Remove this function when WordPress 3.8 is released.
 *
 * @param string $name The name of the shortcode.
 * @return bool True if shortcode exists; False otherwise.
 */
function the_post_format_audio() {
	$audio = esquire_audio_grabber( get_the_ID() );

	if ( ! empty( $audio ) && is_object( $audio ) ) :
		$url = wp_get_attachment_url( $audio->ID );
	?>
		<div class="player">
			<audio controls preload="auto" autobuffer id="audio-player-<?php the_ID(); ?>" src="<?php echo esc_url( $url ); ?>">
				<source src="<?php echo esc_url( $url ); ?>" type="<?php echo esc_attr( get_post_mime_type( $audio->ID ) ); ?>" />
			</audio>
			<p class="audio-file-link"><?php printf( __( 'Download: %1$s', 'esquire' ), sprintf( '<a href="%1$s">%2$s</a>', esc_url( $url ), get_the_title( $audio->ID ) ) ); ?></p>
		</div>
	<?php
	endif;
}
endif;

if ( ! function_exists( 'shortcode_exists' ) ) :
/**
 * Shiv for shortcode_exists().
 *
 * shortcode_exists() was introduced to WordPress in version 3.6. To
 * provide backward compatibility with previous versions, we will define our
 * own version of this function.
 *
 * @todo Remove this function when WordPress 3.8 is released.
 *
 * @param string $name The name of the shortcode.
 * @return bool True if shortcode exists; False otherwise.
 */
function shortcode_exists( $tag ) {
	global $shortcode_tags;
	return array_key_exists( $tag, $shortcode_tags );
}
endif;

/**
 * Deprecated.
 *
 * This function is kept just in case it has
 * been used in a child theme. It does nothing.
 *
 * @deprecated 1.2
 */
function esquire_add_audio_support() {
	_deprecated_function( __FUNCTION__, '1.2' );
}

/**
 * Adjusts content_width value for video post formats.
 */
function esquire_content_width() {
	if ( has_post_format( 'video' ) ) {
		global $content_width;
		$content_width = 496;
	}
}
add_action( 'template_redirect', 'esquire_content_width' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since Esquire 1.1.1
 */
function esquire_wp_title( $title, $sep ) {
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
		$title .= " $sep " . sprintf( __( 'Page %s', 'esquire' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'esquire_wp_title', 10, 2 );
