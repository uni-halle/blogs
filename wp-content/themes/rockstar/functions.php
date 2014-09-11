<?php

//Start WooThemes Functions - Please refrain from editing this file.

$functions_path = TEMPLATEPATH . '/functions/';
$includes_path = TEMPLATEPATH . '/includes/';

// Options panel variables and functions
require_once ($functions_path . 'admin-setup.php');

// Custom functions and plugins
require_once ($functions_path . 'admin-functions.php');

// Custom fields 
require_once ($functions_path . 'admin-custom.php');

// More WooThemes Page
require_once ($functions_path . 'admin-theme-page.php');

// Admin Interface!
require_once ($functions_path . 'admin-interface.php');

// Options panel settings
require_once ($includes_path . 'theme-options.php'); // What we do!

//Custom Theme Fucntions
require_once ($includes_path . 'theme-functions.php'); 

//Custom Comments
require_once ($includes_path . 'theme-comments.php'); 

// Load Javascript in wp_head
require_once ($includes_path . 'theme-js.php');

// Widgets  & Sidebars
require_once ($includes_path . 'sidebar-init.php');

require_once ($includes_path . 'theme-widgets.php');


add_action('wp_head', 'woothemes_wp_head');
add_action('admin_menu', 'woothemes_add_admin');
add_action('admin_head', 'woothemes_admin_head');    

?>