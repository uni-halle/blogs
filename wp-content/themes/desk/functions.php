<?php
if (!isset($dk_settings)){
$dk_settings = get_option( 'sa_options' ); //gets the current value of all the settings as stored in the db
}



//New options page
if (is_admin()){

$functions_path = TEMPLATEPATH . '/options/';
//Theme Options
require_once ($functions_path . 'options.php');
}

//END New options page



//registers the widgetised sidebar and footer
if ( function_exists('register_sidebar') )
    register_sidebar(array('name' => 'Sidebar'));
    
    $args = array('name' => 'Left Footer','before_title' => '<h4 class="widgettitle">','after_title' => "</h4>");
	register_sidebar($args);
	$args = array('name' => 'Center Footer','before_title' => '<h4 class="widgettitle">','after_title' => "</h4>");
	register_sidebar($args);
    $args = array('name' => 'Right Footer','before_title' => '<h4 class="widgettitle">','after_title' => "</h4>");
	register_sidebar($args);


//register the custom header menu
function register_my_menus() {
register_nav_menus(
array(
'header-menu' => __( 'Header Menu' )
)
);
}
add_action( 'init', 'register_my_menus' );

//theme support for thumbnails and feeds
add_theme_support( 'post-thumbnails' );
add_theme_support('automatic-feed-links');

//changes the excerpt more link to a raquo
	function new_excerpt_more($more) {
	return '...<a href="'. get_permalink($post->ID) . '">&raquo;</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

//enqueus jquery if needed
wp_enqueue_script('jquery');

//Loads an old comments.php file if wordpress does not support the new comment methods
add_filter( 'comments_template', 'legacy_comments' );
function legacy_comments( $file ) {
	if ( !function_exists('wp_list_comments') )
		$file = TEMPLATEPATH . '/old.comments.php';
	return $file;
}
//sets the content width global variable
$GLOBALS['content_width'] = 525;
if ( ! isset( $content_width ) ) {$content_width = 525;}

//allows for a custom background
	add_custom_background();
?>