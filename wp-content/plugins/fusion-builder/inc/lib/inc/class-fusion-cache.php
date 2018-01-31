<?php
/**
 * The main cache class.
 *
 * @package Fusion-Library
 * @subpackage Fusion-Cache
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * The cache handler.
 *
 * @since 1.1.2
 */
class Fusion_Cache {

	/**
	 * Resets all caches.
	 *
	 * @since 1.1.2
	 * @access public
	 * @param array $delete_cache An array of caches to delete.
	 * @return void
	 */
	public function reset_all_caches( $delete_cache = array() ) {

		$all_caches = apply_filters( 'reset_all_caches',
			array(
				'compiled_assets'  => true,
				'fb_pages'         => true,
				'demo_data'        => true,
				'po_export'        => true,
				'transients'       => true,
				'patcher_messages' => true,
				'other_caches'     => true,
			)
		);

		$delete_cache = wp_parse_args(
			$delete_cache,
			$all_caches
		);

		if ( ! in_array( true, $delete_cache, true ) ) {
			// Early exit if all set to false.
			return;
		}

		// Get the upload directory for this site.
		$upload_dir = wp_upload_dir();

		if ( ! defined( 'FS_METHOD' ) ) {
			define( 'FS_METHOD', 'direct' );
		}

		// The WordPress filesystem.
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		// Delete file caches.
		if ( true === $delete_cache['compiled_assets'] ) {
			$delete_js_files  = $wp_filesystem->delete( $upload_dir['basedir'] . '/fusion-scripts', true, 'd' );
			$delete_css_files = $wp_filesystem->delete( $upload_dir['basedir'] . '/fusion-styles', true, 'd' );

			// Delete cached CSS in the database.
			update_option( 'fusion_dynamic_css_posts', array() );
		}

		if ( true === $delete_cache['demo_data'] ) {
			$delete_demo_files = $wp_filesystem->delete( $upload_dir['basedir'] . '/avada-demo-data', true, 'd' );
		}

		if ( true === $delete_cache['fb_pages'] ) {
			$delete_fb_pages = $wp_filesystem->delete( $upload_dir['basedir'] . '/fusion-builder-avada-pages', true, 'd' );
		}

		if ( true === $delete_cache['po_export'] ) {
			$delete_fb_pages = $wp_filesystem->delete( $upload_dir['basedir'] . '/fusion-page-options-export', true, 'd' );
		}

		if ( true === $delete_cache['transients'] ) {
			// Delete transients with dynamic names.
			$dynamic_transients = array(
				'_transient_fusion_dynamic_css_%',
				'_transient_avada_%',
				'_transient_fusion_wordpress_org_plugins',
				'_site_transient_timeout_fusion_dynamic_css_%',
				'_site_transient_timeout_avada_%',
				'_site_transient_timeout_fusion_wordpress_org_plugins',
			);
			global $wpdb;
			foreach ( $dynamic_transients as $transient ) {
				// @codingStandardsIgnoreLine
				$wpdb->query( $wpdb->prepare(
					"DELETE FROM $wpdb->options WHERE option_name LIKE %s",
					$transient
				) );
			}

			// Cleanup other transients.
			$transients = array(
				'avada_googlefonts_contents',
				'fusion_css_cache_cleanup',
				'_fusion_ajax_works',
				'fusion_builder_demos_import_skip_check',
				'fusion_patches',
				'fusion_envato_api_down',
				'fusion_dynamic_js_filenames',
				'fusion_patcher_check_num',
				'fusion_dynamic_js_readable',
			);
			foreach ( $transients as $transient ) {
				delete_transient( $transient );
				delete_site_transient( $transient );
			}
		}

		if ( true === $delete_cache['patcher_messages'] ) {
			// Delete patcher messages.
			delete_site_option( 'fusion_patcher_messages' );
		}

		if ( true === $delete_cache['other_caches'] ) {
			// Delete 3rd-party caches.
			$this->clear_third_party_caches();
		}
		do_action( 'fusion_cache_reset_after' );
	}

	/**
	 * Clear cache from:
	 *  - W3TC,
	 *  - WordPress Total Cache
	 *  - WPEngine
	 *  - Varnish
	 *
	 * @access protected
	 * @since 1.0.0
	 */
	protected function clear_third_party_caches() {

		// If W3 Total Cache is being used, clear the cache.
		if ( function_exists( 'w3tc_flush_posts' ) ) {
			w3tc_flush_posts();
		}
		// if WP Super Cache is being used, clear the cache.
		if ( function_exists( 'wp_cache_clean_cache' ) ) {
			global $file_prefix;
			wp_cache_clean_cache( $file_prefix );
		}
		// If SG CachePress is installed, rese its caches.
		if ( class_exists( 'SG_CachePress_Supercacher' ) ) {
			if ( method_exists( 'SG_CachePress_Supercacher', 'purge_cache' ) ) {
				SG_CachePress_Supercacher::purge_cache();
			}
		}
		// Clear caches on WPEngine-hosted sites.
		if ( class_exists( 'WpeCommon' ) ) {
			if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
				WpeCommon::purge_memcached();
			}
			if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {
				WpeCommon::clear_maxcdn_cache();
			}
			if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
				WpeCommon::purge_varnish_cache();
			}
		}
		// if Autoptimize Cache is being used, clear the cache.
		if ( class_exists( 'autoptimizeCache' ) && method_exists( 'autoptimizeCache', 'clearall' ) ) {
			autoptimizeCache::clearall();
		}
		// if LiteSpeed Cache is being used, clear the cache.
		if ( class_exists( 'LiteSpeed_Cache_API' ) && method_exists( 'LiteSpeed_Cache_API', 'purge_all' ) ) {
			LiteSpeed_Cache_API::purge_all();
		}

		if ( ! class_exists( 'Fusion_Settings' ) ) {
			include_once 'class-fusion-settings.php';
		}

		// Clear Varnish caches.
		$settings = Fusion_Settings::get_instance();
		if ( $settings->get( 'cache_server_ip' ) ) {
			$this->clear_varnish_cache();
		}
	}

	/**
	 * Clear varnish cache for the dynamic CSS file.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @return void
	 */
	protected function clear_varnish_cache() {

		// Parse the URL for proxy proxies.
		$p = wp_parse_url( home_url() );

		$varnish_x_purgemethod = ( isset( $p['query'] ) && ( 'vhp=regex' === $p['query'] ) ) ? 'regex' : 'default';

		// Build a varniship.
		$varniship = get_option( 'vhp_varnish_ip' );
		$settings  = Fusion_Settings::get_instance();
		if ( $settings->get( 'cache_server_ip' ) ) {
			$varniship = $settings->get( 'cache_server_ip' );
		} elseif ( defined( 'VHP_VARNISH_IP' ) && VHP_VARNISH_IP ) {
			$varniship = VHP_VARNISH_IP;
		}

		// If we made varniship, let it sail.
		$purgeme = ( isset( $varniship ) && null !== $varniship ) ? $varniship : $p['host'];

		wp_remote_request( 'http://' . $purgeme,
			array(
				'method'  => 'PURGE',
				'headers' => array(
					'host'           => $p['host'],
					'X-Purge-Method' => $varnish_x_purgemethod,
				),
			)
		);
	}

	/**
	 * Handles resetting caches.
	 *
	 * @access public
	 * @since 1.1.2
	 */
	public function reset_caches_handler() {

		if ( is_multisite() && is_main_site() ) {
			$sites = get_sites();
			foreach ( $sites as $site ) {
				// @codingStandardsIgnoreLine
				switch_to_blog( $site->blog_id );
				$this->reset_all_caches();
				restore_current_blog();
			}
			return;
		}
		$this->reset_all_caches();
	}
}
