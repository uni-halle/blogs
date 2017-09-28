<?php
/*
    Plugin Name: Cryout Serious Theme Settings
    Plugin URI: https://www.cryoutcreations.eu/serious-theme-settings
    Description: This plugin is designed to restore our theme's settings page functionality after the enforcement of the Customize-based theme settings. It is only compatible with and will only function when one of our themes is active: Nirvana, Parabola, Tempera or Mantra.
    Version: 0.5.8.1
    Author: Cryout Creations
    Author URI: https://www.cryoutcreations.eu
	License: GPLv3
	License URI: http://www.gnu.org/licenses/gpl.html
*/

class Cryout_Theme_Settings {
	public $version = "0.5.8.1";
	public $settings = array();

	private $status = 0; // 0 = inactive, 1 = active, 2 = good theme, wrong version, 3 = wrong theme, 4 = compatibility for wp4.4, 5 = theme requires update

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
	private $requires_update = array(
		'nirvana' => '0.9',
	);
	private $slug = 'cryout-theme-settings';
	private $title = '';
	public $current_theme = array();
	public $plugin_page = array();
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

					add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );

				break;

				default:
				break;
			endswitch;

		endif;

		$cryout_theme_settings_slug = plugin_basename(__FILE__);
		add_filter( 'plugin_action_links_'.$cryout_theme_settings_slug, array( $this, 'settings_link' ) );
		add_filter( 'plugin_row_meta', array( $this, 'meta_links' ), 10, 2 );
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style' ) );

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
			elseif ( isset($this->compatibility_themes[$current_theme_slug]) && (version_compare( $current_theme_version, $this->compatibility_themes[$current_theme_slug], '>=' ) ) &&
					(version_compare($wp_version, '4.3.9999') >= 0) ):
				// compatibility mode
				$this->status = 4;
				return 4;
			elseif ( isset($this->requires_update[$current_theme_slug])):
				// theme requires update to be supported
				$this->status = 5;
				return 0;
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
		if ( strpos( $hook, $this->current_theme['slug'] ) !== false ) {
			wp_enqueue_script( 'cryout-theme-settings-code', plugins_url( 'code.js', __FILE__ ), NULL, $this->version );
		}
	} // enqueue_script()

	public function enqueue_style($hook) {
		if ( $hook == $this->plugin_page ) {
			wp_enqueue_style( 'cryout-theme-settings-style', plugins_url( 'style.css', __FILE__ ), NULL, $this->version );
		}
	}

	// register settings page to dashboard menu
	public function settings_menu() {
		$this->plugin_page = add_submenu_page('themes.php', $this->title, $this->title, 'manage_options', $this->slug, array( $this, 'settings_page' ) );
	}

	// add settings link on plugin page
	public function settings_link($links) {
		$settings_link = '<a href="themes.php?page=' . $this->slug . '">' . __( 'About Plugin', 'cryout-theme-settings' ) . '</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	// add plugin meta links
	public function meta_links( $links, $file ) {
		// Check plugin
		if ( $file === plugin_basename( __FILE__ ) ) {
			unset( $links[2] );
			$links[] = '<a href="http://www.cryoutcreations.eu/cryout-theme-settings/" target="_blank">' . __( 'Plugin homepage', 'cryout-serious-slider' ) . '</a>';
			$links[] = '<a href="https://www.cryoutcreations.eu/forums/f/wordpress/plugins/serious-settings" target="_blank">' . __( 'Support forum', 'cryout-serious-slider' ) . '</a>';
			$links[] = '<a href="http://wordpress.org/plugins/cryout-theme-settings/#developers" target="_blank">' . __( 'Changelog', 'cryout-serious-slider' ) . '</a>';
		}
		return $links;
	}

	public function settings_page() {
		require_once( plugin_dir_path( __FILE__ ) . 'inc/settings.php' );
	}

} // class Cryout_Theme_Settings


/* * * * get things going * * * */
if (is_admin()) $cryout_theme_settings = new Cryout_Theme_Settings;

// EOF
