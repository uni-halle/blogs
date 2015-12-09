<?php

/**
 * Quintlab theme functions.php.
 */


define('QL_ROOT', dirname(__FILE__) . '/');
define('QL_BASE', get_bloginfo('url') . '/wp-content/themes/quintlabtheme/');


//require_once('includes/types.php');
require_once('includes/fields.php');


//load localization
add_action('init', 'ql_init_i18n');
function ql_init_i18n(){
    load_theme_textdomain('quintlab', QL_ROOT . '/languages');
}

//register required resources
add_action('wp_enqueue_scripts', 'ql_init_resources');
function ql_init_resources() {
    wp_enqueue_script('slick-carousel', QL_BASE . 'bower_components/slick-carousel/slick/slick.min.js', array('jquery'));
    wp_enqueue_script('ql_script', QL_BASE . 'js/quintlab.js', array('jquery', 'slick-carousel'));
    wp_localize_script('ql_script', 'ql', array(
        'BASE' => get_bloginfo('url'),
        'AJAX_URL' => admin_url('admin-ajax.php'),
    ));
    wp_enqueue_style('ql_style', QL_BASE . 'css/quintlab.css');
}


//register theme navigation slots
add_action('init', 'ql_register_navigation');
function ql_register_navigation() {
    register_nav_menus(array(
        'main-menu' => 'Hauptnavigation',
        //'foot-menu' => 'Fußnavigation',
    ));
}


//register support for excerpt
add_action('init', 'ql_register_excerpt');
function ql_register_excerpt() {
    add_post_type_support('page', 'excerpt');
}


//register post type
add_action('init', 'ql_register_posttypes');
function ql_register_posttypes() {
    register_post_type("publication", array(
        'labels' => array(
            'name' => 'Publikationen',
            'singular_name' => 'Publikation',
            'add_new' => 'Publikation hinzufügen',
            'add_new_item' => 'Publikation hinzufügen',
            'edit_item' => 'Publikation bearbeiten',
            'new_item' => 'Neue Publikation',
            'view_item' => 'Publikation ansehen',
            'search_items' => 'Publikation suchen',
        ),
        'public' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'show_in_nav_menus' => true,
        'hierarchical' => false,
        'rewrite' => array('slug' => 'all_publications'),
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-format-aside',
    ));
}

//form handling

add_filter('wpsf_formoptions', 'ql_adjust_form');
function ql_adjust_form($options) {
    $options["formclass"] = "userform";
    $options["errorclass"] = "invalid";
    $options["messageclass"] = "message";
    $options["successmessage"] = __("The contact form has been submitted.", "quintlab");
    foreach ($options["formfields"] as &$field) {
        if ($field["name"] === "wpsf_name") {
            $field["label"] = __("Name", "quintlab");
            $field["errormessage"] = __("Please enter your name.", "quintlab");
        } else if ($field["name"] === "wpsf_email") {
            $field["label"] = __("Email address", "quintlab");
            $field["errormessage"] = __("Please enter your email address.", "quintlab");
        } else if ($field["name"] === "wpsf_subject") {
            $field["label"] = __("Subject", "quintlab");
        } else if ($field["name"] === "wpsf_message") {
            $field["label"] = __("Message", "quintlab");
            $field["errormessage"] = __("Please enter a message.", "quintlab");
        } else if ($field["type"] === "captcha") {
            $field["label"] = __("Spam protection:", "quintlab");
            $field["errormessage"] = __("Please enter the solution.", "quintlab");
        } else if ($field["type"] === "submit") {
            $field["label"] = __("Send", "quintlab");
        }
    }
    return $options;
}

add_action('wpsf_success', 'ql_form_success');
function ql_form_success() {
    $name = $_POST['wpsf_name'];
    $email = $_POST['wpsf_email'];
    $subject = $_POST['wpsf_subject'];
    $message = $_POST['wpsf_message'];
    wp_mail(get_option('admin_email'), "[quintlab] Contact form", "Subject: $subject\n\n$message");
}


//register global options page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page();
}


//enable featured image
add_theme_support('post-thumbnails');

//thumbnail size definitions
if (function_exists('add_image_size')) {
    add_image_size('slide', 1300, 900, true);
    add_image_size('slide-preview', 200, 135, true);
    add_image_size('teaser-portrait', 170, 220, true);
    add_image_size('teaser-landscape', 350, 180, true);
    add_image_size('thumbnail-unclipped', 150, 150, false);
}


//excerpt length suitable for teasers
add_filter('excerpt_length', 'ql_adjust_excerptlength', 999);
function ql_adjust_excerptlength($length) {
    return 25;
}


//adjust publication listing table
add_filter('manage_publication_posts_columns', 'ql_publication_columntitles');
add_action('manage_publication_posts_custom_column' , 'ql_publication_columnvalues', 10, 2);
function ql_publication_columntitles($columns) {
    $columns['year'] = "Jahr";
    return $columns;
}
function ql_publication_columnvalues($column, $post_id) {
    if ($column == 'year') {
        the_field("year", $post_id);
    }
}

/*
//add navigation item label as class
add_filter('nav_menu_css_class', 'ql_navigation_classes', 10, 2);
function ql_navigation_classes($classes, $item) {
    $classes[] = esc_attr(str_replace(' ', '', strtolower($item->title)));
    return $classes;
}
*/


//helpers


function ql_get_post_thumbnail($id=null, $size='medium') {
    $id = $id ? $id : get_the_ID();
    if (has_post_thumbnail($id)) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($id), $size);
        return "<img alt='' src='$image[0]'/>";
    } else {
        return "";
    }
}

function ql_get_acf_image($imagearray, $size='medium') {
    if ($imagearray) {
        $url = $imagearray['sizes'][$size];
        return "<img alt='' src='$url'/>";
    } else {
        return "";
    }
}

