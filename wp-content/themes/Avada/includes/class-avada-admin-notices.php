<?php
/**
 * Handler for notices in admin
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      5.3
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Handle contact pages.
 */
class Avada_Admin_Notices {

	/**
	 * Construct the object and init hooks
	 *
	 * @since 5.3
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'load_script' ) );
		add_action( 'wp_ajax_avada_dismiss_admin_notice', array( $this, 'dismiss_admin_notice' ) );
	}

	/**
	 * Add JS and variables.
	 *
	 * @access public
	 * @since 5.3
	 */
	public function load_script() {

		$vars = array(
			'nonce' => wp_create_nonce( 'avada_admin_notice' ),
		);

		$vars = array_merge( $vars, $this->admin_notices_textdomain_strings() );

		wp_enqueue_script(
			'avada-admin-notices',
			trailingslashit( Avada::$template_dir_url ) . 'assets/admin/js/avada-admin-notices.js',
			array( 'jquery', 'common' ),
			false,
			true
		);

		wp_localize_script( 'avada-admin-notices', 'avadaAdminNotices', $vars );
	}

	/**
	 * Prepare text domain strings for admin notices.
	 *
	 * @access public
	 * @since 5.3
	 */
	public function admin_notices_textdomain_strings() {

		$text_strings = array(
			/* translators: The "Fusion Documentation" link. */
			'deprecated_side_nav_teamplate' => sprintf( __( 'The \'Side Navigation\' page template will be deprecated in a future version of Avada. We have replaced it with a better solution, the <a href="%s" target="_blank" rel="noopener noreferrer">Avada Vertical Menu widget</a>. This new widget offers the same features but with greater flexibility. It works with the WP menu system. Please utilize this new method instead of the page template which will eventually be removed.', 'Avada' ), 'https://theme-fusion.com/avada-doc/widgets/avada-vertical-and-horizontal-menu-widgets' ),
		);

		return $text_strings;

	}

	 /**
	  * Ajax request handler for notices dismissal.
	  *
	  * @access public
	  * @since 5.3
	  */
	public function dismiss_admin_notice() {

		check_ajax_referer( 'avada_admin_notice', 'nonce' );

		if ( ! empty( $_POST ) ) {
			$avada_admin_notices = get_transient( 'avada_admin_notices' );
			$avada_admin_notices = ( false === $avada_admin_notices ? array() : $avada_admin_notices );
			// @codingStandardsIgnoreLine
			$option_name        = wp_unslash( sanitize_text_field( $_POST['option_name'] ) );
			if ( ! array_key_exists( $option_name, $avada_admin_notices ) ) {
				$avada_admin_notices[ $option_name ] = 'dismissed';
			}
			set_transient( 'avada_admin_notices', $avada_admin_notices, 0 );
		}

		wp_die();
	}

	 /**
	  * Check if notice if active.
	  *
	  * @access static
	  * @since 5.3
	  * @param string $option_name name of notice.
	  * @return bool
	  */
	public static function is_admin_notice_active( $option_name ) {
		$avada_admin_notices = get_transient( 'avada_admin_notices' );

		if ( is_array( $avada_admin_notices ) && array_key_exists( $option_name, $avada_admin_notices ) ) {
			return false;
		} else {
			return true;
		}
	}
}
