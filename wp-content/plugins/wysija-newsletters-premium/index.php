<?php
/*
Plugin Name: MailPoet Newsletters Premium
Plugin URI: http://www.mailpoet.com/
Description: Extended functionalities to the free version.
Version: 2.6.16
Author: MailPoet
Author URI: http://www.mailpoet.com/
License: GPLv2 or later
Text Domain: wysija-newsletters
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
*/
defined( 'ABSPATH' ) or die( 'Not allowed' );
define( 'WYSIJANLP', 'wysija-newsletters-premium' );
define( 'WYSIJANLP_URL', plugins_url() . '/wysija-newsletters-premium/' );
define( 'WYSIJANLP_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

add_action( 'plugins_loaded', 'wnlp_trigger_hooks' );
function wnlp_trigger_hooks(){
	if ( class_exists( 'WYSIJA' ) ){
		$premium_helper = WYSIJA::get( 'premium', 'helper', false, WYSIJANLP );
		$model_config = WYSIJA::get( 'config' , 'model' );
		$multisite_prefix = '';
		if ( is_multisite() ) {
			$multisite_prefix = 'ms_';
		}

		add_action( 'wysija_cron_queue', array( $premium_helper, 'croned_queue_process' ), 11 );

		// we don't process the bounce automatically unless the option is ticked
		if ( $model_config->getValue( $multisite_prefix . 'bounce_process_auto' ) ){
			add_action( 'wysija_cron_bounce', array( $premium_helper, 'croned_bounce' ) );
		}

		add_action( 'wysija_cron_weekly', array( $premium_helper, 'croned_weekly' ) );
	}
}

add_filter( 'mailpoet/package', '_filter_mailpoet_premium_package', 10, 2 );
function _filter_mailpoet_premium_package( $package, $path ) {
	if ( ! in_array( $path, array( 'premium', 'wysija-newsletters-premium', 'wysija-newsletters-premium/index.php' ) ) ){
		return $package;
	}

	return 'premium';
}

add_filter( 'mailpoet/get_version', '_filter_mailpoet_premium_version', 10, 2 );
function _filter_mailpoet_premium_version( $version, $package ) {
	if ( $package != 'premium' ){
		return $version;
	}

	return '2.6.16';
}

function wysija_newsletters_premium_init(){
	if ( class_exists( 'WYSIJA' ) ) {
		$hPremium = WYSIJA::get( 'premium', 'helper', false, WYSIJANLP );
		$hPremium->init();
	}
}

global $wysijaextended;
if ( ! $wysijaextended ){
	$wysijaextended = array();
}
$wysijaextended['wysija-newsletters-premium'] = array(
	'name' => 'MailPoet Newsletters Premium',
	'init' => 'wysija_newsletters_premium_init',
);

/*
 *
 * COMMON part to extending wysija
 *
 */
if ( is_admin() ) {
	add_action( 'plugins_loaded', 'is_wysija_installed' );
} else {
	add_action( 'widgets_init', 'register_wysija_widgets' );
}
add_action( 'admin_notices', 'wysija_error_wp_msgs' );

if ( ! function_exists( 'is_wysija_installed' ) ){
	function is_wysija_installed(){
		if ( function_exists( 'wysija_is_plg_active' ) ){
			global $wysijaextended;
			if ( $wysijaextended ){
				foreach ( $wysijaextended as $key => $plugextend ){
					$mainFileName = 'wysija-newsletters/index.php';
					if ( wysija_is_plg_active( $mainFileName ) )  {
						add_action( 'widgets_init', 'register_wysija_widgets' );
						if ( isset( $plugextend['init'] ) ){
							add_action( 'init', $plugextend['init'] );
						}
					} else {
						global $wysija_wp_msg;

						if ( ! current_user_can( 'activate_plugins' ) ){
							continue;
						}

						$activate_link = wp_nonce_url(
							add_query_arg(
								array(
									'action' => 'activate',
									'plugin' => $mainFileName,
									'plugin_status' => 'all'
								),
								admin_url( 'plugins.php' )
							),
							'activate-plugin_' . $mainFileName
						);

						$wysija_wp_msg['error'][] = __( 'Sorry, <strong>' . $plugextend['name'] . '</strong> requires <strong>MailPoet Newsletters</strong> plugin to be active. Please <a href="' . $activate_link . '">activate your MailPoet Newsletters</a>.', 'wysija-newsletters-premium' );
					}
				}
			}
		}
	}
}

if ( ! function_exists( 'register_wysija_widgets' ) ){
	function register_wysija_widgets() {
		global $wysijaextended;
		if ( $wysijaextended ){
			foreach ( $wysijaextended as $key => $plugextend ){
				if ( isset( $plugextend['className'] ) ){
					register_widget( $plugextend['className'] );
				}
			}
		}
	}
}

if ( ! function_exists( 'init_wysija_extend' ) ){
	function init_wysija_extend() {
		global $wysijaextended;
		if ( $wysijaextended ){
			foreach ( $wysijaextended as $key => $plugextend ){
				if ( isset( $plugextend['init'] ) ){
					add_action( 'init', $plugextend['init'] );
				}
			}
		}
	}
}

if ( ! function_exists( 'wysija_error_wp_msgs' ) ){
	function wysija_error_wp_msgs() {
		global $wysija_wp_msg;
		$msgs = '';
		if ( $wysija_wp_msg ){
			foreach ( $wysija_wp_msg as $keymsg => $wp2 ){
				$msgs = '<div class="' . $keymsg . ' fade">';
				foreach ( $wp2 as $mymsg ){
					$msgs .= '<p>' . $mymsg . '</p>';
				}
				$msgs .= '</div>';
			}
		}
		echo $msgs;
	}
}

if ( ! function_exists( 'wysija_is_plg_active' ) ){
	function wysija_is_plg_active( $filename ){
		$arrayactiveplugins = get_option( 'active_plugins' );
		if ( in_array( $filename, $arrayactiveplugins ) ){
			return true;
		}
		if ( is_multisite() ) {
			$arrayactiveplugins = get_site_option( 'active_sitewide_plugins' );
			if ( isset( $arrayactiveplugins[ $filename ] ) || in_array( $filename, $arrayactiveplugins ) ){
				return true;
			}
		}
		return false;
	}
}
