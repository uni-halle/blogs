<?php
/**
 * bueno functions and definitions
 *
 * @package bueno
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 900; /* pixels */

// The excerpt based on words
if ( !function_exists('bueno_string_limit_words') ) {
	function bueno_string_limit_words($string, $word_limit) {
	  $words = explode(' ', $string, ($word_limit + 1));
	    if(count($words) > $word_limit) array_pop($words);
	    $res = implode(' ', $words);
	    $res = trim ($res);
	    $res = preg_replace("/[.]+$/", "", $res);
	    if ( '' !=  $res) {
	    	return $res . '... ';
		} else {
			return $res;
		}
	}
}
/*
 * Load Files.
 */

//Loading options.php for theme customizer
include_once( get_template_directory() . '/options.php');

//Loads the Options Panel
if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/options/' );
	include_once( get_template_directory() . '/options/options-framework.php' );
}
	


/*
 * Load Jetpack compatibility file.
 */
require( get_template_directory() . '/inc/jetpack.php' );

if ( ! function_exists( 'bueno_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function bueno_setup() {

	$defaults = array(
		'default-color'          => '',
		'default-image'          => '',
		'wp-head-callback'       => '_custom_background_cb',
		'admin-head-callback'    => '',
		'admin-preview-callback' => ''
	);

	add_theme_support( 'custom-background', $defaults );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/extras.php' );

	/**
	 * Customizer additions
	 */
	require( get_template_directory() . '/inc/customizer.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on bueno, use a find and replace
	 * to change 'bueno' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'bueno', get_template_directory() . '/languages' );


	/**
	 * Add editor styles
	 */

	add_editor_style( 'css/editor-style.css' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * This theme uses wp_nav_menu() in two locations.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'bueno' ),
		'top' => __( 'Top Page Menu', 'bueno' ),
		'footer' => __( 'Footer Menu', 'bueno' )
	) );

	/*
	 * This theme supports all available post formats.
	 * See http://codex.wordpress.org/Post_Formats
	 *
	 * Structured post formats are formats where Twenty Thirteen handles the
	 * output instead of the default core HTML output.
	 */
	add_theme_support( 'structured-post-formats', array(
		'link', 'video'
	) );
	add_theme_support( 'post-formats', array(
		'aside', 'audio', 'chat', 'gallery', 'image', 'quote', 'status'
	) );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );

	/**
	 * Add image sizes
	 */

	if ( function_exists( 'add_theme_support' ) ) { // Added in 2.9
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'slider-post-thumbnail', 770, 360, true ); // Slider Thumbnail
		add_image_size( 'fullwidth-post-thumbnail', 1170, 560, true ); // Fullwidth thumbnail
		add_image_size( 'large-thumb', 762, 360, true ); // Large Thumbnail for one column output
		add_image_size( 'featured-thumb', 362, 172, true ); // Featured Thumbnail for two column output
		add_image_size( 'related-thumb', 160, 160, true ); // Realted Post Image output
		add_image_size( 'portfolio-thumb', 270, 320, true ); // Realted Post Image output
	}

}
endif; // bueno_setup
add_action( 'after_setup_theme', 'bueno_setup' );



/**
 * Register widgetized area and update sidebar with default widgets
 */
function bueno_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'bueno' ),
		'id'            => 'primary-sidebar',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'bueno_widgets_init' );



/**
 * Enqueue scripts and styles
 */
function bueno_styles() {
	global $wp_styles;

	// Bootstrap styles
	wp_register_style( 'bueno-bootstrap', get_template_directory_uri() . '/bootstrap/css/bootstrap.css');
	wp_register_style( 'bueno-responsive', get_template_directory_uri() . '/bootstrap/css/responsive.css');
	wp_enqueue_style('bueno-bootstrap');
	wp_enqueue_style('bueno-responsive');

	// Main stylesheet
	wp_enqueue_style( 'bueno-style', get_stylesheet_uri() );

	// Slider styles
	wp_register_style( 'flexslider', get_template_directory_uri() . '/css/flexslider.css');
	wp_enqueue_style('flexslider');

	// Popup styles
	wp_register_style( 'magnific', get_template_directory_uri() . '/css/magnific-popup.css');
	wp_enqueue_style('magnific');

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'bueno_ie', get_template_directory_uri() . '/css/ie.css' );
	$wp_styles->add_data( 'bueno_ie', 'conditional', 'lt IE 9' );

}

function bueno_scripts() {

	wp_enqueue_script( 'bueno-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'bueno-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'bueno-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

	// Menu scripts
	wp_enqueue_script('superfish', get_template_directory_uri() . '/js/superfish.js', array('jquery'), '1.4.8', true);
	wp_enqueue_script('mobilemenu', get_template_directory_uri() . '/js/jquery.mobilemenu.js', array('jquery'), '1.0', true);
	wp_enqueue_script('sf_Touchscreen', get_template_directory_uri() . '/js/sfmenu-touch.js', array('jquery'), '1.0', true);

	// Slider
	wp_enqueue_script('flexslider', get_template_directory_uri() . '/js/jquery.flexslider.js', array('jquery'), '2.1', true);
	
	// PopUp
	wp_enqueue_script('magnific', get_template_directory_uri() . '/js/jquery.magnific-popup.js', array('jquery'), '0.8.9', true);

	// Bootstrap JS
	wp_enqueue_script('bootstrap-custom', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'), '1.0', true);

	// Easing effect
	wp_enqueue_script('jquery-easing', get_template_directory_uri() . '/js/jquery.easing.1.3.js', array('jquery'), '1.3', true);

	// Debounced Resize plugin
	wp_enqueue_script('jquery-debounced-resize', get_template_directory_uri() . '/js/jquery.debouncedresize.js', array('jquery'), '1.0', true);

	// Custom Script File
	wp_enqueue_script('custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), '1.0', true);

}
add_action( 'wp_enqueue_scripts', 'bueno_scripts', 10, 2 );
add_action( 'wp_enqueue_scripts', 'bueno_styles', 10, 2 );


/**
 * Adding class 'active' to current menu item
 */

add_filter( 'nav_menu_css_class', 'bueno_active_item_classes', 10, 2 );

function bueno_active_item_classes($classes = array(), $menu_item = false){

    if(in_array('current-menu-item', $menu_item->classes)){
        $classes[] = 'active';
    }

    return $classes;
}


/*-----------------------------------------------------------------------------------*/
/*	Custom Gallery
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'bueno_featured_gallery' ) ) :

function bueno_featured_gallery() {
	$pattern = get_shortcode_regex();

	if ( preg_match( "/$pattern/s", get_the_content(), $match ) && 'gallery' == $match[2] ) {
		add_filter( 'shortcode_atts_gallery', 'bueno_gallery_atts' );
		echo do_shortcode_tag( $match );
	}
}
endif;

function bueno_gallery_atts( $atts ) {
	$atts['size'] = 'large';
	return $atts;
}

/*-----------------------------------------------------------------------------------*/
/*	Get link URL for link post type
/*-----------------------------------------------------------------------------------*/

function bueno_get_link_url() {
	$has_url = get_the_post_format_url();

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}



/*-----------------------------------------------------------------------------------*/
/*	Breabcrumbs
/*-----------------------------------------------------------------------------------*/
if (! function_exists( 'bueno_breadcrumb' )) {
	function bueno_breadcrumb() {
	  $showOnHome = 0; // 1 - show "breadcrumbs" on home page, 0 - hide
	  $delimiter = '<li class="divider">/</li>'; // divider
	  $home = 'Home'; // text for link "Home"
	  $showCurrent = 1; // 1 - show title current post/page, 0 - hide
	  $before = '<li class="active">'; // open tag for active breadcrumb
	  $after = '</li>'; // close tag for active breadcrumb

  	  global $post;
  		$homeLink = home_url();

 	 if (is_front_page()) {

    	if ($showOnHome == 1) echo '<ul class="breadcrumb breadcrumb__t"><li><a href="' . $homeLink . '">' . $home . '</a><li></ul>';

  	} else {

    	echo '<ul class="breadcrumb breadcrumb__t"><li><a href="' . $homeLink . '">' . $home . '</a></li> ' . $delimiter . ' ';
	
	if ( is_home() ) {
		echo $before . 'Blog' . $after;
	} elseif ( is_category() ) {
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
      echo $before . 'Category Archives: "' . single_cat_title('', false) . '"' . $after;

    } elseif ( is_search() ) {
      echo $before . 'Search for: "' . get_search_query() . '"' . $after;

    } elseif ( is_day() ) {
      echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> ' . $delimiter . ' ';
      echo '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a></li> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;

    } elseif ( is_month() ) {
      echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;

    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;

    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
      	$post_name = get_post_type();
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<li><a href="' . $homeLink . '/' . $post_name . '/">' . $post_type->labels->singular_name . '</a></li>';
        if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
        echo $cats;
        if ($showCurrent == 1) echo $before . get_the_title() . $after;
      }

    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;

    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<li><a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a></li>';
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

    } elseif ( is_page() && !$post->post_parent ) {
      if ($showCurrent == 1) echo $before . get_the_title() . $after;

    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        echo $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
      }
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

    } elseif ( is_tag() ) {
      echo $before . 'Tag Archives: "' . single_tag_title('', false) . '"' . $after;

    } elseif ( is_author() ) {
      global $author;
      $userdata = get_userdata($author);
      echo $before . 'by ' . $userdata->display_name . $after;

    } elseif ( is_404() ) {
      echo $before . '404' . $after;
    }
	/*
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __(' Page') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
	*/

    echo '</ul>';

  }
} // end breadcrumbs()
}

/*-----------------------------------------------------------------------------------*/
/*	Sidebar socials
/*-----------------------------------------------------------------------------------*/
add_action( 'before_sidebar', 'bueno_sidebar_socials', 10 );

function bueno_sidebar_socials() {
	$social_counter = 0;
	$social_nets = array('g_twitter_url', 'g_facebook_url', 'g_linkedin_url', 'g_google_url', 'g_pinterest_url', 'g_rss_url');
	foreach ($social_nets as $single_net) {
		if ( '' != of_get_option($single_net) ) {
			$social_counter++;
		}
	}
?>
<aside class="sidebar_socials widget">
<?php
	if ( ( '' != of_get_option( 'g_sidebar_socials_title' ) ) && ( 0 != $social_counter ) ) {
		?>
		<h3 class="widget-title"><?php echo of_get_option('g_sidebar_socials_title'); ?></h3>
		<?php
	}
?>
	<div class="sidebar_socials_icons">
		<?php 
			if ( '' != of_get_option( 'g_twitter_url' ) ) {
		?>
			<a href="<?php echo esc_url( of_get_option( 'g_twitter_url' ) ); ?>"><i class="icon-twitter"></i></a>
		<?php
			}
		?>
		<?php 
			if ( '' != of_get_option( 'g_facebook_url' ) ) {
		?>
			<a href="<?php echo esc_url( of_get_option( 'g_facebook_url' ) ); ?>"><i class="icon-facebook"></i></a>
		<?php
			}
		?>
		<?php 
			if ( '' != of_get_option( 'g_linkedin_url' ) ) {
		?>
			<a href="<?php echo esc_url( of_get_option( 'g_linkedin_url' ) ); ?>"><i class="icon-linkedin"></i></a>
		<?php
			}
		?>
		<?php 
			if ( '' != of_get_option( 'g_google_url' ) ) {
		?>
			<a href="<?php echo esc_url( of_get_option( 'g_google_url' ) ); ?>"><i class="icon-google-plus"></i></a>
		<?php
			}
		?>
		<?php 
			if ( '' != of_get_option( 'g_pinterest_url' ) ) {
		?>
			<a href="<?php echo esc_url( of_get_option( 'g_pinterest_url' ) ); ?>"><i class="icon-pinterest"></i></a>
		<?php
			}
		?>
		<?php 
			if ( '' != of_get_option( 'g_rss_url' ) ) {
		?>
			<a href="<?php echo esc_url( of_get_option( 'g_rss_url' ) ); ?>"><i class="icon-rss"></i></a>
		<?php
			}
		?>
	</div>
</aside>
<?php
}


/*-----------------------------------------------------------------------------------*/
/*	Pagination (based on Twenty Fourteen pagination function)
/*-----------------------------------------------------------------------------------*/
function bueno_pagination() { 

	global $wp_query, $wp_rewrite;
    
	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

	// Set up paginated links.
	$links = paginate_links( array(
		'base'      => $pagenum_link,
		'format'    => $format,
		'total'     => $wp_query->max_num_pages,
		'current'   => $paged,
		'mid_size'  => 1,
		'add_args'  => array_map( 'urlencode', $query_args ),
		'prev_text' => __( '&larr; Prev', 'bueno' ),
		'next_text' => __( 'Next &rarr;', 'bueno' ),
		'type'      => 'list'
	) );

	if ( $links ) {

	?>
	<div class="page_nav_wrap">
		<div class="post_nav">
			<?php echo $links; ?>
		</div><!-- .pagination -->
	</div><!-- .navigation -->
	<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Custom Comments Structure
/*-----------------------------------------------------------------------------------*/
function bueno_comment($comment, $args, $depth) {
     $GLOBALS['comment'] = $comment;

?> 
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>" class="clearfix">
     	<div id="comment-<?php comment_ID(); ?>" class="comment-body clearfix">
      		<div class="clearfix">
      			<div class="comment-author vcard">
  	         		<?php echo get_avatar( $comment->comment_author_email, 65 ); ?>
  	  				<?php printf(__('<span class="author fn">%1$s</span>' ), get_comment_author_link()) ?>
  	      		</div>
  		      	<?php if ($comment->comment_approved == '0') : ?>
  		        	<em><?php _e('Your comment is awaiting moderation.', 'cherry') ?></em>
  		      	<?php endif; ?>	      	
  		     	<div class="extra-wrap">
  		     		<?php comment_text() ?>	     	
  		     	</div>
  		    </div>
	     	<div class="clearfix comment-footer">
			  	<div class="reply">
			    	<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			   	</div>
		   		<div class="comment-meta commentmetadata"><?php printf(__('%1$s', 'cherry' ), get_comment_date('F j, Y')) ?></div>
		 	</div>
    	</div>
<?php }

if (!function_exists('bueno_img_html_to_post_id')) {
	function bueno_img_html_to_post_id( $html, &$matched_html = null ) {

	        $attachment_id = 0;

	        // Look for an <img /> tag
	        if ( ! preg_match( '#' . get_tag_regex( 'img' ) .  '#i', $html, $matches ) || empty( $matches ) )
	                return $attachment_id;

	        $matched_html = $matches[0];

	        // Look for attributes.
	        if ( ! preg_match_all( '#(src|class)=([\'"])(.+?)\2#is', $matched_html, $matches ) || empty( $matches ) )
	                return $attachment_id;

	        $attr = array();
	        foreach ( $matches[1] as $key => $attribute_name )
	                $attr[ $attribute_name ] = $matches[3][ $key ];

	        if ( ! empty( $attr['class'] ) && false !== strpos( $attr['class'], 'wp-image-' ) )
	                if ( preg_match( '#wp-image-([0-9]+)#i', $attr['class'], $matches ) )
	                        $attachment_id = absint( $matches[1] );

	        if ( ! $attachment_id && ! empty( $attr['src'] ) )
	                $attachment_id = attachment_url_to_postid( $attr['src'] );

	        return $attachment_id;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Footer scripts init
/*-----------------------------------------------------------------------------------*/

if (!function_exists('bueno_footer_js')) {
	function bueno_footer_js() {

			$sf_delay = esc_attr( of_get_option('sf_delay') );
			$sf_f_animation = esc_attr( of_get_option('sf_f_animation') );
			$sf_sl_animation = esc_attr( of_get_option('sf_sl_animation') );
			$sf_speed = esc_attr( of_get_option('sf_speed') );
			$sf_arrows = esc_attr( of_get_option('sf_arrows') );
			if ('' == $sf_delay) {$sf_delay = 1000;}
			if ('' == $sf_f_animation) {$sf_f_animation = 'show';}
			if ('' == $sf_sl_animation) {$sf_sl_animation = 'show';}
			if ('' == $sf_speed) {$sf_speed = 'normal';}
			if ('' == $sf_arrows) {$sf_arrows = 'false';}

		?>
		<script type="text/javascript">
			// initialise plugins
			jQuery(function(){
				// main navigation init
				jQuery('.navbar_inner > ul').superfish({
					delay:       <?php echo $sf_delay; ?>, 		// one second delay on mouseout 
					animation:   {opacity:"<?php echo $sf_f_animation; ?>", height:"<?php echo $sf_sl_animation; ?>"}, // fade-in and slide-down animation
					speed:       '<?php echo $sf_speed; ?>',  // faster animation speed 
					autoArrows:  <?php echo $sf_arrows; ?>,   // generation of arrow mark-up (for submenu)
					dropShadows: false
				});
				jQuery('.navbar_inner > div > ul').superfish({
					delay:       <?php echo $sf_delay; ?>, 		// one second delay on mouseout 
					animation:   {opacity:"<?php echo $sf_f_animation; ?>", height:"<?php echo $sf_sl_animation; ?>"}, // fade-in and slide-down animation
					speed:       '<?php echo $sf_speed; ?>',  // faster animation speed 
					autoArrows:  <?php echo $sf_arrows; ?>,   // generation of arrow mark-up (for submenu)
					dropShadows: false
				});
			});
			jQuery(function(){
			  var ismobile = navigator.userAgent.match(/(iPad)|(iPhone)|(iPod)|(android)|(webOS)/i)
			  if(ismobile){
			  	jQuery('.navbar_inner > ul').sftouchscreen();
			  	jQuery('.navbar_inner > div > ul').sftouchscreen();
			  }
			});
		</script>
		<!--[if (gt IE 9)|!(IE)]><!-->
		<script type="text/javascript">
			jQuery(function(){
				jQuery('.navbar_inner > ul').mobileMenu();
			  	jQuery('.navbar_inner > div > ul').mobileMenu();
			})
		</script>
		<!--<![endif]-->
		<?php
	}

	add_action( 'wp_footer', 'bueno_footer_js', 20, 1 );
}
/*-----------------------------------------------------------------------------------*/
/* No-thumb in post class
/*-----------------------------------------------------------------------------------*/
add_filter('post_class','bueno_custom_post_class');

function bueno_custom_post_class($classes) {
	global $post;
	if ( !has_post_thumbnail($post->ID) || ( 'noimg' == of_get_option('post_image_size') ) ) {
		if ( 'image' == get_post_format() ) {
			if ( false == bueno_post_format_image() ) {
				$classes[] = 'no-thumb';
			}
		} elseif ( 'gallery' == get_post_format() ) {
			$attachments = get_children( array('post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'image') );
			if ( !$attachments ) {
				$classes[] = 'no-thumb';
			}
		} else {
			$classes[] = 'no-thumb';
		}
	}
	if ( '' == get_the_title() ) {
		$classes[] = 'no-title';
	}
	return $classes;
}
/*-----------------------------------------------------------------------------------*/
/* Functions for dispalying post meta
/*-----------------------------------------------------------------------------------*/
function bueno_show_post_author() {
	if ( 'false' != of_get_option('post_meta_author') ) {
?>
	<span class="post_author"><i class="icon-user"></i><?php the_author_posts_link() ?></span>
<?php
	}
}
function bueno_show_post_date() {
	if ( 'false' != of_get_option('post_meta_date') ) {
?>
	<span class="post_date"><i class="icon-calendar"></i><time datetime="<?php the_time('Y-m-d\TH:i:s'); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time></span>
<?php
	}
}
function bueno_show_post_comments() {
	if ( 'true' == of_get_option('post_meta_comments') ) {
?>
	<span class="post_comment"><?php comments_popup_link(__('<i class="icon-comments"></i><b>No comments</b>', 'bueno'), __('<i class="icon-comments"></i><b>1 comment</b>', 'bueno'), __('<i class="icon-comments"></i><b>% comments</b>', 'bueno'), 'comments-link', __('<i class="icon-comments"></i><b>Comments are closed</b>', 'bueno')); ?></span>
<?php
	}
}
function bueno_show_post_categories() {
	if ( 'true' == of_get_option('post_meta_categories') ) {
		if ( get_the_category() ) {
			?><span class="post_category"><i class="icon-folder-open"></i><?php the_category(', ') ?></span><?php 
		}
	}
}