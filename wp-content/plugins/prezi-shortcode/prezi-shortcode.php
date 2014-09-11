<?php
/*
Plugin Name: Prezi Shortcode
Plugin URI: http://wordpress.rebelic.nl/prezi-shortcode/
Description: Adds an easy way to embed Prezi presentations in your blog posts and pages.
Author: Timan Rebel
Author URI: http://wordpress.rebelic.nl/
Version: 1.0.1
*/

	function rebelic_prezi_shortcode($atts, $content = null) {
		$width = get_option('rebelic_prezi_width');
		$height = get_option('rebelic_prezi_height');
		
		if(empty($width))
			$width = 425;
			
		if(empty($height))
			$height = 320;
		
		extract(shortcode_atts(array(  
		    "width" 		=> $width,  
			"height" 		=> $height
		), $atts));
		
		return '<iframe src="'. $content .'" width="'. attribute_escape($width). '" height="'. attribute_escape($height) .'"></iframe>';
	}
	add_shortcode('prezi', 'rebelic_prezi_shortcode');
	
	function rebelic_prezi_addbuttons() {
		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true') {
		// add the button for wp25 in a new way
			add_filter("mce_external_plugins", "rebelic_prezi_add_tinymce_plugin", 5);
			add_filter('mce_buttons_2', 'rebelic_prezi_register_button', 5);
		}
	}
	
	// used to insert button in wordpress 2.5x editor
	function rebelic_prezi_register_button($buttons) {
		array_push($buttons, "separator", "rebelicPreziButton");
		return $buttons;
	}
	
	// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
	function rebelic_prezi_add_tinymce_plugin($plugin_array) {
		$dir =  WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		
		$plugin_array['rebelicPrezi'] = $dir . '/tinymce3/editor_plugin.js';	
		return $plugin_array;
	}
	
	function rebelic_prezi_change_tinymce_version($version) {
		return ++$version;
	}
	
	/**
	 * Build settings page for Twitter Publisher
	 */
	function rebelic_prezi_configpage() {
    //check if request is a POST
    if(!empty($_POST)) {
        //update options
        update_option('rebelic_prezi_width', $_POST['rebelic_prezi_width']);
        update_option('rebelic_prezi_height', $_POST['rebelic_prezi_height']);
    }

    echo '<div class="wrap">
            '. screen_icon() .'
            <h2>'.__('Prezi Shortcode Settings', 'rebelic_prezi').'</h2>';

    if(!empty($_POST)) {
        echo '<div id="message" class="updated fade"><p><strong>'.__('Settings saved', 'rebelic_prezi').'</strong></p></div>';
    }

    echo   '<form method="POST">
            <table class="form-table">
            <tr>
                <th><label for="rebelic_prezi_width">'.__('Default width of Prezi:', 'rebelic_prezi').'</label></th>
                <td>
                    <input type="text" name="rebelic_prezi_width" id="rebelic_prezi_width" value="' . get_option('rebelic_prezi_width') .'" />
                </td>
            </tr>
            <tr>
                <th><label for="rebelic_prezi_height">'.__('Default height of Prezi:', 'rebelic_prezi').'</label></th>
                <td>
                    <input type="text" name="rebelic_prezi_height" id="rebelic_prezi_height" value="' . get_option('rebelic_prezi_height') .'" />
                </td>
            </tr>
            </table>
            <p class="submit">
            <input type="submit" name="Submit" class="button-primary" value="'.__('Save Changes', 'rebelic_prezi').'" />
            </p>
            </form>';
	}
	function rebelic_prezi_configpagelink() {
		add_options_page('Prezi Shortcode', 'Prezi Shortcode', 6, basename(__FILE__), 'rebelic_prezi_configpage');
	}
	add_action('admin_menu', 'rebelic_prezi_configpagelink');
	
	/**
	 * Adds a settings link to the plugin description row
	 */
	function rebelic_prezi_filter_plugin_actions($links, $file) {
	    //Static so we don't call plugin_basename on every plugin row.
	    static $this_plugin;
	    
	    if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
	
	        if ( $file == $this_plugin ){
	                $settings_link = '<a href="options-general.php?page=prezi-shortcode.php">' . __('Settings', 'rebelic_prezi') . '</a>';
	                array_unshift( $links, $settings_link ); // before other links
	        }
	        return $links;
	}
	add_filter( 'plugin_action_links', 'rebelic_prezi_filter_plugin_actions', 10, 2 );

	// Modify the version when tinyMCE plugins are changed.
	add_filter('tiny_mce_version', 'rebelic_prezi_change_tinymce_version');
	// init process for button control
	add_action('init', 'rebelic_prezi_addbuttons');
?>