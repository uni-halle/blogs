<?php
/**
 * Aadya functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package Aadya
 * @subpackage Aadya
 * @since Aadya 1.0.0
 */

/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 625;

/**
 * Sets up theme defaults and registers the various WordPress features that
 * Aadya supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Aadya 1.0.0
 */
function aadya_setup() {

	// Load up our theme options page and related code. Options Framework	
	require_once(get_template_directory() . '/inc/options-panel.php');
	
	/*
	 * Makes Aadya available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Aadya, use a find and replace
	 * to change 'aadya' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'aadya', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'aadya' ) );	
	register_nav_menu( 'footer-menu', __( 'Footer Menu', 'aadya' ) );

	
	/*
	 * This theme supports custom background color and image, and here
	 * we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop	
}
add_action( 'after_setup_theme', 'aadya_setup' );

//Social Icon Box
require(get_template_directory() . '/inc/widgets/social-box-widget.php');

//Front Page Text
require(get_template_directory() . '/inc/widgets/front-page-thumbnail-text-widget.php');

//Front Page Feature Text
require(get_template_directory() . '/inc/widgets/front-page-feature-text-widget.php');

//Author Profile
require(get_template_directory() . '/inc/widgets/author-profile-widget.php');


function aadya_load_custom_widgets() {
	register_widget( 'aadya_socialiconbox_widget' );	
	register_widget( 'aadya_frontpage_thumbnail_text_widget' );	
	register_widget( 'aadya_frontpage_featured_text_widget' );	
	register_widget( 'aadya_author_profile_widget' );	
}
add_action('widgets_init', 'aadya_load_custom_widgets');


/**
 * Adds support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );
require_once( get_template_directory() . '/inc/wp_bootstrap_navwalker.php' );

/**
 * Enqueues scripts and styles for front-end.
 *
 * @since Aadya 1.0.0
 */
function aadya_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );


	/*
	 * Loads our special font CSS file.
	 *
	 * The use of Open Sans by default is localized. For languages that use
	 * characters not supported by the font, the font can be disabled.
	 *
	 * To disable in a child theme, use wp_dequeue_style()
	 * function mytheme_dequeue_fonts() {
	 *     wp_dequeue_style( 'aadya-fonts' );
	 * }
	 * add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );
	 */

	/* translators: If there are characters in your language that are not supported
	   by Open Sans, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'aadya' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language, translate
		   this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'aadya' );

		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => 'Open+Sans:400italic,700italic,400,700',
			'subset' => $subsets,
		);
		//wp_enqueue_style( 'aadya-fonts', add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" ), array(), null );
	}

	// Load JavaScripts
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '3.0.0', true );			
	
	// Load Stylesheets. Load bootstrap css as per theme option selected
	$theme_layout = of_get_option('theme_layout');	
	if($theme_layout=="wide") {
		wp_enqueue_style( 'bootstrap', get_template_directory_uri().'/css/bootstrap-wide.css' );
	} else {
		wp_enqueue_style( 'bootstrap', get_template_directory_uri().'/css/bootstrap.css' );
	}	
	wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/css/font-awesome.min.css' );
	wp_enqueue_style( 'bootstrap-social', get_template_directory_uri().'/css/bootstrap-social.css' );	

	/*
	 * Loads the Internet Explorer specific stylesheet.
	 */
	wp_enqueue_style( 'aadya-ie', get_template_directory_uri() . '/css/font-awesome-ie7.min.css');
	$wp_styles->add_data( 'aadya-ie', 'conditional', 'lt IE 9' );		
	
	/*
	 * Loads our main stylesheet.
	 */
	wp_enqueue_style( 'aadya-style', get_stylesheet_uri() );	
	wp_enqueue_style( 'aadya-style-carousel', get_template_directory_uri() . '/css/carousel.css');

}
add_action( 'wp_enqueue_scripts', 'aadya_scripts_styles' );

// queue up the necessary js
function aadya_admin_scripts($hooks)
{
	if ( 'widgets.php' == $hooks ) {
		wp_enqueue_media();			
		wp_enqueue_script( 'aadya-widgets', get_template_directory_uri() . '/js/widgets.js', array( 'jquery-ui-sortable' ) );
		wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/css/font-awesome.min.css' );	
		wp_enqueue_style( 'widget-frontpage-featured-text', get_template_directory_uri().'/css/widget-frontpage-featured-text.css' );		
	}
}
add_action('admin_enqueue_scripts', 'aadya_admin_scripts');

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Aadya 1.0.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function aadya_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'aadya' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'aadya_wp_title', 10, 2 );

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Aadya 1.0.0
 */
function aadya_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'aadya_page_menu_args' );

/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since Aadya 1.0.0
 */
function aadya_widgets_init() {

	// Header Right
	register_sidebar( array(
			'id' => 'aadya_header_right',
			'name' => __( 'Header Right', 'aadya' ),
			'description' => __( 'This sidebar is located on the right-hand side of header area.', 'aadya' ),
			'before_widget' => '<div id="%1$s" class="header-widget header-widget-right %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="header-widget-title">',
			'after_title' => '</h4>',
		) );
		
	register_sidebar( array(
			'id' => 'aadya_header_left',
			'name' => __( 'Header Left', 'aadya' ),
			'description' => __( 'This sidebar is located on the left-hand side of header area. Just after logo.', 'aadya' ),
			'before_widget' => '<div id="%1$s" class="header-widget header-widget-left %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="header-widget-title">',
			'after_title' => '</h4>',
		) );		
		
	// Sidebar Right
	register_sidebar( array(
			'id' => 'aadya_sidebar_right',
			'name' => __( 'Sidebar Right', 'aadya' ),
			'description' => __( 'This sidebar is located on the right-hand side of each page. This is Default Side bar.', 'aadya' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>',
		) );
		
	// Sidebar Left
	register_sidebar( array(
			'id' => 'aadya_sidebar_left',
			'name' => __( 'Sidebar Left', 'aadya' ),
			'description' => __( 'This sidebar is located on the left-hand side of each page.', 'aadya' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>',
		) );		
		
	// Sidebar Footer
	register_sidebar( array(
			'id' => 'extended_footer_one',
			'name' => __( 'Footer One', 'aadya' ),
			'description' => __( 'This sidebar is located on Footer and its First section. Occupies 4 Columns out of 12.', 'aadya' ),
			'before_widget' => '<div class="row"><div class="col-md-12 footer-widget">',
			'after_widget' => '</div></div>',
			'before_title' => '<h4 class="footer-widget-title">',
			'after_title' => '</h4>',
		) );	
	// Sidebar Footer
	register_sidebar( array(
			'id' => 'extended_footer_two',
			'name' => __( 'Footer Two', 'aadya' ),
			'description' => __( 'This sidebar is located on Footer and its Second section.Occupies 4 Columns out of 12.', 'aadya' ),
			'before_widget' => '<div class="row"><div class="col-md-12 footer-widget">',
			'after_widget' => '</div></div>',
			'before_title' => '<h4 class="footer-widget-title">',
			'after_title' => '</h4>',
		) );
	// Sidebar Footer
	register_sidebar( array(
			'id' => 'extended_footer_three',
			'name' => __( 'Footer Three', 'aadya' ),
			'description' => __( 'This sidebar is located on Footer and its Third section. Occupies 4 Columns out of 12.', 'aadya' ),
			'before_widget' => '<div class="row"><div class="col-md-12 footer-widget">',
			'after_widget' => '</div></div>',
			'before_title' => '<h4 class="footer-widget-title">',
			'after_title' => '</h4>',
		) );		
	
	if(of_get_option('extended_footer_count')=='4') {
			register_sidebar( array(
			'id' => 'extended_footer_four',
			'name' => __( 'Footer Four', 'aadya' ),
			'description' => __( 'This sidebar is located on Footer and its Third section. Occupies 4 Columns out of 12.', 'aadya' ),
			'before_widget' => '<div class="row"><div class="col-md-12 footer-widget">',
			'after_widget' => '</div></div>',
			'before_title' => '<h4 class="footer-widget-title">',
			'after_title' => '</h4>',
		) );
	} else {
		unregister_sidebar( 'extended_footer_four' );
	}	
	
	//Here we are providing widget area as a row. 
	//So we must calculate the number of widgets first in this row to adjust the number of columns for each widget.
	//if 3 or less widgets, 4 columns will be alloated, else 3 columns ... not sure how this is working I just gave it a try and it worked ;)
	$mysidebars = wp_get_sidebars_widgets();
	if(isset($mysidebars['aadya_front_page_widget_row_one'])){
	$total_widgets = count( $mysidebars['aadya_front_page_widget_row_one'] );	
	if($total_widgets <= 3) $cols = 4;
	else $cols = 3;	
	} else $cols = 3;	
	
	//Front Page Widget Row Section	1
	register_sidebar( array(
		'id' => 'aadya_front_page_widget_row_one',
		'name' => __( 'Front Page Widget Row One', 'aadya' ),
		'description' => __( 'This widget area is active only on frontpage and first widget area/row.', 'aadya' ),
		'before_widget' => '<div id="%1$s" class="widget col-xs-12 col-sm-6 col-md-'.$cols.' front-page-row-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="front-page-widget-title">',
		'after_title' => '</h4>',
	) );
	
	if(isset($mysidebars['aadya_front_page_widget_row_two'])){
	$total_widgets = count( $mysidebars['aadya_front_page_widget_row_two'] );	
	if($total_widgets <= 3) $cols = 4;
	else $cols = 3;		
	} else $cols = 3;	
	//Front Page Widget Row Section	2
	register_sidebar( array(
		'id' => 'aadya_front_page_widget_row_two',
		'name' => __( 'Front Page Widget Row Two', 'aadya' ),
		'description' => __( 'This widget area is active only on frontpage and second widget area/row.', 'aadya' ),
		'before_widget' => '<div id="%1$s" class="widget col-xs-12 col-sm-6 col-md-'.$cols.' front-page-row-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="front-page-widget-title">',
		'after_title' => '</h4>',
	) );	
	
	if(isset($mysidebars['aadya_front_page_widget_row_three'])){
	$total_widgets = count( $mysidebars['aadya_front_page_widget_row_three'] );	
	if($total_widgets <= 3) $cols = 4;
	else $cols = 3;
	} else $cols = 3;		
	if(of_get_option('front_page_widget_section_count')=='3' || of_get_option('front_page_widget_section_count')=='4') {	
		//Front Page Widget Row Section	3
		register_sidebar( array(
			'id' => 'aadya_front_page_widget_row_three',
			'name' => __( 'Front Page Widget Row Three', 'aadya' ),
			'description' => __( 'This widget area is active only on frontpage and third widget area/row.', 'aadya' ),
			'before_widget' => '<div id="%1$s" class="widget col-md-'.$cols.' front-page-row-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="front-page-widget-title">',
			'after_title' => '</h4>',
		) );	
	}
	
	if(of_get_option('front_page_widget_section_count')=='4') {
		if(isset($mysidebars['aadya_front_page_widget_row_three'])){
		$total_widgets = count( $mysidebars['aadya_front_page_widget_row_four'] );	
		if($total_widgets <= 3) $cols = 4;
		else $cols = 3;	
		} else $cols = 3;		
		//Front Page Widget Row Section	4
		register_sidebar( array(
			'id' => 'aadya_front_page_widget_row_four',
			'name' => __( 'Front Page Widget Row Four', 'aadya' ),
			'description' => __( 'This widget area is active only on frontpage and fourth widget area/row.', 'aadya' ),
			'before_widget' => '<div id="%1$s" class="widget col-md-'.$cols.' front-page-row-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="front-page-widget-title">',
			'after_title' => '</h4>',
		) );	
	} else {
		unregister_sidebar( 'aadya_front_page_widget_row_four' );
	}	

	
	
}
add_action( 'widgets_init', 'aadya_widgets_init' );

/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Aadya 1.0.0
 */
function aadya_content_nav( $html_id ) {
	//Call Custom Pagination here instead of calling it on each and every page where its required
	aadya_custom_pagination();	
}


add_filter('get_avatar','aadya_change_avatar_css');

function aadya_change_avatar_css($class) {
$class = str_replace("class='avatar", "class='media-object avatar img-circle", $class) ;
return $class;
}

/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own aadya_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Aadya 1.0.0
 */

function aadya_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'aadya' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'aadya' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	
	<li <?php comment_class("media"); ?> id="li-comment-<?php comment_ID(); ?>">
	<div class="panel panel-default">
		
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<a class="pull-left link-avatar" href="#">
			<?php echo get_avatar( $comment, 64 ); ?>
			</a>
			<div class="media-body">
			<p class="media-heading">		
				<?php printf( '<cite class="fn">%1$s %2$s</cite>',
							  get_comment_author_link(),
							  // If current post author is also comment author, make it known visually.
							 ( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'aadya' ) . '</span>' : '');
				?>				
				<?php printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
							esc_url( get_comment_link( $comment->comment_ID ) ),
							get_comment_time( 'c' ),
							/* translators: 1: date, 2: time */
							sprintf( __( '%1$s at %2$s', 'aadya' ), get_comment_date(), get_comment_time() ));
				?>
			</p>
		 	<?php if ( '0' == $comment->comment_approved ) : ?>
				<div class="alert alert-warning">
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'aadya' ); ?></p>
				</div>	
			<?php endif; ?>

			<div class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'aadya' ), '<p class="edit-link">', '</p>' ); ?>
			</div><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'aadya' ), 'after' => ' <span>&rarr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</div>		
		
	
		</article><!-- #comment-## -->

	<?php
		break;
	endswitch; // end comment_type check
}
 

/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own aadya_entry_meta() to override in a child theme.
 *
 * @since Aadya 1.0.0
 */
function aadya_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'aadya' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'aadya' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'aadya' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'aadya' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'aadya' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'aadya' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}


/**
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since Aadya 1.0.0
 *
 * @param array Existing class values.
 * @return array Filtered class values.
 */
function aadya_body_class( $classes ) {
	$background_color = get_background_color();

	if (is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';

	if (is_page_template( 'page-templates/front-page-with-slider.php' )) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
	}
	
	if ( is_active_sidebar( 'aadya_sidebar_right' ) && is_active_sidebar( 'aadya_sidebar_left' ) )
		$classes[] = 'two-sidebars';	

	if ( empty( $background_color ) )
		$classes[] = 'custom-background-empty';
	elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
		$classes[] = 'custom-background-white';

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'aadya-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';
		
	$body_background = of_get_option('body_background');
	if(!empty($body_background))
		$classes[] = 'aadya-custom-background';		

	return $classes;
}
add_filter( 'body_class', 'aadya_body_class' );

/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Aadya 1.0.0
 */
function aadya_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'aadya_sidebar_right' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'aadya_content_width' );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @since Aadya 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function aadya_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', 'aadya_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since Aadya 1.0.0
 */
function aadya_customize_preview_js() {
	wp_enqueue_script( 'aadya-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20120827', true );
}
add_action( 'customize_preview_init', 'aadya_customize_preview_js' );

function aadya_nav_menu_css_class( $classes ) {
	if ( in_array('current-menu-item', $classes ) OR in_array( 'current-menu-ancestor', $classes ) )
		$classes[]	=	'active';

	return $classes;
}
add_filter( 'nav_menu_css_class', 'aadya_nav_menu_css_class' );

// Create a graceful fallback to wp_page_menu
function aadya_theme_page_menu() {

	$args = array(
	'sort_column' => 'menu_order, post_title',
	'menu_class'  => 'navbar-link',
	'include'     => '',
	'exclude'     => '',
	'echo'        => true,
	'show_home'   => false,
	'link_before' => '',
	'link_after'  => '',
	'items_wrap' => ''
	);

	wp_page_menu($args);
}

function aadya_custom_pagination($pages = '', $range = 2)
{  
     $showitems = ($range * 2)+1;  
	 
     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo "<div class='pagination-centered'><ul class=\"pagination\">";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."'>&laquo;</a></li>";
         //if($paged > 1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Prev</a></li>";		 
		 if($paged > 1 && $showitems < $pages) echo "<li>".get_previous_posts_link("&lsaquo; Prev")."</li>";	 		 

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<li class='active'><a href=''>".$i."</a></li>":"<li><a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a></li>";
             }
         }
		 
		 if ($paged < $pages && $showitems < $pages) echo "<li>".get_next_posts_link("Next &rsaquo;")."</li>";
         //if ($paged < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged + 1)."'>Next &rsaquo;</a></</li>";  		 
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($pages)."'>&raquo;</a></li>";
         echo "</ul></div> <!-- .pagination-centered -->";
     }
}	

function aadya_wp_head() {
	$body_background = of_get_option('body_background');	
	$customcss = array();
	$bcss = '';
	if(!empty($body_background['color']) || !empty($body_background['image'])) {
		$bcss = 'body.aadya-custom-background { background:';
		$bcss .= (!empty($body_background['color'])) ? " ".$body_background['color'] : '';
		$bcss .= (!empty($body_background['image'])) ? " url('".$body_background['image']."')" : '';
		$bcss .= (!empty($body_background['image']) && !empty($body_background['repeat'])) ? " ".$body_background['repeat'] : '';
		$bcss .= (!empty($body_background['image']) && !empty($body_background['attachment'])) ? " ".$body_background['attachment'] : '';
		$bcss .= (!empty($body_background['image']) && !empty($body_background['position'])) ? " ".$body_background['position'] : '';
		$bcss .= ';}';
		$customcss[] = $bcss;
	}
	
	$header_background = of_get_option('site_header_background');	
	if(!empty($header_background['color']) || !empty($header_background['image'])) {
		$bcss = '.site-header { background:';
		$bcss .= (!empty($header_background['color'])) ? " ".$header_background['color'] : '';
		$bcss .= (!empty($header_background['image'])) ? " url('".$header_background['image']."')" : '';
		$bcss .= (!empty($header_background['image']) && !empty($header_background['repeat'])) ? " ".$header_background['repeat'] : '';
		$bcss .= (!empty($header_background['image']) && !empty($header_background['attachment'])) ? " ".$header_background['attachment'] : '';
		$bcss .= (!empty($header_background['image']) && !empty($header_background['position'])) ? " ".$header_background['position'] : '';
		$bcss .= ';}';	
		$customcss[] = $bcss;
	}

if(!empty($customcss)) { ?>
<style type="text/css" media="all"> 
<?php 
	$cnt = count($customcss);
	foreach($customcss as $index => $css) {
		echo $css;
		if($index < $cnt-1) echo "\r\n";
	}
?> 
</style>	
<?php }

}
add_action( 'wp_head', 'aadya_wp_head', 100);


function aadya_wp_footer() {
	if(of_get_option('add_code_in_wp_footer') == '1'):
		if('' != trim(of_get_option('code_for_wp_footer'))):
			echo of_get_option('code_for_wp_footer');
		endif;
	endif;

	?>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js" type="text/javascript"></script>
	<![endif]-->
	<!-- Bootstrap 3 dont have core support to multilevel menu, we need this JS to implement that -->
	<script src="<?php echo get_template_directory_uri(); ?>/js/theme-menu.js" type="text/javascript"></script>
	<?php
		
}
add_action( 'wp_footer', 'aadya_wp_footer',100);

add_filter('the_excerpt','aadya_excerpt');
function aadya_excerpt(){
	global $post;
	$link='<span class="readmore"><a href="'.get_permalink().'" > Read More +</a></span>';
	$excerpt=get_the_excerpt();		
	if ( preg_match('/<!--more(.*?)?-->/', $post->post_content) ) {	
		echo $excerpt.$link;
	} else {
		echo $excerpt;
	}
}
function aadya_excerpt_read_more($text) {
   return '  <span class="readmore"><a href="'.get_permalink().'" > Read More +</a></span>';
}
add_filter('excerpt_more', 'aadya_excerpt_read_more');

add_filter( 'the_content_more_link', 'my_more_link', 10, 2 );

function my_more_link( $more_link, $more_link_text ) {
	return str_replace( $more_link_text, 'Continue reading &rarr;', $more_link );
}

function aadya_custom_excerpt_length($length) {
	return 85;
}
add_filter('excerpt_length', 'aadya_custom_excerpt_length');


function aadya_get_branding() {	
	$note = "<span class=\"brand-note\"> | Design by <a href=\"http://www.opencodez.com/\" target=\"_blank\">OpenCodez</a></span>";
	return $note;
}


//Custom Functions for Widget area

function aadya_widget_field( $widget, $args = array(), $value ) {
	$args = wp_parse_args($args, array ( 
		'field' => 'title',
		'type' => 'text',
		'label' => '',
		'desc' => '',
		'class' => 'widefat',
		'options' => array(),
		'label_all' => '',
		'ptag' => true,
		) );
	extract( $args, EXTR_SKIP );

	$field_id =  esc_attr( $widget->get_field_id( $field ) );
	$field_name = esc_attr( $widget->get_field_name( $field ) );
	
	if ( $ptag )
		echo '<p>';
	if ( ! empty( $label ) ) {
		echo '<label for="' . $field_id . '">';
		echo $label . '</label>';
	}
	switch ( $type ) {
		case 'media':
			echo '<input class="media-upload-url" id="' . $field_id;
			echo '" name="' . $field_name . '" type="hidden" value="';
			echo esc_attr( $value ) . '" />';
			echo '<input class="media-upload-btn" id="' . $field_id;
			echo '_btn" name="' . $field_name . '_btn" type="button" value="'. __( 'Choose', 'aadya' ) . '">';
			echo '<input class="media-upload-del" id="' . $field_id;
			echo '_del" name="' . $field_name . '_del" type="button" value="'. __( 'Remove', 'aadya' ) . '">';
			break;
		case 'text':
		case 'hidden':
			echo '<input class="' . $class . '" id="' . $field_id;
			echo '" name="' . $field_name . '" type="' . $type .'" value="';
			echo esc_attr( $value ) . '" />';
			break;
		case 'url':
			echo '<input class="' . $class . '" id="' . $field_id;
			echo '" name="' . $field_name . '" type="' . $type .'" value="';
			echo esc_url( $value ) . '" />';
			break;
		case 'textarea':
			echo '<textarea class="' . $class . '" id="' . $field_id;
			echo '" name="' . $field_name . '" type="' . $type .'" row="10" col="20">';
			echo esc_textarea( $value ) . '</textarea>';
			break;
		case 'number':
			echo '<input class="' . $class . '" id="' . $field_id;
			echo '" name="' . $field_name . '" type="text" size="3" value="';
			echo esc_attr( $value ) . '" />';
			break;
		case 'checkbox':
			echo '<input class="' . $class . '" id="' . $field_id;
			echo '" name="' . $field_name . '" type="' . $type .'" value="1" ';
			echo checked( '1', $value, false ) . ' /> ';
			echo '<label for="' . $field_id . '"> ' . $desc . '</label>';
			break;
		case 'label':
			echo '<label for="' . $field_id . '"> ' . $desc . '</label>';
			break;			
		case 'category':
			echo '<select id="' . $field_id . '" name="' . $field_name . '">';
			if ( ! empty( $label_all ) ) {
				if ( 0 == $value )
					$selected = 'selected="selected"';				
			 	else
				 	$selected = '';
			 	echo '<option value="0" ' . $selected;
			 	echo '>' . $label_all . '</option>';				
			}
			foreach ( $options as $option ) {
				if ( $option->term_id == $value )
					$selected = 'selected="selected"';
				else
					$selected = '';	
				echo '<option value="' . $option->term_id . '" ' . $selected;
				echo '>' . $option->name . '</option>';
			}
			echo '</select>';
			break;
		case 'select':
			echo '<select id="' . $field_id . '" name="' . $field_name . '">';
			foreach ( $options as $option ) {
				if ( $option['key'] == $value )
					$selected = 'selected="selected"';
				else
					$selected = '';	
				echo '<option value="' . $option['key'] . '" ' . $selected;
				echo '>' . $option['name'] . '</option>';
			}
			echo '</select>';
			break;			
		case 'icon-select':
			ksort($options, SORT_STRING);
			echo '<div class="icon-select"><select class="widget-icon widget-lib-font-awesome" id="' . $field_id . '" name="' . $field_name . '">';
			foreach ( $options as $k=>$v ) {
				if ( $k == $value )
					$selected = 'selected="selected"';
				else
					$selected = '';	
				echo '<option value="' . $k . '" ' . $selected. '></i>' . $v.'&nbsp;&nbsp;'.$k . '</option>';
			}
			echo '</select></div>';
			break;		

		// Color picker
		case "color":
			$default_color = '';
			echo '<input class="' . $class . '" id="' . $field_id;
			echo '" name="' . $field_name . '" type="text" value="';
			echo esc_attr( $value ) . '"'.$default_color.' />';			
 	
			break;			
	}
	if ( $ptag )
		echo '</p>';
}

function aadya_thumbnail_array() {
	$sizes = array (
		array(	'key' => '',
				'name' => __( 'Thumbnail', 'aadya' ) ),
		array(	'key' => 'medium',
				'name' => __( 'Medium', 'aadya' ) ),
		array(	'key' => 'large',
				'name' => __( 'Large', 'aadya' ) ),
		array(	'key' => 'full',
				'name' => __( 'Full', 'aadya' ) ),
		array(	'key' => 'custom',
				'name' => __( 'Custom', 'aadya' ) ),
		array(	'key' => 'none',
				'name' => __( 'None', 'aadya' ) ),
	);
	global $_wp_additional_image_sizes;

	if ( isset( $_wp_additional_image_sizes ) )
		foreach( $_wp_additional_image_sizes as $name => $item) 
			$sizes[] = array( 'key' => $name, 'name' => $name );
	return apply_filters( 'aadya_thumbnail_array', $sizes );
}

function aadya_thumbnail_size( $option, $x = 96, $y = 96 ) {

	if ( empty( $option ) )
		return 'thumbnail';
	elseif ( 'custom' == $option ) {
		if (($x > 0) && ($y > 0) )
			return array( $x, $y);
		else
			return 'thumbnail';		
	}
	else 
		return $option;
}

function aadya_get_sidebar_cols( ) {	
	$col = 4;	
	return $col;
}

function aadya_get_content_cols( ) {	
	$col = 8;
	$layout = of_get_option('page_layouts');
	if($layout == "sidebar-content-sidebar" || $layout == "content-sidebar-sidebar") {
		$col = 6;
	} else if($layout == "full-width") {
		$col = 12;
	}		
	return $col;
}

?>
