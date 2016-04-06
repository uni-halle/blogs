<?php
/**
 * @author    Christian Foellmann & Jason Lemahieu and Kevin Graeme (Cooperative Extension Technology Services)
 * @copyright Copyright (c) 2014 - 2015 Christian Foellmann (http://christian.foellmann.de)
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package   WP-Repository\WPMU_Theme_Usage_Info
 * @version   2.0.0
 */
/*
Plugin Name: WPMU Theme Usage Info
Plugin URI: https://wordpress.org/plugins/wpmu-theme-usage-info/
Description: Provides info to network admins and users on the popularity of themes
Version: 2.0.0
Author: Christian Foellmann & Jason Lemahieu
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wpmu-theme-usage-info
Domain Path: /languages
Network: true

	WPMU Theme Usage Info

	Copyright (C) 2014 - 2015 Christian Foellmann (http://christian.foellmann.de)
	Copyright (C) 2009 - 2013 Board of Regents of the University of Wisconsin System
	Cooperative Extension Technology Services
	University of Wisconsin-Extension

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Main class to run the plugin
 *
 * @since 1.0.0
 */
class WPMU_Theme_Usage_Info {

	/**
	 * Current version of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var string $version
	 */
	public $version = '2.0.0';

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 */
	public function __construct() {
		/* Do nothing here */
	} // END __construct()

	/**
	 * Hook in actions and filters
	 *
	 * @since 2.0.0
	 */
	private function setup_actions() {

		/** Actions ***********************************************************/
		add_action( 'plugins_loaded',              array( $this, 'load_plugin_textdomain' )        );
		add_action( 'admin_head-themes.php',       array( $this, 'add_css'                )        );
		add_action( 'switch_theme',                array( $this, 'auto_refresh'           )        );
		add_action( 'load-themes.php',             array( $this, 'load'                   )        );
		add_action( 'manage_themes_custom_column', array( $this, 'column_active'          ), 10, 3 );

		/** Filters ***********************************************************/
		add_filter( 'manage_themes-network_columns', array( $this, 'add_column'     ) );
		add_filter( 'wp_prepare_themes_for_js',      array( $this, 'extend_overlay' ) );

		/** (De-)Activation ***************************************************/
		register_activation_hook( __FILE__, array( 'WPMU_Theme_Usage_Info', 'activation' ) );

	} // END setup_actions()

	/**
	 * Getter method for retrieving the object instance.
	 *
	 * @since 2.0.0
	 *
	 * @return WPMU_Theme_Usage_Info|null The instance object
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null === $instance ) {
			$instance = new WPMU_Theme_Usage_Info;
			$instance->setup_actions();
		}

		// Always return the instance
		return $instance;

	} // END instance()

	/**
	 * Check for Transient and update appropriately
	 * 
	 * @since 2.0.0
	 */
	public function load() {

		$theme_stats_data = get_site_transient( 'theme_stats_data' );

		if ( ! $theme_stats_data || isset( $_GET['manual-stats-refresh'] ) )  {
			$theme_stats_data = $this->generate_theme_blog_list();
		}

	} // END load()

	/**
	 * Regenerate the statistics on every theme switch network-wide
	 *
	 * @since 1.0.0
	 */
	public function auto_refresh() {

		if ( wp_is_large_network() ) {
			$this->load();
		} else {
			$this->generate_theme_blog_list();
		}

	} // END auto_refresh()

	public function add_css() {
		?>
<style type="text/css">
	.column-active p { width: 200px; }
	.siteslist { display:none; }
</style>
		<?php
	} // END add_css()

	public function add_column( $columns ) {

		$columns['active'] = __( 'Usage', 'wpmu-theme-usage-info' );

		return $columns;

	} // END add_column()

	/**
	 * Fires inside each custom column of the Multisite themes list table.
	 *
	 * @since 3.1.0
	 *
	 * @param string   $column_name Name of the column.
	 * @param string   $stylesheet  Directory name of the theme.
	 * @param WP_Theme $theme       Current WP_Theme object.
	 */
	public function column_active( $column_name, $stylesheet, $theme ) {

		if ( 'active' === $column_name ) {

			$network_data = get_site_transient( 'theme_stats_data' );
			$data         = isset( $network_data[ $stylesheet ] ) ? $network_data[ $stylesheet ] : 0;
			$active_count = isset( $network_data[ $stylesheet ] ) ? sizeOf( $data )              : 0;

			echo '<p>';
			if ( 0 === $active_count ) {
				_e( 'Not Active on any site', 'wpmu-theme-usage-info' );
			} else {
				printf(
					_n( 'Active on %2$s %1$d site %3$s', 'Active on %2$s %1$d sites %3$s', $active_count, 'wpmu-theme-usage-info' ),
					$active_count,
					"<a href=\"javascript:;\" onClick=\"jQuery('#siteslist_{$theme->stylesheet}').toggle(400);\">",
					'</a>'
				);
			}
			echo '</p>';

			if ( isset( $network_data[ $theme->stylesheet ] ) && is_array( $network_data[ $theme->stylesheet ] ) ) {

				echo "<ul class=\"siteslist\" id=\"siteslist_{$theme->stylesheet}\">";

				foreach ( $network_data[ $theme->stylesheet ] as $theme_data ) {
					$link_title = empty( $theme_data['name'] ) ? $theme_data['siteurl'] : $theme_data['name'];
					echo '<li><a href="http://' . esc_html( $theme_data['siteurl'] ) . '" target="new">' . esc_html( $link_title ) . '</a></li>';
				}

				echo '</ul>';
			}

		} // END if 'active' column

	} // END column_active()

	/**
	 * Fetch sites and the active themes for every single site
	 *
	 * @todo fetch all themes and list them with number of blogs even if count == 0
	 *
	 * @since 1.0.0
	 *
	 * @global object $wpdb
	 * @global array $current_site
	 * @return array
	 */
	private function generate_theme_blog_list() {

		global $wpdb, $current_site;

		$select     = $wpdb->prepare( "SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d ORDER BY domain ASC", $current_site->id );
		$sites      = $wpdb->get_results( $select );
		$sitethemes = array();
		$processedthemes = array();

		foreach ( $sites as $site ) {
			switch_to_blog( $site->blog_id );
			$cto = wp_get_theme();
			$ct  = $cto->stylesheet;

			if ( constant('VHOST') == 'yes' ) {
				$siteurl = $site->domain;
			} else {
				$siteurl = trailingslashit( $site->domain . $site->path );
			}

			if ( array_key_exists( $ct, $processedthemes ) == false ) {
				$sitethemes[ $ct ][0] = array(
					'siteid' => $site->blog_id,
					/*'path' => $path, 'domain' => $domain,*/
					'name' => get_bloginfo('name'),
					'siteurl' => $siteurl,
				);
				$processedthemes[ $ct ] = true;
			} else {
				//get the size of the current array of blogs
				$count = sizeof( $sitethemes[ $ct ] );
				$sitethemes["$ct"][ $count ] = array(
					'siteid' => $site->blog_id,
					/* 'path' => $path, 'domain' => $domain,*/
					'name' => get_bloginfo('name'),
					'siteurl' => $siteurl,
				);

			}

			restore_current_blog();
		} // END foreach 'sites'

		ksort( $sitethemes );

		$hours   = wp_is_large_network() ? 24 : 2;
		$refresh = apply_filters( 'wpmu_theme_stats_refresh' , $hours * HOUR_IN_SECONDS );

		set_site_transient( 'theme_stats_data', $sitethemes, $refresh );

		return $sitethemes;

	} // END generate_theme_blog_list()

	/**
	 * Append the usage count to the version display on the theme overlay
	 *
	 * @since 2.0.0
	 *
	 * @param  string $prepared_themes
	 * @return string
	 */
	public function extend_overlay( $prepared_themes ) {

		$options = false;
		$display = apply_filters( 'wpmu_theme_stats_show_count', $options );

		if ( $display ) {

			$network_data = get_site_transient( 'theme_stats_data' );

			foreach ( $prepared_themes as $theme => $key ) {

				$data         = isset( $network_data[ $theme ] ) ? $network_data[ $theme ] : 0;
				$active_count = isset( $network_data[ $theme ] ) ? sizeOf( $data )         : 0;

				$prepared_themes[ $theme ]['version'] .= ' | ' . sprintf( _n( 'Active on %2$s %1$d site %3$s', 'Active on %2$s %1$d sites %3$s', $active_count, 'wpmu-theme-usage-info' ) , $active_count, '', '' );

			}
		}

		return $prepared_themes;

	} // END extend_overlay()

	/**
	 * Load the plugin's textdomain hooked to 'plugins_loaded'.
	 *
	 * @since 1.0.0
	 *
	 * @see load_plugin_textdomain()
	 * @see plugin_basename()
	 * @action plugins_loaded
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wpmu-theme-usage-info',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);

	} // END load_plugin_textdomain()

	/**
	 * Pre-Activation checks
	 *
	 * Checks if this is a multisite installation
	 *
	 * @since 2.0.0
	 */
	public static function activation() {

		if ( ! is_multisite() ) {
			wp_die( __( 'This plugin only runs on multisite installations. The functionality makes no sense for WP single sites.', 'wpmu-theme-usage-info' ) );
		}

		// Delete legacy options
		delete_site_option( 'cets_theme_info_allow' );
		delete_site_option( 'cets_theme_info_data' );
		delete_site_option( 'cets_theme_info_data_freshness' );

	} // END activation()

} // END class WPMU_Theme_Usage_Info

/**
 * Instantiate the main class
 *
 * @since 2.0.0
 *
 * @var object $wpmu_theme_usage_info Holds the instantiated class {@uses WPMU_Theme_Usage_Info}
 */
function WPMU_Theme_Usage_Info() {
	return WPMU_Theme_Usage_Info::instance();
} // END WPMU_Theme_Usage_Info()

WPMU_Theme_Usage_Info();
