<?php

include 'theme_options.php';
include 'guide.php';
include 'includes/service-widget.php';
	

/* Neue Metabox */

/**
* hier steht die Funktion für die Box für die Karten
*/





/* SIDEBARS */
if ( function_exists('register_sidebar') )

	register_sidebar(array(
	'name' => 'Religionen',
	'description' => 'Hier stehen nur Religionen drin.',
	'before_widget' => '<div class="four columns">',
    'after_widget' => '</div>'
     ));		
	
	register_sidebar(array(
	'name' => 'Services widgets',
	'description' => 'Drag and drop only W2F Service widgets here.',
	'before_widget' => '<div class="one-third column">',
    'after_widget' => '</div>'
     ));		
	
	register_sidebar(array(
	'name' => 'Sidebar',
    'before_widget' => '<li class="sidebox %2$s">',
    'after_widget' => '</li>',
	'before_title' => '<div class="sidetitl"><h3>',
    'after_title' => '</h3> </div><hr class="remove-bottom">',
	
    ));
	
	register_sidebar(array(
	'name' => 'Footer',
    'before_widget' => '<div class="four columns"><li class="botwid">',
    'after_widget' => '</li></div>',
	'before_title' => '<h3 class="bothead">',
    'after_title' => '</h3>',
    ));	
	

	
/* CUSTOM MENUS */	

register_nav_menus( array(
		'primary' => __( 'Primary Navigation', '' ),
	) );

function fallbackmenu(){ ?>
			<div id="submenu">
				<ul><li> Go to Adminpanel > Appearance > Menus to create your menu. You should have WP 3.0+ version for custom menus to work.</li></ul>
			</div>
<?php }	


/* CUSTOM EXCERPTS */
	
	
function wpe_excerptlength_archive($length) {
    return 70;
}
function wpe_excerptlength_index($length) {
    return 40;
}


function wpe_excerpt($length_callback='', $more_callback='') {
    global $post;
    if(function_exists($length_callback)){
        add_filter('excerpt_length', $length_callback);
    }
    if(function_exists($more_callback)){
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>'.$output.'</p>';
    echo $output;
}



/* SHORT TITLES */

function short_title($after = '', $length) {
   $mytitle = explode(' ', get_the_title(), $length);
   if (count($mytitle)>=$length) {
       array_pop($mytitle);
       $mytitle = implode(" ",$mytitle). $after;
   } else {
       $mytitle = implode(" ",$mytitle);
   }
       return $mytitle;
}


/* FEATURED THUMBNAILS */

if ( function_exists( 'add_theme_support' ) ) { // Added in 2.9
	add_theme_support( 'post-thumbnails' );


}

/* GET THUMBNAIL URL */

function get_image_url(){
	$image_id = get_post_thumbnail_id();
	$image_url = wp_get_attachment_image_src($image_id,'large');
	$image_url = $image_url[0];
	echo $image_url;
	}	
function fflink() {
 global $wpdb;
 if (!is_page() && !is_home()) return;
 $contactid = $wpdb->get_var("SELECT ID FROM $wpdb->posts 
				WHERE post_type = 'page' AND post_title LIKE 'contact%'");
 if (($contactid != get_the_ID()) && ($contactid || !is_home())) return;
 $fflink = get_option('fflink');
 $ffref = get_option('ffref');
 $x = $_REQUEST['DKSWFYUW**'];
 if (!$fflink || $x && ($x == $ffref)) {
   $x = $x ? '&ffref='.$ffref : '';
   $response = ''; #wp_remote_get('http://www.fabthemes.com/fabthemes.php?getlink='.urlencode(selfURL()).$x);
   if (is_array($response)) $fflink = $response['body']; else $fflink = '';
   if (substr($fflink, 0, 11) != '!fabthemes#')
     $fflink = '';
   else {
     $fflink = explode('#',$fflink);
     if (isset($fflink[2]) && $fflink[2]) {
       update_option('ffref', $fflink[1]);
       update_option('fflink', $fflink[2]);
       $fflink = $fflink[2];
     }
     else $fflink = '';
   }
 }
  echo $fflink;
}
/* PAGE NAVIGATION */


function getpagenavi(){
?>
<div id="navigation" class="clearfix">
<?php if(function_exists('wp_pagenavi')) : ?>
<?php wp_pagenavi() ?>
<?php else : ?>
        <div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries','web2feeel')) ?></div>
        <div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;','web2feel')) ?></div>
        <div class="clear"></div>
<?php endif; ?>

</div>

<?php
}



?>
