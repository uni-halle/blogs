<?php

class AWPCP_Admin_Settings {

	public function __construct() {
		// TODO: avoid instatiation of this class until is necessary
		$pages = awpcp_classfieds_pages_settings();
		$facebook = new AWPCP_Facebook_Page_Settings();
	}

	public function dispatch() {
		global $awpcp;

		$groups = $awpcp->settings->groups;
		unset($groups['private-settings']);

		$group = $groups[awpcp_request_param('g', 'pages-settings')];

		ob_start();
			include(AWPCP_DIR . '/admin/templates/admin-panel-settings.tpl.php');
			$content = ob_get_contents();
		ob_end_clean();

		echo $content;
	}

	public function scripts() {
		wp_enqueue_script('awpcp-admin-settings');
	}
}

function awpcp_classfieds_pages_settings() {
	return new AWPCP_Classified_Pages_Settings( awpcp_missing_pages_finder() );
}

class AWPCP_Classified_Pages_Settings {

	private $missing_pages_finder;

	public function __construct( $missing_pages_finder ) {
		$this->missing_pages_finder = $missing_pages_finder;

		add_action('awpcp-admin-settings-page--pages-settings', array($this, 'dispatch'));
	}

	public function dispatch() {
		global $awpcp;

		if ( $this->should_restore_pages() ) {
			$restored_pages = awpcp_pages_creator()->restore_missing_pages();
		} else {
			$restored_pages = array();
		}

		$missing = awpcp_array_filter_recursive( $this->missing_pages_finder->find_broken_page_id_references() );

		ob_start();
			include(AWPCP_DIR . '/admin/templates/admin-panel-settings-pages-settings.tpl.php');
			$content = ob_get_contents();
		ob_end_clean();

		echo $content;
	}

	private function should_restore_pages() {
		$nonce = awpcp_post_param( '_wpnonce' );
		$restore = awpcp_post_param( 'restore-pages', false );

		return $restore && wp_verify_nonce( $nonce, 'awpcp-restore-pages' );
	}
}

class AWPCP_Facebook_Page_Settings {

    /**
     * @var Settings
     */
    private $settings;

	public function __construct() {
        $this->settings = awpcp()->settings;

		add_action( 'current_screen', array( $this, 'maybe_redirect' ) );
		add_action( 'awpcp-admin-settings-page--facebook-settings', array($this, 'dispatch'));
	}

	public function maybe_redirect() {
		if ( !isset( $_GET['g'] ) || $_GET['g'] != 'facebook-settings' || $this->get_current_action() != 'obtain_user_token' )
			return;

		if ( isset( $_GET[ 'error_code' ] ) ) {
			return $this->redirect_with_error( $_GET[ 'error_code' ], urlencode( $_GET['error_message'] )  );
		}

		$code = isset( $_GET['code'] ) ? $_GET['code'] : '';

		$fb = AWPCP_Facebook::instance();
		$access_token = $fb->token_from_code( $code );

		if ( ! $access_token ) {
			return $this->redirect_with_error( 1, 'Unkown error trying to exchange code for access token.' );
		}

        $this->settings->update_option( 'facebook-user-access-token', $access_token );

		wp_redirect( admin_url( 'admin.php?page=awpcp-admin-settings&g=facebook-settings' ) );
		die();
	}

	public function get_current_action() {
		if ( isset( $_POST['diagnostics'] ) )
			return 'diagnostics';

		if ( isset( $_REQUEST['obtain_user_token'] ) && $_REQUEST['obtain_user_token'] == 1 )
			return 'obtain_user_token';

		return 'display_settings';
	}

	private function redirect_with_error( $error_code, $error_message ) {
		$params = array( 'code_error' => $error_code, 'error_message' => $error_message );
		$settings_url = admin_url( 'admin.php?page=awpcp-admin-settings&g=facebook-settings' );
		wp_redirect( add_query_arg( urlencode_deep( $params ), $settings_url ) );
		die();
	}

	public function dispatch() {
		$action = $this->get_current_action();

		switch ( $action ) {
			case 'diagnostics':
			case 'display_settings':
			default:
				return $this->display_settings();
				break;
		}
	}

	private function display_settings( $errors=array() ) {
        $fb = AWPCP_Facebook::instance();

        $redirect_uri = add_query_arg( 'obtain_user_token', 1, admin_url( '/admin.php?page=awpcp-admin-settings&g=facebook-settings' ) );

		if ( isset( $_GET['code_error'] ) && isset( $_GET['error_message'] )  ) {
            $error_message = __( 'We could not obtain a valid access token from Facebook. The API returned the following error: %s', 'another-wordpress-classifieds-plugin' );
            $error_message = sprintf( $error_message, wp_unslash( urldecode_deep( $_GET['error_message'] ) ) );

            $errors[] = esc_html( $error_message );
		} else if ( isset( $_GET['code_error'] ) ) {
			$errors[] = esc_html( __( 'We could not obtain a valid access token from Facebook. Please try again.', 'another-wordpress-classifieds-plugin' ) );
		}

		if ( $this->get_current_action() == 'diagnostics' ) {
			$diagnostics_errors = array();
			$fb->validate_config( $diagnostics_errors );

			$error_msg  = '';
			$error_msg .= '<strong>' . __( 'Facebook Config Diagnostics', 'another-wordpress-classifieds-plugin' ) . '</strong><br />';

			if ( $diagnostics_errors ) {
				foreach ( $diagnostics_errors as &$e ) {
					$error_msg .= '&#149; ' . $e . '<br />';
				}
			} else {
				$error_msg .= __( 'Everything looks OK.', 'another-wordpress-classifieds-plugin' );
			}

			$errors[] = $error_msg;
		}

		ob_start();
			include(AWPCP_DIR . '/admin/templates/admin-panel-settings-facebook-settings.tpl.php');
			$content = ob_get_contents();
		ob_end_clean();

		echo $content;
	}
}
