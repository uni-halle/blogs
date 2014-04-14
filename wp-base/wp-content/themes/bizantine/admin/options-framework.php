<?php
/*
Description: A framework for building theme options.
Author: Devin Price
Author URI: http://www.wptheming.com
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/* Basic plugin definitions */

define('OPTIONS_FRAMEWORK_VERSION', '1.0');

/* Make sure we don't expose any info if called directly */

if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a little extension, don't mind me.";
	exit;
}

/* If the user can't edit theme options, no use running this plugin */

add_action('init', 'optionsframework_rolescheck' );

function optionsframework_rolescheck () {
	if ( current_user_can( 'edit_theme_options' ) ) {
		// If the user can edit theme options, let the fun begin!
		add_action( 'admin_menu', 'optionsframework_add_page');
		add_action( 'admin_init', 'optionsframework_init' );
		add_action( 'admin_init', 'optionsframework_mlu_init' );
	}
}

/* Loads the file for option sanitization */

add_action('init', 'optionsframework_load_sanitization' );

function optionsframework_load_sanitization() {
	require_once dirname( __FILE__ ) . '/options-sanitize.php';
}

/* 
 * Creates the settings in the database by looping through the array
 * we supplied in options.php.  This is a neat way to do it since
 * we won't have to save settings for headers, descriptions, or arguments.
 *
 * Read more about the Settings API in the WordPress codex:
 * http://codex.wordpress.org/Settings_API
 *
 */

function optionsframework_init() {

	// Include the required files
	require_once dirname( __FILE__ ) . '/options-interface.php';
	require_once dirname( __FILE__ ) . '/options-medialibrary-uploader.php';
	
	// Loads the options array from the theme
	if ( $optionsfile = locate_template( array('options.php') ) ) {
		require_once($optionsfile);
	}
	else if (file_exists( dirname( __FILE__ ) . '/options.php' ) ) {
		require_once dirname( __FILE__ ) . '/options.php';
	}
	
	$optionsframework_settings = get_option('optionsframework' );
	
	// Updates the unique option id in the database if it has changed
	optionsframework_option_name();
	
	// Gets the unique id, returning a default if it isn't defined
	if ( isset($optionsframework_settings['id']) ) {
		$option_name = $optionsframework_settings['id'];
	}
	else {
		$option_name = 'optionsframework';
	}
	
	// If the option has no saved data, load the defaults
	if ( ! get_option($option_name) ) {
		optionsframework_setdefaults();
	}
	
	// Registers the settings fields and callback
	register_setting( 'optionsframework', $option_name, 'optionsframework_validate' );
}

/**
 * Ensures that a user with the 'edit_theme_options' capability can actually set the options
 * See: http://core.trac.wordpress.org/ticket/14365
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */

function optionsframework_page_capability( $capability ) {
	return 'edit_theme_options';
}

/* 
 * Adds default options to the database if they aren't already present.
 * May update this later to load only on plugin activation, or theme
 * activation since most people won't be editing the options.php
 * on a regular basis.
 *
 * http://codex.wordpress.org/Function_Reference/add_option
 *
 */

function optionsframework_setdefaults() {
	
	$optionsframework_settings = get_option('optionsframework');

	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];
	
	/* 
	 * Each theme will hopefully have a unique id, and all of its options saved
	 * as a separate option set.  We need to track all of these option sets so
	 * it can be easily deleted if someone wishes to remove the plugin and
	 * its associated data.  No need to clutter the database.  
	 *
	 */
	
	if ( isset($optionsframework_settings['knownoptions']) ) {
		$knownoptions =  $optionsframework_settings['knownoptions'];
		if ( !in_array($option_name, $knownoptions) ) {
			array_push( $knownoptions, $option_name );
			$optionsframework_settings['knownoptions'] = $knownoptions;
			update_option('optionsframework', $optionsframework_settings);
		}
	} else {
		$newoptionname = array($option_name);
		$optionsframework_settings['knownoptions'] = $newoptionname;
		update_option('optionsframework', $optionsframework_settings);
	}
	
	// Gets the default options data from the array in options.php
	$options = optionsframework_options();
	
	// If the options haven't been added to the database yet, they are added now
	$values = of_get_default_values();
	
	if ( isset($values) ) {
		add_option( $option_name, $values ); // Add option with default settings
	}
}

/* Add a subpage called "Theme Options" to the appearance menu. */

if ( !function_exists( 'optionsframework_add_page' ) ) {

	function optionsframework_add_page() {
		$of_page = add_theme_page(__('Theme Options','Bizantine'), __('Theme Options','Bizantine'), 'edit_theme_options', 'options-framework','optionsframework_page');
		
		// Load the required CSS and javscript
		add_action('admin_enqueue_scripts', 'optionsframework_load_scripts');
		add_action( 'admin_print_styles-' . $of_page, 'optionsframework_load_styles' );
	}
	
}

/* Loads the CSS */

function optionsframework_load_styles() {
	wp_enqueue_style('admin-style', OPTIONS_FRAMEWORK_DIRECTORY.'css/admin-style.css');
	wp_enqueue_style('color-picker', OPTIONS_FRAMEWORK_DIRECTORY.'css/colorpicker.css');
}	

/* Loads the javascript */

function optionsframework_load_scripts($hook) {

	if ( 'appearance_page_options-framework' != $hook )
        return;
	
	// Enqueued scripts
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('color-picker', OPTIONS_FRAMEWORK_DIRECTORY.'js/colorpicker.js', array('jquery'));
	wp_enqueue_script('options-custom', OPTIONS_FRAMEWORK_DIRECTORY.'js/options-custom.js', array('jquery'));
	wp_enqueue_script('options-tabs', OPTIONS_FRAMEWORK_DIRECTORY.'js/jquery-ui.js', array('jquery'));
	
	// Inline scripts from options-interface.php
	add_action('admin_head', 'of_admin_head');
}

function of_admin_head() {

	// Hook to add custom scripts
	do_action( 'optionsframework_custom_scripts' );
}

/* 
 * Builds out the options panel.
 *
 * If we were using the Settings API as it was likely intended we would use
 * do_settings_sections here.  But as we don't want the settings wrapped in a table,
 * we'll call our own custom optionsframework_fields.  See options-interface.php
 * for specifics on how each individual field is generated.
 *
 * Nonces are provided using the settings_fields()
 *
 */

if ( !function_exists( 'optionsframework_page' ) ) {
	function optionsframework_page() {
		settings_errors();
?>
	<div class="wrap">
    <div class="metabox-holder">     
    <div id="optionsframework">
    <div class="new_options_container">
		<form action="options.php" method="post">
        <?php settings_fields('optionsframework'); ?>    
    	<div class="new_options_container_top">
        	
            <div class="new_options_container_top_logo">
            	<a href="http://www.themealley.com/"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" /></a>
            </div>          
            <div class="new_options_container_top_twit">
            
            	<div class="new_options_container_top_twit_bookmark">
                
                    <div class="new_options_container_top_twit_bookmarkf">
                                        <div id="fb-root"></div>
                                        <script>(function(d, s, id) {
                                          var js, fjs = d.getElementsByTagName(s)[0];
                                          if (d.getElementById(id)) return;
                                          js = d.createElement(s); js.id = id;
                                          js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                                          fjs.parentNode.insertBefore(js, fjs);
                                        }(document, 'script', 'facebook-jssdk'));</script>
                                        <div class="fb-like" data-href="http://www.facebook.com/ThemeAlley" data-send="false" data-layout="box_count" data-width="450" data-show-faces="false"></div>
                                        </div>                
            
            		
                    <div class="new_options_container_top_twit_bookmarkt">
                              	<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-url="https://www.themealley.com/" data-count="vertical" data-text="Responsive WordPress Themes">Tweet</a>
								<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    </div> 
                    
                    
            		<div class="new_options_container_top_twit_bookmarkg">
					<!-- Place this tag where you want the +1 button to render -->
                    <g:plusone size="tall" href="https://www.themealley.com/"></g:plusone>
                                                                    
                    <!-- Place this render call where appropriate -->
                    <script type="text/javascript">
                                                                      (function() {
                                                                        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                                                                        po.src = 'https://apis.google.com/js/plusone.js';
                                                                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                                                                      })();
                    </script>  
                    </div>                     
                              
            		
            	</div>
            </div>
             
            <div class="new_options_container_top_rate">
            	<a href="https://www.themealley.com/business/">
                	<img src="<?php echo get_template_directory_uri(); ?>/images/rate.png" />
                </a>
            </div>            
                      
        </div>

    	<div id="countrytabs" class="new_options_container_middle">

        
			<ul class="shadetabs">
            	<li class="big"><a href="#country1"><h6><?php echo __('General', 'Bizantine'); ?></h6><p><?php echo __('Settings', 'Bizantine'); ?></p></a></li>
                <li class="small"><a href="#country2"><p><?php echo __('Social Settings', 'Bizantine'); ?></p></a></li>
            	<li class="big"><a href="#country10"><h6><?php echo __('Logo Section', 'Bizantine'); ?></h6><p><?php echo __('Settings', 'Bizantine'); ?></p></a></li>       
                
                <li class="big"><a href="#country3"><h6><?php echo __('Header', 'Bizantine'); ?></h6><p><?php echo __('Settings', 'Bizantine'); ?></p></a></li>
                <li class="big"><a href="#country4"><h6><?php echo __('Layout', 'Bizantine'); ?></h6><p><?php echo __('Settings', 'Bizantine'); ?></p></a></li>
                <li class="small"><a href="#country5"><p><?php echo __('Biz One Settings', 'Bizantine'); ?></p></a></li>  
                <li class="small"><a href="#country7"><p><?php echo __('Biz Seven Settings', 'Bizantine'); ?></p></a></li>               
                <li class="small"><a href="#country9"><p><?php echo __('Standard Blog Settings', 'Bizantine'); ?></p></a></li>
                <li class="big"><a href="#country19"><h6><?php echo __('Portfolio', 'Bizantine'); ?></h6><p><?php echo __('Settings', 'Bizantine'); ?></p></a></li>                                             
                
                <li class="big"><a href="#country11"><h6><?php echo __('Footer', 'Bizantine'); ?></h6><p><?php echo __('Settings', 'Bizantine'); ?></p></a></li>                                             
            </ul>        
        
        
            <div class="shadetabscontent">
            
                <?php optionsframework_fields(); /* Settings */ ?>
            
            </div>
            
            <script type="text/javascript">
            
                            	   jQuery(document).ready(function(){
										jQuery.noConflict();
										jQuery('#countrytabs').tabs();
									});	
            
            </script>        
        
    	<div class="new_options_container_bottom">
        	<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( __('Save Options', 'Bizantine' ) ); ?>" />
            <input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( __('Restore Defaults', 'Bizantine' ) ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Click OK to reset. Any theme settings will be lost!', 'Bizantine' ) ); ?>' );" />
        </div> 
        </form>        
        </div>

         

    </div>
    </div>    
    </div>     
   

<?php
	}
}

/** 
 * Validate Options.
 *
 * This runs after the submit/reset button has been clicked and
 * validates the inputs.
 *
 * @uses $_POST['reset']
 * @uses $_POST['update']
 */
function optionsframework_validate( $input ) {

	/*
	 * Restore Defaults.
	 *
	 * In the event that the user clicked the "Restore Defaults"
	 * button, the options defined in the theme's options.php
	 * file will be added to the option for the active theme.
	 */
	 
	if ( isset( $_POST['reset'] ) ) {
		add_settings_error( 'options-framework', 'restore_defaults', __( 'Default options restored.', 'Bizantine' ), 'updated fade' );
		return of_get_default_values();
	}

	/*
	 * Udpdate Settings.
	 */
	 
	if ( isset( $_POST['update'] ) ) {
		$clean = array();
		$options = optionsframework_options();
		foreach ( $options as $option ) {

			if ( ! isset( $option['id'] ) ) {
				continue;
			}

			if ( ! isset( $option['type'] ) ) {
				continue;
			}

			$id = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $option['id'] ) );

			// Set checkbox to false if it wasn't sent in the $_POST
			if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
				$input[$id] = '0';
			}

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
				foreach ( $option['options'] as $key => $value ) {
					$input[$id][$key] = '0';
				}
			}

			// For a value to be submitted to database it must pass through a sanitization filter
			if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
				$clean[$id] = apply_filters( 'of_sanitize_' . $option['type'], $input[$id], $option );
			}
		}

		add_settings_error( 'options-framework', 'save_options', __( 'Options saved.', 'Bizantine' ), 'updated fade' );
		return $clean;
	}

	/*
	 * Request Not Recognized.
	 */
	
	return of_get_default_values();
}

/**
 * Format Configuration Array.
 *
 * Get an array of all default values as set in
 * options.php. The 'id','std' and 'type' keys need
 * to be defined in the configuration array. In the
 * event that these keys are not present the option
 * will not be included in this function's output.
 *
 * @return    array     Rey-keyed options configuration array.
 *
 * @access    private
 */
 
function of_get_default_values() {
	$output = array();
	$config = optionsframework_options();
	foreach ( (array) $config as $option ) {
		if ( ! isset( $option['id'] ) ) {
			continue;
		}
		if ( ! isset( $option['std'] ) ) {
			continue;
		}
		if ( ! isset( $option['type'] ) ) {
			continue;
		}
		if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
			$output[$option['id']] = apply_filters( 'of_sanitize_' . $option['type'], $option['std'], $option );
		}
	}
	return $output;
}

/**
 * Add Theme Options menu item to Admin Bar.
 */
 
add_action( 'wp_before_admin_bar_render', 'optionsframework_adminbar' );

function optionsframework_adminbar() {
	
	global $wp_admin_bar;
	
	$wp_admin_bar->add_menu( array(
		'parent' => 'appearance',
		'id' => 'of_theme_options',
		'title' => __( 'Theme Options', 'Bizantine' ),
		'href' => admin_url( 'themes.php?page=options-framework' )
  ));
}

if ( ! function_exists( 'of_get_option' ) ) {

	/**
	 * Get Option.
	 *
	 * Helper function to return the theme option value.
	 * If no value has been saved, it returns $default.
	 * Needed because options are saved as serialized strings.
	 */
	 
	function of_get_option( $name, $default = false ) {
		$config = get_option( 'optionsframework' );

		if ( ! isset( $config['id'] ) ) {
			return $default;
		}

		$options = get_option( $config['id'] );

		if ( isset( $options[$name] ) ) {
			return $options[$name];
		}

		return $default;
	}
}