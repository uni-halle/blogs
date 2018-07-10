<?php
if ( ! defined('ABSPATH') ) {
  die('Please do not load this file directly!');
}
//Add Custom Post Type
add_action( 'init', 'kt_cpt' );
function kt_cpt() {
  register_post_type( 'history_post',
    array(
      'labels' => array(
        'name' => __( 'History Timeline' ),
        'singular_name' => __( 'History-data' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'history'),
      'show_ui' => true,
	  'capability_type' => 'post',
	  'hierarchical' => false,
    'menu_icon' => plugin_dir_url(__FILE__).'/img/timeline.png', // 16px16
	  'supports' => array('title', 'editor')
    )
  );
}
include( plugin_dir_path( __FILE__ ) . 'include/history-table_list.php');