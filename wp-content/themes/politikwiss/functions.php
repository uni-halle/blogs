<?php 
// Registrierung der Navigation
if (function_exists('register_nav_menus')){
	register_nav_menus( array ('primary' => 'Header Navigation' ));}



// Registrierung der Hintergrundbilder für Posts
if (function_exists('add_theme_support')){
	add_theme_support('post-thumbnails');
	add_theme_support('page_template');
}
if (function_exists('add_image_size')){
	add_image_size( 'slides', 400, 99999, false);
	add_image_size( 'absolventen_thumb', 600, 99999, false);
  add_image_size( 'lehrende_thumb', 900, 99999, false);

}

function the_category_unlinked($separator = ' ') {
    $categories = (array) get_the_category();
    
    $thelist = '';
    foreach($categories as $category) {    // concate
        $thelist .= $separator . $category->category_nicename;
    }
    
    echo $thelist;
}


/**********************************/
/****Konfiguration des Excerpts****/
/**********************************/
function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


/**********************************/
/****Registrierung von "Slides"****/
/**********************************/

add_action('init', 'cptui_register_my_cpt_slides');
function cptui_register_my_cpt_slides() {
register_post_type('slides', array(
'label' => 'Slides',
'description' => 'Das ist der Slider',
'public' => true,
'show_ui' => true,
'show_in_menu' => true,
'capability_type' => 'post',
'map_meta_cap' => true,
'hierarchical' => false,
'rewrite' => array('slug' => 'slides', 'with_front' => true),
'query_var' => true,
'menu_position' => 5,
'supports' => array('title','thumbnail','page-attributes'),
'labels' => array (
  'name' => 'Slides',
  'singular_name' => 'Slide',
  'menu_name' => 'Slides',
  'add_new' => 'Add Slide',
  'add_new_item' => 'Add New Slide',
  'edit' => 'Edit',
  'edit_item' => 'Edit Slide',
  'new_item' => 'New Slide',
  'view' => 'View Slide',
  'view_item' => 'View Slide',
  'search_items' => 'Search Slides',
  'not_found' => 'No Slides Found',
  'not_found_in_trash' => 'No Slides Found in Trash',
  'parent' => 'Parent Slide',
)
) ); }


/**
 * Registrierung von "Aktuelles 120 Master"
 */
add_action('init', 'cptui_register_my_cpt_News120');
function cptui_register_my_cpt_News120() {
register_post_type('News-120', array(
'label' => 'News-120',
'description' => 'Hier Neuigkeiten Posten',
'public' => true,
'show_ui' => true,
'show_in_menu' => true,
'capability_type' => 'post',
'map_meta_cap' => true,
 'has_archive'        => true,
'hierarchical' => true,
'rewrite' => array('slug' => 'News-120', 'with_front' => true),
'query_var' => true,
'menu_position' => 5,
'supports' => array('title', 'editor' ),
'labels' => array (
  'name' => 'News-120',
  'singular_name' => 'News-120',
  'menu_name' => 'News-120',
  'add_new' => 'News-120 hinzufügen',
  'add_new_item' => 'Neues News-120 hinzufügen',
  'edit' => 'Bearbeiten',
  'edit_item' => 'News-120 bearbeiten',
  'new_item' => 'Neues News-120',
  'view' => 'News-120 anzeigen',
  'view_item' => 'News-120 anzeigen',
  'search_items' => 'News-120 durchsuchen',
  'not_found' => 'Keine News-120 gefunden',
  'not_found_in_trash' => 'Keine News-120 im Papierkorb gefunden',
  'parent' => 'Übergeordnetes Event',
)
) ); }
/**
 * Registrierung von "Aktuelles 45 / 75 Master"
 */
add_action('init', 'cptui_register_my_cpt_News45_75');
function cptui_register_my_cpt_News45_75() {
register_post_type('News-45_75', array(
'label' => 'News-45_75',
'description' => 'Hier Neuigkeiten Posten',
'public' => true,
'show_ui' => true,
'show_in_menu' => true,
'capability_type' => 'post',
'map_meta_cap' => true,
'hierarchical' => true,
'rewrite' => array('slug' => 'News-45_75', 'with_front' => true),
'query_var' => true,
'menu_position' => 5,
'supports' => array('title', 'editor' ),
'labels' => array (
  'name' => 'News-45_75',
  'singular_name' => 'News-45_75',
  'menu_name' => 'News-45_75',
  'add_new' => 'News-45_75 hinzufügen',
  'add_new_item' => 'Neues News-45_75 hinzufügen',
  'edit' => 'Bearbeiten',
  'edit_item' => 'News-45_75 bearbeiten',
  'new_item' => 'Neues News-45_75',
  'view' => 'News-45_75 anzeigen',
  'view_item' => 'News-45_75 anzeigen',
  'search_items' => 'News-45_75 durchsuchen',
  'not_found' => 'Keine News-45_75 gefunden',
  'not_found_in_trash' => 'Keine News-45_75 im Papierkorb gefunden',
  'parent' => 'Übergeordnetes Event',
)
) ); }



/**
 * Registrierung von "Absolventen-120"
 */
add_action('init', 'cptui_register_my_cpt_Absolventen');
function cptui_register_my_cpt_Absolventen() {
register_post_type('Absolventen', array(
'label' => 'Absolventen',
'description' => 'Absolventen',
'public' => true,
'show_ui' => true,
'show_in_menu' => true,
'capability_type' => 'post',
'map_meta_cap' => true,
'hierarchical' => false,
'rewrite' => array('slug' => 'Absolventen', 'with_front' => true),
'query_var' => true,
'menu_position' => 5,
'supports' => array('title', 'editor','thumbnail','page-attributes', 'custom-fields' ),
 'taxonomies' => array('category'),
'labels' => array (
  'name' => 'Events',
  'singular_name' => 'Absolventen',
  'menu_name' => 'Absolventen',
  'add_new' => 'Absolventen hinzufügen',
  'add_new_item' => 'Neue Absolventen hinzufügen',
  'edit' => 'Bearbeiten',
  'edit_item' => 'Absolventen bearbeiten',
  'new_item' => 'Neue Absolventen',
  'view' => 'Absolventen anzeigen',
  'view_item' => 'Absolventen anzeigen',
  'search_items' => 'Absolventen durchsuchen',
  'not_found' => 'Keine Absolventen gefunden',
  'not_found_in_trash' => 'Keine Absolventen im Papierkorb gefunden',
  'parent' => 'Übergeordnete Absolventen',
)
) ); }

/**
 * Registrierung von "Absolventen-45"
 */
add_action('init', 'cptui_register_my_cpt_Absolventen45');
function cptui_register_my_cpt_Absolventen45() {
register_post_type('Absolventen 45-75', array(
'label' => 'Absolventen 45-75',
'description' => 'Absolventen 45-75',
'public' => true,
'show_ui' => true,
'show_in_menu' => true,
'capability_type' => 'post',
'map_meta_cap' => true,
'hierarchical' => false,
'rewrite' => array('slug' => 'Absolventen 45-75', 'with_front' => true),
'query_var' => true,
'menu_position' => 5,
'supports' => array('title', 'editor','thumbnail','page-attributes', 'custom-fields' ),
 'taxonomies' => array('category'),
'labels' => array (
  'name' => 'Absolventen 45-75',
  'singular_name' => 'Absolventen 45-75',
  'menu_name' => 'Absolventen 45-75',
  'add_new' => 'Absolventen 45-75 hinzufügen',
  'add_new_item' => 'Neue Absolventen hinzufügen',
  'edit' => 'Bearbeiten',
  'edit_item' => 'Absolventen bearbeiten',
  'new_item' => 'Neue Absolventen',
  'view' => 'Absolventen anzeigen',
  'view_item' => 'Absolventen anzeigen',
  'search_items' => 'Absolventen durchsuchen',
  'not_found' => 'Keine Absolventen gefunden',
  'not_found_in_trash' => 'Keine Absolventen im Papierkorb gefunden',
  'parent' => 'Übergeordnete Absolventen',
)
) ); }


/**
 * Registrierung von "Lehrende"
 */
add_action('init', 'cptui_register_my_cpt_Lehrende');
function cptui_register_my_cpt_Lehrende() {
register_post_type('Lehrende', array(
'label' => 'Lehrende',
'description' => 'Lehrende',
'public' => true,
'show_ui' => true,
'show_in_menu' => true,
'capability_type' => 'post',
'map_meta_cap' => true,
'hierarchical' => false,
'rewrite' => array('slug' => 'Lehrende', 'with_front' => true),
'query_var' => true,
'menu_position' => 5,
'supports' => array('title', 'editor','thumbnail','page-attributes', 'custom-fields' ),
 'taxonomies' => array('category'),
'labels' => array (
  'name' => 'Events',
  'singular_name' => 'Lehrende',
  'menu_name' => 'Lehrende',
  'add_new' => 'Lehrende hinzufügen',
  'add_new_item' => 'Neue Lehrende hinzufügen',
  'edit' => 'Bearbeiten',
  'edit_item' => 'Lehrende bearbeiten',
  'new_item' => 'Neue Lehrende',
  'view' => 'Lehrende anzeigen',
  'view_item' => 'Lehrende anzeigen',
  'search_items' => 'Lehrende durchsuchen',
  'not_found' => 'Keine Lehrende gefunden',
  'not_found_in_trash' => 'Keine Lehrende im Papierkorb gefunden',
  'parent' => 'Übergeordnete Lehrende',
)
) ); }






?>