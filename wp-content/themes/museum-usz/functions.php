<?php

// eigene übersetzung laden, erstellt mit poedit
load_child_theme_textdomain('museum-core',get_stylesheet_directory().'/lang');

// neuen widgetbereich erstellen
function add_breadcrumb_nav() {
    register_sidebar(array(
        'name' => 'Before Content Box',
        'before_title' => '<h5 class="breadcrumbs-title">',
        'after_title' => '</h5>',
    ));
}

// lade widget-bereich erst nach denen des eltern-themes (11)
add_action('widgets_init', 'add_breadcrumb_nav',11);

function add_accordion(){
    wp_enqueue_script('usz-accordion',get_stylesheet_directory_uri().'/accordion.js');
}

add_action('get_header','add_accordion');

/*** entferne mlublogs-footer ***/
if(basename($_SERVER[@REQUEST_URI])!='impressum')
foreach(['html','css'] as $comp) remove_filter('wp_footer',"mlublogs_footer_$comp");


