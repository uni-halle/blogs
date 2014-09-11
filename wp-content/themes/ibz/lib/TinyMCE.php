<?php
/**
 * TinyMCE EDITOR BUTTONS
 */

add_action('init', 'buttons');

function buttons() {
        if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages')){
        	return;
        }
        if ( get_user_option('rich_editing') == 'true' ) {
		global $typenow;
            if (empty($typenow) && !empty($_GET['post']) ) {
                $post = get_post($_GET['post']);
                $typenow = $post->post_type;
            }
			add_filter( 'mce_external_plugins', 'add_plugin' );
			add_filter( 'mce_buttons', 'register_button' );
       }
    }

function register_button($buttons) {  
   array_push($buttons, "|", "maja_one_fourth", "maja_one_third", "maja_one_half", "maja_two_thirds", "maja_three_fourths",  "maja_one", "|", "maja_clear", "maja_separator", "|", "maja_lists_bullet", "maja_lists_check", "maja_lists_arrow", "|", "maja_button");  
   return $buttons;  
} 

function add_plugin($plugin_array) {  
   $plugin_array['quote'] = get_template_directory_uri() . '/lib/js/tinymce.js';  
   return $plugin_array;  
} 

?>