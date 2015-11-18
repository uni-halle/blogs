<?php
/*
Author: Eddie Machado
URL: http://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, etc.
*/

// LOAD BONES CORE (if you remove this, the theme will break)
require_once( 'library/bones.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
// require_once( 'library/admin.php' );

/*********************
LAUNCH BONES
Let's get everything up and running.
*********************/

function bones_ahoy() {

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

  // USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
  require_once( 'library/custom-post-type.php' );

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
  // ie conditional wrapper
    
// Loads the Superfish JavaScript with jQuery as a dependency
wp_enqueue_script( 'hoverintent', get_template_directory_uri() . '/library/js/superfish-js/hoverintent.js', array('jquery'), '20151008' );
wp_enqueue_script( 'supersubs', get_template_directory_uri() . '/library/js/superfish-js/supersubs.js', array('jquery'), '20151008' );
wp_enqueue_script( 'superfish', get_template_directory_uri() . '/library/js/superfish-js/superfish.js', array('jquery'), '20151008' );
wp_enqueue_script( 'superfish-config', get_template_directory_uri() . '/library/js/superfish-js/superfishconfig.js', array('jquery'), '20151008' );

  // launching this stuff after theme setup
  bones_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'bones_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'bones_excerpt_more' );

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'bones_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'bones-thumb-600' => __('600px by 150px'),
        'bones-thumb-300' => __('300px by 100px'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/

/* 
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722
  
  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162
  
  To do:
  - Create a js for the postmessage transport method
  - Create some sanitize functions to sanitize inputs
  - Create some boilerplate Sections, Controls and Settings
*/

function bones_theme_customizer($wp_customize) {
  // $wp_customize calls go here.
  //
  // Uncomment the below lines to remove the default customize sections 

  // $wp_customize->remove_section('title_tagline');
  // $wp_customize->remove_section('colors');
     $wp_customize->remove_section('background_image');
     $wp_customize->remove_section('static_front_page');
     $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');
  
  // Uncomment the following to change the default section titles
     $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
    
    /***** GKUH CUSTOMIZATIONS ******/
    
    
}

add_action( 'customize_register', 'bones_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
        'id' => 'sidebar-gkuh',
		'name' => __( 'GKUH Sidebar', 'bonestheme' ),
		'description' => __( 'Dieser Bereich ist nur auf großen Bildschirmen sichtbar und enthält standardmäßig das Widget „GKUHplus Seitenliste“. Weitere Widgets wenn gewünscht einfach dazu holen. ', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:
*/
	register_sidebar(array(
        'id' => 'sidebar1',
		'name' => __( 'Extra Sidebar', 'bonestheme' ),
		'description' => __( 'Hier kann extra Inhalt hinein, wenn gewünscht', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
		
	));

} // don't remove this bracket!


/******** GKUHplus pagelist widget *******/
// Creating the widget 
class gkuh_pagelist_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'gkuh_pagelist_widget', 

// Widget name will appear in UI
__('GKUHplus Seitenliste', 'gkuh_pagelist_widget_domain'), 

// Widget description
array( 'description' => __( 'Hier wird die Liste der Seiten in der aktuellen Lektion angezeigt', 'gkuh_pagelist_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

    if ( in_category( array( 'inhalt', 'quiz' ) )) { //only display submenu on pages with category 'Lektionsinhalt' oder 'Quiz' 
        
 echo '<h2>In dieser Lektion:</h2>';
    
    $menu_name = 'main-nav';

    if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
	$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
        $args = array(
        'order'                  => 'DESC',
        'post_status'            => 'publish',
        'output'                 => ARRAY_A,
        'nopaging'               => true,
        'update_post_term_cache' => false);   
	$menu_items = wp_get_nav_menu_items($menu->term_id, $args);
	$menu_list = '<ol class="themenuebersicht">';  
    $thisparent = wp_get_post_parent_id($post);

	foreach ( (array) $menu_items as $key => $menu_item ) {
        $thatparent = $menu_item->post_parent;
        $title = $menu_item->title;
        $url = $menu_item->url;
        $thatid = $menu_item->object_id;
        
        if($thisparent === $thatparent && $thatid == get_the_ID()) {
            $menu_list .= '<li class="sidebar-menu-parent sidebar-current-item"><a href="' . $url . '">' . $title . '</a></li>';
        }
        elseif($thisparent === $thatparent) {
            $menu_list .= '<li class="sidebar-menu-parent"><a href="' . $url . '">' . $title . '</a></li>';
        }
	}
	$menu_list .= '</ul>';
    } else {
	$menu_list = '<ul><li>Menu "' . $menu_name . '" nicht definiert.</li></ul>';
    }
    echo $menu_list;
    
echo $args['after_widget'];
}
    
    }
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'gkuh_pagelist_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class gkuh_pagelist_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'gkuh_pagelist_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );




/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'bonestheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.
*/
function bones_fonts() {
  wp_enqueue_style('googleFonts', '//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic');
    wp_enqueue_style('googleFonts', '//fonts.googleapis.com/css?family=Bree+Serif');
}

add_action('wp_enqueue_scripts', 'bones_fonts');

/*************** GKUH FUNCTIONS *********************/ 

/********************************
DEBUG LOG FUNCTION @todo (remove)
********************************/
    /*function mebug() {
  $f=fopen( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mylog.log', 'a' );
  foreach (func_get_args() as $obj) {
    fwrite($f, date('Y-m-d G:i:s')." ");
    if (is_array($obj) || is_object($obj)) {
      fwrite($f, print_r($obj,1)."\n");
    }
    else {
      fwrite($f, $obj."\n");
    }
  }
  fclose($f);
}
*/
/*********************************
CREATE PAGE LIST BY MAIN NAV ORDER @todo
*********************************/
/*
This is crucial for many features including previous/next page, menu colors, submenus and progress. The usual order of wordpress pages/posts is difficult to set and we want to always use the order given for the main nav menu
*/




/**********************
CATEGORIES FOR PAGES
***********************/
      
/* This lets you add categories and tags to pages, which is neccessary for the color association for each lesson  */ 
      
function myplugin_settings() {  
// Add tag metabox to page
register_taxonomy_for_object_type('post_tag', 'page'); 
// Add category metabox to page
register_taxonomy_for_object_type('category', 'page');  
}
 // Add to the admin_init hook of your theme functions.php file 
add_action( 'init', 'myplugin_settings' );


/**************************
COLOR CATEGORY FUNCTIONS
***************************/

/*
This was actually a plugin but I choose to directly integrate it in this theme to avoid multisite plugin activation hassle. The plugin is called "Category Color" (1.2) by Zayed Baloch and Naeem Nur under GPL2+. More information: https://wordpress.org/plugins/category-color/
*/

class RadLabs_Category_Colors{
    protected $_meta;
    protected $_taxonomies;
    protected $_fields;

    function __construct( $meta ){
        if ( !is_admin() )
            return;
        $this->_meta = $meta;
        $this->normalize();

        add_action( 'admin_init', array( $this, 'add' ), 100 );
        add_action( 'edit_term', array( $this, 'save' ), 10, 2 );
        add_action( 'delete_term', array( $this, 'delete' ), 10, 2 );
        add_action( 'load-edit-tags.php', array( $this, 'load_edit_page' ) );
    }

    /*** Enqueue scripts and styles ***/
    function load_edit_page(){
        $screen = get_current_screen();
        if(
            'edit-tags' != $screen->base
            || empty( $_GET['action'] ) || 'edit' != $_GET['action']
            || !in_array( $screen->taxonomy, $this->_taxonomies )
        ){
            return;
        }

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_action( 'admin_head', array( $this, 'output_css' ) );
        add_action( 'admin_footer', array( $this, 'output_js' ), 100 );
    }

    /*** Enqueue scripts and styles ***/
    function admin_enqueue_scripts(){
        wp_enqueue_script( 'jquery' );
        $this->check_field_color();
    }

    // Output CSS into header
    function output_css(){
        echo $this->css ? '<style>' . $this->css . '</style>' : '';
    }

    // Output JS into footer
    function output_js(){
        echo $this->js ? '<script>jQuery(function($){' . $this->js . '});</script>' : '';
    }

    /*** COLOR FIELD ***/

    // Check field color
    function check_field_color(){
        if ( !$this->has_field( 'color' ) )
            return;
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        $this->js .= '$(".color").wpColorPicker();';
    }

    /*** META BOX PAGE ***/

    // Add meta fields for taxonomies
    function add(){
        foreach ( get_taxonomies() as $tax_name ) {
            if ( in_array( $tax_name, $this->_taxonomies ) ) {
                add_action( $tax_name . '_edit_form', array( $this, 'show' ), 9, 2 );
            }
        }
    }

    // Show meta fields
    function show( $tag, $taxonomy ){
        // get meta fields from option table
        $metas = get_option( $this->_meta['id'] );
        if ( empty( $metas ) ) $metas = array();
        if ( !is_array( $metas ) ) $metas = (array) $metas;

        // get meta fields for current term
        $metas = isset( $metas[$tag->term_id] ) ? $metas[$tag->term_id] : array();

        wp_nonce_field( basename( __FILE__ ), 'radlabs_taxonomy_meta_nonce' );

        echo "<h3>Kategoriefarbe wählen</h3>
            <table class='form-table'>";
        foreach ( $this->_fields as $field ) {
            echo '<tr>';

            $meta = !empty( $metas[$field['id']] ) ? $metas[$field['id']] : $field['std'];
            $meta = is_array( $meta ) ? array_map( 'esc_attr', $meta ) : esc_attr( $meta );
            call_user_func( array( $this, 'show_field_' . $field['type'] ), $field, $meta );

            echo '</tr>';
        }
        echo '</table>';
    }

    /*** META BOX FIELDS ***/

    function show_field_begin( $field, $meta ) {
        echo "<th scope='row' valign='top'><label for='{$field['id']}'>{$field['name']}</label></th><td>";
    }

    function show_field_end( $field, $meta ) {
        echo $field['desc'] ? "<br><span class='description'>{$field['desc']}</span></td>" : '</td>';
    }

    function show_field_color( $field, $meta ){
        if ( empty( $meta ) ) $meta = '#';
        $this->show_field_begin( $field, $meta );
        echo "<input type='text' name='{$field['id']}' id='{$field['id']}' value='$meta' class='color'>";
        $this->show_field_end( $field, $meta );
    }


    /*** META BOX SAVE ***/

    // Save meta fields
    function save( $term_id, $tt_id ) {
        $metas = get_option( $this->_meta['id'] );
        if ( !is_array( $metas ) )
            $metas = (array) $metas;
        $meta = isset( $metas[$term_id] ) ? $metas[$term_id] : array();
        foreach ( $this->_fields as $field ) {
            $name = $field['id'];
            $new = isset( $_POST[$name] ) ? $_POST[$name] : ( $field['multiple'] ? array() : '' );
            $new = is_array( $new ) ? array_map( 'stripslashes', $new ) : stripslashes( $new );
            if ( empty( $new ) ) {
                unset( $meta[$name] );
            } else {
                $meta[$name] = $new;
            }
        }
        $metas[$term_id] = $meta;
        update_option( $this->_meta['id'], $metas );
    }

    /*** META BOX DELETE ***/

    function delete( $term_id, $tt_id ){
        $metas = get_option( $this->_meta['id'] );
        if ( !is_array( $metas ) ) $metas = (array) $metas;
        unset( $metas[$term_id] );
        update_option( $this->_meta['id'], $metas );
    }

    /*** HELPER FUNCTIONS ***/

    function normalize(){
        // Default values for meta box
        $this->_meta = array_merge( array(
            'taxonomies' => array( 'category', 'post_tag' )
        ), $this->_meta );

        $this->_taxonomies = $this->_meta['taxonomies'];
        $this->_fields = $this->_meta['fields'];

    }

    // Check if field with $type exists
    function has_field( $type ) {
        foreach ( $this->_fields as $field ) {
            if ( $type == $field['type'] ) return true;
        }
        return false;
    }
}

//Load Texonomy metaboxes
require_once('fields.php');

function rl_color($catid){
    $meta = get_option('rl_category_meta');
    $meta = isset($meta[$catid]) ? $meta[$catid] : array();
    $yt_cat_color = $meta['rl_cat_color'];
    return $yt_cat_color;
}


/**********************
CATEGORIES TO NAV ITEMS
***********************/

function wpa_category_nav_class( $classes, $item ){
    if( $item->post_parent != 0 ){
        $categories = get_the_category($item->object_id);
        $topid = get_cat_ID( 'lektion' );
        foreach($categories as $category) {
            if (cat_is_ancestor_of($topid, $category)) {
                $catclass = $category->slug;
            }
        }
        $classes[] = 'menu-item-category-' . $catclass;
        $catlist[] = $catclass;
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'wpa_category_nav_class', 10, 2 );

/** adds category css class to menu page items **/

//function show_category_of_menu_item ( $classes, $item ) {    
//  if($item)
//    $category = get_category( $item );
//	$classes[] = 'menu-item-category-' . $category[0];
//	return $classes;

    
    //if( 'category' == $item->object ){
    //    $category = get_category( $item->object_id );
    //    $classes[] = 'menu-item-category-' . $category->slug;
    //}
    //return $classes;
//}
//add_filter( 'nav_menu_css_class', 'show_category_of_menu_item', 10, 2 );
                  

/* DON'T DELETE THIS CLOSING TAG */ ?>