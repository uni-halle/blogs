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

add_action('wp_enqueue_scripts',function(){
    wp_register_style('droidsans','//fonts.googleapis.com/css?family=Droid+Sans',false,$theme['Version']);
    wp_register_style('ptserif','//fonts.googleapis.com/css?family=PT+Serif&subset=latin,cyrillic',false,$theme['Version']);
    wp_register_style('inconsolata','//fonts.googleapis.com/css?family=Inconsolata',false,$theme['Version']);
    wp_register_style('ubuntu','//fonts.googleapis.com/css?family=Ubuntu&subset=latin,cyrillic-ext,greek,greek-ext,latin-ext,cyrillic',false,$theme['Version']);
    wp_register_style('lato','//fonts.googleapis.com/css?family=Lato',false,$theme['Version'] );
});

/*** entferne mlublogs-footer ***/
if(basename($_SERVER[@REQUEST_URI])!='impressum')
foreach(['html','css'] as $comp) remove_filter('wp_footer',"mlublogs_footer_$comp");


