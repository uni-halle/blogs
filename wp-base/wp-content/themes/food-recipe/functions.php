<?php
if (function_exists('add_theme_support')) add_theme_support( 'automatic-feed-links' );

if (function_exists('register_nav_menu')) {
	add_action( 'init', 'register_my_menu' );
	
	function register_my_menu() {
		register_nav_menus(
		array(
			'top-menu' => __('Top Menu'),
			'footer-menu' => __('Footer Menu')
			)
		);
	}
}

if ( function_exists('register_sidebar') ) {
    register_sidebar( array('name' => 'sidebar_left', 'before_widget' => '<div class="sidebarbox">', 'after_widget' => '</div>', 'before_title' => '<h3>', 'after_title' => '</h3>') );
    register_sidebar( array('name' => 'sidebar_right', 'before_widget' => '<div class="sidebarbox">', 'after_widget' => '</div>', 'before_title' => '<h3>', 'after_title' => '</h3>') );
}

add_filter('comment_form_default_fields', 'commentform_format');

function commentform_format($arg) {
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$arg['author'] = '<p class="comment-form-author"><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="22"' . $aria_req . ' /> <label for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">(required)</span>' : '' ) . '</label></p>';
	$arg['email'] = '<p class="comment-form-email"><input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="22"' . $aria_req . ' /> <label for="email">' . __( 'Mail' ) . ( $req ? ' <span class="required">(required)</span>' : '' ) . ' (will not be published)</label></p>';
	$arg['url'] = '<p class="comment-form-url"><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="22"' . $aria_req . ' /> <label for="url">' . __( 'Website' ) . '</label></p>';
    return $arg;
}

function custom_comment($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
	        <?php echo get_avatar( get_comment_author_email(), '32' ); ?>
			<strong><span <?php comment_class(); ?>><?php comment_author_link() ?></span></strong> | <a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('F jS, Y') ?> at <?php comment_time() ?></a> <?php edit_comment_link('e','',''); ?>
			<?php if ($comment->comment_approved == '0') : ?>
			<em>Your comment is awaiting moderation.</em>
			<?php endif; ?>
			<?php comment_text() ?>
            <small class="replycomment"><?php comment_reply_link(array('reply_text' => 'Reply to this comment', 'depth' => $depth, 'max_depth'=> $args['max_depth'])) ?></small>
       <?php
}

function custom_pingback($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
		?>
		<li <?php comment_class(); ?>  id="comment-<?php comment_ID() ?>">
			<?php comment_author_link() ?>       
		<?php
}

if ( ! isset( $content_width ) )
	$content_width = 950;

// Post Thumbnails
add_theme_support('post-thumbnails'); 
set_post_thumbnail_size( 75, 75, true );


// Custom Header Image
add_custom_image_header( '', 'foodrecipe_admin_header_style' );

// Your changeable header business starts here
define( 'HEADER_TEXTCOLOR', 'E8DFC7' ); // Hex color value, without leading octothorpe (#) - use the background color from logo.gif
define('HEADER_IMAGE', get_bloginfo('stylesheet_directory') . '/images/logo.gif'); // Default header image to use
define( 'HEADER_IMAGE_WIDTH', apply_filters( 'foodrecipe_header_image_width', 420 ) ); // Width to which WordPress will crop uploaded header images
define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'foodrecipe_header_image_height', 50 ) ); // Height to which WordPress will crop uploaded header images
define( 'NO_HEADER_TEXT', true ); // Do Not Allow text inside the header image.

// Add a way for the custom header to be styled in the admin panel that controls custom headers
	
if ( ! function_exists( 'foodrecipe_admin_header_style' ) ) :

function foodrecipe_admin_header_style() {
?>
<style type="text/css">
        #headimg {
            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
        }
		.appearance_page_custom-header #headimg {
		min-height: 0;
		}
</style>
<?php
}

endif;
?>