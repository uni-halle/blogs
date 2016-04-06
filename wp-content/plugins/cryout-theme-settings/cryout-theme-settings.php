<?php
/*
    Plugin Name: Cryout Serious Theme Settings
    Plugin URI: http://www.cryoutcreations.eu/serious-theme-settings
    Description: This plugin is designed to restore our theme's settings page functionality after the enforcement of the Customize-based theme settings. It is only compatible with and will only function when one of our themes is active: Nirvana, Parabola or Tempera.
    Version: 0.5.6
    Author: Cryout Creations
    Author URI: http://www.cryoutcreations.eu
	License: GPLv3
	License URI: http://www.gnu.org/licenses/gpl.html
*/

class Cryout_Theme_Settings {
	public $version = "0.5.6";
	public $settings = array();
	
	private $status = 0; // 0 = inactive, 1 = active, 2 = good theme, wrong version, 3 = wrong theme, 4 = compatibility for wp4.4
	
	private $supported_themes = array(
		'nirvana' => '1.2',
		'tempera' => '1.4',
		'parabola' => '1.6',
		'mantra' => '2.5',
	);
	private $compatibility_themes = array(
		'tempera' => '0.9',
		'parabola' => '0.9',
		'mantra' => '2.0',
	);
	private $slug = 'cryout-theme-settings';
	private $title = '';
	public $current_theme = array();
	public $renamed_theme = false;
	
	public function __construct(){
		add_action( 'init', array( $this, 'register' ) );	
	} // __construct()

	public function register(){
	
		$this->title = __( 'Cryout Serious Theme Settings', 'cryout-theme-settings' );
		if ( $this->supported_theme() ):
		
			switch ($this->status):
				case 1: // restore theme settings
					
					include_once( plugin_dir_path( __FILE__ ) . 'inc/' . strtolower($this->current_theme['slug']) . '.php' );
				
				break; 
				case 4: // repair wrong headings 
				
					add_action( 'admin_init', array( $this, 'enqueue_script' ) );
				
				break;
				
				default: 			
				break;
			endswitch;
		
			//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) ); // not currently used
			//add_action( 'admin_init', array( $this, 'register_settings' ) ); // not currently used
			
		endif; 	

		$cryout_theme_settings_slug = plugin_basename(__FILE__); 
		add_filter( 'plugin_action_links_'.$cryout_theme_settings_slug, array( $this, 'settings_link' ) );			
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );
		
	} // register()
	
	function supported_theme(){
		global $wp_version;
	
		$current_theme_slug = strtolower( wp_get_theme()->Template );
		$current_theme_version = wp_get_theme($current_theme_slug)->Version;
		
		if (!in_array( $current_theme_slug, array_keys( $this->supported_themes) )) {
			// theme slug does not match supported themes
			// perform additional checks for theme constants
		
			if (defined('MANTRA_VERSION')) {
				if ($current_theme_slug != 'mantra') $this->renamed_theme = true;
				$current_theme_slug = 'mantra';
				$current_theme_version = MANTRA_VERSION;		
			}
			if (defined('PARABOLA_VERSION')) {
				if ($current_theme_slug != 'parabola') $this->renamed_theme = true;
				$current_theme_slug = 'parabola';
				$current_theme_version = PARABOLA_VERSION;		
			}
			if (defined('TEMPERA_VERSION')) {
				if ($current_theme_slug != 'tempera') $this->renamed_theme = true;
				$current_theme_slug = 'tempera';
				$current_theme_version = TEMPERA_VERSION;		
			}
			if (defined('NIRVANA_VERSION')) {
				if ($current_theme_slug != 'nirvana') $this->renamed_theme = true;
				$current_theme_slug = 'nirvana';
				$current_theme_version = NIRVANA_VERSION;		
			}
			if (defined('_CRYOUT_THEME_NAME')) {
				if ($current_theme_slug != _CRYOUT_THEME_NAME) $this->renamed_theme = true;
				$current_theme_slug = _CRYOUT_THEME_NAME;
				if (defined('_CRYOUT_THEME_VERSION')) $current_theme_version = _CRYOUT_THEME_VERSION;
			}
		} // end additional checks
		
		$this->current_theme = array(
			'slug' => $current_theme_slug, 
			'version' => $current_theme_version,
		);		
		
		if (in_array( $current_theme_slug, array_keys( $this->supported_themes) )) {
			// supported theme, check version
			if ( version_compare( $current_theme_version, $this->supported_themes[$current_theme_slug], '>=' ) ):
				// supported version
				$this->status = 1;
				return 1;
			elseif ( (version_compare( $current_theme_version, $this->compatibility_themes[$current_theme_slug], '>=' ) ) && 
					(version_compare($wp_version, '4.3.9999') >= 0) ):
				// compatibility mode
				$this->status = 4;
				return 4;
			else:
				// unsupported version
				$this->status = 2;
				return 0;
			endif;
		} else {
			// unsupported theme
			$this->status = 3;
			return 0;
		};	
	
	} // supported_theme()
	
	public function enqueue_script($hook) {
		if ( !empty($GLOBALS['plugin_page']) && ($GLOBALS['plugin_page'] == $this->current_theme['slug'] . '-page') )
			wp_enqueue_script( 'cryout-theme-settings-code', plugins_url( 'code.js', __FILE__ ), NULL, $this->version );
	} // enqueue_script()
	
	/* currently not used
	public function enqueue_styles() {
		wp_register_style( 'cryout-theme-settings', plugins_url( 'style.css', __FILE__ ) );
		wp_enqueue_style( 'cryout-theme-settings' );
	} // enqueue_styles()
	
	// register plugin settings
	public function register_settings() {
		register_setting( 'cryout_theme_settings_settingsgroup', array( $this, 'settings' ) );
	} // register_settings() */
	
	// register settings page to dashboard menu
	public function settings_menu() {
		add_submenu_page('themes.php', $this->title, $this->title, 'manage_options', $this->slug, array( $this, 'settings_page' ) );
	}
	
	// add settings link on plugin page
	public function settings_link($links) { 
		$settings_link = '<a href="themes.php?page=' . $this->slug . '">' . __( 'Settings', 'cryout-theme-settings' ) . '</a>'; 
		array_unshift($links, $settings_link); 
		return $links; 
	}
	
	public function settings_page() {
		require_once( plugin_dir_path( __FILE__ ) . 'inc/settings.php' );
	}	
	
	/* for future use
	function get_settings() {
		
		$setting_defaults = array(
			'id' => 'value',
		);

		$options = get_option('cryout_theme_settings_options');
		$options = array_merge($setting_defaults,(array)$options);
		
		return $options;
	} // get_settings() 
	*/

 
} // class Cryout_Theme_Settings


/* * * * get things going * * * */
if (is_admin()) $cryout_theme_settings = new Cryout_Theme_Settings;

// EOF