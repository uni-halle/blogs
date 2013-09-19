<?php
/* atmosphere theme by Digital Raindrops */

if ( ! isset( $content_width ) )
	$content_width = 640;

$themename = 'Atmosphere 2010';
$themeOptions = get_option( 'drcms_theme_options' );

add_action( 'after_setup_theme', 'atmosphere_setup' );

if ( ! function_exists( 'atmosphere_setup' ) ):
function atmosphere_setup() {

	add_editor_style();
	add_theme_support( 'post-thumbnails' );  
	set_post_thumbnail_size( 120, 90, true );
	if ( function_exists( 'add_image_size' ) ) {
		add_image_size( 'single-post-thumbnail', 400, 9999 );
	}
	
	add_theme_support( 'automatic-feed-links' );
	load_theme_textdomain( 'atmosphere', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	register_nav_menus( array(
		'primary' => __( 'Below Header', 'atmosphere' ),
		'secondary' => __( 'Above Header', 'atmosphere' ),
	) );

	add_custom_background();

	define( 'HEADER_TEXTCOLOR', '' );
	define( 'HEADER_IMAGE', '%s/images/header.jpg' );
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'atmosphere_header_image_width', 724 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'atmosphere_header_image_height', 314 ) );

	add_custom_image_header('header_style', 'admin_header_style');
}
endif;

if ( ! function_exists( 'header_style' ) ) :
function header_style() {
    ?><style type="text/css">
        #wrapper-jpg {
            background: url(<?php header_image(); ?>);
        }
    </style><?php
}
endif;	

if ( ! function_exists( 'admin_header_style' ) ) :
function admin_header_style() {
    ?><style type="text/css">
        #wrapper-jpg {
            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
        }
    </style><?php
}
endif;

function drcms_insert_category($taxonomy,$catName){
	if (!$taxonomy || !$catName) exit;
	/* if we have a category name then insert the category */
	if(!term_exists($catName, $taxonomy) && $catName){
		$cat_defaults = array(
			'name' => $catName,
			'description' => $catName);
		$temp = wp_insert_term($catName, $taxonomy, $cat_defaults);
	}
}
drcms_insert_category('category','gallery');
drcms_insert_category('category','asides');

function drcms_list_images($path){
    $list = array();
    $dir_handle = @opendir($path) or die("Unable to open $path"); 
    while($file = readdir($dir_handle)){ 
        if($file == "." || $file == ".."){continue;} 
        $filename = explode(".",$file); 
        $cnt = count($filename); $cnt--; $ext = $filename[$cnt]; 
        if(strtolower($ext) == ('png' || 'jpg')){
			if (!strpos($file, '-thumbnail') > 0) {
				array_push($list, $file);
			}
		}
    }
    return $list;
}

if (is_admin() && file_exists(TEMPLATEPATH. '/theme-options.php')) include_once(TEMPLATEPATH. '/theme-options.php');
if (file_exists(STYLESHEETPATH. '/htmLawed.php')) include_once ( STYLESHEETPATH . '/htmLawed.php' );

function clean_the_content( $content )
	{
		$szPostContent = $content;
		$szRemoveFilter = array( "~<p[^>]*>\s?</p>~", "~<a[^>]*>\s?</a>~", "~<font[^>]*>~", "~<\/font>~", "~<span[^>]*>\s?</span>~" );
		$szPostContent = preg_replace( $szRemoveFilter, '' , $szPostContent);
		$szPostContent = htmLawed($szPostContent);
		return $szPostContent;
}	
add_filter('the_content', 'clean_the_content');

function atmosphere_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'atmosphere_page_menu_args' );

function atmosphere_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'atmosphere_excerpt_length' );

function atmosphere_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Read the rest of this entry <span class="meta-nav">&rarr;</span>', 'atmosphere' ) . '</a>';
}

function atmosphere_auto_excerpt_more( $more ) {
	return ' &hellip;' . atmosphere_continue_reading_link();
}
add_filter( 'excerpt_more', 'atmosphere_auto_excerpt_more' );

function atmosphere_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= atmosphere_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'atmosphere_custom_excerpt_more' );

function atmosphere_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'atmosphere_remove_gallery_css' );

if ( ! function_exists( 'atmosphere_comment' ) ) :

function atmosphere_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">says:</span>', 'atmosphere' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'atmosphere' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'atmosphere' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'atmosphere' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'atmosphere' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'atmosphere'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

 function atmosphere_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'atmosphere' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'atmosphere' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'atmosphere' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area', 'atmosphere' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Tertiary Widget Area', 'atmosphere' ),
		'id' => 'tertiary-widget-area',
		'description' => __( 'The tertiary widget area', 'atmosphere' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'atmosphere' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'atmosphere' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'atmosphere' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'atmosphere' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'atmosphere' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'atmosphere' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'atmosphere' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'atmosphere' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'atmosphere_widgets_init' );

function atmosphere_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'atmosphere_remove_recent_comments_style' );

if ( ! function_exists( 'atmosphere_posted_in' ) ) :

function atmosphere_posted_in() {
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'atmosphere' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'atmosphere' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'atmosphere' );
	}
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;

if ( ! function_exists( 'atmosphere_posted_on' ) ) :

function atmosphere_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'atmosphere' ),
	'meta-prep meta-prep-author',sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
		get_permalink(),esc_attr( get_the_time() ),
		get_the_date()),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'atmosphere' ), get_the_author() ),
		get_the_author()));
}
endif;