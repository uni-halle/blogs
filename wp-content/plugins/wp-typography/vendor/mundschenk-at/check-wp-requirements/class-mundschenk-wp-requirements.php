<?php
/**
 *  This file is part of mundschenk-at/check-wp-requirements.
 *
 *  Copyright 2014-2018 Peter Putzer.
 *  Copyright 2009-2011 KINGdesk, LLC.
 *
 *  This program is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation; either version 2
 *  of the License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 *  ***
 *
 *  @package mundschenk-at/check-wp-requirements
 *  @license http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * This class checks if the required runtime environment is available.
 *
 * Included checks:
 *    - PHP version
 *    - mb_string extension
 *    - UTF-8 encoding
 *
 * Note: All code must be executable on PHP 5.2.
 */
class Mundschenk_WP_Requirements {

	/**
	 * The minimum requirements for running the plugins. Must contain:
	 *  - 'php'
	 *  - 'multibyte'
	 *  - 'utf-8'
	 *
	 * @var array A hash containing the version requirements for the plugin.
	 */
	private $install_requirements;

	/**
	 * The user-visible name of the plugin.
	 *
	 * @todo Should the plugin name be translated?
	 * @var string
	 */
	private $plugin_name;

	/**
	 * The full path to the main plugin file.
	 *
	 * @var string
	 */
	private $plugin_file;

	/**
	 * Sets up a new Mundschenk_WP_Requirements object.
	 *
	 * @param string $name         The plugin name.
	 * @param string $plugin_path  The full path to the main plugin file.
	 * @param string $textdomain   The text domain used for i18n.
	 * @param array  $requirements The requirements to check against.
	 */
	public function __construct( $name, $plugin_path, $textdomain, $requirements ) {
		$this->plugin_name = $name;
		$this->plugin_file = $plugin_path;
		$this->textdomain  = $textdomain;

		$this->install_requirements = \wp_parse_args( $requirements, array(
			'php'       => '5.2.0',
			'multibyte' => false,
			'utf-8'     => false,
		) );
	}

	/**
	 * Checks if all runtime requirements for the plugin are met.
	 *
	 * @return bool
	 */
	public function check() {
		$requirements_met = true;

		if ( ! empty( $this->install_requirements['php'] ) && version_compare( PHP_VERSION, $this->install_requirements['php'], '<' ) ) {
			if ( is_admin() ) {
				add_action( 'admin_notices', array( $this, 'admin_notices_php_version_incompatible' ) );
			}
			$requirements_met = false;
		} elseif ( ! empty( $this->install_requirements['multibyte'] ) && ! $this->check_multibyte_support() ) {
			if ( is_admin() ) {
				add_action( 'admin_notices', array( $this, 'admin_notices_mbstring_incompatible' ) );
			}
			$requirements_met = false;
		} elseif ( ! empty( $this->install_requirements['utf-8'] ) && ! $this->check_utf8_support() ) {
			if ( is_admin() ) {
				add_action( 'admin_notices', array( $this, 'admin_notices_charset_incompatible' ) );
			}
			$requirements_met = false;
		}

		if ( ! $requirements_met && is_admin() ) {
			// Load text domain to ensure translated admin notices.
			load_plugin_textdomain( $this->textdomain );
		}

		return $requirements_met;
	}

	/**
	 * Deactivates the plugin.
	 */
	public function deactivate_plugin() {
		deactivate_plugins( plugin_basename( $this->plugin_file ) );
	}

	/**
	 * Checks if multibyte functions are supported.
	 *
	 * @return bool
	 */
	protected function check_multibyte_support() {
		return function_exists( 'mb_strlen' )
			&& function_exists( 'mb_strtolower' )
			&& function_exists( 'mb_substr' )
			&& function_exists( 'mb_detect_encoding' );
	}

	/**
	 * Checks if the blog charset is set to UTF-8.
	 *
	 * @return bool
	 */
	protected function check_utf8_support() {
		return 'utf-8' === strtolower( get_bloginfo( 'charset' ) );
	}

	/**
	 * Print 'PHP version incompatible' admin notice
	 */
	public function admin_notices_php_version_incompatible() {
		$this->display_error_notice(
			/* translators: 1: plugin name 2: target PHP version number 3: actual PHP version number */
			__( 'The activated plugin %1$s requires PHP %2$s or later. Your server is running PHP %3$s. Please deactivate this plugin, or upgrade your server\'s installation of PHP.', $this->textdomain ),
			"<strong>{$this->plugin_name}</strong>",
			$this->install_requirements['php'],
			phpversion()
		);
	}

	/**
	 * Prints 'mbstring extension missing' admin notice
	 */
	public function admin_notices_mbstring_incompatible() {
		$this->display_error_notice(
			/* translators: 1: plugin name 2: mbstring documentation URL */
			__( 'The activated plugin %1$s requires the mbstring PHP extension to be enabled on your server. Please deactivate this plugin, or <a href="%2$s">enable the extension</a>.', $this->textdomain ),
			"<strong>{$this->plugin_name}</strong>",
			/* translators: URL with mbstring PHP extension installation instructions */
			__( 'http://www.php.net/manual/en/mbstring.installation.php', $this->textdomain )
		);
	}

	/**
	 * Prints 'Charset incompatible' admin notice
	 */
	public function admin_notices_charset_incompatible() {
		$this->display_error_notice(
			/* translators: 1: plugin name 2: current character encoding 3: options URL */
			__( 'The activated plugin %1$s requires your blog use the UTF-8 character encoding. You have set your blogs encoding to %2$s. Please deactivate this plugin, or <a href="%3$s">change your character encoding to UTF-8</a>.', $this->textdomain ),
			"<strong>{$this->plugin_name}</strong>",
			get_bloginfo( 'charset' ),
			'/wp-admin/options-reading.php'
		);
	}

	/**
	 * Shows an error message in the admin area.
	 *
	 * @param string $format ... An `sprintf` format string, followd by an unspecified number of optional parameters.
	 */
	protected function display_error_notice( $format ) {
		if ( func_num_args() < 1 || empty( $format ) ) {
			return; // abort.
		}

		$args    = func_get_args();
		$format  = array_shift( $args );
		$message = vsprintf( $format, $args );

		require dirname( $this->plugin_file ) . '/admin/partials/requirements-error-notice.php';
	}
}
